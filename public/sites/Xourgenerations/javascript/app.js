var CACHE_DYNAMIC_NAME = 'content-1'
var SHOW_PROMPT_EVERY_X_DAYS = 14
var DEFAULT_ENTRY = '/content/index.html'
Storage
//consider https://requirejs.org/docs/jquery.html
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
  // which page should we go to?
  var currentPage = window.location.pathname
  // go to last page visited if you are visiting root page again
  var lastpage = ''
  if (localStorage.getItem('lastpage') && currentPage == '/') {
    lastpage = localStorage.getItem('lastpage')
    localStorage.removeItem('lastpage')
    window.location.replace(lastpage)
  } else {
    // stay here if you entered into any other page than the root,
    if (currentPage !== '/') {
      lastpage = localStorage.getItem('lastpage')
      localStorage.setItem('previouspage', lastpage)
      localStorage.setItem('lastpage', window.location.href)
    }
    // otherwise guess the best language for this browser,
    else {
      if ('bob' != 'genious') {
        var home = DEFAULT_ENTRY
        window.location.replace(home)
        return
      }
      var ajaxPromise = fetch('/browserlanguage.php')
        .then(function (response) {
          console.log(response.json)
          return response.json()
        })
        .then(function (jsonFile) {
          var pref = jsonFile.preference
          var codes = jsonFile.languageCodes
          codes.forEach(function (row) {
            if (row.code == pref) {
              var start = row.start
              window.location.replace(start)
            }
          })
        })
        .catch(function (err) {
          console.log('Do not know browser language')
          console.log(err)
        })
    }
    // see which index we should be at
    //findIndexPage();
  }
}
document.addEventListener('DOMContentLoaded', router)

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
// check to see if this is an index file for a series and get value index.json
window.onload = function () {
  //if (!navigator.standalone) {
  // showHomescreenPrompt();
  //}
  var series = document.getElementById('offline-request')
  if (series !== null) {
    checkOfflineSeries(series.dataset.json)
  }
  var notes_page = document.getElementById('notes_page')
  if (notes_page !== null) {
    var notes = notes_page.value
    console.log(notes)
    showNotes(notes)
  }
  findCollapsible()
  videoDecideWhichVideosToShow()

  if (localStorage.getItem('mc2Trainer')) {
    // unhide all trainer notes
    var elements = document.getElementsByClassName('trainer-hide')
    for (var i = 0; i < elements.length; i++) {
      elements[i].className = 'trainer'
    }
    // unhide all items which are collapsed for students
    elements = document.getElementsByClassName('collapsible')
    for (i = 0; i < elements.length; i++) {
      elements[i].className = 'revealed'
    }
    elements = document.getElementsByClassName('collapsed')
    for (i = 0; i < elements.length; i++) {
      elements[i].style.display = 'block'
    }
  }
  if (!navigator.onLine) {
    console.log('I am offline')
    hideWhenOffline()
  }
}

function checkOfflineSeries(series) {
  console.log(series + ' series is being checked')
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
function findSummaries() {
  alert('I am in findSummaries')
}
function goToPageAndSetReturn(page) {
  // If you are in Lesson 1 and want a person to go to Lesson 7,
  // The return button will now bring them back
  // rather than take them to the index.
  localStorage.setItem('returnpage', window.location.href)
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
  // page is set in the nav bar of each web page
  if (localStorage.getItem('returnpage')) {
    page = localStorage.getItem('returnpage')
    localStorage.removeItem('returnpage')
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
  //if (isNaN(days) || days > SHOW_PROMPT_EVERY_X_DAYS){
  localStorage.setItem('lastSeenPrompt', today)
  var dlg = document.getElementById('addToHomeScreen')
  dlg.classList.remove('hidden')
  dlg.classList.add('xhidden')
  //}
}
function hideHomescreenPrompt() {
  var dlg = document.getElementById('addToHomeScreen')
  dlg.classList.remove('xhidden')
  dlg.classList.add('hidden')
}
function showDialog(message) {
  var whitebg = document.getElementById('white-background')
  var dlg = document.getElementById('dlgbox')
  //	 whitebg.style.display = "block";
  dlg.style.display = 'block'

  var winWidth = window.innerWidth
  var winHeight = window.innerHeight
  // dlg.style.left = (winWidth/2) - 480/2 + "px";
  dlg.style.top = '150px'
}

// unique functions//
function toggleSummarySmall(id) {
  /* This function is unique to this app*/
  /*<div id="Summary0" class="summary">
<div class="summary-visible">
<img class = "generations_plus" src="/images/generations-plus.png">
<div class="summary-title">
<span class = "summary_heading" >Motivation and Encouragement</span>
</div></div>
<div class="collapsed" id ="Text0">
<p>Text</p>

</div>
*/
  var text = document.getElementById(id).innerHTML
  var new_text = ''
  if (text.includes('generations-plus-small.png')) {
    new_text = text.replace(
      'generations-plus-small.png',
      'generations-minus.png'
    )
    text = new_text
    new_text = text.replace('collapsed-small', 'sunken-text-small')
    text = new_text
    new_text = text.replace(
      'summary-heading-small',
      'summary-heading-sunken-small'
    )
    document.getElementById(id).classList.remove('summary-visible-small')
    document.getElementById(id).classList.add('summary-sunken-small')
  } else {
    new_text = text.replace(
      'generations-minus.png',
      'generations-plus-small.png'
    )
    text = new_text
    new_text = text.replace('sunken-text-small', 'collapsed-small')
    text = new_text
    new_text = text.replace(
      'summary-heading-sunken-small',
      'summary-heading-small'
    )
    document.getElementById(id).classList.remove('summary-sunken-small')
    document.getElementById(id).classList.add('summary-visible-small')
  }
  document.getElementById(id).innerHTML = new_text
}
function toggleSummaryBig(id) {
  /* This function is unique to this app*/
  var text = document.getElementById(id).innerHTML

  var new_text = ''
  if (text.includes('generations-plus-big.png')) {
    new_text = text.replace(
      'generations-plus-big.png',
      'generations-minus-big.png'
    )
    text = new_text
    new_text = text.replace('collapsed-big', 'sunken-text-big')
    text = new_text
    new_text = text.replace('summary-heading-big', 'summary-heading-sunken-big')
    document.getElementById(id).classList.remove('summary-visible-big')
    document.getElementById(id).classList.add('summary-sunken-big')
  } else {
    new_text = text.replace(
      'generations-minus-big.png',
      'generations-plus-big.png'
    )
    text = new_text
    new_text = text.replace('sunken-text-big', 'collapsed-big')
    text = new_text
    new_text = text.replace('summary-heading-sunken-big', 'summary-heading-big')
    document.getElementById(id).classList.remove('summary-sunken-big')
    document.getElementById(id).classList.add('summary-visible-big')
  }
  document.getElementById(id).innerHTML = new_text
}

// for sharing
//https://developers.google.com/web/updates/2016/09/navigator-share
