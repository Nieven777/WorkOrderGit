<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { CheckIcon, XIcon, PrinterIcon, ClockIcon, CheckCircleIcon, AlertCircleIcon, FileTextIcon, UserIcon, BuildingIcon, TagIcon } from 'lucide-vue-next';

// Define TypeScript interfaces
interface WorkOrder {
  id: string;
  ticket_number: string;
  requested_by: string;
  requisitioner_type: string;
  college: string;
  department: string;
  concern: string;
  description: string;
  date_requested: string;
  status: 'Submitted' | 'Received' | 'Completed' | 'Declined';
  category?: string;
  accepted_by?: string;
  completed_by?: string;
  completed_description?: string;
}

// Mock history data - this would come from your API in a real implementation
const historyData = ref([
  {
    date: '2025-04-03 10:30 AM',
    action: 'Submitted',
    user: 'John Smith',
    notes: 'Initial work order submission'
  },
  {
    date: '2025-04-04 09:15 AM',
    action: 'Accepted',
    user: 'Tech Support Team',
    notes: 'Work order accepted for processing'
  },
  {
    date: '2025-04-05 02:45 PM',
    action: 'Completed',
    user: 'James Wilson',
    notes: 'Repairs completed and verified'
  }
]);

interface Props {
  order: WorkOrder | null;
  loading?: boolean;
}

// Define emits
const emit = defineEmits(['close', 'accept', 'decline', 'complete', 'print', 'update']);

const props = withDefaults(defineProps<Props>(), {
  order: null,
  loading: false
});

const printWorkOrder = () => {
  if (!props.order) return;
  
  // Call your backend endpoint to generate/print the DOCX
  window.open(`/api/work-orders/${props.order.id}/print`, '_blank');
  
};



const printPDF = () => {
    if (!props.order?.id) return;
    window.open(`/work-orders/${props.order.id}/print-pdf`, '_blank');
};


// Active tab state
const activeTab = ref('details');

// Form validation
const errors = ref<Record<string, string>>({});
const showConfirmation = ref<string | null>(null);
const formValid = ref(true);
const completedDescription = ref(props.order?.completed_description || '');

// Watch for changes in the completed description
watch(() => completedDescription.value, (newVal) => {
  validateForm();
  if (props.order) {
    emit('update', { ...props.order, completed_description: newVal });
  }
});

// Form validation function
const validateForm = () => {
  errors.value = {};
  formValid.value = true;
  
  if ((props.order?.status === 'Received') && 
      (!completedDescription.value || completedDescription.value.trim().length < 10)) {
    errors.value.completed_description = 'Please provide a detailed description (minimum 10 characters)';
    formValid.value = false;
  }
  
  return formValid.value;
};

// Handle confirmation actions
const confirmAction = (action: string) => {
  showConfirmation.value = action;
};

const executeAction = (action: string) => {
  if (!props.order) return;
  
  if (action === 'accept') {
    emit('accept', props.order);
  } else if (action === 'decline') {
    emit('decline', props.order);
  } else if (action === 'complete') {
    if (validateForm()) {
      emit('complete', { ...props.order, completed_description: completedDescription.value });
    }
  }
  
  showConfirmation.value = null;
};

// Get status color class
const getStatusColorClass = (status?: string) => {
  switch (status) {
    case 'Submitted': return 'bg-blue-100 text-blue-800';
    case 'Received': return 'bg-yellow-100 text-yellow-800';
    case 'Completed': return 'bg-green-100 text-green-800';
    case 'Declined': return 'bg-red-100 text-red-800';
    default: return 'bg-gray-100 text-gray-800';
  }
};

onMounted(() => {
  // Force validation on initial load
  validateForm();
});
</script>

