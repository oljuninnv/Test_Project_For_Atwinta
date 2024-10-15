<template class="w-full ">
    <div class="flex flex-wrap items-center w-full p-4 justify-center">
    <h1 class="mb-5">
        <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Пользователи:</span>
    </h1>
    <SearchInput class="w-full max-w-md mb-4"></SearchInput>
    <a href="#" class="btn">Добавить запись</a>
    </div>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-[5%]">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th class="px-6 py-3">Имя пользователя</th>
            <th class="px-6 py-3">Почта</th>
            <th class="px-6 py-3">Логин</th>
            <th class="px-6 py-3">Изображение</th>
            <th class="px-6 py-3">Город</th>
            <th class="px-6 py-3">Телефон</th>
            <th class="px-6 py-3">День рождения</th>
            <th class="px-6 py-3">Тип</th>
            <th class="px-6 py-3">GitHub</th>
            <th scope="col" class="px-6 py-3"><span>Действия</span></th>
        </tr>
    </thead>
    <tbody>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">Иван Иванов</th>
            <td class="px-6 py-4">ivan.ivanov@example.com</td>
            <td class="px-6 py-4">ivan123</td>
            <td class="px-6 py-4">image/url</td>
            <td class="px-6 py-4">Москва</td>
            <td class="px-6 py-4">+7 123 456 78 90</td>
            <td class="px-6 py-4">01.01.1990</td>
            <td class="px-6 py-4">Backend</td>
            <td class="px-6 py-4">https://github.com/ivan</td>
            <td class="px-6 py-4 text-right">
                <ul class="flex gap-5 text-right">
                    <li><a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a></li>
                    <li><a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a></li>
                </ul>                     
            </td>
        </tr>
        <!-- Добавьте дополнительные строки пользователей по аналогии -->
    </tbody>
</table>
</div>
</template>

<script setup>
    import SearchInput from "../components/SearchInput.vue"
</script>