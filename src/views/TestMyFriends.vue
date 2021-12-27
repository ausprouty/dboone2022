<template>
  <div>
    <h1>Select Test</h1>
    <BaseSelect
      label="Test"
      :options="test_options"
      v-model="test"
      v-on:change="runTest(test)"
      class="field"
    />
    {{ this.result }}
  </div>
</template>
<script>
import AuthorService from '@/services/AuthorService.js'
import ContentService from '@/services/ContentService.js'
import LogService from '@/services/LogService.js'
import store from '@/store/store.js'
import { mapState } from 'vuex'

export default {
  data() {
    return {
      test: '',
      result: '',
      test_options: [
        'testCountries',
        'testLanguages',
        'testLibrary',
        'testGetPage',
        'testGetPageOrTemplate',
        'testLibraryIndex',
        'testSeries',
        'testPage',
        'testBookmarkCountry',
        'testBookmarkLanguage',
        'testBookmarkLibraryIndex',
        'testBookmarkLibraryFriends',
        'testBookmarkSeries',
        'testBookmarkPage',
        'testSetupCountries',
      ],
    }
  },
  computed: mapState(['user']),
  methods: {
    async runTest(test) {
      var response = await this[test]()
      this.result = response
      LogService.consoleLog(test, response)
    },
    async testCountries() {
      var params = this.setupParams()
      var response = await ContentService.getCountries(params)
      return response
    },
    setupParams() {
      var params = {}
      params.my_uid = store.state.user.uid
      return params
    },
    async testGetPage() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.library_code = 'friends'
      params.folder_name = 'basics'
      params.filename = 'basics104'
      params.bookmark = await AuthorService.bookmark(params)
      var response = await AuthorService.getPage(params)
      return response
    },
    async testGetPageOrTemplate() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.library_code = 'friends'
      params.folder_name = 'basics'
      params.filename = 'basics104'
      params.bookmark = await AuthorService.bookmark(params)
      var response = await AuthorService.getPageOrTemplate(params)
      return response
    },
    async testLanguages() {
      var params = this.setupParams()
      params.country_code = 'AU'
      var response = await ContentService.getLanguages(params)

      return response
    },
    async testLibrary() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.library_code = 'friends'
      var response = await ContentService.getLibrary(params)
      return response
    },
    async testLibraryIndex() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      var response = await ContentService.getLibraryIndex(params)
      return response
    },
    async testSeries() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.folder_name = 'basics'
      var response = await ContentService.getSeries(params)
      return response
    },
    async testPage() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.folder_name = 'basics'
      params.filename = 'basics101'
      var response = await ContentService.getPage(params)
      return response
    },

    async testBookmarkCountry() {
      var params = this.setupParams()
      params.country_code = 'AU'
      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkLanguage() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkLibraryIndex() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.library_code = 'index'
      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkLibraryFriends() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.library_code = 'friends'

      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkSeries() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.library_code = 'friends'
      params.folder_name = 'basics'
      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkPage() {
      var params = this.setupParams()
      params.country_code = 'AU'
      params.language_iso = 'eng'
      params.library_code = 'friends'
      params.folder_name = 'basics'
      params.filename = 'basics104'
      var response = await AuthorService.bookmark(params)
      return response
    },

    async testSetupCountries() {
      var response = await AuthorService.setupCountries(this.countries)
      if (response.data != 'success') {
        alert('setupCountries not successful')
      }
      return response
    },
  },
}
</script>
