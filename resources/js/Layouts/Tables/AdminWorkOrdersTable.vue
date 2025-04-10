<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import WorkOrderDetail from '@/Components/WorkOrderModal.vue'; // Import the new detailed view component

// Configure Axios to include credentials and CSRF token
axios.defaults.withCredentials = true;
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

// Reactive variables for work orders, detail view visibility, and filter values
const workOrders = ref([]);
const showDetailView = ref(false);
const selectedOrder = ref(null);
const isLoading = ref(false);

// Filter & search reactive variables
const searchQuery = ref('');
const selectedCollege = ref('');
const selectedDepartment = ref('');
const selectedStatus = ref('');

// Fetch work orders and then initialize the DataTable once data is rendered
const fetchWorkOrders = async () => {
  try {
    isLoading.value = true;
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
  } finally {
    isLoading.value = false;
  }
};

// DataTable instance reference
let dataTableInstance = null;

// DataTable initialization function
const initializeDataTable = () => {
  if (!window.jQuery || !$.fn.DataTable) {
    console.error("⚠️ jQuery or DataTables is not loaded yet.");
    return;
  }
  // Destroy any existing instance to avoid duplicates
  if ($.fn.DataTable.isDataTable('#dataTable')) {
    $('#dataTable').DataTable().destroy();
  }
  dataTableInstance = $('#dataTable').DataTable({
    responsive: true,
    autoWidth: false,
    order: [[5, 'desc']], // Date Requested column (adjust index if needed)
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

// Apply filters via DataTables API
const applyFilters = () => { 
  if (dataTableInstance) {
    // Global search on Ticket Number (assumed column index 1)
    dataTableInstance.column(1).search(searchQuery.value || '');

    // Column search: College/Unit (assumed column index 2)
    dataTableInstance.column(2).search(selectedCollege.value || '');

    // Column search: Department (assumed column index 3)
    dataTableInstance.column(3).search(selectedDepartment.value || '');

    // Column search: Status (assumed column index 7)
    dataTableInstance.column(6).search(selectedStatus.value || '');

    dataTableInstance.draw();
  }
};

// Reset filters and clear DataTables search
const resetFilters = () => {
  searchQuery.value = '';
  selectedCollege.value = '';
  selectedDepartment.value = '';
  selectedStatus.value = '';
  applyFilters();
};

// Open work order detail view and set the selected order
const openDetailView = (order) => {
  selectedOrder.value = { ...order };
  showDetailView.value = true;
};

// Close the detail view
const closeDetailView = () => { 
  showDetailView.value = false;
  selectedOrder.value = null;
};

// Work Order Status update handlers
const handleAccept = async (order) => {
  try {
    isLoading.value = true;
    await axios.patch(`/api/admin-work-orders/${order.id}`, { status: 'Received' });
    console.log("✅ Order Accepted");
    await fetchWorkOrders();
    closeDetailView();
  } catch (error) {
    console.error("❌ Error accepting order:", error.response ? error.response.data : error);
  } finally {
    isLoading.value = false;
  }
};

const handleDecline = async (order) => {
  try {
    isLoading.value = true;
    await axios.patch(`/api/admin-work-orders/${order.id}`, { status: 'Canceled' });
    console.log("✅ Order Declined");
    await fetchWorkOrders();
    closeDetailView();
  } catch (error) {
    console.error("❌ Error declining order:", error.response ? error.response.data : error);
  } finally {
    isLoading.value = false;
  }
};

const handleComplete = async (order) => {
  if (!order.completed_description) {
    alert('Please provide a description for the completed work.');
    return;
  }
  try {
    isLoading.value = true;
    await axios.patch(`/api/admin-work-orders/${order.id}`, {
      status: 'Completed',
      completed_description: order.completed_description
    });
    console.log("✅ Order Completed");
    calculateCategory(order);
    await fetchWorkOrders();
    closeDetailView();
  } catch (error) {
    console.error("❌ Error completing order:", error.response ? error.response.data : error);
  } finally {
    isLoading.value = false;
  }
};

const handleUpdate = (updatedOrder) => {
  // Update the order in the local state
  selectedOrder.value = { ...updatedOrder };
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

const handlePrint = () => {
  window.print();
};

// Dynamic asset loading: Load external CSS & JS files when the component is mounted
onMounted(() => {
  fetchWorkOrders();
  
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

  loadCSS('/css/styles.css');
  loadCSS('/css/dataTables.bootstrap4.min.css');

  loadScript('/js/jquery-3.5.1.min.js', () => {
    console.log("✅ jQuery loaded");
    loadScript('/js/bootstrap.bundle.min.js');
    loadScript('/js/jquery.dataTables.min.js', () => {
      console.log("✅ DataTables loaded");
      loadScript('/js/dataTables.bootstrap4.min.js', () => {
        console.log("✅ DataTables Bootstrap loaded");
        // Initialize DataTable when no filter has been applied yet
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
});
</script>

<template>
  <body class="nav-fixed">
    <AdminNav />
    <div id="layoutSidenav">
      <div id="layoutSidenav_content">
        <main>
          <!-- Show the main table view when detail view is not active -->
          <div v-if="!showDetailView" class="container mt-4">
            <div class="card mb-4">
              <div class="card-body">
                <!-- Filter and Search Controls -->
                <div class="d-flex mb-3">
                  <!-- Global search: Ticket Number -->
                  <input
                    v-model="searchQuery"
                    @input="applyFilters"
                    class="form-control mr-2"
                    placeholder="Search Ticket Number"
                  />
                  <!-- College filter -->
                  <select v-model="selectedCollege" @change="applyFilters" class="form-control mr-2">
                    <option value="">All Colleges</option>
                    <option v-for="college in [...new Set(workOrders.map(o => o.college))]" :key="college" :value="college">
                      {{ college }}
                    </option>
                  </select>
                  <!-- Department filter -->
                  <select v-model="selectedDepartment" @change="applyFilters" class="form-control mr-2">
                    <option value="">All Departments</option>
                    <option v-for="department in [...new Set(workOrders.map(o => o.department))]" :key="department" :value="department">
                      {{ department }}
                    </option>
                  </select>
                  <!-- Status filter -->
                  <select v-model="selectedStatus" @change="applyFilters" class="form-control mr-2">
                    <option value="">All Statuses</option>
                    <option v-for="status in ['Submitted', 'Received', 'Completed', 'Canceled']" :key="status" :value="status">
                      {{ status }}
                    </option>
                  </select>
                  <button class="btn btn-primary" @click="resetFilters">Reset</button>
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
                    <!-- Render all work orders; DataTables will hide rows that do not match the filters -->
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
                        <button class="btn btn-sm btn-outline-info" @click="openDetailView(order)">
                          View
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Show the detail view when a work order is selected -->
          <WorkOrderDetail 
            v-if="showDetailView" 
            :order="selectedOrder"
            :loading="isLoading"
            @close="closeDetailView"
            @accept="handleAccept"
            @decline="handleDecline"
            @complete="handleComplete"
            @update="handleUpdate"
            @print="handlePrint"
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

button {
  font-size: 0.85rem;
  padding: 0.5rem;
}

.modal-dialog {
  max-width: 80%;
}

.modal-content {
  padding: 1rem;
  font-size: 0.85rem;
}

select,
input {
  font-size: 0.85rem;
  padding: 0.5rem;
}

.form-control {
  max-width: 200px;
}
.modal-header {
  background-color: #61adff; /* Modal header background color */
}
.modal-title {
  font-weight: bold; /* Bold modal title */
}
.form-group {
  margin-bottom: 1rem; /* Spacing for form groups */
}
.badge {
  font-size: 14px; /* Badge text size */
  padding: 5px 10px; /* Badge padding */
}
button {
  cursor: pointer; /* Pointer cursor on buttons */
}
.btn-outline-info {
  color: #17a2b8; /* Button text color */
  border-color: #17a2b8; /* Button border color */
}
.btn-outline-info:hover {
  background-color: #17a2b8; /* Button background on hover */
  color: #fff; /* Button text color on hover */
}
/* Make all text darker */
body, table, th, td, input, select, button, .modal-title {
  color: #212529; /* Dark gray for better readability */
}

/* Ensure placeholders in input fields are also darker */
input::placeholder {
  color: #495057;
}

/* Print styles */
@media print {
  .no-print {
    display: none !important;
  }
  
  .print-only {
    display: block !important;
  }
}
</style>