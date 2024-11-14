<template>
    <section class="p-10">
        <div
            class="bg-zinc-100 rounded-xl relative mx-auto flex h-full w-full max-w-[900px] flex-col items-center p-[16px]">
            <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover"
                style="background-image: url('../../public/bg-profile.jpg');">
                <div
                    class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400">
                    <img v-if="user.image" class="h-full w-full rounded-full"
                        :src="`http://127.0.0.1:8000/storage/${user.image}`" alt="User image" />
                    <img v-else class="h-full w-full rounded-full" src="../../../public/default.png" alt="User image" />
                </div>
            </div>
            <div class="mt-16 flex flex-col items-center">
                <h4 class="text-bluePrimary text-xl font-bold text-center">{{ user.name }}</h4>
                <p class="text-lightSecondary text-base font-normal text-center">{{ user.position }}</p>
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
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Отдел:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.department }}</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Зачислен:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.adopted_at }}</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">О себе:</h5>
                    <p class="text-lightSecondary text-base font-normal">{{ user.about }}</p>
                </div>
                <button @click="goBack" class="mb-4 px-4 py-2 hover:text-white hover:bg-red-500 rounded">
                    Назад
                </button>
            </div>
        </div>
    </section>
</template>

<script>
import { useRoute } from "vue-router";
import axios from '../../libs/axios';

export default {
    data() {
        return {
            user: {}
        };
    },
    mounted() {
        this.fetchUserData();
    },
    methods: {
        async fetchUserData() {
            console.log(this.$route.params.id)
            try {
                const workerId = this.$route.params.id; // Получаем workerId из параметров маршрута
                const response = await axios.get(`api/user/${workerId}`);
                this.user = response.data;
            } catch (error) {
                console.error('Ошибка при получении данных пользователя:', error);
            }
        },
        goBack() {
            this.$router.go(-1); // Возвращаемся на предыдущую страницу
        }
    }
};
</script>
