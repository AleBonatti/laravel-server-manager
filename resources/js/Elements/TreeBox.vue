<template>
    <tree-item :data="data" :selected-ids="ids" :checkbox="multiple"></tree-item>
</template>

<script>
import TreeItem from '@/components/elements/TreeBoxItem.vue';
export default {
    components: { TreeItem },

    props: {
        data: {
            type: Object,
            default: {}
        },
        multiple: {
            type: Boolean,
            default: true
        },
        selected: [Number, Array]
    },
    
    data: () => ({
        ids: []
    }),

    methods: {
    },

    created() {
        if(Array.isArray(this.selected)) {
            this.ids = this.selected;
        } else {
            this.ids = [this.selected===null ? null : parseInt(this.selected)];
        }
    }
};
</script>