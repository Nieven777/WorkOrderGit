<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import AddUserModal from '@/Components/AddUserModal.vue';
import EditUserModal from '@/Components/EditUserModal.vue';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import feather from 'feather-icons';

const showModal = ref(false);
const modalType = ref(''); // Track whether to show Add or Edit modal
const users = ref([]);
const selectedUser = ref(null);

// Fetch users from the backend
const fetchUsers = async () => {
    try {
        const response = await axios.get('/admin/adminusertable');
        users.value = response.data;

        await nextTick(() => {
            initializeDataTable();
            feather.replace();
        });
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};

// Open Add User Modal
const openAddUserModal = () => {
    selectedUser.value = null; // Reset user selection
    modalType.value = 'add';
    showModal.value = true;
};

// Open Edit User Modal
const openEditUserModal = (user) => {
    selectedUser.value = { ...user }; // Copy user details
    modalType.value = 'edit';
    showModal.value = true;
};


// Close modal & refresh users
const closeModal = () => {
    showModal.value = false;
    fetchUsers(); // Refresh users after adding/editing
};

// Initialize DataTable
const initializeDataTable = () => {
  if (!window.jQuery || !$.fn.DataTable) {
    console.error("⚠️ jQuery or DataTables is not loaded yet.");
    return;
  }

  if ($.fn.DataTable.isDataTable('#dataTable')) {
    $('#dataTable').DataTable().destroy(); // Destroy existing table
  }

  $('#dataTable').DataTable({
    responsive: true,
    autoWidth: false,
    destroy: true, // Reset on reload
    order: [[1, 'asc']], // Default order by User column
    rowCallback: function(row, data, index) {
      // Automatically generate row numbers based on DataTable state
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






// Load users on component mount
onMounted(() => {
    fetchUsers(); 

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

    // Load CSS first
    loadCSS('/css/styles.css');
    loadCSS('/css/dataTables.bootstrap4.min.css');

    // Load jQuery first
    loadScript('/js/jquery-3.5.1.min.js', () => {
        console.log("✅ jQuery loaded");

        // Load Bootstrap and DataTables after jQuery
        loadScript('/js/bootstrap.bundle.min.js');
        loadScript('/js/jquery.dataTables.min.js', () => {
            console.log("✅ DataTables loaded");

            loadScript('/js/dataTables.bootstrap4.min.js', () => {
                console.log("✅ DataTables Bootstrap loaded");
                initializeDataTable(); // Initialize after loading
            });
        }); 

        // Load other scripts
        loadScript('/js/all.min.js');

        // Load Feather icons last
        loadScript('/js/feather.min.js', () => {
            console.log("✅ Feather icons loaded");
            feather.replace(); // Apply icons after loading
        });

        loadScript('/demo/datatables-demo.js');

        // Load scripts.js after everything else
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
          <header class="pb-10">
            <div class="container">
              <div class="page-header-content pt-4">
                <div class="row align-items-center justify-content-between"></div>
              </div>
            </div>
          </header>

          <div class="container mt-n10">
            <div class="card mb-4">
              <div class="card-header">
                User List
                <div class="d-flex justify-content-end">
                  <button class="btn btn-primary" type="button" data-toggle="modal" @click="openAddUserModal">Add User +</button>
                </div>
              </div>

              <div class="card-body">
                <div class="datatable">
                  <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>No.</th> <!-- New Column for Row Number -->
                        <th>User</th>
                        <th>ID No.</th>
                        <th>Role</th>
                        <th>College</th>
                        <th>Department</th>
                        <th>Institutional Email</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users" :key="user.id">
                        <td></td> <!-- Empty cell for row number -->
                        <td>{{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}</td>
                        <td>{{ user.user_id }}</td>
                        <td>{{ user.role }}</td>
                        <td>{{ user.college }}</td>
                        <td>{{ user.department }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2"
                                  title="edit"
                                  @click="openEditUserModal(user)">
                            <i data-feather="edit"></i>
                          </button>
                          <button class="btn btn-datatable btn-icon btn-transparent-dark"
                                  title="delete"
                                  @click="deleteUser(user.id)">
                            <i data-feather="trash-2"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>

                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Include the AddUserModal -->
          <AddUserModal v-if="modalType === 'add'" :showModal="showModal" @close="closeModal" />
          <EditUserModal v-if="modalType === 'edit'" :showModal="showModal" :user="selectedUser" @close="closeModal" />

        </main>
      </div>
    </div>
  </body>
</template>
