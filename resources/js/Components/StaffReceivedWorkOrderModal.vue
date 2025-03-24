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
            <!-- Additional fields like Department, Concern, Date Requested, etc. -->
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
            <!-- Completed Work Description -->
            <div class="row" v-if="order.status === 'Received'">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Completed Description:</label>
                  <textarea
                    v-model="order.completed_description"
                    class="form-control"
                    placeholder="Enter description of completed work"
                    required
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
          <!-- Modal Footer: Action Buttons -->
          <div class="modal-footer" v-if="order.status === 'Received'">
            <button class="btn btn-success" @click="completeWorkOrder(order)">Complete</button>
            <button class="btn btn-secondary" @click="$emit('print')">Print</button>
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
  
  const emit = defineEmits(['close', 'complete', 'print']);
  
  // Calculate category and emit complete event with updated data
  const completeWorkOrder = (order) => {
    if (!order.completed_description) {
      alert('Please provide a description for the completed work.');
      return;
    }
    // Calculate category based on date_requested and current date
    const daysDiff = Math.ceil((new Date() - new Date(order.date_requested)) / (1000 * 60 * 60 * 24));
    if (daysDiff <= 1) {
      order.category = 'Category I (Finished within one working day)';
    } else if (daysDiff <= 3) {
      order.category = 'Category II (1-3 working days)';
    } else if (daysDiff <= 7) {
      order.category = 'Category III (4-7 working days)';
    } else {
      order.category = 'Category IV (7+ working days)';
    }
    // Emit complete event with order data
    emit('complete', order);
  };
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
  