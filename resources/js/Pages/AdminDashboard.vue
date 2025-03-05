<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Reactive variables for summary counts
const totalOrders = ref(0);
const inProgressOrders = ref(0);
const completedOrders = ref(0);
const cancelledOrders = ref(0);

// Reactive variable for today's work orders
const todaysOrders = ref([]);

// Fetch summary counts from the API
const fetchCounts = () => {
  axios.get('/api/admin-work-order-counts')
    .then(response => {
      totalOrders.value = response.data.total;
      inProgressOrders.value = response.data.inProgress;
      completedOrders.value = response.data.completed;
      cancelledOrders.value = response.data.cancelled;
    })
    .catch(error => {
      console.error('Error fetching work order counts:', error);
    });
};

// Fetch today's work orders from the API
const fetchTodaysOrders = () => {
  axios.get('/api/todays-work-orders')
    .then(response => {
      todaysOrders.value = response.data;
    })
    .catch(error => {
      console.error('Error fetching today\'s work orders:', error);
    });
};


// DataTables initialization (if you need it for other tables)
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
            order: [[8, 'desc']], // Order by hidden created_at column
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
    // Functions to dynamically load CSS and JS
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
    // (Optionally, load core DataTables CSS if needed)
    // loadCSS('/css/jquery.dataTables.min.css');

    // Load JS files in proper order (jQuery must load first)
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
        loadScript('/assets/demo/datatables-demo.js');
        loadScript('/js/scripts.js');
    });

    // Fetch dynamic dashboard data
    fetchCounts();
    fetchTodaysOrders();
});
</script>

<template>
  <body class="nav-fixed">
    <AdminNav />
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main>
          <!-- Header -->
          <header class="page-header pb-10">
            <div class="container">
              <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between">
                  <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                      <div class="page-header-icon">
                        <i data-feather="activity"></i>
                      </div>
                      Dashboard
                    </h1>
                  </div>
                </div>
              </div>
            </div>
          </header>

          <!-- Summary Cards -->
          <div class="container mt-n10">
            <div class="row">
              <!-- Total Work Orders Card -->
              <div class="col-xl-3 mb-4">
                <div class="card lift h-100 border-left-primary" style="border-left-width: 4px;">
                  <div class="card-body d-flex justify-content-center flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="mr-3">
                        <i class="feather-xl text-primary mb-3"></i>
                        <h5>Total Work Orders</h5>
                        <h3>{{ totalOrders }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- In-progress Card -->
              <div class="col-xl-3 mb-4">
                <div class="card lift h-100 border-left-secondary" style="border-left-width: 4px;">
                  <div class="card-body d-flex justify-content-center flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="mr-3">
                        <i class="feather-xl text-secondary mb-3"></i>
                        <h5>In-progress</h5>
                        <h3>{{ inProgressOrders }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Completed Card -->
              <div class="col-xl-3 mb-4">
                <div class="card lift h-100 border-left-success" style="border-left-width: 4px;">
                  <div class="card-body d-flex justify-content-center flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="mr-3">
                        <i class="feather-xl text-success mb-3"></i>
                        <h5>Completed</h5>
                        <h3>{{ completedOrders }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Cancelled Card -->
              <div class="col-xl-3 mb-4">
                <div class="card lift h-100 border-left-danger" style="border-left-width: 4px;">
                  <div class="card-body d-flex justify-content-center flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="mr-3">
                        <i class="feather-xl text-danger mb-3"></i>
                        <h5>Cancelled</h5>
                        <h3>{{ cancelledOrders }}</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>                            
            </div>

            <!-- Latest Work Orders Today -->
            <div class="card mb-4">
              <div class="card-header">Latest Work Orders Today</div>
              <div class="card-body">
                <div class="datatable table-responsive">
                  <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Ticket Number</th>
                        <th>Requested By</th>
                        <th>Requisitioner Type</th>
                        <th>Concern</th>
                        <th>Date Requested</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="order in todaysOrders" :key="order.id">
                        <td>{{ order.ticket_number }}</td>
                        <td>{{ order.requested_by }}</td>
                        <td>{{ order.requisitioner_type }}</td>
                        <td>{{ order.concern }}</td>
                        <td>{{ order.date_requested }}</td>
                        <td>
                          <span v-if="order.status === 'Submitted'" class="badge badge-primary">Submitted</span>
                          <span v-else-if="order.status === 'Received'" class="badge badge-info">Received</span>
                          <span v-else-if="order.status === 'Completed'" class="badge badge-success">Completed</span>
                          <span v-else-if="order.status === 'Canceled'" class="badge badge-danger">Canceled</span>
                        </td>
                      </tr>
                      <tr v-if="todaysOrders.length === 0">
                        <td colspan="6" class="text-center">No work orders for today.</td>
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
