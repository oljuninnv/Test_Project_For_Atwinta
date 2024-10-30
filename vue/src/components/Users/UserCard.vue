<template>
  <div class="container mx-auto">
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
            :src="`http://127.0.0.1:8000/storage/${userImage}`" alt="User's Face" @mouseover="showTooltip = true"
            @mouseleave="showTooltip = false">
          <img v-else class="h-[60px] w-[60px] rounded-full cursor-pointer" src="../../public/default.png"
            alt="User's Face" @mouseover="showTooltip = true" @mouseleave="showTooltip = false">
        </router-link>
        <div v-if="showTooltip" class="absolute bg-gray-700 text-white text-xs rounded px-2 py-1 -bottom-8 right-0">
          Профиль
        </div>
      </div>
    </header>
    <SearchInput @search="handleSearch" />
    <div v-if="filteredEmployees.length">
      <h2 class="text-2xl font-bold mb-4 text-center">Название отдела: {{ department }}</h2>
      <div v-for="employee in filteredEmployees" :key="employee.worker_id"
        class="py-8 mt-5 px-8 max-w-sm mx-auto bg-white rounded-xl drop-shadow-2xl space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
        <img v-if="employee.image" class="h-[88px] w-[88px] block mx-auto rounded-full sm:mx-0 sm:shrink-0"
          :src="`http://127.0.0.1:8000/storage/${employee.image}`" alt="User's Face">
        <img v-else class="h-[88px] w-[88px] block mx-auto rounded-full sm:mx-0 sm:shrink-0"
          src="../../public/default.png" alt="User's Face">
        <div class="text-center space-y-4 sm:text-left">
          <div class="space-y-1">
            <p class="text-lg text-black font-semibold">{{ employee.name }}</p>
            <p class="text-slate-500 font-medium">{{ employee.position }}</p>
          </div>
          <router-link :to="{ name: 'UserView', params: { id: employee.worker_id } }"
            class="px-4 py-1 text-sm text-red-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-red-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2">
            Открыть профиль
          </router-link>
        </div>
      </div>
      <div class="flex items-center justify-center mt-5">
        <button @click="goBack" class="m-0 px-4 py-2 hover:text-white hover:bg-red-500 rounded">
          Назад
        </button>
      </div>
    </div>
    <div v-else>
      <h2 class="text-2xl font-bold mb-4 text-center">Сотрудники не найдены</h2>
      <button @click="goBack" class="m-0 px-4 py-2 hover:text-white hover:bg-red-500 rounded mx-auto block">
        Назад
      </button>
    </div>
  </div>
</template>

<script>
import axios from '../../libs/axios';
import { useRoute, useRouter } from 'vue-router';
import SearchInput from "../../components/SearchInput.vue";

export default {
  components: {
    SearchInput,
  },
  data() {
    return {
      department: '',
      searchQuery: '',
      departmentID: '',
      userRole: '',
      userImage: '',
      employees: [],
      route: useRoute(),
      router: useRouter(), // Инициализируем router
      showTooltip: false,
    };
  },
  computed: {
    filteredEmployees() {
      return this.employees.filter(employee =>
        employee.name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
  },
  created() {
    this.fetchWorkerInformation();
  },
  methods: {
    async fetchWorkerInformation() {
      const userData = JSON.parse(localStorage.getItem('UserData'));
      this.userRole = userData.roles;
      this.userImage = userData.user.image;
      this.departmentID = this.route.params.department_id;
      try {
        const response = await axios.get(`/api/workers_information/${this.departmentID}`); // Исправлено на правильный синтаксис
        const data = await response.data;
        console.log(data);
        this.department = data.department;
        this.employees = data.employees;
      } catch (error) {
        console.error('Ошибка при получении данных:', error);
      }
    },
    handleSearch(query) {
      this.searchQuery = query;
    },
    goBack() {
      this.$router.go(-1); // Возвращаемся на предыдущую страницу
    }
  },
};
</script>