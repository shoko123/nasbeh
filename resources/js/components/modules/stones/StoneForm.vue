<template>
  <v-container v-if="item" fluid class="pa-1 ma-0">
    <v-row wrap no-gutters>
      <v-btn
        prepend-icon="mdi-map-marker"
        class="mb-3 text-none"
        color="blue-lighten-3"
        @click="openContextTab()"
      >
        {{ tag }}@opencontext.org
      </v-btn>
    </v-row>
    <v-row wrap no-gutters>
      <v-text-field v-model="item.id" label="Label" class="mr-1" filled readonly />
      <v-text-field v-model="item.square" label="Square" class="mr-1" filled readonly />
      <v-text-field v-model="item.context" label="Context" class="mr-1" filled readonly />
      <v-text-field v-model="item.cultural_period" label="Period" class="mr-1" filled readonly />
      <v-text-field v-model="item.material" label="Material" class="mr-1" filled readonly />
    </v-row>

    <v-row wrap no-gutters>
      <v-textarea v-model="item.description" label="Description" class="mr-1" filled readonly />
      <v-textarea
        v-model="item.conservation_notes"
        label="Conservation Notes"
        class="mr-1"
        filled
        readonly
      />
      <v-textarea
        v-model="item.dimension_notes"
        label="Dimension Notes"
        class="mr-1"
        filled
        readonly
      />
    </v-row>
    <v-row wrap no-gutters>
      <v-text-field v-model="item.completeness" label="Completeness" class="mr-1" filled readonly />
      <v-text-field v-model="item.weight" label="Weight" class="mr-1" filled readonly />
      <v-text-field v-model="item.length" label="Length" class="mr-1" filled readonly />
      <v-text-field v-model="item.width" label="Width" class="mr-1" filled readonly />
      <v-text-field v-model="item.diameter" label="Diameter" class="mr-1" filled readonly />
    </v-row>
    <v-row wrap no-gutters>
      <v-text-field
        v-model="item.excavation_date"
        label="Excavation Date"
        class="mr-1"
        filled
        readonly
      />
      <v-text-field v-model="item.catalog_date" label="Catalog Date" class="mr-1" filled readonly />
      <v-text-field
        v-model="item.catalogued_by"
        label="Catalogued By"
        class="mr-1"
        filled
        readonly
      />
    </v-row>
  </v-container>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { TFieldsByModule } from '@/js/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

let { fields, tag } = storeToRefs(useItemStore())

const item = computed(() => {
  return <TFieldsByModule<'Stone'>>fields.value
})

function openContextTab() {
  console.log(`goToOpenContext`)
  window.open(item.value.uri, '_blank', 'noreferrer')
}
</script>
