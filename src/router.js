import Vue from 'vue'
import Router from 'vue-router'
import CountriesPreview from './views/CountriesPreview.vue'
import Login from './views/Login.vue'
import NotFoundComponent from './views/NotFound.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/prototype',
      name: 'prototype',
      component: function () {
        return import(
          /* webpackChunkName: "prototype" */ './views/Prototype.vue'
        )
      },
    },
    {
      path: '/',
      name: 'home',
      component: CountriesPreview,
    },
    {
      path: '/languages',
      name: 'languages',
      component: function () {
        return import(
          /* webpackChunkName: "languages" */ './views/Languages.vue'
        )
      },
    },
    {
      path: '/compare/page/:country_code/:language_iso/:library_code/:folder_name/:filename/:cssFORMATTED',
      name: 'comparePage',
      component: function () {
        return import(
          /* webpackChunkName: "comparePage" */ './views/PageCompare.vue'
        )
      },
      props: true,
    },

    {
      path: '/farm',
      name: 'farm',
      component: function () {
        return import(/* webpackChunkName: "farm" */ './views/Register.vue')
      },
      props: true,
    },
    {
      path: '/edit/countries',
      name: 'editCountries',
      component: function () {
        return import(
          /* webpackChunkName: "editCountries" */ './views/CountriesEdit.vue'
        )
      },
    },
    {
      path: '/edit/languages/:country_code',
      name: 'editLanguages',
      component: function () {
        return import(
          /* webpackChunkName: "editLanguages" */ './views/LanguagesEdit.vue'
        )
      },
      props: true,
    },
    {
      path: '/edit/libraryIndex/:country_code/:language_iso',
      name: 'editLibraryIndex',
      component: function () {
        return import(
          /* webpackChunkName: "editLibraryIndex" */ './views/LibraryIndexEdit.vue'
        )
      },
    },
    {
      path: '/edit/library/:country_code/:language_iso/:library_code?',
      name: 'editLibrary',
      component: function () {
        return import(
          /* webpackChunkName: "editLibrary" */ './views/LibraryEdit.vue'
        )
      },
      props: true,
    },
    {
      path: '/edit/series/:country_code/:language_iso/:library_code/:folder_name',
      name: 'editSeries',
      component: function () {
        return import(
          /* webpackChunkName: "editSeries" */ './views/SeriesEdit.vue'
        )
      },
      props: true,
    },
    {
      path: '/edit/page/:country_code/:language_iso/:library_code/:folder_name/:filename/:cssFORMATTED/:styles_set?',
      name: 'editPage',
      component: function () {
        return import(/* webpackChunkName: "editPage" */ './views/PageEdit.vue')
      },
      props: true,
    },
    {
      path: '/preview',
      name: 'previewCountries',
      component: function () {
        return import(
          /* webpackChunkName: "previewCountries" */ './views/CountriesPreview.vue'
        )
      },
    },
    {
      path: '/preview/languages/:country_code',
      name: 'previewLanguages',
      component: function () {
        return import(
          /* webpackChunkName: "previewLanguages" */ './views/LanguagesPreview.vue'
        )
      },
      props: true,
    },
    {
      path: '/preview/libraryIndex/:country_code/:language_iso',
      name: 'previewLibraryIndex',

      component: function () {
        return import(
          /* webpackChunkName: "previewLibraryIndex" */ './views/LibraryIndexPreview.vue'
        )
      },
    },
    {
      path: '/preview/library/:country_code/:language_iso/:library_code',
      name: 'previewLibrary',
      component: function () {
        return import(
          /* webpackChunkName: "previewLibrary" */ './views/LibraryPreview.vue'
        )
      },
      props: true,
    },
    {
      path: '/preview/library2/:country_code/:language_iso/:library_code',
      name: 'previewLibrary2',

      component: function () {
        return import(
          /* webpackChunkName: "previewLibrary2" */ './views/LibraryPreview2.vue'
        )
      },
      props: true,
    },
    {
      path: '/preview/series/:country_code/:language_iso/:library_code/:folder_name',
      name: 'previewSeries',
      component: function () {
        return import(
          /* webpackChunkName: "previewSeries" */ './views/SeriesPreview.vue'
        )
      },
      props: true,
    },
    {
      path: '/preview/page/:country_code/:language_iso/:library_code/:folder_name/:filename',
      name: 'previewPage',
      component: function () {
        return import(
          /* webpackChunkName: "previewPage" */ './views/PagePreview.vue'
        )
      },
      props: true,
    },
    {
      path: '/sort/countries',
      name: 'sortCountries',
      component: function () {
        return import(
          /* webpackChunkName: "sortCountries" */ './views/CountriesSort.vue'
        )
      },
    },
    {
      path: '/sort/languages/:country_code',
      name: 'sortLanguages',
      component: function () {
        return import(
          /* webpackChunkName: "sortLanguages" */ './views/LanguagesSort.vue'
        )
      },
      props: true,
    },
    {
      path: '/sort/library/:country_code/:language_iso/:library_code/',
      name: 'sortLibrary',

      component: function () {
        return import(
          /* webpackChunkName: "sortLibrary" */ './views/LibrarySort.vue'
        )
      },
      props: true,
    },
    {
      path: '/sort/series/:country_code/:language_iso/:library_code/:folder_name',
      name: 'sortSeries',
      component: function () {
        return import(
          /* webpackChunkName: "sortSeries" */ './views/SeriesSort.vue'
        )
      },
      props: true,
    },
    {
      path: '/template/:country_code/:language_iso/:library_code/:title/:template/:cssFORMATTED/:styles_set/:book_code/:book_format',
      name: 'createTemplate',
      component: function () {
        return import(
          /* webpackChunkName: "createTemplate" */ './views/Template.vue'
        )
      },
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
      component: function () {
        return import(/* webpackChunkName: "users" */ './views/Users.vue')
      },
      props: false,
    },
    {
      path: '/user/:uid',
      name: 'user',
      component: function () {
        return import(/* webpackChunkName: "user" */ './views/User.vue')
      },
      props: true,
    },
    {
      path: '/test/generations',
      name: 'testGenerations',
      component: function () {
        return import(
          /* webpackChunkName: "testGenerations" */ './views/TestGenerations.vue'
        )
      },
      props: false,
    },
    {
      path: '/test/myfriends',
      name: 'testMyfriends',
      component: function () {
        return import(
          /* webpackChunkName: "testMyfriends" */ './views/TestMyFriends.vue'
        )
      },
      props: false,
    },
    {
      path: '/test/mc2',
      name: 'testmc2',
      component: function () {
        return import(/* webpackChunkName: "testmc2" */ './views/TestMC2.vue')
      },
      props: false,
    },

    {
      path: '/validate',
      name: 'validate',
      component: function () {
        return import(/* webpackChunkName: "validate" */ './views/Validate.vue')
      },
      props: false,
    },
    {
      path: '/admin',
      name: 'admin',
      component: function () {
        return import(/* webpackChunkName: "admin" */ './views/Admin.vue')
      },
      props: true,
    },
    {
      path: '/upload',
      name: 'upload',

      component: function () {
        return import(/* webpackChunkName: "upload" */ './views/Upload.vue')
      },
      props: true,
    },
    {
      path: '*',
      component: NotFoundComponent,
    },
  ],
})
