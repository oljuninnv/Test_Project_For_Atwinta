<template>
    <section class="p-5"> 
        <div>
            <div v-if="isAuthorized && hasWorkerId">
                <SearchInput></SearchInput>
                <UserCard></UserCard>
            </div>       
            <div v-else-if="!isAuthorized || !hasWorkerId" class="text-center text-xl">Ошибка 404: Страница не найдена</div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import UserCard from "../components/UserCard.vue";
import SearchInput from "../components/SearchInput.vue"

// Данные пользователя, полученные из localStorage
const userData = ref(null);

// Функция для получения данных пользователя из localStorage
const fetchUserData = () => {
    const userDataString = localStorage.getItem('UserData');
    if (userDataString) {
        userData.value = JSON.parse(userDataString);
        console.log('Данные пользователя:', userData.value);
    } else {
        userData.value = null;
        console.log('Данные пользователя не найдены в localStorage.');
    }
};

// Вызов функции при монтировании компонента
onMounted(() => {
    console.log('Компонент монтируется...');
    fetchUserData();
});

// Проверка авторизации
const isAuthorized = computed(() => {
    return userData.value !== null; // Проверяем, есть ли данные пользователя
});

// Проверка наличия worker_id
const hasWorkerId = computed(() => {
    return userData.value && userData.value.worker_id !== null;
});
</script>