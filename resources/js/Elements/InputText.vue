<script setup>
    const props = defineProps({
        name: String,
        type: { type: String, default: 'text' },
        maxlength: { type: String, default: '50' },
        placeholder: String,
        modelValue: [String, Number],
        error: String
    });
    let emit = defineEmits(['update:modelValue']);
</script>
<template>
    <input :type="type" :id="name" :value="modelValue" @input="emit('update:modelValue', $event.target.value)" :maxlength="maxlength" :placeholder="placeholder" :aria-invalid="error?'true':'false'" :class="[error ? 'border-red-300 text-red-900 placeholder-red-300 pr-10 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 text-gray-700 focus:border-purple-500 focus:ring-purple-500', 'block w-full rounded-md focus:outline-none sm:text-sm']" />
</template>