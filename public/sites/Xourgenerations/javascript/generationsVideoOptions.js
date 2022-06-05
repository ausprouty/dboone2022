/* INITIAL INPUT:

	<div id="VideoToggle0" class="summary vimeo">
        <div onclick="toggleVideo('Videotoggle0', 'vimeo')" class="summary-visible">
            <img class = "generations_plus_big" src="/images/generations-plus-big.png">
            <div class="summary-title">
                <span class = "summary_heading_sunken" >Watch <i>The Spirit-filled Life</i>  online</span>
            </div>
        </div>
        <div class="collapsed" id ="VideoToggleVideo0">
            [vimeo]163070867 
        </div>
    </div>
	
	INITIAL OUTPUT:
	
	<div id="VideoToggle0" class="summary-sunken vimeo ">
        <div onclick="toggleVideo('Videotoggle0', 'vimeo')" class="summary-visible">
            <img class = "generations_plus_big" src="/images/generations-plus-big.png">
            <div class="summary-title">
                <span class = "summary_heading_sunken" >Watch <i>The Spirit-filled Life</i>  online</span>
            </div>
        </div>
        <div class="sunken-text" id ="VideoToggle0Video">
            <iframe> SOME CONTENT </iframe>
        </div>
    </div>
	
	
*/


function toggleVideo(id, video_type){
	var video = document.getElementById(id + 'Video').innerHTML;
	if (!video.match(/<iframe/g)){
		var iframe =  videoCreateIframe(video.trim());
		console.log (iframe);
		document.getElementById(id + 'Video').innerHTML = iframe;
	}
	var text = document.getElementById(id).innerHTML;
	var temp = '';
	// Do we need to expand?
	if (text.includes('generations-plus-big.png')){
		var new_text = text.replace('generations-plus-big.png', 'generations-minus-big.png');
		text = new_text;
		var new_text = text.replace('collapsed', 'sunken-text');
		text = new_text;
		var new_text = text.replace('summary_heading', 'summary_heading_sunken');
		document.getElementById(id).classList.remove('summary-visible');
		document.getElementById(id).classList.add('summary-sunken');
	}
	// Collapse
	else{
		var new_text = text.replace('generations-minus-big.png', 'generations-plus-big.png');
		text = new_text;
		var new_text = text.replace('sunken-text', 'collapsed');
		text = new_text;
		var new_text = text.replace('summary_heading_sunken', 'summary_heading');
		document.getElementById(id).classList.remove('summary-sunken');
		document.getElementById(id).classList.add('summary-visible');
	}
	document.getElementById(id).innerHTML = new_text;
	
}

function videoChangeVideoLanguage(div){
    var video_type = null;
    if (div.match(/\[acts\]/g)){
        video_type = 'acts';
    }
    if (div.match(/\[jfilm\]/g)){
        video_type = 'jfilm';
    }
    if (div.match(/\[lumo\]/g)){
        video_type = 'lumo';
    }
    if (div.match(/\[vimeo\]/g)){
        video_type = 'vimeo';
    }
    videoDisplayVideoOptions(div,video_type);
}
function videoCreateIframe(video){
	if (video.match(/vimeo/g)){
		var iframe =  videoCreateIframeVimeo(video);
		return iframe;
	}
	else{
		return videoCreateIframeArclight(video);
	}
}
function videoCreateIframeArclight(video){
    var template = `<div class="arc-cont">
      <iframe src="https://api.arclight.org/videoPlayerUrl?refId=[my_video]" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>
    <style>.arc-cont{position:relative;display:block;margin:10px auto;width:100%}.arc-cont:after{padding-top:59%;display:block;content:""}.arc-cont>iframe{position:absolute;top:0;bottom:0;right:0;left:0;width:98%;height:98%;border:0}</style>
    </div>
    `; 
    var my_video = videoFindLanguageCode(video);
    var temp = template.replaceAll("[video]", video);
    var iframe = temp.replace("[my_video]", my_video);
	var data = localVideoData();
    var language = videoGetDisplayLanguage();
	if (data.allow_user_to_select_language == "true" && videoMoreThanOneLanguage(video)){
		var change_language = data.change_language[language];
		var temp = `<div class="changeLanguage" onClick="videoChangeVideoLanguage('ShowOptionsFor[video]')"> [ChangeLanguage] </div>`;
		var temp2 = temp.replace("[ChangeLanguage]", change_language);
		temp = temp2.replaceAll("[video]", video);
		temp2 = iframe;
		iframe = temp2 + temp;
	}
    return iframe;
  }
