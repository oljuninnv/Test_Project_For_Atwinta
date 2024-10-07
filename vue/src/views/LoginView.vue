<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Авторизация</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" @submit.prevent="login">
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
              class="input_text"
            />
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
              class="input_text"
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
import { loginUser } from '../services/api/auth';

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
      if (!this.formData.password) {
        this.errorMessage.password = 'Пароль обязателен';
      }
    },
    async login() {
      this.validateInput();
      if (Object.keys(this.errorMessage).length === 0) {
        try {
          const response = await loginUser(this.formData);
          console.log(response);
          alert('Авторизация прошла успешно');
          if (response.data) {
            localStorage.setItem('token', response.token);
            this.$router.push('/users');
          }
        } catch (error) {
          if (error.response) {
            switch (error.response.status) {
              case 406:
                alert('Пользователь не подтвердил почту');
                break;
              case 408:
                alert('Ошибка в заполнении данных');
                break;
              case 500:
                alert('Неправильный логин или пароль. Попробуйте заново');
                break;
              default:
                alert('Произошла неизвестная ошибка. Попробуйте позже.');
                console.error('Произошла ошибка:', error);
            }
          } else {
            alert('Проблема с сетью. Проверьте подключение к интернету.');
            console.error('Ошибка сети:', error);
          }
        }
      }
    }
  }
};
</script>