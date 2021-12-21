import axios from 'axios'
import LogService from '@/services/LogService.js'

const apiURL = process.env.VUE_APP_DEFAULT_SITES_URL
const apiSite = process.env.VUE_APP_SITE
const apiLocation = process.env.VUE_APP_SITE_LOCATION
const postContentDestination =
  'ContentApi.php?site=' + apiSite + '&location=' + apiLocation

const apiCONTENT = axios.create({
  baseURL: apiURL,
  withCredentials: false, // This is the default
  crossDomain: true,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})
// I want to export a JSON.stringified of response.data.text
export default {
  async getCountries(params) {
    params.scope = 'countries'
    var res = await this.returnContent(params)
    LogService.consoleLog('getCountries', res)
    return res
  },
  async getLanguages(params) {
    params.scope = 'languages'
    var res = await this.returnContent(params)

    return res
  },
  async getLibrary(params) {
    params.scope = 'library'
    console.log('in getLibrary in Content Service with params')
    console.log(params)
    var res = await this.returnContent(params)
    console.log(res)
    return res
  },
  async getLibraryIndex(params) {
    params.scope = 'libraryIndex'
    var res = await this.returnContent(params)
    return res
  },
  async getSeries(params) {
    params.scope = 'series'
    return await this.returnContent(params)
  },
  async getPage(params) {
    params.scope = 'page'
    return await this.returnContent(params)
  },
  async returnContent(params) {
    try {
      var content = {}
      var contentForm = this.toFormData(params)
      var response = await apiCONTENT.post(postContentDestination, contentForm)
      LogService.consoleLog('returnContent', response)
      params.function = 'returnContent'
      if (response.data) {
        content = response.data
      }
      return content
    } catch (error) {
      this.error = error.toString() + ' ' + params.action
      console.log(this.error)
      console.log(params)
      return 'error'
    }
  },
  //TODO: write something intellegent here  ZX
  validate(entry) {
    var clean = entry

    return clean
  },

  toFormData(params) {
    params.site = apiSite
    var form_data = new FormData()
    for (var key in params) {
      form_data.append(key, params[key])
    }
    // Display the key/value pairs
    //for (var pair of form_data.entries()) {
    //  console.log(params, pair[0] + ', ' + pair[1])
    // }
    return form_data
  },
}
