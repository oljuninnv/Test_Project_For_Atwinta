<template>
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
        <h2 class="text-lg font-semibold text-gray-900">Редактировать статус тестового задания</h2>
          
        <!-- <span>{{ formData.status }}</span> -->
        <div v-if="formData.status === 'выполнен'" class="mt-4">
        <label>
          <input type="checkbox" v-model="isChecked" />
          Проверен
        </label>
      </div>

      <!-- Поле для выбора даты окончания -->
      <div class="relative mt-4">
        <label for="end_date" class="block text-sm font-medium text-gray-700">Изменить дату окончания (Текущая: {{formData.end_date}})</label>
        <input 
          type="date" 
          id="end_date"
          v-model="formData.end_date" 
          class="input_text w-full border border-gray-300 rounded-md p-2" 
          @change="validateEndDate"
        />
        <span v-if="dateError" class="text-red-500 text-sm">{{ dateError }}</span>
      </div>
  
        <!-- Кнопки управления -->
        <div class="flex justify-end mt-4">
          <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
          <button type="button" @click="updateTestTask" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2">Сохранить</button>
        </div>
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
  const formData = ref({ status: '', end_date: '' }); // Инициализация с пустыми значениями
  const isChecked = ref(false); // Состояние чекбокса
  const dateError = ref(''); // Сообщение об ошибке для даты
  
  // Синхронизация данных с переданным тестовым заданием
  watch(
    () => props.test_task,
    (newTestTask) => {
      formData.value = { ...newTestTask };
    },
    { immediate: true }
  );

  watch(() => props.isVisible, () => {
    mount();
  })
  
  // Заполнение полей при монтировании компонента
  onMounted(() => {
   mount();
  });

  const mount = () => {
    formData.value.status = props.test_task?.status;
    formData.value.end_date = props.test_task?.end_date;
    console.log(props.test_task);
  }
  
  // Проверка даты окончания
  function validateEndDate() {
    const selectedDate = new Date(formData.value.end_date);
    const currentDate = new Date();
    
    // Устанавливаем время на полночь для корректного сравнения
    currentDate.setHours(0, 0, 0, 0);
  
    if (selectedDate < currentDate) {
      dateError.value = 'Дата окончания не может быть меньше текущей даты.';
    } else {
      dateError.value = '';
    }
  }
  
  // Обновление тестового задания
  async function updateTestTask() {   
    const updatedData = ref({ status: '', end_date: '' });

    updatedData.end_date = formData.value.end_date;
    updatedData.status = isChecked.value ? 'проверен' : formData.value.status;
    // updatedData.append('status', isChecked.value ? 'проверен' : formData.value.status);
    // updatedData.append('end_date', formData.value.end_date);

    console.log(updatedData.end_date);
  
    try {
      const response = await axios.put(`/api/test_taskes_status/${props.test_task.id}`, updatedData);
      
      alert('Статус тестого задания обновлено');
      emit('update', response.data); // Эмитируем обновленные данные
      emit('close'); // Закрываем модальное окно
    } catch (error) {
      console.error('Ошибка при обновлении статуса тестового задания:', error);
      alert('Произошла ошибка при обновлении статуса тестового задания');
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