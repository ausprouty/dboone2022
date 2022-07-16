document.addEventListener('onload', mc2DecideWhichVideosToShow())

function mc2DecideWhichVideosToShow() {
  // called by app.js
  var coll = document.getElementsByClassName('external-movie')
  var i
  for (i = 0; i < coll.length; i++) {
    var c = coll[i].nextElementSibling
    var vid = c.innerHTML
    if (mc2VideoShouldBeShown(vid)) {
      coll[i].addEventListener('click', function () {
        this.classList.toggle('active')
        var content = this.nextElementSibling
        if (content.style.display === 'block') {
          content.style.display = 'none'
          this.classList.remove('revealed')
          this.classList.add('external-movie')
        } else {
          // modify content
          var video = content.innerHTML
          if (!video.match(/<iframe/g)) {
            var iframe = mc2CreateIframe(video)
            content.innerHTML = iframe
          }
          content.style.display = 'block'
          this.classList.remove('external-movie')
          this.classList.add('revealed')
        }
      })
    } else {
      coll[i].classList.add('hidden')
    }
  }
}
function mc2VideoShouldBeShown(video) {
  var shown = mc2FindLanguageCodeForVideo(video)
  return shown
}

function mc2DisplayPrimaryVideoOptions(div, video_type = 'all') {
  var language = mc2GetDisplayLanguage()
  var data = localVideoData()
  console.log(data)
  // select language
  var title_text =
    '<div class="alert"><form onsubmit="mc2SavePrimaryVideoOption();">' +
    '\n' +
    '<h3>' +
    data.select_language[language] +
    '</h3>' +
    '\n'
  var select_text =
    '<select name="video_options" id="video_options"  onmousedown="if(this.options.length>6){this.size=6;}"  onchange=\'this.size=0;\' onblur="this.size=0;">' +
    '\n'
  var options = data.languages
  var length = data.languages.length
  var option_text = ''
  var temp = ''
  for (var i = 0; i < length; i++) {
    if (video_type == 'all' || data.languages[i][video_type]) {
      option_text =
        '<option value="' +
        data.languages[i][language] +
        '">' +
        data.languages[i][language] +
        '</option>' +
        '\n'
      temp = select_text.concat(option_text)
      select_text = temp
    }
  }
  option_text = '</select>' + '\n'
  temp = select_text.concat(option_text)
  select_text = temp
  //hidden
  var hidden_text =
    '<input type="hidden" id="video_div" name="video_div" value="' +
    div +
    '">' +
    '\n'
  // submit
  var submit_text =
    '<input type="submit" value="' +
    data.save[language] +
    '">' +
    '\n' +
    '</form></div>'
  // put together
  document.getElementById(div).innerHTML =
    title_text + select_text + hidden_text + submit_text
}

