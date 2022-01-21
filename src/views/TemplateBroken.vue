<template>
  <div>
    <NavBar called_by="template" />

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
        <BaseInput
          label="Filename:"
          v-model="template_filename"
          type="text"
          class="field"
        />

        <h2>Shortcuts you can use in templates</h2>
        <ul>
          <li>[BiblePassage]</li>
        </ul>

        <vue-ckeditor v-model="pageText" :config="config" />

        <div class="version">
          <p class="version">Version 2.05 {{ this.languageDirectory }}</p>
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
import NavBar from '@/components/NavBarAdmin.vue'
import './ckeditor/index.js'
import VueCkeditor from 'vue-ckeditor2'

import { pageMixin } from '@/mixins/PageMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
export default {
  mixins: [pageMixin, authorizeMixin],
  props: [
    'country_code',
    'language_iso',
    'library_code',
    'title',
    'template',
    'cssFORMATTED',
    'book_code',
    'book_format',
  ],
  components: {
    NavBar,
    VueCkeditor,
  },
  computed: mapState(['bookmark', 'cssURL', 'ckEditStyleSets']),
  data() {
    return {
      authorized: false,
      content: {},
      template_filename: this.$route.params.template,
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
        extraAllowedContent: 'ol(*)',
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
      var res = await AuthorService.getTemplate(this.$route.params)
      if (res) {
        this.pageText = res
      }
    },
    async saveForm() {
      try {
        this.content.text = ContentService.validate(this.pageText)
        LogService.consoleLogMessage(this.template_filename)
        //remove any dots for safety
        //  var safe_name = this.template_filename.replace('.', '')
        var safe_name = this.template_filename
        var params = this.$route.params
        params.text = this.pageText
        if (this.$route.params.book_format == 'series') {
          params.series = this.$route.params.book_code
        }
        params.template = safe_name
        LogService.consoleLogMessage(params)
        await AuthorService.editTemplate(params)
        this.$router.push({
          name: 'editLibrary',
          params: {
            country_code: this.$route.params.country_code,
            language_iso: this.$route.params.language_iso,
            library_code: this.$route.params.library_code,
          },
        })
      } catch (error) {
        LogService.consoleLogError('LIBRARY EDIT There was an error ', error)
        this.error = true
        this.loaded = false
        this.error_message = error
      }
    },
  },
  async beforeCreate() {
    if (this.$route.params.styles_set == '') {
      this.$route.params.styles_set = process.env.VUE_APP_SITE_STYLES_SET
    }
    this.$route.params.version = 'lastest'
    var css = this.$route.params.cssFORMATTED
    var clean = css.replace(/-/g, '/')
    this.$route.params.css = clean
    LogService.consoleLogMessage(this.$route.params)
    // set directory for custom images
    //see https://ckeditor.com/docs/ckfinder/ckfinder3-php/integration.html
    this.languageDirectory =
      '/content/' +
      this.$route.params.country_code +
      '/' +
      this.$route.params.language_iso +
      '/images/custom'
  },
  async created() {
    try {
      this.authorized = this.authorize('write', this.$route.params)
      LogService.consoleLogMessage('here is my language directory')
      LogService.consoleLogMessage(this.languageDirectory)
      await this.loadTemplate()
      this.template_filename = this.$route.params.template
      this.loaded = true
      this.loading = false
    } catch (error) {
      LogService.consoleLogError('There was an error in Template.vue:', error)
    }
  },
}
</script>
