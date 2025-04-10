select<script setup>
import AdminNav from '@/Layouts/Adminnav/AdminNav.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const loading = ref(true); 

// Form fields
const requested_by = ref('');
const selectedRtype = ref('');
const otherRtype = ref('');
const selectedConcern = ref('');
const otherConcern = ref('');
const description = ref('');
const selectedDepartment = ref('');
const selectedCollegeUnit = ref('');

// Options for dropdowns
const concerns = ref([]);
const wrequisitioner = ref([]);
const departments = ref([]);
const collegeunit = ref([]);


// User details and date
const user = ref({
  first_name: '',
  middle_name: '',
  last_name: '',
  college: '',
  role: ''
});
const todayDate = ref('');

// Validation errors
const errors = ref({});

// Privacy consent
const modalVisible = ref(false);
const privacyConsent = ref(false);

// Fetch requisitioner types
const fetchRequisitioner = async () => {
  try {
    const response = await axios.get('/api/wrequisitioner');
    wrequisitioner.value = response.data;
  } catch (error) {
    console.error("Error fetching requisitioner:", error);
  }
};

// Fetch concerns
const fetchConcerns = async () => {
  try {
    const response = await axios.get('/api/concerns');
    concerns.value = response.data;
  } catch (error) {
    console.error("Error fetching concerns:", error);
  }
};

// Fetch user details (temporary solution doesn't filter departments by college)
const fetchUser = async () => {
  try {
    const response = await axios.get('/api/user', { withCredentials: true });
    user.value = response.data;
    todayDate.value = new Date().toISOString().split('T')[0];
    
    // Instead of filtering departments, fetch all departments:
    await fetchAllDepartments();
  } catch (error) {
    console.error("Error fetching user:", error);
  }
};

// Temporary solution: Fetch all departments
const fetchAllDepartments = async () => {
  try {
    const response = await axios.get('/api/departments'); // Adjust endpoint as needed
    departments.value = response.data;
  } catch (error) {
    console.error("Error fetching departments:", error);
  }
};

// Fetch College/Unit data
const fetchCollegeUnit = async () => {
  try {
    const response = await axios.get('/api/colleges');
    collegeunit.value = response.data;
  } catch (error) {
    console.error("Error fetching college unit:", error);
  }
};


// Form validation
const validateForm = () => {
  errors.value = {};

  if (!requested_by.value.trim()) {
    errors.value.requested_by = "Requested by is required";
  }
  if (!selectedRtype.value) {
    errors.value.selectedRtype = "Requisitioner type is required";
  }
  if (selectedRtype.value === 'other' && !otherRtype.value.trim()) {
    errors.value.otherRtype = "Please specify the other type";
  }
  if (!selectedConcern.value) {
    errors.value.selectedConcern = "Concern is required";
  }
  if (selectedConcern.value === 'other' && !otherConcern.value.trim()) {
    errors.value.otherConcern = "Please specify your concern";
  }
  if (!selectedDepartment.value) {
    errors.value.selectedDepartment = "Department is required";
  }
  if (!selectedCollegeUnit.value) {
    errors.value.selectedCollegeUnit = "College/Unit is required";
  }
  if (!description.value.trim()) {
    errors.value.description = "Description is required";
  }

  return Object.keys(errors.value).length === 0;
};

// Reset form
const resetForm = () => {
  requested_by.value = '';
  selectedRtype.value = '';
  otherRtype.value = '';
  selectedConcern.value = '';
  otherConcern.value = '';
  selectedDepartment.value = '';
  selectedCollegeUnit.value = '';
  description.value = '';
  privacyConsent.value = false;
};

