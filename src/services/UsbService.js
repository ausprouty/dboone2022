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

// I want to export a JSON.stringified of response.data.content.text
export default {
  async publish(scope, params) {
    var action = null
    params.my_uid = store.state.user.uid
    params.token = store.state.user.token
    // params.bookmark = JSON.stringify(store.state.bookmark)

    // LogService.consoleLogMessage('publish')
    // LogService.consoleLogMessage(params)
    switch (scope) {
      case 'bookmark':
        action = 'AuthorApi.php?page=bookmarkToUSB&action=bookmark'
        break
      case 'countries':
        action =
          'AuthorApi.php?page=publishCountriesToUSB&action=publishCountries'
        break
      case 'country':
        action = 'AuthorApi.php?page=publishCountryToUSB&action=publishCountry'
        break
      case 'language':
        action = 'AuthorApi.php?page=publishToUSB&action=publishLanguage'
        break
      case 'languages':
        action =
          'AuthorApi.php?page=publishLanguagesToUSB&action=publishLanguages'
        break
      case 'languagesAvailable':
        action =
          'AuthorApi.php?page=publishLanguagesAvailableToUSB&action=publishLanguagesAvailable'
        break
      case 'library':
        action = 'AuthorApi.php?page=publishLibraryToUSB&action=publishLibrary'
        break
      case 'libraryAndBooks':
        action =
          'AuthorApi.php?page=publishLibraryAndBooksToUSB&action=publishLibraryAndBooks'
        break
      case 'libraryIndex':
        action =
          'AuthorApi.php?page=publishLibraryIndexToUSB&action=publishLibraryIndex'
        break
      case 'series':
        action = 'AuthorApi.php?page=publishSeriesToUSB&action=publishSeries'
        break
      case 'seriesAndChapters':
        action =
          'AuthorApi.php?page=publishSeriesAndChaptersToUSB&action=publishSeriesAndChapters'
        break
      case 'page':
        action =
          'AuthorApi.php?page=publishPageToUSBToUSB&action=publishPageToUSB'
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
    // Display the key/value pairs
    // for (var pair of form_data.entries()) {
    //  LogService.consoleLogMessage(pair[0] + ', ' + pair[1])
    //}
    //   LogService.consoleLogMessage(form_data)
    return form_data
  },
}
