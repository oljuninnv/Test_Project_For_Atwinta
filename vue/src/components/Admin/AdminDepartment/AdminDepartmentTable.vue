<template>
  <div class="w-full">
    <div class="flex flex-wrap items-center w-full p-4 justify-center">
      <h1 class="mb-5">
        <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Отделы:</span>
      </h1>
      <SearchInput @search="filterDepartments" class="w-full max-w-md mb-4" />
      <button @click="showForm = !showForm" class="btn">Добавить запись</button>
    </div>

    <!-- Форма добавления отдела -->
    <div v-if="showForm" class="p-4 bg-gray-100 rounded-md mb-4">
      <AdminAddDepartment @department-added="handleDepartmentAdded" />
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-[5%]">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th class="px-6 py-3">Название отдела</th>
            <th class="px-6 py-3">Должности</th>
            <th scope="col" class="px-6 py-3"><span>Действия</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="department in filteredDepartments" :key="department.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ department.name }}</th>
            <td class="px-6 py-4">
              <ul class="flex flex-wrap gap-1">
                <li v-for="position in department.positions" :key="position">{{ position }}</li>
              </ul>
            </td>
            <td class="px-6 py-4 text-right">
              <ul class="flex gap-5 text-right">
                <li><a href="#" @click.prevent="editDepartment(department)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a></li>
                <li><a href="#" @click.prevent="deleteDepartment(department.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a></li>
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
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import SearchInput from "../../SearchInput.vue";
import AdminAddDepartment from './AdminAddDepartment.vue';

const showForm = ref(false);
const departments = ref([]);

const currentPage = ref(1);
const itemsPerPage = 2;
const searchQuery = ref('');

const totalPages = computed(() => filteredDepartments.value.length < 2 ? 1 : Math.ceil(departments.value.length / itemsPerPage));

const filteredDepartments = computed(() => {
  console.log("Filtered Departments:", departments.value);
  return departments.value.filter(department => 
    department.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  ).slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
});

function filterDepartments(query) {
  console.log("Search Query:", query);
  searchQuery.value = query;
  currentPage.value = 1; // Сбросить на первую страницу при новом поиске
}

function handleDepartmentAdded(department) {
  departments.value.push(department);
}

function editDepartment(department) {
  console.log('Редактировать отдел:', department);
}

function deleteDepartment(id) {
  departments.value = departments.value.filter(department => department.id !== id);
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
