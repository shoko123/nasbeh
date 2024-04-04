import type { TApiTrio } from '@/js/types/trioTypes'
import type { TCollectionView } from '@/js/types/collectionTypes'
import type { TLocus } from '@/js/types/modules/Locus'
import type { TStone } from '@/js/types/modules/Stone'
import type { TPottery } from '@/js/types/modules/Pottery'

type TModuleInfo = {
  url_name: string
  fields: { id: string }
  modify: { id: string }
  lookup: { id: string }
  tabular: { id: string }
}

type TAllModules<T extends TModuleInfo = TModuleInfo> = {
  Stone: TStone<T>
  Locus: TLocus<T>
  Pottery: TPottery<T>
}

type TAddTagAndSlug<M> = M & { tag: string; slug: string }
type ModuleUnionA<T extends object> = {
  [k in keyof T]: T[k] & { module: k }
}[keyof T]

type ModuleUnionB = ModuleUnionA<TAllModules>
type TModule = keyof TAllModules
type TAllByName<TModuleName extends TModule> = TAllModules[TModuleName]

type TUrlModule = ModuleUnionB['url_name']
type TFieldsUnion = ModuleUnionB['fields']
type TModifyUnion = ModuleUnionB['modify']
type TLookupUnion = ModuleUnionB['lookup']
type TApiPageMainTabularUnion = ModuleUnionB['tabular'] & { slug: string }

type TFieldsByModule<ModuleName extends TModule> = TAllByName<ModuleName>['fields']
type TTabularByModule<ModuleName extends TModule> = TAllByName<ModuleName>['tabular']
type TApiTabularByModule<ModuleName extends TModule> = TAddTagAndSlug<TTabularByModule<ModuleName>>

type FuncSlugToId = (
  slug: string,
) => { success: true; id: string } | { success: false; message: string }

type TApiModuleInit = {
  counts: { items: number; media: number }
  first_id: string
  display_options: {
    item_views: string[]
    main_collection_views: TCollectionView[]
    related_collection_views: TCollectionView[]
  }
  lookups: { column_name: string; group_name: string }[]
  trio: TApiTrio
  welcome_text: string
}
export {
  TModuleInfo,
  TModule,
  TUrlModule,
  TFieldsUnion,
  TModifyUnion,
  TLookupUnion,
  TApiPageMainTabularUnion,
  TFieldsByModule,
  TApiTabularByModule,
  TTabularByModule,
  TApiModuleInit,
  FuncSlugToId,
}
