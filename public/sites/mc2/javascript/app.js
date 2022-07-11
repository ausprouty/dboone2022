var CACHE_DYNAMIC_NAME = 'content-1'

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

  localStorage.setItem('lastpage', window.location.href)
}
document.addEventListener('DOMContentLoaded', router)
document.addEventListener('DOMContentLoaded', modifyPage)

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
// check to see if this is an index file for a series and get value index.json
function modifyPage() {
  findCollapsible()
  findSummaries()
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
      var new_text = ''
      if (text.includes('+')) {
        new_text = text.replace('+', '-')
      } else {
        new_text = text.replace('-', '+')
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
  console.log('find Collapsible')
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

// page is set in the nav bar of each web page
function pageGoBack(page) {
  if (localStorage.getItem('returnpage')) {
    page = localStorage.getItem('returnpage')
    localStorage.removeItem('returnpage')
  }
  window.location.replace(page)
}
// to show verses
function popUp(field) {
  var content = document.getElementById(field)
  if (content.style.display === 'block') {
    content.style.display = 'none'
  } else {
    content.style.display = 'block'
    //this.className= "revealed";
  }
}
// If you are in Lesson 1 and want a person to go to Lesson 7,
// The return button will now bring them back
// rather than take them to the index.
// called from html pages
function goToPageAndSetReturn(page) {
  localStorage.setItem('returnpage', window.location.href)
  window.location.replace(page)
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

// for sharing
//https://developers.google.com/web/updates/2016/09/navigator-share