<template>
  <div v-if="isAdmin" class="flex flex-wrap bg-gray-100 w-full h-screen">
    <div class="bg-white rounded p-3 shadow-lg w-full md:w-3/12 lg:w-2/12">
      <div class="flex items-center space-x-4 p-2 mb-5">
        <h4 class="font-semibold text-lg text-gray-700 capitalize font-poppins tracking-wide">AdminPanel</h4>
      </div>
      <hr>
      <ul class="space-y-2 text-sm">
        <li>
          <a href="#" @click.prevent="showTable('users')"
            class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
            <span>Пользователи</span>
          </a>
        </li>
        <li>
          <a href="#" @click.prevent="showTable('workers')"
            class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
            <span>Сотрудники</span>
          </a>
        </li>
        <li>
          <a href="#" @click.prevent="showTable('departments')"
            class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
            <span>Отделы</span>
          </a>
        </li>
        <li>
          <a href="#" @click.prevent="showTable('work-positions')"
            class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
            <span>Должности</span>
          </a>
        </li>
        <hr>
        <li>
          <a href="#" @click.prevent="showTable('admins')"
            class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
            <span>Администраторы</span>
          </a>
        </li>
        <hr>
        <li>
          <a href="#"
            class="flex items-center space-x-3 text-gray-700 p-2 rounded-md font-medium hover:bg-gray-200 focus:bg-gray-200 focus:shadow-outline">
            <router-link to="/departments" class="flex gap-2">
              <span class="text-gray-600">
                <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
              </span>
              <span>Выйти</span>
            </router-link>
          </a>
        </li>
      </ul>
    </div>

    <div class="w-full md:w-9/12 lg:w-10/12 p-4">
      <div class="text-gray-500">
        <AdminUserTable v-if="activeTable === 'users'" />
        <AdminWorkerTable v-if="activeTable === 'workers'" />
        <AdminDepartmentTable v-if="activeTable === 'departments'" />
        <AdminWorkPositionsTable v-if="activeTable === 'work-positions'" />
        <AdminTable v-if="activeTable === 'admins'" />
      </div>
    </div>
  </div>

  <!-- Страница ошибки 404 -->
  <div v-else class="flex items-center justify-center w-full h-screen bg-gray-100">
    <h1 class="text-xl font-bold text-red-500">404 - Доступ запрещен</h1>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import AdminUserTable from "../../components/Admin/AdminUser/AdminUserTable.vue";
import AdminDepartmentTable from "../../components/Admin/AdminDepartment/AdminDepartmentTable.vue";
import AdminWorkerTable from "../../components/Admin/AdminWorker/AdminWorkerTable.vue";
import AdminWorkPositionsTable from "../../components/Admin/AdminWorkerPosition/AdminWorkPositionsTable.vue";
import AdminTable from "../../components/Admin/AdminTable/AdminTable.vue";

const activeTable = ref('users');

// Получаем данные о пользователе из localStorage
const userData = JSON.parse(localStorage.getItem('UserData'));
const isAdmin = computed(() => {
  return userData && userData.roles && userData.roles.includes('Admin');
});

function showTable(table) {
  activeTable.value = table;
}
</script>