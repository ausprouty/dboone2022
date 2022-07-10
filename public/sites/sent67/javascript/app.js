var CACHE_DYNAMIC_NAME = 'content-1'
var SHOW_PROMPT_EVERY_X_DAYS = 14
var DEFAULT_ENTRY = '/content/index.html'

if ('serviceWorker' in navigator) {
  navigator.serviceWorker
    .register('/sw.js')
    .then(function () {
      console.log('Service worker registered!')
      localStorage.setItem('swWorking', 'TRUE')
    })
    .catch(function (err) {
      console.log(err)
      localStorage.setItem('swWorking', 'FALSE')
    })
}

// return to last page if restarting
// check for current dynamic

function router() {
  // check if dynamic cache needs updating
  if (CACHE_DYNAMIC_NAME != localStorage.getItem('dynamic-cache')) {
    restoreDynamic()
    localStorage.setItem('dynamic-cache', CACHE_DYNAMIC_NAME)
  }
  // store last page so that root index can redirect when you come again
  var currentPage = window.location.pathname
  localStorage.setItem('lastpage', window.location.href)
  //
}
document.addEventListener('DOMContentLoaded', router)

// check to see if this is an index file for a series and get value index.json
document.addEventListener('DOMContentLoaded', (event) => {
  // restore revealed areas
  console.log ('app.onload')
  appRevealedRestore()
  var series = document.getElementById('offline-request')
  if (series !== null) {
    checkOfflineSeries(series.dataset.json)
  }
  findCollapsible()

  if (localStorage.getItem('mc2Trainer')) {
    // unhide all trainer notes
    var elements = document.getElementsByClassName('trainer-hide')
    for (var i = 0; i < elements.length; i++) {
      elements[i].className = 'trainer'
    }
    // unhide all items which are collapsed for students
    elements = document.getElementsByClassName('collapsible')
    for (var i = 0; i < elements.length; i++) {
      elements[i].className = 'revealed'
    }
    elements = document.getElementsByClassName('collapsed')
    for (var i = 0; i < elements.length; i++) {
      elements[i].style.display = 'block'
    }
  }
  if (!navigator.onLine) {
    console.log('I am offline')
    hideWhenOffline()
  }
})

