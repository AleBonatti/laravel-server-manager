<script setup>
  import { ref, watch, onMounted } from 'vue'
  import { Listbox, ListboxButton, ListboxOption, ListboxOptions } from '@headlessui/vue'
  import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

  const props = defineProps({
      items: { type: Array, default: [] },
      field: { type: String, default: 'code' },
      modelValue: [String, Number]
  });
  let emit = defineEmits(['update:modelValue']);//

  const selected = ref({});// ref(props.items[1]);

  watch(selected, (newItem, oldItem) => {
    if(newItem!==oldItem) {
      emit('update:modelValue', newItem[props.field]);
    }
  });

  onMounted(() => {
    let index = props.items.findIndex((item) => item[props.field]===props.modelValue);
    if(index >= 0) selected.value = props.items[index];
  });
</script>

<template>
  <Listbox as="div" v-model="selected">
    <div class="relative mt-1">
      <ListboxButton class="relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500 sm:text-sm">
        <span class="flex items-center">
          <img v-if="selected.ico" :src="selected.ico" alt="" class="h-6 w-6 flex-shrink-0 rounded-full" />
          <span class="ml-3 block truncate">{{ selected.label }}</span>
        </span>
        <span class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
          <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
        </span>
      </ListboxButton>

      <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <ListboxOptions class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
          <ListboxOption as="template" v-for="(item, index) in props.items" :key="index" :value="item" v-slot="{ active, selected }">
            <li :class="[active ? 'text-white bg-purple-600' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-3 pr-9']">
              <div class="flex items-center">
                <img :src="item.ico" alt="" class="h-6 w-6 flex-shrink-0 rounded-full" />
                <span :class="[selected ? 'font-semibold' : 'font-normal', 'ml-3 block truncate']">{{ item.label }}</span>
              </div>
              <span v-if="selected" :class="[active ? 'text-white' : 'text-purple-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                <CheckIcon class="h-5 w-5" aria-hidden="true" />
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
</template>