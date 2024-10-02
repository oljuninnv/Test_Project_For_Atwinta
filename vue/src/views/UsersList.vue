<template>
    <section class="p-5">
        <SearchInput></SearchInput>
        <h2 class="text-bluePrimary text-xl font-bold text-center">Сотрудники отдела разработки</h2>
        <UserCard></UserCard>
    </section>
</template>

<script setup>
    import UserCard from "../components/UserCard.vue"
    import SearchInput from "../components/SearchInput.vue"
</script>