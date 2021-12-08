import { isNull, isString, isObject, isArray } from 'lodash'

//const show_log = process.env.VUE_APP_SITE_CONSOLE_LOG
const show_log = true
export default {
  consoleLog(line1, line2, line3) {
    if (show_log == true) {
      console.log(line1)
      if (typeof line2 !== 'undefined') {
        console.log(line2)
      }
      if (typeof line3 !== 'undefined') {
        console.log(line3)
      }
    }
  },
  consoleLogMessage(message) {
    if (show_log == true) {
      console.log(message)
    }
  },
  consoleLogError(message, error, source) {
    console.log(message)
    console.log(error)
    var text = null
    var text1 = null
    var text2 = null
    if (isArray(error)) {
      var len = error.length
      for (var i = 0; i < len; i++) {
        text1 = error[i]
        text2 = text + text1
        text = text2
      }
    }
    if (isString(error)) {
      text = error
    }
    if (isNull(error)) {
      text = 'No reason given'
    }
    if (isObject(error)) {
      text = JSON.stringify(error)
    }

    var output = source + ':  ' + message + '  ' + text
    alert(output)
  }
}
