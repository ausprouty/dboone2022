<template>
  <div
    class="app-card -shadow"
    v-bind:class="{ notpublished: !language.publish.$model }"
  >
    <div
      class="float-right"
      style="cursor:pointer"
      @click="deleteLanguageForm(language.id.$model)"
    >
      X
    </div>
    <form @submit.prevent="saveForm">
      <h2>Language Details</h2>
      <BaseInput
        v-model="language.name.$model"
        label="Language Name (as you want your audience to see it)"
        type="text"
        placeholder="Language Name"
        class="field"
        :class="{ error: language.name.$error }"
        @blur="language.name.$touch()"
      />
      <template v-if="language.name.$error">
        <p v-if="!language.name.required" class="errorMessage">
          Language Name is required
        </p>
      </template>

      <div v-if="!language.iso.$model">
        <p>
          <a
            target="a_blank"
            href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes"
            >Language ISO Reference File</a
          >
        </p>
      </div>
      <BaseInput
        v-model="language.iso.$model"
        label="Language 3 letter ISO"
        type="text"
        placeholder="3 letter ISO code"
        class="field"
        :class="{ error: language.iso.$error }"
        @blur="language.iso.$touch()"
        @input="forceLowerISO(language.iso.$model)"
      />
      <template v-if="language.iso.$error">
        <p v-if="!language.iso.required" class="errorMessage">
          Language ISO is required
        </p>
      </template>
      <BaseSelect
        label="Text Direction"
        :options="direction"
        v-model="language.rldir.$model"
        class="field"
      />
      <hr />
      <h2>Content Location</h2>
      <div v-if="language.iso.$model">
        <BaseSelect
          label="Content Folder"
          :options="content_folders"
          v-model="language.folder.$model"
          class="field"
          :class="{ error: language.folder.$error }"
        />
        <div>
          <p>
            <a @click="setupLanguageFolder(language.iso.$model)"
              >Create new content folder</a
            >
          </p>
        </div>
        <template v-if="language.folder.$error">
          <p v-if="!language.folder.required" class="errorMessage">
            Content folder is required
          </p>
        </template>
      </div>
      <hr />
      <h2>Images</h2>
      <div v-if="language.folder.$model">
        <BaseSelect
          label="Library Image Folder"
          :options="image_folders"
          v-model="language.image_dir.$model"
          class="field"
          :class="{ error: language.image_dir.$error }"
          @blur="language.image_dir.$touch()"
        />

        <div>
          <p>
            <a @click="setupImageFolder(language.iso.$model)"
              >Create new image folder</a
            >
          </p>
        </div>
        <template v-if="language.image_dir.$error">
          <p v-if="!language.image_dir.required" class="errorMessage">
            Image Folder is required
          </p>
        </template>
        <input type="checkbox" id="checkbox" v-model="language.titles.$model" />
        <label for="checkbox">
          <p>Book images contain Titles</p>
        </label>
        <hr />

        <div>
          <input
            type="checkbox"
            id="checkbox"
            v-model="language.custom.$model"
          />
          <label for="checkbox">
            <h2>Custom Library Index?</h2>
          </label>
        </div>
        <hr />

        <h2>Common Terms</h2>
        <BaseInput
          v-model="language.download.$model"
          label="Download for offline use"
          type="text"
          placeholder
          class="field"
        />
        <BaseInput
          v-model="language.download_ready.$model"
          label="Ready for offline use"
          type="text"
          placeholder
          class="field"
        />
        <BaseInput
          v-model="language.read_more.$model"
          label="Read More"
          type="text"
          placeholder
          class="field"
        />
        <BaseInput
          v-model="language.read.$model"
          label="Read %"
          type="text"
          placeholder
          class="field"
        />
        <BaseInput
          v-model="language.watch.$model"
          label="Watch % online"
          type="text"
          placeholder
          class="field"
        />
         <BaseInput
          v-model="language.listen.$model"
          label="Listen to % online"
          type="text"
          placeholder
          class="field"
        />
        <BaseInput
          v-model="language.notes.$model"
          label="Notes: (click outside box to save)"
          type="text"
          placeholder
          class="field"
        />
        <BaseInput
          v-model="language.send_notes.$model"
          label="Send Notes and Action Points"
          type="text"
          placeholder
          class="field"
        />
        <hr />

        <h2>Bible</h2>
        <div>
          <br />Old Testament:
          <select v-model="language.bible_ot.$model">
            <option
              v-for="o in this.ot"
              v-bind:key="o.bid"
              v-bind:value="o.bid"
              >{{ o.volume_name }}</option
            >
          </select>
        </div>

        <div>
          <br />New Testament:
          <select v-model="language.bible_nt.$model">
            <option
              v-for="n in this.nt"
              v-bind:key="n.bid"
              v-bind:value="n.bid"
              >{{ n.volume_name }}</option
            >
          </select>
        </div>
        <br />
        <input
          type="checkbox"
          id="checkbox"
          v-model="language.prototype.$model"
        />
        <label for="checkbox">
          <h2>Prototype?</h2>
        </label>

        <br />
        <input
          type="checkbox"
          id="checkbox"
          v-model="language.publish.$model"
        />
        <label for="checkbox">
          <h2>Publish?</h2>
        </label>
      </div>
    </form>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import { bibleMixin } from '@/mixins/BibleMixin.js'
