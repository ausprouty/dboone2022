import Vue from 'vue'
import Vuex from 'vuex'
import { mapState } from 'vuex'
import LogService from '@/services/LogService.js'
import AuthorService from '@/services/AuthorService.js'
//import { timeout } from 'q'
Vue.use(Vuex)

export const libraryUploadMixin = {
  computed: mapState(['user']),
  methods: {
    async handleImageUpload(code) {
      LogService.consoleLogMessage('handleImageUpload: ' + code)
      var checkfile = {}
      var i = 0
      var arrayLength = this.$refs.image.length
      for (i = 0; i < arrayLength; i++) {
        checkfile = this.$refs.image[i]['files']
        if (checkfile.length == 1) {
          LogService.consoleLogMessage('checkfile')
          LogService.consoleLogMessage(checkfile)
          LogService.consoleLogMessage(checkfile[0])
          LogService.consoleLogMessage(checkfile[0].name)
          var filename = checkfile[0].name
          var type = AuthorService.typeImage(checkfile[0])
          if (type) {
            var params = {}
            params.directory = 'content/' + this.bookmark.language.image_dir
            params.name = code
            await AuthorService.imageStore(params, checkfile[0])
            // now update data on form
            LogService.consoleLogMessage('code is  ' + code)
            for (i = 0; i < arrayLength; i++) {
              checkfile = this.$v.books.$each[i]
              //              LogService.consoleLogMessage('checkfile.$model.code: ' + checkfile.$model.code)
              if (checkfile.$model.code == code) {
                this.$v.books.$each[i].$model.image.title = filename
                this.$v.books.$each[i].$model.image.image =
                  '/' + params.directory + '/' + filename

                LogService.consoleLogMessage('trying to assign ' + filename)
              }
            }
            await this.saveForm('stay')
            this.showForm()
          }
        }
      }
    },

    async handleStyleUpload(code) {
      LogService.consoleLogMessage('code in handle Style:' + code)
      var checkfile = ''
      var i = 0
      var arrayLength = this.$refs.style.length
      LogService.consoleLogMessage(this.$refs.style)
      for (i = 0; i < arrayLength; i++) {
        checkfile = this.$refs.style[i]['files']
        if (checkfile.length == 1) {
          LogService.consoleLogMessage(checkfile[0])
          var type = AuthorService.typeStyle(checkfile[0])
          if (type) {
            LogService.consoleLogMessage(checkfile)
            var params = {}
            params.file = checkfile[0]
            params.country_code = this.$route.params.country_code
            type = await AuthorService.createStyle(params)
            var style = await AuthorService.getStyles(params)
            if (style) {
              this.styles = style
              this.style_error = false
            }
          } else {
            this.style_error = true
          }
        }
      }
    },
    async handleTemplateUpload(code) {
      LogService.consoleLogMessage('code in handle Template:' + code)
      var checkfile = ''
      var i = 0
      var arrayLength = this.$refs.template.length
      LogService.consoleLogMessage(this.$refs.template)
      for (i = 0; i < arrayLength; i++) {
        checkfile = this.$refs.template[i]['files']
        if (checkfile.length == 1) {
          LogService.consoleLogMessage(' i is ' + i)
          LogService.consoleLogMessage(checkfile[0])
          var type = AuthorService.typeTemplate(checkfile[0])
          if (type) {
            LogService.consoleLogMessage('type ok')
            LogService.consoleLogMessage(checkfile)
            var params = {}
            params.file = checkfile[0]
            params.country_code = this.$route.params.country_code
            params.language_iso = this.$route.params.language_iso
            params.folder_name = code
            LogService.consoleLogMessage(params)
            type = await AuthorService.createTemplate(params)
            if (type) {
              var template = await AuthorService.getTemplates(params)
              if (template) {
                this.templates = template
                LogService.consoleLogMessage(template)
                this.template_error = false
              }
            }
          } else {
            this.template_error = true
          }
        }
      }
    },
  }

}