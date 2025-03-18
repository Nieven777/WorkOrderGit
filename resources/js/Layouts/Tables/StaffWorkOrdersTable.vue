<script setup>
import StaffNav from '@/Layouts/Staffnav/StaffNav.vue';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';

const workOrders = ref([]);

// ✅ Toggle status edit dropdown for an order
const toggleEditStatus = (order) => {
  order.editStatus = !order.editStatus;
};

// ✅ Update work order status dynamically
const updateStatus = async (order) => {
  try {
    await axios.patch(`/api/work-orders/${order.id}`, { status: order.status });
    console.log("✅ Status updated");
    order.editStatus = false;
    await fetchWorkOrders(); // ✅ Refresh after updating
  } catch (error) {
    console.error("❌ Error updating status:", error);
  }
};

// ✅ Initialize DataTables
const initializeDataTable = () => {
  if (!window.jQuery || !$.fn.DataTable) {
    console.error("⚠️ jQuery or DataTables is not loaded yet.");
    return;
  }

  if ($.fn.DataTable.isDataTable('#dataTable')) {
    $('#dataTable').DataTable().destroy();
  }

  $('#dataTable').DataTable({
    responsive: true,
    autoWidth: false,
    order: [[7, 'desc']],
    rowCallback: function(row, data, index) {
      $('td:eq(0)', row).html(index + 1);
    }
  });

  console.log("✅ DataTables initialized");

  // ✅ Replace icons after DataTables loads
  if (window.feather) {
    feather.replace();
  }
};

// ✅ Fetch work orders and load into table
const fetchWorkOrders = async () => {
  try {
    const response = await axios.get('/api/staff-work-orders');

    workOrders.value = response.data
      .filter(order => order.status === 'Submitted')
      .map(order => ({
        ...order,
        showFullDescription: false,
        editStatus: false,
      }));

    console.log("✅ Work orders fetched:", workOrders.value);

    // ✅ Wait for DOM update before initializing DataTable
    nextTick(() => {
      initializeDataTable();
    });
  } catch (error) {
    console.error("❌ Error fetching work orders:", error);
  }
};

// ✅ Ensure DataTables are ready after fetching
onMounted(() => {
  // Load CSS files dynamically
  const loadCSS = (href) => {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
  };

  // Load JavaScript files dynamically
  const loadScript = (src, callback) => {
    const script = document.createElement('script');
    script.src = src;
    script.defer = true;
    script.onload = callback || null;
    document.body.appendChild(script);
  };

  // ✅ Load CSS files
  loadCSS('/css/styles.css');
  loadCSS('/css/dataTables.bootstrap4.min.css');

  // ✅ Load jQuery first
  loadScript('/js/jquery-3.5.1.min.js', () => {
    console.log("✅ jQuery loaded");

    // ✅ Load Bootstrap and DataTables after jQuery
    loadScript('/js/bootstrap.bundle.min.js');
    loadScript('/js/jquery.dataTables.min.js', () => {
      console.log("✅ DataTables loaded");

      loadScript('/js/dataTables.bootstrap4.min.js', () => {
        console.log("✅ DataTables Bootstrap loaded");
        initializeDataTable();
      });
    });

    // ✅ Load other necessary scripts
    loadScript('/js/all.min.js');

    // ✅ Load Feather icons last
    loadScript('/js/feather.min.js', () => {
      console.log("✅ Feather icons loaded");
      feather.replace(); // Apply icons after loading
    });

    // ✅ Load DataTables demo and custom scripts
    loadScript('/demo/datatables-demo.js');
    loadScript('/js/scripts.js');
  });
  
  fetchWorkOrders();
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
                  <div class="col-auto"></div>
                </div>
              </div>
            </div>
          </header>

          <!-- ✅ Main Content -->
          <div class="container mt-n10">
            <div class="card mb-4">
              <div class="card-header">Work Order Requests</div>
              <div class="card-body">
                <div class="datatable">
                  <table class="table table-bordered table-hover"
                        id="dataTable"
                        style="margin: auto;"
                        cellspacing="0">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Ticket Number</th>
                        <th>College/Unit</th>
                        <th>Department</th>
                        <th>Concern</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                        <th>Actions</th>
                        <th style="display:none;">Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(order, index) in workOrders" :key="order.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ order.ticket_number }}</td>
                        <td>{{ order.college }}</td>
                        <td>{{ order.department }}</td>
                        <td>{{ order.concern }}</td>
                        <td>{{ order.date_requested }}</td>
                        <td>
                          <span v-if="order.status === 'Submitted'" class="badge badge-primary">Submitted</span>
                          <span v-else-if="order.status === 'Received'" class="badge badge-info">In-progress</span>
                          <span v-else-if="order.status === 'Completed'" class="badge badge-success">Completed</span>
                          <span v-else-if="order.status === 'Canceled'" class="badge badge-danger">Canceled</span>
                        </td>
                        <td>
                          <div class="d-flex justify-content-center">
                            <!-- ✅ View Button -->
                            <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                    title="View"
                                    @click="toggleEditStatus(order)">
                              <i data-feather="eye"></i>
                            </button>
                            <!-- ✅ Check Button -->
                            <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                    title="Confirm"
                                    @click="toggleEditStatus(order)">
                              <i data-feather="check"></i>
                            </button>
                          </div>
                          <div v-if="order.editStatus" style="margin-top: 5px;">
                            <select v-model="order.status"
                                    @change="updateStatus(order)"
                                    class="form-control">
                              <option value="Submitted">Submitted</option>
                              <option value="Received">Accept</option>
                              <option value="Completed">Complete</option>
                              <option value="Canceled">Cancel</option>
                            </select>
                          </div>
                        </td>
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
