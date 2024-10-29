<template>
    <div v-if="isAuthenticated">
        <div class="absolute top-4 left-4 flex items-center">
            <router-link to="/profile" class="return flex items-center text-black text-lg">
                Назад
            </router-link>
        </div>
        <div class="flex items-center justify-center min-h-screen bg-gray-100 mb-5">
            <form @submit.prevent="saveChanges" class="mt-8 w-full max-w-[600px] bg-white p-6 rounded-lg shadow-md">
                <h5 class="text-bluePrimary text-xl font-bold mb-4 w-full text-center block">Редактировать информацию о себе</h5>

                <div class="mb-4">
                    <label for="username" class="block text-gray-700 font-bold mb-2">Имя:</label>
                    <input type="text" id="username" v-model="userData.name" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите имя..." required />
                </div>

                <div class="mb-4">
                    <label for="login" class="block text-gray-700 font-bold mb-2">Логин:</label>
                    <input type="text" id="login" v-model="userData.login" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите логин..." required />
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input type="email" id="email" v-model="userData.email" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите email..." required />
                </div>

                <div class="mb-4">
                    <label for="city" class="block text-gray-700 font-bold mb-2">Город:</label>
                    <input type="text" id="city" v-model="userData.city" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите город..." required />
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Телефон:</label>
                    <input type="text" id="phone" v-model="userData.phone" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите номер..." required />
                </div>

                <div class="mb-4">
                    <label for="github" class="block text-gray-700 font-bold mb-2">GitHub:</label>
                    <input type="text" id="github" v-model="userData.github" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите github..." required />
                </div>

                <div class="mb-4">
                    <label for="about" class="block text-gray-700 font-bold mb-2">О себе:</label>
                    <textarea id="about" v-model="userData.about" rows="4" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите информацию о себе..."></textarea>
                </div>

                <div class="mb-4">
                    <label for="avatar" class="block text-gray-700 font-bold mb-2">Изображение пользователя:</label>
                    <input type="file" id="avatar" accept="image/*" @change="handleFileUpload" class="border rounded w-full py-2 px-3 text-gray-700" />
                </div>

                <button type="submit" class="bg-bluePrimary font-bold py-2 px-4 rounded hover:bg-red-500 hover:text-white w-full">Сохранить изменения</button>
            </form>
        </div>
    </div>
    <div v-else class="text-center text-xl">Ошибка 404: Страница не найдена</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from '../../libs/axios';

const img = ref('')
const route = useRoute();
const router = useRouter(); // Инициализируем router
const userData = ref({
    name: '',
    login: '',
    email: '',
    about: '',
    city: '',
    phone: '',
    github: '',
    type: '',
    image: null
});
const isAuthenticated = ref(false);

onMounted(() => {
    const storedUser = localStorage.getItem('UserData');
    if (storedUser) {
        const user = JSON.parse(storedUser);
        if (user.id == route.params.id) {
            img.value = user.image;
            userData.value = { ...user, image:null }; // Копируем данные пользователя
            isAuthenticated.value = true;
        } else {
            isAuthenticated.value = false;
        }
    }
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        userData.value.image = file; // Сохраняем файл в userData
    }
};

const saveChanges = async () => {
    try {
        const formData = new FormData();
        formData.append('name', userData.value.name);
        formData.append('email', userData.value.email);
        formData.append('phone', userData.value.phone);
        formData.append('city', userData.value.city);
        formData.append('github', userData.value.github);
        formData.append('about', userData.value.about);
        formData.append('type', userData.value.type);
        formData.append('login', userData.value.login);

        if (userData.value.image != null) {
            formData.append('image', userData.value.image, userData.value.image.name);
        }    

        formData.append('_method', 'put');

        const response = await axios.post(`/api/users/${route.params.id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        console.log(response);
        console.log('Сохраненные данные:', userData.value.image);
        userData.value.image = img.value;
        console.log(userData.value.image);
        localStorage.setItem('UserData', JSON.stringify(userData.value));
        router.push('/profile'); // Переадресация на страницу профиля
    } catch (error) {
        console.error('Ошибка при изменении пользователя:', error); // Логируем ошибку
    }
};
</script>