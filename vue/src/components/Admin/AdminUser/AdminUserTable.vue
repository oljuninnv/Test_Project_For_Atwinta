<template class="w-full">
  <div class="flex flex-wrap items-center w-full p-4 justify-center">
    <h1 class="mb-5">
      <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Пользователи:</span>
    </h1>
    <SearchInput @search="filterUsers" class="search_input" />
    <button v-if="!showForm" @click="showForm = !showForm" class="btn">Добавить запись</button>
    <button v-if="showForm" @click="showForm = !showForm" class="btn">Убрать форму</button>
  </div>

  <!-- Форма добавления пользователя -->
  <div v-if="showForm" class="p-4 bg-gray-100 rounded-md mb-4">
    <AdminAddUser @UserAdd="handleUserAdded"></AdminAddUser>
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

  <div class="relative overflow-hidden shadow-md sm:rounded-lg mt-[5%]">
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
    <div class="h-full w-full overflow-x-scroll">
      <table class="table overflow-x-scroll">
        <thead class="thead">
          <tr>
            <th class="px-6 py-3">Имя пользователя</th>
            <th class="px-6 py-3">Почта</th>
            <th class="px-6 py-3">Логин</th>
            <th class="px-6 py-3">Изображение</th>
            <th class="px-6 py-3">Город</th>
            <th class="px-6 py-3">Телефон</th>
            <th class="px-6 py-3">Telegram</th>
            <th class="px-6 py-3">Закончено ли тестовое задание?</th>
            <th class="px-6 py-3">День рождения</th>
            <th class="px-6 py-3">Тип</th>
            <th class="px-6 py-3">GitHub</th>
            <th scope="col" class="px-6 py-3"><span>Действия</span></th>
          </tr>
        </thead>
        <tbody class="overflow-x-scroll">
          <tr v-for="user in users" :key="user.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ user.name
              }}</th>
            <td class="px-6 py-4">{{ user.email }}</td>
            <td class="px-6 py-4">{{ user.login }}</td>
            <td class="px-6 py-4">
              {{ user.image }}
            </td>
            <td class="px-6 py-4">{{ user.city }}</td>
            <td class="px-6 py-4">{{ user.phone }}</td>
            <td class="px-6 py-4">{{ user.telegram }}</td>
            <td class="px-6 py-4">{{ user.is_finished===1 ? 'True' : 'False'}}</td>
            <td class="px-6 py-4">{{ user.birthday }}</td>
            <td class="px-6 py-4">{{ user.type }}</td>
            <td class="px-6 py-4">{{ user.github }}</td>
            <td class="px-6 py-4 text-right">
              <ul class="flex gap-5 text-right">
                <li><a href="#" @click.prevent="editUser(user)" class="action_href">Edit</a></li>
                <li><a href="#" @click.prevent="removeUser(user.id)" class="action_href">Delete</a></li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>

  <!-- Пагинация -->
  <div class="flex justify-center flex-col items-center mb-4 mt-5">
    <div class="pagination">
        <button @click="prevPage" :disabled="pagination.page === 1 || loading" class="btn">Назад</button>
        <span class="mx-2">Страница {{ pagination.page }} из {{ pagination.last_page }}</span>
        <button @click="nextPage" :disabled="pagination.page === pagination.last_page || loading" class="btn">Вперед</button>
    </div>
</div>

  <AdminEditUser :user="selectedUser" :isVisible="isModalVisible" @close="isModalVisible = false"
    @user-updated="fetchUsers" :errors="errors" />

</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import SearchInput from "../../SearchInput.vue";
import AdminAddUser from './AdminAddUser.vue';
import AdminEditUser from './AdminEditUser.vue';
import { GetUsers, DeleteUser, updateUser } from '../../../services/api/auth';
import axios from '../../../libs/axios';

const showForm = ref(false);
const users = ref([]);
const loading = ref(false);
const errors = ref([]);

const pagination = ref({
  page: 1,
  per_page: 3,
  total: 1,
  last_page: 1,
});

const loadUsers = async (page = 1, name = null) => {
  try {
    loading.value = true;  // Показываем загрузку
    const response = await GetUsers(page, pagination.value.per_page, name);
    console.log(response);
    users.value = response.data;
    pagination.value.total = response.meta.total;
    pagination.value.last_page = response.meta.last_page;
    console.log(pagination.value.last_page);
    loading.value = false; // Скрываем загрузку

  } catch (error) {
    console.error('Ошибка при загрузке пользователей:', error);
  }
};

onMounted(async () => {
  await loadUsers();
});

const updateItemsPerPage = (event) => {
    pagination.value.per_page = parseInt(event.target.value);
    loadUsers(1);
};

async function filterUsers(query) {
  console.log("Search Query:", query);
  await loadUsers(1, query);
}

async function handleUserAdded() {
  await loadUsers(1);
}

const isModalVisible = ref(false);
const selectedUser = ref(null);

// Функция для открытия модального окна
function editUser(user) {
  selectedUser.value = { ...user }; // Устанавливаем выбранного пользователя (создаем копию)
  isModalVisible.value = true;
}

const fetchUsers = async (updatedUser) => {
  try {
    const formData = new FormData();
    formData.append('name', updatedUser.name);
    formData.append('email', updatedUser.email);
    formData.append('phone', updatedUser.phone);
    formData.append('city', updatedUser.city);
    formData.append('birthday', updatedUser.birthday);
    formData.append('telegram', updatedUser.telegram);
    formData.append('github', updatedUser.github);
    formData.append('type', updatedUser.type);
    formData.append('about', updatedUser.about);
    formData.append('login', updatedUser.login);
   
    if (updatedUser.is_finished) {
      formData.append('is_finished', updatedUser.is_finished);
    }
    if (updatedUser.image != null) {
      formData.append('image', updatedUser.image, updatedUser.image.name);
    }
    formData.append('_method', 'put');

    errors.value =[];
    const response = await axios.post(`/api/users/${updatedUser.id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    // console.log(response);

    isModalVisible.value = false;
  } catch (error) {
    console.log(error);
    if (error.response) {
            switch (error.response.status) {
              case 422:
                for (const [key, messages] of Object.entries(error.response.data.errors)) {
                  errors.value.push(...messages);
              }
                break;
              default:
                alert('Произошла неизвестная ошибка. Попробуйте позже.');
                console.error('Произошла ошибка:', error);
            }
          } else {
            alert('Проблема с сетью. Проверьте подключение к интернету.');
            console.error('Ошибка сети:', error);
          }
  }
  await loadUsers();
};

const removeUser = async (userId) => {
  try {
    await DeleteUser(Number(userId)); // Вызываем API для удаления пользователя
  } catch (error) {
    console.error('Ошибка при удалении пользователя:', error);
  }
  await loadUsers();
};

async function prevPage() {
  if (pagination.value.page > 1) {
    await loadUsers(pagination.value.page - 1);
    pagination.value.page = pagination.value.page - 1;
  }
}

function getErrors (){
  console.log(errors.value);
  return errors.value;
}

async function nextPage() {
  if (pagination.value.page < pagination.value.last_page) {
    await loadUsers(pagination.value.page + 1);
    pagination.value.page = pagination.value.page + 1;
  }
}
</script>