function mc2SavePrimaryVideoOption() {
  var data = localVideoData()
  var videoOption = document.getElementById('video_options').value
  localSetPreference('mc2PrimaryVideoLanguage', videoOption)
  var div = document.getElementById('video_div').value

  if (videoOption != data.default_video_language) {
    mc2DisplayAlternativeVideoOption(div)
  } else {
    window.localStorage.removeItem('mc2AlternativeVideoLanguage')
    mc2ChangeVideosDisplayed()
  }
}
function mc2DisplayAlternativeVideoOption(div) {
  var language = mc2GetDisplayLanguage()
  var data = localVideoData()
  var form_text =
    '<div class="alert"><form onsubmit="mc2SaveAlternateVideoOption();">' + '\n'
  var alternative_text =
    '<h3>' + data.alternative_language[language] + '</h3>' + '\n'
  var temp1 =
    '<input type="radio" id="yes" name="alternative" value="yes">' + '\n'
  var temp2 = '<label for="no">' + data.yes[language] + '</label><br>' + '\n'
  var temp3 =
    ' <input type="radio" id="no" name="alternative" value="no">' + '\n'
  var temp4 =
    '<label for="yes">' + data.no[language] + '</label><br></br>' + '\n'
  var temp = alternative_text.concat(temp1, temp2, temp3, temp4)
  alternative_text = temp
  //hidden
  var hidden_text =
    '<input type="hidden" id="video_div" name="video_div" value="' +
    div +
    '">' +
    '\n'
  var submit_text =
    '<input type="submit" value="' +
    data.save[language] +
    '">' +
    '\n' +
    '</form></div>'
  // put together
  var message = form_text + alternative_text + hidden_text + submit_text
  document.getElementById(div).innerHTML = message
}
function mc2SaveAlternateVideoOption() {
  if (document.getElementById('yes').checked) {
    var data = localVideoData()
    localSetPreference(
      'mc2AlternativeVideoLanguage',
      data.default_video_language
    )
  } else {
    localRemovePreference('mc2AlternativeVideoLanguage')
  }
  var div = document.getElementById('video_div').value
  mc2ChangeVideosDisplayed()
}
function mc2CreateIframe(video) {
  if (video.match(/vimeo/g)) {
    var iframe = mc2CreateIframeVimeo(video)
    return iframe
  } else if (video.match(/youtube/g)) {
    var iframe = mc2CreateIframeYoutube(video)
    return iframe
  } else {
    return mc2CreateIframeArclight(video)
  }
}
function mc2CreateIframeArclight(video) {
  var template = `<div class="arc-cont">
      <iframe src="https://api.arclight.org/videoPlayerUrl?refId=[my_video]" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>
    <style>.arc-cont{position:relative;display:block;margin:10px auto;width:100%}.arc-cont:after{padding-top:59%;display:block;content:""}.arc-cont>iframe{position:absolute;top:0;bottom:0;right:0;left:0;width:98%;height:98%;border:0}</style>
    </div>
    `
  var my_video = mc2FindLanguageCodeForVideo(video)
  var temp = template.replaceAll('[video]', video)
  var iframe = temp.replace('[my_video]', my_video)
  var data = localVideoData()
  var language = mc2GetDisplayLanguage()
  if (
    data.allow_user_to_select_language == 'true' &&
    mc2MoreThanOneLanguage(video)
  ) {
    var change_language = data.change_language[language]
    var temp = `<div class="changeLanguage" onClick="mc2ChangeVideoLanguage('ShowOptionsFor[video]')"> [ChangeLanguage] </div>`
    var temp2 = temp.replace('[ChangeLanguage]', change_language)
    temp = temp2.replaceAll('[video]', video)
    temp2 = iframe
    iframe = temp2 + temp
  }
  return iframe
}
function mc2CreateIframeVimeo(video) {
  var my_video = video.replace('[vimeo]', '')
  var template = `<div class="arc-cont">
	<iframe src="https://player.vimeo.com/video/[my_video]" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>
	<style>.arc-cont{position:relative;display:block;margin:10px auto;width:100%}.arc-cont:after{padding-top:59%;display:block;content:""}.arc-cont>iframe{position:absolute;top:0;bottom:0;right:0;left:0;width:98%;height:98%;border:0}</style>
    </div>`
  var iframe = template.replaceAll('[my_video]', my_video)
  return iframe
}
function mc2CreateIframeYoutube(video) {
  var my_video = video.replace('[youtube]', '')
  var template = `<div class="arc-cont">
	<iframe src="https://www.youtube.com/embed/[my_video]" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>
	<style>.arc-cont{position:relative;display:block;margin:10px auto;width:100%}.arc-cont:after{padding-top:59%;display:block;content:""}.arc-cont>iframe{position:absolute;top:0;bottom:0;right:0;left:0;width:98%;height:98%;border:0}</style>
    </div>`
  var iframe = template.replaceAll('[my_video]', my_video)
  return iframe
}
function mc2CreateIframeYoutubeX(video) {
  var my_video = video.replace('[youtube]', '')
  var template = `<iframe   src="https://www.youtube.com/embed/[my_video]" width="100%"  frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; " allowfullscreen></iframe>`
  var iframe = template.replaceAll('[my_video]', my_video)
  return iframe
}

