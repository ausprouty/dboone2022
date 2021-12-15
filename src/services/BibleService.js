import AuthorService from '@/services/AuthorService.js'

export default {
  async bibleLinkMaker(params) {
    params.page = 'bibleLinkMaker'
    params.action = 'bibleLinkMaker'
    console.log(params)
    var res = await AuthorService.aReturnContent(params)
    return res
  },
  async biblePopupMaker(params) {
    params.page = 'biblePopupMaker'
    params.action = 'biblePopupMaker'
    console.log(params)
    var res = await AuthorService.aReturnContent(params)
    return res
  },
  async getBibleVersions(params) {
    params.page = 'bibleVersions'
    params.action = 'getBibleVersions'
    var res = await AuthorService.aReturnContent(params)
    return res
  },

  async getDbtArray(params) {
    /* requires params.language_iso
      and params.entry in form of 'Zephaniah 1:2-3'

    returns:
      params.dbt = array(
      'entry' => 'Zephaniah 1:2-3'
      'bookId' => 'Zeph',
      'chapterId' => 1,
      'verseStart' => 2,
      'verseEnd' => 3,
      'collection_code' => 'OT' ,
     );
  */
    params.page = 'bibleDbtArray'
    params.action = 'createBibleDbtArrayFromPassage'
    var res = await AuthorService.aReturnContent(params)
    console.log(res)
    return res
  },

  async getBiblePassage(params) {
    /*
  expects
    params.dbt = array(
      'entry' => 'Zephaniah 1:2-3'
      'bookId' => 'Zeph',
      'chapterId' => 1,
      'verseStart' => 2,
      'verseEnd' => 3,
      'collection_code' => 'OT' ,
     );
     params.bid

  Returns:
     "debug":
    "link": "https:\/\/biblegateway.com\/passage\/?search=Nehemiah%201:1-11&version=NIV",
		"passage_name": "Nehemiah 1",
		"bible_name": "New International Version",
		"bible": "\n<!-- begin bible --><div class = \"bible\">	"publisher": "\n<!-- begin publisher --><div class= \"publisher\"> <\/div><!-- end publisher -->\n",
		"content": {
			"reference": "Nehemiah 1",
      "text": "\n<!-- begin bible -->
      		"link": "https:\/\/biblegateway.com\/passage\/?search=Nehemiah%201:1-11&version=NIV"
		}
  */
    params.page = 'bibleGetPassage'
    params.action = 'bibleGetPassage'
    return await AuthorService.aReturnContent(params)
  },
}
