<template>
    <section class="p-10">
        <div class="bg-zinc-100 rounded-xl relative mx-auto flex h-full w-full max-w-[900px] flex-col items-center p-[16px]">
            <div class="relative mt-1 flex h-32 w-full justify-center rounded-xl bg-cover" style='background-image: url("https://static.tildacdn.com/tild6136-3234-4133-a464-323131643966/ZRIs1bGpmRo_1x.jpg");'>
                <div class="absolute -bottom-12 flex h-[88px] w-[88px] items-center justify-center rounded-full border-[4px] border-white bg-pink-400">
                    <img class="h-full w-full rounded-full" src="https://i.ibb.co/6YbS9ff/avatar11.png" alt="avatar" />
                </div>
            </div>
            <div class="mt-16 flex flex-col items-center">
                <h4 class="text-bluePrimary text-xl font-bold text-center">Майя Владимировна Григорьевна</h4>
                <p class="text-lightSecondary text-base font-normal text-center">Продукт-менеджер</p>
            </div>
            <div class="mt-6 mb-3 flex flex-col gap-2">
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Логин:</h5>
                    <p class="text-lightSecondary text-base font-normal">Jennyfer_1472</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Email:</h5>
                    <p class="text-lightSecondary text-base font-normal">mcclure.clara@yahoo.com</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Отдел:</h5>
                    <p class="text-lightSecondary text-base font-normal">Отдел управления проектами</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">Зачислен:</h5>
                    <p class="text-lightSecondary text-base font-normal">04.08.2018</p>
                </div>
                <div>
                    <h5 class="text-bluePrimary text-xl font-bold">О себе:</h5>
                    <p class="text-lightSecondary text-base font-normal">Ну, я был твоим начальником, я бы мог выйти очень, очень лакомый кусочек...</p>
                </div>
                <router-link to="/profile/edit" class="bg-bluePrimary font-bold py-2 px-4 rounded hover:bg-red-500 hover:text-white w-full text-center block">
                    Редактировать информацию
                </router-link>
            </div>            
        </div>
    </section>
</template>