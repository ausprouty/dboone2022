// return to last page if restarting
// check for current dynamic

function router() {
  // store last page so that root index can redirect when you come again
  localStorage.setItem('lastpage', window.location.href)
}

document.addEventListener('DOMContentLoaded', router)

// check to see if this is an index file for a series and get value index.json
window.onload = function () {
  findCollapsible()
  findSummaries()
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

function hideWhenOffline() {
  // get rid of all readmore comments
  var readmore = document.getElementsByClassName('readmore')
  if (readmore.length > 0) {
    for (var i = 0; i < readmore.length; i++) {
      readmore[i].style.display = 'none'
    }
  }
  readmore = document.getElementsByClassName('bible_readmore')
  if (readmore.length > 0) {
    for (var i = 0; i < readmore.length; i++) {
      readmore[i].style.display = 'none'
    }
  }
  // hide external-link
  var links = document.getElementsByClassName('external-link')
  if (links.length > 0) {
    for (var i = 0; i < links.length; i++) {
      links[i].style.className = 'unlink'
    }
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
