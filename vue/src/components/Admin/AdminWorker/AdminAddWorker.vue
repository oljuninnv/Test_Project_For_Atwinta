<template>
  <form @submit.prevent="submitForm" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded">
    <div>
      <label for="user" class="block text-sm font-medium leading-6 text-gray-900">Пользователь</label>
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
        <div v-if="isDropdownOpen && filteredUsers.length > 0" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
          <ul class="max-h-60 overflow-auto">
            <li
              v-for="user in filteredUsers"
              :key="user.id"
              @mousedown="selectUser(user)"
              class="cursor-pointer hover:bg-gray-100 px-2 py-1"
            >
              {{ user.name }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div>
      <label for="position" class="block text-sm font-medium leading-6 text-gray-900">Должность</label>
      <div class="relative mt-2">
        <input
          type="text"
          v-model="positionSearchTerm"
          @input="filterPositions"
          @focus="isPositionDropdownOpen = true"
          @blur="closePositionDropdown"
          placeholder="Поиск должности..."
          class="input_text w-full border border-gray-300 rounded-md p-2"
        />
        <div v-if="isPositionDropdownOpen && filteredPositions.length > 0" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
          <ul class="max-h-60 overflow-auto">
            <li
              v-for="position in filteredPositions"
              :key="position.id"
              @mousedown="selectPosition(position)"
              class="cursor-pointer hover:bg-gray-100 px-2 py-1"
            >
              {{ position.name }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <label for="department" class="block text-sm font-medium leading-6 text-gray-900">Отдел</label>
      <div class="relative mt-2">
        <input
          type="text"
          v-model="departmentSearchTerm"
          @input="filterDepartments"
          @focus="isDepartmentDropdownOpen = true"
          @blur="closeDepartmentDropdown"
          placeholder="Поиск отдела..."
          class="input_text w-full border border-gray-300 rounded-md p-2"
        />
        <div v-if="isDepartmentDropdownOpen && filteredDepartments.length > 0" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
          <ul class="max-h-60 overflow-auto">
            <li
              v-for="department in filteredDepartments"
              :key="department.id"
              @mousedown ="selectDepartment(department)"
              class="cursor-pointer hover:bg-gray-100 px-2 py-1"
            >
              {{ department.name }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <label for="hireDate" class="block text-sm font-medium leading-6 text-gray-900">Дата найма</label>
      <input
        type="date"
        v-model="formData.adopted_at"
        class="input_text w-full border border-gray-300 rounded-md p-2 mt-2"
      />
    </div>

    <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 mt-5">Отправить</button>
  </form>
</template>

<script>
import axios from '../../../libs/axios';
import { ref, onMounted } from 'vue';

export default {
  setup() {
    const formData = ref({
      user_id: null,
      position_id: null,
      department_id: null,
      adopted_at: ''
    });

    const users = ref([]);
    const positions = ref([]);
    const departments = ref([]);
    const searchTerm = ref('');
    const positionSearchTerm = ref('');
    const departmentSearchTerm = ref('');
    const filteredUsers = ref([]);
    const filteredPositions = ref([]);
    const filteredDepartments = ref([]);
    const isDropdownOpen = ref(false);
    const isPositionDropdownOpen = ref(false);
    const isDepartmentDropdownOpen = ref(false);

    const fetchData = async () => {
      try {
        const response = await axios.get('/api/get_data_for_worker');
        users.value = response.data.users; 
        departments.value = response.data.departments; 
        positions.value = response.data.positions; 

        filteredUsers.value = users.value;
        filteredPositions.value = positions.value;
        filteredDepartments.value = departments.value;
      } catch (error) {
        console.error('Ошибка при загрузке данных:', error);
      }
    };

    onMounted(() => {
      fetchData(); 
    });

    const selectUser = (user) => {
      formData.value.user_id = user.id;
      searchTerm.value = user.name;
      isDropdownOpen.value = false;
      filteredUsers.value = []; 
    };

    const selectPosition = (position) => {
      formData.value.position_id = position.id;
      positionSearchTerm.value = position.name;
      isPositionDropdownOpen.value = false;
      filteredPositions.value = [];
    };

    const selectDepartment = (department) => {
      formData.value.department_id = department.id;
      departmentSearchTerm.value = department.name;
      isDepartmentDropdownOpen.value = false;
      filteredDepartments.value = [];
    };

    const submitForm = async () => {
      try {
        console.log(formData.value);
        await axios.post('/api/workers', formData.value);

        alert('Работник успешно добавлен! Перезагрузите страницу, чтобы данные в таблице обновились.');
        
        // Очистка данных формы
        formData.value.user_id = null;
        formData.value.position_id = null;
        formData.value.department_id = null;
        formData.value.adopted_at = '';
        
        // Обновление данных после добавления работника
        await fetchData();
      } catch (error) {
        console.error('Ошибка при добавлении работника:', error);
        alert('Произошла ошибка при добавлении работника. Пожалуйста, попробуйте еще раз.');
      }
    };

    const filterUsers = () => {
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
    };

    return {
      formData,
      users,
      positions,
      departments,
      searchTerm,
      positionSearchTerm,
      departmentSearchTerm,
      filteredUsers,
      filteredPositions,
      filteredDepartments,
      isDropdownOpen,
      isPositionDropdownOpen,
      isDepartmentDropdownOpen,
      fetchData,
      selectUser,
      selectPosition,
      selectDepartment,
      submitForm,
      filterUsers
    };
  }
};
</script>