<template>
    <section v-if="isAuthenticated" class="p-10">
        <router-link v-if="user.worker_id" to="/users" class="return flex items-center text-black text-lg">
                Назад
            </router-link>
            <router-link v-else to="/deepartments" class="return flex items-center text-black text-lg">
                Назад
            </router-link>
        <div class="bg-zinc-100 rounded-xl relative mx-auto flex h-full w-full max-w-[900px] flex-col items-center p-[16px]">
            
            <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover" style="background-image: url('../../public/bg-profile.jpg');">
                <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400">
                    <img class="h-full w-full rounded-full bg-white" :src="`http://127.0.0.1:8000/storage/${user.image}`"  alt="avatar" />
                </div>
            </div>
            <div class="mt-16 flex flex-col items-center">
                <h4 class="text-bluePrimary text-xl font-bold text-center">{{ user.name }}</h4>
                <p v-if="user.worker_id" class="text-lightSecondary text-base font-normal text-center">{{ user.position }}</p>
            </div>
            <div class="mt-6 mb-3 flex flex-col gap-2">
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Логин:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.login }}</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Email:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.email }}</p>
                </div>
                <div v-if="user.worker_id">
                    <h5 class="text-bluePrimary text-xl font-bold">Отдел:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ departmentName }}</p>
                </div>
                <div v-if="user.worker_id">
                    <h5 class="text-bluePrimary text-xl font-bold">Зачислен:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.adopted_at }}</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">О себе:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.about }}</p>
                </div>
                <router-link :to="{ name: 'UserProfileEdit', params: { id: user.id } }" class="bg-bluePrimary font-bold py-2 px-4 rounded hover:bg-red-500 hover:text-white w-full text-center block">
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
        user.value = JSON.parse(storedUser);
        if (user.value.worker_id) {
            isAuthenticated.value = true;
            fetchWorkerData(user.value.worker_id); // Получаем данные работника по worker_id
        } else {
            isAuthenticated.value = true;
        }
    } else {
        isAuthenticated.value = false;
    }
};

const fetchWorkerData = async (workerId) => {
    try {
        const workerResponse = await axios.get(`api/workers/${workerId}`);
        const { position_id, department_id, adopted_at } = workerResponse.data; 
        user.value.adopted_at = adopted_at;
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
        user.value.position = positionName.value;
    } catch (error) {
        console.error('Ошибка при получении данных отдела и должности:', error);
    }
};

onMounted(() => {
    checkAuthentication();
});
</script>