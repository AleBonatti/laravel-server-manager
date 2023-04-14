<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
const props = defineProps({
  items: { type: Array, default: [] },
  labelField: { type: String, default: 'label' },
  urlField: { type: String, default: 'url' },
  replace: { Number, default: null }
});
function renderUrl(value) {
  if(props.replace===null) return value;

  return value.replace('{id}', props.replace);
}
</script>
<template>
    <Menu as="div" class="relative inline-block text-left">
      <div>
        <MenuButton class="flex items-center ml-3 rounded-full bg-gray-100 text-purple-400 hover:bg-gray-200 hover:text-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-100">
            <span class="sr-only">Options</span>
            <slot></slot>
        </MenuButton>
      </div>
      <transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in" leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
        <MenuItems class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
          <div class="py-1">
            <MenuItem :key="index" v-for="(item, index) in items" v-slot="{ active }">
              <router-link :to="renderUrl(item[urlField])" :class="[active ? 'bg-gray-100 text-gray-700' : 'text-gray-500', 'block px-4 py-2 text-sm']">{{ item[labelField] }}</router-link>
            </MenuItem>
          </div>
        </MenuItems>
      </transition>
    </Menu>
</template>