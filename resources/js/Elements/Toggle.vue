<script setup>
    import { ref, watch, onMounted } from 'vue';
    import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue'

    const enabled = ref(false)
    const props = defineProps({
        label: String,
        info: String,
        modelValue: Number
    });
    let emit = defineEmits(['update:modelValue']);

    watch(enabled, (newItem, oldItem) => {
        if(newItem!==oldItem) {
            emit('update:modelValue', newItem ? 1 : 0);
        }
    });

    onMounted(() => {
        enabled.value = props.modelValue===1 ? true : false;
    });
</script>
<template>
  <SwitchGroup as="div" class="flex items-center">
    <Switch v-model="enabled" :class="[enabled ? 'bg-purple-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2']">
      <span aria-hidden="true" :class="[enabled ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
    </Switch>
    <SwitchLabel as="span" class="ml-3">
      <span class="text-sm font-medium text-gray-700">{{ label }}</span>
      <span v-if="info" class="text-sm text-gray-500">{{ info }}</span>
    </SwitchLabel>
  </SwitchGroup>
</template>