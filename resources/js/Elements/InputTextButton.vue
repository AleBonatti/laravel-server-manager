<script setup>
    const props = defineProps({
        name: String,
        type: { type: String, default: 'text' },
        maxlength: { type: String, default: '50' },
        placeholder: String,
        modelValue: [String, Number],
        error: String
    });
    let emit = defineEmits(['update:modelValue', 'click']);
</script>
<template>
<div class="mt-1 flex rounded-md">
    <div class="relative flex flex-grow items-stretch focus-within:z-10">
      <input :type="type" :id="name" :value="modelValue" @input="emit('update:modelValue', $event.target.value)" :maxlength="maxlength" :placeholder="placeholder" :aria-invalid="error?'true':'false'" :class="error ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 text-gray-700 focus:border-purple-500 focus:ring-purple-500'" class="block w-full rounded-none rounded-l-md pr-10 focus:outline-none sm:text-sm" />
    </div>
    <button type="button" @click="emit('click', $event.target.value)" class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-0">
        <slot>click</slot>
    </button>
  </div>
</template>