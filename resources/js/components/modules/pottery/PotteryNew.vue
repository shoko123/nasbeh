<template>
  <v-container fluid class="pa-1 ma-0">
    <v-row wrap no-gutters>
      <v-text-field
        v-model="data.id"
        label="Name"
        :error-messages="nameErrors"
        class="mr-1"
        filled
      />
      <v-select v-model="data.area" label="Area" :items="areas" />
      <v-text-field
        v-model="data.id"
        label="Square"
        :error-messages="squareErrors"
        class="mr-1"
        filled
      />
      <v-text-field
        v-model="data.id"
        label="stratum"
        :error-messages="stratumErrors"
        class="mr-1"
        filled
      />
    </v-row>

    <slot :id="data.id" name="data" :v="v" :data="data" />
  </v-container>
</template>

<script lang="ts" setup>
import { onMounted, reactive, computed } from 'vue'
import { storeToRefs } from 'pinia'
import type { TFieldsByModule } from '@/js/types/moduleTypes'
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import { useItemStore } from '../../../scripts/stores/item'
import { useTrioStore } from '../../../scripts/stores/trio/trio'

const props = defineProps<{
  isCreate: boolean
}>()

onMounted(() => {
  if (!props.isCreate) {
    Object.assign(data, fields.value)
  }
  console.log(`PotteryNew isCreate: ${props.isCreate}\n data: ${JSON.stringify(data, null, 2)}`)
})

const { fields } = storeToRefs(useItemStore())
const { trio, groupLabelToKey } = storeToRefs(useTrioStore())

let data: TFieldsByModule<'Pottery'> = reactive({
  id: '',
  name: '',
  area: 'XX',
  addendum: null,
  year: null,
  square: '',
  stratum: '',
  type: '',
  cross_ref: '',
  description: '',
  notes: '',
  elevation: '',
})

const areas = computed(() => {
  let paramKeys = trio.value.groupsObj[groupLabelToKey.value['Area']].paramKeys
  return paramKeys.map((x) => trio.value.paramsObj[x].text)
})

const rules = computed(() => {
  return {
    id: {},
    name: { required },
    area: { required }, //from select list
  }
})

const v = useVuelidate(rules, data)

const nameErrors = computed(() => {
  return <string>(v.value.name.$error ? v.value.name.$errors[0].$message : undefined)
})

const squareErrors = computed(() => {
  return <string>(v.value.square.$error ? v.value.square.$errors[0].$message : undefined)
})

const stratumErrors = computed(() => {
  return <string>(v.value.stratum.$error ? v.value.stratum.$errors[0].$message : undefined)
})
</script>
