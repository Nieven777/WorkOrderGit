<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import { ref, onMounted, nextTick, computed } from 'vue';
import axios from 'axios';
import WorkOrderModal from '@/Components/WorkOrderModal.vue'; // Adjust the path if necessary

// Configure Axios to include credentials and CSRF token
axios.defaults.withCredentials = true;
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

const workOrders = ref([]);
const showModal = ref(false);
const selectedOrder = ref(null);

// Filter variables
const selectedCollege = ref('');
const selectedDepartment = ref('');
const selectedStatus = ref('');

// Fetch work orders
const fetchWorkOrders = async () => {
  try {
    const response = await axios.get('/api/admin-work-orders');
    workOrders.value = response.data.map(order => ({
      ...order,
      showFullDescription: false,
      completed_description: order.completed_description || '',
      category: order.category || 'N/A'
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

// Computed properties for filtering
const filteredWorkOrders = computed(() => {
  return workOrders.value.filter(order => {
    return (
      (selectedCollege.value === '' || order.college === selectedCollege.value) &&
      (selectedDepartment.value === '' || order.department === selectedDepartment.value) &&
      (selectedStatus.value === '' || order.status === selectedStatus.value)
    );
  });
});

// Extract unique values for dropdowns
const uniqueColleges = computed(() => [...new Set(workOrders.value.map(order => order.college))]);
const uniqueDepartments = computed(() => [...new Set(workOrders.value.map(order => order.department))]);
const uniqueStatuses = ['Submitted', 'Received', 'Completed', 'Canceled'];

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
    order: [[5, 'desc']], // Assuming Date Requested is at index 5
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

// Modal & Status Update Methods
const openModal = (order) => {
  selectedOrder.value = { ...order };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const acceptOrder = async (order) => {
  try {
    await axios.patch(`/api/admin-work-orders/${order.id}`, { status: 'Received' });
    console.log("✅ Order Accepted");
    await fetchWorkOrders();
    closeModal();
  } catch (error) {
    console.error("❌ Error accepting order:", error.response ? error.response.data : error);
  }
};

const declineOrder = async (order) => {
  try {
    await axios.patch(`/api/admin-work-orders/${order.id}`, { status: 'Canceled' });
    console.log("✅ Order Declined");
    await fetchWorkOrders();
    closeModal();
  } catch (error) {
    console.error("❌ Error declining order:", error.response ? error.response.data : error);
  }
};

const completeOrder = async (order) => {
  if (!order.completed_description) {
    alert('Please provide a description for the completed work.');
    return;
  }
  try {
    await axios.patch(`/api/admin-work-orders/${order.id}`, {
      status: 'Completed',
      completed_description: order.completed_description
    });
    console.log("✅ Order Completed");
    calculateCategory(order);
    await fetchWorkOrders();
    closeModal();
  } catch (error) {
    console.error("❌ Error completing order:", error.response ? error.response.data : error);
  }
};

const calculateCategory = (order) => {
  const daysDiff = Math.ceil(
    (new Date() - new Date(order.date_requested)) / (1000 * 60 * 60 * 24)
  );
  if (daysDiff <= 1) {
    order.category = 'Category I (Finished within one working day)';
  } else if (daysDiff <= 3) {
    order.category = 'Category II (1-3 working days)';
  } else if (daysDiff <= 7) {
    order.category = 'Category III (4-7 working days)';
  } else {
    order.category = 'Category IV (7+ working days)';
  }
};

const printOrder = () => {
  window.print();
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
    <AdminNav />
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main>
          <div class="container mt-4">
            <div class="card mb-4">
              <div class="card-header bg-primary text-white">Work Orders</div>
              <div class="card-body">
                
                <!-- Filters -->
                <div class="d-flex mb-3">
                  <select v-model="selectedCollege" class="form-control mr-2">
                    <option value="">All Colleges</option>
                    <option v-for="college in uniqueColleges" :key="college" :value="college">
                      {{ college }}
                    </option>
                  </select>

                  <select v-model="selectedDepartment" class="form-control mr-2">
                    <option value="">All Departments</option>
                    <option v-for="department in uniqueDepartments" :key="department" :value="department">
                      {{ department }}
                    </option>
                  </select>

                  <select v-model="selectedStatus" class="form-control">
                    <option value="">All Statuses</option>
                    <option v-for="status in uniqueStatuses" :key="status" :value="status">
                      {{ status }}
                    </option>
                  </select>
                </div>

                <!-- Work Orders Table -->
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
                    <tr v-for="(order, index) in filteredWorkOrders" :key="order.id">
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
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Work Order Modal -->
          <WorkOrderModal
            v-if="showModal"
            :order="selectedOrder"
            @close="closeModal"
            @accept="acceptOrder"
            @decline="declineOrder"
            @complete="completeOrder"
            @print="printOrder"
          />
        </main>
      </div>
    </div>
  </body>
</template>

<style scoped>
/* Modal Header styling */
.modal-header {
  background-color: #007bff; /* Primary color */
}
.modal-title {
  font-weight: bold;
}
/* Form group spacing */
.form-group {
  margin-bottom: 1rem;
}
/* Badge styling */
.badge {
  font-size: 14px;
  padding: 5px 10px;
}
/* Button styling */
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
