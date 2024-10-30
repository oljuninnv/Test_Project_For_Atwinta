<template>
  <div class="w-full">
    <div class="flex flex-wrap items-center w-full p-4 justify-center">
      <h1 class="mb-5">
        <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Отделы:</span>
      </h1>
      <SearchInput @search="filterDepartments" class="search_input" />
      <button v-if="!showForm" @click="showForm = !showForm" class="btn">Добавить запись</button>
      <button v-if="showForm" @click="showForm = !showForm" class="btn">Убрать форму</button>
    </div>

    <!-- Форма добавления отдела -->
    <div v-if="showForm" class="p-4 bg-gray-100 rounded-md mb-4">
      <AdminAddDepartment @department-added="handleDepartmentAdded" />
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-[5%]">
      <table class="table">
        <thead class="thead">
          <tr>
            <th class="px-6 py-3">Название отдела</th>
            <th class="px-6 py-3">Текущие должности</th>
            <th scope="col" class="px-6 py-3"><span>Действия</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="department in filteredDepartments" :key="department.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ department.department_name }}</th>
            <td class="px-6 py-4">
              {{ department.positions.join(', ') }}
            </td>
            <td class="px-6 py-4 text-right">
              <ul class="flex gap-5 text-right">
                <li><a href="#" @click.prevent="editDepartment(department)" class="action_href">Edit</a></li>
                <li><a href="#" @click.prevent="deleteDepartment(department.department_id)" class="action_href">Delete</a></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <AdminEditDepartment
      :department="selectedDepartment"
      :isVisible="isModalVisible" 
      @close="isModalVisible = false" 
      @update="handleUpdateDepartment"
    />

    <!-- Пагинация -->
    <div class="pagination">
      <button @click="prevPage" :disabled="currentPage === 1" class="btn">Назад</button>
      <span class="mx-2">Страница {{ currentPage }} из {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="btn">Вперед</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from '../../../libs/axios';
import SearchInput from "../../SearchInput.vue";
import AdminAddDepartment from './AdminAddDepartment.vue';
import AdminEditDepartment from './AdminEditDepartment.vue';

const showForm = ref(false);
const departments = ref([]);
const isModalVisible = ref(false);
const selectedDepartment = ref(null);
const currentPage = ref(1);
const itemsPerPage = 2;
const searchQuery = ref('');

const totalPages = computed(() => filteredDepartments.value.length < 2 ? 1 : Math.ceil(departments.value.length / itemsPerPage));

const filteredDepartments = computed(() => {
  return departments.value.filter(department => 
    department.department_name.toLowerCase().includes(searchQuery.value.toLowerCase())
  ).slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
});

function filterDepartments(query) {
  searchQuery.value = query;
  currentPage.value = 1; // Сбросить на первую страницу при новом поиске
}

function handleDepartmentAdded(department) {
  departments.value.push(department);
}

async function deleteDepartment(id) {
  await axios.delete(`api/departments/${id}`);
  fetchDepartments()
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

// Получение данных из API
async function fetchDepartments() {
  try {
    const response = await axios.get('api/departments_information');
    departments.value = response.data; // Предполагается, что данные приходят в нужном формате
    console.log(departments.value);
  } catch (error) {
    console.error('Ошибка при получении данных:', error);
  }
}
// Вызов функции получения данных при монтировании компонента
onMounted(() => {
  fetchDepartments();
});

function editDepartment(department) {
  selectedDepartment.value = department;
  isModalVisible.value = true;
}

function handleUpdateDepartment(updatedDepartment) {
  console.log(updatedDepartment);
  if (updatedDepartment) {
    fetchDepartments();
  }
  isModalVisible.value = false;
}

</script>