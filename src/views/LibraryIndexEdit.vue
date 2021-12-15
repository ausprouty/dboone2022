<template>
  <div>
    <NavBar />

    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">
      There was an error... {{ this.error_message }}
    </div>
    <div class="content" v-if="loaded">
      <div v-if="!this.authorized">
        <p>
          You have stumbled into a restricted page. Sorry I can not show it to
          you now
        </p>
      </div>
      <div v-if="this.authorized">
        <h1>
          {{ this.bookmark.language.name }} -- {{ this.bookmark.country.name }}
          <a
            target="_blank"
            class="help"
            v-bind:href="
              this.prototype_url + 'HD/eng/help-1/library_index_preview.html'
            "
          >
            <img class="help-icon" src="/sites/default/images/icons/help.png" />
          </a>
        </h1>
        <p>
          <vue-ckeditor v-model="pageText" :config="config" />
        </p>
        <hr />
        <h2>Page Style</h2>
        <BaseSelect :options="styles" v-model="style" class="field" />
        <template v-if="style_error">
          <p class="errorMessage">Only .css files may be uploaded</p>
        </template>

        <label>
          Add new stylesheet&nbsp;&nbsp;&nbsp;&nbsp;
          <input
            type="file"
            v-bind:id="style"
            ref="style"
            v-on:change="handleStyleUpload()"
          />
        </label>
        <br />
        <br />
        <hr />
        <h2>Language Footer Text</h2>
        <p>
          <vue-ckeditor v-model="footerText" :config="config" />
        </p>
        <div class="version">
          <p class="version">Version 2.05</p>
        </div>
        <button class="button red" @click="saveForm">Save Changes</button>
      </div>
      <div v-if="!this.authorized">
        <p>
          You need to
          <a href="/login">login to make changes</a> here
        </p>
      </div>
      <div></div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import ContentService from '@/services/ContentService.js'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import NavBar from '@/components/NavBarCountry.vue'
