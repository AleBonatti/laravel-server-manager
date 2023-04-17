<script setup>
    import { ref, watch } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { useForm } from '@inertiajs/vue3';
    import InputGroup from '@/Elements/InputGroup.vue';
    import InputText from '@/Elements/InputText.vue';
    import Toggle from '@/Elements/Toggle.vue';
    import modal from '@/Elements/Modal.vue';
    import NavBar from '@/Shared/NavBar.vue';

    const props = defineProps({
        errors: Object,
        items: { type: Array }
    });

    let params = ref({
        name: null,
        orderBy: 'nome',
        orderSort: 'asc',
        active: '',
        perPage: 25
    });
    let selected = useForm({ id: null, name: null, extra_commands: null, active: 1});
    let checked = ref([]);

    let editing = ref(false);
    let saving = ref(false);

    let sort = () => {};

    let create = () => {
        selected.id = null;
        selected.name = null;
        selected.extra_commands = null;
        selected.active = 1;
        editing.value = true;
    };

    let edit = (item) => {
        selected.id = item.id;
        selected.name = item.name;
        selected.extra_commands = item.extra_commands;
        selected.active = item.active;
        editing.value = true;
    };

    let save = () => {
        const method = selected.id===null ? 'post' : 'put';
        const url = method==='put' ? `/admin/database/${selected.id}` : '/admin/database';

        selected.submit(method, url, {
            onStart: () => { saving.value = true },
            onSuccess: () => {
                editing.value = false;
            },
            onFinish: () => {
                saving.value = false;
            }
        });
    }

    let destroy = () => {
        if(checked.value.length===0 || !confirm(`Vuoi eliminare ${checked.value.length} elementi selezionati?`) ) return;
        router.post('/admin/api/delete/database', checked.value, {
            onSuccess: () => {
                checked.value = [];
            }
        })
    }

    let filter = () => {
        router.get('/admin/database', params.value, { preserveState: true, replace: true });
    }

    let checkAll = (e) => {
        checked.value = e.target.checked ? props.items.map(a => a.id) : [];
    }
</script>

