<template>
    <form @submit.prevent="submitForm" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded">
            <div>
    <label for="user" class="block text-sm font-medium leading-6 text-gray-900">Работник</label>
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
            @click="selectUser(user)"
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
              @click="selectPosition(position)"
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
              @click="selectDepartment(department)"
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
          v-model="formData.hireDate"
          class="input_text w-full border border-gray-300 rounded-md p-2 mt-2"
        />
      </div>
  
      <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 mt-5">Отправить</button>
    </form>
  </template>
  
  <script>
  export default {
    data() {
      return {
        formData: {
          userId: '',
          positionId: '',
          departmentId: '',
          hireDate: ''
        },
        positionSearchTerm: '',
        departmentSearchTerm: '',
        searchTerm: '', // Инициализация переменной для поиска пользователей
        filteredUsers: [
          { id: 1, name: 'Иванов Иван' },
          { id: 2, name: 'Петров Петр' },
          { id: 3, name: 'Сидоров Сидор' }
        ],
        filteredPositions: [
          { id: 1, name: 'Менеджер по продажам' },
          { id: 2, name: 'Разработчик' },
          { id: 3, name: 'Дизайнер' },
          { id: 4, name: 'Тестировщик' },
          { id: 5, name: 'Системный администратор' }
        ],
        filteredDepartments: [
          { id: 1, name: 'Отдел продаж' },
          { id: 2, name: 'IT-отдел' },
          { id: 3, name: 'Маркетинг' },
          { id: 4, name: 'Финансовый отдел' },
          { id: 5, name: 'HR-отдел' }
        ],
        users: [
          { id: 1, name: 'Иванов Иван' },
          { id: 2, name: 'Петров Петр' },
          { id: 3, name: 'Сидоров Сидор' }
        ],
        positions: [
          { id: 1, name: 'Менеджер по продажам' },
          { id: 2, name: 'Разработчик' },
          { id: 3, name: 'Дизайнер' },
          { id: 4, name: 'Тестировщик' },
          { id: 5, name: 'Системный администратор' }
        ],
        departments: [
          { id: 1, name: 'Отдел продаж' },
          { id: 2, name: 'IT-отдел' },
          { id: 3, name: 'Маркетинг' },
          { id: 4, name: 'Финансовый отдел' },
          { id: 5, name: 'HR-отдел' }
        ],
        isPositionDropdownOpen: false,
        isDepartmentDropdownOpen: false,
        isDropdownOpen: false, // Переменная для управления дропдауном пользователей
      };
    },
    watch: {
      searchTerm(newVal) {
    console.log('searchTerm изменился на:', newVal);
    if (!newVal) {
      this.formData.userId = '';
      console.log('Сбросил userId');
    }
    this.filterUsers();
  },
      positionSearchTerm(newVal) {
        if (!newVal) {
          this.formData.positionId = ''; // Сброс ID должности при очищении поля
        }
        this.filterPositions();
      },
      departmentSearchTerm(newVal) {
        if (!newVal) {
          this.formData.departmentId = ''; // Сброс ID отдела при очищении поля
        }
        this.filterDepartments();
      }
    },
    methods: {
      filterPositions() {
        if(this.positionSearchTerm){
          this.filteredPositions = this.filteredPositions.filter(position =>
          position.name.toLowerCase().includes(this.positionSearchTerm.toLowerCase())
        );
        this.isPositionDropdownOpen = this.filteredPositions.length > 0;
        }
        else{
          this.filteredPositions = this.positions;
        }
      },
      selectPosition(position) {
        this.formData.positionId = position.id;
        this.positionSearchTerm = position.name;
        this.isPositionDropdownOpen = false;
        this.filteredPositions = [];
      },
      filterDepartments() {
        if(this.departmentSearchTerm){
          this.filteredDepartments = this.filteredDepartments.filter(department =>
          department.name.toLowerCase().includes(this.departmentSearchTerm.toLowerCase())
        );
        this.isDepartmentDropdownOpen = this.filteredDepartments.length > 0;
        }else{
          this.filteredDepartments = this.departments;
        }       
      },
      selectDepartment(department) {
        this.formData.departmentId = department.id;
        this.departmentSearchTerm = department.name;
        this.isDepartmentDropdownOpen = false;
        this.filteredDepartments = [];
      },
      filterUsers() {
        if (this.searchTerm){this.filteredUsers = this.filteredUsers.filter(user =>
          user.name.toLowerCase().includes(this.searchTerm.toLowerCase())
        );
        this.isDropdownOpen = this.filteredUsers.length > 0;
      }else{
        this.filteredUsers = this.users;
      }},
      selectUser(user) {
        this.formData.userId = user.id; // Сохраняем ID пользователя
        this.searchTerm = user.name;
        this.isDropdownOpen = false;
        this.filteredUsers = [];
      },
      closeDropdown() {
        setTimeout(() => {
          this.isDropdownOpen = false; // Закрытие дропдауна через небольшой таймаут
        }, 200);
      },
      closePositionDropdown() {
        setTimeout(() => {
          this.isPositionDropdownOpen = false;
        }, 200);
      },
      closeDepartmentDropdown() {
        setTimeout(() => {
          this.isDepartmentDropdownOpen = false;
        }, 200);
      },
    },
    mounted() {
    this.filteredUsers = this.users;
    this.filteredPositions = this.positions;
    this.filteredDepartments = this.departments;
  }};
  </script>