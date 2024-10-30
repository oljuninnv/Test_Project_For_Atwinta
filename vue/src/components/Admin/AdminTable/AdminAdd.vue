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
              @mousedown.prevent="selectUser(user)"
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
import { ref, onMounted, defineEmits } from 'vue';
import axios from '../../../libs/axios';

const emit = defineEmits(['user-added']);
const isVisible = ref(true);
const searchTerm = ref('');
const isDropdownOpen = ref(false);
const users = ref([]);
const filteredUsers = ref([]);
const formData = ref({ user_id: null });

onMounted(async () => {
  await fetchUsers();
});

async function fetchUsers() {
  try {
    const response = await axios.get('/api/get_others');
    users.value = response.data; 
    filteredUsers.value = users.value.response; 
    console.log(filteredUsers.value);
  } catch (error) {
    console.error('Ошибка при получении пользователей:', error);
  }
}

function filterUsers() {
  const term = searchTerm.value.toLowerCase();
  if (term) {
    filteredUsers.value = users.value.filter(user => user.name.toLowerCase().includes(term));
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
  formData.value.user_id = user.id; // Сохраняем ID выбранного пользователя
  searchTerm.value = user.name; // Обновляем поле ввода
  isDropdownOpen.value = false; // Закрываем выпадающий список
}

async function addAdmin() {
  if (!formData.value.user_id) {
    alert('Пожалуйста, выберите пользователя.');
    return;
  }

  try {
    const form = new FormData();
    form.append('user_id', formData.value.user_id);
    await axios.post('/api/user_roles/', form);
    alert('Администратор добавлен. Пожалуйста перезагрузите таблицу, чтобы обновить таблицу');
    emit('user-added');
    isVisible.value = false; // Закрываем модальное окно
  } catch (error) {
    console.error('Ошибка при добавлении администратора:', error);
    alert('Ошибка при добавлении администратора. Попробуйте еще раз.');
  }
}
</script>