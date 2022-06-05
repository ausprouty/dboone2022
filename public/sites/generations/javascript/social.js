function facebook(aus = '339218283324109') {
  var account = '639453526082463'
  var link = 'https://facebook.com/' + account
  let isApple = ['iPhone', 'iPad', 'iPod'].includes(navigator.platform)
  if (isApple) {
    link = 'fb://profile/' + account
  }
  if (isApple) {
    link = 'fb://page/' + account
  }
  window.open(link)
}
