import AuthorService from '@/services/AuthorService.js'
import ContentService from '@/services/ContentService.js'
import LogService from '@/services/LogService.js'

export const countriesMixin = {
  data() {
    return {
      countries: [
        {
          name: null,
          english: null,
          code: null,
          index: null,
          image: null,
          publish: null,
        },
      ],
      loading: false,
      loaded: null,
      error: null,
      error_message: null,
      prototype: false,
      prototype_date: null,
      publish: false,
      publish_date: null,

      recnum: null,
      content: {
        recnum: '',
        version: '',
        edit_date: '',
        edit_uid: '',
        publish_uid: '',
        publish_date: '',
        language_iso: '',
        country_code: '',
        folder: '',
        filetype: '',
        title: '',
        filename: '',
        text: '',
      },
    }
  },

  methods: {
    async getCountries() {
      try {
        this.error = this.loaded = null
        this.loading = true
        this.countries = []
        AuthorService.bookmarkClear()
        var response = await ContentService.getCountries(this.$route.params)
        if (typeof response != 'undefined') {
          this.countries = response.text
        } else {
          this.countries = []
        }
        if (response.recnum) {
          this.recnum = response.recnum
          this.publish_date = response.publish_date
          this.prototype_date = response.prototype_date
        }
        this.loaded = true
        this.loading = false
      } catch (error) {
        LogService.consoleLogError(
          'There was an error with ContentService.getCountries of CountriesMixin:',
          error
        )
      }
    },
    async showPage(country) {
      localStorage.setItem('lastPage', 'countries')
      this.$route.params.country_code = country.code
      var link = ''
      var res = await ContentService.getLanguages(this.$route.params)
      var response = res.text
      if (response.length === 1) {
        link = 'library'
        if (this.$route.params.version == 'latest') {
          link = 'previewLibrary'
        }
        var language = response[0]
        this.$router.push({
          name: link,
          params: {
            country_code: country.code,
            language_iso: language.iso,
            library_code: 'library',
          },
        })
      } else {
        link = 'languages'
        if (this.$route.params.version == 'latest') {
          link = 'previewLanguages'
        }

        this.$router.push({
          name: link,
          params: { country_code: country.code },
        })
      }
    },
  },
}
