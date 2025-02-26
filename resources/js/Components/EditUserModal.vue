<template>
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit User</h5>
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
                      <form @submit.prevent="updateUser">
                        <div class="form-row">
                          <div class="form-group col-md-3">
                            <label class="small mb-1" for="inputUsername">Username</label>
                            <input v-model="form.username" class="form-control" id="inputUsername" type="text" />
                          </div>
                          <div class="form-group col-md-3">
                            <label class="small mb-1" for="inputFirstName">First Name</label>
                            <input v-model="form.first_name" class="form-control" id="inputFirstName" type="text" />
                          </div>
                          <div class="form-group col-md-3">
                            <label class="small mb-1" for="inputMiddleName">Middle Name</label>
                            <input v-model="form.middle_name" class="form-control" id="inputMiddleName" type="text" />
                          </div>
                          <div class="form-group col-md-3">
                            <label class="small mb-1" for="inputLastName">Last Name</label>
                            <input v-model="form.last_name" class="form-control" id="inputLastName" type="text" />
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label class="small mb-1" for="inputEmail">Email</label>
                            <input v-model="form.email" class="form-control" id="inputEmail" type="email" />
                          </div>
                          <div class="form-group col-md-4">
                            <label class="small mb-1" for="inputUserID">User ID</label>
                            <input v-model="form.user_id" class="form-control" id="inputUserID" type="text" disabled />
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
                        <button class="btn btn-primary" type="submit" :disabled="loading">
                          <span v-if="loading">Updating...</span>
                          <span v-else>Update</span>
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
    user: Object,
  },
  emits: ["close"],


  data() {
  return {
    form: {
      username: this.user?.username || "",
      first_name: this.user?.first_name || "",
      middle_name: this.user?.middle_name || "", // Ensure empty string if null
      last_name: this.user?.last_name || "",
      email: this.user?.email || "",
      user_id: this.user?.user_id || "",
      role: this.user?.role || "", // Ensure empty string if null
    },
  };
},

  watch: {
    user(newUser) {
      this.form = { ...newUser };
    },
  },
  
  methods: {
    async updateUser() {
      this.loading = true;
      try {
        await axios.put(`/api/users/${this.form.id}`, this.form);
        alert("User updated successfully!");
        this.$emit("close");
      } catch (error) {
        console.error("Error updating user:", error.response ? error.response.data : error);
      }
      this.loading = false;
    },
    closeModal() {
      this.$emit("close");
    },
  },
};
</script>
