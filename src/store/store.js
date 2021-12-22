import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import { saveStatePlugin } from '@/utils.js' // <-- Import saveStatePlugin

Vue.use(Vuex)

//const bookmark = JSON.parse(localStorage.getItem('bookmark')) || defaultBookmark
//const user = JSON.parse(localStorage.getItem('user')) || defaultUser
//const bookmark = defaultBookmark
//const user = defaultUser
export default new Vuex.Store({
  plugins: [saveStatePlugin], // <-- Use
  state: {
    bookmark: {},
    user: {
      uid: null,
      expires: 0,
    },
    revision: '2.0',
    baseURL: './',
    cssURL: './content/',
    standard: {
      items: ['about', 'basics', 'community', 'compass', 'life', 'steps'],
      ckEditStyleSets: [
        'compass',
        'firststeps',
        'generations',
        'mc2',
        'multiply',
        'myfriends',
        'sent67',
        'train',
      ],
      library: [
        {
          id: '',
          book: '',
          title: '',
          folder: '',
          index: '',
          style: process.env.VUE_APP_SITE_STYLE,
          image: process.env.VUE_APP_SITE_IMAGE,
          format: 'series',
          pages: 1,
          instructions: '',
        },
      ],
    },
    content: {
      recnum: null,
      version: '1.1',
      edit_date: null,
      edit_uid: null,
      publish_uid: null,
      publish_date: null,
      language_iso: null,
      country_code: null,
      folder: null,
      filetype: null,
      title: null,
      filename: null,
      text: null,
    },
  },
  mutations: {
    LOGIN_USER(state, value) {
      state.user = value[0]
    },
    LOGOUT_USER(state) {
      state.user = {}
    },
    SET_USER_DATA(state, userData) {
      state.user = userData
      axios.defaults.headers.common[
        'Authorization'
      ] = `Bearer ${userData.token}`
    },
    NEW_BOOKMARK(state) {
      console.log('STORE - NEW BOOKMARK    ')
      state.bookmark = {}
    },
    UPDATE_ALL_BOOKMARKS(state, value) {
      console.log('STORE - UPDATE ALL BOOKMARKS    ')
      state.bookmark = {}
      if (typeof value.country != 'undefined') {
        state.bookmark.country = value.country
      }
      if (typeof value.language != 'undefined') {
        state.bookmark.language = value.language
      }
      if (typeof value.library != 'undefined') {
        state.bookmark.library = value.library
      }
      if (typeof value.series != 'undefined') {
        state.bookmark.series = value.series
      }
      if (typeof value.book != 'undefined') {
        state.bookmark.book = value.book
      }
      if (typeof value.page != 'undefined') {
        state.bookmark.page = value.page
      }
      console.log('state.bookmark')
      console.log(state.bookmark)
    },
    SET_BOOKMARK(state, [mark, value]) {
      switch (mark) {
        case 'country':
          state.bookmark.country = value
          break
        case 'language':
          state.bookmark.language = value
          break
        case 'library':
          state.bookmark.library = value
          break
        case 'book':
          state.bookmark.book = value
          break
        case 'series':
          state.bookmark.series = value
          break
        case 'page':
          state.bookmark.page = value
          break
      }
    },
    UNSET_BOOKMARK(state, [mark]) {
      switch (mark) {
        case 'country':
          state.bookmark.country = {
            code: 'au',
            english: '',
            name: '',
            index: '',
            image: '',
          }
          break
        case 'language':
          state.bookmark.language = {
            id: '',
            folder: '',
            iso: '',
            name: '',
            bible: '',
            life_issues: '',
            image_dir: '',
            nt: '',
            ot: '',
            rldir: '',
          }
          break
        case 'library':
          state.bookmark.library = []
          break
        case 'book':
          state.bookmark.book = {
            book: '',
            folder: '',
            format: '',
            id: '',
            image: '',
            index: '',
            instructions: '',
            title: '',
          }
          break
        case 'series':
          state.bookmark.series = {
            series: '',
            language: '',
            description: 'This means not set',
            chapters: [],
          }
          break
        case 'page':
          state.bookmark.page = ''
          break
      }
    },
  },
  actions: {
    updateAllBookmarks({ commit }, value) {
      commit('UPDATE_ALL_BOOKMARKS', value)
    },
    newBookmark({ commit }, value) {
      commit('UNSET_BOOKMARK', [value])
    },
    updateBookmark({ commit }, [mark, value]) {
      commit('SET_BOOKMARK', [mark, value])
    },
    unsetBookmark({ commit }, [mark]) {
      commit('UNSET_BOOKMARK', [mark])
    },
    loginUser({ commit }, [mark]) {
      commit('LOGIN_USER', [mark])
    },
    logoutUser({ commit }) {
      commit('LOGOUT_USER')
    },
  },
})
