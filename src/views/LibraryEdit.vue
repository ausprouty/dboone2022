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
        <p>
          You need to
          <a href="/login">login to make changes</a> here
        </p>
      </div>
      <div v-if="this.authorized">
        <h1>
          Library
          <a
            target="_blank"
            class="help"
            v-bind:href="this.prototype_url + 'HD/eng/help-1/library_edit.html'"
          >
            <img class="help-icon" src="/sites/default/images/icons/help.png" />
          </a>
        </h1>
        <div v-if="images">
          <img v-bind:src="this.header_image" class="header" />
          <br />
        </div>
        <br />
        <br />

        <LibraryFormatTemplate v-bind:format="library_format" />

        <hr />
        <h2>Preliminary Text</h2>

        <p>
          <vue-ckeditor v-model="text" :config="config" />
        </p>
      </div>
      <br />
      <hr />

      <br />
      <h2>Books</h2>
      <div>
        <button class="button" @click="prototypeAll">
          Select ALL to prototype
        </button>
        <button class="button" @click="publishAll">
          Select ALL to publish
        </button>

        <button class="button" @click="prototypeNone">
          Do NOT prototype ANY
        </button>
        <button class="button" @click="publishNone">Do NOT publish ANY</button>
      </div>
      <div v-for="(book, id) in $v.books.$each.$iter" :key="id" :book="book">
        <div
          class="app-card -shadow"
          v-bind:class="{ notpublished: !book.publish.$model }"
        >
          <div
            class="float-right"
            style="cursor: pointer"
            @click="deleteBookForm(id)"
          >
            X
          </div>
          <h2>Name and Code</h2>
          <div>
            <BaseInput
              v-model="book.id.$model"
              label="Book Number:"
              type="text"
              placeholder="#"
              class="field"
              :class="{ error: book.id.$error }"
              @blur="book.id.$touch()"
            />
          </div>
          <div>
            <BaseInput
              v-model="book.title.$model"
              label="Title:"
              type="text"
              placeholder="Title"
              class="field"
              :class="{ error: book.title.$error }"
              @blur="book.title.$touch()"
            />
            <template v-if="book.title.$error">
              <p v-if="!book.title.required" class="errorMessage">
                Book Title is required
              </p>
            </template>
          </div>
          <div>
            <BaseSelect
              label="Code:"
              :options="bookcodes"
              v-model="book.code.$model"
              class="field"
              :class="{ error: book.code.$error }"
              @mousedown="book.code.$touch()"
            />
          </div>
          <div>
            <p>
              <a class="black" @click="createBook(book.code.$model)"
                >Create new Code</a
              >
            </p>
          </div>
          <div
            v-bind:id="book.title.$model"
            v-bind:class="{ hidden: isHidden }"
          >
            <BaseInput
              label="New Code:"
              v-model="book.code.$model"
              type="text"
              placeholder="code"
              class="field"
            />
            <button class="button" @click="addNewBookTitle(book.title.$model)">
              Save Code
            </button>
          </div>
          <div v-if="images">
            <div>
              <h3>Book Image:</h3>
              <div v-if="book.image.$model">
                <img v-bind:src="book.image.$model.image" class="book" />
                <br />
              </div>
              <v-select
                :options="images"
                label="title"
                v-model="book.image.$model"
              >
                <template slot="option" slot-scope="option">
                  <img class="select" :src="option.image" />

                  <br />
                  {{ option.title }}
                </template>
              </v-select>
            </div>
            <div v-if="image_permission">
              <label>
                Add new Image&nbsp;&nbsp;&nbsp;&nbsp;
                <input
                  type="file"
                  v-bind:id="book.code.$model"
                  ref="image"
                  v-on:change="handleImageUpload(book.code.$model)"
                />
              </label>
            </div>
          </div>
          <div>
            <h3>Format and Styling:</h3>
            <BaseSelect
              label="Format:"
              :options="formats"
              v-model="book.format.$model"
              class="field"
              :class="{ error: book.format.$error }"
              @mousedown="book.format.$touch()"
            />
            <template v-if="book.format.$error">
              <p v-if="!book.format.required" class="errorMessage">
                Format is required
              </p>
            </template>
          </div>

          <div>
            <BaseSelect
              label="Book and Chapters Style Sheet:"
              :options="styles"
              v-model="book.style.$model"
              class="field"
            />
            <template v-if="style_error">
              <p class="errorMessage">Only .css files may be uploaded</p>
            </template>

            <label>
              Add new stylesheet&nbsp;&nbsp;&nbsp;&nbsp;
              <input
                type="file"
                v-bind:id="book.title.$model"
                ref="style"
                v-on:change="handleStyleUpload(book.code.$model)"
              />
            </label>
            <div>
              <BaseSelect
                label="Editor Styles Shown:"
                :options="ckEditStyleSets"
                v-model="book.styles_set.$model"
                class="field"
                :class="{ error: book.styles_set.$error }"
                @mousedown="book.styles_set.$touch()"
              />
              <template v-if="book.styles_set.$error">
                <p v-if="!book.styles_set.required" class="errorMessage">
                  Editor Styles is required
                </p>
              </template>
            </div>

            <BaseSelect
              label="Template:"
              :options="templates"
              v-model="book.template.$model"
              class="field"
              :class="{ error: book.template.$error }"
              @mousedown="book.template.$touch()"
            />
            <label>
              Add new template&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input
                type="file"
                v-bind:id="book.title.$model"
                ref="template"
                v-on:change="handleTemplateUpload(book.code.$model)"
              />
            </label>
            <template v-if="template_error">
              <p class="errorMessage">
                Only .html files may be uploaded as templates
              </p>
            </template>
            <button
              class="button yellow"
              @click="
                createTemplate(
                  book.template.$model,
                  book.style.$model,
                  book.styles_set.$model,
                  book.title.$model,
                  book.code.$model,
                  book.format.$model
                )
              "
            >
              Edit or Create Template
            </button>
            <br />
            <label for="checkbox">
              <h3>Hide on library page?&nbsp;&nbsp;</h3>
            </label>
            <input type="checkbox" id="checkbox" v-model="book.hide.$model" />
            <br />

            <BaseInput
              label="Password (to add hidden item to library page):"
              v-model="book.password.$model"
              type="text"
              placeholder="password"
              class="field"
            />
            <label for="checkbox">
              <h3>Prototype?&nbsp;&nbsp;</h3>
            </label>
            <input
              type="checkbox"
              id="checkbox"
              v-model="book.prototype.$model"
            />
            <br />

            <label for="checkbox">
              <h3>Publish?&nbsp;&nbsp;&nbsp;</h3>
            </label>
            <input
              type="checkbox"
              id="checkbox"
              v-model="book.publish.$model"
            />
            <br />
            <br />
          </div>
        </div>
      </div>
      <div>
        <button class="button" @click="addNewBookForm">New Book</button>
        <div v-if="!$v.$anyError">
          <button class="button red" @click="saveForm">Save Changes</button>
        </div>
        <div v-if="$v.$anyError">
          <button class="button grey">Disabled</button>
          <p v-if="$v.$anyError" class="errorMessage">
            Please fill out the required field(s).
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavBar from '@/components/NavBarAdmin.vue'
import LibraryFormatTemplate from '@/components/LibraryFormatTemplate.vue'
import ContentService from '@/services/ContentService.js'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import vSelect from 'vue-select'
// see https://stackoverflow.com/questions/55479380/adding-images-to-vue-select-dropdown
import '@/assets/css/vueSelect.css'

