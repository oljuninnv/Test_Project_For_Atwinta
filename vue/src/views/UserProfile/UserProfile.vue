<template>
    <section v-if="isAuthenticated" class="p-10">
        <router-link v-if="user.user.worker_id" to="/users" class="return flex items-center text-black text-lg">
                Назад
            </router-link>
            <router-link v-else to="/departments" class="return flex items-center text-black text-lg">
                Назад
            </router-link>
        <div class="bg-zinc-100 rounded-xl relative mx-auto flex h-full w-full max-w-[900px] flex-col items-center p-[16px]">           
            <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover" style="background-image: url('../../public/bg-profile.jpg');">
                <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400">
                    <img class="h-full w-full rounded-full bg-white" :src="`http://127.0.0.1:8000/storage/${user.user.image}`"  alt="avatar" />
                </div>
            </div>
            <div class="mt-16 flex flex-col items-center">
                <h4 class="text-bluePrimary text-xl font-bold text-center">{{ user.user.name }}</h4>
                <p v-if="user.user.worker_id" class="text-lightSecondary text-base font-normal text-center">{{ user.user.position }}</p>
            </div>
            <div class="mt-6 mb-3 flex flex-col gap-2">
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Логин:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.user.login }}</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Email:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.user.email }}</p>
                </div>
                <div v-if="user.user.worker_id">
                    <h5 class="text-bluePrimary text-xl font-bold">Отдел:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ departmentName }}</p>
                </div>
                <div v-if="user.user.worker_id">
                    <h5 class="text-bluePrimary text-xl font-bold">Зачислен:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.user.adopted_at }}</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">О себе:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.user.about }}</p>
                </div>
                <router-link :to="{ name: 'UserProfileEdit', params: { id: user.user.id } }" class="bg-bluePrimary font-bold py-2 px-4 rounded hover:bg-red-500 hover:text-white w-full text-center block">
                    Редактировать информацию
                </router-link>
            </div>            
        </div>      
    </section>
    <section v-else>
        <div  class="text-center text-xl">Ошибка 404: Страница не найдена</div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '../../libs/axios';

const user = ref({});
const isAuthenticated = ref(false);
const departmentName = ref(''); 
const positionName = ref(''); 

const checkAuthentication = () => {
    const storedUser = localStorage.getItem('UserData');
    if (storedUser) {
        try {
            user.value = JSON.parse(storedUser);
            // Проверяем, существует ли user и worker_id
            if (user.value && user.value.user && user.value.user.worker_id) {
                console.log('Worker: ' + user.value.user.worker_id);
                isAuthenticated.value = true;
                fetchWorkerData(user.value.user.worker_id); // Получаем данные работника по worker_id
            } else {
                isAuthenticated.value = true;
                console.log('User: ' + (user.value.user ? user.value.user.worker_id : 'Не указано'));
            }
        } catch (error) {
            console.error('Ошибка парсинга UserData:', error);
            isAuthenticated.value = false;
        }
    } else {
        isAuthenticated.value = false;
    }
};

const fetchWorkerData = async (workerId) => {
    try {
        const workerResponse = await axios.get(`api/workers/${workerId}`);
        const { position_id, department_id, adopted_at } = workerResponse.data; 
        user.value.user.adopted_at = adopted_at;
        await fetchDepartmentAndPosition(department_id, position_id); 
    } catch (error) {
        console.error('Ошибка при получении данных работника:', error);
    }
};
const fetchDepartmentAndPosition = async (departmentId, positionId) => {
    try {
        const [departmentResponse, positionResponse] = await Promise.all([
            axios.get(`api/departments/${departmentId}`),
            axios.get(`api/positions/${positionId}`)
        ]);

        departmentName.value = departmentResponse.data.name; 
        positionName.value = positionResponse.data.name; 
        user.value.user.position = positionName.value;
    } catch (error) {
        console.error('Ошибка при получении данных отдела и должности:', error);
    }
};

onMounted(() => {
    checkAuthentication();
});
</script>