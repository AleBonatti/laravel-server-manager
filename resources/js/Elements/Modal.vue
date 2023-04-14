<template>
  <TransitionRoot as="template" :show="open">
    <Dialog as="div" class="relative z-10" @close="emit('close')">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>
      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center text-center sm:items-center sm:p-0">
          <!-- Possibili valori larghezza da inserire per la compilazione: sm:max-w-6xl sm:max-w-4xl sm:max-w-3xl sm:max-w-2xl sm:max-w-xl -->
          <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel :class="`sm:max-w-${width}`" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full md:w-11/12 xl:w-full">
              <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                <button type="button" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-0" @click="emit('close')">
                  <span class="sr-only">Chiudi</span>
                  <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                </button>
              </div>
              <div class="p-6">
                <div class="mt-3 sm:mt-0 text-left">
                  <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-700 mb-6">
                    <slot name="header">Header</slot>
                  </DialogTitle>
                  <slot><p class="text-sm text-gray-500">Contenuto default della modale...</p></slot>
                </div>
              </div>
              <div v-if="!hideFooter" class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse">
                <slot name="footer">
                  <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm" @click="emit('close')">Chiudi</button>
                </slot>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
  import { ref } from 'vue'
  import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
  import { XMarkIcon } from '@heroicons/vue/24/outline'

  defineProps({
    open: Boolean,
    hideFooter: { type: Boolean, default: false },
    width: { type: String, default: '4xl' }
  });

  const emit = defineEmits(['close']);
</script>