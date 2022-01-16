document.addEventListener('onload', videoReveal())

function videoReveal() {
  // called by app.js
//
  var coll = document.getElementsByClassName('external-movie')
  var i
  for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener('click', function () {
      this.classList.toggle('active')
      var content = this.nextElementSibling
      if (content.style.display === 'block') {
        plyr.setup('#video')
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

/* consider
 <a href="javascript:void(0);" onclick="shareVideo([video])">
                <img class="social" src="/content/M2/images/standard/share_video.png" />
            </a>
*/
