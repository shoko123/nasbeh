// routesStore.js
//handles the entire routing mechanism - parsing, loading resources, error handling

import { defineStore, storeToRefs } from 'pinia'
import type { TModule, TUrlModule } from '../../../types/moduleTypes'
import type { LocationQuery } from 'vue-router'
import type { TGroupBase } from '@/js/types/trioTypes'
import { useLocusStore } from '../modules/locus'
import { usePotteryStore } from '../modules/pottery'
import { useStoneStore } from '../modules/stone'
import { useTrioStore } from '../trio/trio'
import { useFilterStore } from '../trio/filter'

const moduleConversion: Record<TUrlModule, TModule> = {
  loci: 'Locus',
  pottery: 'Pottery',
  stones: 'Stone',
}

export const useRoutesParserStore = defineStore('routesParser', () => {
  function parseModule(module: string) {
    //console.log(`parseModule() module: "${module}"`)
    switch (module) {
      case 'loci':
      case 'pottery':
      case 'stones':
        return {
          success: true,
          module: moduleConversion[module],
          url_module: module,
          message: '',
        }

      default:
        console.log(`******* URL Parser error: Unsupported module name "${module}" *********`)
        return {
          success: false,
          data: {},
          message: `Error: unknown url module "${module}"`,
        }
    }
  }

  function parseSlug(module: TModule, slug: string) {
    //console.log(`parseSlug() module: ${module}, slug: ${slug}`);
    let store
    switch (module) {
      case 'Locus':
        store = useLocusStore()
        break

      case 'Pottery':
        store = usePotteryStore()
        break

      case 'Stone':
        store = useStoneStore()
        break
    }
    return store.slugToId(slug)
  }

  function parseUrlQuery(
    qp: LocationQuery,
  ): { success: true } | { success: false; message: string } {
    //console.log(`urlQueryToApiFilters().urlQuery: ${JSON.stringify(qp, null, 2)}`);
    const { trio, groupLabelToKey } = storeToRefs(useTrioStore())
    const { selectedFilterParams } = storeToRefs(useFilterStore())

    if (qp === null) {
      return { success: true }
    }

    const selectedFilters: string[] = []
    for (const [key, value] of Object.entries(qp)) {
      if (value === null) {
        console.log(`group "${key} has no parameters!`)
        continue
      }

      console.log(
        `urlQueryEntry(${key}) =>: ${JSON.stringify((<string>value).split(','), null, 2)}`,
      )

      const undoUnderKey = key.replace(/_/g, ' ')
      if (undoUnderKey in groupLabelToKey.value === false) {
        return { success: false, message: `Unrecognized Url query parameter "${undoUnderKey}"` }
      }
      const group = trio.value.groupsObj[groupLabelToKey.value[undoUnderKey]]
      const paramTexts = (<string>value).split(',')
      switch (group.code) {
        case 'OB':
          {
            const res = processUrlOB(
              group,
              paramTexts.map((x) => x.replace(/_/g, ' ')),
              selectedFilters,
            )
            if (!res.success) {
              return res
            }
          }
          break

        case 'CS':
          {
            const res = processUrlCS(group, paramTexts, selectedFilters)
            if (!res.success) {
              return res
            }
          }
          break

        default:
          {
            const res = processUrlDefault(
              group,
              paramTexts.map((x) => x.replace(/_/g, ' ')),
              selectedFilters,
            )
            if (!res.success) {
              return res
            }
          }
          break
      }
    }
    selectedFilterParams.value = selectedFilters
    return { success: true }
  }

  function processUrlDefault(
    group: TGroupBase,
    paramTexts: string[],
    selectedFilters: string[],
  ): { success: true } | { success: false; message: string } {
    const { trio } = storeToRefs(useTrioStore())
    for (const x of paramTexts) {
      const i = group.paramKeys.findIndex((y) => trio.value.paramsObj[y].text === x)
      if (i === -1) {
        return {
          success: false,
          message: `*** Url option "${x}" is illegal for parameter "${group.label}".`,
        }
      }
      selectedFilters.push(group.paramKeys[i])
    }
    return { success: true }
  }

  function processUrlOB(
    group: TGroupBase,
    paramTexts: string[],
    selectedFilterParams: string[],
  ): { success: true } | { success: false; message: string } {
    const { trio, orderByOptions } = storeToRefs(useTrioStore())
    const selected: string[] = []

    for (const x of paramTexts) {
      const nameOnly = x.slice(0, -2)
      const lastTwo = x.substring(x.length - 2)

      if (selected.some((y) => y === nameOnly)) {
        return { success: false, message: `Duplicate url Order By parameter "${nameOnly}".` }
      }

      const ordeByIndex = orderByOptions.value.findIndex((y) => y.name === nameOnly)

      if (ordeByIndex === undefined || (lastTwo !== '.A' && lastTwo !== '.D')) {
        return { success: false, message: `Unrecognized url Order By parameter "${x}".` }
      }

      const firstEmptyParamKey = group.paramKeys.find((x) => trio.value.paramsObj[x].text === '')
      if (firstEmptyParamKey === undefined) {
        return { success: false, message: `Problem with url Order By parameter "${x}".` }
      }
      trio.value.paramsObj[firstEmptyParamKey].text = x
      selectedFilterParams.push(firstEmptyParamKey)
      selected.push(nameOnly)
    }
    return { success: true }
  }

  function processUrlCS(
    group: TGroupBase,
    paramTexts: string[],
    selectedFilterParams: string[],
  ): { success: true } | { success: false; message: string } {
    const { trio } = storeToRefs(useTrioStore())
    if (paramTexts.length > 6) {
      return {
        success: false,
        message: `Url query problem: Too many search terms for parameter "${group.label}".`,
      }
    }
    for (const x of paramTexts) {
      const firstEmptyParamKey = group.paramKeys.find((x) => trio.value.paramsObj[x].text === '')
      if (firstEmptyParamKey === undefined) {
        return { success: false, message: `Problem with url search parameter "${x}".` }
      }
      trio.value.paramsObj[firstEmptyParamKey].text = x
      selectedFilterParams.push(firstEmptyParamKey)
    }
    return { success: true }
  }

  return { parseModule, parseSlug, parseUrlQuery }
})
