<script setup>
import AdminNav from '@/Layouts/Staffnav/StaffNav.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const workOrders = ref([]);

// Toggle the description display
const toggleDescription = (order) => {
  order.showFullDescription = !order.showFullDescription;
};

// Update work order status dynamically
const updateStatus = (order) => {
  axios.patch(`/api/work-orders/${order.id}`, { status: order.status })
    .then(response => {
      console.log("Status updated", response.data);
      // Optionally notify the admin (e.g., via a toast)
    })
    .catch(error => {
      console.error("Error updating status", error);
      // Optionally revert the status change or display an error message
    });
};

// Initialize DataTables with ordering based on a hidden created_at column
const initializeDataTable = () => {
    if (!window.jQuery || !$.fn.DataTable) {
        console.error("⚠️ jQuery or DataTables is not loaded yet.");
        return;
    }
    if (!$.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable({
            destroy: true,
            responsive: true,
            autoWidth: false,
            order: [[8, 'desc']], // Order by the hidden created_at column (zero-based index 8)
        });
        console.log("✅ DataTables initialized");
    }
    
    if (window.feather) {
        feather.replace();
    } else {
        console.error("⚠️ Feather icons library is not yet loaded.");
    }
};

onMounted(() => {
  // Functions to dynamically load CSS and JS files
  const loadCSS = (href) => {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = href;
      document.head.appendChild(link);
  };

  const loadScript = (src, callback) => {
      const script = document.createElement('script');
      script.src = src;
      script.defer = true;
      script.onload = callback || null;
      document.body.appendChild(script);
  }; 

  // Load CSS files
  loadCSS('/css/styles.css');
  loadCSS('/css/dataTables.bootstrap4.min.css');

  // Load JS files in order (jQuery must be loaded first)
  loadScript('/js/jquery-3.5.1.min.js', () => {
      console.log("✅ jQuery loaded");
      loadScript('/js/bootstrap.bundle.min.js');
      loadScript('/js/jquery.dataTables.min.js', () => {
          console.log("✅ DataTables loaded");
          loadScript('/js/dataTables.bootstrap4.min.js', () => {
              console.log("✅ DataTables Bootstrap loaded");
              initializeDataTable();
          });
      });
      loadScript('/js/all.min.js');
      loadScript('/js/feather.min.js', () => {
          console.log("✅ Feather icons loaded");
          feather.replace();
      });
      loadScript('/demo/datatables-demo.js');
      loadScript('/js/scripts.js');
  });

  // Fetch all work orders for admin
  // (Assuming your API endpoint for admin is '/api/admin-work-orders')
  axios.get('/api/admin-work-orders')
    .then(response => {
        // Map through orders and add a property for controlling description toggle
        workOrders.value = response.data.map(order => ({
           ...order,
           showFullDescription: false
        }));
    })
    .catch(error => {
       console.error("Error fetching work orders:", error);
    });
});
</script>

<template>
  <body class="nav-fixed">
    <StaffNav />
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main>
          <header class="pb-10">
            <div class="container">
              <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                  <div class="col-auto">
                    <h1>All Work Orders</h1>
                  </div>
                </div>
              </div>
            </div>
          </header>
          <!-- Main content -->
          <div class="container mt-n10">
            <div class="card mb-4">
              <div class="card-header">Work Order Requests</div>
              <div class="card-body">
                <div class="datatable">
                  <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Ticket Number</th>
                        <th>Requested By</th>
                        <th>Requisitioner Type</th>
                        <th>Concern</th>
                        <th>Description</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <!-- Hidden column for sorting -->
                        <th style="display:none;">Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="order in workOrders" :key="order.id">
                        <td>{{ order.ticket_number }}</td>
                        <td>{{ order.requested_by }}</td>
                        <td>{{ order.requisitioner_type }}</td>
                        <td>{{ order.concern }}</td>
                        <td>
                          <div>
                            <span>
                              <template v-if="!order.showFullDescription">
                                {{ order.description.length > 50 ? order.description.substring(0, 50) + '...' : order.description }}
                              </template>
                              <template v-else>
                                {{ order.description }}
                              </template>
                            </span>
                            <span v-if="order.description.length > 50">
                              <button class="btn btn-link btn-sm" @click="toggleDescription(order)">
                                {{ order.showFullDescription ? 'Show Less' : 'Show More' }}
                              </button>
                            </span>
                          </div>
                        </td>
                        <td>{{ order.date_requested }}</td>
                        <td>
                          <!-- The select element allows the admin to change status -->
                          <select v-model="order.status" @change="updateStatus(order)" class="form-control">
                            <option value="Submitted">Submitted</option>
                            <option value="Received">Received</option>
                            <option value="Completed">Completed</option>
                            <option value="Canceled">Canceled</option>
                          </select>
                        </td>
                        <td>
                          <!-- Additional actions (e.g., view details) can be added here -->
                          <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2">
                            <i data-feather="eye"></i>
                          </button>
                        </td>
                        <!-- Hidden created_at column for ordering -->
                        <td style="display:none;">{{ order.created_at }}</td>
                      </tr>
                      <tr v-if="workOrders.length === 0">
                        <td colspan="9" class="text-center">No work orders found.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </body>
</template>
