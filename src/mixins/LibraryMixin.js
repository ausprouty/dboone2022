import AuthorService from '@/services/AuthorService.js'
import ContentService from '@/services/ContentService.js'
import LogService from '@/services/LogService.js'
import { mapState } from 'vuex'
export const libraryMixin = {
  //  computed: mapState(['bookmark']),
  data() {
    return {
      library: [
        {
          id: '',
          code: '',
          title: '',
          folder: '',
          index: '',
          style: process.env.VUE_APP_SITE_STYLE,
          image: 'issues.jpg',
          format: 'series',
        },
      ],
      image_dir: 'sites/default/images',
      loading: false,
      loaded: '',
      error: '',
      error_message: '',
      rldir: 'ltr',
      prototype: false,
      prototype_date: null,
      books: [],
      write: false,
      publish: false,
      publish_date: '',
      recnum: '',
      content: {
        recnum: '',
        version: '',
        edit_date: '',
        edit_uid: '',
        publish_uid: '',
        publish_date: '',
        language_iso: '',
        country_code: '',
        folder_name: '',
        filetype: '',
        title: '',
        filename: '',
        text: '',
      },
    }
  },
  computed: mapState(['user', 'bookmark']),
  methods: {
    async getLibrary() {
      LogService.consoleLogMessage('started getLibrary in LibraryMixin')
      try {
        this.error = this.loaded = ''
        this.loading = true
        console.log('in getLibrary in Library Mixin')
        console.log(this.$route.params)
        if (!this.$route.params.library_code) {
          this.$route.params.library_code = 'library'
        } else {
          if (this.$route.params.library_code.includes('.html')) {
            this.$route.params.library_code =
              this.$route.params.library_code.slice(0, -5)
            console.log('assigned: ' + this.$route.params.library_code)
          }
        }
        console.log('about to get Library with:')
        console.log(this.$route.params)
        console.log('as')
        console.log(this.user)
        var params = this.$route.params
        var response = await ContentService.getLibrary(params)
        console.log('response from Get Library')
        console.log(response)
        if (typeof response.text == 'undefined') {
          response.text = ''
          response.text.text = ''
        }
        this.text = response.text.text ? response.text.text : ''
        if (response.recnum) {
          this.recnum = response.recnum
          this.publish_date = response.publish_date
          this.prototype_date = response.prototype_date
        } else {
          this.recnum = this.publish_date = this.prototype_date = ''
        }
        console.log('about to check bookmarks with:')
        console.log(params)
        console.log('as')
        console.log(this.user)
        await this.CheckBookmarks(params)
        this.image_dir = process.env.VUE_APP_SITE_IMAGE_DIR

        if (typeof this.bookmark.language.image_dir != 'undefined') {
          // LogService.consoleLogMessage('get Library is using Bookmark')
          console.log(this.bookmark.language.image_dir)
          this.image_dir = this.bookmark.language.image_dir
        }
        this.rldir = this.bookmark.language.rldir
      } catch (error) {
        LogService.consoleLogError('There was an error in LibraryMixin:', error)
        this.newLibrary()
      }
    },
    async getImagesInContentDirectory(directory) {
      // get images for library header
      var options = []
      var param = {}
      param.route = JSON.stringify(this.$route.params)
      param.image_dir = directory
      var img = await AuthorService.getImagesInContentDirectory(param)
      if (typeof img !== 'undefined') {
        if (img.length > 0) {
          img = img.sort()
          var length = img.length
          var i = 0
          for (i = 0; i < length; i++) {
            var formatted = {}
            formatted.title = img[i]
            formatted.image = directory + '/' + img[i]
            options.push(formatted)
          }
        }
      }
      LogService.consoleLogMessage(
        'from getImagesInContentDirectory for ' + directory
      )
      LogService.consoleLogMessage(options)
      return options
    },
    async getLibraryIndex() {
      this.error = this.loaded = null
      this.loading = true
      this.recnum = null
      this.publish_date = null
      await this.UnsetBookmarks()
      await this.CheckBookmarks(this.$route.params)
      var response = await ContentService.getLibraryIndex(this.$route.params)
      if (response) {
        if (response.recnum) {
          this.recnum = response.recnum
          this.publish_date = response.publish_date
          this.prototype_date = response.prototype_date
        }
        var text = response.text
        this.pageText = text.page
        this.style = text.style
        this.footerText = text.footer
      } else {
        this.pageText = ''
        this.style = ''
        this.footerText = ''
      }

      this.loaded = true
      this.loading = false
    },
    newLibrary() {
      this.books = [
        {
          id: 1,
          code: 'life',
          title: 'Life Principles',
          image: 'life.jpg',
          format: 'series',
          style: process.env.VUE_APP_SITE_STYLE,
        },
        {
          id: 2,
          code: 'basics',
          title: 'Basic Conversations',
          image: 'basics.jpg',
          format: 'series',
          style: process.env.VUE_APP_SITE_STYLE,
        },
        {
          id: 3,
          code: 'community',
          title: 'Live Community',
          image: 'community.jpg',
          format: 'page',
          page: 'community',
          style: process.env.VUE_APP_SITE_STYLE,
        },
        {
          id: 4,
          code: 'steps',
          title: 'First Steps',
          image: 'firststeps.jpg',
          format: 'series',
          style: process.env.VUE_APP_SITE_STYLE,
        },
        {
          id: 5,
          code: 'compass',
          title: 'Compass',
          image: 'compass.jpg',
          format: 'series',
          style: process.env.VUE_APP_SITE_STYLE,
        },
        {
          id: 6,
          code: 'about',
          title: 'About MyFriends',
          image: 'about.jpg',
          format: 'page',
          page: 'community',
          style: process.env.VUE_APP_SITE_STYLE,
        },
      ]
    },
  },
}
