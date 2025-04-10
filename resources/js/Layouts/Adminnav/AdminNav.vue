<script setup>
import { router } from '@inertiajs/vue3';
import { ref, onMounted, nextTick } from 'vue';

const dropdownVisible = ref(false);
const workOrderVisible = ref(false);

function logout() {
    router.post('/logout', {}, {
        onFinish: () => {
            router.visit('/login');
        }
    });
}

// Toggle user dropdown state
const toggleDropdown = () => {
    dropdownVisible.value = !dropdownVisible.value;
};

// Toggle work order dropdown state
const toggleWorkOrders = () => {
    workOrderVisible.value = !workOrderVisible.value;
    $('#collapseWorkOrders').collapse(workOrderVisible.value ? 'show' : 'hide');
};

onMounted(() => {
    const loadScript = (src, callback) => {
        const script = document.createElement('script');
        script.src = src;
        script.defer = true;
        script.onload = callback || null;
        document.body.appendChild(script);
    }; 

    // Load jQuery first
    loadScript('/js/jquery-3.5.1.min.js', () => {
        console.log('✅ jQuery loaded');

        // Load Bootstrap JS after jQuery
        loadScript('/js/bootstrap.bundle.min.js', () => {
            console.log('✅ Bootstrap loaded');

            nextTick(() => {
                // Initialize feather icons after Bootstrap loads
                loadScript('/js/feather.min.js', () => {
                    console.log('✅ Feather loaded');
                    feather.replace();
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (event) => {
                    if (!event.target.closest('.dropdown-user')) {
                        dropdownVisible.value = false;
                    }
                });
            });
        });
    });
});
</script>

<template>
    <!-- Disable page scrolling by setting overflow to hidden on the body -->
    <body class="nav-fixed" style="overflow: hidden;">
        <!-- Top Navigation Bar -->
        <nav class="topnav navbar navbar-expand shadow justify-content-between navbar-light bg-white" id="sidenavAccordion">
            <a class="navbar-brand" href="/home">Work Order Monitoring</a>
            <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle">
                <i data-feather="menu"></i>
            </button>

            <!-- Search Bar -->
            <form class="form-inline mr-auto d-none d-md-block mr-3">
                <div class="input-group input-group-joined input-group-solid">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" />
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i data-feather="search"></i>
                        </div>
                    </div>
                </div>
            </form>

            <!-- User Profile Dropdown -->
            <ul class="align-items-center ml-auto">
                <li class="nav-item dropdown no-caret mr-3 mr-lg-0 dropdown-user">
                    <a 
                        class="btn btn-icon btn-transparent-dark dropdown-toggle" 
                        @click="toggleDropdown"
                        :class="{ 'show': dropdownVisible }"
                    >
                        <img class="img-fluid" src="/assets/img/illustrations/profiles/profile-1.png" />
                    </a>
                    <div 
                        class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up"
                        :class="{ 'show': dropdownVisible }"
                    >
                        <h6 class="dropdown-header d-flex align-items-center">
                            <img class="dropdown-user-img" src="/assets/img/illustrations/profiles/profile-1.png" />
                            <div class="dropdown-user-details">
                                <div class="dropdown-user-details-name">John Doe</div>
                                <div class="dropdown-user-details-email">johndoe@example.com</div>
                            </div>
                        </h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                            Profile
                        </a>
                        <a class="dropdown-item" @click="logout">
                            <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Sidebar and Main Layout -->
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sidenav shadow-right sidenav-light">
                    <div class="sidenav-menu">
                        <div class="nav accordion" id="accordionSidenav">
                            <!-- Dashboard -->
                            <a class="nav-link" href="/dashboard">
                                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                                Dashboard
                            </a>

                            <!-- Work Orders -->
                            <a 
                                class="nav-link collapsed"
                                href="javascript:void(0);"
                                @click="toggleWorkOrders"
                                :aria-expanded="workOrderVisible"
                            >
                                <div class="nav-link-icon"><i data-feather="clipboard"></i></div>
                                Work Orders
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseWorkOrders">
                                <nav class="sidenav-menu-nested nav">
                                    <a class="nav-link" href="/admin/AdminWorkOrderList">Work Order List</a>
                                    <a class="nav-link" href="/admin/AdminRequestWork">Add Work Order</a>
                                </nav>
                            </div>

                            <!-- Equipment -->
                            <a class="nav-link" href="/admin/AdminEquipmentList">
                                <div class="nav-link-icon"><i data-feather="tool"></i></div>
                                Equipment
                            </a>

                            <!-- Users -->
                            <a class="nav-link" href="/admin/adminuserlist">
                                <div class="nav-link-icon"><i data-feather="users"></i></div>
                                Users
                            </a>

                            <!-- Profile -->
                            <a class="nav-link" href="/profile">
                                <div class="nav-link-icon"><i data-feather="user"></i></div>
                                Profile
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </body>
</template>

<style scoped>
/* Fix dropdown visibility */
.dropdown-menu {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
}

.show {
    opacity: 1;
    visibility: visible;
}

/* Adjust dropdown alignment */
.sidenav-collapse-arrow {
    margin-left: auto;
}

/* Fix nested menu padding */
.sidenav-menu-nested .nav-link {
    padding-left: 2.5rem;
}
</style>
