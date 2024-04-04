<template>
  <v-container fluid class="pa-1 ma-0">
    <v-row wrap no-gutters>
      <v-select v-model="data.area" label="Area" :items="areas" />
      <v-text-field
        id="locus"
        v-model="data.id"
        label="Locus"
        :error-messages="locusErrors"
        class="mr-1"
        filled
      />
      <v-text-field
        id="basket"
        v-model="data.id"
        label="Basket"
        :error-messages="basketErrors"
        class="mr-1"
        filled
      />
      <v-text-field
        id="stone_no"
        v-model="data.id"
        label="Stone No."
        :error-messages="stone_noErrors"
        class="mr-1"
        filled
      />
      <v-text-field
        id="year"
        v-model="data.area"
        label="Year"
        :error-messages="yearErrors"
        class="mr-1"
        filled
      />
      <v-text-field
        id="date"
        v-model="data.area"
        label="Date"
        :error-messages="dateErrors"
        type="date"
        min="1990-01-01"
        max="2018-12-31"
      />
    </v-row>

    <slot :id="data.id" name="data" :v="v" :data="data" />
  </v-container>
</template>

<script lang="ts" setup>
import { useTrioStore } from '../../../scripts/stores/trio/trio'
import { TFieldsByModule } from '@/js/types/moduleTypes'
import { onMounted, reactive, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength } from '@vuelidate/validators'
import { useItemStore } from '../../../scripts/stores/item'

const props = defineProps<{
  isCreate: boolean
}>()

onMounted(() => {
  if (!props.isCreate) {
    Object.assign(data, fields.value)
  }
  //console.log(`StoneNew.Mount fields: ${JSON.stringify(data, null, 2)}`)
})

const { fields } = storeToRefs(useItemStore())
let { trio, groupLabelToKey } = storeToRefs(useTrioStore())

const data: TFieldsByModule<'Stone'> = reactive({
  id: 0,
  area: 'A',
  name: '',
})

const areas = computed(() => {
  let paramKeys = trio.value.groupsObj[groupLabelToKey.value['Area']].paramKeys
  return paramKeys.map((x) => trio.value.paramsObj[x].text)
})

const rules = computed(() => {
  return {
    id: {},
    area: { required }, //no need for validation - from select
    name: { required, maxLength: maxLength(50) },
  }
})

const v = useVuelidate(rules, data)

const locusErrors = computed(() => {
  return <string>(v.value.locus.$error ? v.value.locus.$errors[0].$message : undefined)
})

const basketErrors = computed(() => {
  return <string>(v.value.basket.$error ? v.value.basket.$errors[0].$message : undefined)
})

const stone_noErrors = computed(() => {
  return <string>(v.value.stone_no.$error ? v.value.stone_no.$errors[0].$message : undefined)
})

const yearErrors = computed(() => {
  return <string>(v.value.year.$error ? v.value.year.$errors[0].$message : undefined)
})
const dateErrors = computed(() => {
  return <string>(v.value.date.$error ? v.value.date.$errors[0].$message : undefined)
})
</script>
