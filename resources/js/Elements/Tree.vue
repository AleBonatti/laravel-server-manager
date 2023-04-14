<script>
    import InputGroup from '@/components/elements/InputGroup.vue';
    import InputText from '@/components/elements/InputText.vue';
    import Modal from '@/components/elements/Modal.vue';
    //import { Tree, Fold, foldAll, unfoldAll, Draggable } from 'he-tree-vue'; //Check: plugins - cloneTreeData, walkTreeData, getPureTreeData: utils
    import { BaseTree, Draggable } from '@he-tree/vue';

    export default {
        components: { InputGroup, InputText, BaseTree, Draggable, Modal },

        emits: ["updated"],

        watch: {
            folded(newVal, oldVal) {
                if(newVal!==oldVal) {
                    if(newVal) 
                        this.$refs.tree.closeAll();
                    else
                        this.$refs.tree.openAll();
                }
            }
        },

        props: {
            data: { type: Array, default: null },
            group: { type: String, required: true },
            folded: { type: Boolean, default: false }
        },

        data: () => ({
            selectedNode: {},
            selectedPath: null,
            editing: false,
            saving: false
        }),
        
        methods: {
            edit(node, path) {
                this.selectedNode = node;//this.data[index];
                this.selectedPath = path;
                this.editing = true;
            },

            saveNode() {
                axios.post(`/admin/menu/${this.selectedNode.id}/update`, this.selectedNode)
                    .then(response => {
                        this.$emit('updated');
                        this.editing = false;
                    })
                    .catch(error => {
                        console.log(error);
                        //this.toast.error('Attenzione: si è verificato un errore sul salvataggio dati');
                    });
            },

            drop(obj) {
                axios.post('/admin/menu/save', { data: this.data, group: this.group })
                    .then(response => {
                        this.$emit('updated');
                    })
                    .catch(error => {
                        console.log(error);
                        //this.toast.error('Attenzione: si è verificato un errore sul salvataggio dati');
                    });
            },

            remove(node, stat) {
                if(confirm(`Vuoi eliminare la voce '${node.label}'?`)) {
                    axios.post(`/admin/menu/${node.id}/destroy`)
                        .then(response => {
                            if(response.data.success) {
                                this.$refs.tree.remove(stat);
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            //this.toast.error('Attenzione: si è verificato un errore sul salvataggio dati');
                        });
                }
            }/* ,

            update()
            {
                this.$refs.tree.add({ title: 'merda', children:[]});
            } */
        }
    };
</script>
<template>
<!-- <button @click="update">pio</button>
            {{data}} -->
    <draggable v-model="data" @after-drop="drop" ref="tree" :indent="40"><!-- v-slot="{ node, index, path, tree }"  -->
        <template #default="{ node, stat }">
            <div class="flex justify-between p-2 bg-gray-50 border-gray-200 border-2 rounded-md">
                <div>
                    <span v-if="node.children.length" @click="stat.open=!stat.open" class="text-gray-400 text-md">{{ stat.open ? '-' : '+' }}</span>
                    <a href="#" @click.prevent="edit(node, path)" class="ml-2 text-gray-700 text-sm font-medium hover:text-purple-600">{{ node.label }}</a>
                </div>
                <div class="text-sm text-gray-400">
                    <a href="#" @click.prevent="edit(node, path)" class="text-purple-600 hover:underline font-medium text-sm hover:text-purple-700">Edit</a> |
                    <a href="#" @click.prevent="remove(node, stat)" class="text-purple-600 hover:underline font-medium text-sm hover:text-purple-700">Del</a>
                </div>
            </div>
        </template>
    </draggable>
    <!-- Modale -->
    <Modal :open="editing" @close="editing=false" width="xl">
        <template #header>Modifica nodo</template>
        <InputGroup label="Titolo" :inline="true">
            <InputText maxlength="100" v-model="selectedNode.label" />
        </InputGroup>
        <InputGroup label="Indirizzo Url" :inline="true">
            <InputText maxlength="200" v-model="selectedNode.url" placeholder="#" />
        </InputGroup>
        <template #footer>
            <button type="button" @click="saveNode" :disabled="saving" class="disabled:opacity-60 inline-flex w-full justify-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-purple-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                <svg v-if="saving" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 animate-spin mr-2 opacity-75">
                    <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0112.548-3.364l1.903 1.903h-3.183a.75.75 0 100 1.5h4.992a.75.75 0 00.75-.75V4.356a.75.75 0 00-1.5 0v3.18l-1.9-1.9A9 9 0 003.306 9.67a.75.75 0 101.45.388zm15.408 3.352a.75.75 0 00-.919.53 7.5 7.5 0 01-12.548 3.364l-1.902-1.903h3.183a.75.75 0 000-1.5H2.984a.75.75 0 00-.75.75v4.992a.75.75 0 001.5 0v-3.18l1.9 1.9a9 9 0 0015.059-4.035.75.75 0 00-.53-.918z" clip-rule="evenodd" />
                </svg>
                Salva dati
            </button>
            <button type="button" @click="editing=false" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">Chiudi</button>
        </template>
    </Modal>
</template>