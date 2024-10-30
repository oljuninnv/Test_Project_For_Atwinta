<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-2 lg:px-8">
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
      <form class="max-w-lg mx-auto p-4 bg-white shadow-md rounded flex flex-col gap-5" @submit.prevent="submitForm">
        
        <div>
          <label for="departmentName" class="block text-sm font-medium leading-6 text-gray-900">Название отдела</label>
          <div class="mt-2">
            <input
              id="departmentName"
              v-model="formData.departmentName"
              required
              class="input_text"
            />
          </div>
        </div>

        <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">Создать отдел</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import axios from '../../../libs/axios';

const emit = defineEmits(['department-added']);

const formData = ref({
  departmentName: '',
});

async function submitForm() {
  const newDepartment = {
    name: formData.value.departmentName,
  };

  try {
    // Отправка POST запроса для добавления нового отдела
    const response = await axios.post('api/departments', newDepartment);
    
    console.log('Создан новый отдел:', response.data);
    
    // Эмитируем событие с новым отделом
    emit('department-added', response.data);

    // Сброс формы после успешной отправки
    formData.value.departmentName = '';
  } catch (error) {
    console.error('Ошибка при добавлении отдела:', error);
  }
}
</script>