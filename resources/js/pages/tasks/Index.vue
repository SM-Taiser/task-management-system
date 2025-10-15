<script setup lang="ts">
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import Toast from '@/components/Toast.vue';

const props = defineProps({
  tasks: Array,
  status: Array
})

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Tasks',
        href: '/tasks',
    }
];
const showModal = ref(false)
const isEditMode = ref(false)
const editingTask = ref(null)

const form = useForm({
  id: null,
  title: '',
  description: '',
  status: 'pending',
})

function openCreateModal() {
  isEditMode.value = false
  form.reset()
  console.log(form);
  
  showModal.value = true
}

function openEditModal(task) {
  isEditMode.value = true
  editingTask.value = task
  form.id = task.id
  form.title = task.title
  form.description = task.description
  form.status = task.status
  showModal.value = true
}
const selectedFilter = ref(props.filters?.filter || '')

function changeFilter() {
  router.get(route('tasks.index'), { filter: selectedFilter.value }, {
    preserveState: true,
    preserveScroll: true,
  })
}


const flashMessage = ref('')
const key = ref(1);

function submitForm() {
  if (isEditMode.value) {
    form.put(route('tasks.update', form.id), {
      onSuccess: () => {
        showModal.value = false;
        key.value += 1; // Force re-render of Toast component
        flashMessage.value = 'Task updated successfully!';
      },
    });
  } else {
    form.post(route('tasks.store'), {
      onSuccess: () => {
        showModal.value = false;
        key.value += 1; // Force re-render of Toast component
        flashMessage.value = 'Task created successfully!';
      },
    });
  }
  form.reset();
}

function deleteTask(id) {
  if (confirm('Are you sure?')) {
    router.delete(route('tasks.destroy', id))
    key.value += 1; // Force re-render of Toast component
    flashMessage.value = 'Task deleted successfully!'
  }
}

function limitString(str, maxLength = 20) {
  return str.length > maxLength ? str.slice(0, maxLength) + '...' : str;
}

const incompleteTasks = computed(() => props.tasks?.filter(t => t.status === 'Incomplete'))
const doneTasks = computed(() => props.tasks?.filter(t => t.status === 'Complete'))
const todos = computed(() => props.tasks?.filter(t => t.status === 'Todo'))
</script>

