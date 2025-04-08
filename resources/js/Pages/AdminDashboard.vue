<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import { ref, onMounted, nextTick, watch } from 'vue';
import axios from 'axios';

const totalOrders = ref(0);
const inProgressOrders = ref(0);
const completedOrders = ref(0);
const cancelledOrders = ref(0);
const todaysOrders = ref([]);
const monthlyWorkOrders = ref([]);
const recentActivities = ref([]);
const loading = ref(true);

// Charts
const pieChart = ref(null);
const barChart = ref(null);

// Fetch summary counts
const fetchCounts = async () => {
  try {
    const { data } = await axios.get('/api/admin-work-order-counts');
    totalOrders.value = data.total;
    inProgressOrders.value = data.inProgress;
    completedOrders.value = data.completed;
    cancelledOrders.value = data.cancelled;
  } catch (error) {
    console.error('Error fetching work order counts:', error);
  }
};

// Fetch today's work orders
const fetchTodaysOrders = async () => {
  try {
    const { data } = await axios.get('/api/todays-work-orders');
    todaysOrders.value = data;
  } catch (error) {
    console.error("Error fetching today's work orders:", error);
  } finally {
    loading.value = false;
    await nextTick();
    initializeDataTable();
  }
};

// Fetch bar chart data
const fetchMonthlyWorkOrders = async () => {
  try {
    const { data } = await axios.get('/api/monthly-work-orders');
    monthlyWorkOrders.value = data;
  } catch (error) {
    console.error("Error fetching monthly work orders:", error);
  }
};

// Fetch recent activity
const fetchRecentActivities = async () => {
  try {
    const { data } = await axios.get('/api/recent-activities');
    recentActivities.value = data;
  } catch (error) {
    console.error("Error fetching recent activities:", error);
  }
};

// Chart updates
const updatePieChart = () => {
  const ctx = document.getElementById('myPieChart');
  if (!ctx) return;
  if (pieChart.value) pieChart.value.destroy();

  pieChart.value = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['In-progress', 'Completed', 'Cancelled'],
      datasets: [{
        data: [inProgressOrders.value, completedOrders.value, cancelledOrders.value],
        backgroundColor: ['#007bff', '#28a745', '#dc3545'],
        hoverBackgroundColor: ['#5a6268', '#218838', '#c82333'],
      }],
    },
    options: {
      responsive: true,
      cutout: '70%',
    },
  });
};

const updateBarChart = () => {
  const ctx = document.getElementById('myBarChart');
  if (!ctx) return;
  if (barChart.value) barChart.value.destroy();

  barChart.value = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: monthlyWorkOrders.value.map(item => item.month),
      datasets: [{
        label: 'Work Orders',
        data: monthlyWorkOrders.value.map(item => item.count),
        backgroundColor: '#007bff',
      }],
    },
    options: {
      scales: {
        y: { beginAtZero: true },
      },
    },
  });
};

// DataTable
const initializeDataTable = () => {
  if (!window.jQuery || !$.fn.DataTable) {
    console.error("⚠️ jQuery or DataTables is not loaded.");
    return;
  }

  const table = $('#dataTable');
  if (!$.fn.DataTable.isDataTable(table)) {
    table.DataTable({
      destroy: true,
      responsive: true,
      autoWidth: false,
      order: [[4, 'desc']],
    });
  }

  if (window.feather) {
    window.feather.replace();
  }
};