function videoCreateIframeVimeo(video){
	var my_video = video.replace("[vimeo]", '');
	var template = `<div class="arc-cont">
	<iframe src="https://player.vimeo.com/video/[my_video]" 
	  allowfullscreen webkitallowfullscreen mozallowfullscreen>
	</iframe>
	<style>.arc-cont{position:relative;display:block;margin:10px auto;width:100%}.arc-cont:after{padding-top:59%;display:block;content:""}.arc-cont>iframe{position:absolute;top:0;bottom:0;right:0;left:0;width:98%;height:98%;border:0}</style>
    </div>`;
	var iframe = template.replaceAll("[my_video]", my_video);
	return iframe;
  }
  /* This section creates listeners which will transform the elements from
  
  <div id="ToggleVideo0" class="video">
        <button id="toggleButton0" type="button" onclick="generationsToggleVideo('ToggleVideo0')"  class="external-movie vimeo">Watch &nbsp;<i>Gathering MyFriends</i>&nbsp;  online</button>
        <div class="collapsed">[vimeo]414666303</div>
    </div>
	
	to
	
	<div id="ToggleVideo0" class="video  summary-sunken">
        <button id="toggleButton0" type="button" onclick="generationsToggleVideo('ToggleVideo0')"  class="external-movie sunken-visible">Watch &nbsp;<i>Gathering MyFriends</i>&nbsp;  online</button>
        <div class="sunken_text" style= "display:block"><iframe> somethig </iframe></div>
    </div>
	
	
*/	
function videoDecideWhichVideosToShow(){
	// called by app.js
	var coll = document.getElementsByClassName("external-movie");
	var i;
	for (i = 0; i < coll.length; i++) {
        var c = coll[i].nextElementSibling;
        var vid = c.innerHTML;
        if (videoShouldBeShown(vid)) {
            coll[i].addEventListener("click", function() {

                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.display === "block") {
                content.style.display = "none";
                this.classList.remove('revealed');
                this.classList.add('external-movie');
                } else {
                // modify content
                var video = content.innerHTML;
                if (!video.match(/<iframe/g)){
                    var iframe =  videoCreateIframe(video);
                    content.innerHTML = iframe;
                }
                content.style.display = "block";
                this.classList.remove('external-movie');
                this.classList.add('revealed');
                }
            });
        } else{
        coll[i].classList.add('hidden');
        
        }
	}
}
function videoDisplayAlternativeOption(div){
  
	var language = videoGetDisplayLanguage();
    var data = localVideoData();
	var form_text = '<div class="alert"><form onsubmit="videoSaveAlternateOption();">' + "\n";
	var alternative_text = '<h3>' + data.alternative_language[language] + '</h3>'+ "\n";
	var temp1 = '<input type="radio" id="yes" name="alternative" value="yes">' + "\n";
	var temp2 = '<label for="no">' + data.yes[language] + '</label><br>' + "\n";
	var temp3 = ' <input type="radio" id="no" name="alternative" value="no">' + "\n";
	var temp4= '<label for="yes">' + data.no[language] + '</label><br></br>' + "\n";
	var temp = alternative_text.concat(temp1, temp2, temp3, temp4);
	alternative_text = temp;
	//hidden
	var hidden_text =  '<input type="hidden" id="video_div" name="video_div" value="' + div + '">' + "\n";
	var submit_text =  '<input type="submit" value="' + data.save[language] + '">' + "\n" + '</form></div>';
	// put together
	var message = form_text + alternative_text + hidden_text + submit_text;
	document.getElementById(div).innerHTML =  message;
}
function videoDisplayPrimaryOptions(div, video_type = "all"){
    var language = videoGetDisplayLanguage();
    var data = localVideoData();
    console.log (data);
    // select language
    var title_text = '<div class="alert"><form onsubmit="videoSavePrimaryOption();">' + "\n" + '<h3>' + data.select_language[language] + '</h3>'+ "\n";
    var select_text = '<select name="video_options" id="video_options"  onmousedown="if(this.options.length>6){this.size=6;}"  onchange=\'this.size=0;\' onblur="this.size=0;">'+ "\n";
    var options = data.languages;
    var length = data.languages.length;
    var option_text = '';
    var temp = '';
    for (var i =0; i < length; i++){
        if (video_type == 'all' || data.languages[i][video_type] ){
            option_text =  '<option value="' + data.languages[i][language] + '">' + data.languages[i][language] + '</option>' + "\n";
            temp = select_text.concat(option_text);
            select_text = temp;
        }
    }
    option_text = '</select>' + "\n";
    temp = select_text.concat(option_text);
    select_text = temp;
    //hidden
    var hidden_text =  '<input type="hidden" id="video_div" name="video_div" value="' + div + '">' + "\n";
    // submit
    var submit_text =  '<input type="submit" value="' + data.save[language] + '">' + "\n" + '</form></div>';
    // put together
    document.getElementById(div).innerHTML = title_text + select_text + hidden_text + submit_text; 

};
function videoDisplayVideoOptions(div, video_type){
    localDisplayPrimaryVideoOptions(div, video_type);
}
function videoFindLanguageCode(video){
    var yourVideo = null;
    var languageCode = null;
    if (video.match(/\[acts\]/g)){
      languageCode = videoGetLanguageCodeForVideo('acts');
      if (languageCode){
        yourVideo = video.replace('[acts]', languageCode);
      }
      return yourVideo;
    }
    if (video.match(/\[jfilm\]/g)){
      languageCode = videoGetLanguageCodeForVideo('jfilm');
      if (languageCode){
        yourVideo = video.replace('[jfilm]', languageCode);
      }
      return yourVideo;
    }
    if (video.match(/\[lumo\]/g)){
      languageCode = videoGetLanguageCodeForVideo('lumo');
      if (languageCode){
        yourVideo = video.replace('[lumo]', languageCode);
      }
      return yourVideo;
      //todo: JESUS Project is using number for language on new releases
    }
    if (video.match(/\[vimeo\]/g)){
      yourVideo = video;
      return yourVideo;
    }
    return yourVideo;
  }
