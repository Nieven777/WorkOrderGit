<script setup>
import EmployeeNav from '@/Layouts/EmployeeNav/EmployeeNav.vue';
import { ref, onMounted, computed, nextTick } from 'vue';
import axios from 'axios';

const workOrders = ref([]);
const loading = ref(true); // Loading state

const searchQuery = ref('');
const selectedDepartment = ref('');
const selectedStatus = ref('');

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
            order: [[8, 'desc']],
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

    // Load CSS files from public/
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
                nextTick(() => initializeDataTable());
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

    // Fetch current user's work orders
    axios.get('/api/my-work-orders')
        .then(response => {
            workOrders.value = response.data.map(order => ({
                ...order,
                showFullDescription: false
            }));
            nextTick(() => initializeDataTable());
        })
        .catch(error => {
            console.error("Error fetching work orders:", error);
        })
        .finally(() => {
            loading.value = false; // Hide loading screen once data is loaded
        });
});

const filteredWorkOrders = computed(() => {
    return workOrders.value.filter(order => {
        return (
            (selectedDepartment.value === '' || order.department === selectedDepartment.value) &&
            (selectedStatus.value === '' || order.status === selectedStatus.value) &&
            (searchQuery.value === '' || order.ticket_number.toLowerCase().includes(searchQuery.value.toLowerCase()))
        );
    });
});
</script>

<template>
    <body class="nav-fixed">
        <EmployeeNav />
        <div id="layoutSidenav">
            <div id="layoutSidenav_content">
                <main>
                    <!-- Loading Screen -->
                    <div v-if="loading" class="loading-screen">
                        <div class="spinner"></div>
                    </div>

                    <header class="pb-10">
                        <div class="container">
                            <div class="page-header-content pt-4">
                                <div class="row align-items-center justify-content-between"></div>
                            </div>
                        </div>
                    </header>
                    
                    <!-- Main page content -->
                    <div class="container mt-n10" v-if="!loading">
                        <div class="card mb-4">
                            <div class="card-header">
                                My Work Orders
                                <div class="d-flex justify-content-end">
                                    <a href="/employee/EmployeeRequestWork" class="btn btn-primary">
                                        Submit a Workorder +
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex mb-3">
                                    <input v-model="searchQuery" class="form-control mr-2" placeholder="Search Ticket Number" />
                                    <select v-model="selectedDepartment" class="form-control mr-2">
                                        <option value="">All Departments</option>
                                        <option v-for="department in [...new Set(workOrders.map(order => order.department))]" :key="department" :value="department">
                                            {{ department }}
                                        </option>
                                    </select>
                                    <select v-model="selectedStatus" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="Submitted">Submitted</option>
                                        <option value="Received">Received</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Canceled">Canceled</option>
                                    </select>
                                </div>

                                <div class="datatable">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Ticket Number</th>
                                                <th>Department</th>
                                                <th>Requisitioner Type</th>
                                                <th>Concern</th>
                                                <th>Date Requested</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                                <th style="display:none;">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="order in filteredWorkOrders" :key="order.id">
                                                <td>{{ order.ticket_number }}</td>
                                                <td>{{ order.department }}</td>
                                                <td>{{ order.requisitioner_type }}</td>
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
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2">
                                                        <i data-feather="eye"></i>
                                                    </button>
                                                </td>
                                                <td style="display:none;">{{ order.created_at }}</td>
                                            </tr>
                                            <tr v-if="filteredWorkOrders.length === 0">
                                                <td colspan="9" class="text-center">No work orders found.</td>
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