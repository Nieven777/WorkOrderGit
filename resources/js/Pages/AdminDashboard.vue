<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import { onMounted } from 'vue';

onMounted(() => {
    // Function to dynamically load CSS
    const loadCSS = (href) => {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;
        document.head.appendChild(link);
    };

    // Function to dynamically load JS
    const loadScript = (src) => {
        const script = document.createElement('script');
        script.src = src;
        script.defer = true;
        document.body.appendChild(script);
    };

    // Load CSS files from public/
    loadCSS('/css/styles.css');
    loadCSS('/css/dataTables.bootstrap4.min.css');

    // Load JS files from public/
    loadScript('/js/all.min.js');
    loadScript('/js/feather.min.js');
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
                    <header class="page-header  pb-10">
                        <div class="container">
                            <div class="page-header-content pt-4">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mt-4">
                                        <h1 class="page-header-title">
                                            <div class="page-header-icon"><i data-feather="activity"></i></div>
                                            Dashboard
                                        </h1>
                                        <div class="page-header-subtitle">Welcome, Admin!</div>
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
                                                <h5>Work Report</h5>
                                                <!-- <div class="text-muted small">Work reports description goes here</div> -->
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
                                                <h5>Profile</h5>
                                                <!-- <div class="text-muted small">Edit profile</div> -->
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
                                                <h5>Work Order</h5>
                                                <!-- <div class="text-muted small">Work order hereeeeee.</div> -->
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
                                                <!-- <p class="mb-4">Our new report generation system is now online. You can start creating custom reporting for any documents available on your account.</p> -->
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
  
