<script setup>
    import { ref } from 'vue'
    const props = defineProps({
        name: String
    });
    let emit = defineEmits(['change']);
    let selected = ref(null);

    function emetti() {
        if(selected.value!==null) {
            emit('change', selected.value);
            selected.value = null;
        }
    }
</script>
<template>
    <div class="mt-1 flex rounded-md shadow-sm">
        <select :id="name" :name="name" v-model="selected" class="text-gray-700 border-gray-300 focus:border-gray-300 focus:ring-0 block w-full rounded-none rounded-l-md py-2 text-base focus:outline-none sm:text-sm">
            <slot></slot>
        </select>
        <button type="button" @click="emetti" :disabled="selected===null" class="disabled:opacity-50 elative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-purple-600 bg-purple-500 px-4 py-2 text-sm font-medium text-white hover:bg-purple-600 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">+</button>
    </div>
</template>