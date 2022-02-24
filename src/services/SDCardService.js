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
  getBooks(params) {
    params = this.initialize(params)
    params.page = 'getBooksForLanguage'
    params.action = 'getBooksForLanguage'
    return AuthorService.aReturnContent(params)
  },
  getFooters(params) {
    params = this.initialize(params)
    params.page = 'getFooters'
    params.action = 'getFooters'
    return AuthorService.aReturnContent(params)
  },
  getLanguages(params) {
    params.page = 'getLanguagesAvailable'
    params.action = 'getLanguagesAvailable'
    return AuthorService.aReturnContent(params)
  },
  initialize(params) {
    params.site = process.env.VUE_APP_SITE
    params.location = process.env.VUE_APP_LOCATION
    params.my_uid = store.state.user.uid
    params.token = store.state.user.token
    params.destination = 'sdcard'
    return params
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
