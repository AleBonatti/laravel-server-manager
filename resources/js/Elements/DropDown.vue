<script setup>
    const props = defineProps({
        name: String,
        modelValue: [String, Number],
        error: String
    });
    let emit = defineEmits(['update:modelValue']);
</script>
<template>
    <select :id="name" :name="name" :value="modelValue" @change="emit('update:modelValue', $event.target.value)" :class="[error ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'text-gray-700 border-gray-300 focus:border-purple-500 focus:ring-purple-500', 'disabled:opacity-50 block w-full rounded-md py-2 text-base focus:outline-none sm:text-sm']">
      <slot></slot>
    </select>
</template>