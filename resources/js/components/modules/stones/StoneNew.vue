<template>
  <v-container fluid class="pa-1 ma-0">
    <v-row wrap no-gutters>
      <div v-if="props.isCreate && !isInOpenContext">

      </div>
      <div v-else>

      </div>
      <v-text-field v-model="newFields.id" label="Label" :error-messages="idErrors" class="mr-1" filled readonly />
      <v-text-field v-model="newFields.square" label="Square" :error-messages="squareErrors" class="mr-1" filled
        :readonly="isInOpenContext" />
      <v-text-field v-model="newFields.context" label="Context" :error-messages="contextErrors" class="mr-1" filled
        readonly />
      <v-text-field v-model="newFields.occupation_level" label="Occupation Level"
        :error-messages="occupation_levelErrors" class="mr-1" filled readonly />
      <DatePicker v-model:startDate="newFields.excavation_date" title="Excavation Date"></DatePicker>
      <v-text-field v-model="newFields.excavation_object_id" label="Excavation Object Id"
        :error-messages="excavation_object_idErrors" class="mr-1" filled readonly />
      <v-text-field v-model="newFields.old_museum_id" label="Old Museum Id" class="mr-1" filled readonly />
    </v-row>

    <v-row wrap no-gutters>
      <v-textarea v-model="newFields.cataloger_description" label="Cataloger Description" class="mr-1" filled
        readonly />
      <v-textarea v-model="newFields.conservation_notes" label="Conservation Notes" class="mr-1" filled readonly />
      <v-textarea v-model="newFields.dimension_notes" label="Dimension Notes" class="mr-1" filled readonly />
    </v-row>

    <v-row wrap no-gutters>
      <v-text-field v-model="newFields.weight" label="Weight" class="mr-1" filled readonly />
      <v-text-field v-model="newFields.length" label="Length" class="mr-1" filled readonly />
      <v-text-field v-model="newFields.width" label="Width" class="mr-1" filled readonly />
      <v-text-field v-model="newFields.diameter" label="Diameter" class="mr-1" filled readonly />
      <DatePicker v-model:startDate="newFields.catalog_date" title="Catalog Date"></DatePicker>
    </v-row>
    <v-row wrap no-gutters>
      <v-text-field v-model="newFields.cultural_period" label="Cataloger Assumed Period" class="mr-1" filled readonly />
      <!-- <v-text-field v-model="cataloger" label="Cataloger" class="mr-1" filled readonly /> -->
    </v-row>

    <v-row wrap no-gutters>

      <DatePicker v-model:startDate="newFields.specialist_date" title="Specialist Date"></DatePicker>
    </v-row>
    <v-row wrap no-gutters>

      <v-textarea v-model="newFields.specialist_description" label="Specialist Description"
        :error-messages="specialist_descriptionErrors" class="mr-1" filled />

      <!-- <DatePicker v-model:startDate="newFields.specialist_date" title="Specialist Date"></DatePicker> -->
    </v-row>

    <slot :id="newFields.id" name="newItem" :v="v" :new-fields="newFields" />
  </v-container>
</template>

<script lang="ts" setup>
// import { useTrioStore } from '../../../scripts/stores/trio/trio'
import { TFieldsByModule } from '@/js/types/moduleTypes'
import { computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { useStoneStore } from '../../../scripts/stores/modules/stone'
import DatePicker from './DatePicker.vue'

const props = defineProps<{
  isCreate: boolean
}>()

const { newFields, rules, isInOpenContext } = storeToRefs(useStoneStore())

const v = useVuelidate(rules, newFields.value as TFieldsByModule<'Stone'>)

const idErrors = computed(() => {
  return <string>(v.value.id?.$error ? v.value.id.$errors[0].$message : undefined)
})

const squareErrors = computed(() => {
  return <string>(v.value.square?.$error ? v.value.square.$errors[0].$message : undefined)
})

const contextErrors = computed(() => {
  return <string>(v.value.context?.$error ? v.value.context.$errors[0].$message : undefined)
})

const occupation_levelErrors = computed(() => {
  return <string>(v.value.occupation_level?.$error ? v.value.occupation_level.$errors[0].$message : undefined)
})

const excavation_object_idErrors = computed(() => {
  return <string>(v.value.excavation_object_id?.$error ? v.value.excavation_object_id.$errors[0].$message : undefined)
})

const specialist_descriptionErrors = computed(() => {
  return <string>(v.value.specialist_description?.$error ? v.value.specialist_description.$errors[0].$message : undefined)
})
</script>
