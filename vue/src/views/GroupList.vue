<template>
    <section class="p-5">
        <SearchInput></SearchInput>
        <h2 class="text-bluePrimary text-xl font-bold text-center">Группы</h2>
        <GroupCard></GroupCard>
    </section>
</template>

<script setup>
    import GroupCard from "../components/GroupCard.vue"
    import SearchInput from "../components/SearchInput.vue"
</script>