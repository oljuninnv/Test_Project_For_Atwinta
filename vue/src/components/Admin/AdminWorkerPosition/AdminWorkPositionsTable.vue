<template class="w-full">
  <div class="flex flex-wrap items-center w-full p-4 justify-center">
    <h1 class="mb-5">
      <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Должности:</span>
    </h1>
    <SearchInput @search="filterPositions" class="search_input" />
    <button @click="openAddPositionModal" class="btn">Добавить запись</button>
  </div>

  <AdminWorkPositionModal v-if="isAddModalVisible" @close="isAddModalVisible = false"
    @position-added="refreshPositions" />

  <!-- Форма добавления должности -->
  <div v-if="showForm" class="p-4 bg-gray-100 rounded-md mb-4">
    <AdminAddPosition @PositionAdd="handlePositionAdded"></AdminAddPosition>
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
          <th class="px-6 py-3">ID</th>
          <th class="px-6 py-3">Название</th>
          <th scope="col" class="px-6 py-3"><span>Действия</span></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="position in positions" :key="position.id"
          class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
          <td class="px-6 py-4">{{ position.id }}</td>
          <td class="px-6 py-4">{{ position.name }}</td>
          <td class="px-6 py-4 text-right">
            <ul class="flex gap-5 text-right">
              <li><a href="#" @click.prevent="editPosition(position)" class="action_href">Edit</a></li>
              <li><a href="#" @click.prevent="removePosition(position.id)" class="action_href">Delete</a></li>
            </ul>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Пагинация -->
  <div class="pagination">
    <button @click="prevPage" :disabled="pagination.page === 1 || loading == true" class="btn">Назад</button>
    <span class="mx-2">Страница {{ pagination.page }} из {{ pagination.last_page }}</span>
    <button @click="nextPage" :disabled="pagination.page === pagination.last_page || loading === true"
      class="btn">Вперед</button>
  </div>

  <AdminEditPosition :position="selectedPosition" :isVisible="isModalVisible" @close="isModalVisible = false"
    @position-updated="fetchPositions" />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from '../../../libs/axios';
import SearchInput from "../../SearchInput.vue";
import AdminWorkPositionModal from "./AdminWorkPositionModal.vue";
import { GetPositions } from '../../../services/api/position';
import AdminEditPosition from "./AdminEditPosition.vue";

const isAddModalVisible = ref(false);
const showForm = ref(false);
const positions = ref([]);
const isModalVisible = ref(false);
const selectedPosition = ref(null);

const pagination = ref({
  page: 1,
  per_page: 3,
  total: 1,
  last_page: 1,
});

const updateItemsPerPage = (event) => {
    pagination.value.per_page = parseInt(event.target.value);
    fetchPositions(1);
};

const loading = ref(false);

onMounted(async () => {
  await fetchPositions();
});

async function fetchPositions(page = 1, name = null) {
  try {
    loading.value = true;  // Показываем загрузку
    const response = await GetPositions(page, pagination.value.per_page, name);
    console.log(response);
    positions.value = response.data;
    console.log(positions.value);
    pagination.value.total = response.total;
    pagination.value.last_page = response.last_page;
    console.log(positions.value);
    loading.value = false; // Скрываем загрузку

  } catch (error) {
    console.error('Ошибка при загрузке пользователей:', error);
  }
}

async function filterPositions(query) {
  console.log("Search Query:", query);
  await fetchPositions(1, query);
}

function handlePositionAdded() {
  fetchPositions(); 
}

// Функции редактирования и удаления
function editPosition(position) {
  console.log(position);
  selectedPosition.value = position;
  isModalVisible.value = true;
}

async function removePosition(id) {
  try {
    await axios.delete(`/api/positions/${id}`);
    fetchPositions(1);
  } catch (error) {
    console.error('Ошибка при удалении должности:', error);
  }
}

// Пагинация
async function prevPage() {
  if (pagination.value.page > 1) {
    await fetchPositions(pagination.value.page - 1);
    pagination.value.page = pagination.value.page - 1;
  }
}

async function nextPage() {
  if (pagination.value.page < pagination.value.last_page) {
    await fetchPositions(pagination.value.page + 1);
    pagination.value.page = pagination.value.page + 1;
  }
}

function openAddPositionModal() {
  isAddModalVisible.value = true;
}

function refreshPositions() {
  isAddModalVisible.value = false;
  fetchPositions(1);
  }

</script>