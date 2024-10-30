<template>
  <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
      <h2 class="text-lg font-semibold text-gray-900">Редактировать отдел</h2>
      <div class="relative mt-2">
        <input type="text" v-model="formData.department_name" placeholder="Введите название отдела..."
          class="input_text w-full border border-gray-300 rounded-md p-2" />
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" @click="$emit('close')"
          class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
        <button type="button" @click="updateDepartment"
          class="ml-2 bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2">Сохранить</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from '../../../libs/axios';

const props = defineProps({
  isVisible: {
    type: Boolean,
    required: true,
  },
  department: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['close', 'update']);
const formData = ref({ name: '' }); // Инициализация с пустым значением

watch(() => props.department, (newDepartment) => {
  formData.value = { ...newDepartment };
}, { immediate: true });

async function updateDepartment() {

  if (formData.value.department_name.trim()) {
    try {
      const response = await axios.put(`/api/departments/${props.department.department_id}`, {
        name: formData.value.department_name,
      });
      alert('Должность обновлена');
      emit('update', response.data);
      emit('close');
    } catch (error) {
      console.error('Ошибка при обновлении отдела:', error);
      alert('Произошла ошибка при обновлении отдела');
    }
  } else {
    alert('Пожалуйста, введите название отдела');
  }
}
</script>