<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { ref } from '@vue/reactivity';
import logo from '../../img/logo.png'

const logoImg = ref(logo);
</script>

<template>
    <Link :href="'/'">
    <img class="h-20" :src="logoImg" alt="logo" />
    </Link>
</template>

