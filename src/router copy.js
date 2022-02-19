import Vue from 'vue'
import Router from 'vue-router'

import CountriesEdit from './views/CountriesEdit.vue'
import CountriesPreview from './views/CountriesPreview.vue'
import CountriesSort from './views/CountriesSort.vue'

import LibraryIndexEdit from './views/LibraryIndexEdit.vue'
//import LibraryIndexEdit from './views/LibraryIndexEditShort.vue'
import LibraryIndexPreview from './views/LibraryIndexPreview.vue'

import Languages from './views/Languages.vue'

import LanguageEdit from './views/LanguagesEdit.vue'
import LanguagesPreview from './views/LanguagesPreview.vue'
import LanguagesSort from './views/LanguagesSort.vue'

import LibraryEdit from './views/LibraryEdit.vue'

import LibraryPreview from './views/LibraryPreview.vue'
import LibraryPreview2 from './views/LibraryPreview2.vue'
import LibrarySort from './views/LibrarySort.vue'

import SeriesEdit from './views/SeriesEdit.vue'
import SeriesPreview from './views/SeriesPreview.vue'
import SeriesSort from './views/SeriesSort.vue'

import PageEdit from './views/PageEdit.vue'
import PagePreview from './views/PagePreview.vue'
import PageCompare from './views/PageCompare.vue'

import Template from './views/Template.vue'

import Login from './views/Login.vue'
import Register from './views/Register.vue'

import Admin from './views/Admin.vue'
import Prototype from './views/Prototype.vue'
import TestGenerations from './views/TestGenerations.vue'
import TestMC2 from './views/TestMC2.vue'
import TestMyFriends from './views/TestMyFriends.vue'
import Upload from './views/Upload.vue'
import Validate from './views/Validate.vue'
import User from './views/User.vue'
import Users from './views/Users.vue'

// for CKFinder

//edit.myfriends.network/preview

import NotFoundComponent from './views/NotFound.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/prototype',
      name: 'prototype',
      component: Prototype,
    },
    {
      path: '/',
      name: 'home',
      component: CountriesPreview,
    },
    {
      path: '/languages',
      name: 'languages',
      component: Languages,
    },
    {
      path: '/compare/page/:country_code/:language_iso/:library_code/:folder_name/:filename/:cssFORMATTED',
      name: 'comparePage',
      component: PageCompare,
      props: true,
    },

    {
      path: '/farm',
      name: 'farm',
      component: Register,
      props: true,
    },

    {
      path: '/edit/countries',
      name: 'editCountries',
      component: CountriesEdit,
    },
    {
      path: '/edit/languages/:country_code',
      name: 'editLanguages',
      component: LanguageEdit,
      props: true,
    },
    {
      path: '/edit/libraryIndex/:country_code/:language_iso',
      name: 'editLibraryIndex',
      component: LibraryIndexEdit,
    },
    {
      path: '/edit/library/:country_code/:language_iso/:library_code?',
      name: 'editLibrary',
      component: LibraryEdit,
      props: true,
    },

    {
      path: '/edit/series/:country_code/:language_iso/:library_code/:folder_name',
      name: 'editSeries',
      component: SeriesEdit,
      props: true,
    },
    {
      path: '/edit/page/:country_code/:language_iso/:library_code/:folder_name/:filename/:cssFORMATTED/:styles_set?',
      name: 'editPage',
      component: PageEdit,
      props: true,
    },
    {
      path: '/preview',
      name: 'previewCountries',
      component: CountriesPreview,
    },
    {
      path: '/preview/languages/:country_code',
      name: 'previewLanguages',
      component: LanguagesPreview,
      props: true,
    },
    {
      path: '/preview/libraryIndex/:country_code/:language_iso',
      name: 'previewLibraryIndex',
      component: LibraryIndexPreview,
    },
    {
      path: '/preview/library/:country_code/:language_iso/:library_code',
      name: 'previewLibrary',
      component: LibraryPreview,
      props: true,
    },
    {
      path: '/preview/library2/:country_code/:language_iso/:library_code',
      name: 'previewLibrary2',
      component: LibraryPreview2,
      props: true,
    },
    {
      path: '/preview/series/:country_code/:language_iso/:library_code/:folder_name',
      name: 'previewSeries',
      component: SeriesPreview,
      props: true,
    },
    {
      path: '/preview/page/:country_code/:language_iso/:library_code/:folder_name/:filename',
      name: 'previewPage',
      component: PagePreview,
      props: true,
    },
    {
      path: '/sort/countries',
      name: 'sortCountries',
      component: CountriesSort,
    },
    {
      path: '/sort/languages/:country_code',
      name: 'sortLanguages',
      component: LanguagesSort,
      props: true,
    },
    {
      path: '/sort/library/:country_code/:language_iso/:library_code/',
      name: 'sortLibrary',
      component: LibrarySort,
      props: true,
    },
    {
      path: '/sort/series/:country_code/:language_iso/:library_code/:folder_name',
      name: 'sortSeries',
      component: SeriesSort,
      props: true,
    },
    {
      path: '/template/:country_code/:language_iso/:library_code/:title/:template/:cssFORMATTED/:styles_set/:book_code/:book_format',
      name: 'createTemplate',
      component: Template,
      props: true,
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      props: false,
    },
    {
      path: '/users',
      name: 'users',
      component: Users,
      props: false,
    },
    {
      path: '/user/:uid',
      name: 'user',
      component: User,
      props: true,
    },
    {
      path: '/test/generations',
      name: 'testGenerations',
      component: TestGenerations,
      props: false,
    },
    {
      path: '/test/myfriends',
      name: 'testMyfriends',
      component: TestMyFriends,
      props: false,
    },
    {
      path: '/test/mc2',
      name: 'testmc2',
      component: TestMC2,
      props: false,
    },

    {
      path: '/validate',
      name: 'validate',
      component: Validate,
      props: false,
    },
    {
      path: '/admin',
      name: 'admin',
      component: Admin,
      props: true,
    },
    {
      path: '/upload',
      name: 'upload',
      component: Upload,
      props: true,
    },
    {
      path: '*',
      component: NotFoundComponent,
    },
  ],
})
