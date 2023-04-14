<script setup>
    const props = defineProps({
        id: String,
        name: String,
        value: String,
        label: String,
        info: String,
        modelValue: Number
    });
    let emit = defineEmits(['update:modelValue']);
</script>
<template>
    <div class="relative flex items-start pl-1">
        <div class="flex h-5 items-center">
            <input type="radio" :id="id" :value="value" :name="name" :checked="value===modelValue"  @change="$emit('update:modelValue', $event.target.value)" class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500" />
        </div>
        <div class="ml-3 text-sm">
            <label :for="id" class="text-gray-700">{{ label }}</label>
            <p v-if="info" class="text-gray-500 text-xs">{{ info }}</p>
        </div>
    </div>
</template>