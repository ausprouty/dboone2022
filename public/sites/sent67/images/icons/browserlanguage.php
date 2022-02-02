<?php
function getPreferredLanguage() {
$langs = array();
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
// break up string into pieces (languages and q factors)
preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
        $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
if (count($lang_parse[1])) {
    // create a list like "en" => 0.8
    $langs = array_combine($lang_parse[1], $lang_parse[4]);
    // set default to 1 for any without q factor
    foreach ($langs as $lang => $val) {
        if ($val === '') $langs[$lang] = 1;
    }
    // sort list based on value
    arsort($langs, SORT_NUMERIC);
}
}
//extract most important (first)
foreach ($langs as $lang => $val) { break; }
//if complex language simplify it
if (stristr($lang,"-")) {$tmp = explode("-",$lang); $lang = $tmp[0]; }
return $lang;
}
$output = '{
	"source": "http://www.metamodpro.com/browser-language-codes",
	"languageCodes": [{
			"code": "af",
			"language": "Afrikaans",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sq",
			"language": "Albanian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "am",
			"language": "Amharic",
			"start": "/content/amh/index.html"
		},
		{
			"code": "ar",
			"language": "Arabic (Standard)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-dz",
			"language": "Arabic (Algeria)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-bh",
			"language": "Arabic (Bahrain)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-eg",
			"language": "Arabic (Egypt)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-iq",
			"language": "Arabic (Iraq)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-jo",
			"language": "Arabic (Jordan)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-kw",
			"language": "Arabic (Kuwait)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-lb",
			"language": "Arabic (Lebanon)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-ly",
			"language": "Arabic (Libya)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-ma",
			"language": "Arabic (Morocco)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-om",
			"language": "Arabic (Oman)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-qa",
			"language": "Arabic (Qatar)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-sa",
			"language": "Arabic (Saudi Arabia)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-sy",
			"language": "Arabic (Syria)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-tn",
			"language": "Arabic (Tunisia)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-ae",
			"language": "Arabic (U.A.E.)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "ar-ye",
			"language": "Arabic (Yemen)",
			"start": "/content/arb/index.html"
		},
		{
			"code": "an",
			"language": "Aragonese",
			"start": "/content/aus/index.html"
		},
		{
			"code": "hy",
			"language": "Armenian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "as",
			"language": "Assamese",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ast",
			"language": "Asturian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "az",
			"language": "Azerbaijani",
			"start": "/content/aus/index.html"
		},
		{
			"code": "eu",
			"language": "Basque",
			"start": "/content/aus/index.html"
		},
		{
			"code": "be",
			"language": "Belarusian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "bn",
			"language": "Bengali",
			"start": "/content/aus/index.html"
		},
		{
			"code": "bs",
			"language": "Bosnian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "br",
			"language": "Breton",
			"start": "/content/aus/index.html"
		},
		{
			"code": "bg",
			"language": "Bulgarian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "my",
			"language": "Burmese",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ca",
			"language": "Catalan",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ch",
			"language": "Chamorro",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ce",
			"language": "Chechen",
			"start": "/content/aus/index.html"
		},
		{
			"code": "zh",
			"language": "Chinese",
			"start": "/content/zh-s/index.html"
		},
		{
			"code": "zh-hk",
			"language": "Chinese (Hong Kong)",
			"start": "/content/zh-s/index.html"
		},
		{
			"code": "zh-cn",
			"language": "Chinese (PRC)",
			"start": "/content/zh-china/index.html"
		},
		{
			"code": "zh-sg",
			"language": "Chinese (Singapore)",
			"start": "/content/zh-s/index.html"
		},
		{
			"code": "zh-tw",
			"language": "Chinese (Taiwan)",
			"start": "/content/zh-s/index.html"
		},
		{
			"code": "cv",
			"language": "Chuvash",
			"start": "/content/aus/index.html"
		},
		{
			"code": "co",
			"language": "Corsican",
			"start": "/content/aus/index.html"
		},
		{
			"code": "cr",
			"language": "Cree",
			"start": "/content/aus/index.html"
		},
		{
			"code": "hr",
			"language": "Croatian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "cs",
			"language": "Czech",
			"start": "/content/aus/index.html"
		},
		{
			"code": "da",
			"language": "Danish",
			"start": "/content/aus/index.html"
		},
		{
			"code": "nl",
			"language": "Dutch (Standard)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "nl-be",
			"language": "Dutch (Belgian)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en",
			"language": "English",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-au",
			"language": "English (Australia)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-bz",
			"language": "English (Belize)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-ca",
			"language": "English (Canada)",
			"start": "/content/eng/index.html"
		},
		{
			"code": "en-ie",
			"language": "English (Ireland)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-jm",
			"language": "English (Jamaica)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-nz",
			"language": "English (New Zealand)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-ph",
			"language": "English (Philippines)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-za",
			"language": "English (South Africa)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-tt",
			"language": "English (Trinidad & Tobago)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-gb",
			"language": "English (United Kingdom)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "en-us",
			"language": "English (United States)",
			"start": "/content/eng/index.html"
		},
		{
			"code": "en-zw",
			"language": "English (Zimbabwe)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "eo",
			"language": "Esperanto",
			"start": "/content/aus/index.html"
		},
		{
			"code": "et",
			"language": "Estonian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "fo",
			"language": "Faeroese",
			"start": "/content/aus/index.html"
		},
		{
			"code": "fa",
			"language": "Farsi",
			"start": "/content/aus/index.html"
		},
		{
			"code": "fj",
			"language": "Fijian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "fi",
			"language": "Finnish",
			"start": "/content/aus/index.html"
		},
		{
			"code": "fr",
			"language": "French (Standard)",
			"start": "/content/fra/index.html"
		},
		{
			"code": "fr-be",
			"language": "French (Belgium)",
			"start": "/content/fra/index.html"
		},
		{
			"code": "fr-ca",
			"language": "French (Canada)",
			"start": "/content/fra/index.html"
		},
		{
			"code": "fr-fr",
			"language": "French (France)",
			"start": "/content/fra/index.html"
		},
		{
			"code": "fr-lu",
			"language": "French (Luxembourg)",
			"start": "/content/fra/index.html"
		},
		{
			"code": "fr-mc",
			"language": "French (Monaco)",
			"start": "/content/fra/index.html"
		},
		{
			"code": "fr-ch",
			"language": "French (Switzerland)",
			"start": "/content/fra/index.html"
		},
		{
			"code": "fy",
			"language": "Frisian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "fur",
			"language": "Friulian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "gd",
			"language": "Gaelic (Scots)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "gd-ie",
			"language": "Gaelic (Irish)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "gl",
			"language": "Galacian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ka",
			"language": "Georgian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "de",
			"language": "German (Standard)",
			"start": "/content/deu/index.html"
		},
		{
			"code": "de-at",
			"language": "German (Austria)",
			"start": "/content/deu/index.html"
		},
		{
			"code": "de-de",
			"language": "German (Germany)",
			"start": "/content/deu/index.html"
		},
		{
			"code": "de-li",
			"language": "German (Liechtenstein)",
			"start": "/content/deu/index.html"
		},
		{
			"code": "de-lu",
			"language": "German (Luxembourg)",
			"start": "/content/deu/index.html"
		},
		{
			"code": "de-ch",
			"language": "German (Switzerland)",
			"start": "/content//deu-sh/index.html"
		},
		{
			"code": "el",
			"language": "Greek",
			"start": "/content/aus/index.html"
		},
		{
			"code": "gu",
			"language": "Gujurati",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ht",
			"language": "Haitian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "he",
			"language": "Hebrew",
			"start": "/content/aus/index.html"
		},
		{
			"code": "hi",
			"language": "Hindi",
			"start": "/content/hin/index.html"
		},
		{
			"code": "hu",
			"language": "Hungarian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "is",
			"language": "Icelandic",
			"start": "/content/aus/index.html"
		},
		{
			"code": "id",
			"language": "Indonesian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "iu",
			"language": "Inuktitut",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ga",
			"language": "Irish",
			"start": "/content/aus/index.html"
		},
		{
			"code": "it",
			"language": "Italian (Standard)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "it-ch",
			"language": "Italian (Switzerland)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ja",
			"language": "Japanese",
			"start": "/content/aus/index.html"
		},
		{
			"code": "kn",
			"language": "Kannada",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ks",
			"language": "Kashmiri",
			"start": "/content/aus/index.html"
		},
		{
			"code": "kk",
			"language": "Kazakh",
			"start": "/content/aus/index.html"
		},
		{
			"code": "km",
			"language": "Khmer",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ky",
			"language": "Kirghiz",
			"start": "/content/aus/index.html"
		},
		{
			"code": "tlh",
			"language": "Klingon",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ko",
			"language": "Korean",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ko-kp",
			"language": "Korean (North Korea)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ko-kr",
			"language": "Korean (South Korea)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "la",
			"language": "Latin",
			"start": "/content/aus/index.html"
		},
		{
			"code": "lv",
			"language": "Latvian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "lt",
			"language": "Lithuanian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "lb",
			"language": "Luxembourgish",
			"start": "/content/aus/index.html"
		},
		{
			"code": "mk",
			"language": "FYRO Macedonian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ms",
			"language": "Malay",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ml",
			"language": "Malayalam",
			"start": "/content/aus/index.html"
		},
		{
			"code": "mt",
			"language": "Maltese",
			"start": "/content/aus/index.html"
		},
		{
			"code": "mi",
			"language": "Maori",
			"start": "/content/aus/index.html"
		},
		{
			"code": "mr",
			"language": "Marathi",
			"start": "/content/aus/index.html"
		},
		{
			"code": "mo",
			"language": "Moldavian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "nv",
			"language": "Navajo",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ng",
			"language": "Ndonga",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ne",
			"language": "Nepali",
			"start": "/content/aus/index.html"
		},
		{
			"code": "no",
			"language": "Norwegian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "nb",
			"language": "Norwegian (Bokmal)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "nn",
			"language": "Norwegian (Nynorsk)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "oc",
			"language": "Occitan",
			"start": "/content/aus/index.html"
		},
		{
			"code": "or",
			"language": "Oriya",
			"start": "/content/aus/index.html"
		},
		{
			"code": "om",
			"language": "Oromo",
			"start": "/content/gaz/index.html"
		},
		{
			"code": "fa-ir",
			"language": "Persian/Iran",
			"start": "/content/aus/index.html"
		},
		{
			"code": "pl",
			"language": "Polish",
			"start": "/content/aus/index.html"
		},
		{
			"code": "pt",
			"language": "Portuguese",
			"start": "/content/por/index.html"
		},
		{
			"code": "pt-br",
			"language": "Portuguese (Brazil)",
			"start": "/content/por/index.html"
		},
		{
			"code": "pa",
			"language": "Punjabi",
			"start": "/content/aus/index.html"
		},
		{
			"code": "pa-in",
			"language": "Punjabi (India)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "pa-pk",
			"language": "Punjabi (Pakistan)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "qu",
			"language": "Quechua",
			"start": "/content/aus/index.html"
		},
		{
			"code": "rm",
			"language": "Rhaeto-Romanic",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ro",
			"language": "Romanian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ro-mo",
			"language": "Romanian (Moldavia)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ru",
			"language": "Russian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ru-mo",
			"language": "Russian (Moldavia)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sz",
			"language": "Sami (Lappish)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sg",
			"language": "Sango",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sa",
			"language": "Sanskrit",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sc",
			"language": "Sardinian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sd",
			"language": "Sindhi",
			"start": "/content/aus/index.html"
		},
		{
			"code": "si",
			"language": "Singhalese",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sr",
			"language": "Serbian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sk",
			"language": "Slovak",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sl",
			"language": "Slovenian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "so",
			"language": "Somani",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sb",
			"language": "Sorbian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "es",
			"language": "Spanish",
			"start": "/content/spa/index.html"
		},
		{
			"code": "es-ar",
			"language": "Spanish (Argentina)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-bo",
			"language": "Spanish (Bolivia)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-cl",
			"language": "Spanish (Chile)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-co",
			"language": "Spanish (Colombia)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-cr",
			"language": "Spanish (Costa Rica)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-do",
			"language": "Spanish (Dominican Republic)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-ec",
			"language": "Spanish (Ecuador)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-sv",
			"language": "Spanish (El Salvador)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-gt",
			"language": "Spanish (Guatemala)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-hn",
			"language": "Spanish (Honduras)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-mx",
			"language": "Spanish (Mexico)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-ni",
			"language": "Spanish (Nicaragua)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-pa",
			"language": "Spanish (Panama)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-py",
			"language": "Spanish (Paraguay)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-pe",
			"language": "Spanish (Peru)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-pr",
			"language": "Spanish (Puerto Rico)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-es",
			"language": "Spanish (Spain)",
			"start": "/content/spa/index.html"
		},
		{
			"code": "es-uy",
			"language": "Spanish (Uruguay)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "es-ve",
			"language": "Spanish (Venezuela)",
			"start": "/content/spa-la/index.html"
		},
		{
			"code": "sx",
			"language": "Sutu",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sw",
			"language": "Swahili",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sv",
			"language": "Swedish",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sv-fi",
			"language": "Swedish (Finland)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "sv-sv",
			"language": "Swedish (Sweden)",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ta",
			"language": "Tamil",
			"start": "/content/tam/index.html"
		},
		{
			"code": "tt",
			"language": "Tatar",
			"start": "/content/aus/index.html"
		},
		{
			"code": "te",
			"language": "Teluga",
			"start": "/content/aus/index.html"
		},
		{
			"code": "th",
			"language": "Thai",
			"start": "/content/aus/index.html"
		},
		{
			"code": "tig",
			"language": "Tigre",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ts",
			"language": "Tsonga",
			"start": "/content/aus/index.html"
		},
		{
			"code": "tn",
			"language": "Tswana",
			"start": "/content/aus/index.html"
		},
		{
			"code": "tr",
			"language": "Turkish",
			"start": "/content/aus/index.html"
		},
		{
			"code": "tk",
			"language": "Turkmen",
			"start": "/content/aus/index.html"
		},
		{
			"code": "uk",
			"language": "Ukrainian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "hsb",
			"language": "Upper Sorbian",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ur",
			"language": "Urdu",
			"start": "/content/urd/index.html"
		},
		{
			"code": "ve",
			"language": "Venda",
			"start": "/content/aus/index.html"
		},
		{
			"code": "vi",
			"language": "Vietnamese",
			"start": "/content/aus/index.html"
		},
		{
			"code": "vo",
			"language": "Volapuk",
			"start": "/content/aus/index.html"
		},
		{
			"code": "wa",
			"language": "Walloon",
			"start": "/content/aus/index.html"
		},
		{
			"code": "cy",
			"language": "Welsh",
			"start": "/content/aus/index.html"
		},
		{
			"code": "xh",
			"language": "Xhosa",
			"start": "/content/aus/index.html"
		},
		{
			"code": "ji",
			"language": "Yiddish",
			"start": "/content/aus/index.html"
		},
		{
			"code": "zu",
			"language": "Zulu",
			"start": "/content/aus/index.html"
		}
	],

';
}
 
//This returns the most preferred langauage "q=1"
;
$pref =  getPreferredLanguage();
$output .= '"preference": "'. $pref . '"
}';

echo $output;