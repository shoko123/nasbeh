import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { TFieldsByModule, TFieldsUnion, FuncSlugToId } from '@/js/types/moduleTypes'

export const useLocusStore = defineStore('locus', () => {
  const newFields = ref<TFieldsByModule<'Locus'>>({
    id: '',
    category: '',
    a: 0,
    b: 0,
    oc_label: '',
    square: '',
    uri: '',
    context_uri: '',
    published_date: '',
    updated_date: '',
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
    if (isCreate) {
      return newFields.value
    } else {
      return newFields.value
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
    newFields,
    beforeStore,
    slugToId,
    tagAndSlugFromId,
    headers,
  }
})
