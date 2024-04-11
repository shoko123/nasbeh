<template>
  <v-container v-if="item" fluid class="pa-1 ma-0">
    <v-row wrap no-gutters>
      <v-btn prepend-icon="mdi-map-marker" class="mb-3 text-none" color="blue-lighten-3" @click="openContextTab()">
        {{ tag }}@opencontext.org
      </v-btn>
    </v-row>
    <v-row wrap no-gutters>
      <v-text-field v-model="item.id" label="Label" class="mr-1" filled readonly />
      <v-text-field v-model="item.square" label="Square" class="mr-1" filled readonly />
      <v-text-field v-model="item.context" label="Context" class="mr-1" filled readonly />
      <v-text-field v-model="item.excavation_date" label="Excavation Date" class="mr-1" filled readonly />
      <v-text-field v-model="item.excavation_object_id" label="Excavation Object Id" class="mr-1" filled readonly />
      <v-text-field v-model="item.old_museum_id" label="Old Museum Id" class="mr-1" filled readonly />
    </v-row>

    <v-row wrap no-gutters>
      <v-textarea v-model="item.cataloger_description" label="Cataloger Description" class="mr-1" filled readonly />
      <v-textarea v-model="item.conservation_notes" label="Conservation Notes" class="mr-1" filled readonly />
      <v-textarea v-model="item.dimension_notes" label="Dimension Notes" class="mr-1" filled readonly />
    </v-row>

    <v-row wrap no-gutters>
      <v-text-field v-model="item.weight" label="Weight" class="mr-1" filled readonly />
      <v-text-field v-model="item.length" label="Length" class="mr-1" filled readonly />
      <v-text-field v-model="item.width" label="Width" class="mr-1" filled readonly />
      <v-text-field v-model="item.diameter" label="Diameter" class="mr-1" filled readonly />
      <v-text-field v-model="item.catalog_date" label="Cataloger Entry Date" class="mr-1" filled readonly />
    </v-row>

    <v-row wrap no-gutters>
      <v-text-field v-model="item.cultural_period" label="Cataloger Assumed Period" class="mr-1" filled readonly />
      <v-text-field v-model="cataloger" label="Cataloger" class="mr-1" filled readonly />
      <v-text-field v-model="specialist" label="Specialist" class="mr-1" filled readonly />
      <v-text-field v-model="item.specialist_date" label="Specialist Entry Date" class="mr-1" filled readonly />
    </v-row>

    <v-row wrap no-gutters>
      <v-textarea v-model="item.specialist_description" label="Specialist Description" class="mr-1" filled readonly />
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { TFieldsByModule } from '@/js/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

let { fields, tag, discreteColumns } = storeToRefs(useItemStore())

const item = computed(() => {
  return <TFieldsByModule<'Stone'>>fields.value
})

const cataloger = computed(() => {
  return discreteColumns.value['cataloger_id']
})

const specialist = computed(() => {
  return 'Jennie Ebeling'
})

function openContextTab() {
  console.log(`goToOpenContext`)
  window.open(<string>item.value.uri, '_blank', 'noreferrer')
}
</script>
