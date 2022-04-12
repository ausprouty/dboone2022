const apiURL = process.env.VUE_APP_DEFAULT_SITES_URL
const apiSite = process.env.VUE_APP_SITE
const apiLocation = process.env.VUE_APP_SITE_LOCATION

const apiSECURE = axios.create({
  baseURL: apiURL,
  withCredentials: false, // This is the default
  crossDomain: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})
import axios from 'axios'
import store from '@/store/store.js'
import AuthorService from '@/services/AuthorService.js'
// I want to export a JSON.stringified of response.data.text
export default {
  initialize(params) {
    if (typeof params.apk_settings != 'undefined') {
      if (typeof params.apk_settings === 'string') {
        var temp = JSON.parse(params.apk_settings)
        params.apk_settings = temp
      }
      if (typeof params.apk_settings.language != 'undefined') {
        params.country_code = params.apk_settings.language.country_code
        params.language_iso = params.apk_settings.language.language_iso
      }
      var temp1 = JSON.stringify(params.apk_settings)
      params.apk_settings = temp1
    }
    params.site = process.env.VUE_APP_SITE
    params.my_uid = store.state.user.uid
    params.subdirectory = 'apk'
    params.token = store.state.user.token
    if (typeof params.destination == 'undefined') {
      params.destination = 'apk'
    }
    console.log(params)
    return params
  },
  async checkCommonFiles(params) {
    params = this.initialize(params)
    params.page = 'checkCommonFiles'
    params.action = 'checkCommonFiles'
    var res = await AuthorService.aReturnContent(params)
    return res
  },
  async checkContentIndex(params) {
    params = this.initialize(params)
    params.page = 'checkContentIndex'
    params.action = 'checkContentIndex'
    var res = await AuthorService.aReturnContent(params)
    return res
  },
  async checkStatusBook(params) {
    params = this.initialize(params)
    params.page = 'checkStatusBook'
    params.action = 'checkStatusBook'
    return await AuthorService.aReturnContent(params)
  },
  async getBuilds(params) {
    params = this.initialize(params)
    params.page = 'getBuilds'
    params.action = 'getBuilds'
    return await AuthorService.aReturnContent(params)
  },
  async getBooks(params) {
    params = this.initialize(params)
    params.page = 'getBooksForLanguage'
    params.action = 'getBooksForLanguage'
    return await AuthorService.aReturnContent(params)
  },
  async getFooters(params) {
    params = this.initialize(params)
    params.page = 'getFooters'
    params.action = 'getFooters'
    return await AuthorService.aReturnContent(params)
  },
  async getLanguages(params) {
    params = this.initialize(params)
    params.page = 'getLanguagesAvailable'
    params.action = 'getLanguagesAvailable'
    return await AuthorService.aReturnContent(params)
  },
  async publish(scope, params) {
    var action = null
    params = this.initialize(params)
    switch (scope) {
      case 'country':
        action = 'AuthorApi.php?page=publishCountry&action=publishCountry'
        break
      case 'language':
        action = 'AuthorApi.php?page=publish&action=publishLanguage'
        break
      case 'libraries':
        action = 'AuthorApi.php?page=publishLibraries&action=publishLibraries'
        break
      case 'library':
        action = 'AuthorApi.php?page=publishLibrary&action=publishLibrary'
        break
      case 'libraryAndBooks':
        action =
          'AuthorApi.php?page=publishLibraryAndBooks&action=publishLibraryAndBooks'
        break
      case 'libraryIndex':
        action =
          'AuthorApi.php?page=publishLibraryIndex&action=publishLibraryIndex'
        break
      case 'series':
        action = 'AuthorApi.php?page=publishSeries&action=publishSeries'
        break
      case 'seriesAndChapters':
        action =
          'AuthorApi.php?page=publishSeriesAndChapters&action=publishSeriesAndChapters'
        break
      case 'page':
        action = 'AuthorApi.php?page=publishPage&action=publishPage'
        break
      case 'videoMakeBatFileForApk':
        action =
          'AuthorApi.php?page=videoMakeBatFileForApk&action=videoMakeBatFileForApk'
        break
      case 'videoConcatBat':
        action = 'AuthorApi.php?page=videoConcatBat&action=videoConcatBat'
        break
      case 'media':
        action = 'AuthorApi.php?page=copyBookMedia&action=copyBookMedia'
        break
      case 'cover':
        action = 'AuthorApi.php?page=copyApkCover&action=copyApkCover'
        break

      case 'default':
        action = null
    }
    var complete_action =
      action + '&site=' + apiSite + '&location=' + apiLocation
    var contentForm = this.toFormData(params)
    var response = await apiSECURE.post(complete_action, contentForm)
    return response
  },
  async verifyBookApk(params) {
    params = this.initialize(params)
    params.page = 'verifyBook'
    params.action = 'verifyBookApk'
    return await AuthorService.aReturnContent(params)
  },
  async verifyBookCover(params) {
    params = this.initialize(params)
    params.page = 'verifyBook'
    params.action = 'verifyBookCover'
    return await AuthorService.aReturnContent(params)
  },
  async verifyBookMedia(params) {
    params = this.initialize(params)
    params.page = 'verifyBook'
    params.action = 'verifyBookMedia'
    return await AuthorService.aReturnContent(params)
  },
  async verifyBookVideoList(params) {
    params = this.initialize(params)
    params.page = 'verifyBook'
    params.action = 'verifyBookVideoList'
    return await AuthorService.aReturnContent(params)
  },
  async verifyCommonFiles(params) {
    params = this.initialize(params)
    params.page = 'verifyCommonFiles'
    params.action = 'verifyCommonFiles'
    var res = await AuthorService.aReturnContent(params)
    return res
  },
  async verifyLanguageIndex(params) {
    params = this.initialize(params)
    params.page = 'verifyLanguageIndex'
    params.action = 'verifyLanguageIndex'
    return await AuthorService.aReturnContent(params)
  },
  async verifyContentIndex(params) {
    params = this.initialize(params)
    params.page = 'verifyContentIndex'
    params.action = 'verifyContentIndex'
    return await AuthorService.aReturnContent(params)
  },
  // see https://morioh.com/p/f4d331b62cda
  async zipMediaBatFiles(params) {
    params = this.initialize(params)
    params.page = 'zipMediaBatFiles'
    params.action = 'zipMediaBatFiles'
    return await AuthorService.aReturnContent(params)
  },

  toFormData(obj) {
    var form_data = new FormData()
    for (var key in obj) {
      form_data.append(key, obj[key])
    }
    return form_data
  },
}
