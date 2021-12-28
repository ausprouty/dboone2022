import AuthorService from '@/services/AuthorService.js'
import ContentService from '@/services/ContentService.js'
import LogService from '@/services/LogService.js'

export const languageMixin = {
  data() {
    return {
      language: [],
      languages: [],
      more_languages: 'More Languages',
      choose_language: 'Choose Language',
      content: {},
      loading: false,
      loaded: null,
      error: null,
      error_message: null,
      prototype: false,
      prototype_date: null,
      publish: false,
      publish_date: null,
      recnum: null,
    }
  },
  methods: {
    async checkBookmark() {
      try {
        this.error = this.loaded = null
        this.loading = true
        this.languages = []
        // LogService.consoleLogMessage('about the check bookmarks')
        await AuthorService.bookmark(this.$route.params)
      } catch (error) {
        LogService.consoleLogError(
          'There was an error withcheckBookmarks in getLanguages:',
          error
        )
      }
    },
    async getLanguages() {
      await this.checkBookmark()
      try {
        console.log('route params')
        console.log(this.$route.params)
        var response = await ContentService.getLanguages(this.$route.params)
        if (typeof response !== 'undefined') {
          this.languages = response.text.languages
          // need to have id so we can delete them
          // now deal with legacy data
          var len = this.languages.length
          for (var i = 0; i < len; i++) {
            this.languages[i].id = i
            if (typeof this.languages[i].watch == 'undefined') {
              this.languages[i].watch = null
              this.languages[i].notes = null
              this.languages[i].send_notes = null
              this.languages[i].read = null
            }
            if (typeof this.languages[i].read == 'undefined') {
              this.languages[i].read = null
            }
          }
          this.choose_language = response.text.choose_language
          this.more_languages = response.text.more_languages
        } else {
          this.languages = []
          this.choose_language = 'Choose Language'
          this.more_languages = 'More Languages'
        }

        if (typeof response.recnum != 'undefined') {
          this.recnum = response.recnum
          this.publish_date = response.publish_date
          this.prototype_date = response.prototype_date
        }
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in LanguageMixin:',
          error
        )
      }
    },
  },
}
