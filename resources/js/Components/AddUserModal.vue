<template>
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add New User</h5>
            <button type="button" class="close" @click="closeModal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <main>
              <div>
                <div>
                  <div>
                    <div>Account Details</div>
                    <div class="card-body">
                      <form @submit.prevent="registerUser">
                        <div class="form-row">
                          <div class="form-group col-md-3">
                            <label class="small mb-1" for="inputUsername">Username</label>
                            <input v-model="form.username" class="form-control" id="inputUsername" type="text" placeholder="Enter your username" />
                            <span v-if="errors.username" class="text-danger">{{ errors.username[0] }}</span>
                          </div>
                          <div class="form-group col-md-3">
                            <label class="small mb-1" for="inputFirstName">First Name</label>
                            <input v-model="form.first_name" class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" />
                            <span v-if="errors.first_name" class="text-danger">{{ errors.first_name[0] }}</span>
                          </div>
                          <div class="form-group col-md-3">
                            <label class="small mb-1" for="inputMiddleName">Middle Name</label>
                            <input v-model="form.middle_name" class="form-control" id="inputMiddleName" type="text" placeholder="Enter your middle name" />
                          </div>
                          <div class="form-group col-md-3">
                            <label class="small mb-1" for="inputLastName">Last Name</label>
                            <input v-model="form.last_name" class="form-control" id="inputLastName" type="text" placeholder="Enter your last name" />
                            <span v-if="errors.last_name" class="text-danger">{{ errors.last_name[0] }}</span>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-4">
                          <label class="small mb-1" for="inputCollege">College</label>
                          <select v-model="form.college" class="form-control" id="inputCollege">
                              <option value="">Select College</option>
                              <option v-for="college in colleges" :key="college.c_u_id" :value="college.college_unit">
                                {{ college.college_unit }}
                              </option>
                            </select>
                          </div>

                          <div class="form-group col-md-4">
                            <label class="small mb-1" for="inputDepartment">Department</label>
                            <select v-model="form.department" class="form-control" id="inputDepartment">
                              <option value="">Select Department</option>
                              <option v-for="department in departments" :key="department.deaprtmemt_id" :value="department.department">
                                {{ department.department }}
                              </option>
                            </select>
                          </div>

                          <div class="form-group col-md-4">
                            <label class="small mb-1" for="inputRole">Role</label>
                            <select v-model="form.role" class="form-control" id="inputRole">
                              <option value="">Select Role</option>
                              <option value="admin">Admin</option>
                              <option value="employee">Employee</option>
                              <option value="staff">Staff</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label class="small mb-1" for="inputEmailAddress">Email Address</label>
                            <input v-model="form.email" class="form-control" id="inputEmailAddress" type="email" placeholder="Enter your email address" />
                            <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}</span>
                          </div>
                          <div class="form-group col-md-4">
                            <label class="small mb-1" for="inputUserID">User ID</label>
                            <input v-model="form.user_id" class="form-control" id="inputUserID" type="text" placeholder="Enter User ID" />
                          </div>
                          <div class="form-group col-md-4">
                            <label class="small mb-1" for="inputProfilePicture">Profile Picture</label>
                            <input class="form-control" id="inputProfilePicture" type="file" @change="handleFileUpload" />
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label class="small mb-1" for="inputPass">Password</label>
                            <input v-model="form.password" class="form-control" id="inputPass" type="password" placeholder="Enter password" />
                            <span v-if="errors.password" class="text-danger">{{ errors.password[0] }}</span>
                          </div>
                          <div class="form-group col-md-6">
                            <label class="small mb-1" for="inputConfirmPassword">Confirm Password</label>
                            <input v-model="form.password_confirmation" class="form-control" id="inputConfirmPassword" type="password" placeholder="Confirm Password" />
                          </div>
                        </div>
                        <button class="btn btn-primary" type="submit" :disabled="loading">
                          <span v-if="loading">Processing...</span>
                          <span v-else>Submit</span>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </main>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showModal" class="modal-backdrop fade show"></div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    props: {
      showModal: Boolean,
    },
    emits: ["close"],
  
    data() {
      return {
        loading: false,
        colleges: [], // Stores fetched colleges
        departments: [], // Stores fetched departments
        form: {
          username: "",
          first_name: "",
          middle_name: "",
          last_name: "",
          college: "",
          department: "",
          role: "",
          email: "",
          user_id: "",
          password: "",
          password_confirmation: "",
          profile_picture: null,
        },
        errors: {},
      };
    },
  
    mounted() {
      this.getColleges();
      this.getDepartments();
    },
  
    methods: {
      async registerUser() {
        this.errors = {};
        this.loading = true;
        try {
          let formData = new FormData();
          for (let key in this.form) {
            formData.append(key, this.form[key]);
          }
  
          // Debugging: Check form data
          console.log("Sending Form Data:", Object.fromEntries(formData.entries()));
  
          await axios.post("/api/register", formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          });
  
          alert("User registered successfully!");
          this.resetForm();
          this.$emit("close");
        } catch (error) {
          console.error("Error:", error.response ? error.response.data : error);
          if (error.response && error.response.data.errors) {
            this.errors = error.response.data.errors;
          }
        }
        this.loading = false;
      },
  
      resetForm() {
        this.form = {
          username: "",
          first_name: "",
          middle_name: "",
          last_name: "",
          college: "",
          department: "",
          role: "",
          email: "",
          user_id: "",
          password: "",
          password_confirmation: "",
          profile_picture: null,
        };
      },
  
      closeModal() {
        this.$emit("close");
      },
  
      handleFileUpload(event) {
        this.form.profile_picture = event.target.files[0];
      },
  
      async getColleges() {
        try {
          const response = await axios.get("/api/colleges");
          this.colleges = response.data;
        } catch (error) {
          console.error("Error fetching colleges:", error);
        }
      },
  
      async getDepartments() {
        try {
          const response = await axios.get("/api/departments");
          this.departments = response.data;
        } catch (error) {
          console.error("Error fetching departments:", error);
        }
      },
    },
  };
  </script>