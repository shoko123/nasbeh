<template>
  <v-container v-if="item" fluid class="pa-1 ma-0">
    <div>Checking out date pickers</div>
    <v-row wrap no-gutters>
      <v-text-field v-model="item.id" label="Label Id" class="mr-1" filled readonly />
      <v-textarea v-model="item.cataloger_description" label="Cataloger Description" class="mr-1" filled readonly />
    </v-row>
    <v-row wrap no-gutters>
      <v-text-field v-model="item.id" label="Label Id" class="mr-1" filled readonly />
      <v-text-field v-model="item.excavation_date" label="Excavation Date" class="mr-1" filled readonly />
      <v-text-field v-model="item.catalog_date" label="Catalog Date" class="mr-1" filled readonly />
      <v-text-field v-model="item.specialist_date" label="Specialist Day from fields" class="mr-1" filled readonly />
      <!-- <date-picker v-model="item.excavation_date" label="Pick a Date" color="primary"
        @update:model-value="updateModelValue"></date-picker> -->
    </v-row>

  </v-container>
</template>

<script lang="ts" setup>
import { computed, ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { TFieldsByModule } from '@/js/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'
import DatePicker from './DatePicker.vue'


onMounted(() => {

  //item.value.catalog_date = new Date(item.value.catalog_date)//Object.assign(newFields.value, fields.value as TFieldsByModule<'Stone'>)

  //console.log(`StoneNew.Mount fields: ${JSON.stringify(newFields, null, 2)}`)
})

let { fields } = storeToRefs(useItemStore())

const item = computed(() => {
  return <TFieldsByModule<'Stone'>>fields.value
})

function updateModelValue(value: number): void {
  startDate.value = new Date(value);
}

const startDate = ref<Date>(new Date());



</script>
