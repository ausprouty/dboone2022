import Vue from 'vue'
import Vuex from 'vuex'
import store from '@/store/store.js'
//import { mapState } from 'vuex'
//import { timeout } from 'q'
Vue.use(Vuex)

export const authorizeMixin = {
  //computed: mapState(['user']),
  methods: {
    authorize(reason, route) {
      console.log('this is the authorizeMixin speaking')
      console.log(reason)
      console.log(this.$store.state.user)
      console.log(store.state.user)
      if (this.$route.path == '/login') {
        return true
      }
      if (typeof route == 'undefined') {
        return false
      }
      if (typeof store.state.user == 'undefined') {
        this.$router.push({ name: 'login' })
      }
      // check if expired
      //  var date = new Date()
      //   var timestamp = date.getTime() / 1000
      // if (store.state.user.expires < timestamp) {
      //
      //   this.$router.push({ name: 'login' })
      //}
      // can edit anything
      if (
        store.state.user.scope_countries == '*' &&
        store.state.user.scope_languages == '*'
      ) {
        if (reason != 'readonly') {
          return true
        } else {
          return false
        }
      }
      // check route
      if (typeof route.country_code === 'undefined') {
        route.country_code = 'undefined'
      }
      if (typeof route.language_iso === 'undefined') {
        route.language_iso = 'undefined'
      }
      // check for legacy errors
      if (typeof store.state.user.scope_countries === 'undefined') {
        store.state.user.scope_countries = 'undefined'
      }
      if (typeof store.state.user.scope_languages === 'undefined') {
        store.state.user.scope_languages = 'undefined'
      }
      // check authority
      if (reason == 'read') {
        return true
      }
      // can edit this langauge in this country
      if (
        store.state.user.scope_countries.includes(route.country_code) &&
        store.state.user.scope_languages.includes(route.language_iso)
      ) {
        if (reason != 'readonly') {
          return true
        } else {
          return false
        }
      }
      // can edit anything in country
      if (
        store.state.user.scope_countries.includes(route.country_code) &&
        store.state.user.scope_languages == '*'
      ) {
        if (reason != 'readonly') {
          return true
        } else {
          return false
        }
      }

      // can only read
      if (reason == 'readonly') {
        return true
      }
      return false
    },
  },
}
