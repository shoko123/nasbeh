//showTypes.ts
//types returned from 'show' api route
import { TApiArray } from '@/js/types/collectionTypes'
import { TFieldsUnion } from '@/js/types/moduleTypes'

type TApiItemShow<F extends TFieldsUnion> = {
  fields: F
  model_tags: { group_label: string; tag_text: string }[]
  global_tags: { group_label: string; tag_text: string }[]
  media: TApiArray<'media'>[]
  related: TApiArray<'related'>[]
  slug: string
  short: string
}

type TApiItemUpdate = {
  fields: TFieldsUnion
  slug: string
}

export { TApiItemShow, TApiItemUpdate }
