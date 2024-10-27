<template class="w-full">
  <div class="flex flex-wrap items-center w-full p-4 justify-center">
    <h1 class="mb-5">
      <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Работники:</span>
    </h1>
    <SearchInput @search="filterWorkers" class="w-full max-w-md mb-4" />
    <button v-if="!showForm" @click="showForm = !showForm" class="btn">Добавить запись</button>
    <button v-if="showForm" @click="showForm = !showForm" class="btn">Убрать форму</button>
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
          <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            <button @click="openModal(worker.user)">{{ worker.user.name }}</button>
          </td>
          <td class="px-6 py-4">{{ worker.position.name }}</td>
          <td class="px-6 py-4">{{ worker.department.name }}</td>
          <td class="px-6 py-4">{{ worker.adopted_at }}</td>
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

  <!-- Модальное окно для просмотра информации о пользователе -->
  <AdminGetUserInformation 
      :isVisible="isModalVisible" 
      :formData="formData" 
      @close="isModalVisible = false"
    />

  <!-- Модальное окно для редактирования работника -->
  <AdminEditWorker 
    :workerData="selectedWorker"
    :isVisible="isEditModalVisible"
    @close="isEditModalVisible = false"
    @worker-updated="fetchWorkers"
  />
</template>

<script setup>
import SearchInput from "../../SearchInput.vue";
import AdminAddWorker from './AdminAddWorker.vue';
import AdminEditWorker from "./AdminEditWorker.vue";
import AdminGetUserInformation from "../AdminGetUserInformation.vue";
import { ref, computed, onMounted } from 'vue';
import axios from '../../../libs/axios';

const showForm = ref(false);
const workers = ref([]);
const selectedWorker = ref(null);
const isModalVisible = ref(false);
const isEditModalVisible = ref(false);
const currentPage = ref(1);
const itemsPerPage = 2;
const searchQuery = ref('');

const formData = ref({
    id:null,
    name: '',
    login: '',
    email: '',
    phone: '',
    city: '',
    birthday: '',
    github: '',
    type: '',
    about: ''
    });

  const totalPages = computed(() => filteredWorkers.value.length < 2 ? 1 : Math.ceil(workers.value.length / itemsPerPage));

const filteredWorkers = computed(() => {
  const filtered = workers.value.filter(worker =>
    worker.user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
    return filtered.slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
});

// Открытие модального окна с информацией о пользователе
function openModal(user) {
  formData.value = { ...user }; // Копируем данные пользователя в форму
  console.log(formData.value);
  isModalVisible.value = true; // Открываем модальное окно
}

// Обработка добавления работника
function handleWorkerAdded(worker) {
  filteredWorkers.value.push(worker);
  fetchWorkers(); // Перезагрузить список работников после добавления
}

// Редактирование работника
function editWorker(worker) {
  selectedWorker.value = worker;
  isEditModalVisible.value = true; // Открываем модальное окно редактирования
}

// Удаление работника
async function deleteWorker(id) {
  try {
    await axios.delete(`/api/workers/${id}`);
    fetchWorkers(); // Обновляем список после удаления
  } catch (error) {
    console.error('Ошибка при удалении работника:', error);
  }
}

// Пагинация
const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

// Загрузка работников при монтировании компонента
async function fetchWorkers() {
  try {
    const response = await axios.get('/api/workers');
    workers.value = response.data;
    console.log(workers.value);
  } catch (error) {
    console.error('Ошибка при загрузке работников:', error);
  }
}

onMounted(fetchWorkers);

function filterWorkers(query) {
  searchQuery.value = query;
    currentPage.value = 1; // Сброс на первую страницу при новом поиске
}
</script>

<style scoped>
.btn {
  padding: 0.5rem 1rem;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 0.25rem;
}
.btn:disabled {
  background-color: #ccc;
}
</style>
