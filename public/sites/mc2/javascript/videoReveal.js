document.addEventListener('onload', videoReveal())

function videoReveal() {
  // called by app.js
  var coll = document.getElementsByClassName('external-movie')
  var i
  for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener('click', function () {
      this.classList.toggle('active')
      var content = this.nextElementSibling
      if (content.style.display === 'block') {
        content.style.display = 'none'
        this.classList.remove('revealed')
        this.classList.add('external-movie')
      } else {
        content.style.display = 'block'
        this.classList.remove('external-movie')
        this.classList.add('revealed')
      }
    })
  }
}
