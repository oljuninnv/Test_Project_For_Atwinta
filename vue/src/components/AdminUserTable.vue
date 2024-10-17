<template class="w-full">
  <div class="flex flex-wrap items-center w-full p-4 justify-center">
    <h1 class="mb-5">
      <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Пользователи:</span>
    </h1>
    <SearchInput @search="filterUsers" class="w-full max-w-md mb-4" />
    <button @click="showForm = !showForm" class="btn">Добавить запись</button>
  </div>

  <!-- Форма добавления пользователя -->
  <div v-if="showForm" class="p-4 bg-gray-100 rounded-md mb-4">
    <AdminAddUser @user-added="handleUserAdded"></AdminAddUser>
  </div>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-[5%]">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th class="px-6 py-3">Имя пользователя</th>
          <th class="px-6 py-3">Почта</th>
          <th class="px-6 py-3">Логин</th>
          <th class="px-6 py-3">Изображение</th>
          <th class="px-6 py-3">Город</th>
          <th class="px-6 py-3">Телефон</th>
          <th class="px-6 py-3">День рождения</th>
          <th class="px-6 py-3">Тип</th>
          <th class="px-6 py-3">GitHub</th>
          <th scope="col" class="px-6 py-3"><span>Действия</span></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in filteredUsers" :key="user.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ user.name }}</th>
          <td class="px-6 py-4">{{ user.email }}</td>
          <td class="px-6 py-4">{{ user.login }}</td>
          <td class="px-6 py-4">{{ user.image }}</td>
          <td class="px-6 py-4">{{ user.city }}</td>
          <td class="px-6 py-4">{{ user.phone }}</td>
          <td class="px-6 py-4">{{ user.birthday }}</td>
          <td class="px-6 py-4">{{ user.type }}</td>
          <td class="px-6 py-4">{{ user.github }}</td>
          <td class="px-6 py-4 text-right">
            <ul class="flex gap-5 text-right">
              <li><a href="#" @click.prevent="editUser(user)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a></li>
              <li><a href="#" @click.prevent="deleteUser(user.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a></li>
            </ul>                     
          </td>
        </tr>
      </tbody>
    </table>

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
import SearchInput from "../components/SearchInput.vue";
import AdminAddUser from './AdminAddUser.vue';

const showForm = ref(false);
const users = ref([
  { 
    id: 1, 
    name: 'Иван Иванов', 
    email: 'ivan.ivanov@example.com', 
    login: 'ivan123', 
    image: 'https://example.com/images/ivan.jpg', 
    city: 'Москва', 
    phone: '+7 123 456 78 90', 
    birthday: '01.01.1990', 
    type: 'Backend', 
    github: 'https://github.com/ivan' 
  },
  { 
    id: 2, 
    name: 'Мария Петрова', 
    email: 'maria.petrova@example.com', 
    login: 'maria123', 
    image: 'https://example.com/images/maria.jpg', 
    city: 'Санкт-Петербург', 
    phone: '+7 987 654 32 10', 
    birthday: '15.05.1992', 
    type: 'Frontend', 
    github: 'https://github.com/maria' 
  },
  { 
    id: 3, 
    name: 'Сергей Сидоров', 
    email: 'sergey.sidorov@example.com', 
    login: 'sergey123', 
    image: 'https://example.com/images/sergey.jpg', 
    city: 'Екатеринбург', 
    phone: '+7 321 654 98 76', 
    birthday: '20.03.1988', 
    type: 'Fullstack', 
    github: 'https://github.com/sergey' 
  },
  { 
    id: 4, 
    name: 'Анна Кузнецова', 
    email: 'anna.kuznetsova@example.com', 
    login: 'anna123', 
    image: 'https://example.com/images/anna.jpg', 
    city: 'Казань', 
    phone: '+7 456 789 12 34', 
    birthday: '30.07.1995', 
    type: 'Designer', 
    github: 'https://github.com/anna' 
  },
  { 
    id: 5, 
    name: 'Дмитрий Смирнов', 
    email: 'dmitry.smirnov@example.com', 
    login: 'dmitry123', 
    image: 'https://example.com/images/dmitry.jpg', 
    city: 'Новосибирск', 
    phone: '+7 654 321 09 87', 
    birthday: '10.11.1991', 
    type: 'DevOps', 
    github: 'https://github.com/dmitry' 
  }
]);

const currentPage = ref(1);
const itemsPerPage = 2;
const searchQuery = ref('');

const totalPages = computed(() => filteredUsers.value.length < 2 ? 1 : Math.ceil(users.value.length / itemsPerPage));

const filteredUsers = computed(() => {
  console.log("Filtered Users:", users.value);
  return users.value.filter(user => 
    user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  ).slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
});

function filterUsers(query) {
  console.log("Search Query:", query);
  searchQuery.value = query;
  currentPage.value = 1; // Сбросить на первую страницу при новом поиске
}

function handleUserAdded(user) {
  users.value.push(user); // Добавляем нового пользователя в массив
}
function editUser(user) {
  // Логика редактирования пользователя
}

function deleteUser(id) {
  users.value = users.value.filter(user => user.id !== id);
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
  