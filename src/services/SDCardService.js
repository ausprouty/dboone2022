const apiURL = process.env.VUE_APP_DEFAULT_SITES_URL
const apiSite = process.env.VUE_APP_SITE
const apiLocation = process.env.VUE_APP_SITE_LOCATION

const postDestination =
  'AuthorApi.php?site=' + apiSite + '&location=' + apiLocation

const apiSECURE = axios.create({
  baseURL: apiURL,
  withCredentials: false, // This is the default
  crossDomain: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})
const apiDOWNLOAD = axios.create({
  baseURL: apiURL,
  withCredentials: false, // This is the default
  crossDomain: true,
  headers: {
    responseType: 'blob',
    'Content-Type': 'application/json',
  },
})
import axios from 'axios'
import store from '@/store/store.js'
import AuthorService from '@/services/AuthorService.js'
import { parseXML } from 'jquery'

// I want to export a JSON.stringified of response.data.text
export default {
  initialize(params) {
    params.site = process.env.VUE_APP_SITE
    params.my_uid = store.state.user.uid
    params.sdcard = JSON.stringify(store.state.sdCardSettings)
    params.subdirectory = 'sdcard'
    params.token = store.state.user.token
    if (typeof params.destination == 'undefined') {
      params.destination = 'sdcard'
    }
    return params
  },
  async checkStatusBook(params) {
    params = this.initialize(params)
    params.page = 'checkStatusBook'
    params.action = 'checkStatusBook'
    return await AuthorService.aReturnContent(params)
  },
  // see https://morioh.com/p/f4d331b62cda
  async zipMediaBatFiles(params) {
    params = this.initialize(params)
    params.page = 'zipMediaBatFiles'
    params.action = 'zipMediaBatFiles'
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
  async verifyBookNoJS(params) {
    params = this.initialize(params)
    params.page = 'verifyBook'
    params.action = 'verifyBookNoJS'
    return await AuthorService.aReturnContent(params)
  },
  async verifyBookPDF(params) {
    params = this.initialize(params)
    params.page = 'verifyBook'
    params.action = 'verifyBookPDF'
    return await AuthorService.aReturnContent(params)
  },
  async verifyBookSDCard(params) {
    params = this.initialize(params)
    params.page = 'verifyBook'
    params.action = 'verifyBookSDCard'
    return await AuthorService.aReturnContent(params)
  },
  async verifyBookVideoList(params) {
    params = this.initialize(params)
    params.page = 'verifyBook'
    params.action = 'verifyBookVideoList'
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
      case 'videoMakeBatFileForSDCard':
        action =
          'AuthorApi.php?page=videoMakeBatFileForSDCard&action=videoMakeBatFileForSDCard'
        break
      case 'videoConcatBat':
        action = 'AuthorApi.php?page=videoConcatBat&action=videoConcatBat'
        break
      case 'media':
        action = 'AuthorApi.php?page=copyBookMedia&action=copyBookMedia'
        break
      case 'cover':
        action = 'AuthorApi.php?page=copySDCardCover&action=copySDCardCover'
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
  toFormData(obj) {
    var form_data = new FormData()
    for (var key in obj) {
      form_data.append(key, obj[key])
    }
    return form_data
  },
}
