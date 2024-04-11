import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { TFieldsByModule, TFieldsUnion, FuncSlugToId } from '@/js/types/moduleTypes'

export const usePotteryStore = defineStore('pottery', () => {
  const newFields = ref<TFieldsByModule<'Pottery'>>({
    id: '',
    name: '',
    area: '',
  })
  const slugToId: FuncSlugToId = function (slug: string) {
    const arr = slug.split('.')

    if (arr.length === 2) {
      return {
        success: true,
        id: slug,
      }
    }
    return { success: false, message: 'No . [dot] detected in slug.' }
  }

  function tagAndSlugFromId(id: string) {
    return { tag: id, slug: id }
  }

  function beforeStore(isCreate: boolean): TFieldsUnion | false {
    //console.log(`stone.beforStore() isCreate: ${isCreate}  fields: ${JSON.stringify(fields, null, 2)}`)
    if (isCreate) {
      return newFields.value
    } else {
      return newFields.value
    }
  }

  const headers = computed(() => {
    return [
      { title: 'Name', align: 'start', key: 'tag' },
      { title: 'Year', align: 'end', key: 'year' },
      { title: 'Type', align: 'start', key: 'type' },
      { title: 'Cross Reference', align: 'start', key: 'cross_ref' },
      { title: 'Description', align: 'start', key: 'description' },
      { title: 'Stratum', align: 'start', key: 'stratum' },
      { title: 'Square', align: 'start', key: 'square' },
      { title: 'Elevation', align: 'start', key: 'elevation' },
    ]
  })

  return {
    newFields,
    beforeStore,
    tagAndSlugFromId,
    slugToId,
    headers,
  }
})
