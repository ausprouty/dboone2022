var CACHE_DYNAMIC_NAME = 'content-1'

// Initialize deferredPrompt for use later to show browser install prompt.
let deferredPrompt
// from https://web.dev/customize-install/
window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent the mini-infobar from appearing on mobile
  e.preventDefault()
  // Stash the event so it can be triggered later.
  deferredPrompt = e
  // Update UI notify the user they can install the PWA
  homescreenPromptShow()
  // Optionally, send analytics event that PWA install promo was shown.
  console.log(`'beforeinstallprompt' event was fired.`)
})
addToHomeScreenButton.addEventListener('click', async () => {
  // Hide the app provided install promotion
  homescreenPromptHide()
  // Show the install prompt
  deferredPrompt.prompt()
  // Wait for the user to respond to the prompt
  const { outcome } = await deferredPrompt.userChoice
  // Optionally, send analytics event with outcome of user choice
  console.log(`User response to the install prompt: ${outcome}`)
  // We've used the prompt, and can't use it again, throw it away
  deferredPrompt = null
})

window.addEventListener('appinstalled', (event) => {
  console.log('👍', 'appinstalled', event)
  // Clear the deferredPrompt so it can be garbage collected
  window.deferredPrompt = null
})

document.addEventListener('DOMContentLoaded', offlineRequestCheck)
document.addEventListener('DOMContentLoaded', homescreenCheck)

function offlineRequestCheck() {
  var series = document.getElementById('offline-request')
  console.log(series)
  if (series !== null) {
    offlineSeriesCheck(series.dataset.json)
  }
}
function offlineSeriesCheck(series) {
  console.log(series + ' series is being checked')
  // set ios prompt if needed
  //https://www.netguru.co/codestories/few-tips-that-will-make-your-pwa-on-ios-feel-like-native

  if (navigator.onLine) {
    console.log('I am ONline')
    var swWorking = localStorage.getItem('swWorking')
    if ('serviceWorker' in navigator && swWorking == 'TRUE') {
      console.log('I have a service worker')
      inLocalStorage('offline', series).then(function (result) {
        console.log(result + ' is value')
        var link = ''
        if (result == '') {
          console.log(series + ' not offline')
          link = document.getElementById('offline-request')
          link.style.visibility = 'visible'
        } else {
          link = document.getElementById('offline-ready')
          link.style.visibility = 'visible'
        }
      })
    } else {
      console.log('I do NOT have a service worker')
      var link = document.getElementById('offline-request')
      link.style.display = 'none'
      //var link = document.getElementById('offline-already');
      //link.style.display = 'none';
    }
  } else {
    console.log('I am offline')
    offlineItemsHide()
  }
}
function datediff(first, second) {
  //  Take the difference between the dates and divide by milliseconds per day.
  //  Round to nearest whole number to deal with DST.
  return Math.round((second - first) / (1000 * 60 * 60 * 24))
}
function homescreenCheck() {
  var lastPrompt = localStorage.lastSeenPrompt
  if (typeof lastPrompt == 'undefined') {
    homescreenPromptShow()
  }
}
function homescreenPromptHide() {
  var dlg = document.getElementById('addToHomeScreen')
  dlg.classList.remove('xhidden')
  dlg.classList.add('hidden')
}
function homescreenPromptShow() {
  let today = Date.now()
  //var lastPrompt = localStorage.lastSeenPrompt
  //let days = Math.round((today - lastPrompt) / (1000 * 60 * 60 * 24))
  //if (isNaN(days) || days > SHOW_PROMPT_EVERY_X_DAYS){
  localStorage.setItem('lastSeenPrompt', today)
  var dlg = document.getElementById('addToHomeScreen')
  dlg.classList.remove('hidden')
  dlg.classList.add('xhidden')
  //}
}
function closeScreen() {
  var screen = document.getElementById('addToHomeScreen')
  screen.remove()
  return false
}
// get value of variable in array
// is id in key?
function inLocalStorage(key, id) {
  var deferred = $.Deferred()
  var result = ''
  console.log('looking offline for local storage')
  var key_value = localStorage.getItem(key)
  if (typeof key_value != 'undefined' && key_value) {
    key_value = JSON.parse(key_value)
    console.log(key_value)
    key_value.forEach(function (array_value) {
      console.log(array_value + '  array value')
      console.log(id + '  id')
      if (array_value == id) {
        console.log('stored locally')
        result = id
      }
    })
    console.log(result)
  } else {
    result = ''
    console.log('not stored locally')
  }
  deferred.resolve(result)
  return deferred.promise()
}

function offlineItemsHide() {
  // get rid of all readmore comments
  var readmore = document.getElementsByClassName('readmore')
  if (readmore.length > 0) {
    console.log('I found readmore')
    for (var i = 0; i < readmore.length; i++) {
      readmore[i].style.display = 'none'
    }
  }
  readmore = document.getElementsByClassName('bible_readmore')
  if (readmore.length > 0) {
    console.log('I found bible_readmore')
    for (var i = 0; i < readmore.length; i++) {
      readmore[i].style.display = 'none'
    }
  }
  // hide external-link
  var links = document.getElementsByClassName('external-link')
  if (links.length > 0) {
    console.log('I found external-link')
    for (var i = 0; i < links.length; i++) {
      links[i].style.className = 'unlink'
    }
  }
  // hide external-movie
  links = document.getElementsByClassName('external-movie')
  if (links.length > 0) {
    console.log('I found external-link')
    for (var i = 0; i < links.length; i++) {
      links[i].style.display = 'none'
    }
  }
}

// this stores series for offline use
// https://developers.google.com/web/ilt/pwa/caching-files-with-service-worker
var el = document.getElementById('offline-request')
if (el) {
  document
    .getElementById('offline-request')
    .addEventListener('click', function (event) {
      event.preventDefault()
      console.log('button pressed')
      var id = this.dataset.json
      var ajaxPromise = fetch(id)
        .then(function (response) {
          //get-series-urls returns a JSON-encoded array of
          // resource URLs that a given series depends on
          return response.json()
        })
        .then(function (jsonFile) {
          jsonFile.forEach(function (element) {
            console.log(element.url)
            caches.open(CACHE_DYNAMIC_NAME).then(function (cache) {
              cache.add(element.url)
            })
          })
        })
        .then(function () {
          // store that series is available for offline use
          console.log(id + ' Series ready for offline use')
          var offline = []
          var already
          if (
            typeof localStorage.offline != 'undefined' &&
            localStorage.offline
          ) {
            offline = JSON.parse(localStorage.offline) //get existing values
          }
          offline.forEach(function (array_value) {
            if (array_value == id) {
              console.log('stored locally')
              already = 'Y'
            }
          })
          console.log(already + ' is already')
          if (already != 'Y') {
            offline.push(id)
            console.log(offline)
          }
          localStorage.setItem('offline', JSON.stringify(offline)) //put the object back
          var ready = document.getElementById('offline-ready').innerHTML
          document.getElementById('offline-request').innerHTML = ready
          document.getElementById('offline-request').style.background =
            '#00693E'
        })
        .catch(function (err) {
          console.log(err)
        })
    })
}
