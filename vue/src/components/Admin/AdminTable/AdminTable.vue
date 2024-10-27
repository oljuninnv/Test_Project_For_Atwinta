<template class="w-full">
    <div class="flex flex-wrap items-center w-full p-4 justify-center">
      <h1 class="mb-5">
        <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Администраторы:</span>
      </h1>
      <SearchInput @search="filterUsers" class="w-full max-w-md mb-4" />
      <button @click="openAddAdminModal" class="btn">Добавить запись</button>
    </div>

    <AdminAdd 
      v-if="isAddModalVisible" 
      @close="isAddModalVisible = false"
      @user-added="refreshUsers" 
    />
  
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-5">
      <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
          <tr>
            <th class="px-6 py-3">Имя пользователя</th>
            <th class="px-6 py-3">Роль</th>
            <th class="px-6 py-3">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="userRole in filteredUsers" :key="userRole.id" class="bg-white border-b">
            <td class="px-6 py-4">
                <button @click="openModal(userRole.user)">{{ userRole.user.name }}</button>
            </td>
            <td class="px-6 py-4">{{ userRole.role.name }}</td>
            <td class="px-6 py-4">
                <a href="#" @click.prevent="removeUser(userRole)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a>
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

    <AdminGetUserInformation 
      :isVisible="isVisible" 
      :formData="formData" 
      @close="isVisible = false"
    />

  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import SearchInput from "../../SearchInput.vue";
  import AdminGetUserInformation from '../AdminGetUserInformation.vue';
  import AdminAdd from './AdminAdd.vue';
  import axios from '../../../libs/axios';
  
  const showForm = ref(false);
  const userRoles = ref([]);
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
    loadUserRoles();
  }


  const loadUserRoles = async () => {
  try {
    const response = await axios.get('/api/user_roles');
    userRoles.value = response.data.filter(user => user.role.name == 'Admin');

    // Если нужно делать что-то с ролями администраторов, вы можете добавить дополнительную логику здесь
    console.log('Роли для администратора:', userRoles.value);
    
  } catch (error) {
    console.error('Ошибка при загрузке пользователей:', error);
  }
};
  
  onMounted(loadUserRoles);

  const currentPage = ref(1);
  const itemsPerPage = 2;
  const searchQuery = ref('');
  
  const totalPages = computed(() => filteredUsers.value.length < 2 ? 1 : Math.ceil(userRoles.value.length / itemsPerPage));
  
  const filteredUsers = computed(() => {
    const filtered = userRoles.value.filter(userRole =>
      userRole.user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
    return filtered.slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
  });
  
  function filterUsers(query) {
    searchQuery.value = query;
    currentPage.value = 1; // Сброс на первую страницу при новом поиске
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
  
  const removeUser = async (userRole) => {
    try {
        await axios.put(`/api/user_roles/${userRole.id}`,userRole)
    } catch (error) {
      console.log(userId);
        console.error('Ошибка при удалении пользователя:', error);
    }
    await loadUserRoles();
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
  