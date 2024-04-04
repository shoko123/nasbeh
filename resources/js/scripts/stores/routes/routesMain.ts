// routesStore.js
//handles the entire routing mechanism - parsing, loading resources, error handling

import { ref } from 'vue'
import { defineStore, storeToRefs } from 'pinia'
import {
  useRouter,
  type LocationQueryRaw,
  type RouteLocationNormalized,
  type RouteLocationRaw,
} from 'vue-router'
import type { TModule } from '@/js/types/moduleTypes'
import type { TRouteInfo, TPageName, TPlanAction } from '@/js/types/routesTypes'

import { useRoutesParserStore } from './routesParser'
import { useRoutesPlanTransitionStore } from './routesPlanTransition'
import { useRoutesPrepareStore } from './routesPrepare'
import { useAuthStore } from '../auth'
import { useNotificationsStore } from '../notifications'
import { useCollectionMainStore } from '../collections/collectionMain'
import { useFilterStore } from '../trio/filter'

export const useRoutesMainStore = defineStore('routesMain', () => {
  const router = useRouter()
  const { parseModule } = useRoutesParserStore()
  const { planTransition } = useRoutesPlanTransitionStore()
  const { showSnackbar, showSpinner } = useNotificationsStore()

  const urlModuleFromModule: { [key in TModule]: string } = {
    Locus: 'loci',
    Pottery: 'pottery',
    Stone: 'stones',
  }

  const current = ref<TRouteInfo>({
    url_module: undefined,
    slug: undefined,
    url_full_path: undefined,
    module: undefined,
    name: 'home',
    queryParams: undefined,
    preLoginFullPath: undefined,
  })

  const to = ref<TRouteInfo>({
    url_module: undefined,
    slug: undefined,
    url_full_path: undefined,
    module: undefined,
    name: 'home',
    queryParams: undefined,
    preLoginFullPath: undefined,
  })

  const inTransition = ref(false)

  async function handleRouteChange(
    handle_to: RouteLocationNormalized,
    handle_from: RouteLocationNormalized,
  ): Promise<RouteLocationRaw | true> {
    //These elements have to be here to prevent circular reference
    const { prepareForNewRoute } = useRoutesPrepareStore()
    const { authenticated } = storeToRefs(useAuthStore())

    console.log(`handleRouteChange(${String(handle_from.name)} -> ${String(handle_to.name)})`)

    //authorize
    if (handle_to.name === 'login' && authenticated.value) {
      console.log(
        `Authenticated user trying to access login route. to.name: ${handle_to.name}, authenticated: ${authenticated.value}`,
      )
      return handle_from
    }

    if (!authorize(handle_to.path)) {
      showSnackbar('Unauthorized; redirected to Login Page')
      return { name: 'login' }
    }

    to.value.name = <TPageName>handle_to.name
    to.value.url_full_path = handle_to.fullPath

    //parse module
    //console.log(`A.current: ${JSON.stringify(current.value, null, 2)}\nto: ${JSON.stringify(to.value, null, 2)})`)
    if (Object.prototype.hasOwnProperty.call(handle_to.params, 'module')) {
      const res = parseModule(<string>handle_to.params.module)
      if (res.success) {
        to.value.module = <TModule>res.module
        to.value.url_module = res.url_module
      } else {
        console.log(`parseModule returned ${JSON.stringify(res, null, 2)}`)
        showSnackbar(`${res.message}. redirected to Home Page`)
        inTransition.value = false
        return { name: 'home' }
      }
    } else {
      to.value.module = undefined
      to.value.url_module = undefined
    }

    //console.log(`after successful module parse. to: ${JSON.stringify(to.value, null, 2)})`)

    //verify that the transition is legal and prepare the plan required for a successful transition.

    const res1 = planTransition(handle_to, handle_from)

    if (!res1.success) {
      console.log('plan failed...')
      showSnackbar(`${res1.message} Redirected to Home Page`)
      inTransition.value = false
      return { name: 'home' }
    }

    console.log(`Plan successful: ${JSON.stringify(res1.data, null, 2)}`)

    //Access server and load stuff (async)
    inTransition.value = true

    const res = await prepareForNewRoute(
      <TModule>to.value.module,
      handle_to.query,
      <string>handle_to.params.slug,
      <TPlanAction[]>res1.data,
      handle_from.name === undefined,
    )
    if (res.success) {
      finalizeRouting(handle_to, handle_from)
    } else {
      inTransition.value = false
      if (res.message === 'Error: Empty result set' && handle_from.name === 'filter') {
        showSnackbar('No results returned. Please modify query and resubmit!')
        return { name: 'filter' }
      } else {
        showSpinner(false)
        showSnackbar(`Navigation Error: ${res.message}. Redirected to home page`)
        return { name: 'home' }
      }
    }
    //console.log(`router.beforeEach returned ${JSON.stringify(res, null, 2)}`);
    inTransition.value = false
    return true
  }

  function authorize(path: string) {
    //has to be here to prevent circular reference
    const { authenticated, accessibility } = storeToRefs(useAuthStore())

    if (path === '/auth/login' || path === '/') {
      return true
    }
    return !(accessibility.value.authenticatedUsersOnly && !authenticated.value)
  }

  function finalizeRouting(
    handle_to: RouteLocationNormalized,
    handle_from: RouteLocationNormalized,
  ) {
    current.value.name = <TPageName>handle_to.name
    current.value.module = to.value.module
    current.value.url_module = to.value.url_module
    current.value.queryParams = ['index', 'show'].includes(current.value.name)
      ? handle_to.query
      : undefined
    current.value.url_full_path = handle_to.fullPath
    current.value.preLoginFullPath = to.value.name === 'login' ? handle_from.fullPath : undefined

    switch (handle_to.name) {
      case 'show':
      case 'update':
      case 'media':
      case 'tag':
        current.value.slug = <string>handle_to.params.slug
        break
      default:
        current.value.slug = undefined
    }

    //console.log(`finalizing routing. current: ${JSON.stringify(current.value)}`)
    //current.value = Object.assign(to.value);
    //current.value = JSON.parse(JSON.stringify(to.value))
  }

  function pushHome(message = '') {
    console.log(`goHome`)
    inTransition.value = false
    if (message !== '') {
      showSnackbar(message)
    }
    routerPush('home')
  }

  function routerPush(
    routeName: string,
    slug: string = 'none',
    module: TModule | 'current' = 'current',
    keepQuery: boolean = true,
  ) {
    let urlModule,
      query = null
    switch (routeName) {
      case 'back1':
        router.go(-1)
        break

      case 'home':
      case 'dashboard':
        router.push({ name: routeName })
        break

      case 'login':
      case 'register':
      case 'forgot-password':
      case 'reset-password':
        router.push({ name: routeName })
        break

      case 'welcome':
      case 'filter':
      case 'create':
        urlModule = module === 'current' ? current.value.url_module : urlModuleFromModule[module]
        router.push({ name: routeName, params: { module: urlModule } })
        break

      case 'index':
        urlModule = module === 'current' ? current.value.url_module : urlModuleFromModule[module]
        query = keepQuery ? current.value.queryParams : ''
        router.push({
          name: 'index',
          params: { module: urlModule },
          query: <LocationQueryRaw>query,
        })
        break

      case 'show':
        urlModule = module === 'current' ? current.value.url_module : urlModuleFromModule[module]
        query = keepQuery ? current.value.queryParams : ''
        router.push({
          name: 'show',
          params: { module: urlModule, slug: slug },
          query: <LocationQueryRaw>query,
        })
        break

      case 'update':
      case 'media':
      case 'tag':
        router.push({ name: routeName, params: { module: current.value.url_module, slug: slug } })
        break
    }
  }

  function moveFromItemToItem<IDtype extends string | number>(
    slug: string,
    id: IDtype,
    module: TModule | 'current' = 'current',
  ) {
    const { itemIndexById } = useCollectionMainStore()
    const { clearSelectedFilters } = useFilterStore()

    console.log(
      `moveFromItemToItem "${current.value.module} ${current.value.slug}" -> "${module} ${slug}" (id: ${id})`,
    )
    if (current.value.module === module) {
      if (current.value.slug === slug) {
        console.log(`moveTo same item - ignore`)
        return
      }
      if (itemIndexById(id) !== -1) {
        console.log(`moveTo item that is already in the current collection - go!`)
        routerPush('show', slug, module)
      } else {
        console.log(
          `moveTo item that is NOT in the current collection - remove filters and reload collection!`,
        )
        clearSelectedFilters()
        showSnackbar(`Filters removed and result set reloaded`)
        routerPush('show', slug, module, false)
      }
    } else {
      console.log(`GoTo item in a different module`)
      routerPush('show', slug, module, false)
    }
  }
  return { inTransition, current, to, handleRouteChange, routerPush, pushHome, moveFromItemToItem }
})
