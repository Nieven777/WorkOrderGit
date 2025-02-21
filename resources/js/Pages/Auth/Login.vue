<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post('/login');
}; 

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
    

    // Load JS files from public/
    loadScript('/js/all.min.js');
    loadScript('/js/feather.min.js');
    loadScript('/js/scripts.js');

});
</script>

<template>
  <div>
    <div id="layoutAuthentication">
      <div id="layoutAuthentication_content">
        <main>
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                  <div class="card-header text-center">
                    <h3 class="font-weight-light my-4">Login</h3>
                  </div>
                  <div class="card-body">
                    <form @submit.prevent="submit">
                      <div class="form-group">
                        <label class="small mb-1" for="inputEmailAddress">Email</label>
                        <input
                          class="form-control"
                          id="inputEmailAddress"
                          placeholder="Enter email address"
                          v-model="form.email"
                          type="email"
                          required
                        />
                      </div>
                      <div class="form-group">
                        <label class="small mb-1" for="inputPassword">Password</label>
                        <input
                          class="form-control"
                          id="inputPassword"
                          placeholder="Enter password"
                          v-model="form.password"
                          type="password"
                          required
                        />
                      </div>
                      <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                        <button class="btn btn-primary" type="submit" :disabled="form.processing">
                          Login
                        </button>
                      </div>
                    </form>
                  </div>
                  <div class="card-footer text-center">
                    <div class="small">
                      <a href="/register">Need an account? Sign up!</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<!-- Include assets in Laravel layout -->
