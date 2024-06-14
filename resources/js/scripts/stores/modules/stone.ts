import { ref, computed } from 'vue'
import { defineStore, storeToRefs } from 'pinia'
import { required, maxLength } from '@vuelidate/validators'
import { TFieldsByModule, TFieldsUnion, FuncSlugToId } from '@/js/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'
import { useItemNewStore } from '../../../scripts/stores/itemNew'
import { useRoutesMainStore } from '../../../scripts/stores/routes/routesMain'

export const useStoneStore = defineStore('stone', () => {
  const { fields } = storeToRefs(useItemStore())
  const { openIdSelectorModal } = storeToRefs(useItemNewStore())
  const { current } = storeToRefs(useRoutesMainStore())
  const newFields = ref<Partial<TFieldsByModule<'Stone'>>>({})

  const rules = computed(() => {
    return inOC.value
      ? {
          id: {},
          specialist_description: { maxLength: maxLength(25) },
        }
      : {
          id: {},
          square: {},
          context: {},
          excavation_date: {},
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
          catalog_date: {},
          specialist_description: { maxLength: maxLength(7) },
          specialist_date: {},
          thumbnail: {},
          base_type_id: 0,
          material_id: 0,
        }
  })

  const isNew = computed(() => {
    return ['update', 'create'].includes(current.value.name)
  })

  const inOC = computed(() => {
    return isNew.value
      ? newFields.value.uri !== ''
      : (<TFieldsByModule<'Stone'>>fields.value).uri !== ''
  })

  const slugToId: FuncSlugToId = function (slug: string) {
    const arr = slug.split('.')

    if (arr.length === 1) {
      return {
        success: false,
        message: 'No . [dot] detected in slug',
      }
    } else {
      return {
        success: true,
        id: slug,
      }
    }
  }

  function tagAndSlugFromId(id: string) {
    //console.log(`Stone.tagAndSlugFromId()`)
    return { tag: id, slug: id }
  }

  const currentIds = ref<string[]>([])

  function prepareForNew(isCreate: boolean, ids?: string[]): void {
    console.log(
      `prepNew(Stone) create(${isCreate}) fields: ${JSON.stringify(fields.value, null, 2)}`,
    )
    if (isCreate) {
      currentIds.value = ids!
      openIdSelectorModal.value = true
      prepareDefaultNewFields()
      console.log(`isCreate. current ids: ${currentIds.value}`)
    } else {
      Object.assign(newFields.value, fields.value as TFieldsByModule<'Stone'>)
    }
  }

  const availableItemNumbers = computed(() => {
    const itemNos = currentIds.value.map((x) => {
      const sections = x.split('.')
      return parseInt(sections[2])
    })

    const all = [...Array(200).keys()].map((i) => i + 1)

    return all.filter((x) => {
      return !itemNos.includes(x)
    })

    // console.log(`current ids: ${itemNos}`)
    // console.log(`all ids: ${all}`)
    // console.log(`diff: ${diff}`)
  })

  function beforeStore(isCreate: boolean): Partial<TFieldsUnion> | false {
    //console.log(`stone.beforStore() isCreate: ${isCreate}  fields: ${JSON.stringify(fields, null, 2)}`)
    if (isCreate) {
      //
    } else {
      //
    }
    if (inOC.value) {
      return {
        id: newFields.value.id,
        specialist_description: newFields.value.specialist_description,
        specialist_date: new Date(),
      }
    } else {
      return newFields.value
    }
  }

  function prepareDefaultNewFields() {
    newFields.value.id = 'B2024.1.' + availableItemNumbers.value[0]
    newFields.value.square = ''
    newFields.value.context = ''
    newFields.value.excavation_date = null
    newFields.value.occupation_level = ''
    newFields.value.excavation_object_id = ''
    newFields.value.whole = false
    newFields.value.cataloger_typology = ''
    newFields.value.cataloger_description = ''
    newFields.value.conservation_notes = ''
    newFields.value.weight = ''
    newFields.value.length = ''
    newFields.value.width = ''
    newFields.value.height = ''
    newFields.value.diameter = ''
    newFields.value.dimension_notes = ''
    newFields.value.cultural_period = ''
    newFields.value.old_museum_id = ''
    newFields.value.cataloger_id = 1
    newFields.value.catalog_date = null
    newFields.value.specialist_description = ''
    newFields.value.specialist_date = null
    newFields.value.thumbnail = ''
    newFields.value.uri = ''
    newFields.value.base_type_id = 1
    newFields.value.base_type_id = 1
    newFields.value.material_id = 1
  }

  const headers = computed(() => {
    return [
      { title: 'Label', align: 'start', key: 'tag' },
      { title: 'Square', align: 'start', key: 'square' },
      { title: 'Context', align: 'start', key: 'context' },
      { title: 'Excavation Date', align: 'start', key: 'excavation_date' },
      { title: 'Cataloger Description', align: 'start', key: 'cataloger_description' },
      { title: 'Conservation Notes', align: 'start', key: 'conservation_notes' },
    ]
  })

  return {
    newFields,
    rules,
    inOC,
    isNew,
    prepareForNew,
    availableItemNumbers,
    beforeStore,
    slugToId,
    tagAndSlugFromId,
    headers,
    currentIds,
  }
})
