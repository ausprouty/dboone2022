import { required } from 'vuelidate/lib/validators'
import LogService from '@/services/LogService.js'
import AuthorService from '@/services/AuthorService.js'

export const userMixin = {
  data() {
    return {
      member: {
        firstname: null,
        lastname: null,
        countries: null,
        languages: null,
        start_page: null,
        username: null,
        password: null,
      },
      country_options: [],
      language_options: [],
      languages_present: [],
      countries_present: [],
    }
  },
  validations: {
    member: {
      firstname: { required },
      lastname: { required },
      countries: { required },
      languages: { required },
      start_page: { required },
      username: { required },
      password: {},
    },
  },
  methods: {
    async languageOptionsOld() {
      var options = [
        { display: 'Global', code: '|*|' },
        { display: 'English', code: '|eng|' },
        { display: 'French', code: '|fra|' },
        { display: 'Simplifed Chinese', computed: '|cmn|' },
      ]
      this.language_options = options
      return
    },

    async languageOptions() {
      var j = 0
      var option = {}
      var present = {}
      // get countries of Current User
      if (typeof this.member.languages == 'undefined') {
        this.member.languages = {}
      }
      present = this.member.languages.split('|')
      if (present.length > 0) {
        present.shift()
        present.pop()
      }
      console.log(present)
      var option_count = present.length
      // find all languages you can edit
      var languages = await AuthorService.getLanguagesForAuthorization(
        this.$route.params
      )
      console.log(languages)
      var length = languages.length
      console.log(length)
      for (var i = 0; i < length; i++) {
        option = {}
        option.display =
          languages[i].language_name + ' (' + languages[i].language_iso + ')'
        option.code = '|' + languages[i].language_iso + '|'
        this.language_options.push(option)
        // now see if the user has this option
        for (j = 0; j < option_count; j++) {
          if (present[j] == languages[i].language_iso) {
            this.languages_present.push(option)
          }
        }
      }
      console.log(this.language_options)
      option = {}
      option.display = 'Global'
      option.code = '*'
      this.language_options.push(option)
      for (j = 0; j < option_count; j++) {
        if (present[j] == option.code) {
          this.languages_present.push(option)
        }
      }
      this.$v.member.languages.$model = this.languages_present
      return
    },
    async countryOptions() {
      await this.getCountries()
      // get countries of Current User
      var present = this.member.countries.split('|')
      if (present.length > 0) {
        present.shift()
        present.pop()
      }
      var option_count = present.length
      var j = 0
      // find all countries
      var options = []
      var option = {}
      LogService.consoleLogMessage(this.countries)
      var length = this.countries.length
      for (var i = 0; i < length; i++) {
        option = {}
        if (this.countries[i].english) {
          option.display = this.countries[i].english
        } else {
          option.display = this.countries[i].name
        }
        option.code = '|' + this.countries[i].code + '|'
        options.push(option)
        // now see if the user has this option
        for (j = 0; j < option_count; j++) {
          if (present[j] == this.countries[i].code) {
            this.countries_present.push(option)
          }
        }
      }
      option = {}
      option.display = 'Global'
      option.code = '*'
      options.push(option)
      for (j = 0; j < option_count; j++) {
        if (present[j] == option.code) {
          this.countries_present.push(option)
        }
      }
      this.country_options = options
      this.$v.member.countries.$model = this.countries_present
      return
    },
  },
}
