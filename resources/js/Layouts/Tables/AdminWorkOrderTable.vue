<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const workOrders = ref([]); 
const toggleDescription = (order) => {
  order.showFullDescription = !order.showFullDescription;
};

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
            order: [[8, 'desc']], // Order by the hidden created_at column (index 8)
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
                initializeDataTable(); // Initialize after loading
            });
        });

        // Load other scripts
        loadScript('/js/all.min.js');

        // Load Feather icons last
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
         })
         .catch(error => {
            console.error("Error fetching work orders:", error);
         });
});
</script>
<template>
    <template>
    <body class="nav-fixed">
        <AdminNav />
        <div id="layoutSidenav">
            <div id="layoutSidenav_content">
                <main>
                    <header class="pb-10">
                        <div class="container">
                            <div class="page-header-content pt-4">
                                <div class="row align-items-center justify-content-between">

                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container mt-n10">
                        <div class="card mb-4">
                            <div class="card-header">My Work Orders</div>
                            <div class="card-body">
                                <div class="datatable">
                                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Ticket Number</th>
                                                <th>Requested By</th>
                                                <th>Requisitioner Type</th>
                                                <th>Concern</th>
                                                <th>Description</th>
                                                <th>Date Requested</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                                <!-- Hidden column for sorting -->
                                                <th style="display:none;">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="order in workOrders" :key="order.id">
                                                <td>{{ order.ticket_number }}</td>
                                                <td>{{ order.requested_by }}</td>
                                                <td>{{ order.requisitioner_type }}</td>
                                                <td>{{ order.concern }}</td>
                                                <td>
                                                    <div>
                                                        <span>
                                                            <template v-if="!order.showFullDescription">
                                                                {{ order.description.length > 20 ? order.description.substring(0, 20) + '...' : order.description }}
                                                            </template>
                                                            <template v-else>
                                                                {{ order.description }}
                                                            </template>
                                                        </span>
                                                        <span v-if="order.description.length > 20">
                                                            <button class="btn btn-link btn-sm" @click="toggleDescription(order)">
                                                                {{ order.showFullDescription ? 'Show Less' : 'Show More' }}
                                                            </button>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>{{ order.date_requested }}</td>
                                                <td>
                                                    <span v-if="order.status === 'Submitted'" class="badge badge-primary">Submitted</span>
                                                    <span v-else-if="order.status === 'Received'" class="badge badge-info">Received</span>
                                                    <span v-else-if="order.status === 'Completed'" class="badge badge-success">Completed</span>
                                                    <span v-else-if="order.status === 'Canceled'" class="badge badge-danger">Canceled</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-datatable btn-icon btn-transparent-dark mr-2">
                                                        <i data-feather="eye"></i>
                                                    </button>
                                                    <!-- Additional actions can be added here -->
                                                </td>
                                                <!-- Hidden created_at column for sorting -->
                                                <td style="display:none;">{{ order.created_at }}</td>
                                            </tr>
                                            <tr v-if="workOrders.length === 0">
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
</template>