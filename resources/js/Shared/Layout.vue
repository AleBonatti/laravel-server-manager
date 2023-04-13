<template>
    <Nav />
    <!-- Main column -->
    <div class="flex flex-col lg:pl-64 content-between h-screen">
        <main class="flex-1 pb-20 lg:pb-16">
            <slot />
        </main>
        <!-- footer -->
        <footer class="sm:mx-6 lg:mx-8 pb-3 pl-3 pt-3 border-t border-t-gray-300">
            <p class="text-xs font-semibold">Seppia CMS 6.1</p>
        </footer>
    </div>
</template>
<script setup>
    import Nav from '@/Shared/Nav.vue';
</script>