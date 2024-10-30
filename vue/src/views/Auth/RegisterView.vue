<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Регистрация</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form v-if="step === 1" class="space-y-6" @submit.prevent="nextStep">
        <div>
          <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Имя</label>
          <div class="mt-2">
            <input id="name" name="name" v-model="formData.name" required class="input_text" />
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
          <div class="mt-2">
            <input id="email" name="email" type="email" v-model="formData.email" required class="input_text" />
          </div>
        </div>

        <div>
          <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Телефон</label>
          <div class="mt-2">
            <input id="phone" name="phone" v-model="formData.phone" required class="input_text" />
          </div>
        </div>

        <div>
          <label for="city" class="block text-sm font-medium leading-6 text-gray-900">Город</label>
          <div class="mt-2">
            <input id="city" name="city" v-model="formData.city" required class="input_text" />
          </div>
        </div>

        <div>
          <label for="birthday" class="block text-sm font-medium leading-6 text-gray-900">Дата рождения</label>
          <div class="mt-2">
            <input id="birthday" name="birthday" type="date" v-model="formData.birthday" class="input_text" />
          </div>
        </div>

        <div>
          <label for="github" class="block text-sm font-medium leading-6 text-gray-900">Ссылка на GitHub</label>
          <div class="mt-2">
            <input id="github" name="github" v-model="formData.github" required class="input_text" />
          </div>
        </div>

        <label for="type" class="block text-sm font-medium leading-6 text-gray-900">Тип</label>
        <div class="mt-2">
          <select id="type" name="type" v-model="formData.type" class="input_text">
            <option value="" disabled>Выберите тип</option>
            <option value="front">Frontend</option>
            <option value="back">Backend</option>
          </select>
        </div>

        <div>
          <button type="submit"
            class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400">Далее</button>
        </div>
      </form>

      <form v-if="step === 2" class="space-y-6" @submit.prevent="register">
        <div>
          <label for="login" class="block text-sm font-medium leading-6 text-gray-900">Логин</label>
          <div class="mt-2">
            <input id="login" name="login" v-model="formData.login" required class="input_text" />
          </div>

          <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>
          <div class="mt-2">
            <input id="password" name="password" type="password" v-model="formData.password" class="input_text" />
            <span v-if="errorMessage.password" class="text-red-500">{{ errorMessage.password }}</span>
          </div>
        </div>

        <div>
          <button type="submit"
            class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400">Зарегистрироваться</button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm text-gray-500">
        Уже зарегистрированы?
        {{ ' ' }}
        <router-link class="font-semibold leading-6 text-red-600 hover:text-neutral-400"
          to="/auth/login">Войти</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { registerUser } from '../../services/api/auth';

export default {
  data() {
    return {
      step: 1,
      formData: {
        name: '',
        email: '',
        phone: '',
        city: '',
        birthday: '',
        github: '',
        login: '',
        password: '',
        type: ''
      },
      errorMessage: {}
    };
  },
  methods: {
    nextStep() {
      this.step = 2;
    },
    async register() {
      try {
        const response = await registerUser(this.formData);
        console.log(response);
        if (response.access_token) {
          localStorage.setItem('token', response.access_token);
          this.$router.push('/auth/login');
        }
      } catch (error) {
        if (error.response) {
          const statusCode = error.response.status;
          const errorData = error.response.data;

          switch (statusCode) {
            case 400:
              this.errorMessage = { password: errorData.message };
              break;
            case 408:
              alert('Слишком много пользователей было зарегистрировано за последний час');
              break;
            case 409:
              alert('Пользователь с такой почтой уже существует');
              break;
            case 500:
              if (errorData.validations && errorData.validations.type) {
                this.errorMessage.type = errorData.validations.type[0];
              } else {
                alert(errorData.message);
              }
              break;
            default:
              alert('Произошла неизвестная ошибка. Пожалуйста, попробуйте позже.');
          }
        } else {
          alert('Ошибка сети или сервера. Пожалуйста, проверьте подключение.');
        }
      }
    }
  }
};
</script>