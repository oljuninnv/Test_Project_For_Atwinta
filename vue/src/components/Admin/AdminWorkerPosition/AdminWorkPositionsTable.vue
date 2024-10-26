<template class="w-full">
    <div class="flex flex-wrap items-center w-full p-4 justify-center">
      <h1 class="mb-5">
        <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Должности:</span>
      </h1>
      <SearchInput @search="filterPositions" class="w-full max-w-md mb-4" />
      <button v-if="!showForm" @click="showForm = !showForm" class="btn">Добавить запись</button>
      <button v-if="showForm" @click="showForm = !showForm" class="btn">Убрать форму</button>
    </div>
  
    <!-- Форма добавления должности -->
    <div v-if="showForm" class="p-4 bg-gray-100 rounded-md mb-4">
      <AdminAddPosition @PositionAdd="handlePositionAdded"></AdminAddPosition>
    </div>
  
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-[5%]">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th class="px-6 py-3">ID</th>
            <th class="px-6 py-3">Название</th>
            <th scope="col" class="px-6 py-3"><span>Действия</span></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="position in paginatedPositions" :key="position.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <td class="px-6 py-4">{{ position.id }}</td>
            <td class="px-6 py-4">{{ position.name }}</td>
            <td class="px-6 py-4 text-right">
              <ul class="flex gap-5 text-right">
                <li><a href="#" @click.prevent="editPosition(position)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a></li>
                <li><a href="#" @click.prevent="removePosition(position.id)" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a></li>
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
  
    <AdminEditPosition 
      :position="selectedPosition" 
      :isVisible="isModalVisible" 
      @close="isModalVisible = false" 
      @position-updated="fetchPositions"
    />
  </template>
  
  <script setup>
  import { ref, onMounted, computed } from 'vue';
  import axios from '../../../libs/axios';
  import SearchInput from "../../SearchInput.vue";  
  const showForm = ref(false);
  const filteredPositions = ref([]);
  const positions = ref([]);
  const currentPage = ref(1);
  const itemsPerPage = 2; // Количество позиций на странице
  const totalPages = computed(() => Math.ceil(filteredPositions.value.length / itemsPerPage)); // Общее количество страниц
  const isModalVisible = ref(false);
  const selectedPosition = ref(null);
  
  onMounted(async () => {
    await fetchPositions();
  });
  
  async function fetchPositions() {
    try {
      const response = await axios.get('/api/positions');
      positions.value = response.data;
      filteredPositions.value = positions.value; // Изначально показываем все позиции
    } catch (error) {
      console.error('Ошибка при получении должностей:', error);
    }
  }
  
  function filterPositions(searchTerm) {
    if (searchTerm) {
      filteredPositions.value = positions.value.filter(position =>
        position.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    } else {
      filteredPositions.value = positions.value;
    }
    currentPage.value = 1; // Сбрасываем на первую страницу при новом поиске
  }
  
  function handlePositionAdded() {
    fetchPositions(); // Обновляем список после добавления
  }
  
  // Функции редактирования и удаления
  function editPosition(position) {
    selectedPosition.value = position;
    isModalVisible.value = true;
  }
  
  async function removePosition(id) {
    try {
        await axios.delete(`/api/positions/${id}`);
    fetchPositions(); // Обновляем список после удаления
  } catch (error) {
    console.error('Ошибка при удалении должности:', error);
  }
}

// Пагинация
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

// Вычисляем позиции для отображения на текущей странице
const paginatedPositions = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  return filteredPositions.value.slice(start, start + itemsPerPage);
});
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
  