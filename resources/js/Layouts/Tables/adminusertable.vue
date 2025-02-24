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
        const response = await axios.get('/users');
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

// Toggle User Status (Active/Inactive)
const toggleUserStatus = async (user) => {
    try {
        await axios.put(`/users/${user.id}/toggle-status`);
        fetchUsers(); // Refresh the user list
    } catch (error) {
        console.error("Error updating status:", error);
    }
};


// Initialize DataTable
const initializeDataTable = () => {
    const table = $('#dataTable').DataTable();
    table.destroy(); // Prevent multiple initializations
    $('#dataTable').DataTable();
};

// Load users on component mount
onMounted(() => {
    fetchUsers();

    // Dynamically load CSS & JS
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

    loadScript('/js/all.min.js');
    loadScript('/js/feather.min.js', () => feather.replace());
    loadScript('/js/jquery-3.5.1.min.js');
    loadScript('/js/bootstrap.bundle.min.js');
    loadScript('/js/jquery.dataTables.min.js');
    loadScript('/js/scripts.js');
    loadScript('/js/dataTables.bootstrap4.min.js');
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users" :key="user.id">
                        <td>{{ user.first_name }} {{ user.last_name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role }}</td>
                        <td>{{ user.department }}</td>
                        <td>
                            <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2" @click="editUser(user)">
                            <i data-feather="edit"></i>
                            </button>
                            <button class="btn btn-datatable btn-icon btn-transparent-dark" @click="toggleUserStatus(user)">
                            <i :class="user.status === 'Active' ? 'text-success' : 'text-danger'" data-feather="power"></i>
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
