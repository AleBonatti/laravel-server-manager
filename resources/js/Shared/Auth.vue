<template>
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <img src="/assets/loghi/logo.svg" class="h-10 m-auto">
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md my-8">
            <slot />
        </div>

        <p class="text-xs text-gray-500 text-center mt-4">Seppia server manager - &copy; 2023 Methalb web design</p>
    </div>
</template>