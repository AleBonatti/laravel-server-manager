<script>
    export default {
        name: "tag-manager",

        props: {
            media_id: Number,
            tags: {
                type: Array,
                function () { return [] }
            },
            selected_tags: {
                type: Array,
                function () { return [] }
            }
        },

        data: () => ({
            nome: null,
            showAll: false,
            error: null,
        }),

        methods: {
            addFromText() {
                axios.post(`/admin/tag/attach`, { media_id: this.media_id, nome: this.nome })
                    .then(response => {
                        if(response.data.success) {
                            this.$emit('add', response.data.tag);
                            this.nome = null;
                        } else {
                            this.setError(response.data.error);
                        }
                    });
            },

            addFromList(tag_id) {
                axios.post(`/admin/tag/attach`, { media_id: this.media_id, tag_id })
                    .then(response => {
                        if(response.data.success) {
                            this.$emit('add', response.data.tag);
                            this.showAll = false;
                        } else {
                            this.setError(response.data.error);
                        }
                    });
            },

            setError(message) {
                this.error = message;
                setTimeout(() => {
                    this.error = null;
                }, 4000); 
            },

            remove(index) {
                axios.post(`/admin/tag/detach`, { media_id: this.media_id, tag_id: this.selected_tags[index].tag_id })
                    .then(response => {
                        if(response.data.success) {
                            this.$emit('remove', index);
                        }
                    });
            }
        }
    }
</script>
<template>
    <div>
        <div class="my-2 relative w-full pb-2 md:pb-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" @keyup.enter="addFromText" v-model="nome" class="w-full mr-4 pl-8 rounded-md border border-gray-300 text-sm font-medium text-gray-700 focus:border-purple-500 focus:ring-purple-500" placeholder="crea nuovo Tag o assegna Tag esistente..." />
        </div>
        <div class="pb-3 flex items-top justify-between">          
            <div class="text-sm w-2/3">
                <ul v-if="selected_tags.length > 0" class="gap-1 w-full">
                    <li :key="index" v-for="tag, index in selected_tags" class="inline-block mr-1 mb-1">
                        <span class="text-sm inline-flex items-center px-3 py-1 rounded-md bg-white text-gray-800 border border-purple-400 hover:border-purple-800">
                            <a href="#" @click.prevent="remove(index)" class="text-purple-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>
                            {{ tag.nome }}
                        </span>
                    </li>
                </ul>
                <p v-else class="italic pl-1">nessun tag associato</p>
            </div>
            <a href="#" @click.prevent="showAll=!showAll" class="text-xs text-purple-700 w-1/3 text-right pt-1">tutti i tag</a>
        </div>

        <transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="transform opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="transform opacity-0"
        >
        <div v-show="showAll" class="py-2 px-3 bg-purple-100 rounded-md">
            <a href="#" :key="index" @click.prevent="addFromList(tag.id)" v-for="tag, index in tags" class="text-sm text-bold mr-4 text-purple-900 hover:underline inline-block">{{ tag.nome }}</a>
        </div>
        </transition>

        <p class="text-sm text-red-500 text-center" v-show="error">Attenzione: {{ error }}</p>
    </div>
</template>