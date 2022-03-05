<template>
  <div class="preview" v-bind:class="this.rldir">
    <NavBar called_by="page" />
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
      </div>
      <div v-if="this.sdcard">
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
      </div>
      <div v-if="this.pdf">
        <div>
          <button class="button" @click="localPublish('pdf')">
            {{ this.pdf_text }}
          </button>
        </div>
      </div>
      <div v-if="this.write">
        <button class="button" @click="editPage">Edit</button>
      </div>
      <img
        @click="showPreview()"
        class="help-icon"
        src="/sites/default/images/icons/preview.png"
      />
      <link rel="stylesheet" v-bind:href="this.book_style" />
      <div class="app-link">
        <div class="app-card -shadow">
          <div v-on:click="goBack()">
            <img
              v-bind:src="this.image_navigation"
              v-bind:class="this.image_navigation_class"
            />
            <span
              class="title"
              v-bind:class="this.bookmark.language.rldir"
              v-if="this.show_navigation_title"
              >{{ this.navigation_title }}</span
            >
          </div>
        </div>
      </div>
      <div v-if="this.show_page_title">
        <h1 v-if="this.bookmark.page.count">
          {{ this.bookmark.page.count }}.&nbsp; {{ this.bookmark.page.title }}
        </h1>
        <h1 v-else>{{ this.bookmark.page.title }}</h1>
      </div>
      <div v-if="this.show_page_image">
        <img
          v-bind:src="this.image_page"
          v-bind:class="this.image_page_class"
        />
      </div>
      <div>
        <span v-html="pageText"></span>
      </div>
      <div class="version">
        <p class="version">Version 2.05</p>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import LogService from '@/services/LogService.js'
import PrototypeService from '@/services/PrototypeService.js'
import PublishService from '@/services/PublishService.js'
import NavBar from '@/components/NavBarAdmin.vue'

import { pageMixin } from '@/mixins/PageMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { publishMixin } from '@/mixins/PublishMixin.js'
export default {
  mixins: [pageMixin, authorizeMixin, publishMixin],
  props: ['country_code', 'language_iso', 'folder_name', 'filename'],
  components: {
    NavBar,
  },
  computed: mapState(['bookmark', 'cssURL', 'standard', 'user']),
  data() {
    return {
      prototype_text: 'Prototype',
      publish_text: 'Publish',
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      rldir: 'ltr',
      book_style: process.env.VUE_APP_SITE_STYLE,
    }
  },
  methods: {
    editPage() {
      var css = this.bookmark.page.style
        ? this.bookmark.page.style
        : this.bookmark.book.style

      if (typeof this.bookmark.book.styles_set == 'undefined') {
        this.bookmark.book.styles_set = this.standard.styles_set
      }
      var clean = css.replace(/\//g, '@')
      var params = {
        country_code: this.$route.params.country_code,
        language_iso: this.$route.params.language_iso,
        library_code: this.$route.params.library_code,
        folder_name: this.$route.params.folder_name,
        filename: this.$route.params.filename,
        cssFORMATTED: clean,
        styles_set: this.bookmark.book.styles_set,
      }
      LogService.consoleLogMessage('params')
      LogService.consoleLogMessage(params)
      this.$router.push({
        name: 'editPage',
        params: params,
      })
    },
    goBack() {
      // can not use  window.history.back() as this may lead to endless loop with edit
      if (this.bookmark.book.format == 'series') {
        LogService.consoleLogMessage('goBack in series')
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
        LogService.consoleLogMessage('goBack Page')
        this.$router.push({
          name: 'previewLibrary',
          params: {
            country_code: this.$route.params.country_code,
            language_iso: this.$route.params.language_iso,
            library_code: this.$route.params.library_code,
          },
        })
      }
    },
    showPreview() {
      var link = ''
      var root = process.env.VUE_APP_PROTOTYPE_CONTENT_URL
      if (this.bookmark.book.format == 'series') {
        link =
          root +
          this.$route.params.country_code +
          '/' +
          this.$route.params.language_iso +
          '/' +
          this.$route.params.folder_name +
          '/' +
          this.$route.params.filename +
          '.html'
      } else {
        link =
          root +
          this.$route.params.country_code +
          '/' +
          this.$route.params.language_iso +
          '/' +
          'pages' +
          '/' +
          this.$route.params.filename +
          '.html'
      }
      window.open(link, '_blank')
    },
    async localPublish(location) {
      var response = null
      var params = []
      params.recnum = this.recnum
      params.route = JSON.stringify(this.$route.params)
      if (location == 'prototype') {
        this.prototype_text = 'Prototyping'
        response = await PrototypeService.publish('page', params)
        this.prototype_text = 'Prototyped'
      }
      if (location == 'website') {
        this.publish_text = 'Publishing'
        response = await PublishService.publish('page', params)
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
      return bm
    },
    async loadView() {
      try {
        await this.getPageorTemplate('page only')
        if (this.recnum) {
          var bm = await this.localBookmark(this.recnum)
          this.rldir = bm.language.rldir
          this.book_style = bm.book.style
        }
        this.read = this.authorize('read', this.$route.params)
        this.write = this.authorize('write', this.$route.params)
        // authorize for prototype and publish
        this.prototype = false
        this.publish = false
        if (this.recnum) {
          this.prototype = this.mayPrototypePage()
          if (this.prototype) {
            if (!this.prototype_date) {
              this.prototype_text = 'Prototype'
            } else {
              this.prototype_text = 'Prototype  Again'
            }
          }
          if (this.prototype_date) {
            this.publish = this.mayPublishPage()
            if (this.publish) {
              if (this.publish_date) {
                this.publish_text = 'Publish  Again'
              } else {
                this.publish_text = 'Publish '
              }
            }
          }
        }
        // end authorization for prototype and publish
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in Page.vue during load view',
          error
        ) // Logs out the error
      }
    },
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  async created() {
    this.loadView()
  },
}
</script>
<style>
.title {
  font-weight: bold;
  padding-left: 20px;
  position: absolute;
  padding-top: 20px;
  font-size: 24px;
}
.title.rtl {
  padding-left: 0px;
  padding-right: 20px;
}
li.rtl {
  text-align: right;
}
</style>
