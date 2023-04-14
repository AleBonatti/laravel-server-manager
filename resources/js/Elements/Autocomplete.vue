<template>
    <Combobox v-model="selected" by="id" nullable>
      <div class="relative mt-1">
        <ComboboxInput @change="query=$event.target.value" :displayValue="(item) => item?.label" class="w-full rounded-md border text-gray-700 border-gray-300 bg-white py-2 pl-3 pr-10 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500 sm:text-sm" />
        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
        </ComboboxButton>
        <TransitionRoot leave="transition ease-in duration-100" leaveFrom="opacity-100" leaveTo="opacity-0" @after-leave="query=''">
          <ComboboxOptions class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base ring-1 ring-black ring-opacity-30 focus:outline-none sm:text-sm z-10">
            <div v-if="items.length===0" class="relative cursor-default select-none py-2 px-4 text-gray-700">{{ query!=='' ? props.emptyMsg : 'digita il testo da ricercare...' }}</div>
            <ComboboxOption v-for="item in items" as="template" :key="item.id" :value="item" v-slot="{ selected, active }">
              <li class="relative cursor-default bg-white select-none py-2 pl-10 pr-4 text-gray-700">
                <span class="block truncate" :class="{ 'font-medium': selected, 'font-normal': !selected }">
                  {{ item.label }}
                </span>
                <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <CheckIcon class="h-5 w-5" aria-hidden="true" />
                </span>
              </li>
            </ComboboxOption>
          </ComboboxOptions>
        </TransitionRoot>
      </div>
    </Combobox>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { Combobox, ComboboxInput, ComboboxButton, ComboboxOptions, ComboboxOption, TransitionRoot } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { debounce } from 'lodash/debounce';

let emit = defineEmits(['update']);
const props = defineProps({
  value: { type: Number, default: null },
  emptyMsg: { type: String, default: 'Nessun risultato trovato' },
  apiUrl: { type: String, default: '/admin/api/collection/elementi' }
});

let selected = ref();
let query = ref('');
let items = ref([]);

watch(query, _.debounce((newVal) => {
  if(query.value!==''){
    fetch().then((response) => {
      items.value = response;
    });
  }
}, 500));

watch(selected, (nuovo, vecchio) => {
  if(nuovo!==vecchio) {
    emit('update', nuovo)
  }
});

const fetch = async () => {
    const res = await axios.post(props.apiUrl, { search: query.value, tipo: 'evento', orderBy: 'created_at', orderSort: 'desc', limit: 6 })

    return res.data.items;
}

onMounted(() => {
  if(props.value!==null) {
    axios.post(`/admin/api/model/elemento/${props.value}`)
      .then(response => {
        selected.value = { id: response.data.id, label: response.data.nome };
      });
  }
});
</script>
