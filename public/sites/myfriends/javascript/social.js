function getMailtoUrl(to, subject, body) {
  var args = []
  if (typeof subject !== 'undefined') {
    args.push('subject=' + encodeURIComponent(subject))
  }
  if (typeof body !== 'undefined') {
    args.push('body=' + encodeURIComponent(body))
  }
  var url = 'mailto:' + encodeURIComponent(to)
  if (args.length > 0) {
    url += '?' + args.join('&')
  }
  return url
}

function facebook(account = 339218283324109) {
  var link = 'https://facebook.com/' + account
  let isApple = ['iPhone', 'iPad', 'iPod'].includes(navigator.platform)
  if (isApple) {
    link = 'fb://profile/' + account
  }
  let isAndroid = ['Android'].includes(navigator.platform)
  if (isAndroid) {
    link = 'fb://page/' + account
  }
  window.open(link)
}
function languages() {
  location.href = '/content/languages.html'
}
function share() {
  var subject = 'MyFriends Discussion'
  var message = 'Here is the link to our discussion: '
  var url = location.href
  if ('share' in navigator) {
    navigator.share({
      title: subject,
      text: message,
      url: url,
    })
  } else {
    var body = message + ': ' + url
    // Here we use the WhatsApp API as fallback; remember to encode your text for URI
    //location.href = 'mailto:?body=' + encodeURIComponent(text + ' - ') + location.href + ';subject=' + encodeURIComponent(title);
    location.href = getMailtoUrl('', subject, body)
  }
}