<template>
  <Head title="Tasks" />
    <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-800">Tasks</h2>
          <Toast v-if="flashMessage" :key :message="flashMessage" type="success" />
          <div class="flex justify-between items-center gap-4">
            <!-- Filter Dropdown -->
            <div>
              <select
                v-model="selectedFilter"
                @change="changeFilter"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Filter Status</option>
                <option v-for="value in status" :key="value" :value="value">
                  {{ value }}
                </option>
              </select>
            </div>

            <!-- New Task Button -->
            <div>
              <button
                @click="openCreateModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
              >
                <i class="fa-solid fa-plus"></i>
                New Task
              </button>
            </div>
          </div>
        </div>

        <!-- Task Board -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

           <!-- TODO -->
        <div class="light-blue p-4 rounded-lg">
        <h2 class="text-lg font-semibold mb-4 flex items-center text-blue-800">
          <i class="fa-regular fa-square mr-2"></i>
          To do
        </h2>
        <div v-for="task in todos" :key="task.id" class="bg-white rounded-lg shadow mb-3 p-4">
          <div class="flex items-start">
            <div class="flex-1">
              <p class="text-gray-700 font-medium">{{ limitString(task.title, 20) }}</p>
              <p class="text-gray-500 text-sm">{{ limitString(task.description, 20) }}</p>
            </div>
            <div class="flex space-x-2 ml-4">
              <button @click="openEditModal(task)" class="text-gray-500 hover:text-blue-600">
                <i class="fas fa-pen"></i>
              </button>
              <button @click="deleteTask(task.id)" class="text-gray-500 hover:text-red-600">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
        </div>

        <!-- In complete -->
        <div class="peach-light p-4 rounded-lg">
        <h2 class="text-lg font-semibold mb-4 flex items-center text-[#8F4F00]">
          <i class="fa-solid fa-hourglass-half mr-2"></i>
          In complete
        </h2>
        <div v-for="task in incompleteTasks" :key="task.id" class="bg-white rounded-lg shadow mb-3 p-4">
          <div class="flex items-start">
            <div class="flex-1">
              <p class="text-gray-700 font-medium">{{ limitString(task.title, 20) }}</p>
              <p class="text-gray-500 text-sm">{{ limitString(task.description, 20) }}</p>
            </div>
            <div class="flex space-x-2 ml-4">
              <button @click="openEditModal(task)" class="text-gray-500 hover:text-blue-600">
                <i class="fas fa-pen"></i>
              </button>
              <button @click="deleteTask(task.id)" class="text-gray-500 hover:text-red-600">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
        </div>

        <!-- Done -->
         <div class="orange-light 200 p-4 rounded-lg">
        <h2 class="text-lg font-semibold mb-4 flex items-center text-[#81290E]">
         <i class="fa-regular fa-square-check mr-2"></i>
          Done
        </h2>
        <div v-for="task in doneTasks" :key="task.id" class="bg-white rounded-lg shadow mb-3 p-4">
          <div class="flex items-start">
            <div class="flex-1">
              <p class="text-gray-700 font-medium">{{ limitString(task.title, 20) }}</p>
              <p class="text-gray-500 text-sm">{{ limitString(task.description, 20) }}</p>
            </div>
            <div class="flex space-x-2 ml-4">
              <button @click="openEditModal(task)" class="text-gray-500 hover:text-blue-600">
                <i class="fas fa-pen"></i>
              </button>
              <button @click="deleteTask(task.id)" class="text-gray-500 hover:text-red-600">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
        </div>
        </div>

        <!-- Modal with backdrop -->
        <Transition
        enter-active-class="transition-opacity duration-300 ease-out"
        leave-active-class="transition-opacity duration-200 ease-in"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
        >
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <!-- Modal Container -->
            <Transition
            enter-active-class="transition-all duration-300 ease-out"
            leave-active-class="transition-all duration-200 ease-in"
            enter-from-class="opacity-0 scale-95 translate-y-4"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-4"
            >
            <div 
                v-if="showModal"
                class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden"
                @click.stop
            >
                <!-- Gradient Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        :d="isEditMode ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M12 4v16m8-8H4'" />
                    </svg>
                    {{ isEditMode ? 'Edit Task' : 'Create New Task' }}
                </h2>
                <p class="text-blue-100 text-sm mt-1">
                    {{ isEditMode ? 'Update your task details below' : 'Fill in the details to create a new task' }}
                </p>
                </div>

                <!-- Form Content -->
                <form @submit.prevent="submitForm" class="px-8 py-6 space-y-6">
                <!-- Title Field -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    Title
                    <span class="text-red-500">*</span>
                    </label>
                    <input 
                    v-model="form.title" 
                    type="text"
                    placeholder="Enter task title..."
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-200 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 placeholder:text-gray-400"
                    :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500/10': form.errors.title }"
                    />
                    <Transition
                    enter-active-class="transition-all duration-200"
                    leave-active-class="transition-all duration-150"
                    enter-from-class="opacity-0 -translate-y-1"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-1"
                    >
                    <div v-if="form.errors.title" class="flex items-center gap-1.5 text-sm text-red-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ form.errors.title }}
                    </div>
                    </Transition>
                </div>

                <!-- Description Field -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Description
                    </label>
                    <textarea 
                    v-model="form.description"
                    rows="4"
                    placeholder="Add a description for your task..."
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl transition-all duration-200 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 placeholder:text-gray-400 resize-none"
                    ></textarea>
                </div>

                <!-- Status Field -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Status
                    </label>
                    <div class="relative">
                    <select 
                        v-model="form.status"
                        class="w-full px-4 py-3 pr-10 border-2 border-gray-200 rounded-xl appearance-none transition-all duration-200 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 cursor-pointer bg-white"
                    >
                         <option v-for="value in status" :key="value" :value="value">
                           {{ value }}
                         </option>
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button 
                    type="button" 
                    @click="showModal = false"
                    class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold transition-all duration-200 hover:bg-gray-50 hover:border-gray-400 hover:shadow-md active:scale-95"
                    >
                    Cancel
                    </button>
                    <button 
                    type="submit"
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/50 hover:scale-105 active:scale-95"
                    >
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ isEditMode ? 'Update Task' : 'Create Task' }}
                    </span>
                    </button>
                </div>
                </form>

                <!-- Close Button -->
                <button
                @click="showModal = false"
                class="absolute top-6 right-6 text-white/80 hover:text-white transition-colors duration-200 hover:rotate-90 transform"
                >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                </button>
            </div>
            </Transition>
        </div>
        </Transition>
    </div>
    </AppLayout>
</template>

<style scoped>
.peach-light {
  background-color: #FFE4C2;
}
.light-blue {
  background-color: #CAD9F6;
}
.orange-light {
  background-color: #FAD0C6;
}
</style>
