<script setup>
    const props = defineProps({
        name: String,
        maxlength: { type: String, default: '50' },
        placeholder: String,
        modelValue: [String, Number],
        rows: { type: Number, default: 4 },
        error: String
    });
    let emit = defineEmits(['update:modelValue']);
</script>
<template>
    <textarea :id="name" :value="modelValue" :rows="rows" @input="emit('update:modelValue', $event.target.value)" :maxlength="maxlength" :placeholder="placeholder" :aria-invalid="error?'true':'false'" :class="[error ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'text-gray-700 border-gray-300 focus:border-purple-500 focus:ring-purple-500', 'block w-full rounded-md pr-10 focus:outline-none sm:text-sm']" />
</template>