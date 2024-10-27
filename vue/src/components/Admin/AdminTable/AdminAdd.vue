<template>
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
        <h2 class="text-lg font-semibold text-gray-900">Добавить администратора</h2>
        <div class="relative mt-2">
          <input
            type="text"
            v-model="searchTerm"
            @input="filterUsers"
            @focus="isDropdownOpen = true"
            @blur="closeDropdown"
            placeholder="Поиск пользователя..."
            class="input_text w-full border border-gray-300 rounded-md p-2"
          />
          <div v-if="isDropdownOpen && filteredUsers.length > 0" class="absolute z-10 bg-white border border-gray-300 rounded-md mt-1 w-full">
            <ul>
              <li
                v-for="user in filteredUsers"
                :key="user.id"
                @mousedown.prevent ="selectUser(user)" 
                class="cursor-pointer p-2 hover:bg-gray-100"
              >
                {{ user.name }} 
              </li>
            </ul>
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
          <button type="button" @click="addAdmin" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2">Добавить</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted,defineEmits } from 'vue';
  import axios from '../../../libs/axios';
  
  const emit = defineEmits(['user-added']);
  const isVisible = ref(true);
  const searchTerm = ref('');
  const isDropdownOpen = ref(false);
  const users = ref([]);
  const filteredUsers = ref([]);
  const formData = ref({ user_id: null, role_id: 1 });
  
  onMounted(async () => {
    await fetchUsers();
  });
  
  async function fetchUsers() {
    try {
      const usersResponse = await axios.get('/api/users'); 
      const rolesResponse = await axios.get('/api/user_roles'); 
  
      const adminUserIds = new Set(rolesResponse.data.filter(item => item.role.name === "Admin").map(role => role.user_id));
  
      users.value = usersResponse.data.response.filter(user => !adminUserIds.has(user.id));
      filteredUsers.value = users.value; 
    } catch (error) {
      console.error('Ошибка при получении пользователей:', error);
    }
  }
  
  function filterUsers() {
    const term = searchTerm.value.toLowerCase();
    if (term) {
      filteredUsers.value = users.value.filter(user =>
        user.name.toLowerCase().includes(term)
      );
      isDropdownOpen.value = filteredUsers.value.length > 0;
    } else {
      filteredUsers.value = users.value;
      isDropdownOpen.value = false;
    }
  }
  
  function closeDropdown() {
    isDropdownOpen.value = false;
  }
  
  function selectUser(user) {
    formData.value.user_id = user.id; // Сохраняем ID пользователя
    searchTerm.value = user.name; // Обновляем поле ввода
    isDropdownOpen.value = false; // Закрываем выпадающий список
    filteredUsers.value = []; // Очищаем список отфильтрованных пользователей
  }
  
  async function addAdmin() {
    const selectedUser = users.value.find(user => user.id === formData.value.user_id); // Используем ID выбранного пользователя
    console.log(formData.value.user_id)
    if (selectedUser) {
        try {
            const form = new FormData();
            form.append('role_id', formData.value.role_id);
            form.append('user_id', formData.value.user_id);
      await axios.post(`/api/user_roles/`, form);
      alert('Администратор добавлен');
      emit('user-added');
      isVisible = ref(false);
    } catch (error) {
      console.error('Ошибка при добавлении администратора:', error);
    }
  } else {
    alert('Пользователь не найден');
  }
}
</script>