<script setup>
import StaffNav from '@/Layouts/Staffnav/StaffNav.vue';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import StaffReceivedWorkOrderModal from '@/Components/StaffReceivedWorkOrderModal.vue'; // Path as needed

const workOrders = ref([]);
const showModal = ref(false);
const selectedOrder = ref(null);

// Fetch only work orders with status "Received"
const fetchWorkOrders = async () => {
  try {
    const response = await axios.get('/api/staff-received-work-orders');

    // Filter orders that are "Received" (i.e., accepted but not completed)
    workOrders.value = response.data
      .filter(order => order.status === 'Received')
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
    order: [[6, 'desc']],
    rowCallback: function (row, data, index) {
      $('td:eq(0)', row).html(index + 1);
    }
  });
  console.log("✅ DataTables initialized");
  if (window.feather) {
    feather.replace();
  }
};

const openModal = (order) => {
  selectedOrder.value = { ...order };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const completeOrder = async (order) => {
  // Here we update the order to "Completed" with the completed description and category.
  try {
    await axios.patch(`/api/work-orders/${order.id}`, {
      status: 'Completed',
      completed_description: order.completed_description,
      category: order.category
    });
    console.log("✅ Order Completed");
    await fetchWorkOrders(); // Refresh the list; this order should now disappear.
    closeModal();
  } catch (error) {
    console.error("❌ Error completing order:", error.response ? error.response.data : error);
  }
};

// Dynamic asset loading (similar to previous pages)
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
  loadCSS('/css/styles.css');
  loadCSS('/css/dataTables.bootstrap4.min.css');

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
              <div class="card-header bg-primary text-white">Received Work Orders</div>
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
          <!-- Use the StaffReceivedWorkOrderModal component -->
          <StaffReceivedWorkOrderModal
            v-if="showModal"
            :order="selectedOrder"
            @close="closeModal"
            @complete="completeOrder"
            @print="$emit('print')"
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
