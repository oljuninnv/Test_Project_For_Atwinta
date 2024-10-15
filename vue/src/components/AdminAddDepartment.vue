<template>
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-2 lg:px-8">
      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
        <form class="max-w-lg mx-auto p-4 bg-white shadow-md rounded flex flex-col gap-5" @submit.prevent="submitForm">
          
          <div>
            <label for="departmentName" class="block text-sm font-medium leading-6 text-gray-900">Название отдела</label>
            <div class="mt-2">
              <input
                id="departmentName"
                name="departmentName"
                v-model="departmentName"
                required
                class="input_text"
              />
            </div>
          </div>
  
          <div>
            <label for="positions" class="block text-sm font-medium leading-6 text-gray-900">Выберите должности</label>
            <div class="mt-2">
              <select
                id="positions"
                v-model="selectedPositions"
                multiple
                class="input_text"
              >
                <option
                  v-for="position in positions"
                  :key="position.id"
                  :value="position.id"
                >
                  {{ position.title }}
                </option>
              </select>
            </div>
          </div>
  
          <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">Создать отдел</button>
  
          <h3 class="mt-5 text-lg font-medium">Текущие данные отдела:</h3>
          <p><strong>Название:</strong> {{ departmentName }}</p>
          <p><strong>Выбранные должности:</strong> {{ selectedPositionTitles.join(', ') }}</p>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        departmentName: '',
        selectedPositions: [],
        positions: [
          { id: 1, title: 'Разработчик' },
          { id: 2, title: 'Дизайнер' },
          { id: 3, title: 'Менеджер' },
          { id: 4, title: 'Аналитик' }
        ]
      };
    },
    computed: {
      selectedPositionTitles() {
        return this.selectedPositions.map(id => {
          const position = this.positions.find(pos => pos.id === id);
          return position ? position.title : '';
        });
      }
    },
    methods: {
      submitForm() {
        const newDepartment = {
          name: this.departmentName,
          positions: this.selectedPositions
        };
        console.log('Создан новый отдел:', newDepartment);
        
        // Здесь можно добавить логику для отправки данных на сервер
  
        // Сброс формы после отправки
        this.departmentName = '';
        this.selectedPositions = [];
      }
    }
  };
  </script>