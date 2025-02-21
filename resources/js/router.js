import { createRouter, createWebHistory } from 'vue-router';
import AdminDashboard from '@/Pages/AdminDashboard.vue';
import StaffDashboard from '@/Pages/StaffDashboard.vue';
import EmployeeDashboard from '@/Pages/EmployeeDashboard.vue';
import adminuserlist from '@/Pages/admin/adminuserlist.vue';
import AdminEquipmentList from '@/Pages/admin/AdminEquipmentList.vue';
import Login from '@/Pages/Auth/Login.vue';

const routes = [

  // DASHBOARD ROUTES HEREE
  { path: '/admin/dashboard', component: AdminDashboard, meta: { role: 'admin' } },
  { path: '/staff/dashboard', component: StaffDashboard, meta: { role: 'staff' } },
  { path: '/employee/dashboard', component: EmployeeDashboard, meta: { role: 'employee' } },
  { path: '/', component: Login },

  // ADMIN ROUTES HERE
  { path: '/admin/adminuserlist', component: adminuserlist, meta: { role: 'admin' } },
  { path: '/admin/AdminEquipmentList', component: AdminEquipmentList, meta: { role: 'admin' } },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guard
router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem('user')); // Assuming you store user data in localStorage

  if (to.meta.role) {
    if (!user || user.role !== to.meta.role) {
      return next('/'); // Redirect unauthorized users to login
    }
  }
  next();
});

export default router;
