

function getMailtoUrl(to, subject, body) {
  var args = [];
  if (typeof subject !== "undefined") {
    args.push("subject=" + encodeURIComponent(subject));
  }
  if (typeof body !== "undefined") {
    args.push("body=" + encodeURIComponent(body));
  }
  var url = "mailto:" + encodeURIComponent(to);
  if (args.length > 0) {
    url += "?" + args.join("&");
  }
  return url;
}

function facebook(aus = "339218283324109") {
  var account = '639453526082463';
  var link = "https://facebook.com/" + account;
  let isApple = ["iPhone", "iPad", "iPod"].includes(navigator.platform);
  if (isApple) {
    link = "fb://profile/" + account;
  }
  let isAndroid = ["Android"].includes(navigator.platform);
  if (isApple) {
    link = "fb://page/" + account;
  }
  window.open(link);
}
function sendAction(page, title){
  
   if (localStorage.getItem(page)){
   var text = '';
   var text2 = '';
    var notes = JSON.parse(localStorage.getItem(page));
    var length = notes.length;
    for (i = 0; i< length; i++){
      if (notes[i].value != ''){
        text2 = text  + notes[i].value + "\n\n";
        text = text2;
      }
    }
    var url = page.replace(/-/g, '/');
    text2 = text +  'See the questions at https://generations.myfriends.network/content/' + url + "\n";
    text = text2;
    var subject = 'Your notes for ' + title;
    location.href = getMailtoUrl("", subject, text);
  }
  else{
    shareLesson(page, title); 
  }
  
}

function shareLesson(message) {


  var subject = "Generations";
  var url = location.href;
  if ("share" in navigator) {
    navigator.share({
      title: subject,
      text: message,
      url: url
    });
  } else {
    var body = message + ': ' + url;
    // Here we use the WhatsApp API as fallback; remember to encode your text for URI
    //location.href = 'mailto:?body=' + encodeURIComponent(text + ' - ') + location.href + ';subject=' + encodeURIComponent(title);
    location.href = getMailtoUrl("", subject, body);
  }

}
