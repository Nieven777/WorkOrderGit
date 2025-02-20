<script setup>
import { router } from '@inertiajs/vue3';
// import { Link} from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3'
function logout() {
    router.post('/logout', {}, {
        onFinish: () => {
            router.visit('/login'); // Redirect to login page after logout
        }
    });
}



const showingNavigationDropdwn = ref(false);

const files = [
    'js/jquery-3.5.1.min.js',
    'js/jquery.dataTables.min.js',
    'js/bootstrap.bundle.min.js',
    'js/scripts.js',
    'js/all.min.js',
    '/s/feather.min.js',
    // '/assets/demo/Chart.min.js',
    // '/assets/demo/chart-area-demo.js',
    // '/assets/demo/chart-pie-demo.js',
    // '/assets/demo/datatables-demo.js',
    // '/js/feather.min.js',
    'css/styles.css',
    // '/css/dataTables.bootstrap4.min.css',

];


const loadFiles = () => {
    files.forEach((file) => {
        if (file.endsWith('.js')) {
            const script = document.createElement('script');
            script.file = file;
            script.async = true;
            document.head.appendChild(script);
        } else if (file.endsWith('.css')) {
            const link = document.createElement('link');
            link.href =file;
            link.rel = 'stylesheet';
            document.body.appendChild(link);
        }
    });
};




onMounted(() => {
    loadFiles();
});


</script>
 
<template>
    <body class="nav-fixed">
        <nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white" id="sidenavAccordion">
            <!-- Navbar Brand-->
            <!-- * * Tip * * You can use text or an image for your navbar brand.-->
            <!-- * * * * * * When using an image, we recommend the SVG format.-->
            <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
            <a class="navbar-brand" href="/home">ADMIN</a>
            <!-- Sidenav Toggle Button-->
            <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle"><i data-feather="menu"></i></button>
            <!-- Navbar Search Input-->
            <!-- * * Note: * * Visible only on and above the md breakpoint-->
            <form class="form-inline mr-auto d-none d-md-block mr-3">
                <div class="input-group input-group-joined input-group-solid">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" />
                    <div class="input-group-append">
                        <div class="input-group-text"><i data-feather="search"></i></div>
                    </div>
                </div>
            </form>
            <!-- Navbar Items-->
            <ul class=" align-items-center ml-auto">
                
                <!-- Navbar Search Dropdown-->
                <!-- * * Note: * * Visible only below the md breakpoint-->
                
                <!-- User Dropdown-->
                <li class="nav-item dropdown no-caret mr-3 mr-lg-0 dropdown-user">
                    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="img-fluid" src="/assets/img/illustrations/profiles/profile-1.png" /></a>
                    <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                        <h6 class="dropdown-header d-flex align-items-center">
                            <img class="dropdown-user-img" src="/assets/img/illustrations/profiles/profile-1.png" />
                            <div class="dropdown-user-details">
                                <div class="dropdown-user-details-name">Name here</div>
                                <div class="dropdown-user-details-email">email here</div>
                                <!-- <div class="dropdown-user-details-name">{{ Auth::user()->name}}</div>
                                <div class="dropdown-user-details-email">{{ Auth::user()->email}}</div> -->
                            </div>
                        </h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#!">
                            <div class="dropdown-item-icon"><i data-feather="settings"></i></div>
                            Account
                        </a>
                        <a class="dropdown-item" @click="logout">
                            <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sidenav shadow-right sidenav-light">
                    <div class="sidenav-menu">
                        <div class="nav accordion" id="accordionSidenav">
                            <!-- Sidenav Menu Heading (Core)-->
                            <div class="sidenav-menu-heading">Dashboard goes here</div>
                            <!-- Sidenav Accordion (Dashboard)-->
                            <a class="nav-link collapsed" href="/dashboard">
                            <div class="nav-link-icon"><i data-feather="activity"></i></div>
                             Dashboard
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="sidenav-menu-heading"></div>



                            <a class="nav-link collapsed" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
                                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                                Users
                                <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseDashboards" data-parent="#accordionSidenav">
                                <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPages">
                                    
                                    <a class="nav-link" href="dashboard-2.html">List</a>
                                    <a class="nav-link" href="dashboard-3.html">New</a>
                                </nav>
                            </div>



                            <div class="sidenav-menu-heading"></div>

                            <a class="nav-link collapsed" href="/dashboard">
                            <div class="nav-link-icon"><i data-feather="activity"></i></div>
                             Equipment
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="sidenav-menu-heading"></div>

                            <a class="nav-link collapsed" href="/dashboard">
                            <div class="nav-link-icon"><i data-feather="activity"></i></div>
                             Work Orders
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="sidenav-menu-heading"></div>

                            <a class="nav-link collapsed" href="/dashboard">
                            <div class="nav-link-icon"><i data-feather="activity"></i></div>
                             Profile
                            <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="sidenav-menu-heading"></div>


                        </div>
                    </div>
                    <!-- Sidenav Footer-->
                    <div class="sidenav-footer">
                        <div class="sidenav-footer-content">
                            <div class="sidenav-footer-subtitle">Logged in as:</div>
                            <div class="sidenav-footer-title">Admin</div>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                        <div class="container">
                            <div class="page-header-content pt-4">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mt-4">
                                        <h1 class="page-header-title">
                                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                                            Dashboard
                                        </h1>
                                        <!-- <div class="page-header-subtitle">Welcome, Admin!</div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- Main page content-->
                    <div class="container mt-n10">
                        <div class="row">
                            <div class="col-xl-4 mb-4">
                                <!-- Dashboard example card 1-->
                                <a class="card lift h-100" href="#!">
                                    <div class="card-body d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="mr-3">
                                                <i class="feather-xl text-primary mb-3" data-feather="package"></i>
                                                <h5>Work Reports</h5>
                                                <div class="text-muted small">Work reports decss</div>
                                            </div>
                                            <img src="  /assets/img/illustrations/browser-stats.svg" alt="..." style="width: 8rem" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <!-- Dashboard example card 2-->
                                <a class="card lift h-100" href="#!">
                                    <div class="card-body d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="mr-3">
                                                <i class="feather-xl text-secondary mb-3" data-feather="book"></i>
                                                <h5>Work Order</h5>
                                                <div class="text-muted small">Work order lorem ipsum</div>
                                            </div>
                                            <img src="/assets/img/illustrations/processing.svg" alt="..." style="width: 8rem" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <!-- Dashboard example card 3-->
                                <a class="card lift h-100" href="#!">
                                    <div class="card-body d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="mr-3">
                                                <i class="feather-xl text-green mb-3" data-feather="layout"></i>
                                                <h5>Profile</h5>
                                                <div class="text-muted small">Profile Description goes here</div>
                                            </div>
                                            <img src="/assets/img/illustrations/windows.svg" alt="..." style="width: 8rem" />
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-8">
                                <!-- Tabbed dashboard card example-->
                                
                                <!-- Illustration dashboard card example-->
                                <div class="card mb-4">
                                    <div class="card-body py-5">
                                        <div class="d-flex flex-column justify-content-center">
                                            <img class="img-fluid mb-4" src="/assets/img/illustrations/data-report.svg" alt="" style="height: 10rem" />
                                            <div class="text-center px-0 px-lg-5">
                                                <h5>New reports are here! Generate custom reports now!</h5>
                                                <p class="mb-4">Our new report generation system is now online. You can start creating custom reporting for any documents available on your account.</p>
                                                <a class="btn btn-primary p-3" href="#!">Get Started</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                
            </div>
        </div>
    </body>
  </template>
  