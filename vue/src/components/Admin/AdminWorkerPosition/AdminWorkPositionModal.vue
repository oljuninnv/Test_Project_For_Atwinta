<template>
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
        <h2 class="text-lg font-semibold text-gray-900">Добавить должность</h2>
        <div class="relative mt-2">
          <input
            type="text"
            v-model="positionName"
            placeholder="Введите название должности..."
            class="input_text w-full border border-gray-300 rounded-md p-2"
          />
        </div>
        <div class="flex justify-end mt-4">
          <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
          <button type="button" @click="addPosition" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2">Добавить</button>
        </div>
      </div>
    </div>
  </template>

<script setup>
import { ref } from 'vue';
import axios from '../../../libs/axios';

const emit = defineEmits(['position-added']);
const isVisible = ref(true);
const positionName = ref('');

async function addPosition() {
  if (positionName.value.trim()) {
    try {
      const response = await axios.post('/api/positions', {
        name: positionName.value.trim(),
      });
      alert('Должность добавлена');
      emit('position-added', response.data); 
      positionName.value = ''; 
      emit('close'); 
    } catch (error) {
      console.error('Ошибка при добавлении должности:', error);
      alert('Произошла ошибка при добавлении должности');
    }
  } else {
    alert('Пожалуйста, введите название должности');
  }
}
</script>