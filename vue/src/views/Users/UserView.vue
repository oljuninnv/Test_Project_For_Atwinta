<template>
    <div>
      <template v-if="isAuthenticated">
        <UserPage />
      </template>
      <template v-else>
        <div class="text-center text-xl">Ошибка 404: Страница не найдена</div>
      </template>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import UserPage from "../../components/Users/UserPage.vue";
  
  // Проверяем наличие worker_id в localStorage
  const workerId = localStorage.getItem('UserData') ? JSON.parse(localStorage.getItem('UserData')).worker_id : null;
  
  // Вычисляемое свойство для проверки авторизации
  const isAuthenticated = computed(() => {
    return workerId !== null;
  });
  </script>