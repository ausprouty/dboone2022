<template>
  <div>
    <NavBar />
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">
      There was an error... {{ this.error_message }}
    </div>
    <div class="content" v-if="loaded">
      <h1>
        {{ this.bookmark.book.title }}
        <a
          target="_blank"
          class="help"
          v-bind:href="this.prototype_url + 'HD/eng/help-1/series_edit.html'"
        >
          <img class="help-icon" src="/sites/default/images/icons/help.png" />
        </a>
      </h1>
      <div class="form">
        <BaseTextarea
          v-model="description"
          label="Series Description:"
          type="text"
          placeholder
          class="field"
        />
      </div>

      <br />
      <hr />
      <br />
      <div>
        <button class="button" @click="prototypeAll">
          Select ALL to Prototype</button
        >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        <button class="button" @click="publishAll">Select ALL to publish</button
        ><br />
        <button class="button" @click="prototypeNone">
          Do NOT prototype ANY</button
        >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button class="button" @click="publishNone">Do NOT publish ANY</button>

        ><br />

        <div
          v-for="(chapter, index) in $v.chapters.$each.$iter"
          :key="chapter.id"
          :chapter="chapter"
        >
          <div
            class="app-card -shadow"
            v-bind:class="{ notpublished: !chapter.publish.$model }"
          >
            <div
              class="float-right"
              style="cursor: pointer"
              @click="deleteChapterForm(index)"
            >
              X
            </div>
            <div class="form">
              <BaseInput
                v-model="chapter.count.$model"
                label="Chapter Number"
                type="text"
                placeholder="leave blank for un-numbered items"
                class="field"
                :class="{ error: chapter.count.$error }"
                @blur="chapter.count.$touch()"
              />
              <BaseInput
                v-model="chapter.title.$model"
                label="Title"
                type="text"
                placeholder
                class="field"
                :class="{ error: chapter.title.$error }"
                @blur="chapter.title.$touch()"
              />
              <template v-if="chapter.title.$error">
                <p v-if="!chapter.title.required" class="errorMessage">
                  Title is required
                </p>
              </template>

              <BaseTextarea
                v-model="chapter.description.$model"
                label="Description:"
                type="text"
                placeholder
                class="field"
                :class="{ error: chapter.description.$error }"
                @blur="chapter.description.$touch()"
              />
              <template v-if="chapter.description.$error">
                <p v-if="!chapter.description.required" class="errorMessage">
                  Description is required
                </p>
              </template>

              <BaseInput
                v-model="chapter.filename.$model"
                label="File Name"
                type="text"
                placeholder
                class="field"
                :class="{ error: chapter.filename.$error }"
                @blur="chapter.filename.$touch()"
              />
              <template v-if="chapter.filename.$error">
                <p v-if="!chapter.filename.required" class="errorMessage">
                  Description is required
                </p>
              </template>
              <div v-if="images">
                <div v-if="chapter.image.$model">
                  <img
                    v-bind:src="
                      process.env.VUE_APP_SITE_CONTENT +
                      series_image_dir +
                      '/' +
                      chapter.image.$model
                    "
                    class="book"
                  />
                  <br />
                </div>
                <BaseSelect
                  label="Image"
                  :options="images"
                  v-model="chapter.image.$model"
                  class="field"
                />
              </div>
              <div v-if="image_permission">
                <label>
                  Add new Image&nbsp;&nbsp;&nbsp;&nbsp;
                  <input
                    type="file"
                    v-bind:id="chapter.filename.$model"
                    ref="image"
                    v-on:change="handleImageUpload(chapter.filename.$model)"
                  />
                </label>
              </div>
              <input
                type="checkbox"
                id="checkbox"
                v-model="chapter.prototype.$model"
              />
              <label for="checkbox">
                <h2>Prototype?</h2>
              </label>
              &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
              <input
                type="checkbox"
                id="checkbox"
                v-model="chapter.publish.$model"
              />
              <label for="checkbox">
                <h2>Publish?</h2>
              </label>
            </div>
          </div>
        </div>

        <div v-if="this.authorized">
          <div v-if="this.new">
            <p class="errorMessage">
              Import Series in tab format (number| title | description| bible
              reference| filename| image|publish=Y)
            </p>
            <label>
              <input type="file" ref="file" v-on:change="importSeries()" />
            </label>
            <br />
            <br />
          </div>
          <button class="button" @click="addNewChapterForm">New Chapter</button>

          <div v-if="!$v.$anyError">
            <button class="button red" @click="saveForm">Save Changes</button>
          </div>
          <div v-if="$v.$anyError">
            <button class="button grey">Disabled</button>
            <p v-if="$v.$anyError" class="errorMessage">
              Please fill out the required field(s).
            </p>
          </div>
          <br />
          <br />
          <br />
        </div>
        <div v-if="!this.authorized">
          <p>
            You need to
            <a href="/login">login to make changes</a> here
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

import ContentService from '@/services/ContentService.js'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import NavBar from '@/components/NavBarAdmin.vue'

