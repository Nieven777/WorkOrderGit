
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
const loading = ref(true); // ✅ Loading state

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
      console.error("Error fetching today's work orders:", error);
    })
    .finally(() => {
      loading.value = false; // ✅ Disable loading state after data is fetched
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
      order: [[4, 'desc']], // ✅ Order by the hidden created_at column (index 4)
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

  // ✅ Load CSS first
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
        initializeDataTable(); // ✅ Initialize after loading
      });
    });

    // ✅ Load other scripts
    loadScript('/js/all.min.js');

    // ✅ Load Feather icons last
    loadScript('/js/feather.min.js', () => {
      console.log("✅ Feather icons loaded");
      feather.replace(); // ✅ Apply icons after loading
    });

    loadScript('/demo/datatables-demo.js');
    loadScript('/js/scripts.js');
  });

  // ✅ Fetch dynamic dashboard data
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

          <!-- ✅ Loading screen -->
          <div v-if="loading" class="loading-screen">
            <div class="spinner"></div>
          </div>

          <!-- Summary Cards -->
          <div class="container mt-n10" v-if="!loading">
            <div class="row">
              <!-- Total Work Orders -->
              <div class="col-xl-3 mb-4">
                <div class="card lift h-100 border-left-primary thick-border">
                  <div class="card-body">
                    <h5>Total Work Orders</h5>
                    <h3>{{ totalOrders }}</h3>
                  </div>
                </div>
              </div>
              <!-- In-progress Orders -->
              <div class="col-xl-3 mb-4">
                <div class="card lift h-100 border-left-secondary thick-border">
                  <div class="card-body">
                    <h5>In-progress</h5>
                    <h3>{{ inProgressOrders }}</h3>
                  </div>
                </div>
              </div>
              <!-- Completed Orders -->
              <div class="col-xl-3 mb-4">
                <div class="card lift h-100 border-left-success thick-border">
                  <div class="card-body">
                    <h5>Completed</h5>
                    <h3>{{ completedOrders }}</h3>
                  </div>
                </div>
              </div>
              <!-- Cancelled Orders -->
              <div class="col-xl-3 mb-4">
                <div class="card lift h-100 border-left-danger thick-border">
                  <div class="card-body">
                    <h5>Cancelled</h5>
                    <h3>{{ cancelledOrders }}</h3>
                  </div>
                </div>
              </div>
            </div>

            <!-- Latest Work Orders -->
            <div class="card mb-4">
              <div class="card-header">Latest Work Orders Today</div>
              <div class="card-body">
                <div class="datatable table-responsive">
                  <table class="table table-bordered table-hover" id="dataTable" cellspacing="0">
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
                          <span :class="`badge badge-${order.status.toLowerCase()}`">{{ order.status }}</span>
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

<style>
/* ✅ Loading screen styling */
.loading-screen {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
.spinner {
  border: 4px solid #ccc;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

/* ✅ Thicker Border */
.thick-border {
  border-left-width: 7px !important;
}
</style>
