import type { TModuleInfo } from '@/js/types/moduleTypes'
type TStone<T extends TModuleInfo> = {
  url_name: 'stones'
  fields: {
    id: string
    square: string
    context: string
    excavation_date: Date
    occupation_level: string
    cataloger_material: string
    whole: boolean
    cataloger_typology: string
    cataloger_description: string
    conservation_notes: string
    weight: string
    length: string
    width: string
    height: string
    diameter: string
    dimension_notes: string
    cultural_period: string
    excavation_object_id: string
    old_museum_id: string
    cataloger_id: number
    catalog_date: Date
    specialist_description: string
    specialist_date: Date | string
    thumbnail: string
    uri: string | null
    base_type_id: number
    material_id: number
  }
  modify: TStone<T>['fields']
  lookup: Pick<TStone<T>['fields'], 'id'>
  tabular: TStone<T>['fields']
}

export { TStone }
