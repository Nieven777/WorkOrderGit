<script setup>
import { router } from '@inertiajs/vue3';

function logout() {
    router.post('/logout', {}, {
        onFinish: () => {
            router.visit('/login'); // Redirect to login page after logout
        }
    });
}
</script>

<template>
    <body class="nav-fixed">
        <nav class="topnav navbar navbar-expand shadow justify-content-between navbar-light bg-white" id="sidenavAccordion">
            <a class="navbar-brand" href="/home">ADMIN</a>
            <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 mr-lg-2" id="sidebarToggle">
                <i data-feather="menu"></i>
            </button>
            <form class="form-inline mr-auto d-none d-md-block mr-3">
                <div class="input-group input-group-joined input-group-solid">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" />
                    <div class="input-group-append">
                        <div class="input-group-text"><i data-feather="search"></i></div>
                    </div>
                </div>
            </form>
            <ul class="align-items-center ml-auto">
                <li class="nav-item dropdown no-caret mr-3 mr-lg-0 dropdown-user">
                    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage" href="javascript:void(0);" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="img-fluid" src="/assets/img/illustrations/profiles/profile-1.png" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right border-0 shadow animated--fade-in-up" aria-labelledby="navbarDropdownUserImage">
                        <h6 class="dropdown-header d-flex align-items-center">
                            <img class="dropdown-user-img" src="/assets/img/illustrations/profiles/profile-1.png" />
                            <div class="dropdown-user-details">
                                <div class="dropdown-user-details-name">Name here</div>
                                <div class="dropdown-user-details-email">email here</div>
                            </div>
                        </h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
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
                            <div class="sidenav-menu-heading">Dashboard</div>
                            <a class="nav-link" href="/dashboard">
                                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="/userlist">
                                <div class="nav-link-icon"><i data-feather="users"></i></div>
                                Users
                            </a>
                            <a class="nav-link" href="/admin/AdminEquipmentList">
                                <div class="nav-link-icon"><i data-feather="tool"></i></div>
                                Equipment
                            </a>
                            <a class="nav-link" href="/work-orders">
                                <div class="nav-link-icon"><i data-feather="clipboard"></i></div>
                                Work Orders
                            </a>
                            <a class="nav-link" href="/profile">
                                <div class="nav-link-icon"><i data-feather="user"></i></div>
                                Profile
                            </a>
                        </div>
                    </div>
                    <div class="sidenav-footer">
                        <div class="sidenav-footer-content">
                            <div class="sidenav-footer-subtitle">Logged in as:</div>
                            <div class="sidenav-footer-title">Admin</div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </body>
</template>
