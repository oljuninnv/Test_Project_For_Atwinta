<template>
    <div>
        <header class="flex justify-between items-center">
      <h1 class="text-xl font-bold">ATWINTA</h1>
      <div class="relative">
        <router-link to="/profile" >
      <img 
        v-if="userImage" 
        class="h-[60px] w-[60px] rounded-full cursor-pointer" 
        :src="`http://127.0.0.1:8000/storage/${userImage}`" 
        alt="User's Face"
        @mouseover="showTooltip = true"
        @mouseleave="showTooltip = false"
      >
      <img 
        v-else 
        class="h-[60px] w-[60px] rounded-full cursor-pointer" 
        src="../../public/default.png" 
        alt="User's Face"
        @mouseover="showTooltip = true"
        @mouseleave="showTooltip = false"
      >
    </router-link>
        <div v-if="showTooltip" class="absolute bg-gray-700 text-white text-xs rounded px-2 py-1 -bottom-8 right-0">
          Профиль
        </div>
      </div>
    </header>
        <SearchInput @search="handleSearch" />
        <h2 class="text-bluePrimary text-xl font-bold text-center mb-5">Страница со списком отделов</h2>
        <div v-for="department in filteredDepartments" :key="department.id"
             @click="openModal(department)"
             class="py-8 mt-5 px-8 max-w-sm mx-auto bg-white rounded-xl drop-shadow-2xl space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6 cursor-pointer">
            <div class="text-center space-y-2 sm:text-left">
                <div class="space-y-0.5">
                    <h2>{{ department.department_name }}</h2>
                </div>
                <router-link v-if="userRole == 'Admin'"
                    :to="{ name: 'UsersList', params: { department_id: department.department_id } }"
                    class="hover:text-red-500">
                    Подробнее
                </router-link>
            </div>
        </div>

        <!-- Модальное окно -->
        <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto">
                <span @click="closeModal" class="close absolute top-4 right-4 cursor-pointer text-gray-600 hover:text-gray-800">&times;</span>
                <h2 class="text-lg font-semibold text-gray-900">Название отдела: {{ selectedDepartment?.department_name }}</h2>
                <div class="mt-4">
                    <p><strong>Количество сотрудников:</strong> {{ selectedDepartment?.employee_count }}</p>
                    <p><strong>Текущие должности:</strong> {{ selectedDepartment?.positions.join(', ') }}</p>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" @click="closeModal" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '../../libs/axios';
import SearchInput from "../../components/SearchInput.vue"

export default {
    components: {
        SearchInput,
    },
    data() {
        return {
            departments: [],
            searchQuery: '',
            userImage: '',
            userRole:'',
            isModalOpen: false,
            selectedDepartment: null,
            showTooltip: false,
        };
    },
    computed: {
        filteredDepartments() {
            return this.departments.filter(department =>
                department.department_name.toLowerCase().startsWith(this.searchQuery.toLowerCase())
            );
        },
    },
    mounted() {
        this.fetchDepartments();
    },
    methods: {
        async fetchDepartments() {
            try {
                const userData = JSON.parse(localStorage.getItem('UserData'));
                this.userImage = userData.user.image;
                this.userRole = userData.roles; 
                console.log(this.userRole);
                const response = await axios.get('/api/departments_information'); // Убедитесь, что путь правильный
                this.departments = response.data;
                console.log('www', this.departments);
            } catch (error) {
                console.error('Ошибка при получении данных:', error);
            }
        },
        handleSearch(query) {
            this.searchQuery = query; // Здесь query - это строка, введенная пользователем
        },
        openModal(department) {
            this.selectedDepartment = department;
            this.isModalOpen = true;
        },
        closeModal() {
            this.isModalOpen = false;
            this.selectedDepartment = null;
        },
    },
};
</script>

