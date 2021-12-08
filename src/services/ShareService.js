export default {
  share() {
    var text = 'Add text to share with the URL'
    if ('share' in navigator) {
      navigator.share({
        title: document.title,
        text: text,
        url: location.href
      })
    } else {
      // Here we use the WhatsApp API as fallback; remember to encode your text for URI
      location.href =
        'https://api.whatsapp.com/send?text=' +
        encodeURIComponent(text + ' - ') +
        location.href
    }
  },
  facebook() {
    var link = ''
    link = '
    link  = process.env.VUE_APP_FACEBOOK_LINK
    //  website:
    //link = 'fb://profile/339218283324109' // IOS
    // link = 'fb://page/339218283324109' // Adroid
    window.open(link)
  }
}
