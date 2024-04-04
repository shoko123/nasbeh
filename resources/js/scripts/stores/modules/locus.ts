import { computed } from 'vue'
import { defineStore } from 'pinia'
import { TFieldsByModule, TFieldsUnion, FuncSlugToId } from '@/js/types/moduleTypes'

export const useLocusStore = defineStore('locus', () => {
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
      { title: 'OC Label', align: 'start', key: 'oc_label' },
      { title: 'Basic Typology', align: 'start', key: 'basic_typology' },
      { title: 'Number', align: 'start', key: 'number' },
      { title: 'Subnumber', align: 'start', key: 'sub_number' },
      { title: 'Square', align: 'start', key: 'square' },
      { title: 'Published Date', align: 'start', key: 'published_date' },
      { title: 'Updated Date', align: 'start', key: 'updated_date' },
    ]
  })

  return {
    beforeStore,
    slugToId,
    tagAndSlugFromId,
    headers,
  }
})
