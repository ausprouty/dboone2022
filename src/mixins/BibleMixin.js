import BibleService from '@/services/BibleService.js'
import LogService from '@/services/LogService.js'
export const bibleMixin = {
  methods: {
    /* expects  var params = {
          language_iso: language_iso,
          testament: "OT" or "NT" or "Full"
        }
    */
    async getBibleVersions(params) {
      try {
        var versions = []
        if (params.language_iso.length > 2) {
          var response = await BibleService.getBibleVersions(params)
          if (response !== false) {
            versions = response
          }
          return versions
        }
      } catch (error) {
        LogService.consoleLogError(
          'BIBLE MIXIN -- There was an error finding Bible Versions:',
          error
        )
        this.error = error.toString() + 'BIBLE MIXIN -- getBibleVersions'
        return null
      }
    },
  },
}