// Load JS/CSS dynamically with corrected sequence
const loadAssets = () => {
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
    script.onload = callback;
    document.body.appendChild(script);
  };

  // Load CSS first
  loadCSS('/css/styles.css');
  loadCSS('/css/dataTables.bootstrap4.min.css');

  // Load JS in order, ensuring dependencies are met
  loadScript('/js/jquery-3.5.1.min.js', () => {
    loadScript('/js/bootstrap.bundle.min.js', () => {
      loadScript('/js/jquery.dataTables.min.js', () => {
        loadScript('/js/dataTables.bootstrap4.min.js', () => {
          // After loading DataTables and Bootstrap, initialize DataTable
          initializeDataTable();
        });
      });
    });

    loadScript('/js/all.min.js'); // Load font-awesome or other JS dependencies
    loadScript('/js/feather.min.js', () => {
      if (window.feather) window.feather.replace(); // Initialize feather icons
    });

    loadScript('/js/Chart.min.js', () => {
      // Initialize charts after Chart.js is loaded
      updatePieChart();
      updateBarChart();
    });

    // Load demo chart scripts
    loadScript('/demo/chart-area-demo.js');
    loadScript('/demo/chart-bar-demo.js');
    loadScript('/demo/chart-pie-demo.js');
  });
};

// On mount
onMounted(async () => {
  loadAssets();

  await fetchCounts();
  await fetchTodaysOrders();
  await fetchMonthlyWorkOrders();
  await fetchRecentActivities();

  await nextTick(() => {
    updatePieChart();
    updateBarChart();
  });
});
</script>


<template>
  <div class="nav-fixed">
    <AdminNav />
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main>
          <!-- Header -->
          <header class="page-header pb-10">
            <div class="container">
              <div class="page-header-content pt-2">
                <div class="row align-items-center justify-content-between">
                  <div class="col-auto mt-4">
                    <h3 class="page-header-title">
                      <div class="page-header-icon">
                        <i data-feather="activity"></i>
                      </div>
                     Admin Dashboard
                    </h3>
                  </div>
                </div>
              </div>
            </div>
          </header>

          <!-- ✅ Loading screen -->
          <div v-if="loading" class="loading-screen">
            <div class="spinner"></div>
          </div>

          <div class="container mt-n10" v-if="!loading">

          <!-- Quick Actions -->
            <div class="row mb-4">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">Quick Actions</div>
                  <div class="card-body d-flex flex-wrap gap-3">
                    <button class="btn btn-primary">Create Work Order</button>
                    <button class="btn btn-success">View All Orders</button>
                    <button class="btn btn-secondary">Manage Users</button>
                    <button class="btn btn-warning">Generate Reports</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Bar and Pie Charts -->
            <div class="row">
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header">Monthly Workorders</div>
                  <div class="card-body">
                    <div class="chart-bar">
                      <canvas id="myBarChart" width="100%" height="50"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card mb-4">
                  <div class="card-header">Workorders</div>
                  <div class="card-body">
                    <div class="chart-pie">
                      <canvas id="myPieChart" width="100%" height="50"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>



            <!-- Recent Activities -->
            <div class="row mb-4">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">Recent Activities</div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li v-for="(activity, index) in recentActivities" :key="index" class="list-group-item">
                        <strong>{{ activity.user }}</strong> {{ activity.action }} - <small class="text-muted">{{ activity.timestamp }}</small>
                      </li>
                      <li v-if="recentActivities.length === 0" class="list-group-item text-center text-muted">
                        No recent activities.
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <!-- Today's Work Orders -->
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
  </div>
</template>


<style scoped>
/* ✅ Loading screen */
.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.spinner {
  border: 6px solid #f3f3f3;
  border-top: 6px solid #007bff;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* ✅ Chart and card spacing */
.chart-bar, .chart-pie {
  padding: 10px;
}

/* ✅ Quick Actions responsiveness */
.card-body.d-flex {
  flex-wrap: wrap;
  gap: 1rem;
}

.card-body.d-flex button {
  flex: 1 1 200px;
}

/* ✅ Status badge styles */
.badge-submitted {
  background-color: #6c757d;
  color: white;
}

.badge-in-progress {
  background-color: #1c07ff;
  color: black;
}

.badge-completed {
  background-color: #28a745;
  color: white;
}

.badge-cancelled {
  background-color: #dc3545;
  color: white;
}

/* Optional: Hover effect for rows */
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
}
</style>
