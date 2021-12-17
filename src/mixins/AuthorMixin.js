import Vue from 'vue'
import Vuex from 'vuex'
import { mapState } from 'vuex'
//import { timeout } from 'q'
Vue.use(Vuex)

export const authorMixin = {
  computed: mapState(['user']),
  methods: {
    authorize(reason, route) {
      if (this.$route.path == '/login') {
        return true
      }
      if (typeof route == 'undefined') {
        return false
      }
      if (typeof this.user.expires == 'undefined') {
        alert('in authorMixin user.expires is undefined')
        this.$router.push({ name: 'login' })
      }
      // check if expired
      var date = new Date()
      var timestamp = date.getTime() / 1000
      if (this.user.expires < timestamp) {
        alert(
          'in authorMixin user.expires is expired: ' +
            this.user.expires +
            '< ' +
            timestamp
        )
        this.$router.push({ name: 'login' })
      }
      // can edit anything
      if (
        this.user.scope_countries == '*' &&
        this.user.scope_languages == '*'
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
      if (typeof this.user.scope_countries === 'undefined') {
        this.user.scope_countries = 'undefined'
      }
      if (typeof this.user.scope_languages === 'undefined') {
        this.user.scope_languages = 'undefined'
      }
      // check authority
      if (reason == 'read') {
        return true
      }
      // can edit this langauge in this country
      if (
        this.user.scope_countries.includes(route.country_code) &&
        this.user.scope_languages.includes(route.language_iso)
      ) {
        if (reason != 'readonly') {
          return true
        } else {
          return false
        }
      }
      // can edit anything in country
      if (
        this.user.scope_countries.includes(route.country_code) &&
        this.user.scope_languages == '*'
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
