<template class="w-full ">
    <div class="flex flex-wrap items-center w-full p-4 justify-center">
    <h1 class="mb-5">
        <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Отделы:</span>
    </h1>
    <SearchInput class="w-full max-w-md mb-4"></SearchInput>
    <a href="#" class="btn">Добавить запись</a>
    </div>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-[5%]">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th class="px-6 py-3">Название отдела</th>
            <th class="px-6 py-3">Должности</th>
            <th scope="col" class="px-6 py-3"><span>Действия</span></th>
        </tr>
    </thead>
    <tbody>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">Отдел разработки</th>
            <td class="px-6 py-4">
                <ul class="flex flex-wrap gap-1">
                    <li><a href="#">Frontend</a></li>
                    <li><a href="#">Backend</a></li>
                </ul>
            </td>
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