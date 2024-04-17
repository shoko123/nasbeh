<template>
  <v-container v-if="item" fluid class="pa-1 ma-0">
    <div>Checking out date pickers</div>
    <v-row wrap no-gutters>
      <v-text-field v-model="item.id" label="Label Id" class="mr-1" filled readonly />
      <v-textarea v-model="item.cataloger_description" label="Cataloger Description" class="mr-1" filled readonly />
    </v-row>
    <v-row wrap no-gutters>
      <v-text-field v-model="item.id" label="Label Id" class="mr-1" filled readonly />
      <v-text-field v-model="excavation_date" label="Excavation Date" class="mr-1" filled readonly />
      <v-text-field v-model="catalog_date" label="Catalog Date" class="mr-1" filled readonly />
      <v-text-field :model-value="specialist_date" label="Specialist Day from fields" class="mr-1" filled readonly />
    </v-row>
    <v-row wrap no-gutters>
      <datePicker v-model:startDate="item.excavation_date" title="Excavation Date"></datePicker>
      <datePicker v-model:startDate="item.catalog_date" title="Catalog Date"></datePicker>
      <datePicker v-model:startDate="item.specialist_date" title="Specialist Date"></datePicker>
    </v-row>

  </v-container>
</template>

<script lang="ts" setup>
import { computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { TFieldsByModule } from '@/js/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'
import DatePicker from './DatePicker.vue'
import { dateStringFromDate } from '../../../scripts/utils/utils'

onMounted(() => {
  //item.value.catalog_date = new Date(item.value.catalog_date)//Object.assign(newFields.value, fields.value as TFieldsByModule<'Stone'>)
  //console.log(`StoneNew.Mount fields: ${JSON.stringify(newFields, null, 2)}`)
})

let { fields } = storeToRefs(useItemStore())

const item = computed(() => {
  return <TFieldsByModule<'Stone'>>fields.value
})

const excavation_date = computed(() => {
  return dateStringFromDate(item.value.excavation_date)
})

const catalog_date = computed(() => {
  return dateStringFromDate(item.value.catalog_date)
})

const specialist_date = computed(() => {
  return dateStringFromDate(item.value.specialist_date)
})


</script>