function videoGetDisplayLanguage(){
    var data = localVideoData();
    if (data.default_display_language){
        return data.default_display_language;
    }
    return null;
}
function videoGetLanguageCodeForVideo(video_code){
    var language = localGetPreferenceValue("videoPrimaryLanguage");
    var code = videoLanguageCodeForVideo (language, video_code);
   
    if (!code){
      language = localGetPreferenceValue("videoAlternativeLanguage");
      code = videoLanguageCodeForVideo (language, video_code);
    }
    if (!code){
      language = "English";
      code = videoLanguageCodeForVideo (language, video_code);
    }
    return code;
}
function videoGetVideoPreferences(){
    if (window.localStorage.getItem("videoPreferences")){
        return JSON.parse(window.localStorage.getItem("videoPreferences"));
    }
    var blank = {};
    return blank;
}
function videoLanguageCodeForVideo (language, video_code){
    var data = localVideoData();
    for (var i = 0; i < data.languages.length; i++){
        if (data.languages[i].english == language){
            if (data.languages[i][video_code]){
                return data.languages[i][video_code];
            }
            else{
                return null;
            }
        }
    }
    return null;
}
function videoMoreThanOneLanguage(video){
	var count = 0;
	var video_type = null;
	if (video.match(/\[acts\]/g)){
		video_type = 'acts';
    }
    if (video.match(/\[jfilm\]/g)){
		video_type = 'jfilm';
    }
    if (video.match(/\[lumo\]/g)){
		video_type = 'lumo';
	}
	var data = localVideoData();
	var languages = data.languages;
	for (var i = 0; i < languages.length; i++) {
		if (languages[i][video_type]) {
			count++;
			if (count > 1){
				return true;
			}
		}
	}
	return false; 
  }
function videoSaveAlternateOption(){
    if (document.getElementById("yes").checked){
		var data = localVideoData();
        localSetPreference("videoAlternativeLanguage", data.default_video_language);
    }
    else{
        localRemovePreference("videoAlternativeLanguage");
    }
	var div = document.getElementById("video_div").value;
    videoChangeVideosDisplayed();
}
function videoSavePrimaryOption(){
	var data = localVideoData();
    var videoOption = document.getElementById("video_options").value;
	localSetPreference("videoPrimaryVideoLanguage", videoOption);
	var div = document.getElementById("video_div").value;

    if( videoOption != data.default_video_language){
		videoDisplayAlternativeOption(div);
	}
	else{
		 window.localStorage.removeItem("videoAlternativeLanguage");
		 videoChangeVideosDisplayed();
	}
}
function videoSaveVideoPreferences(raw_data){
    var data = JSON.stringify(raw_data);
    window.localStorage.setItem("videoPreferences", data);
}
function videoShouldBeShown(video){
  var shown =  videoFindLanguageCode(video);
  return shown;

}




