<template>
    <div class="w-full">
      <div class="flex flex-wrap items-center w-full p-4 justify-center">
        <h1 class="mb-5">
          <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Тестовые задания:</span>
        </h1>
        <SearchInput @search="filtertest_taskes" class="search_input" />
        <button v-if="!showForm" @click="showForm = !showForm" class="btn">Добавить запись</button>
        <button v-if="showForm" @click="showForm = !showForm" class="btn">Убрать форму</button>
      </div>
  
      <!-- Форма добавления отдела -->
      <div v-if="showForm" class="p-4 bg-gray-100 rounded-md mb-4">
        <TestTaskesAdd @testTaskes-added="handleTestTaskesAdded" />
      </div>
  
      <div class="flex justify-center">
          <label for="itemsPerPage">Элементов на странице:</label>
          <select id="itemsPerPage" @change="updateItemsPerPage">
              <option value="3">3</option>
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="50">50</option>
          </select>
      </div>
  
      <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-[5%]">
        <div role="status" v-if="loading"
          class="absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2 w-full flex h-full flex-wrap justify-center items-center bg-[#fff] overflow-hidden	">
          <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="currentColor" />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentFill" />
          </svg>
          <span class="sr-only">Loading...</span>
        </div>
        <table class="table">
          <thead class="thead">
            <tr>
              <th class="px-6 py-3">Название</th>
              <th class="px-6 py-3">Ссылка на задание</th>
              <th class="px-6 py-3">Продолжительность выполнения (в неделях)</th>
              <th scope="col" class="px-6 py-3"><span>Действия</span></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="test_task in filteredtest_taskes" :key="test_task.id"
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
              <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                test_task.name }}</th>
              <td class="px-6 py-4">
                {{test_task.file}}
              </td>
              <td class="px-6 py-4">
                {{test_task.time_limit_in_weeks}}
              </td>
              <td class="px-6 py-4 text-right">
                <ul class="flex gap-5 text-right">
                  <li><a href="#" @click.prevent="editTestTask(test_task)" class="action_href">Edit</a></li>
                  <li><a href="#" @click.prevent="deleteTestTask(test_task.id)"
                      class="action_href">Delete</a></li>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <TestTaskesEdit :test_task="selectedTestTask" :isVisible="isModalVisible" @close="isModalVisible = false"
        @update="handleUpdateTestTask" />
  
      <!-- Пагинация -->
      <div class="pagination">
        <button @click="prevPage" :disabled="pagination.page === 1 || loading == true" class="btn">Назад</button>
        <span class="mx-2">Страница {{ pagination.page }} из {{ pagination.last_page }}</span>
        <button @click="nextPage" :disabled="pagination.page === pagination.last_page || loading === true"
          class="btn">Вперед</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import axios from '../../../libs/axios';
  import SearchInput from "../../SearchInput.vue";
  import TestTaskesAdd from './TestTaskesAdd.vue';
  import TestTaskesEdit from './TestTaskesEdit.vue';
  import { GetTaskes } from '../../../services/api/test_taskes';
  
  const showForm = ref(false);
  const test_taskes = ref([]);
  const isModalVisible = ref(false);
  const selectedTestTask = ref(null);
  const searchQuery = ref('');
  
  const updateItemsPerPage = (event) => {
      pagination.value.per_page = parseInt(event.target.value);
      fetchtest_taskes(1);
  };
  
  const pagination = ref({
    page: 1,
    per_page: 3,
    total: 1,
    last_page: 1,
  });
  
  const loading = ref(false);
  
  const filteredtest_taskes = computed(() => {
    if (!Array.isArray(test_taskes.value)) {
      return test_taskes.value = [];
    }
    
    return test_taskes.value.filter(test_task => {
      const testTaskName = test_task.name || '';
      return testTaskName.toLowerCase().includes(searchQuery.value.toLowerCase());
    });
  });
  
  async function filtertest_taskes(query) {
    console.log("Search Query:", query);
    searchQuery.value = query; // Сохраняем поисковый запрос
    await fetchtest_taskes(1, query); // Передаем запрос на сервер
}
  
  function handleTestTaskesAdded() {
    fetchtest_taskes(1)
  }
  
  async function deleteTestTask(id) {
    await axios.delete(`api/test_taskes/${id}`);
    fetchtest_taskes()
  }
  
  async function prevPage() {
    if (pagination.value.page > 1) {
      await fetchtest_taskes(pagination.value.page - 1);
      pagination.value.page = pagination.value.page - 1;
    }
  }
  
  async function nextPage() {
    if (pagination.value.page < pagination.value.last_page) {
      await fetchtest_taskes(pagination.value.page + 1);
      pagination.value.page = pagination.value.page + 1;
    }
  }
  
  // Получение данных из API
  async function fetchtest_taskes(page = 1, name = null) {
    try {
        loading.value = true; // Показываем загрузку
        const response = await GetTaskes(page, pagination.value.per_page, name); // Передаем параметр name
        console.log(response);
        test_taskes.value = response.data; // Обновляем данные
        pagination.value.total = response.meta.total;
        pagination.value.last_page = response.meta.last_page;
        loading.value = false; // Скрываем загрузку
    } catch (error) {
        console.error('Ошибка при загрузке данных:', error);
        loading.value = false; // Скрываем загрузку даже при ошибке
    }
}
  // Вызов функции получения данных при монтировании компонента
  onMounted(() => {
    fetchtest_taskes();
  });
  
  function editTestTask(test_task) {
  selectedTestTask.value = { ...test_task }; // Копируем данные тестового задания
  isModalVisible.value = true; // Открываем модальное окно
}
  
  function handleUpdateTestTask(updatedTestTask) {
    console.log(updatedTestTask);
    if (updatedTestTask) {
      fetchtest_taskes(1, null);
    }
    isModalVisible.value = false;
  }
  </script>