<template>
    <nav-bar title="Database" :breadcrumbs="[{ label:'Gestione backup database' }]" />

    <div class="max-w-screen-2xl mx-auto">
    <!-- <div class="py-8 space-y-4"> -->
        <div class="py-4 sm:flex sm:items-center sm:justify-between">
            <div class="w-full sm:w-full lg:w-2/4 block md:flex md:space-x-2">
                <div class="sm:flex items-center space-y-2 md:space-y-0 md:space-x-3">
                    <div class="relative h-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <input type="text" v-on:keyup.enter="filter" v-model="params.name" class="pl-8 pr-10 py-2 border-gray-300 focus:border-purple-500 focus:ring-purple-500 block w-full rounded-md focus:outline-none sm:text-sm placeholder:text-gray-400 placeholder:font-normal placeholder:italic" placeholder="search..." />
                    </div>
                    <div class="flex sm:pr-3 md:pr-0 pb-2 md:pb-0 w-full h-full">
                        <select @change="filter" v-model="params.active" class="text-gray-700 border-gray-300 focus:border-purple-500 focus:ring-purple-500 block w-full rounded-md py-2 pl-3 pr-10 text-base focus:outline-none sm:text-sm">
                            <option value="">all</option>
                            <option value="1">enabled</option>
                            <option value="0">disabled</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="w-full sm:w-full lg:w-auto md:space-x-2 block md:flex items-center">
                <div class="mt-4 sm:mt-0 md:ml-16 flex justify-end lg:justify-between w-full items-center">
                    <button type="button" v-if="checked.length > 0" @click="destroy" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-700 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-800 focus:outline-none sm:w-auto sm:text-sm">Elimina {{ checked.length }} {{ checked.length > 1 ? 'elementi' : 'elemento' }}</button>
                    <select @change="filter" v-model="params.perPage" class="form-select rounded-md w-1/2 sm:w-auto block pl-3 pr-10 border mx-2 border-gray-300 px-4 py-2 bg-white text-sm text-gray-700 hover:bg-gray-50 focus:border-purple-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-purple-500">
                        <option value="15">15 per pagina</option>
                        <option value="25">25 per pagina</option>
                        <option value="50">50 per pagina</option>
                    </select>
                    <button type="button" @click="create" class="w-8 h-8 flex justify-center items-center rounded-full border border-transparent text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">+</button>
                </div>
            </div>
        </div>

        <!-- Tabella dati -->
        <div class="flex-col space-y-4">
            <div class="align-middle min-w-full overflow-x-auto shadow-lg overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th>
                                <div class="flex justify-center rounded-md">
                                    <input type="checkbox" @change="checkAll($event)" value="1" class="form-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 block" />
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button @click="sort('name')" class="flex items-center space-x-1 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider group focus:outline-none focus:underline">
                                    <span class="text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Db name</span>
                                    <span class="relative flex items-center" v-if="params.orderBy==='name'">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="params.orderSort==='asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'"></path></svg>
                                    </span>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-center">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">export command</span>    
                            </th>
                            <th class="px-6 py-3 text-center">
                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">export</span>    
                            </th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" v-if="this.items.length > 0">
                        <tr :key="item.id" v-for="item in items" class="bg-white">
                            <td class="relative w-12 px-6 sm:w-16 sm:px-8">
                                <div v-if="checked.includes(item.id)" class="absolute inset-y-0 left-0 w-0.5 bg-purple-600"></div>
                                <input type="checkbox" :value="item.id" v-model="checked" class="form-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 block">
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                <p class="font-semibold truncate">{{ item.name }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 text-center">
                                {{ item.extra_commands }}
                            </td>
                            <td class="px-6 py-4 text-sm leading-5 text-gray-900 text-center">
                                <span :class="[item.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800', 'inline-flex items-center rounded px-2 py-0.5 text-xs font-medium']">{{ item.active ? 'yes' : 'no' }}</span>
                            </td>
                            <td class="pr-4 whitespace-no-wrap text-sm leading-5 text-gray-300 text-right">
                                <a href="#" @click.prevent="edit(item)" class="hover:text-purple-700 text-purple-500 inline-block" alt="modifica">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="bg-white divide-y divide-gray-200" v-else-if="this.items.length===0">
                        <tr>
                            <td colspan="6">
                                <div class="flex justify-center items-center space-x-2">
                                    <svg class="inline-block w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="font-medium py-8 text-gray-400 text-xl">Nessun tag trovato...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modale inserimento/modifica dati -->
        <modal :show="editing" @save="save" titolo="Gestione Tag" @close="editing=false" width="xl">
            <template #header>Modifica elemento</template>
            <input-group label="Database name" per="name" :error="errors.name">
                <input-text id="name" maxlength="50" v-model="selected.name"></input-text>
            </input-group>
            <input-group label="Export extra command" per="extra_commands" :error="errors.extra_commands">
                <input-text id="extra_commands" maxlength="50" v-model="selected.extra_commands"></input-text>
            </input-group>
            <div class="mt-6">
                <Toggle label="Export enabled" v-model="selected.active" />
            </div>
            <template #footer>
                <button type="button" :disabled="saving" class="disabled:opacity-60 inline-flex w-full justify-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-purple-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" @click="save">
                    <svg v-if="saving" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 animate-spin mr-2 opacity-75">
                        <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0112.548-3.364l1.903 1.903h-3.183a.75.75 0 100 1.5h4.992a.75.75 0 00.75-.75V4.356a.75.75 0 00-1.5 0v3.18l-1.9-1.9A9 9 0 003.306 9.67a.75.75 0 101.45.388zm15.408 3.352a.75.75 0 00-.919.53 7.5 7.5 0 01-12.548 3.364l-1.902-1.903h3.183a.75.75 0 000-1.5H2.984a.75.75 0 00-.75.75v4.992a.75.75 0 001.5 0v-3.18l1.9 1.9a9 9 0 0015.059-4.035.75.75 0 00-.53-.918z" clip-rule="evenodd" />
                    </svg>
                    Salva dati
                </button>
                <button type="button" @click="editing=false" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">Chiudi</button>
            </template>
        </modal>
    </div>
</template>