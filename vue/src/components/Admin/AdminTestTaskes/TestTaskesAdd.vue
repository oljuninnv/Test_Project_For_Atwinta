<template>
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-2 lg:px-8">
      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
        <form class="max-w-lg mx-auto p-4 bg-white shadow-md rounded flex flex-col gap-5" @submit.prevent="submitForm">
          <div>
            <label for="TestTaskName" class="block text-sm font-medium leading-6 text-gray-900">Название тестового задания</label>
            <div class="mt-2">
              <input 
                id="TestTaskName" 
                v-model="formData.TestTaskName" 
                required 
                class="input_text" 
                placeholder="Введите название тестого задания"
              />
            </div>
          </div>
  
          <div>
            <label for="fileUpload" class="block text-sm font-medium leading-6 text-gray-900">Загрузить файл (PDF)</label>
            <div class="mt-2">
              <input 
                id="fileUpload" 
                type="file" 
                @change="handleFileUpload" 
                accept=".pdf" 
                required 
                class="input_text" 
              />
            </div>
          </div>
  
          <div>
            <label for="time_limit_in_weeks" class="block text-sm font-medium leading-6 text-gray-900">Счетчик</label>
            <div class="mt-2">
              <input 
                id="time_limit_in_weeks" 
                type="number" 
                v-model="formData.time_limit_in_weeks" 
                required 
                class="input_text" 
                placeholder="Введите число"
                min="0" 
              />
            </div>
          </div>
  
          <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">Создать тестовое задание</button>
  
          <!-- Уведомление об ошибке -->
          <div v-if="errorMessage" class="mt-4 text-red-500">
            {{ errorMessage }}
          </div>
  
          <!-- Уведомление об успешном добавлении -->
          <div v-if="successMessage" class="mt-4 text-green-500">
            {{ successMessage }}
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from '../../../libs/axios';
  
  const emit = defineEmits(['testTaskes-added']);
  
  const formData = ref({
    TestTaskName: '',
    file: null,
    time_limit_in_weeks: 0, // Добавлено поле для счетчика
  });
  
  const errorMessage = ref('');
  const successMessage = ref('');
  
  function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file && file.type !== 'application/pdf') {
      errorMessage.value = 'Пожалуйста, загрузите файл в формате PDF.';
      formData.value.file = null;
    } else {
      errorMessage.value = '';
      formData.value.file = file;
    }
  }
  
  async function submitForm() {
    if (!formData.value.file) {
      errorMessage.value = 'Файл обязателен для загрузки.';
      return;
    }
  
    const newTestTask = new FormData();
    newTestTask.append('name', formData.value.TestTaskName);
    newTestTask.append('file', formData.value.file);
    newTestTask.append('time_limit_in_weeks', formData.value.time_limit_in_weeks); // Добавляем счетчик в FormData
  
    try {
      const response = await axios.post('api/test_taskes', newTestTask, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
  
      console.log('Добавлено новое тестовое задание:', response.data);
      emit('testTaskes-added', response.data);
      successMessage.value = 'Добавлено новое тестовое задание!';
      formData.value.TestTaskName = '';
      formData.value.file = null;
      formData.value.time_limit_in_weeks = 0; // Сбрасываем счетчик
      errorMessage.value = '';
    } catch (error) {
      console.error('Ошибка при добавлении тестового задания:', error);
      alert('Ошибка при добавлении тестового задания. Пожалуйста, попробуйте еще раз.');
      successMessage.value = '';
    }
  }
  </script>
  
  <style scoped>
  .input_text {
    border: 1px solid #ccc;
    padding: 8px;
    border-radius: 4px;
    width: 100%;
  }
  </style>
  