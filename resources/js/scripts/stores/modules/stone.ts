import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { TFieldsByModule, TFieldsUnion, FuncSlugToId } from '@/js/types/moduleTypes'

export const useStoneStore = defineStore('stone', () => {
  const newFields = ref<TFieldsByModule<'Stone'>>({
    id: '',
    square: '',
    context: '',
    excavation_date: new Date(),
    occupation_level: '',
    excavation_object_id: '',
    whole: false,
    cataloger_material: '',
    cataloger_typology: '',
    cataloger_description: '',
    conservation_notes: '',
    weight: '',
    length: '',
    width: '',
    height: '',
    diameter: '',
    dimension_notes: '',
    cultural_period: '',
    old_museum_id: '',
    cataloger_id: 1,
    catalog_date: new Date(),
    specialist_description: '',
    specialist_date: new Date(),
    thumbnail: '',
    uri: null,
    base_type_id: 1,
    material_id: 1,
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

  function beforeStore(isCreate: boolean): TFieldsUnion | false {
    //console.log(`stone.beforStore() isCreate: ${isCreate}  fields: ${JSON.stringify(fields, null, 2)}`)
    //const sf = <TFieldsByModule<'Stone'>>fields
    if (isCreate) {
      //do something here
      //const pDate = new Date()

      //'2023-12-23' //new Date('2023-12-23') //pDate.toISOString().slice(0, 10)
      return newFields.value
    } else {
      const today = new Date('2022-12-22').toISOString().substring(0, 10)
      console.log(`today: ${today}`)
      newFields.value.specialist_date = today //today.toISOString().slice(0, 10)
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
    beforeStore,
    slugToId,
    tagAndSlugFromId,
    headers,
  }
})
