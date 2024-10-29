<template>
    <section class="p-5"> 
        <div>
            <div v-if="isAuthorized && hasWorkerId">             
                <UserCard></UserCard>
            </div>       
            <div v-else-if="!isAuthorized || !hasWorkerId" class="text-center text-xl">Ошибка 404: Страница не найдена</div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import UserCard from "../../components/Users/UserCard.vue";
import SearchInput from "../../components/SearchInput.vue"

const userData = ref(null);

const userImage = ref('null');


const fetchUserData = () => {
    const userDataString = localStorage.getItem('UserData');
    if (userDataString) {
        userData.value = JSON.parse(userDataString);
        console.log('Данные пользователя:', userImage.value);
    } else {
        userData.value = null;
        console.log('Данные пользователя не найдены в localStorage.');
    }
};

onMounted(() => {
    fetchUserData();
});

// Проверка авторизации
const isAuthorized = computed(() => {
    return userData.value !== null; // Проверяем, есть ли данные пользователя
});

// Проверка наличия worker_id
const hasWorkerId = computed(() => {
    console.log(userData.value.image)
    return userData.value && userData.value.worker_id !== null;
});

</script>