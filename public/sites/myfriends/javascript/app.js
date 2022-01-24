var CACHE_DYNAMIC_NAME = 'content-1'
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
  // which page should we go to?
  var currentPage = window.location.pathname
  // go to last page visited if you are visiting root page again
  if (localStorage.getItem('lastpage') && currentPage == '/') {
    var lastpage = localStorage.getItem('lastpage')
    localStorage.removeItem('lastpage')
    window.location.replace(lastpage)
  } else {
    // stay here if you entered into any other page than the root,
    if (currentPage !== '/') {
      var lastpage = localStorage.getItem('lastpage')
      localStorage.setItem('previouspage', lastpage)
      localStorage.setItem('lastpage', window.location.href)
    }
    // otherwise guess the best language for this browser,
    else {
      if ('bob' != 'genious') {
        home = DEFAULT_ENTRY
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

function restoreDynamic() {
  if (typeof localStorage.offline != 'undefined' && localStorage.offline) {
    console.log('restoreDynamic')
    offline = JSON.parse(localStorage.offline) //get existing values
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
// check to see if this is an index file for a series and get value index.json
window.onload = function () {
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
  //mc2DecideWhichVideosToShow();
  findSummaries()
  if (localStorage.getItem('mc2Trainer')) {
    // unhide all trainer notes
    elements = document.getElementsByClassName('trainer-hide')
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
}

function findSummaries() {
  var coll = document.getElementsByClassName('summary')
  var i
  for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener('click', function () {
      this.classList.toggle('active')
      var text = this.innerHTML
      if (text.includes('+')) {
        var new_text = text.replace('+', '-')
      } else {
        var new_text = text.replace('-', '+')
      }
      this.innerHTML = new_text
      // get nextElementSibling
      var content = this.nextElementSibling
      // hide or show?
      if (content.style.display === 'block') {
        content.style.display = 'none'
        this.classList.remove('summary-shown')
        this.classList.add('summary-hidden')
      } else {
        content.style.display = 'block'
        this.classList.remove('summary-hidden')
        this.classList.add('summary-shown')
      }
    })
  }
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

// page is set in the nav bar of each web page
function pageGoBack(page) {
  if (localStorage.getItem('returnpage')) {
    page = localStorage.getItem('returnpage')
    localStorage.removeItem('returnpage')
  }
  window.location.replace(page)
}
// If you are in Lesson 1 and want a person to go to Lesson 7,
// The return button will now bring them back
// rather than take them to the index.
function goToPageAndSetReturn(page) {
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
  var readmore = document.getElementsByClassName('bible_readmore')
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
  var links = document.getElementsByClassName('external-movie')
  if (links.length > 0) {
    console.log('I found external-link')
    for (var i = 0; i < links.length; i++) {
      links[i].style.display = 'none'
    }
  }
}

function checkOfflineSeries(series) {
  console.log(series + ' series is being checked')
  // set ios prompt if needed
  //https://www.netguru.co/codestories/few-tips-that-will-make-your-pwa-on-ios-feel-like-native

  if (this.needsToSeePrompt()) {
    localStorage.setItem('lastSeenPrompt', new Date()) // set current time for prompt
    var myBtn = document.getElementById('offline-request'),
      myDiv = document.createElement('div')
    myDiv.setAttribute('class', 'ios-notice-image')
    myDiv.innerHTML =
      '<img class = "ios-notice-icon" src="/images/icons/app-icon-144x144.png">'
    myDiv.innerHTML +=
      '<p class="ios-notice">' +
      'Install this app on your phone without going to the Apple Store.' +
      '</p>'
    myDiv.innerHTML +=
      '<img class = "ios-notice-homescreen" src="/images/installOnIOS.png">'

    myBtn.parentNode.replaceChild(myDiv, myBtn)
    console.log('I am showing prompt')
    return
  }
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

// get value of variable in array
// is id in key?
function inLocalStorage(key, id) {
  var deferred = $.Deferred()
  var result = ''
  console.log('looking offline for local storage')
  key_value = localStorage.getItem(key)
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
    var result = ''
    console.log('not stored locally')
  }
  deferred.resolve(result)
  return deferred.promise()
}

function dlgOK() {
  var whitebg = document.getElementById('white-background')
  var dlg = document.getElementById('dlgbox')
  whitebg.style.display = 'none'
  dlg.style.display = 'none'
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

function needsToSeePrompt() {
  if (navigator.standalone) {
    return false
  }
  let today = new Date()
  let lastPrompt = localStorage.lastSeenPrompt
  let days = datediff(lastPrompt, today)
  let isApple = ['iPhone', 'iPad', 'iPod'].includes(navigator.platform)
  return (isNaN(days) || days > 14) && isApple
}

function datediff(first, second) {
  //  Take the difference between the dates and divide by milliseconds per day.
  //  Round to nearest whole number to deal with DST.
  return Math.round((second - first) / (1000 * 60 * 60 * 24))
}

// for sharing
//https://developers.google.com/web/updates/2016/09/navigator-share
