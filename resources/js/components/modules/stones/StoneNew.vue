<template>
  <v-container v-if="newFields" fluid class="pa-1 ma-0">
    <v-row wrap no-gutters>
      <v-text-field v-model="newFields.id" label="Label" :error-messages="idErrors" class="mr-1" filled readonly />
      <v-text-field v-model="newFields.square" label="Square" :error-messages="squareErrors" class="mr-1" filled
        :readonly="isInOpenContext" />
      <v-text-field v-model="newFields.context" label="Context" :error-messages="contextErrors" class="mr-1" filled
        readonly />
      <v-text-field v-model="newFields.occupation_level" label="Occupation Level"
        :error-messages="occupation_levelErrors" class="mr-1" filled readonly />
      <v-text-field v-model="newFields.excavation_date" label="Excavation Date" :error-messages="excavation_dateErrors"
        class="mr-1" filled readonly />
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
      <v-text-field v-model="newFields.catalog_date" label="Cataloger Entry Date" class="mr-1" filled readonly />
    </v-row>
    <v-row wrap no-gutters>
      <v-text-field v-model="newFields.cultural_period" label="Cataloger Assumed Period" class="mr-1" filled readonly />
      <!-- <v-text-field v-model="cataloger" label="Cataloger" class="mr-1" filled readonly /> -->
    </v-row>

    <v-row wrap no-gutters>
      <v-textarea v-model="newFields.specialist_description" label="Specialist Description"
        :error-messages="specialist_descriptionErrors" class="mr-1" filled />
    </v-row>

    <slot :id="newFields.id" name="newItem" :v="v" :new-fields="newFields" />
  </v-container>
</template>

<script lang="ts" setup>
// import { useTrioStore } from '../../../scripts/stores/trio/trio'
import { TFieldsByModule } from '@/js/types/moduleTypes'
import { onMounted, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength } from '@vuelidate/validators'
import { useItemStore } from '../../../scripts/stores/item'
import { useStoneStore } from '../../../scripts/stores/modules/stone'

const props = defineProps<{
  isCreate: boolean
}>()

onMounted(() => {
  if (!props.isCreate) {
    Object.assign(newFields.value, fields.value as TFieldsByModule<'Stone'>)
  }
  //console.log(`StoneNew.Mount fields: ${JSON.stringify(newFields, null, 2)}`)
})

const { fields } = storeToRefs(useItemStore())
const { newFields } = storeToRefs(useStoneStore())

const rules = computed(() => {
  return {
    id: {},
    square: {},
    context: {},
    excavation_date: new Date,
    occupation_level: { required, maxLength: maxLength(50) },
    excavation_object_id: {},
    whole: false,
    cataloger_typology: {},
    cataloger_description: {},
    conservation_notes: {},
    weight: {},
    length: {},
    width: {},
    height: {},
    diameter: {},
    dimension_notes: {},
    cultural_period: {},
    old_museum_id: {},
    cataloger_id: 0,
    catalog_date: new Date,
    specialist_description: { maxLength: maxLength(7) },
    specialist_date: new Date,
    thumbnail: {},
    uri: {},
    base_type_id: 0,
    material_id: 0,
  }
})

const currentItemFields = computed(() => {
  return fields.value! as TFieldsByModule<'Stone'>
})

const isInOpenContext = computed(() => {
  return currentItemFields.value.uri !== null
})

const v = useVuelidate(rules, newFields.value)

const idErrors = computed(() => {
  return <string>(v.value.id.$error ? v.value.id.$errors[0].$message : undefined)
})

const squareErrors = computed(() => {
  return <string>(v.value.square.$error ? v.value.square.$errors[0].$message : undefined)
})

const contextErrors = computed(() => {
  return <string>(v.value.context.$error ? v.value.context.$errors[0].$message : undefined)
})

const excavation_dateErrors = computed(() => {
  return <string>(v.value.excavation_date.$error ? v.value.excavation_date.$errors[0].$message : undefined)
})

const occupation_levelErrors = computed(() => {
  return <string>(v.value.occupation_level.$error ? v.value.occupation_level.$errors[0].$message : undefined)
})

const excavation_object_idErrors = computed(() => {
  return <string>(v.value.excavation_object_id.$error ? v.value.excavation_object_id.$errors[0].$message : undefined)
})

const specialist_descriptionErrors = computed(() => {
  return <string>(v.value.specialist_description.$error ? v.value.specialist_description.$errors[0].$message : undefined)
})
</script>
