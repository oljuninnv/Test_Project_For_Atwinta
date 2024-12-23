<template>
  <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full">
      <h2 class="text-lg font-semibold text-gray-900">Редактировать сотрудника</h2>

      <div>
        <label for="position" class="block text-sm font-medium leading-6 text-gray-900">Должность</label>
        <div class="relative mt-2">
          <input type="text" v-model="formData.position" @input="filterPositions" @focus="isPositionDropdownOpen = true"
            @blur="closePositionDropdown" placeholder="Поиск должности..."
            class="input_text w-full border border-gray-300 rounded-md p-2" />
          <div v-if="isPositionDropdownOpen && filteredPositions.length > 0"
            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
            <ul class="max-h-60 overflow-auto">
              <li v-for="position in filteredPositions" :key="position.id" @mousedown="selectPosition(position)"
                class="cursor-pointer hover:bg-gray-100 px-2 py-1">
                {{ position.name }}
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="mt-4">
        <label for="department" class="block text-sm font-medium leading-6 text-gray-900">Отдел</label>
        <div class="relative mt-2">
          <input type="text" v-model="formData.department" @input="filterDepartments"
            @focus="isDepartmentDropdownOpen = true" @blur="closeDepartmentDropdown" placeholder="Поиск отдела..."
            class="input_text w-full border border-gray-300 rounded-md p-2" />
          <div v-if="isDepartmentDropdownOpen && filteredDepartments.length > 0"
            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg">
            <ul class="max-h-60 overflow-auto">
              <li v-for="department in filteredDepartments" :key="department.id"
                @mousedown="selectDepartment(department)" class="cursor-pointer hover:bg-gray-100 px-2 py-1">
                {{ department.name }}
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="flex justify-end mt-4">
        <button type="button" @click="$emit('close')"
          class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
        <button type="button" @click="updatePosition"
          class="ml-2 bg-blue-500 hover:bg-blue-600 text-white rounded px-4 py-2">Сохранить</button>
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
  workerData: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['close', 'worker-updated']);
const formData = ref({
  user_id:null,
  worker_id: null,
  position_id: null,
  department_id: null,
  adopted_at: new Date().toISOString().split('T')[0],
  position: '',
  department: ''
});
const isPositionDropdownOpen = ref(false);
const isDepartmentDropdownOpen = ref(false);
const filteredPositions = ref([]);
const filteredDepartments = ref([]);

// Получение данных о должностях и отделах при монтировании компонента
onMounted(async () => {
  await fetchData();
});

const fetchData = async () => {
  try {
    const response = await axios.get('/api/get_data_for_worker_without_user');
    filteredPositions.value = response.data.positions;
    filteredDepartments.value = response.data.departments;
  } catch (error) {
    console.error('Ошибка при получении данных:', error);
  }
};

// Слежение за изменениями в переданных данных о работнике
watch(() => props.workerData, (newWorker) => {
  if (newWorker) {
    console.log('Данные работника перед заполнением:', newWorker);
    formData.value.user_id = newWorker.user.id;
    formData.value.worker_id = newWorker.id;
    formData.value.position_id = newWorker.position.id;
    formData.value.department_id = newWorker.department.id;
    formData.value.adopted_at = newWorker.adopted_at || new Date().toISOString().split('T')[0];

    console.log('worker_id',newWorker.user.id);
    // Автоматическое заполнение полей должности и отдела
    const position = filteredPositions.value.find(pos => pos.id === newWorker.position_id);
    const department = filteredDepartments.value.find(dep => dep.id === newWorker.department_id);

    formData.value.position = position ? position.name : '';
    formData.value.department = department ? department.name : '';
  }
}, { immediate: true });

// Функция выбора должности
const selectPosition = (position) => {
  formData.value.position_id = position.id;
  formData.value.position = position.name; // Заполняем поле должности
  isPositionDropdownOpen.value = false;
};

// Функция выбора отдела
const selectDepartment = (department) => {
  formData.value.department_id = department.id;
  formData.value.department = department.name; // Заполняем поле отдела
  isDepartmentDropdownOpen.value = false;
};

// Закрытие выпадающего списка должностей
const closePositionDropdown = () => {
  isPositionDropdownOpen.value = false;
};

// Закрытие выпадающего списка отделов
const closeDepartmentDropdown = () => {
  isDepartmentDropdownOpen.value = false;
};

async function updatePosition() {
  if (formData.value.position_id && formData.value.department_id) {
    console.log('Обновление данных работника:', formData.value);
    try {
      formData.value.adopted_at = new Date().toISOString().split('T')[0];
      const response = await axios.put(`/api/workers/${formData.value.worker_id}`, {
        ...formData.value,
      });
      alert('Сотрудник обновлен');
      emit('worker-updated', response.data);
      emit('close');
    } catch (error) {
      console.error('Ошибка при обновлении должности:', error);
      alert('Произошла ошибка при обновлении должности');
    }
  } else {
    alert('Пожалуйста, заполните все поля');
  }
}
</script>