export default {
  mixins: [bibleMixin],
  props: {
    language: Object
  },

  data() {
    return {
      image_folders: ['just a minute please'],
      content_folders: ['enter language first'],
      direction: ['rtl', 'ltr'],
      ot: [],
      nt: []
    }
  },
  methods: {
    foldersFilter() {
      LogService.consoleLogMessage('I want to filter folders')
    },
    deleteLanguageForm(id) {
      console.log(this.language.id.$model)
      // see https://forum.vuejs.org/t/passing-data-back-to-parent/1201
      this.$emit('clicked', this.language.id.$model)
    },
    async forceLowerISO(value) {
      if (this.language.$model.iso.length > 2) {
        var params = {}
        params.language_iso = value.toLowerCase()
        params.country_code = this.$route.params.country_code
        this.language.$model.iso = params.language_iso
        await this.setupLanguageFolder(params.language_iso)
        this.content_folders = await AuthorService.getContentFoldersForLanguage(
          params
        )
        params.testament = 'NT'
        this.nt = await this.getBibleVersions(params)
        params.testament = 'OT'
        this.ot = await this.getBibleVersions(params)
      }
    },
    //todo rewrite
    async setupImageFolder(iso) {
      var params = {}
      params.country_code = this.$route.params.country_code
      params.language_iso = iso
      console.log('setup image folder')
      console.log(params)
      await AuthorService.setupImageFolder(params)
      var res = await AuthorService.getFoldersImages(params)
      this.image_folders = res
    },
    async setupLanguageFolder(iso) {
      var params = {}
      params.country_code = this.$route.params.country_code
      params.language_iso = iso
      await AuthorService.setupLanguageFolder(params)
      var res = await AuthorService.getContentFoldersForLanguage(params)
      this.content_folders = res
    }
  },
  async created() {
    // see https://stackoverflow.com/questions/35748162/how-to-get-the-text-of-the-selected-option-using-vuejs
    // see https://vue-select.org/guide/values.html#transforming-selections
    var params = {}
    params.country_code = this.$route.params.country_code
    params.language_iso = this.language.$model.iso
    this.image_folders = await AuthorService.getFoldersImages(params)
    LogService.consoleLogMessage(this.image_folders)
    // LogService.consoleLogMessage(this.content_folders)
    this.ot = null
    this.nt = null
    this.content_folders = []
    if (typeof this.language.$model.iso !== 'undefined') {
      if (this.language.$model.iso != null) {
        var response = await AuthorService.getContentFoldersForLanguage(params)
        this.content_folders = response
        params.testament = 'OT'
        console.log(params)
        this.ot = await this.getBibleVersions(params)
        LogService.consoleLogMessage('OT')
        LogService.consoleLogMessage(this.ot)
        params.testament = 'NT'
        this.nt = await this.getBibleVersions(params)
        LogService.consoleLogMessage('NT')
        LogService.consoleLogMessage(this.nt)
      }
    }
  }
}
</script>