function mc2MoreThanOneLanguage(video) {
  var count = 0
  var video_type = null
  if (video.match(/\[acts\]/g)) {
    video_type = 'acts'
  }
  if (video.match(/\[jfilm\]/g)) {
    video_type = 'jfilm'
  }
  if (video.match(/\[lumo\]/g)) {
    video_type = 'lumo'
  }
  var data = localVideoData()
  var languages = data.languages
  for (var i = 0; i < languages.length; i++) {
    if (languages[i][video_type]) {
      count++
      if (count > 1) {
        return true
      }
    }
  }
  return false
}
function mc2FindLanguageCodeForVideo(video) {
  var yourVideo = null
  var languageCode = null
  if (video.match(/\[acts\]/g)) {
    languageCode = mc2GetLanguageCodeForVideo('acts')
    if (languageCode) {
      yourVideo = video.replace('[acts]', languageCode)
    }
    return yourVideo
  }
  if (video.match(/\[jfilm\]/g)) {
    languageCode = mc2GetLanguageCodeForVideo('jfilm')
    if (languageCode) {
      yourVideo = video.replace('[jfilm]', languageCode)
    }
    return yourVideo
  }
  if (video.match(/\[lumo\]/g)) {
    languageCode = mc2GetLanguageCodeForVideo('lumo')
    if (languageCode) {
      yourVideo = video.replace('[lumo]', languageCode)
    }
    return yourVideo
    //todo: also need to replace English
    //todo: JESUS Project is using number for language on new releases
  }
  if (video.match(/\[vimeo\]/g)) {
    yourVideo = video
    return yourVideo
  }
  if (video.match(/\[youtube\]/g)) {
    yourVideo = video
    return yourVideo
  }
  return yourVideo
}

function mc2GetDisplayLanguage() {
  var data = localVideoData()
  if (data.default_display_language) {
    return data.default_display_language
  }
  return null
}

function mc2GetLanguageCodeForVideo(video_code) {
  var language = localGetPreferenceValue('mc2PrimaryVideoLanguage')
  var code = mc2LanguageCodeForVideo(language, video_code)

  if (!code) {
    language = localGetPreferenceValue('mc2AlternativeVideoLanguage')
    code = mc2LanguageCodeForVideo(language, video_code)
  }
  if (!code) {
    language = 'English'
    code = mc2LanguageCodeForVideo(language, video_code)
  }
  return code
}
function mc2LanguageCodeForVideo(language, video_code) {
  var data = localVideoData()
  for (var i = 0; i < data.languages.length; i++) {
    if (data.languages[i].english == language) {
      if (data.languages[i][video_code]) {
        return data.languages[i][video_code]
      } else {
        return null
      }
    }
  }
  return null
}
function mc2ChangeVideoLanguage(div) {
  var video_type = null
  if (div.match(/\[acts\]/g)) {
    video_type = 'acts'
  }
  if (div.match(/\[jfilm\]/g)) {
    video_type = 'jfilm'
  }
  if (div.match(/\[lumo\]/g)) {
    video_type = 'lumo'
  }
  if (div.match(/\[vimeo\]/g)) {
    video_type = 'vimeo'
  }
  mc2DisplayVideoOptions(div, video_type)
}
function mc2DisplayVideoOptions(div, video_type) {
  localDisplayPrimaryVideoOptions(div, video_type)
}

function mc2GetVideoPreferences() {
  if (window.localStorage.getItem('mc2VideoPreferences')) {
    return JSON.parse(window.localStorage.getItem('mc2VideoPreferences'))
  }
  var blank = {}
  return blank
}
function mc2SaveVideoPreferences(raw_data) {
  var data = JSON.stringify(raw_data)
  window.localStorage.setItem('mc2VideoPreferences', data)
}