import { seriesMixin } from '@/mixins/SeriesMixin.js'
import { required } from 'vuelidate/lib/validators'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  mixins: [ seriesMixin, authorMixin],
  props: ['country_code', 'language_iso', 'library_code', 'folder_name'],
  computed: mapState(['bookmark']),
  components: {
    NavBar,
  },
  data() {
    return {
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      authorized: false,
      image_permission: false,
      isHidden: true,
      new: false,
      file: null,
      chapter: {
        title: null,
        description: null,
        count: null,
        filename: null,
        image: null,
        prototype: null,
        publish: null,
      },
    }
  },
  validations: {
    chapters: {
      required,
      $each: {
        title: { required },
        description: {},
        count: '',
        filename: { required },
        image: '',
        prototype: '',
        publish: '',
      },
    },
  },
  methods: {
    addNewChapterForm() {
      if (!this.chapters) {
        this.chapters = []
      }
      this.chapters.push({
        id: null,
        title: null,
        description: null,
        count: null,
        filename: null,
        image: null,
        prototype: null,
        publish: null,
      })
    },
    deleteChapterForm(id) {
      this.chapters.splice(id, 1)
    },
    async handleImageUpload(code) {
      LogService.consoleLogMessage('handleImageUpload: ' + code)
      var checkfile = {}
      var i = 0
      var arrayLength = this.$refs.image.length
      for (i = 0; i < arrayLength; i++) {
        checkfile = this.$refs.image[i]['files']
        if (checkfile.length == 1) {
          // LogService.consoleLogMessage(checkfile)
          //  LogService.consoleLogMessage(checkfile[0])
          var type = AuthorService.typeImage(checkfile[0])
          if (type) {
            var params = {}
            params.directory = 'content/' + this.series_image_dir
            params.name = code
            AuthorService.imageStore(params, checkfile[0])
            for (i = 0; i < arrayLength; i++) {
              checkfile = this.$v.chapters.$each[i]
              if (checkfile.$model.filename == code) {
                this.$v.chapters.$each[i].$model.image = code + type
              }
            }
            await this.saveForm()
            this.showForm()
          }
        }
      }
    },
    async importSeries() {
      LogService.consoleLogMessage('about to import series')
      this.file = this.$refs.file.files[0]
      LogService.consoleLogMessage('this.file')
      LogService.consoleLogMessage(this.file)
      var param = []
      param.route = JSON.stringify(this.$route.params)
      param.template = this.bookmark.book.template
      param.series_name = this.bookmark.book.title
      param.description = this.description
      LogService.consoleLogMessage(param)
      await AuthorService.setupSeries(param, this.file)
      LogService.consoleLogMessage('back from update')
      try {
        this.getSeries(this.$route.params)
        LogService.consoleLogMessage('tried get series')
        this.authorized = this.authorize('write', this.$route.params)
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in SeriesEdit.vue:',
          error
        )
      }
    },
    publishAll() {
      var arrayLength = this.chapters.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.chapters.$each.$iter[i].publish.$model = true
      }
    },
    prototypeAll() {
      var arrayLength = this.chapters.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.chapters.$each.$iter[i].prototype.$model = true
      }
    },
    publishNone() {
      var arrayLength = this.chapters.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.chapters.$each.$iter[i].publish.$model = false
      }
    },
    prototypeNone() {
      var arrayLength = this.chapters.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.chapters.$each.$iter[i].prototype.$model = false
      }
    },
    async revert() {
      var params = {}
      params.recnum = this.recnum
      params.route = JSON.stringify(this.$route.params)
      params.scope = 'series'
      var res = await AuthorService.revert(params)
      console.log(res.content)
      this.seriesDetails = res.content.text
      this.recnum = res.content.recnum
    },
    async saveData() {
      LogService.consoleLogMessage(this.content)
      var text = {}
      text.description = this.description
      text.download_now = this.download_now
      text.download_ready = this.download_ready
      text.chapters = this.chapters
      LogService.consoleLogMessage('text')
      LogService.consoleLogMessage(text)
      var valid = ContentService.validate(text)
      this.content.text = JSON.stringify(valid)
      this.$route.params.filename = 'index'
      this.content.route = JSON.stringify(this.$route.params)
      this.content.filetype = 'json'
      LogService.consoleLogMessage('this.content')
      LogService.consoleLogMessage(this.content)
      //this.$store.dispatch('newBookmark', 'clear')
      var response = await AuthorService.createContentData(this.content)
      return response
    },
    async saveForm() {
      try {
        var response = await this.saveData()
        if (response.data.error != true) {
          this.$router.push({
            name: 'previewSeries',
            params: {
              country_code: this.$route.params.country_code,
              language_iso: this.$route.params.language_iso,
              library_code: this.$route.params.library_code,
              folder_name: this.$route.params.folder_name,
            },
          })
        } else {
          this.error = true
          this.loaded = false
          this.error_message = response.data.message
        }
      } catch (error) {
        LogService.consoleLogError('LIBRARY EDIT There was an error ', error)
        this.error = true
        this.loaded = false
        this.error_message = response.data.message
      }
    },
    async showForm() {
      try {
        this.getSeries(this.$route.params)
        LogService.consoleLogMessage('I got series')
        // get images
        this.image_permission = this.authorize('write', this.$route.params)
        this.series_image_dir =
          this.$route.params.country_code +
          '/' +
          this.$route.params.language_iso +
          '/' +
          this.$route.params.folder_name
        var imageDirectories = []
        imageDirectories.push(this.series_image_dir)
        imageDirectories.push(this.bookmark.language.image_dir)
        var img = await AuthorService.getImagesInContentDirectories(
          imageDirectories
        )
        if (img) {
          img.push('')
          this.images = img.sort()
        }
        LogService.consoleLogMessage('this.chapters')
        LogService.consoleLogMessage(this.chapters)
        this.authorized = this.authorize('write', this.$route.params)
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in SeriesEdit.vue:',
          error
        )
      }
    },
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  created() {
    this.showForm()
  },
}
</script>
