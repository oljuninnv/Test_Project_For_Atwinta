<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Восстановление пароля</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" @submit.prevent="sendRestoreEmail">
        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email-адрес</label>
          <div class="mt-2">
            <input
              id="email"
              name="email"
              type="email"
              v-model="email"
              required
              class="input_text"
            />
          </div>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Отправить код</button>
        </div>

        <p v-if="message" class="text-green-500">{{ message }}</p>
        <p v-if="errorMessage" class="text-red-500">{{ errorMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script>
import { restorePassword } from '../services/api/auth';

export default {
  data() {
    return {
      email: '',
      message: '',
      errorMessage: ''
    };
  },
  methods: {
    async sendRestoreEmail() {
      try {
        await restorePassword({ email: this.email });
        this.message = 'Код для восстановления пароля был отправлен на ваш email.';
        this.errorMessage = '';
      } catch (error) {
        this.errorMessage = 'Ошибка при отправке кода. Пожалуйста, проверьте введенный email.';
        this.message = '';
      }
    }
  }
};
</script>