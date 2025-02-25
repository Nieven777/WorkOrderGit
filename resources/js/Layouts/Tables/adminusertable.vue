<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import AddUserModal from '@/Components/AddUserModal.vue';
import { ref, onMounted, nextTick } from 'vue';
import axios from 'axios';
import feather from 'feather-icons';

const showModal = ref(false);
const users = ref([]);

// Fetch users from the backend
const fetchUsers = async () => {
    try {
        const response = await axios.get('/admin/adminusertable');
        users.value = response.data;

        // Wait for DOM to update, then initialize DataTables & feather icons
        await nextTick(() => {
            initializeDataTable();
            feather.replace();
        });
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};

// Open modal
const openModal = () => {
    showModal.value = true;
};

// Close modal & refresh users
const closeModal = () => {
    showModal.value = false;
    fetchUsers(); // Refresh users after adding
};

// Delete user
const deleteUser = async (id) => {
    if (confirm("Are you sure you want to delete this user?")) {
        try {
            await axios.delete(`/users/${id}`);
            fetchUsers(); // Refresh list after deletion
        } catch (error) {
            console.error("Error deleting user:", error);
        }
    }
};

const selectedUser = ref(null);

// Open Edit User Modal
const editUser = (user) => {
    selectedUser.value = { ...user }; // Copy user details
    showModal.value = true;
};




// Initialize DataTable
const initializeDataTable = () => {
    if (!window.jQuery || !$.fn.DataTable) {
        console.error("⚠️ jQuery or DataTables is not loaded yet.");
        return;
    }

    // Check if DataTable is already initialized to prevent duplicate initialization
    if (!$.fn.DataTable.isDataTable('#dataTable')) {
        $('#dataTable').DataTable({
            destroy: true, // Ensure it resets properly
            responsive: true,
            autoWidth: false,
        });
        console.log("✅ DataTables initialized");
    }
    
    // Ensure feather icons are applied after DataTable loads
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
                  <button class="btn btn-primary" type="button" @click="openModal">Add User +</button>
                </div>
              </div>

              <div class="card-body">
                <div class="datatable">
                  <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>User</th>
                        <th>ID Number</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Institutional Email</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users" :key="user.id">
                        <td>{{ user.first_name }} {{ user.middle_name }} {{ user.last_name }}</td>
                        <td>{{ user.user_id }}</td>
                        <td>{{ user.role }}</td>
                        <td>{{ user.department }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" @click="editUser(user)">
                            <i data-feather="edit"></i>
                            </button>
                            
                            <button class="btn btn-datatable btn-icon btn-transparent-dark" @click="deleteUser(user.id)">
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
          <AddUserModal :showModal="showModal" @close="closeModal" />
        </main>
      </div>
    </div>
  </body>
</template>
