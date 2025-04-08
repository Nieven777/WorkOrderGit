<script setup>
import StaffNav from '@/Layouts/Staffnav/StaffNav.vue';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import StaffWorkOrderModal from '@/Components/StaffWorkOrderModal.vue'; // Adjust the path if necessary

// Configure Axios to include credentials and CSRF token (if needed)
axios.defaults.withCredentials = true;
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

const workOrders = ref([]);
const showModal = ref(false);
const selectedOrder = ref(null);

// ------------------------------
// Data Fetching & DataTable Setup
// ------------------------------
const fetchWorkOrders = async () => {
  try {
    const response = await axios.get('/api/staff-work-orders');
    // Filter to show only "Submitted" orders
    workOrders.value = response.data
      .filter(order => order.status === 'Submitted')
      .map(order => ({
        ...order,
        showFullDescription: false,
        editStatus: false,
      }));
    nextTick(() => {
      setTimeout(() => {
        initializeDataTable();
      }, 100);
    });
  } catch (error) {
    console.error("❌ Error fetching work orders:", error);
  }
};

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
    rowCallback: function (row, data, index) {
      $('td:eq(0)', row).html(index + 1);
    }
  });
  console.log("✅ DataTables initialized");
  if (window.feather) {
    feather.replace();
  } else {
    console.error("⚠️ Feather icons library is not yet loaded.");
  }
};

// ------------------------------
// Modal & Status Update Methods
// ------------------------------
const openModal = (order) => {
  selectedOrder.value = { ...order };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const acceptOrder = async (order) => {
  try {
    await axios.patch(`/api/work-orders/${order.id}`, { status: 'Received' });
    console.log("✅ Order Accepted");
    await fetchWorkOrders();
    closeModal();
  } catch (error) {
    console.error("❌ Error accepting order:", error.response ? error.response.data : error);
  }
};

// ------------------------------
// Dynamic Asset Loading
// ------------------------------
onMounted(() => {
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

  // Load JS files in proper order
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

  fetchWorkOrders();
});
</script>

<template>
  <body class="nav-fixed">
    <StaffNav />
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main>
          <!-- Work Orders Table -->
          <div class="container mt-4">
            <div class="card mb-4">
              <div class="card-header bg-primary text-white">Work Order Requests</div>
              <div class="card-body">
                <table class="table table-bordered table-hover" id="dataTable">
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
                        <span :class="{
                          'badge badge-primary': order.status === 'Submitted',
                          'badge badge-info': order.status === 'Received',
                          'badge badge-success': order.status === 'Completed',
                          'badge badge-danger': order.status === 'Canceled'
                        }">
                          {{ order.status }}
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-outline-info" @click="openModal(order)">
                          View
                        </button>
                      </td>
                    </tr>
                    <tr v-if="workOrders.length === 0">
                      <td colspan="8" class="text-center">No work orders found.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Use the StaffWorkOrderModal component -->
          <StaffWorkOrderModal
            v-if="showModal"
            :order="selectedOrder"
            @close="closeModal"
            @accept="acceptOrder"
          />
        </main>
      </div>
    </div>
  </body>
</template>

<style scoped>
/* Adjust modal size and reduce padding */
#dataTable_wrapper {
  transform: scale(0.8);
  transform-origin: top;
}

table th,
table td {
  font-size: 0.85rem; /* Slightly smaller font */
  padding: 0.5rem; /* Reduced padding */
}
.modal-header {
  background-color: #007bff;
}
.modal-title {
  font-weight: bold;
}
.form-group {
  margin-bottom: 1rem;
}
.badge {
  font-size: 14px;
  padding: 5px 10px;
}
button {
  cursor: pointer;
}
.btn-outline-info {
  color: #17a2b8;
  border-color: #17a2b8;
}
.btn-outline-info:hover {
  background-color: #17a2b8;
  color: #fff;
}
</style>
  