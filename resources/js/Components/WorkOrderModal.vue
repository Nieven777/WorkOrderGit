<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { CheckIcon, XIcon, PrinterIcon, ClockIcon, CheckCircleIcon, AlertCircleIcon, FileTextIcon, UserIcon, BuildingIcon, TagIcon, CalendarIcon, InfoIcon, ClipboardIcon, HardHatIcon } from 'lucide-vue-next';
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
  <div class="w-full h-full bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-3 flex items-center justify-between">
          <div class="flex items-center">
            <button 
              @click="$emit('close')" 
              class="mr-2 p-1.5 rounded-md hover:bg-gray-100 text-gray-600 hover:text-gray-900"
              aria-label="Go back"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-800 flex items-center">
              <FileTextIcon class="w-5 h-5 mr-2 text-blue-600" />
              Work Order Details
            </h1>
          </div>
          
          <div class="flex space-x-2">
            <button
              v-if="order?.status === 'Submitted'"
              class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 focus:outline-none"
              @click="confirmAction('accept')"
            >
              <CheckIcon class="mr-1.5 w-4 h-4" />
              Accept
            </button>
            <button
              v-if="order?.status === 'Submitted'"
              class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 focus:outline-none"
              @click="confirmAction('decline')"
            >
              <XIcon class="mr-1.5 w-4 h-4" />
              Decline
            </button>
            <button
              v-if="order?.status === 'Received'"
              class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 focus:outline-none"
              @click="confirmAction('complete')"
              :disabled="!formValid"
            >
              <CheckIcon class="mr-1.5 w-4 h-4" />
              Complete
            </button>
            <button 
              class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 focus:outline-none"
              @click="printWorkOrder"
            >
              <PrinterIcon class="mr-1.5 w-4 h-4" />
              Export
            </button>

            <button 
              @click="printPDF"
              class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none"
            >
              <PrinterIcon class="mr-1.5 w-4 h-4" />
              Print
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center p-8">
        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-600"></div>
      </div>

      <!-- Content -->
      <div v-else-if="order" class="bg-white rounded-lg shadow-sm p-6">
        <!-- Status Banner -->
        <div 
          class="mb-4 p-3 rounded-md flex items-center justify-between text-sm" 
          :class="getStatusColorClass(order.status)"
        >
          <div class="flex items-center">
            <CheckCircleIcon v-if="order.status === 'Completed'" class="mr-2 w-4 h-4" />
            <ClockIcon v-else-if="order.status === 'Submitted'" class="mr-2 w-4 h-4" />
            <AlertCircleIcon v-else class="mr-2 w-4 h-4" />
            <span class="font-medium">Status: {{ order.status }}</span>
          </div>
          <div class="flex items-center">
            <CalendarIcon class="mr-1 w-4 h-4" />
            <span>{{ order.date_requested }}</span>
          </div>
        </div>
        
        <!-- Document Layout -->
        <div class="space-y-6">
          <!-- Ticket Number -->
          <div class="pb-4 border-b border-gray-100">
            <h2 class="text-base font-semibold mb-2 text-gray-800 flex items-center">
              <TagIcon class="w-4 h-4 mr-2 text-blue-600" />
              Ticket Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Ticket Number</label>
                <p class="font-medium text-blue-700">{{ order.ticket_number }}</p>
              </div>
            </div>
          </div>

          <!-- Requester Information -->
          <div class="pb-4 border-b border-gray-100">
            <h2 class="text-base font-semibold mb-2 text-gray-800 flex items-center">
              <UserIcon class="w-4 h-4 mr-2 text-blue-600" />
              Requester Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Requested By</label>
                <p>{{ order.requested_by }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Requisitioner Type</label>
                <p>{{ order.requisitioner_type }}</p>
              </div>
            </div>
          </div>

          <!-- Department Information -->
          <div class="pb-4 border-b border-gray-100">
            <h2 class="text-base font-semibold mb-2 text-gray-800 flex items-center">
              <BuildingIcon class="w-4 h-4 mr-2 text-blue-600" />
              Department Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">College/Unit</label>
                <p>{{ order.college }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Department</label>
                <p>{{ order.department }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Concern</label>
                <p>{{ order.concern }}</p>
              </div>
            </div>
          </div>

          <!-- Request Details -->
          <div class="pb-4 border-b border-gray-100">
            <h2 class="text-base font-semibold mb-2 text-gray-800 flex items-center">
              <ClipboardIcon class="w-4 h-4 mr-2 text-blue-600" />
              Request Details
            </h2>
            <div>
              <label class="block text-sm font-medium text-gray-600 mb-1">Description</label>
              <p class="whitespace-pre-line text-sm bg-gray-50 p-3 rounded">{{ order.description }}</p>
            </div>
          </div>

          <!-- Work Progress -->
          <div v-if="order.status === 'Received' || order.status === 'Completed'">
            <h2 class="text-base font-semibold mb-2 text-gray-800 flex items-center">
              <HardHatIcon class="w-4 h-4 mr-2 text-blue-600" />
              {{ order.status === 'Completed' ? 'Completion Details' : 'Work Progress' }}
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div v-if="order.accepted_by">
                <label class="block text-sm font-medium text-gray-600 mb-1">Accepted By</label>
                <p>{{ order.accepted_by }}</p>
              </div>
              <div v-if="order.status === 'Completed' && order.completed_by">
                <label class="block text-sm font-medium text-gray-600 mb-1">Completed By</label>
                <p>{{ order.completed_by }}</p>
              </div>
              <div v-if="order.status === 'Completed' && order.category">
                <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                <p>{{ order.category }}</p>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-600 mb-1">
                Work Description
                <span v-if="order.status === 'Received'" class="text-red-500">*</span>
              </label>
              <div v-if="order.status === 'Received'">
                <textarea
                  v-model="completedDescription"
                  class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm p-2"
                  :class="{'border-red-500': errors.completed_description}"
                  rows="4"
                  placeholder="Describe work performed..."
                ></textarea>
                <p v-if="errors.completed_description" class="mt-1 text-xs text-red-600 flex items-start">
                  <InfoIcon class="mr-1 w-3 h-3 mt-0.5" />
                  {{ errors.completed_description }}
                </p>
              </div>
              <div v-else class="bg-gray-50 p-3 rounded">
                <p class="whitespace-pre-line text-sm">{{ completedDescription || order.completed_description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="bg-white p-6 rounded-lg shadow-sm text-center">
        <AlertCircleIcon class="mx-auto mb-3 w-8 h-8 text-red-500" />
        <h3 class="text-base font-medium text-gray-900">No order data available</h3>
        <p class="text-sm text-gray-500">Unable to load work order details</p>
      </div>
    </div>

    <!-- Confirmation Dialog -->
    <div 
      v-if="showConfirmation"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-25"
    >
      <div class="bg-white rounded-lg shadow-sm p-4 w-full max-w-sm mx-4">
        <h3 class="text-base font-semibold text-gray-900 mb-3">
          Confirm Action
        </h3>
        <p class="mb-4 text-sm text-gray-600">
          Are you sure you want to 
          <span class="font-medium" v-if="showConfirmation === 'accept'">accept</span>
          <span class="font-medium" v-if="showConfirmation === 'decline'">decline</span>
          <span class="font-medium" v-if="showConfirmation === 'complete'">complete</span>
          this work order?
        </p>
        <div class="flex justify-end gap-2">
          <button 
            class="px-3 py-1.5 border border-gray-300 text-gray-700 text-sm font-medium rounded hover:bg-gray-50"
            @click="showConfirmation = null"
          >
            Cancel
          </button>
          <button 
            class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700"
            @click="executeAction(showConfirmation)"
          >
            Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Professional, compact styling */
body {
  font-size: 14px;
  line-height: 1.5;
}

/* Tighten up spacing */
.space-y-6 > * + * {
  margin-top: 1.25rem;
}

/* Subtle borders */
.border-gray-100 {
  border-color: #f3f4f6;
}

/* Professional shadows */
.shadow-sm {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .grid-cols-2 {
    grid-template-columns: 1fr;
  }
  
  /* Stack buttons on mobile */
  .flex.space-x-2 {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .flex.space-x-2 > * {
    width: 100%;
  }
}

/* Focus states for accessibility */
button:focus, input:focus, textarea:focus {
  outline: 2px solid #3b82f6;
  outline-offset: 1px;
  box-shadow: none;
}

/* Tighten up textarea */
textarea {
  min-height: 100px;
}
</style>