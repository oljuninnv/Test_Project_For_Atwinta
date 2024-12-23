<template class="w-full">
  <div class="flex flex-wrap items-center w-full p-4 justify-center">
    <h1 class="mb-5">
      <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Администраторы:</span>
    </h1>
    <SearchInput @search="filterUsers" class="search_input" />
    <button @click="openAddAdminModal" class="btn">Добавить запись</button>
  </div>

  <AdminAdd v-if="isAddModalVisible" @close="isAddModalVisible = false" @user-added="refreshUsers" />

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

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
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
          <th class="px-6 py-3">Имя пользователя</th>
          <th class="px-6 py-3">Роль</th>
          <th class="px-6 py-3">Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="bg-white border-b">
          <td class="px-6 py-4">
            <button @click="openModal(user)">{{ user.name }}</button>
          </td>
          <td class="px-6 py-4">Admin</td>
          <td class="px-6 py-4">
            <a href="#" @click.prevent="removeUser(user.id)" class="action_href">Delete</a>
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

  <AdminGetUserInformation :isVisible="isVisible" :formData="formData" @close="isVisible = false" />

</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import SearchInput from "../../SearchInput.vue";
import AdminGetUserInformation from '../AdminGetUserInformation.vue';
import AdminAdd from './AdminAdd.vue';
import { GetAdmins, } from '../../../services/api/auth';
import axios from '../../../libs/axios';

const pagination = ref({
  page: 1,
  per_page: 1,
  total: 1,
  last_page: 1,
});

const updateItemsPerPage = (event) => {
    pagination.value.per_page = parseInt(event.target.value);
    loadUsers(1);
};


const loading = ref(false);

const showForm = ref(false);
const users = ref([]);
const isAddModalVisible = ref(false);
const isVisible = ref(false);

const formData = ref({
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

function openModal(user) {
  console.log(user);
  formData.value = { ...user }; // Копируем данные пользователя в форму
  isVisible.value = true; // Открываем модальное окно
}

function openAddAdminModal() {
  isAddModalVisible.value = true; // Открываем модальное окно для добавления администратора
}

function refreshUsers() {
  isAddModalVisible.value = false;
  loadUsers();
}


const loadUsers = async (page = 1, name = null) => {
  try {
    loading.value = true;  // Показываем загрузку
    const response = await GetAdmins(page, pagination.value.per_page, name);
    console.log(response);
    users.value = response.data;
    console.log(users);
    pagination.value.total = response.meta.total;
    pagination.value.last_page = response.meta.last_page;
    console.log(users.value);
    loading.value = false; // Скрываем загрузку

  } catch (error) {
    console.error('Ошибка при загрузке пользователей:', error);
  }
};

onMounted(loadUsers);

const currentPage = ref(1);
const itemsPerPage = 2;
const searchQuery = ref('');

async function filterUsers(query) {
  console.log("Search Query:", query);
  await loadUsers(1, query);
}

async function prevPage() {
  if (pagination.value.page > 1) {
    await loadUsers(pagination.value.page - 1);
    pagination.value.page = pagination.value.page - 1;
  }
}

async function nextPage() {
  if (pagination.value.page < pagination.value.last_page) {
    await loadUsers(pagination.value.page + 1);
    pagination.value.page = pagination.value.page + 1;
  }
}

const removeUser = async (id) => {
  try {

    await axios.delete(`/api/delete_admin/${id}`)
  } catch (error) {
    console.log(id);
        console.error('Ошибка при удалении пользователя:', error);
    }
    await loadUsers();
  }

  </script>