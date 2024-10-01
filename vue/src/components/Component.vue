<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Авторизация</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" @submit.prevent="loginUser">
        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email-адрес</label>
          <div class="mt-2">
            <input
              id="email"
              name="email"
              type="email"
              v-model="formData.email"
              autocomplete="email"
              required
              class="input"
            />
            <span v-if="errorMessage.email" class="text-red-500">{{ errorMessage.email }}</span>
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>
            <div class="text-sm">
              <router-link class="font-semibold text-red-600 hover:text-neutral-400" to="/#">Забыли пароль?</router-link>
            </div>
          </div>
          <div class="mt-2">
            <input
              id="password"
              name="password"
              type="password"
              v-model="formData.password"
              autocomplete="current-password"
              required
              class="input"
            />
            <span v-if="errorMessage.password" class="text-red-500">{{ errorMessage.password }}</span>
          </div>
        </div>

        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Войти</button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Ещё не зарегистрированы?
        {{ ' ' }}
        <router-link class="font-semibold leading-6 text-red-600 hover:text-neutral-400" to="/auth/register">Регистрация</router-link>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formData: {
        email: '',
        password: ''
      },
      errorMessage: {}
    };
  },
  methods: {
    validateInput() {
      this.errorMessage = {};
      // Валидация полей
      if (!this.formData.email) {
        this.errorMessage.email = 'Email обязателен';
      } else if (!/\S+@\S+\.\S+/.test(this.formData.email)) {
        this.errorMessage.email = 'Введите корректный Email';
      }

      if (!this.formData.password) {
        this.errorMessage.password = 'Пароль обязателен';
      } else if (this.formData.password.length < 6) {
        this.errorMessage.password = 'Пароль должен содержать не менее 6 символов';
      }
    },
    loginUser() {
      this.validateInput();
      if (Object.keys(this.errorMessage).length === 0) {
        console.log('Авторизация успешна', this.formData);
      }
    }
  }
};
</script>