import './ckeditor/index.js'
import VueCkeditor from 'vue-ckeditor2'
import { bookMarkMixin } from '@/mixins/BookmarkMixin.js'
import { libraryMixin } from '@/mixins/LibraryMixin.js'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  mixins: [bookMarkMixin, libraryMixin, authorMixin],
  props: ['country_code', 'language_iso'],
  components: {
    NavBar,
    VueCkeditor,
  },
  computed: mapState(['bookmark', 'cssURL']),
  data() {
    return {
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      authorized: false,
      style_error: false,
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
      config: {
        extraPlugins: [
          'bidi',
          'uploadimage',
          'iframe',
          'uploadwidget',
          'clipboard',
          'videoembed',
          'templates',
          'panelbutton',
          'floatpanel',
          'colorbutton',
          'justify',
        ],
        extraAllowedContent: [
          '*(*)[id]',
          'ol[*]',
          'span[*]',
          'align[*]',
          'webkitallowfullscreen',
          'mozallowfullscreen',
          'allowfullscreen',
        ],
        contentsCss: this.$route.params.css,
        stylesSet: this.$route.params.styles_set,
        templates_replaceContent: false,
        templates_files: [
          '/sites/' +
            process.env.VUE_APP_SITE +
            '/ckeditor/templates/' +
            this.$route.params.styles_set +
            '.js',
        ],
        // Configure your file manager integration. This example uses CKFinder 3 for PHP.
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/howto.html#howto_private_folders
        filebrowserBrowseUrl:
          process.env.VUE_APP_SITE_CKFINDER_URL + 'ckfinder.html',
        filebrowserUploadUrl:
          process.env.VUE_APP_SITE_CKFINDER_URL +
          'core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=' +
          this.languageDirectory,

        // end Configuration
        toolbarGroups: [
          { name: 'styles', groups: ['styles'] },
          { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
          {
            name: 'editing',
            groups: ['find', 'selection', 'spellchecker', 'editing'],
          },
          { name: 'links', groups: ['links'] },
          { name: 'insert', groups: ['insert'] },
          { name: 'forms', groups: ['forms'] },
          { name: 'tools', groups: ['tools'] },
          { name: 'document', groups: ['mode', 'document', 'doctools'] },
          { name: 'clipboard', groups: ['clipboard', 'undo'] },
          { name: 'others', groups: ['others'] },
          '/',
          {
            name: 'paragraph',
            groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'],
          },
          { name: 'colors', groups: ['colors'] },
          { name: 'about', groups: ['about'] },
        ],
        height: 600,
        removeButtons:
          'About,Button,Checkbox,CreatePlaceholder,DocProps,Flash,Form,HiddenField,Iframe,NewPage,PageBreak,Preview,Print,Radio,Save,Scayt,Select,Smiley,SpecialChar,TextField,Textarea',
      },
    }
  },
  methods: {
    goBack() {
      window.history.back()
    },
    async loadTemplate() {
      this.authorized = this.authorize('write', this.$route.params)
      this.loading = false
      this.loaded = true
      if (this.bookmark.book.template) {
        this.$route.params.template = this.bookmark.book.template
        LogService.consoleLogMessage('looking for template')
        LogService.consoleLogMessage(this.$route.params)
        var res = await AuthorService.getTemplate(this.$route.params)
        LogService.consoleLogMessage(res)
        if (res) {
          LogService.consoleLogMessage('I found template')
          this.pageText = res
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
    async saveForm() {
      try {
        var text = {}
        text.page = ContentService.validate(this.pageText)
        text.footer = ContentService.validate(this.footerText)
        text.style = this.style
        this.content.text = JSON.stringify(text)
        this.$route.params.filename = 'index'
        this.content.route = JSON.stringify(this.$route.params)

        this.content.filetype = 'html'
        //this.$store.dispatch('newBookmark', 'clear')
        var response = await AuthorService.createContentData(this.content)
        if (response.data.error != true) {
          this.$router.push({
            name: 'previewLibraryIndex',
            params: {
              country_code: this.$route.params.country_code,
              language_iso: this.$route.params.language_iso,
            },
          })
        } else {
          this.error = true
          this.loaded = false
          this.error_message = response.data.message
        }
      } catch (error) {
        LogService.consoleLogError(
          'COUNTRY PAGE EDIT There was an error ',
          error
        )
        this.error = true
        this.loaded = false
        this.error_message = error
      }
    },
  },
  async beforeCreate() {
    LogService.consoleLogMessage('before Create in LibraryIndexEdit')
    LogService.consoleLogMessage(this.$route.params)
    // set directory for custom images
    //see https://ckeditor.com/docs/ckfinder/ckfinder3-php/integration.html
    this.languageDirectory =
      '/content/' +
      this.$route.params.country_code +
      '/' +
      this.$route.params.language_iso +
      '/images/custom'

    this.$route.params.styles_set = process.env.VUE_APP_SITE_STYLES_SET
    this.$route.params.version = 'lastest'
    this.$route.params.filename = 'index'
    this.$route.params.library_code = 'index'
    this.$route.params.css = '/sites/default/styles/freeformGLOBAL.css'
    LogService.consoleLogMessage('final params')
    LogService.consoleLogMessage(this.$route.params)
  },
  async created() {
    try {
      LogService.consoleLogMessage('in Created')
      LogService.consoleLogMessage(this.$route)
      await this.getLibraryIndex()
      // get styles
      var param = {}
      param.route = JSON.stringify(this.$route.params)
      var style = await AuthorService.getStyles(param)
      if (typeof style !== 'undefined') {
        this.styles = style
      }

      this.authorized = this.authorize('write', this.$route.params)
      this.publish = false
      if (this.recnum && !this.publish_date) {
        this.publish = this.authorize('publish', this.$route.params)
      }
    } catch (error) {
      LogService.consoleLogError(
        'There was an error in LanguageIndexEdit.vue:',
        error
      )
    }
  },
}
</script>
