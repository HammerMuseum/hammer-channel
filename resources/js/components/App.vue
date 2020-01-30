<template>
    <router-view></router-view>
</template>
<script>
    import mixin  from '../mixin';
    export default {
        mixins: [ mixin ],
        mounted() {
            console.log('Mounted');
        }
    }
</script>
