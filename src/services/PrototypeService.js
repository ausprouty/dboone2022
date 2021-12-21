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
  async prototype(scope, params) {
    var action = null
    params.site = process.env.VUE_APP_SITE
    params.location = process.env.VUE_APP_LOCATION
    params.my_uid = store.state.user.uid
    params.token = store.state.user.token
    params.destination = 'prototype'
    // params.bookmark = JSON.stringify(store.state.bookmark)

    //LogService.consoleLogMessage('publish')
    //LogService.consoleLogMessage(params)
    switch (scope) {
      case 'bookmark':
        action = 'AuthorApi.php?page=bookmark&action=bookmark'
        break
      case 'countries':
        action = 'AuthorApi.php?page=publishCountries&action=publishCountries'
        break
      case 'country':
        action = 'AuthorApi.php?page=publishCountry&action=publishCountry'
        break
      case 'language':
        action = 'AuthorApi.php?page=publish&action=publishLanguage'
        break
      case 'languages':
        action = 'AuthorApi.php?page=publishLanguages&action=publishLanguages'
        break
      case 'languagesAvailable':
        action =
          'AuthorApi.php?page=publishLanguagesAvailable&action=publishLanguagesAvailable'
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
      case 'readyTopublishCountry':
        action =
          'AuthorApi.php?page=readyTopublish&action=readyTopublishCountry'
        break
      case 'readyTopublishLanguage':
        action =
          'AuthorApi.php?page=readyTopublish&action=readyTopublishLanguage'
        break
      case 'readyTopublishLibrary':
        action =
          'AuthorApi.php?page=readyTopublish&action=readyTopublishLibrary'
        break
      case 'readyTopublishSeries':
        action = 'AuthorApi.php?page=readyTopublish&action=readyTopublishSeries'
        break
      case 'default':
        action = null
    }
    try {
      var content = []
      var complete_action =
        action + '&site=' + apiSite + '&location=' + apiLocation
      var contentForm = this.toFormData(params)
      var response = await apiSECURE.post(complete_action, contentForm)
      console.log(response)
      if (response.data.content) {
        content = response.data.content
      }
      return content
    } catch (error) {
      this.error = error.toString() + ' ' + action
      console.log(this.error)
      console.log(action)
      return 'error'
    }
  },

  toFormData(obj) {
    var form_data = new FormData()
    for (var key in obj) {
      form_data.append(key, obj[key])
    }
    // Display the key/value pairs
    //for (var pair of form_data.entries()) {
    //  LogService.consoleLogMessage(pair[0] + ', ' + pair[1])
    // }
    //LogService.consoleLogMessage(form_data)
    return form_data
  },
}
