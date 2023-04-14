<script setup>
    import { ExclamationCircleIcon } from '@heroicons/vue/20/solid'
    const props = defineProps({
        name: String,
        label: String,
        info: String,
        error: String
    });
</script>

<template>
    <div class="mt-4">
      <label :for="name" v-html="label" class="block text-sm font-medium text-gray-700" />
      <div class="relative mt-1 rounded-md shadow-sm">
        <slot></slot>
        <div v-if="error" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
          <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
        </div>
      </div>
      <p class="mt-1 pl-1 text-sm text-red-600" v-show="error" v-text="error" />
      <span v-if="info" class="italic text-xs text-gray-400 pl-1">{{ info }}</span>
    </div>
</template>