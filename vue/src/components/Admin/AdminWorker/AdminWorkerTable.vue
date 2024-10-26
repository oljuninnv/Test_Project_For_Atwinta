<template class="w-full">
  <div class="flex flex-wrap items-center w-full p-4 justify-center">
    <h1 class="mb-5">
      <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Работники:</span>
    </h1>
    <SearchInput @search="filterWorkers" class="w-full max-w-md mb-4" />
    <button @click="showForm = !showForm" class="btn">Добавить запись</button>
  </div>

  <!-- Форма добавления работника -->
  <div v-if="showForm" class="p-4 bg-gray-100 rounded-md mb-4">
    <AdminAddWorker @worker-added="handleWorkerAdded" @close="showForm = false"></AdminAddWorker>
  </div>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-[5%]">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th class="px-6 py-3">Пользователь</th>
          <th class="px-6 py-3">Должность</th>
          <th class="px-6 py-3">Отдел</th>
          <th class="px-6 py-3">Принят</th>    
          <th class="px-6 py-3">Действия</th>                
        </tr>
      </thead>
      <tbody>
        <tr v-for="worker in filteredWorkers" :key="worker.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><a href="#">{{ worker.userId }}</a></th>
          <td class="px-6 py-4">{{ worker.positionId }}</td>
          <td class="px-6 py-4">{{ worker.departmentId }}</td>
          <td class="px-6 py-4">{{ worker.hireDate }}</td>
          <td class="px-6 py-4 text-right">
            <ul class="flex gap-5 text-right">
              <li><a href="#" @click.prevent="editWorker(worker)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a></li>
              <li><a href="#" @click.prevent="deleteWorker(worker.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a></li>
            </ul>                   
          </td>
        </tr>
      </tbody>
    </table>
  </div>

    <!-- Пагинация -->
    <div class="flex justify-center mt-4">
      <button @click="prevPage" :disabled="currentPage === 1" class="btn">Назад</button>
      <span class="mx-2">Страница {{ currentPage }} из {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="btn">Вперед</button>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import SearchInput from "../../SearchInput.vue";
import AdminAddWorker from './AdminAddWorker.vue';

const showForm = ref(false);
const workers = ref([
  { id: 1, userId: 'Иван Иванов', positionId: 'Руководитель отдела', departmentId: 'Отдел качества', hireDate: '24.10.2018' },
  { id: 2, userId: 'Петр Петров', positionId: 'Менеджер', departmentId: 'Отдел продаж', hireDate: '15.05.2020' },
  { id: 3, userId: 'Марат Иванов', positionId: 'Разработчик', departmentId: 'IT-отдел', hireDate: '15.05.2020' },

]);

const currentPage = ref(1);
const itemsPerPage = 2;
const searchQuery = ref('');

const totalPages = computed(() => filteredWorkers.value.length < 2 ? 1 : Math.ceil(workers.value.length / itemsPerPage));

const filteredWorkers = computed(() => {
  console.log("Filtered Workers:", workers.value);
  return workers.value.filter(worker => 
    worker.userId.toLowerCase().includes(searchQuery.value.toLowerCase())
  ).slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
});

function filterWorkers(query) {
  console.log("Search Query:", query);
  searchQuery.value = query;
  currentPage.value = 1; // Сбросить на первую страницу при новом поиске
}

function handleWorkerAdded(worker) {
  workers.value.push(worker); // Добавляем нового работника в массив
}

function editWorker(worker) {
  // Логика редактирования работника
}

function deleteWorker(id) {
  workers.value = workers.value.filter(worker => worker.id !== id);
}

function prevPage() {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
}

function nextPage() {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
}
</script>

<style scoped>
/* Добавьте стили при необходимости */
.btn {
  padding: 0.5rem 1rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
}
.btn:disabled {
  background-color: #ccc;
}
</style>