import './ckeditor/index.js'
import VueCkeditor from 'vue-ckeditor2'
import { mapState } from 'vuex'
import { bookMarkMixin } from '@/mixins/BookmarkMixin.js'
import { libraryMixin } from '@/mixins/LibraryMixin.js'
import { libraryUploadMixin } from '@/mixins/library/LibraryUploadMixin.js'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import { required } from 'vuelidate/lib/validators'
export default {
  mixins: [authorMixin, bookMarkMixin, libraryMixin, libraryUploadMixin],
  components: {
    NavBar,
    LibraryFormatTemplate,
    VueCkeditor,
    'v-select': vSelect,
  },
  props: ['country_code', 'language_iso', 'library_code'],
  computed: mapState(['bookmark', 'cssURL', 'standard']),
  data() {
    return {
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      // site_image_dir: process.env.VUE_APP_SITE_IMAGE_DIR,
      site_dir: process.env.VUE_APP_SITE_DIR,
      formats: ['page', 'series', 'library'],
      pages: ['one', 'many'],
      header_image: null,
      book_images: [],
      images: [],
      folders: [],
      ckEditStyleSets: ['default'],
      styles_sets: [],
      bookcodes: [],
      templates: [],
      authorized: false,
      image_permission: false,
      isHidden: true,
      text: 'What will you write?',
      image: 'withFriends.png',
      style_error: false,
      template_error: false,
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
          '/sites/' + process.env.VUE_APP_SITE + '/ckeditor/templates/' +
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
  validations: {
    books: {
      required,
      $each: {
        id: { required },
        code: {},
        title: { required },
        style: {},
        styles_set: {},
        image: { required },
        format: { required },
        template: '',
        hide: '',
        password: '',
        prototype: '',
        publish: '',
      },
    },
  },
  methods: {
    addNewBookForm() {
      if (this.books.length == 0) {
        this.newLibrary()
      } else {
        this.books.push({
          id: '',
          code: '',
          title: '',
          style: '',
          styles_set: '',
          image: '',
          format: '',
          template: '',
          hide: '',
          password: '',
          prototype: '',
          publish: '',
        })
      }
    },
    addNewBookTitle(title) {
      LogService.consoleLogMessage('I came to addNewBookTitle')
      LogService.consoleLogMessage(title)
      this.bookcodes = []
      var change = this.$v.books.$model
      LogService.consoleLogMessage('change')
      LogService.consoleLogMessage(change)
      var arrayLength = change.length
      for (var i = 0; i < arrayLength; i++) {
        this.bookcodes.push(this.$v.books.$model[i].code)
      }
      LogService.consoleLogMessage(this.bookcodes)
      LogService.consoleLogMessage('about to hide')
      this.isHidden = true
      LogService.consoleLogMessage('hidden')
    },
    createBook(title) {
      LogService.consoleLogMessage(title)
      this.isHidden = false
    },
    prototypeAll() {
      var arrayLength = this.books.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.books.$each.$iter[i].prototype.$model = true
      }
    },
    prototypeNone() {
      var arrayLength = this.books.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.books.$each.$iter[i].prototype.$model = false
      }
    },
    publishAll() {
      var arrayLength = this.books.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.books.$each.$iter[i].publish.$model = true
      }
    },
    publishNone() {
      var arrayLength = this.books.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.books.$each.$iter[i].publish.$model = false
      }
    },
    async revert() {
      var params = {}
      params.recnum = this.recnum
      params.route = JSON.stringify(this.$route.params)
      params.scope = 'library'
      var res = await AuthorService.revert(params)
      console.log(res.content)
      this.seriesDetails = res.content.text
      this.recnum = res.content.recnum
    },
    async createFolder(folder) {
      LogService.consoleLogMessage(folder)
      var params = {}
      params.route = this.$route.params
      params.route.folder_name = folder.toLowerCase()
      params.route = JSON.stringify(params.route)
      AuthorService.createContentFolder(params)
      this.folders = await AuthorService.getFoldersContent(params)
    },

    async createTemplate(
      template,
      css,
      styles_set,
      title,
      book_code,
      book_format
    ) {
      await this.saveForm('stay')
      // creating a new template
      if (typeof template == 'undefined') {
        template = 'new'
      }
      if (typeof styles_set == 'undefined') {
        styles_set = 'mc2'
      }
      // use default style if not set
      if (typeof css == 'undefined') {
        css = this.style
      }
      LogService.consoleLogMessage(template)
      LogService.consoleLogMessage(css)
      this.$router.push({
        name: 'createTemplate',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
          title: title,
          template: template,
          cssFORMATTED: css,
          styles_set: styles_set,
          book_code: book_code,
          book_format: book_format,
        },
      })
    },
    deleteBookForm(id) {
      LogService.consoleLogMessage('Deleting id ' + id)
      this.books.splice(id, 1)
      LogService.consoleLogMessage(this.books)
    },

    async saveForm(action = null) {
      try {
        // create index files
        var check = ''
        var params = {}
        var route = this.$route.params
        var arrayLength = this.books.length
        for (var i = 0; i < arrayLength; i++) {
          check = this.books[i]
          if (check.format == 'series') {
            route.folder_name = check.code
            route.filename = 'index'

            params.route = JSON.stringify(route)
            AuthorService.createSeriesIndex(params)
          }
        }
        // update library file
        var output = {}
        output.books = this.books
        output.format = this.library_format
        output.text = this.text
        console.log('see library')
        console.log(output)
        var valid = ContentService.validate(output)
        this.content.text = JSON.stringify(valid)
        this.$route.params.filename = this.$route.params.library_code
        delete this.$route.params.folder_name
        this.content.route = JSON.stringify(this.$route.params)
        this.content.filetype = 'json'
        this.$store.dispatch('newBookmark', 'clear')

        var response = await AuthorService.createContentData(this.content)
        if (response.data.error != true && action != 'stay') {
          this.$router.push({
            name: 'previewLibrary',
            params: {
              country_code: this.$route.params.country_code,
              language_iso: this.$route.params.language_iso,
              library_code: this.$route.params.library_code,
            },
          })
        }
        if (response.data.error == true) {
          this.error = true
          this.loaded = false
          this.error_message = response.data.message
        }
      } catch (error) {
        LogService.consoleLogError(
          'LIBRARY EDIT There was an error in Saving Form ',
          error
        )
        this.loaded = false
        this.error_message = error
        this.error = true
      }
    },
    async showForm() {
      try {
        await this.getLibrary()
        this.books = this.bookmark.library.books
        this.images = await this.getImagesInContentDirectory(
          this.bookmark.language.image_dir
        )
        LogService.consoleLogMessage(this.images)
        // get folders
        var param = {}
        param.route = JSON.stringify(this.$route.params)
        param.image_dir = this.bookmark.language.image_dir
        var folder = await AuthorService.getFoldersContent(param)
        if (typeof folder !== 'undefined') {
          this.folders = folder
        }
        // get styles
        var style = await AuthorService.getStyles(param)
        if (typeof style !== 'undefined') {
          this.styles = style
        }
        //get style_sets
        var style_sets = await AuthorService.getCkEditStyleSets(param)
        console.log(style_sets)
        console.log('style_sets')
        if (typeof style_sets !== 'undefined') {
          this.ckEditStyleSets = style_sets
        }
        //get templates
        var template = await AuthorService.getTemplates(param)
        if (typeof template !== 'undefined') {
          this.templates = template
          LogService.consoleLogMessage('this.templates')
          LogService.consoleLogMessage(this.templates)
        }
        //make bookcodes list
        this.updateBookCodes()
        if (!this.image) {
          this.image = this.$route.params.library_code + '.png'
        }
        this.library_format = this.bookmark.library.format
        this.header_image =
          this.site_dir + this.bookmark.library.format.image.image
        this.image_permission = this.authorize('write', this.$route.params)
        this.authorized = this.authorize('write', this.$route.params)
        this.loaded = true
        this.loading = false
        LogService.consoleLogMessage('after loading is true')
      } catch (error) {
        LogService.consoleLogError('There was an error in Library.vue:', error) // Logs out the error
        this.error_message = error + 'Library Edit - showForm()'
        this.error = true
      }
    },
    updateBookCodes() {
      var arrayLength = this.bookmark.library.books.length
      if (typeof arrayLength !== 'undefined') {
        for (var i = 0; i < arrayLength; i++) {
          if (!this.bookcodes.includes(this.bookmark.library.books[i].code)) {
            this.bookcodes.push(this.bookmark.library.books[i].code)
          }
        }
        if (this.bookcodes.length > 0) {
          this.bookcodes.sort()
        }
      }
    },
  },
  beforeCreate() {
    LogService.consoleLogMessage('before Create')
    LogService.consoleLogMessage(this.$route.params)
    // set directory for custom images
    //see https://ckeditor.com/docs/ckfinder/ckfinder3-php/integration.html
    this.languageDirectory =
      this.$route.params.country_code +
      '/' +
      this.$route.params.language_iso +
      '/images/custom'
    this.$route.params.version = 'latest'
    if (!this.$route.params.library_code) {
      this.$route.params.library_code = 'library'
    }
    this.$route.params.styles_set = 'default'
    this.$route.params.version = 'lastest'
    this.$route.params.filename = 'index'
    this.$route.params.css = '/sites/default/styles/freeform.css'
  },
  async created() {
    this.library = []
    this.text = ''
    this.image = ''
    this.showForm()
  },
}
</script>
<style scoped>
img.select {
  width: 100px;
}
</style>
