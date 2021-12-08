import Vue from 'vue'
import Vuex from 'vuex'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import { mapState } from 'vuex'
Vue.use(Vuex)

export const bookMarkMixin = {
  computed: mapState(['bookmark', 'standard']),

  methods: {
    async UnsetBookmarks() {
      return this.$store.dispatch('unsetBookmark', ['country'])
    },
    async CheckBookmarks(route) {
      try {
        var bmark = await AuthorService.bookmark(route)
        return bmark
        //dispatch is done in AuthorService
        //this.$store.dispatch('updateAllBookmarks', bookmark)
      } catch (error) {
        LogService.consoleLogError(
          'BOOKMARK MIXIN -- There was an error setting bookmarks:',
          error
        ) // Logs out the error
        this.error = error.toString() + 'BOOKMARK MIXIN --CheckBookmarks'
      }
      return true
    },
  },
}
