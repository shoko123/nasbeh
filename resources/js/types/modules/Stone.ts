import type { TModuleInfo } from '@/js/types/moduleTypes'
type TStone<T extends TModuleInfo> = {
  url_name: 'stones'
  fields: {
    id: string
    square: string
    context: string
    occupation_level: string
    base_typology: string
    material: string
    completeness: string
    description: string
    conservation_notes: string
    weight: string
    length: string
    width: string
    height: string
    diameter: string
    dimension_notes: string
    cultural_period: string
    excavation_date: string
    catalog_date: string
    catalogued_by: string
    decoration: string
    excavation_object_id: string
    old_museum_id: string
    uri: string
  }
  modify: Pick<TStone<T>['fields'], 'id' | 'square' | 'context'>
  lookup: Pick<TStone<T>['fields'], 'id'>
  tabular: TStone<T>['fields']
}

export { TStone }
