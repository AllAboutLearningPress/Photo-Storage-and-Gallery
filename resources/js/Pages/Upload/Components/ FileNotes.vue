<template>
    <div class="file__notes">
        <a class="file__note btn btn-outline-primary" href="#"
            >Choose a family?</a
        >
        <a
            v-if="hasDuplicate"
            class="file__note btn btn-outline-warning"
            href="#"
            >Compare duplicate</a
        >
        <a v-else class="file__note btn btn-outline-warning" href="#"
            >Checking for duplicate</a
        >
    </div>
</template>
<script>
export default {
    props() {
        return {
            hasDuplicate: Boolean,
        };
    },
};
</script>
