import { createRouter, createWebHistory } from 'vue-router';
import AdminDashboard from '@/Pages/AdminDashboard.vue';
import StaffDashboard from '@/Pages/StaffDashboard.vue';
import EmployeeDashboard from '@/Pages/EmployeeDashboard.vue';
import Login from '@/Pages/Auth/Login.vue';

const routes = [
  { path: '/admin/dashboard', component: AdminDashboard },
  { path: '/staff/dashboard', component: StaffDashboard },
  { path: '/employee/dashboard', component: EmployeeDashboard },
  { path: '/', component: Login },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
 
export default router;
