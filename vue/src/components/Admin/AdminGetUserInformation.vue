<template>
  <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full max-h-[80vh] overflow-y-auto">
      <h2 class="text-lg font-semibold text-gray-900">Просмотр пользователя</h2>
      <div class="mt-4">
        <p><strong>ID:</strong> {{ formData.id }}</p>
        <p><strong>Имя:</strong> {{ formData.name }}</p>
        <p><strong>Логин:</strong> {{ formData.login }}</p>
        <p><strong>Email:</strong> {{ formData.email }}</p>
        <p><strong>Телефон:</strong> {{ formData.phone }}</p>
        <p><strong>Город:</strong> {{ formData.city }}</p>
        <p><strong>Дата рождения:</strong> {{ formData.birthday }}</p>
        <p><strong>GitHub:</strong> {{ formData.github }}</p>
        <p><strong>Тип:</strong> {{ formData.type }}</p>
        <p><strong>О себе:</strong> {{ formData.about }}</p>
      </div>
      <div class="flex justify-end mt-4">
        <button type="button" @click="$emit('close')"
          class="bg-gray-300 hover:bg-gray-400 rounded px-4 py-2">Закрыть</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isVisible: {
      type: Boolean,
      required: true
    },
    formData: {
      type: Object,
      required: true
    }
  }
};
</script>
