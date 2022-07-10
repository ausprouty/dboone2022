document.addEventListener('DOMContentLoaded', checkOffline)
function checkOffline(){
    var series = document.getElementById('offline-request')
    if (series !== null) {
        checkOfflineSeries(series.dataset.json)
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
function datediff(first, second) {
  //  Take the difference between the dates and divide by milliseconds per day.
  //  Round to nearest whole number to deal with DST.
  return Math.round((second - first) / (1000 * 60 * 60 * 24))
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
