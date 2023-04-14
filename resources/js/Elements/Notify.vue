<template>
  <!-- Global notification live region, render this permanently at the end of the document -->
  <div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-1000">
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
      <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
      <transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="show" :class="fetchClass(options.type)" class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white ring-2">
          <div class="p-2">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <ExclamationCircleIcon v-if="['error','danger','red'].includes(options.type)" class="h-6 w-6 text-red-400" aria-hidden="true" />
                <ExclamationCircleIcon v-else-if="['warning','yellow'].includes(options.type)" class="h-6 w-6 text-yellow-500" aria-hidden="true" />
                <CheckCircleIcon v-else class="h-6 w-6 text-green-400" aria-hidden="true" />
              </div>
              <div class="ml-3 w-0 flex-1 pt-0.5">
                <p v-if="options.title" class="text-sm font-medium text-gray-900">{{ options.title }}</p>
                <p class="text-sm text-gray-700">{{ options.message }}</p>
              </div>
              <div class="ml-4 flex flex-shrink-0">
                <button type="button" @click="show = false" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                  <span class="sr-only">Chiudi</span>
                  <XMarkIcon class="h-5 w-5" aria-hidden="true" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { CheckCircleIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline';
import { XMarkIcon } from '@heroicons/vue/20/solid';

defineExpose({notify})
let show = ref(false);
let options = ref({});

const props = defineProps({
  timeout: { type: Number, default: 2000 }
});

/* const colori = {
    success:  { bg: 'bg-gray-50', bgHover: 'hover:bg-gray-100', title: 'text-gray-700', text: 'text-gray-500', textHover: 'group-hover:text-gray-700' },
    error:  { bg: 'bg-pink-50', bgHover: 'hover:bg-pink-100', title: 'text-pink-700', text: 'text-pink-500', textHover: 'group-hover:text-pink-700' },
    warning:  { bg: 'bg-green-50', bgHover: 'hover:bg-green-100', title: 'text-green-700', text: 'text-green-500', textHover: 'group-hover:text-green-700' },
    info:  { bg: 'bg-blue-50', bgHover: 'hover:bg-blue-100', title: 'text-blue-700', text: 'text-blue-500', textHover: 'group-hover:text-blue-700' }
}; */

function fetchClass(type) {
  if(['error','danger','red'].includes(type)) {
    return 'ring-red-400';
  } else if (['warning','yellow'].includes(type)) {
    return 'ring-yellow-500';
  } else {
    return 'ring-green-400';
  }
}

function notify(data) {
  options.value = {
    title: data.title ? data.title : null,
    message: data.message ? data.message : 'Operazione completata con successo',
    type: data.type ? data.type : 'success',
  }
  show.value = true;
  setTimeout(() => {
    show.value = false;
  }, props.timeout);
}
</script>