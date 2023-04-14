<script setup>
    let emit = defineEmits(['update']);
    const props = defineProps({
        titolo: { type: String, default: null },
        info: { type: String, default: null },
        saving: { type: Boolean, default: false },
    });
</script>
<template>
    <div class="shadow sm:overflow-hidden sm:rounded-md">
        <div class="bg-white p-4 sm:p-6">
            <h3 v-show="titolo!==null" class="text-base font-medium text-gray-700">{{ titolo }}</h3>
            <slot></slot>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button type="submit" @click="emit('update')" :disabled="saving" class="disabled:opacity-50 inline-flex justify-center rounded-md border border-transparent bg-purple-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <svg v-if="props.saving" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 animate-spin mr-2 opacity-75">
                    <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0112.548-3.364l1.903 1.903h-3.183a.75.75 0 100 1.5h4.992a.75.75 0 00.75-.75V4.356a.75.75 0 00-1.5 0v3.18l-1.9-1.9A9 9 0 003.306 9.67a.75.75 0 101.45.388zm15.408 3.352a.75.75 0 00-.919.53 7.5 7.5 0 01-12.548 3.364l-1.902-1.903h3.183a.75.75 0 000-1.5H2.984a.75.75 0 00-.75.75v4.992a.75.75 0 001.5 0v-3.18l1.9 1.9a9 9 0 0015.059-4.035.75.75 0 00-.53-.918z" clip-rule="evenodd" />
                </svg>
                Salva
            </button>
        </div>
    </div>
</template>