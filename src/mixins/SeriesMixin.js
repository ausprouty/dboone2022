import AuthorService from '@/services/AuthorService.js'
import ContentService from '@/services/ContentService.js'
import LogService from '@/services/LogService.js'

export const seriesMixin = {
  data() {
    return {
      seriesDetails: {
        series: '',
        language: '',
        description: '',
        download_now: 'Download for offline use',
        download_ready: 'Ready for offline use',
      },
      chapter: {},
      chapters: [
        {
          id: '',
          title: '',
          desciption: '',
          count: '',
          filename: '',
        },
      ],
      rldir: 'ltr',
      image_dir: '/sites/default/images/',
      series_image_dir: '/sites/default/images/',
      loading: false,
      loaded: null,
      error: null,
      error_message: null,
      prototype: false,
      prototype_date: null,
      prototype_series: false,
      publish: false,
      publish_date: null,
      download_now: 'Download for offline use',
      download_ready: 'Ready for offline use',
      recnum: null,
      content: {
        recnum: '',
        version: '',
        edit_date: '',
        edit_uid: '',
        publish_uid: '',
        publish_date: '',
        language_iso: '',
        country_code: '',
        folder: '',
        filetype: '',
        title: '',
        filename: '',
        text: '',
      },
    }
  },
  methods: {
    async getSeries(params) {
      try {
        LogService.consoleLogMessage('params in SeriesMixin ')
        LogService.consoleLogMessage(params)
        this.error = this.loaded = null
        this.loading = true
        await AuthorService.bookmark(params)
      } catch (error) {
        LogService.consoleLogError(
          'There was an error withcheckBookmarks in CountriesMixin:',
          error
        )
      }
      try {
        var response = await ContentService.getSeries(params)

        if (typeof response == 'undefined') {
          LogService.consoleLogMessage('No Series Data obtained')
          this.chapters = []
          this.new = true
          this.description = null
          this.loaded = true
          this.loading = false
          return
        }
        if (typeof response.text != 'undefined') {
          LogService.consoleLogMessage('Series Data obtained')
          LogService.consoleLogMessage(response)
          // latest data
          if (typeof response.recnum != 'undefined') {
            this.recnum = response.recnum
            this.publish_date = response.publish_date
            this.prototype_date = response.prototype_date
          }
          LogService.consoleLogMessage(response.text)
          // this.seriesDetails = JSON.parse(response.text)
          this.seriesDetails = response.text
          LogService.consoleLogMessage('Series Details')
          LogService.consoleLogMessage(this.seriesDetails)
          this.chapters = this.seriesDetails.chapters
          this.description = this.seriesDetails.description
          this.download_now = this.seriesDetails.download_now
          this.download_ready = this.seriesDetails.download_ready
        } else {
          this.description = response.description
          this.chapters = response.chapters
        }
        //TODO: evaluate if we want to always be able to add with tab delimited file
        //this.new = false
        //if (!this.chapters) {
        this.new = true
        // }

        this.image_dir = process.env.VUE_APP_SITE_IMAGE_DIR
        if (typeof this.bookmark.language.image_dir != 'undefined') {
          LogService.consoleLogMessage('USING BOOKMARK')
          this.image_dir = this.bookmark.language.image_dir
        }
        console.log('look at vue app site dir')
        console.log(process.env.VUE_APP_SITE_DIR)
        if (typeof this.bookmark.book.image.image != 'undefined') {
          this.book_image =  this.bookmark.book.image.image
        }

        this.style = process.env.VUE_APP_SITE_STYLE
        if (typeof this.bookmark.book.style != 'undefined') {
          LogService.consoleLogMessage('USING BOOKMARK')
          this.style = this.bookmark.book.style
        }
        this.rldir = this.bookmark.language.rldir
        LogService.consoleLogMessage('this.image_dir')
        LogService.consoleLogMessage(this.image_dir)
        this.loaded = true
        this.loading = false
        LogService.consoleLogMessage('finished with get Series')
      } catch (error) {
        LogService.consoleLogError('There was an error in SeriesMixin:', error) // Logs out the error
      }
    },

    newSeries() {
      return
    },
  },
}
