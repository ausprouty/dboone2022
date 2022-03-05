<template>
  <div class="preview" v-bind:dir="this.rldir">
    <link rel="stylesheet" v-bind:href="this.style" />
    <NavBar called_by="library" />
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
        <div v-if="this.sdcard">
          <div>
            <button class="button" @click="localPublish('sdcard')">
              {{ this.sdcard_text }}
            </button>
          </div>
        </div>
      </div>
      <a
        target="_blank"
        class="help"
        v-bind:href="this.prototype_url + 'HD/eng/help-1/library_preview.html'"
      >
        <img class="help-icon" src="/sites/default/images/icons/help.png" />
      </a>
      <img
        @click="showPreview()"
        class="help-icon"
        src="/sites/default/images/icons/preview.png"
      />
      <hr class="border" />
      <a v-bind:href="this.back">
        <img v-bind:src="this.back_image" class="app-img-header" />
      </a>
      <div>
        <span v-html="text"></span>
      </div>

      <Book
        v-for="book in this.bookmark.library.books"
        :key="book.title"
        :book="book"
      />
      <div class="version">
        <p class="version">Version 2.05</p>
      </div>
      <hr class="border" />
    </div>
    <div v-if="this.write">
      <button class="button" @click="editLibrary">Edit</button>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <button class="button" @click="sortLibrary">Sort</button>
    </div>
    <div v-if="this.readonly">
      <button class="button" @click="editLibrary">View Details</button>
    </div>
  </div>
</template>

<script>
import Book from '@/components/BookPreview.vue'
import NavBar from '@/components/NavBarAdmin.vue'
import LogService from '@/services/LogService.js'
import PrototypeService from '@/services/PrototypeService.js'
import PublishService from '@/services/PublishService.js'

import { mapState } from 'vuex'

import { libraryMixin } from '@/mixins/LibraryMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { publishMixin } from '@/mixins/PublishMixin.js'
export default {
  mixins: [libraryMixin, authorizeMixin, publishMixin],
  props: ['country_code', 'language_iso', 'library_code'],
  computed: mapState(['bookmark', 'cssURL', 'standard', 'user']),
  components: {
    Book,
    NavBar,
  },
  data() {
    return {
      style: null,
      readonly: false,
      write: false,
      publish: false,
      sdcard: false,
      prototype_text: 'Prototype Library and Books',
      publish_text: 'Publish Library and Books',
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      site_directory: process.env.VUE_APP_SITE_DIR,
      back: 'country',
      back_image: null,
      format: {},
    }
  },
  methods: {
    editLibrary() {
      this.$router.push({
        name: 'editLibrary',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
        },
      })
    },
    sortLibrary() {
      this.$router.push({
        name: 'sortLibrary',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
        },
      })
    },
    goBack() {
      window.history.back()
    },
    showPreview() {
      var root = process.env.VUE_APP_PROTOTYPE_CONTENT_URL
      var page = this.$route.params.library_code
      if (this.$route.params.library_code == 'library') {
        page = 'index'
      }
      var link =
        root +
        this.$route.params.country_code +
        '/' +
        this.$route.params.language_iso +
        '/' +
        page +
        '.html'
      console.log('I want to go to ' + link)
      window.open(link, '_blank')
    },

    async localPublish(location) {
      var response = null
      var params = []
      params.recnum = this.recnum
      params.library_code = this.$route.params.library_code
      params.route = JSON.stringify(this.$route.params)
      if (location == 'prototype') {
        this.prototype_text = 'Prototyping'
        response = await PrototypeService.publish('libraryAndBooks', params)
        this.prototype_text = 'Finished Prototyping'
      }

      if (location == 'website') {
        this.publish_text = 'Publishing'
        response = await PublishService.publish('libraryAndBooks', params)
        this.prototype_text = 'Finished Publishing'
      }
      if (response['error']) {
        this.error = response['message']
        this.loaded = false
        if (location == 'website') {
          this.publish_text = 'Error Publishing'
        } else if (location == 'prototype') {
          this.prototype_text = 'Error Prototyping'
        }
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
        this.recnum = null
        //this.$store.dispatch('newBookmark', 'clear')
        await this.getLibrary(this.$route.params)
        this.back = '/preview/languages/' + this.$route.params.country_code
        //todo: allow this to backtrack
        // this is only true if the library goes back to a custom library
        console.log(this.$route.params)
        if (typeof this.$route.params.library_code == 'undefined') {
          this.$route.params.library_code = ''
        }
        if (this.$route.params.library_code != 'library') {
          this.back =
            '/preview/library' +
            this.$route.params.country_code +
            '/' +
            this.$route.params.language_iso
        }
        if (typeof this.bookmark.library.format.image !== 'undefined') {
          this.back_image = this.bookmark.library.format.image.image
        }
        this.style = this.bookmark.library.format.style
        this.readonly = this.authorize('readonly', this.$route.params)
        this.write = this.authorize('write', this.$route.params)
        // authorize for prototype and publish
        this.prototype = false
        this.publish = false
        if (this.recnum) {
          this.prototype = this.mayPrototypeLibrary()
          if (this.prototype) {
            if (!this.prototype_date) {
              this.prototype_text = 'Prototype Library and Books'
            } else {
              this.prototype_text = 'Prototype Library and Books Again'
            }
          }
          if (this.prototype_date) {
            this.publish = this.mayPublishLibrary()
            if (this.publish) {
              if (this.publish_date) {
                this.publish_text = 'Publish Library and Books Again'
              } else {
                this.publish_text = 'Publish Library and Books'
              }
            }
          }
        }
        // end of Prototype and Publish
        this.loaded = true
        this.loading = false
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in LibraryPreview.vue:',
          error
        )
      }
    },
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  async created() {
    await this.loadView()
  },
}
</script>
