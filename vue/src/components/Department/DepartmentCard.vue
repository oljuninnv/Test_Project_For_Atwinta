<template>
    <div>
        <SearchInput @search="handleSearch" />
        <div v-for="department in filteredDepartments" :key="department.id"
             @click="openModal(department)"
             class="py-8 mt-5 px-8 max-w-sm mx-auto bg-white rounded-xl drop-shadow-2xl space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6 cursor-pointer">
            <div class="text-center space-y-2 sm:text-left">
                <div class="space-y-0.5">
                    <h2>{{ department.department_name }}</h2>
                </div>
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
            isModalOpen: false,
            selectedDepartment: null,
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
                const response = await axios.get('/api/departments_information'); // Убедитесь, что путь правильный
                this.departments = response.data;
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

