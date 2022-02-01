import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
export const pageMixin = {
  data() {
    return {
      error: null,
      header: 'series',
      image_navigation: null,
      image_navigation_class: 'book',
      image_navigation_dir: null,
      image_page: null,
      image_page_class: 'book',
      image_page_dir: 'book',
      loading: false,
      loaded: null,
      navigation_title: null,
      need_template: false,
      pageText: '',
      pdf: false,
      publish: false,
      publish_date: null,
      prototype: false,
      prototype_date: null,
      read: false,
      recnum: null,
      rldir: 'ltr',
      sdcard: false,
      show_page_image: false,
      show_page_title: false,
      show_navigation_title: false,
      write: false,
    }
  },
  methods: {
    async getPageorTemplate(which) {
      this.error = this.loaded = null
      this.loading = true
      this.countries = []
      var bmark = await this.pageCheckBookmarks()
      await this.setImagesAndLinks()
      try {
        if (typeof bmark.langauge !== undefined) {
          this.rldir = bmark.language.rldir
        }
        // get page content
        var params = this.$route.params
        var response = ''
        LogService.consoleLogMessage(
          'getPageorTemplate looking for page with these parameters'
        )
        LogService.consoleLogMessage(params)
        if (which == 'either') {
          params.bookmark = JSON.stringify(this.bookmark)
          response = await AuthorService.getPageOrTemplate(params)
        } else {
          response = await AuthorService.getPage(params)
        }
        LogService.consoleLogMessage('response from getPage')
        LogService.consoleLogMessage(response)
        // has this page been prototyped or published?
        if (response.recnum) {
          this.recnum = response.recnum
          this.publish_date = response.publish_date
          this.prototype_date = response.prototype_date
        }
        this.pageText = response.text

        // bring up passage if needed
        if (this.pageText.includes('[BiblePassage]')) {
          var len = this.bookmark.series.chapters.length
          console.log('Page includes [BiblePassage]')
          for (var i = 0; i < len; i++) {
            if (
              this.bookmark.series.chapters[i].filename ==
              this.$route.params.filename
            ) {
              if (
                typeof this.bookmark.series.chapters[i].reference != 'undefined'
              ) {
                var str = this.bookmark.series.chapters[i].reference
                this.reference = str.replace(' : ', ':')
                console.log(this.reference)
              } else {
                console.log('Unable to find Bible reference for outline')
                this.reference = null
              }
            }
          }
        }

        this.loaded = true
        this.loading = false
      } catch (error) {
        LogService.consoleLogError(
          'There was an error and no page was found',
          '-- from PageMixin'
        )
        if (this.$route.name != 'editPage') {
          var css = this.bookmark.page.style
            ? this.bookmark.page.style
            : process.env.VUE_APP_SITE_STYLE
          css.replace('/', '@')
          this.$route.params.cssFORMATTED = css
          this.need_template = true
          this.$router.push({
            name: 'editPage',
            params: this.$route.params,
          })
        } else {
          this.pageText = 'Enter Text'
          this.loaded = true
          this.loading = false
        }
      }
    },

    async pageCheckBookmarks() {
      try {
        //await this.UnsetBookmarks()
        var bmark = await AuthorService.bookmark(this.$route.params)
        return bmark
      } catch (error) {
        LogService.consoleLogError(
          'There was an error withcheckBookmarks in PageMixin:',
          error
        )
      }
    },

    async setImagesAndLinks() {
      /* If it is part of a series, you will want to put the book in this area
      If it is a book you will put the library here.
     */
      if (this.bookmark.book.format == 'series') {
        LogService.consoleLogMessage('I am looking at a series')
        // image
        this.image_navigation = process.env.VUE_APP_SITE_IMAGE
        if (typeof this.bookmark.book !== 'undefined') {
          this.image_navigation = this.bookmark.book.image.image
        }

        //directory
        this.image_navigation_dir = process.env.VUE_APP_SITE_IMAGE_DIR
        if (typeof this.bookmark.language !== 'undefined') {
          this.image_navigation_dir = this.bookmark.language.image_dir
        }
        // class
        this.image_book_class = 'book'
        // show title
        this.show_navigation_title = true
        if (this.bookmark.language.titles) {
          this.show_navigation_title = false
        }
        if (this.show_navigation_title) {
          this.navigation_title = this.bookmark.book.title
        }

        this.style = process.env.VUE_APP_SITE_STYLE
        if (this.bookmark.book) {
          this.style = this.bookmark.book.style
        }
        // Set page title and/or image
        this.show_page_title = true
        this.image_page_class = 'something'
        this.image_page_dir =
          this.bookmark.language.folder + '/' + this.bookmark.book.name
        if (typeof this.bookmark.page.image !== 'undefined') {
          if (this.bookmark.page.image !== null) {
            if (typeof this.bookmark.page.image.image !== 'undefined') {
              this.image_page = this.bookmark.page.image.image
              this.show_page_title = false
              this.show_page_image = true
            }
          }
        }

        return
      }
      //
      // if it is not a series
      //
      if (this.bookmark.book.format == 'page') {
        LogService.consoleLogMessage('I am looking at a page')
        // image
        this.image_navigation = process.env.VUE_APP_SITE_IMAGE
        if (typeof this.bookmark.library.format.image !== 'undefined') {
          this.image_navigation = this.bookmark.library.format.image.image
        }
        this.image_navigation_class = 'book'
        // show title
        this.show_navigation_title = false
        //style
        this.style = process.env.VUE_APP_SITE_STYLE
        if (this.bookmark.book) {
          this.style = this.bookmark.book.style
        }
        // Set page title and/or image
        this.image_page = this.bookmark.book.image.image
        this.image_page_class = 'something'
        this.image_page_dir = ''
        this.show_page_image = true
        this.show_page_title = true
        this.show_navigation_title = true
      }
    },
  },
}
