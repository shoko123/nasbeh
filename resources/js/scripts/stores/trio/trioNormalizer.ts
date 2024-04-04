import { defineStore, storeToRefs } from 'pinia'
import { useMediaStore } from '../media'

import type {
  TApiTrio,
  TGroupTmpUnion,
  TGroupUnion,
  TApiGroupByCode,
  TParamObj,
  TGroupObj,
  TCategoriesArray,
  TGroupLabelToKey,
  TGroupColumn,
  TParamTmp,
  TApiParamNameAndColumn,
} from '@/js/types/trioTypes'

export const useTrioNormalizerStore = defineStore('trioNormalize', () => {
  const { mediaCollectionNames } = storeToRefs(useMediaStore())

  let categories: TCategoriesArray = []
  let groupsObj: TGroupObj = {}
  let paramsObj: TParamObj = {}
  let groupLabelToKey: TGroupLabelToKey = {}
  let fieldNameToGroupKey: TGroupLabelToKey = {}
  let orderByOptions: TApiParamNameAndColumn[] = []
  let catCnt = 0
  let grpCnt = 0
  let prmCnt = 0
  let tmpGroup: TGroupTmpUnion | null = null
  let tmpParams: TParamTmp[] = []

  function reset() {
    categories = []
    groupsObj = {}
    paramsObj = {}
    groupLabelToKey = {}
    fieldNameToGroupKey = {}
    catCnt = 0
    grpCnt = 0
    prmCnt = 0
    tmpGroup = null
    tmpParams = []
  }

  function normalizeTrio2(apiTrio: TApiTrio) {
    reset()

    apiTrio.forEach((cat) => {
      categories.push({ name: cat.name, groupKeys: [] })
      cat.groups.forEach((grp) => {
        const grpKey = pad(grpCnt, 3)
        categories[catCnt].groupKeys.push(grpKey)
        switch (grp.group_type_code) {
          case 'CL':
            handleCL(grp as TApiGroupByCode<'CL'>)
            break
          case 'CV':
            handleCV(grp as TApiGroupByCode<'CV'>)
            break
          case 'CB':
            handleCB(grp as TApiGroupByCode<'CB'>)
            break
          case 'CR':
            handleCR(grp as TApiGroupByCode<'CR'>)
            break
          case 'CS':
            handleCS(grp as TApiGroupByCode<'CS'>)
            break
          case 'TM':
            handleTM(grp as TApiGroupByCode<'TM'>)
            break
          case 'TG':
            handleTG(grp as TApiGroupByCode<'TG'>)
            break
          case 'MD':
            handleMD(grp as TApiGroupByCode<'MD'>)
            break
          case 'OB':
            handleOB(grp as TApiGroupByCode<'OB'>)
            break
          default:
        }
        saveGroupAndParams(grpKey)
        grpCnt++
      })
      catCnt++
    })

    return {
      trio: { categories, groupsObj, paramsObj },
      groupLabelToKey,
      fieldNameToGroupKey,
      orderByOptions,
    }
  }

  function saveGroupAndParams(grpKey: string) {
    // console.log(
    //   `saveGroup() group: ${JSON.stringify(tmpGroup, null, 2)} params: ${JSON.stringify(tmpParams, null, 2)}`,
    // )
    const grpToSave: TGroupUnion = {
      ...(tmpGroup as TGroupTmpUnion),
      paramKeys: <string[]>[],
      categoryIndex: catCnt,
    }

    tmpParams.forEach((p) => {
      const prmKey = pad(prmCnt, 3)
      grpToSave.paramKeys.push(prmKey)
      paramsObj[prmKey] = { text: p.text, extra: p.extra, groupKey: pad(grpCnt, 3) }
      prmCnt++
    })
    groupsObj[grpKey] = grpToSave
    groupLabelToKey[grpToSave.label] = grpKey

    if (['CL', 'CV', 'CR', 'CB'].includes(grpToSave.code)) {
      fieldNameToGroupKey[(<TGroupColumn>grpToSave).column_name] = grpKey
    }
  }

  function processDependency(dependency: string[]) {
    //console.log(`processDep() groupLabelToKey: ${JSON.stringify(groupLabelToKey, null, 2)}`);
    return dependency.map((x) => {
      const pieces = x.split('.')
      const group = groupsObj[groupLabelToKey[pieces[0]]]
      //console.log(`groupLabel: ${pieces[0]}. key: ${groupLabelToKey[pieces[0]]}  `);
      const res = group.paramKeys.find((x) => paramsObj[x].text === pieces[1])
      return res!
    })
  }

  function handleCL(grp: TApiGroupByCode<'CL'>) {
    tmpParams = grp.params.map((x) => {
      return { text: x.name, extra: x.id }
    })
    tmpGroup = {
      code: grp.group_type_code,
      label: grp.group_name,
      column_name: grp.column_name,
      dependency: grp.dependency === null ? [] : processDependency(<string[]>grp.dependency),
    }
  }

  function handleCV(grp: TApiGroupByCode<'CV'>) {
    tmpParams = grp.params.map((x) => {
      return { text: x, extra: null }
    })
    tmpGroup = {
      label: grp.group_name,
      code: grp.group_type_code,
      column_name: grp.column_name,
    }
  }

  function handleCR(grp: TApiGroupByCode<'CR'>) {
    tmpParams = grp.params.map((x) => {
      return { text: x, extra: null }
    })
    tmpGroup = {
      label: grp.group_name,
      code: grp.group_type_code,
      column_name: grp.column_name,
    }
  }

  function handleCB(grp: TApiGroupByCode<'CB'>) {
    tmpParams = grp.params.map((x, index) => {
      return { text: x, extra: index === 0 ? 1 : 0 }
    })
    tmpGroup = {
      label: grp.group_name,
      code: grp.group_type_code,
      column_name: grp.column_name,
    }
  }

  function handleCS(grp: TApiGroupByCode<'CS'>) {
    tmpParams = Array(6).fill({ text: '', extra: null })
    tmpGroup = {
      label: grp.group_name,
      code: grp.group_type_code,
      column_name: grp.column_name,
    }
  }

  function handleTG(grp: TApiGroupByCode<'TG'>) {
    tmpParams = grp.params.map((x) => {
      return { text: x.name, extra: x.id }
    })

    tmpGroup = {
      label: grp.group_name,
      code: grp.group_type_code,
      dependency: grp.dependency === null ? [] : processDependency(<string[]>grp.dependency),
      multiple: grp.multiple,
      group_id: grp.group_id,
    }
  }

  function handleTM(grp: TApiGroupByCode<'TM'>) {
    tmpParams = grp.params.map((x) => {
      return { text: x.name, extra: x.id }
    })

    tmpGroup = {
      label: grp.group_name,
      code: grp.group_type_code,
      dependency: grp.dependency === null ? [] : processDependency(<string[]>grp.dependency),
      multiple: grp.multiple,
      group_id: grp.group_id,
    }
  }

  function handleMD(grp: TApiGroupByCode<'MD'>) {
    tmpParams = mediaCollectionNames.value.map((x) => {
      return { text: x, extra: null }
    })
    tmpGroup = {
      label: grp.group_name,
      code: grp.group_type_code,
    }
  }

  function handleOB(grp: TApiGroupByCode<'OB'>) {
    orderByOptions = grp.params
    tmpParams = Array(grp.params.length).fill({ text: '', extra: null })
    tmpGroup = {
      label: grp.group_name,
      code: grp.group_type_code,
    }
  }

  function pad(num: number, size: number): string {
    let s = num + ''
    while (s.length < size) s = '0' + s
    return s
  }

  return {
    normalizeTrio2,
  }
})