// Submit form
const confirmSubmit = async () => {
  if (!privacyConsent.value) {
    alert("You must agree to the privacy notice to proceed.");
    return;
  }

  try {
    const payload = {
      requested_by: requested_by.value,
      requisitioner_type: selectedRtype.value === 'other' ? otherRtype.value : selectedRtype.value,
      college: selectedCollegeUnit.value, // ithink this is the problem
      department: selectedDepartment.value, // temporary: this is department name/id from all departments
      concern: selectedConcern.value === 'other' ? otherConcern.value : selectedConcern.value,
      date_requested: todayDate.value,
      description: description.value,
    };

    await axios.post('/api/admin-submit-work-order', payload);
    alert("Work order submitted successfully!");
    resetForm();
    modalVisible.value = false;
  } catch (error) {
    console.error("Error submitting work order:", error);
    alert("Failed to submit work order.");
  }
};

// Open confirmation modal
const submitForm = () => {
  if (!validateForm()) {
    alert("Please fill in all required fields correctly.");
    return;
  }
  modalVisible.value = true;
};

onMounted(async () => {
  loading.value = true;
  await Promise.all([
    fetchUser(),
    fetchConcerns(),
    fetchRequisitioner(),
    fetchCollegeUnit()
  ]); 
  loading.value = false;

// Load assets (CSS and JS files)
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

  // Load CSS files
  loadCSS('/css/styles.css');
  // loadCSS('/css/dataTables.bootstrap4.min.css');

  // Load JS files
  loadScript('/js/jquery-3.5.1.min.js', () => {
    console.log("✅ jQuery loaded");
    loadScript('/js/bootstrap.bundle.min.js');
    // loadScript('/js/jquery.dataTables.min.js', () => {
    //   console.log("✅ DataTables loaded");
    //   loadScript('/js/dataTables.bootstrap4.min.js', () => {
    //     console.log("✅ DataTables Bootstrap loaded");
    //     initializeDataTable();
    //   });
    // });
    loadScript('/js/all.min.js');
    loadScript('/js/feather.min.js', () => {
    //   console.log("✅ Feather icons loaded");
      feather.replace();
    });
    // loadScript('/demo/datatables-demo.js');
    loadScript('/js/scripts.js');
  });
});
</script>

