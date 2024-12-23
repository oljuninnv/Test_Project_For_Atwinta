<template>
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
        <h2 class="text-lg font-semibold text-gray-900">Редактировать тестовое задание</h2>
        <form class="max-w-lg mx-auto p-4 bg-white shadow-md rounded flex flex-col gap-5" @submit.prevent="submitForm" enctype="multipart/form-data">
        <!-- Название тестового задания -->
        <div class="relative mt-2">
          <label for="name" class="block text-sm font-medium text-gray-700">Название тестового задания</label>
          <input 
                id="name" 
                v-model="formData.name" 
                required 
                class="input_text" 
                placeholder="Введите название тестого задания"
              />
        </div>
  
        <!-- Загрузка нового файла -->
        <div class="relative mt-4">
          <label for="fileUpload" class="block text-sm font-medium text-gray-700">Загрузить новый файл (PDF)</label>
          <input 
            type="file" 
            id="fileUpload" 
            @change="handleFileUpload" 
            accept=".pdf" 
            class="input_text w-full border border-gray-300 rounded-md p-2" 
          />
        </div>
  
        <!-- Счетчик time_limit_in_weeks -->
        <div class="relative mt-4">
          <label for="time_limit_in_weeks" class="block text-sm font-medium text-gray-700">Лимит времени (в неделях)</label>
          <input 
                id="time_limit_in_weeks" 
                type="number" 
                v-model="formData.time_limit_in_weeks" 
                required 
                class="input_text" 
                placeholder="Введите число"
                min="1"/>
        </div>
  
        <!-- Кнопки управления -->
        <div class="flex justify-end mt-4">
          <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
          <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2">Сохранить</button>
        </div>
    </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch, onMounted } from 'vue';
  import axios from '../../../libs/axios';
  
  const props = defineProps({
    isVisible: {
      type: Boolean,
      required: true,
    },
    test_task: {
      type: Object || null,
      required: true,
    },
  });
  
  const emit = defineEmits(['close', 'update']);
  const formData = ref({ name: '', file: null, time_limit_in_weeks: 0 }); // Инициализация с пустыми значениями
  
  // Синхронизация данных с переданным тестовым заданием
  watch(
    () => props.test_task,
    (newTestTask) => {
      formData.value = { ...newTestTask };
    },
    { immediate: true }
  );
  
  // Заполнение полей при монтировании компонента
  onMounted(() => {
    formData.value.name = props.test_task?.name;
    formData.value.file = null; // Загружаем файл, если он есть
    formData.value.time_limit_in_weeks = props.test_task?.time_limit_in_weeks;
  });
  
  // Обработчик загрузки файла
  function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file && file.type !== 'application/pdf') {
      alert('Пожалуйста, загрузите файл в формате PDF.');
      formData.value.file = null; // Сбрасываем файл, если он не PDF
    } else {
      console.log(file);
      formData.value.file = file; // Сохраняем файл, если он PDF
    }
  }
  
  // Обновление тестового задания
  async function submitForm() {
  const updatedData = new FormData();

  // Проверяем, что значения существуют
  if (formData.value.name) {
    updatedData.append('name', formData.value.name);
  } else {
    alert('Имя не может быть пустым');
    return;
  }

  if (formData.value.time_limit_in_weeks) {
    updatedData.append('time_limit_in_weeks', formData.value.time_limit_in_weeks);
  } else {
    alert('Счетчик не может быть пустым');
    return;
  }

  if (formData.value.file) {
    updatedData.append('file', formData.value.file);
  }

  try {
    updatedData.append('_method', 'put');
    const response = await axios.post(`/api/test_taskes/${props.test_task.id}`, updatedData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    alert('Тестовое задание обновлено');
    emit('update', response.data);
    emit('close');
  } catch (error) {
    console.error('Ошибка при обновлении тестового задания:', error);
    alert('Произошла ошибка при обновлении тестового задания');
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