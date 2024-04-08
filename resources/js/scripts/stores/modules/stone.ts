import { computed } from 'vue'
import { defineStore } from 'pinia'
import { TFieldsByModule, TFieldsUnion, FuncSlugToId } from '@/js/types/moduleTypes'

export const useStoneStore = defineStore('stone', () => {
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

  function beforeStore(isCreate: boolean, fields: TFieldsUnion): TFieldsUnion | false {
    //console.log(`stone.beforStore() isCreate: ${isCreate}  fields: ${JSON.stringify(fields, null, 2)}`)
    const sf = <TFieldsByModule<'Stone'>>fields
    if (isCreate) {
      const rf = { ...sf }
      //do something here
      return rf
    } else {
      return sf
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
    beforeStore,
    slugToId,
    tagAndSlugFromId,
    headers,
  }
})
