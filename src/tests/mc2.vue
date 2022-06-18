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
import BibleService from '@/services/BibleService.js'
import ContentService from '@/services/ContentService.js'
import PrototypeService from '@/services/PrototypeService.js'
import PublishService from '@/services/PublishService.js'
import NoJSService from '@/services/NoJSService.js'
import SDCardService from '@/services/SDCardService.js'
import PDFService from '@/services/PDFService.js'
import LogService from '@/services/LogService.js'
import store from '@/store/store.js'
import { mapState } from 'vuex'

export default {
  data() {
    return {
      test: '',
      result: '',
      test_options: [
        'testBibleBrainGetBibles',
        'testBibleBrainGetBooks',
        'testbibleBrainGetChapterResources',
        'testBibleBrainGetVideo',
        'testGetBooks',
        'testGetFooters',
        'testGetLanguages',
        'testPdf',
        'testNoJSPage',
        'testVideoConcatBat',
        'testVideoMakeBatFileForSDCard',
        'testPrototypePage',
        'testPublishPage',
        'testSDCardPage',
        'testBibleABSUpdate',
        'testBibleABSnew',
        'testBibleDBTUpdate',
        'testBibleDBTCheckDetail',
        'testBibleDBTCheckIndex',
        'testBibleVerse',
        'testBibleLinkMaker',
        'testBibleVersionsGet',
        'testBookmarkCountry',
        'testBookmarkLanguage',
        'testBookmarkLibrary',
        'testBookmarkSeries',
        'testBookmarkPage',
        'testCountries',
        'testLanguages',
        'testLibrary',
        'testImagesGet',
        'testGetContentFoldersForLanguage',
        'testLanguagesGet',
        'testLanguagesAvailable',
        'testLanguagesForAuthorization',
        'testLogin',
        'testGetPage',
        'testGetPageOrTemplate',
        'testSetupImageFolder',
        'testSetupCountries',
        'testSetupLanguageFolder',
        'testSeries',
        'testGetPage',
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
    setupParams() {
      var params = {}
      params.my_uid = store.state.user.uid
      return params
    },
    async testBibleBrainGetBibles() {
      var params = this.setupParams()
      params.language_code = 'AMH'
      var response = await BibleService.bibleBrainGetBibles(params)
      return response
    },

    async testBibleBrainGetBooks() {
      var params = this.setupParams()
      params.fileset = 'AMHEVG'
      var response = await BibleService.bibleBrainGetBooks(params)
      return response
    },
    async testbibleBrainGetChapterResources() {
      var params = this.setupParams()
      params.fileset = 'AMHEVG'
      params.book = 'MAT'
      params.chapter = 2
      var response = await BibleService.bibleBrainGetChapterResources(params)
      return response
    },

    async testBibleBrainGetVideo() {
      var params = this.setupParams()
      params.fileset = 'AMHEVG'
      params.bookId = 'MRK'
      params.chapterId = 2
      var response = await BibleService.bibleBrainGetVideo(params)
      return response
    },
    async testBibleABSUpdate() {
      var params = this.setupParams()
      var response = await AuthorService.bibleUpdateABS(params)
      return response
    },
    async testBibleABSnew() {
      var params = this.setupParams()
      var response = await AuthorService.bibleABSnew(params)
      return response
    },

    async testBibleDBTUpdate() {
      var params = this.setupParams()
      var response = await AuthorService.bibleUpdateDBT(params)
      return response
    },
    async testBibleDBTCheckIndex() {
      var params = this.setupParams()
      var response = await AuthorService.bibleCheckDBTIndex(params)
      return response
    },
    async testBibleDBTCheckDetail() {
      var params = this.setupParams()
      params.language_iso = 'yor'
      var response = await AuthorService.bibleCheckDBTDetail(params)
      return response
    },
    async testBibleLinkMaker() {
      var params = this.setupParams()
      params.recnum = 4129
      var response = await BibleService.bibleLinkMaker(params)
      return response
    },
    async testBibleVerse() {
      var params = this.setupParams()
      params.entry = 'Matthew 1:2-3'
      params.bookId = 'Matt'
      params.chapterId = 1
      params.verseStart = 2
      params.verseEnd = 3
      params.collection_code = 'NT'
      params.bid = '3893' // this is bid
      params.bid = '283'
      var response = await BibleService.getBiblePassage(params)
      return response
    },
    async testBookmarkCountry() {
      var params = this.setupParams()
      params.country_code = 'M2'
      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkLanguage() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkLibrary() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.library_code = 'library'

      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkSeries() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.library_code = 'library'
      params.folder_name = 'multiply1'
      var response = await AuthorService.bookmark(params)
      return response
    },
    async testBookmarkPage() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.library_code = 'library'
      params.folder_name = 'multiply1'
      params.filename = 'multiply104'
      var response = await AuthorService.bookmark(params)
      return response
    },
    async testGetContentFoldersForLanguage() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'fra'
      var response = await AuthorService.getContentFoldersForLanguage(params)
      return response
    },
    async testCountries() {
      var params = this.setupParams()
      var response = await ContentService.getCountries(params)
      return response
    },
    async testBibleVersionsGet() {
      var params = this.setupParams()
      params.language_iso = 'yor'
      params.testament = 'NT'
      var response = await BibleService.getBibleVersions(params)
      return response
    },

    async testImagesGet() {
      var output = ''
      var temp = ''
      var img = await AuthorService.getImagesInContentDirectory(
        'M2/images/standard'
      )
      if (img) {
        var length = img.length
        for (var i = 0; i < length; i++) {
          temp = output + ' ' + img[i]
          output = temp
        }
      }
      return output
    },
    async testGetBooks() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      var response = await SDCardService.getBooks(params)
      return response
    },
    async testGetFooters() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      var response = await SDCardService.getFooters(params)
      return response
    },
    async testGetLanguages() {
      var params = this.setupParams()
      params.country_code = 'M2'
      var response = await SDCardService.getLanguages(params)
      return response
    },
    async testGetPage() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.library_code = 'library'
      params.folder_name = 'multiply1'
      params.filename = 'multiply104'
      params.bookmark = await AuthorService.bookmark(params)
      var response = await AuthorService.getPage(params)
      return response
    },
    async testGetPageOrTemplate() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.library_code = 'library'
      params.folder_name = 'multiply1'
      params.filename = 'multiply104'
      params.bookmark = await AuthorService.bookmark(params)
      var response = await AuthorService.getPageOrTemplate(params)
      return response
    },

    async testLanguages() {
      var params = this.setupParams()
      params.country_code = 'M2'
      var response = await ContentService.getLanguages(params)
      return response
    },
    async testLanguagesGet() {
      var params = this.setupParams()
      params.country_code = 'M2'
      var response = await ContentService.getLanguages(params)
      return response
    },
    async testLanguagesAvailable() {
      var params = this.setupParams()
      var response = await AuthorService.getLanguagesAvailable(params)
      return response
    },
    async testLanguagesForAuthorization() {
      var params = this.setupParams()
      var response = await AuthorService.getLanguagesForAuthorization(params)
      return response
    },
    async testLibrary() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      var response = await ContentService.getLibrary(params)
      return response
    },
    async testLogin() {
      var params = this.setupParams()
      params.password = 'Ruth1987'
      params.username = 'bob'
      var response = await AuthorService.login(params)
      return response
    },
    async testNoJSPage() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.folder_name = 'multiply1'
      params.filename = 'multiply105'
      params.library_code = 'library'
      params.scope = 'page'
      var content = await AuthorService.getPageOrTemplate(params)
      params.recnum = content.recnum
      var response = await NoJSService.publish('page', params)
      return response
    },
    async testPdf() {
      var params = this.setupParams()
      var response = await PDFService.publish('testPdf', params)
      return response
    },

    async testSeries() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.folder_name = 'multiply1'
      var response = await ContentService.getSeries(params)
      return response
    },
    async testSetupCountries() {
      var countries = []
      var country = {}
      country.code = 'NW'
      countries.push(country)
      var response = await AuthorService.setupCountries(countries)
      if (response.data != 'success') {
        alert('setupCountries not successful')
      }
      return response
    },
    async testSetupImageFolder() {
      var params = this.setupParams()
      params.country_code = 'NW'
      params.language_iso = 'yor'
      var response = await AuthorService.setupImageFolder(params)
      return response
    },
    async testSetupLanguageFolder() {
      var params = this.setupParams()
      params.language_iso = 'yor'
      params.country_code = 'M2'
      var response = await AuthorService.setupLanguageFolder(params)
      return response
    },
    async testPrototypePage() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.folder_name = 'multiply1'
      params.filename = 'multiply105'
      params.library_code = 'library'
      params.scope = 'page'
      var content = await AuthorService.getPageOrTemplate(params)
      params.recnum = content.recnum
      var response = await PrototypeService.publish('page', params)
      return response
    },
    async testPublishPage() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.folder_name = 'multiply1'
      params.filename = 'multiply105'
      params.library_code = 'library'
      params.scope = 'page'
      var content = await AuthorService.getPageOrTemplate(params)
      params.recnum = content.recnum
      var response = await PublishService.publish('page', params)
      return response
    },
    async testSDCardPage() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.folder_name = 'multiply1'
      params.filename = 'multiply105'
      params.library_code = 'library'
      params.scope = 'page'
      var content = await AuthorService.getPageOrTemplate(params)
      params.recnum = content.recnum
      var response = await SDCardService.publish('page', params)
      return response
    },
    async testVideoMakeBatFileForSDCard() {
      var params = this.setupParams()
      params.country_code = 'M2'
      params.language_iso = 'eng'
      params.folder_name = 'multiply1'
      var response = await SDCardService.publish(
        'videoMakeBatFileForSDCard',
        params
      )
      return response
    },
    async testVideoConcatBat() {
      var params = this.setupParams()
      var response = await SDCardService.publish('videoConcatBat', params)
      return response
    },
  },
}
</script>
