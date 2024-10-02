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
            <label for="birthDate" class="block text-sm font-medium leading-6 text-gray-900">Дата рождения</label>
            <div class="mt-2">
              <input id="birthDate" name="birthDate" type="date" v-model="formData.birthDate" class="input_text" />
            </div>
          </div>
  
          <div>
            <label for="githubLink" class="block text-sm font-medium leading-6 text-gray-900">Ссылка на GitHub</label>
            <div class="mt-2">
              <input id="githubLink" name="githubLink" v-model="formData.githubLink" required class="input_text" />
            </div>
          </div>
  
          <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400">Далее</button>
          </div>
        </form>
  
        <form v-if="step === 2" class="space-y-6" @submit.prevent="registerUser">
          <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Пароль</label>
            <div class="mt-2">
              <input id="password" name="password" type="password" v-model="formData.password" required class="input_text" />
              <span v-if="errorMessage.password" class="text-red-500">{{ errorMessage.password }}</span>
            </div>
          </div>
  
          <div>
            <button type="submit" class="flex w-full justify-center rounded-md bg-red-500 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-neutral-400">Зарегистрироваться</button>
          </div>
        </form>
  
        <p class="mt-10 text-center text-sm text-gray-500">
          Уже зарегистрированы?
          {{ ' ' }}
          <router-link class="font-semibold leading-6 text-red-600 hover:text-neutral-400" to="/auth/login">Войти</router-link>
        </p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        step: 1,
        formData: {
          name: '',
          email: '',
          phone: '',
          city: '',
          birthDate: '',
        githubLink: '',
        password: ''
      },
      errorMessage: {}
    };
  },
  methods: {
    nextStep() {
      this.errorMessage = {};
      // Здесь можно добавить валидацию данных первого этапа
      if (this.formData.name && this.formData.email) { // Пример проверки
        this.step = 2;
      } else {
        // Установите сообщения об ошибках
      }
    },
    registerUser() {
      this.errorMessage = {};
      if (!this.formData.password) {
        this.errorMessage.password = 'Пароль обязателен';
      }
      
      if (Object.keys(this.errorMessage).length === 0) {
        // Здесь можно отправить данные на сервер
        console.log('Регистрация успешна', this.formData);
      }
    }
  }
};
</script>