import { ref, computed } from 'vue'
import { defineStore, storeToRefs } from 'pinia'
import { required, maxLength } from '@vuelidate/validators'
import { TFieldsByModule, TFieldsUnion, FuncSlugToId } from '@/js/types/moduleTypes'
import { useItemStore } from '../../../scripts/stores/item'

export const useStoneStore = defineStore('stone', () => {
  const { fields } = storeToRefs(useItemStore())
  const newFields = ref<Partial<TFieldsByModule<'Stone'>>>({})

  const rules = computed(() => {
    return isInOpenContext.value
      ? {
          id: {},
          specialist_description: { maxLength: maxLength(25) },
          specialist_date: {},
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
  const isInOpenContext = computed(() => {
    const uri = (<TFieldsByModule<'Stone'>>fields.value).uri
    return uri !== ''
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

  function prepareForNew(isCreate: boolean): void {
    console.log(
      `prepNew(Stone) create(${isCreate}) fields: ${JSON.stringify(fields.value, null, 2)}`,
    )
    Object.assign(newFields.value, fields.value as TFieldsByModule<'Stone'>)
    if (newFields.value.specialist_date === null) {
      newFields.value.specialist_date = new Date()
    }
  }

  function beforeStore(isCreate: boolean): Partial<TFieldsUnion> | false {
    //console.log(`stone.beforStore() isCreate: ${isCreate}  fields: ${JSON.stringify(fields, null, 2)}`)
    //const sf = <TFieldsByModule<'Stone'>>fields
    if (isCreate) {
      //
    }
    if (isInOpenContext.value) {
      //do something here
      return {
        id: newFields.value.id,
        specialist_description: newFields.value.specialist_description,
        specialist_date: newFields.value.specialist_date,
      } // as TFieldsByModule<'Stone'>
    } else {
      return newFields.value
    }
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
    isInOpenContext,
    prepareForNew,
    beforeStore,
    slugToId,
    tagAndSlugFromId,
    headers,
  }
})