// start of Show Install prompt
// Initialize deferredPrompt for use later to show browser install prompt.
let deferredPrompt
// from https://web.dev/customize-install/
window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent the mini-infobar from appearing on mobile
  e.preventDefault()
  // Stash the event so it can be triggered later.
  deferredPrompt = e
  // Update UI notify the user they can install the PWA
  showHomescreenPrompt()
  // Optionally, send analytics event that PWA install promo was shown.
  console.log(`'beforeinstallprompt' event was fired.`)
})
addToHomeScreenButton.addEventListener('click', async () => {
  // Hide the app provided install promotion
  hideHomescreenPrompt()
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

// end of Show Install prompt

function checkOfflineSeries(series) {
  console.log(series + ' series is being checked')
  if (navigator.onLine) {
    console.log('I am ONline')
    var swWorking = localStorage.getItem('swWorking')
    if ('serviceWorker' in navigator && swWorking == 'TRUE') {
      console.log('I have a service worker')
      inLocalStorage('offline', series).then(function (result) {
        console.log(result + ' is value')
        if (result == '') {
          console.log(series + ' not offline')
          var link = document.getElementById('offline-request')
          link.style.visibility = 'visible'
        } else {
          var link = document.getElementById('offline-ready')
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
    hideWhenOffline()
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
function closeScreen() {
  var screen = document.getElementById('addToHomeScreen')
  screen.remove()
  return false
}
function findCollapsible() {
  console.log ('findCollapsible')
  var coll = document.getElementsByClassName('collapsible')
  var i
  for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener('click', function () {
      this.classList.toggle('active')
      var content = this.nextElementSibling
      if (content.style.display === 'block') {
        content.style.display = 'none'
        //this.className= "collapsible";
        this.classList.remove('revealed')
        this.classList.add('collapsible')
      } else {
        content.style.display = 'block'
        //this.className= "revealed";
        this.classList.remove('collapsible')
        this.classList.add('revealed')
      }
    })
  }
}

function appRevealSummary(id) {
  var windowLocation = appRevealWindowLocation()
  var button = document.getElementById('Summary' + id)

  var content = button.nextElementSibling
  if (content.style.display === 'block') {
    // we save this in case we need to goToPageAndSetReturn;
    appRevealSummaryDelete(windowLocation, id)
    content.style.display = 'none'
    button.classList.remove('summary-shown')
    button.classList.add('summary-hidden')
  } else {
    // we save this in case we need to goToPageAndSetReturn;
    appRevealSummaryAdd(windowLocation, id)
    content.style.display = 'block'
    button.classList.remove('summary-hidden')
    button.classList.add('summary-shown')
  }
  var text = button.innerHTML
  if (text.includes('+')) {
    var new_text = text.replace('+', '-')
  } else {
    var new_text = text.replace('-', '+')
  }
  button.innerHTML = new_text
}
function appRevealSummaryAdd(windowLocation, id) {
  var current = appRevealSummaryRetreive(windowLocation)
  if (current) {
    appRevealSummaryClose(windowLocation, current)
  }
  current = id
  appRevealSummarySave(windowLocation, current)
}

function appRevealSummaryClose(windowLocation, id) {
  if (!document.getElementById('Summary' + id)) {
    return
  }
  var button = document.getElementById('Summary' + id)
  //button.classList.toggle("active");
  var text = button.innerHTML
  if (text.includes('-')) {
    var new_text = text.replace('-', '+')
    button.innerHTML = new_text
  }
  var content = button.nextElementSibling
  if (content.style.display === 'block') {
    content.style.display = 'none'
    button.classList.remove('summary-shown')
    button.classList.add('summary-hidden')
  }
}

function appRevealSummaryDelete(windowLocation, id) {
  //var current = appRevealSummaryRetreive(windowLocation);
  //for( var i = 0; i < current.length; i++){
  //    if ( current[i] === id) {
  //        current.splice(i, 1);
  //    }
  //}
  var current = null
  appRevealSummarySave(windowLocation, current)
}

function appRevealSummaryRetreive(windowLocation) {
  if (window.localStorage.getItem('sent67SummaryRevealed')) {
    var current = JSON.parse(
      window.localStorage.getItem('sent67SummaryRevealed')
    )
    if (current.page == windowLocation) {
      return current.revealed
    }
  }
  //var blank = []
  var blank = null
  return blank
}
function appRevealSummarySave(windowLocation, current) {
  var record = {}
  record.page = windowLocation
  record.revealed = current
  window.localStorage.setItem('sent67SummaryRevealed', JSON.stringify(record))
}
function appRevealWindowLocation() {
  var windowLocation = window.location.href
  if (windowLocation.includes('#')) {
    windowLocation = windowLocation.split('#')[0]
  }
  return windowLocation
}
function appRevealedRestore() {
  var windowLocation = appRevealWindowLocation()
  var current = appRevealSummaryRetreive(windowLocation)
  if (current) {
    appRevealSummary(current)
  }
}

function goToPageAndSetReturn(page, anchor = null) {
  // If you are in Lesson 1 and want a person to go to Lesson 7,
  // The return button will now bring them back
  // rather than take them to the index.
  // DANGER:  If window.location.href contains '#' you must remove it
  // This is because the person has already returned once.
  var windowLocation = appRevealWindowLocation()
  var returnLocation = windowLocation + anchor
  localStorage.setItem('returnpage', returnLocation)
  // save revealed for return
  if (localStorage.getItem('sent67SummaryRevealed')) {
    var last = localStorage.getItem('sent67SummaryRevealed')
    localStorage.setItem('sent67SummaryRevealedSaved', last)
  }
  window.location.replace(page)
}
function hideWhenOffline() {
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
function inLocalStorage(key, id) {
  // get value of variable in array
  // is id in key?
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
function pageGoBack(page) {
  if (localStorage.getItem('returnpage')) {
    page = localStorage.getItem('returnpage')
    localStorage.removeItem('returnpage')
  }
  if (localStorage.getItem('sent67SummaryRevealedSaved')) {
    var last = localStorage.getItem('sent67SummaryRevealedSaved')
    localStorage.setItem('sent67SummaryRevealed', last)
  }
  window.location.replace(page)
}
function popUp(field) {
  var content = document.getElementById(field)
  if (content.style.display === 'block') {
    content.style.display = 'none'
    this.classList.remove('revealed')
    this.classList.add('collapsible')
  } else {
    content.style.display = 'block'
    //this.className= "revealed";
    this.classList.remove('collapsible')
    this.classList.add('revealed')
  }
}
function restoreDynamic() {
  if (typeof localStorage.offline != 'undefined' && localStorage.offline) {
    console.log('restoreDynamic')
    var offline = JSON.parse(localStorage.offline) //get existing values
    offline.forEach(function (series) {
      var ajaxPromise = fetch(series)
        .then(function (response) {
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
    })
  }
}
function showHomescreenPrompt() {
  let today = Date.now()
  var lastPrompt = localStorage.lastSeenPrompt
  let days = Math.round((today - lastPrompt) / (1000 * 60 * 60 * 24))
  if (isNaN(days) || days > SHOW_PROMPT_EVERY_X_DAYS) {
    localStorage.setItem('lastSeenPrompt', today)
    var dlg = document.getElementById('addToHomeScreen')
    dlg.classList.remove('hidden')
    dlg.classList.add('xhidden')
  }
}
function hideHomescreenPrompt() {
  var dlg = document.getElementById('addToHomeScreen')
  dlg.classList.remove('xhidden')
  dlg.classList.add('hidden')
}
function showDialog(message) {
  //var whitebg = document.getElementById('white-background')
  var dlg = document.getElementById('dlgbox')
  //	 whitebg.style.display = "block";
  dlg.style.display = 'block'

  //var winWidth = window.innerWidth
  //var winHeight = window.innerHeight
  // dlg.style.left = (winWidth/2) - 480/2 + "px";
  dlg.style.top = '150px'
}

// for sharing
//https://developers.google.com/web/updates/2016/09/navigator-share
