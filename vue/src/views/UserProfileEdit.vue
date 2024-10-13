<template>
    <div class="absolute top-4 left-4 flex items-center">
        <span class="mr-2 w-5 h-5 bg-no-repeat bg-left-center bg-contain" style="background-image: url('../public/return.png');"></span>
          <router-link to="/profile" class="return flex items-center text-black text-lg">
              Назад
          </router-link>
   </div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <form class="mt-8 w-full max-w-[600px] bg-white p-6 rounded-lg shadow-md">
            <h5 class="text-bluePrimary text-xl font-bold mb-4 w-full text-center block">Редактировать информацию о себе</h5>

            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold mb-2">Логин:</label>
                <input type="text" id="username" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите логин..." required />
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" id="email" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите email..." required />
            </div>

            <div class="mb-4">
                <label for="about" class="block text-gray-700 font-bold mb-2">О себе:</label>
                <textarea id="about" rows="4" class="border rounded w-full py-2 px-3 text-gray-700" placeholder="Введите информацию о себе..."></textarea>
            </div>

            <div class="mb-4">
                <label for="avatar" class="block text-gray-700 font-bold mb-2">Изображение пользователя:</label>
                <input type="file" id="avatar" accept="image/*" class="border rounded w-full py-2 px-3 text-gray-700" />
            </div>

            <button type="submit" class="bg-bluePrimary font-bold py-2 px-4 rounded hover:bg-red-500 hover:text-white w-full">Сохранить изменения</button>
        </form>
    </div>
</template>
