<template>
    <body class="nav-fixed">
      <EmployeeNav />
      <div id="layoutSidenav" style="width: 95%;">
        <div id="layoutSidenav_content">
          <main> 
            <header class="page-header pb-10">
              <div class="container">
                <div class="row align-items-center justify-content-between">
                  <div class="col-auto mt-4">
                    <h3>Request Work Order Page</h3>
                  </div>
                </div>
              </div>
            </header>
  
            <!-- Main page content -->
            <div class="container mt-n10">
              <div class="row">
                <div class="col-lg-12">
                  <!-- Account Details Card -->
                  <div class="card shadow-lg">
                    <div class="card-header bg-cyan text-white">
                      <h5 class="mb-0">User Details</h5>
                    </div>
                    <div class="card-body">
                      <form>
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="small mb-1">Requested by</label>
                            <input class="form-control" type="text" v-model="fullName" disabled/>
                          </div>
                          <div class="form-group col-md-3 d-flex align-items-center gap-3">
                            <div class="flex-grow-1">
                                <label for="requisitioner" class="small mb-1">Select Requisitioner type:</label>
                                <select v-model="selectedRtype" class="form-control">
                                    <option v-for="wrequisitioner in wrequisitioner" :key="wrequisitioner" :value="wrequisitioner">
                                        {{ wrequisitioner }}
                                    </option>
                                    <option value="other">Other type</option>
                                </select>
                            </div>

                                <!-- Show input field if "Other concern" is selected -->
                                <div v-if="selectedRtype === 'other'" class="form-group col-md-12 flex-grow-1">
                                    <label for="otherRtype" class="small mb-1 ">Specify the type:</label>
                                    <input type="text" v-model="otherRtype" class="form-control col-md-12" placeholder="Enter position" />
                                </div>
                            </div>
                        </div>
  
                        <div class="row">
                          <div class="form-group col-md-6">
                            <label class="small mb-1">College/Unit</label>
                            <input class="form-control" type="text" v-model="user.college" disabled/>
                          </div>
                          <div class="form-group col-md-6">
                            <label class="small mb-1">Department</label>
                            <input class="form-control" type="text" v-model="user.department" disabled />
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
  
              <!-- Work Order Details Card -->
              <div class="row mt-4">
                <div class="col-lg-12">
                  <div class="card shadow-lg">
                    <div class="card-header bg-green text-white">
                      <h5 class="mb-0">Work Order Details</h5>
                    </div>
                    <div class="card-body">
                      <form>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="concern" class="small mb-1">Select Concern:</label>
                                <select v-model="selectedConcern" class="form-control">
                                    <option v-for="concern in concerns" :key="concern" :value="concern">
                                        {{ concern }}
                                    </option>
                                    <option value="other">Other concern</option>
                                </select>

                                <!-- Show input field if "Other concern" is selected -->
                                <div v-if="selectedConcern === 'other'" class="form-group col-md-12">
                                    <label for="otherConcern" class="small mb-1 ">Specify your concern:</label>
                                    <input type="text" v-model="otherConcern" class="form-control col-md-12" placeholder="Enter your concern" />
                                </div>
                            </div>
                          <div class="form-group col-md-6">
                            <label class="small mb-1">Date Requested</label>
                            <input class="form-control" type="date" v-model="todayDate" disabled/>
                          </div>
                        </div>
  
                        <div class="row">
                          <div class="form-group col-md-12">
                            <label class="small mb-1">Description</label>
                            <textarea class="form-control" rows="3" ></textarea>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </body>
  </template>
  
  <script setup>
  import EmployeeNav from '@/Layouts/EmployeeNav/EmployeeNav.vue';
  import { ref, computed, onMounted } from 'vue';
  import axios from 'axios';
  
  //Conserns
  const concerns = ref([]); // Stores fetched concerns
    const selectedConcern = ref(''); // Selected concern
    const otherConcern = ref(''); // Stores user-inputted concern

  //Requisitioner type
    const wrequisitioner = ref([]);
    const selectedRtype = ref('');
    const otherRtype = ref('');

    // Fetch requisitioner type from the API
    const fetchRequisitioner = async () => {
        try {
            const response = await axios.get('/api/wrequisitioner'); // Fetch from Laravel API
            wrequisitioner.value = response.data; // Update requisitioner list
        } catch (error) {
            console.error("Error fetching requisitioner:", error);
        }
    };
// Fetch concerns from the API
const fetchConcerns = async () => {
    try {
        const response = await axios.get('/api/concerns'); // Fetch from Laravel API
        concerns.value = response.data; // Update concerns list
    } catch (error) {
        console.error("Error fetching concerns:", error);
    }
};

  // Reactive variables
  const user = ref({
    first_name: '',
    middle_name: '',
    last_name: '',
    college: '',
    department: '',
    role: ''
  });
  const todayDate = ref('');
  
  // Computed property to format full name
  const fullName = computed(() => {
    return `${user.value.first_name} ${user.value.middle_name ? user.value.middle_name + ' ' : ''}${user.value.last_name}`;
  });
  
  // Fetch current user
  const fetchUser = async () => {
    try {
      const response = await axios.get('/api/user', { withCredentials: true });
      user.value = response.data;
      todayDate.value = new Date().toISOString().split('T')[0]; // Format YYYY-MM-DD
    } catch (error) {
      console.error("Error fetching user:", error);
    }
  };
  
  // Call fetchUser when component mounts
  onMounted(() => {
    fetchUser();
    fetchConcerns();
    fetchRequisitioner();

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

    // Load CSS first
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
            feather.replace(); // Apply icons after loading
        });

        loadScript('/demo/datatables-demo.js');

        // Load scripts.js after everything else
        loadScript('/js/scripts.js');
    }); 
  });
  </script>
  