<template>
  <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto">
      <h2 class="text-lg font-semibold text-gray-900">Редактирование пользователя</h2>
      <form @submit.prevent="updateUser" class="flex flex-col gap-5 mt-4" enctype="multipart/form-data">
        <div>
          <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Имя</label>
          <input id="name" name="name" v-model="formData.name" required class="input_text mt-2" />
        </div>

        <div>
          <label for="login" class="block text-sm font-medium leading-6 text-gray-900">Логин</label>
          <input id="login" name="login" v-model="formData.login" required class="input_text mt-2" />
        </div>

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
          <input id="email" name="email" type="email" v-model="formData.email" required class="input_text mt-2" />
        </div>

        <div>
          <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Телефон</label>
          <input id="phone" name="phone" v-model="formData.phone" required class="input_text mt-2" />
        </div>

        <div>
          <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Город</label>
          <input id="city" name="city" v-model="formData.city" required class="input_text mt-2" />
        </div>

        <div>
          <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900">Дата рождения</label>
          <input id="birthday" name="birthday" type="date" v-model="formData.birthday" class="input_text mt-2" />
        </div>

        <div>
          <label for="github" class="block text-sm font-medium leading-6 text-gray-900">Ссылка на GitHub</label>
          <input id="github" name="github" v-model="formData.github" required class="input_text mt-2" />
        </div>

        <div>
          <label for="type" class="block text-sm font-medium leading-6 text-gray-900">Тип</label>
          <select id="type" name="type" v-model="formData.type" required class="input_text mt-2">
            <option value="" disabled>Выберите тип</option>
            <option value="front">Frontend</option>
            <option value="back">Backend</option>
          </select>
        </div>

        <div>
          <label for="about" class="block text-sm font-medium leading-6 text-gray-900">О себе</label>
          <textarea id="about" name="about" v-model="formData.about" rows="3" class="input_text mt-2"></textarea>
        </div>

        <div>
          <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Изображение профиля</label>
          <input id="image" name="image" type="file"  @change="handleFileUpload" accept=".jpg, .jpeg, .png, .webp" class="mt-2"/>
        </div>

        <div>
          <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Было ли закончено задание</label>
          <input 
            id="is_finished" 
            name="is_finished" 
            type="checkbox" 
            v-model="formData.is_finished" 
            class="mt-2"
          />
      </div>

        <div class="flex justify-end mt-4">
          <button type="button" @click="$emit('close')" class="mr-2 bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Отмена</button>
          <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue';

const props = defineProps({
  isVisible: {
    type: Boolean,
    required: true
  },
  user: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['close', 'user-updated']);

const formData = ref({});

// Инициализация formData при изменении props.user
watch(() => props.user, (newUser) => {
  formData.value = { ...newUser };
}, { immediate: true });

function updateUser() {
  formData.value.is_finished = formData.value.is_finished ? true : false;
  console.log(formData.value.about);
  emit('user-updated', formData.value); // Передаем данные для обновления
}

function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    formData.value.image = file; // Добавляем файл в FormData
  }
}
</script>