<template>
    <div class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Work Order Details</h5>
            <button type="button" class="close" @click="$emit('close')">&times;</button>
          </div>
          <!-- Modal Body -->
          <div class="modal-body" v-if="order">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Ticket Number:</label>
                  <p>{{ order.ticket_number }}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>College/Unit:</label>
                  <p>{{ order.college }}</p>
                </div>
              </div>
            </div>
            <!-- Additional details can be added here as needed -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Department:</label>
                  <p>{{ order.department }}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Concern:</label>
                  <p>{{ order.concern }}</p>
                </div>
              </div>
            </div>
            <!-- More fields can be inserted similarly -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Date Requested:</label>
                  <p>{{ order.date_requested }}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Status:</label>
                  <p>{{ order.status }}</p>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal Footer: Action Buttons -->
          <div class="modal-footer">
            <button class="btn btn-success" @click="$emit('accept', order)">Accept</button>
            <button class="btn btn-secondary" @click="$emit('close')">Close</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  const props = defineProps({
    order: {
      type: Object,
      default: null
    }
  });
  </script>
  
  <style scoped>
  .modal-header {
    background-color: #007bff;
  }
  .modal-title {
    font-weight: bold;
  }
  .form-group {
    margin-bottom: 1rem;
  }
  button {
    cursor: pointer;
  }
  </style>
  