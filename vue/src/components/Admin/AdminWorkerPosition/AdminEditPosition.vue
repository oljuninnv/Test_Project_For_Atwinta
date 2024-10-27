<template>
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
        <h2 class="text-lg font-semibold text-gray-900">Редактировать должность</h2>
        <div class="relative mt-2">
          <input
            type="text"
            v-model="formData.name"
            placeholder="Введите название должности..."
            class="input_text w-full border border-gray-300 rounded-md p-2"
          />
        </div>
        <div class="flex justify-end mt-4">
          <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
          <button type="button" @click="updatePosition" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2">Сохранить</button>
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
    position: {
      type: Object,
      required: true,
    },
  });
  
  const emit = defineEmits(['close', 'position-updated']);
  const formData = ref({})
  
  // Слежение за изменениями в переданных данных о должности
  watch(() => props.position, (newPosition) => {
  formData.value = { ...newPosition };
}, { immediate: true });
  
  async function updatePosition() {
    if (formData.value.name.trim()) {
      try {
        const response = await axios.put(`/api/positions/${props.position.id}`, {
          name: formData.value.name,
        });
        alert('Должность обновлена');
        emit('position-updated', response.data); 
        emit('close'); 
      } catch (error) {
        console.error('Ошибка при обновлении должности:', error);
        alert('Произошла ошибка при обновлении должности');
      }
    } else {
      alert('Пожалуйста, введите название должности');
    }
  }
  </script>