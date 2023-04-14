<script>
    import Pagination from '@/components/elements/Pagination.vue';
    import Modal from '@/components/elements/Modal.vue';

    export default {
        name: "FileManager",
        components: { Pagination, Modal },

        data: () => ({
            dataSet: {},
            params: {
                nome: null,
                collection: null,
                page: 0,
                orderBy: 'created_at',
                orderSort: 'desc',
                perPage: 15
            },
            sendButtonText: 'Seleziona file',
            selected: [],
            ready: false
        }),

        props: {
            show: {
                type: Boolean,
                default: false
            },
            multiselect: {
                type: Boolean,
                default: true
            },
            collectionName: {
                type: String,
                default: 'immagini'
            }
        },

        methods: {
            fetch() {
                this.params.collection = this.collection_name;
                axios.post('/admin/media/fetch', this.params)
                    .then(response => {
                        this.dataSet = response.data.items;
                        if(!this.ready) this.ready = true;
                    })
                    .catch(error => {
                        this.$notify({ type: 'error', title: 'Attenzione:', text: 'impossibile recuperare i dati' });
                    });
            },
            search() {
                this.params.page = 0;
                this.fetch();
            },
            paginate(page) {
                this.params.page = page;
                this.fetch();
            },
            select(id) {
                if(!this.multiselect) {
                    this.selected = [id];
                } else if(!this.selected.includes(id)) {
                    this.selected.push(id);
                } else {
                    this.selected.splice(this.selected.indexOf(id), 1);
                }
            },
            sendSelected() {
                if(this.selected.length > 0) {
                    this.$emit('send', this.selected);
                    this.selected = [];
                }
            },
            close() {
                this.$emit('close');
            }
        },

        computed: {
            selectedLength: function () {
                return this.selected.length;
            }
        },

        watch: {
            show: function (val) {
                if(val && !this.ready) {
                    setTimeout(()=> {
                        this.fetch();
                    }, 800)
                }
            },
            selectedLength: function (val) {
                if(val===1) {
                    this.sendButtonText = 'Assegna il file selezionato';
                } else if(val > 1) {
                    this.sendButtonText = `Assegna i ${this.selected.length} file selezionati`;
                } else {
                    this.sendButtonText = 'Seleziona file';
                }
            }
        }
    }
</script>

<template>
<Modal :open="show" @close="$emit('close')" width="6xl" :hide-footer="true">
    <template #header>
    <div class="mb-8">
        <input type="text" v-on:keyup.enter="search" v-model="params.nome" class="rounded-md border border-gray-300 text-sm text-gray-700 focus:border-purple-100 focus:outline-none focus:ring-0" placeholder="cerca..." />            
        <button type="button" @click="sendSelected" :disabled="selected.length===0" class="ml-4 py-2 px-4 disabled:opacity-50 rounded-md text-sm leading-5 font-medium focus:outline-none text-white bg-purple-700 hover:bg-purple-800 transition duration-200 ease-in-out">
            {{ sendButtonText }}
        </button>
    </div>
    </template>
    <div class="bg-white pb-4 sm:pb-4">
        <div class="min-h-full">
            <div v-if="!ready" class="flex justify-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 animate-spin mr-2 opacity-75">
                    <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0112.548-3.364l1.903 1.903h-3.183a.75.75 0 100 1.5h4.992a.75.75 0 00.75-.75V4.356a.75.75 0 00-1.5 0v3.18l-1.9-1.9A9 9 0 003.306 9.67a.75.75 0 101.45.388zm15.408 3.352a.75.75 0 00-.919.53 7.5 7.5 0 01-12.548 3.364l-1.902-1.903h3.183a.75.75 0 000-1.5H2.984a.75.75 0 00-.75.75v4.992a.75.75 0 001.5 0v-3.18l1.9 1.9a9 9 0 0015.059-4.035.75.75 0 00-.53-.918z" clip-rule="evenodd" />
                </svg>
            </div>
            <div v-else>
                <div v-if="dataSet.data.length > 0" class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-6">
                    <div :key="key" v-for="(item, key) in dataSet.data">
                        <div class="w-full h-40 flex items-center justify-center">
                            <a href="#" @click.prevent="select(item.id)" :class="{ 'ring-2 ring-offset-2 ring-purple-600 ring-offset-gray-200' : selected.includes(item.id) }" class="block rounded-lg outline-none hover:opacity-80">
                                <img v-if="!item.mime_type.includes('svg')" :src="item.thumb" class="rounded-lg h-40 w-full object-cover">
                                <img v-else :src="item.url" class="bg-gray-200 p-2 w-28 h-28">
                            </a>
                        </div>
                        <div class="text-xs text-gray-600 mt-2">
                            <span class="font-medium text-gray-700 block">{{ item.name }}</span>
                            <span v-if="item.is_image">{{ item.width }}x{{ item.height }}</span>
                            {{ item.size_for_humans }}
                        </div>
                    </div>
                </div>
                <div v-else class="flex justify-center items-center space-x-2">
                    <svg class="inline-block w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-medium py-8 text-gray-400 text-xl">Nessun file trovato...</span>
                </div>
            </div>
        </div>
    </div>
    <Pagination :pagination="dataSet" :offset="4" :showCounter="false" :large="false" @paginate="paginate" />
</Modal>
</template>