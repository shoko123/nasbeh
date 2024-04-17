<template>
    <v-btn icon="mdi-cancel" variant="text" @click="resetDate"></v-btn>
    <v-menu v-model="isMenuOpen" :close-on-content-click="false" class="ma-2">
        <template #activator="{ props }">
            <v-text-field :model-value="formattedDate" v-bind="props" :label="dpProps.title"> </v-text-field>
        </template>
        <v-date-picker v-model="startDate" hide-actions color="primary" title="" @update:model-value="dateSelected()">
            <template #header> {{ dpProps.title }} {{ formattedDate }}</template>
        </v-date-picker>
    </v-menu>
</template>

<script setup>
import { ref, computed, defineModel } from "vue";
import { dateStringFromDate } from '../../../scripts/utils/utils'

const startDate = defineModel("startDate", { type: Date })
const dpProps = defineProps({ title: { type: String, required: true }, })

function dateSelected() {
    //console.log(`Date selected`)
    isMenuOpen.value = false
}

const isMenuOpen = ref(false)

const formattedDate = computed(() => {
    return dateStringFromDate(startDate.value)
})
function resetDate() {
    startDate.value = null
    // isMenuOpen.value = false
}
</script>