<template>
  <body class="nav-fixed" >
    <AdminNav />
    <div v-if="loading" class="loading-screen">
      <div class="spinner"></div>
    </div>
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
          <div class="container mt-n10" v-if="!loading">
            <!-- User Details Card -->
            <div class="row">
              <div class="col-lg-12">
                <div class="card shadow-lg">
                  <div class="card-header bg-cyan text-white">
                    <h5 class="mb-0">User Details</h5>
                  </div>
                  <div class="card-body">
                    <form>
                      <div class="row">
                        <!-- Requested by (editable) -->
                        <div class="form-group col-md-6">
                          <label class="small mb-1">Requested by:</label>
                          <input placeholder="Type here..." class="form-control" type="text" v-model="requested_by" />
                          <div v-if="errors.requested_by" class="text-danger">{{ errors.requested_by }}</div>
                        </div>

                        <!-- Requisitioner Type -->
                        <div class="form-group col-md-3">
                          <label class="small mb-1">Select Requisitioner type:</label>
                          <select v-model="selectedRtype" class="form-control" style="min-width: 100%; max-width: 100%;">
                            <option value="">Select a type</option>
                            <option v-for="item in wrequisitioner" :key="item" :value="item">
                              {{ item }}
                            </option>
                            <option value="other">Other type</option>
                          </select>
                          <div v-if="errors.selectedRtype" class="text-danger">{{ errors.selectedRtype }}</div>
                        </div>

                        <!-- Other Requisitioner Type (if 'other') -->
                        <div v-if="selectedRtype === 'other'" class="form-group col-md-3">
                          <label class="small mb-1">Specify the type:</label>
                          <input type="text" v-model="otherRtype" class="form-control" style="min-width: 100%;" placeholder="Enter position" />
                          <div v-if="errors.otherRtype" class="text-danger">{{ errors.otherRtype }}</div>
                        </div>
                      </div>
 
                      <div class="row"> 
                        <div class="form-group col-md-6">
                            <label for="college" class="form-label">College/Unit</label>
                            <select v-model="selectedCollegeUnit" class="form-control">
                            <option value="" disabled>Select College/Unit</option>
                            <option v-for="college in collegeunit" 
                                    :key="college.c_u_id" 
                                    :value="college.college_unit">
                                {{ college.college_unit }}
                            </option>
                        </select>
                        <div v-if="errors.selectedCollegeUnit" class="text-danger" >{{ errors.selectedCollegeUnit }}</div>

                            </div>

                        <!-- Department Dropdown (Temporary: All departments) -->
                        <div class="form-group col-md-6">
                          <label class="form-label">Department</label>
                          <select class="form-control" v-model="selectedDepartment">
                            <option value="">Select a department</option>
                            <option v-for="dept in departments" :key="dept.department_id" :value="dept.department">
                              {{ dept.department }}
                            </option>
                          </select>
                          <div v-if="errors.selectedDepartment" class="text-danger">{{ errors.selectedDepartment }}</div>
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
                        <!-- Concern -->
                        <div class="form-group col-md-6">
                          <label class="small mb-1">Select Concern:</label>
                          <select v-model="selectedConcern" class="form-control"> 
                            <option value="">Select a concern</option>
                            <option v-for="item in concerns" :key="item" :value="item">
                              {{ item }}
                            </option>
                            <option value="other">Other concern</option>
                          </select>
                          <div v-if="errors.selectedConcern" class="text-danger">{{ errors.selectedConcern }}</div>

                          <!-- Other Concern (if 'other') -->
                          <div v-if="selectedConcern === 'other'" class="form-group mt-2">
                            <label class="small mb-1">Specify your concern:</label>
                            <input type="text" v-model="otherConcern" class="form-control" placeholder="Enter your concern" />
                            <div v-if="errors.otherConcern" class="text-danger">{{ errors.otherConcern }}</div>
                          </div>
                        </div>

                        <!-- Date Requested -->
                        <div class="form-group col-md-6">
                          <label class="small mb-1">Date Requested</label>
                          <input class="form-control" type="date" v-model="todayDate" disabled />
                          <div v-if="errors.todayDate" class="text-danger">{{ errors.todayDate }}</div>
                        </div>
                      </div>

                      <div class="row">
                        <!-- Description -->
                        <div class="form-group col-md-12">
                          <label class="small mb-1">Description</label>
                          <textarea class="form-control" rows="3" v-model="description" placeholder ="Type here..."></textarea>
                          <div v-if="errors.description" class="text-danger">{{ errors.description }}</div>
                        </div>
                      </div>
                      <div class="col-md-12 mt-3">
                        <button @click.prevent="submitForm" class="btn btn-primary">Submit Work Order</button>
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

    <!-- Modal for Privacy Notice and Consent -->
    <div
      v-if="modalVisible"
      class="modal fade show d-block"
      tabindex="-1"
      role="dialog"
      style="background: rgba(0, 0, 0, 0.5);"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Privacy Notice and Consent</h5>
          </div>
          <div class="modal-body">
            <p>
              <strong>Privacy Notice</strong><br>
              The information provided in this form, including personal details and unit information, is collected for the purpose of processing and managing work order requests. Your data will be handled confidentially and accessed only by authorized personnel. It will be stored securely and retained in accordance with our data protection policies.
            </p>
            <p>
              <strong>Consent</strong><br>
              By submitting this form, I confirm that I have read and understood the Privacy Notice. I consent to the collection and processing of my personal data for work order purposes.
            </p>
            <div>
              <input type="checkbox" id="consentCheckbox" v-model="privacyConsent" />
              <label for="consentCheckbox" style="margin-left: 20px;">I Agree</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="confirmSubmit">Proceed</button>
            <button type="button" class="btn btn-danger" @click="modalVisible = false">Reject</button>
          </div>
        </div>
      </div>
    </div>
  </body>
</template>

<style scoped>
/* Make font darker */
body {
    color: #333;
}

/* Make labels bold */
label {
    font-weight: bold;
    color: #000;
}

/* Style input boxes and select dropdowns */
input, textarea, select {
    border: 1px solid black !important;
    padding: 8px;
    border-radius: 4px;
}

/* Loading screen styles */
.loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgb(255, 255, 255);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #ccc;
    border-top-color: #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

