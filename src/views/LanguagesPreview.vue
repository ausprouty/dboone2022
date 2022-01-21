<template>
  <div class="preview">
    <NavBar />
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">There was an error... {{ this.error }}</div>
    <div class="content" v-if="loaded">
      <div v-if="this.publish">
        <button class="button" @click="localPublish('website')">
          {{ this.publish_text }}
        </button>
      </div>
      <div v-if="this.prototype">
        <div>
          <button class="button" @click="localPublish('prototype')">
            {{ this.prototype_text }}
          </button>
        </div>
        <div>
          <button class="button" @click="localPublish('sdcard')">
            {{ this.sdcard_text }}
          </button>
        </div>
        <div>
          <button class="button" @click="localPublish('nojs')">
            {{ this.nojs_text }}
          </button>
        </div>
        <div>
          <button class="button" @click="localPublish('pdf')">
            {{ this.pdf_text }}
          </button>
        </div>
      </div>
      <a href="preview/languages">
        <img src="/sites/default/images/languages.jpg" class="app-img-header" />
      </a>

      <h1>
        {{ this.choose_language }}
        <a
          target="_blank"
          class="help"
          v-bind:href="this.prototype_url + 'HD/eng/help-1/languages.html'"
        >
          <img class="help-icon" src="/sites/default/images/icons/help.png" />
        </a>
        <img
          @click="showPreview()"
          class="help-icon"
          src="/sites/default/images/icons/preview.png"
        />
      </h1>
      <Language
        v-for="language in languages"
        :key="language.iso"
        :language="language"
      />
      <div v-if="!this.ZZ">
        <a href="/languages/ZZ">{{ this.more_languages }}</a>
      </div>
      <div class="version">
        <p class="version">Version 2.05</p>
      </div>
      <div v-if="this.write">
        <button class="button" @click="editLanguages">Edit</button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button class="button" @click="sortLanguages">Sort</button>
      </div>
      <div v-if="this.readonly">
        <button class="button" @click="editLanguages">View Details</button>
      </div>
    </div>
  </div>
</template>

<script>
import Language from '@/components/LanguagePreview.vue'
import NavBar from '@/components/NavBarAdmin.vue'
import LogService from '@/services/LogService.js'
import PrototypeService from '@/services/PrototypeService.js'
import PublishService from '@/services/PublishService.js'
import SDCardService from '@/services/SDCardService.js'
import NoJSService from '@/services/NoJSService.js'
import PDFService from '@/services/PDFService.js'
import { mapState } from 'vuex'
import { languageMixin } from '@/mixins/LanguageMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { publishMixin } from '@/mixins/PublishMixin.js'
export default {
  mixins: [languageMixin, authorizeMixin, publishMixin],
  props: ['country_code'],
  components: {
    Language,
    NavBar,
  },
  data() {
    return {
      readonly: false,
      write: false,
      publish: false,
      publishable: false,
      prototype_text: 'Prototype',
      publish_text: 'Publish',
      sdcard_text: 'Update SD Card',
      nojs_text: 'Update No Javascript',
      pdf_text: 'Update PDF files',
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      more_languages: 'More Languages',
      choose_language: 'Choose Language',
    }
  },
  computed: mapState(['bookmark', 'user']),
  methods: {
    editLanguages() {
      this.$router.push({
        name: 'editLanguages',
        params: {
          country_code: this.country_code,
        },
      })
    },
    sortLanguages() {
      this.$router.push({
        name: 'sortLanguages',
        params: {
          country_code: this.country_code,
        },
      })
    },
    goBack() {
      window.history.back()
    },
    showPreview() {
      const root = process.env.VUE_APP_PROTOTYPE_CONTENT_URL
      var link = root + this.$route.params.country_code + '/languages.html'
      window.open(link, '_blank')
    },

    async localPublish(location) {
      var response = null
      var params = []
      params.recnum = this.recnum
      params.route = JSON.stringify(this.$route.params)
      if (location == 'nojs') {
        this.nojs_text = 'Publishing'
        response = await NoJSService.publish('languages', params)
        this.nojs_text = 'Published'
      }
      if (location == 'pdf') {
        this.pdf_text = 'Publishing'
        response = await PDFService.publish('languages', params)
        this.pdf_text = 'Published'
      }
      if (location == 'prototype') {
        this.prototype_text = 'Prototyping'
        response = await PrototypeService.publish('languages', params)
        this.prototype_text = 'Prototyped'
      }
      if (location == 'sdcard') {
        this.sdcard_text = 'Publishing'
        response = await SDCardService.publish('languages', params)
        this.sdcard_text = 'Published'
      }
      if (location == 'website') {
        this.publish_text = 'Publishing'
        response = await PublishService.publish('languages', params)
        this.publish_text = 'Published'
      }

      if (response['error']) {
        this.error = response['message']
        this.loaded = false
      } else {
        //  this.UnsetBookmarks()
        this.recnum = null
        this.loaded = false
        this.loading = true
        this.publish = false
        await this.loadView()
      }
    },
    async localBookmark(recnum) {
      var param = {}
      param.recnum = recnum
      param.library_code = this.$route.params.library_code
      var bm = await PrototypeService.publish('bookmark', param)
      LogService.consoleLogMessage('localBookmark')
      LogService.consoleLogMessage(bm)
    },
    async loadView() {
      try {
        await this.getLanguages(this.$route.params)
        if (this.recnum) {
          await this.localBookmark(this.recnum)
        }
        this.readonly = this.authorize('readonly', this.$route.params)
        this.write = this.authorize('write', this.$route.params)
        // authorize for prototype and publish
        this.prototype = false
        this.publish = false
        if (this.recnum) {
          this.prototype = this.mayPrototypeLanguages()
          if (this.prototype) {
            if (!this.prototype_date) {
              this.prototype_text = 'Prototype'
            } else {
              this.prototype_text = 'Prototype Again'
            }
          }
          if (this.prototype_date) {
            this.publish = this.mayPublishLanguages()
            if (this.publish) {
              if (this.publish_date) {
                this.publish_text = 'Publish Again'
              } else {
                this.publish_text = 'Publish'
              }
            }
          }
        }
        // end authorization for prototype and publish
        this.ZZ = false
        if (this.$route.params.country_code == 'ZZ') {
          this.ZZ = true
        }
        this.loaded = true
        this.loading = false
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in LanguagesEdit.vue:',
          error
        ) // Logs out the error
      }
    },
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  created() {
    this.loadView()
  },
}
</script>

<style></style>
