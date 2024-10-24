<template>
    <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-2 lg:px-8">
      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
        <form class="max-w-lg mx-auto p-4 bg-white shadow-md rounded flex flex-col gap-5" form @submit.prevent="addUser" enctype="multipart/form-data">
          <div>
            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Имя</label>
            <div class="mt-2">
              <input id="name" name="name" v-model="formData.name" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
            <div class="mt-2">
              <input id="email" name="email" type="email" v-model="formData.email" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Телефон</label>
            <div class="mt-2">
              <input id="phone" name="phone" v-model="formData.phone" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Город</label>
            <div class="mt-2">
              <input id="city" name="city" v-model="formData.city" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900">Дата рождения</label>
            <div class="mt-2">
              <input id="birthday" name="birthday" type="date" v-model="formData.birthday" class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="github" class="block text-sm font-medium leading-6 text-gray-900">Ссылка на GitHub</label>
            <div class="mt-2">
              <input id="github" name="github" v-model="formData.github" required class="input_text" />
            </div>
          </div>
  
          <label for="type" class="block text-sm font-medium leading-6 text-gray-900">Тип</label>
          <div class="mt-2">
            <select id="type" name="type" v-model="formData.type" required class="input_text">
              <option value="" disabled>Выберите тип</option>
              <option value="front">Frontend</option>
              <option value="back">Backend</option>
            </select>
          </div>
  
          <!-- Поле о себе -->
          <div>
            <label for="about" class="block text-sm font-medium leading-6 text-gray-900">О себе</label>
            <div class="mt-2">
              <textarea id="about" name="about" v-model="formData.about" rows="3" class="input_text"></textarea>
            </div>
          </div>
  
          <!-- Загрузка изображения -->
          <div>
            <label for="image" class="block text-sm font-medium leading-6 text-gray-900">Изображение профиля</label>
            <div class="mt-2">
              <input id="image" name="image" type="file" @change="handleFileUpload" accept=".jpg, .jpeg, .png, .webp" />
            </div>
          </div>

          <div>
            <label for="login" class="block text-sm font-medium leading-6 text-gray-900">Логин</label>
            <div class="mt-2">
              <input id="login" name="login" v-model="formData.login" required class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>
            <div class="mt-2">
                <input id="password" name="password" type="password" v-model="formData.password" required class="input_text" />
          </div>
        </div>
  
          <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400">Добавить</button>
          </div>
        </form>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
import { AddUser } from '../services/api/auth';

const emit = defineEmits(['UserAdd']);
const showForm = ref(true);

const formData = ref(new FormData());

function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    formData.value.append('image', file); // Добавляем файл в FormData
  }
}

async function addUser() {
  formData.value.append('name', formData.value.name);
  formData.value.append('email', formData.value.email);
  formData.value.append('phone', formData.value.phone);
  formData.value.append('city', formData.value.city);
  formData.value.append('birthday', formData.value.birthday);
  formData.value.append('github', formData.value.github);
  formData.value.append('type', formData.value.type);
  formData.value.append('about', formData.value.about);
  formData.value.append('login', formData.value.login);
  formData.value.append('password', formData.value.password);

  console.log('sss',formData.value);
  emit('UserAdd', formData.value); // Передаем данные родительскому компоненту
  await AddUser(formData.value);
  
  // Очищаем форму после добавления пользователя
  formData.value = new FormData(); 
}
</script>