<template>
  <!-- Full Page Content that works with existing layout -->
  <div class="w-full h-full bg-gray-50">
    <!-- Header with actions and back button -->
    <div class="bg-white shadow-sm border-b mb-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-4 flex items-center justify-between">
          <div class="flex items-center">
            <button 
              @click="$emit('close')" 
              class="mr-3 p-2 rounded-md hover:bg-gray-100"
              aria-label="Go back"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <h1 class="text-xl font-medium text-gray-800 flex items-center">
              <FileTextIcon class="w-5 h-5 mr-2 text-blue-600" />
              Work Order Details
            </h1>
          </div>
          
          <div class="flex space-x-2">
            <button
              v-if="order?.status === 'Submitted'"
              class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              @click="confirmAction('accept')"
            >
              <CheckIcon class="mr-1 w-4 h-4" />
              Accept
            </button>
            <button
              v-if="order?.status === 'Submitted'"
              class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
              @click="confirmAction('decline')"
            >
              <XIcon class="mr-1 w-4 h-4" />
              Decline
            </button>
            <button
              v-if="order?.status === 'Received'"
              class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              @click="confirmAction('complete')"
              :disabled="!formValid"
            >
              <CheckIcon class="mr-1 w-4 h-4" />
              Complete
            </button>
            <button 
    class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
    @click="printWorkOrder"
  >
    <PrinterIcon class="mr-1 w-4 h-4" />
    Export DOCX
  </button>

  <button 
        @click="printPDF"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
    >
        <PrinterIcon class="inline mr-2" />
        Print PDF
    </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center p-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600"></div>
      </div>

      <!-- Content -->
      <div v-else-if="order">
        <!-- Status Banner -->
        <div 
          class="mb-6 p-3 rounded-md flex items-center justify-between" 
          :class="getStatusColorClass(order.status)"
          aria-live="polite"
        >
          <div class="flex items-center">
            <CheckCircleIcon v-if="order.status === 'Completed'" class="mr-2 w-5 h-5" />
            <ClockIcon v-else-if="order.status === 'Submitted'" class="mr-2 w-5 h-5" />
            <AlertCircleIcon v-else class="mr-2 w-5 h-5" />
            <span class="font-bold">Status: {{ order.status }}</span>
          </div>
          <span class="text-sm">{{ order.date_requested }}</span>
        </div>
        
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
          <div class="flex -mb-px">
            <button 
              @click="activeTab = 'details'" 
              class="py-3 px-4 text-sm font-medium"
              :class="activeTab === 'details' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            >
              Order Details
            </button>
            <button 
              @click="activeTab = 'history'" 
              class="py-3 px-4 text-sm font-medium"
              :class="activeTab === 'history' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            >
              History
            </button>
          </div>
        </div>

        <!-- Details Tab -->
        <div v-if="activeTab === 'details'" class="space-y-6 pb-8">
          <!-- Basic Info Card -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h4 class="text-lg font-semibold mb-4 text-gray-800">Order Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Ticket Number</label>
                <p class="mt-1 font-semibold text-blue-700">{{ order.ticket_number }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 flex items-center">
                  <UserIcon class="w-4 h-4 mr-1" />
                  Requested By
                </label>
                <p class="mt-1">{{ order.requested_by }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Requisitioner Type</label>
                <p class="mt-1">{{ order.requisitioner_type }}</p>
              </div>
            </div>
          </div>

          <!-- Department Info Card -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h4 class="text-lg font-semibold mb-4 text-gray-800">Department Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 flex items-center">
                  <BuildingIcon class="w-4 h-4 mr-1" />
                  College/Unit
                </label>
                <p class="mt-1">{{ order.college }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Department</label>
                <p class="mt-1">{{ order.department }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 flex items-center">
                  <TagIcon class="w-4 h-4 mr-1" />
                  Concern
                </label>
                <p class="mt-1">{{ order.concern }}</p>
              </div>
            </div>
          </div>

          <!-- Description Card -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h4 class="text-lg font-semibold mb-4 text-gray-800">Request Details</h4>
            <div>
              <label class="block text-sm font-medium text-gray-700">Description</label>
              <p class="mt-1 whitespace-pre-line">{{ order.description }}</p>
            </div>
          </div>

          <!-- Completion Details Card (Only for Received or Completed orders) -->
          <div 
            v-if="order.status === 'Received' || order.status === 'Completed'" 
            class="bg-white p-6 rounded-lg shadow-sm"
          >
            <h4 class="text-lg font-semibold mb-4 text-gray-800">
              {{ order.status === 'Completed' ? 'Completion Details' : 'Work Progress' }}
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div v-if="order.accepted_by">
                <label class="block text-sm font-medium text-gray-700">Accepted By</label>
                <p class="mt-1">{{ order.accepted_by }}</p>
              </div>
              <div v-if="order.status === 'Completed' && order.completed_by">
                <label class="block text-sm font-medium text-gray-700">Completed By</label>
                <p class="mt-1">{{ order.completed_by }}</p>
              </div>
              <div v-if="order.status === 'Completed' && order.category">
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <p class="mt-1">{{ order.category }}</p>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">
                Completed Description
                <span v-if="order.status === 'Received'" class="text-red-500">*</span>
              </label>
              <textarea
                v-if="order.status === 'Received'"
                v-model="completedDescription"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                :class="{'border-red-500': errors.completed_description}"
                rows="4"
                placeholder="Enter description of completed work"
              ></textarea>
              <p v-else class="mt-1 whitespace-pre-line border p-3 bg-gray-50 rounded">{{ completedDescription || order.completed_description }}</p>
              <p v-if="errors.completed_description" class="mt-1 text-sm text-red-600">
                {{ errors.completed_description }}
              </p>
            </div>
          </div>
        </div>

        <!-- History Tab -->
        <div v-else-if="activeTab === 'history'" class="bg-white p-6 rounded-lg shadow-sm">
          <h4 class="text-lg font-semibold mb-6 text-gray-800">Work Order History</h4>
          
          <div class="relative">
            <!-- Timeline -->
            <div class="border-l-2 border-blue-500 ml-4">
              <div 
                v-for="(item, index) in historyData" 
                :key="index"
                class="mb-8 ml-6"
              >
                <div class="absolute w-4 h-4 bg-blue-500 rounded-full mt-1.5 -left-2 border-2 border-white"></div>
                <div class="flex flex-wrap items-center mb-1 gap-2">
                  <time class="text-sm font-medium text-gray-500">{{ item.date }}</time>
                  <span 
                    class="text-xs font-medium px-2 py-1 rounded-full"
                    :class="{'bg-green-100 text-green-800': item.action === 'Completed', 
                            'bg-blue-100 text-blue-800': item.action === 'Submitted',
                            'bg-yellow-100 text-yellow-800': item.action === 'Accepted'}"
                  >
                    {{ item.action }}
                  </span>
                </div>
                <h3 class="text-md font-medium">{{ item.user }}</h3>
                <p v-if="item.notes" class="mt-1 text-gray-600">{{ item.notes }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="bg-white p-6 rounded-lg shadow-sm text-center">
        <AlertCircleIcon class="mx-auto mb-4 w-12 h-12 text-red-500" />
        <h3 class="text-lg font-medium text-gray-900">No order data available</h3>
        <p class="text-gray-500">Unable to load work order details</p>
      </div>
    </div>
  </div>

  <!-- Confirmation Dialog -->
  <div 
    v-if="showConfirmation"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-25"
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirmation-title"
  >
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
      <h3 id="confirmation-title" class="text-lg font-medium text-gray-900 mb-4">
        Confirm Action
      </h3>
      <p class="mb-5 text-gray-600">
        Are you sure you want to 
        <span class="font-bold" v-if="showConfirmation === 'accept'">accept</span>
        <span class="font-bold" v-if="showConfirmation === 'decline'">decline</span>
        <span class="font-bold" v-if="showConfirmation === 'complete'">complete</span>
        this work order?
      </p>
      <div class="flex justify-end gap-2">
        <button 
          class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50"
          @click="showConfirmation = null"
        >
          Cancel
        </button>
        <button 
          class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700"
          @click="executeAction(showConfirmation)"
        >
          Confirm
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Ensure spacing is consistent */
.grid {
  gap: 1.5rem;
}

/* Ensure form controls match your app's styling */
textarea, input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
}

textarea:focus, input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Make sure text is readable */
p {
  margin-top: 0.25rem;
  margin-bottom: 0.25rem;
}

label {
  display: block;
  margin-bottom: 0.25rem;
  font-weight: 500;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
  .grid-cols-3, .grid-cols-2 {
    grid-template-columns: 1fr;
  }
  
  .flex-wrap {
    flex-wrap: wrap;
  }
  
  .mr-3 {
    margin-right: 0.5rem;
  }
}

/* Subtle animation for tab switching */
.tab-content-enter-active,
.tab-content-leave-active {
  transition: opacity 0.3s ease;
}

.tab-content-enter-from,
.tab-content-leave-to {
  opacity: 0;
}
</style>