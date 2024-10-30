<template>
    <div>
        <header class="flex justify-between items-center mb-5">
            <h1 class="text-xl font-bold">ATWINTA</h1>
            <div v-if="userRole == 'Admin'" class="relative">
                <router-link to="/admin" class="text-xl font-bold">
                    AdminPanel
                </router-link>
            </div>
            <div class="relative">
                <router-link to="/profile">
                    <img v-if="userImage" class="h-[60px] w-[60px] rounded-full cursor-pointer"
                        :src="`http://127.0.0.1:8000/storage/${userImage}`" alt="User's Face"
                        @mouseover="showTooltip = true" @mouseleave="showTooltip = false">
                    <img v-else class="h-[60px] w-[60px] rounded-full cursor-pointer" src="../../public/default.png"
                        alt="User's Face" @mouseover="showTooltip = true" @mouseleave="showTooltip = false">
                </router-link>
                <div v-if="showTooltip"
                    class="absolute bg-gray-700 text-white text-xs rounded px-2 py-1 -bottom-8 right-0">
                    Профиль
                </div>
            </div>
        </header>
        <SearchInput @search="handleSearch" />
        <h2 class="text-bluePrimary text-xl font-bold text-center mb-5">Страница со списком отделов</h2>
        <div v-for="department in filteredDepartments" :key="department.id" @click="openModal(department)"
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
                <span @click="closeModal"
                    class="close absolute top-4 right-4 cursor-pointer text-gray-600 hover:text-gray-800">&times;</span>
                <h2 class="text-lg font-semibold text-gray-900">Название отдела: {{ selectedDepartment?.department_name
                    }}</h2>
                <div class="mt-4">
                    <p><strong>Количество сотрудников:</strong> {{ selectedDepartment?.employee_count }}</p>
                    <p><strong>Текущие должности:</strong></p>
                    <ul v-if="selectedDepartment && selectedDepartment.positions.length">
                        <li v-for="position in selectedDepartment.positions" :key="position.id">
                            {{ position.name }};
                        </li>
                    </ul>
                    <p v-else>Нет текущих должностей.</p>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" @click="closeModal"
                        class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
                </div>
            </div>
        </div>
        <!-- Пагинация -->
        <div class="pagination">
                    <button @click="prevPage" :disabled="pagination.page === 1 || loading == true"
                        class="btn">Назад</button>
                    <span class="mx-2">Страница {{ pagination.page }} из {{ pagination.last_page }}</span>
                    <button @click="nextPage" :disabled="pagination.page === pagination.last_page || loading === true"
                        class="btn">Вперед</button>
                </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from '../../libs/axios';
import SearchInput from "../../components/SearchInput.vue";
import { GetDepartments } from '../../services/api/department';

export default {
    components: {
        SearchInput,
    },
    setup() {
        const pagination = ref({
            page: 1,
            per_page: 3,
            total: 1,
            last_page: 1,
        });
        const departments = ref([]);
        const searchQuery = ref('');
        const userImage = ref('');
        const userRole = ref('');
        const isModalOpen = ref(false);
        const selectedDepartment = ref(null);
        const showTooltip = ref(false);

        const filteredDepartments = computed(() => {
            return departments.value.filter(department =>
                department.department_name.toLowerCase().startsWith(searchQuery.value.toLowerCase())
            );
        });

        const fetchDepartments = async (page = 1, name = null) => {
            try {
                const userData = JSON.parse(localStorage.getItem('UserData'));
                userImage.value = userData.user.image;
                userRole.value = userData.roles;
                const response = await GetDepartments(page, pagination.value.per_page, name);
                departments.value = response.data;
                pagination.value.total = response.total; // Обновляем общее количество
                pagination.value.last_page = response.last_page; // Обновляем последнюю страницу
            } catch (error) {
                console.error('Ошибка при получении данных:', error);
            }
        };

        const handleSearch = (query) => {
            searchQuery.value = query; // Здесь query - это строка, введенная пользователем
            fetchDepartments(1, query);
        };

        const openModal = (department) => {
            selectedDepartment.value = department;
            isModalOpen.value = true;
        };

        const closeModal = () => {
            isModalOpen.value = false;
            selectedDepartment.value = null;
        };

        const prevPage = async () => {
            if (pagination.value.page > 1) {
                await fetchDepartments(pagination.value.page - 1);
                pagination.value.page--;
            }
        };

        const nextPage = async () => {
            if (pagination.value.page < pagination.value.last_page) {
                await fetchDepartments(pagination.value.page + 1);
                pagination.value.page++;
            }
        };

        onMounted(() => {
            fetchDepartments();
        });

        return {
            pagination,
            departments,
            searchQuery,
            userImage,
            userRole,
            isModalOpen,
            selectedDepartment,
            showTooltip,
            filteredDepartments,
            fetchDepartments,
            handleSearch,
            openModal,
            closeModal,
            prevPage,
            nextPage,
        };
    },
};
</script>
