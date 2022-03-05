<template>
  <div class="preview" v-bind:dir="this.rldir">
    <NavBar called_by="series" />
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">There was an error...{{ this.error }}</div>
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
            <button class="button" @click="sdCard('video_list')">
              {{ this.videolist_text }}
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
        </div>
        <div v-if="this.pdf">
          <div>
            <button class="button" @click="localPublish('pdf')">
              {{ this.pdf_text }}
            </button>
          </div>
        </div>
      </div>
      <div>
        <a
          target="_blank"
          class="help"
          v-bind:href="this.prototype_url + 'HD/eng/help-1/series_preview.html'"
        >
          <img class="help-icon" src="/sites/default/images/icons/help.png" />
        </a>
        <img
          @click="showPreview()"
          class="help-icon"
          src="/sites/default/images/icons/preview.png"
        />
        <link rel="stylesheet" v-bind:href="this.style" />

        <div class="app-series-header">
          <img
            v-on:click="returnToIndex()"
            v-bind:src="this.book_image"
            class="app-series-header"
          />
        </div>

        <div v-if="!bookmark.language.titles">
          <h2>{{ bookmark.book.title }}</h2>
        </div>
        <div v-if="this.description">{{ this.description }}</div>
        <br />
        <p id="offline-ready">{{ this.bookmark.language.download_ready }}</p>

        <br />

        <Chapter
          v-for="chapter in chapters"
          :key="chapter.id"
          :chapter="chapter"
        />
      </div>
      <div>
        <p class="button">
          <button id="offline-request" class="cache-series">
            {{ this.bookmark.language.download }}
          </button>
        </p>
      </div>
      <div class="version">
        <p class="version">Version 2.05</p>
      </div>
    </div>
    <hr />
    <div v-if="this.write">
      <button class="button" @click="editSeries">Edit</button>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <button class="button" @click="sortSeries">Sort</button>
    </div>
    <div v-if="this.readonly">
      <button class="button" @click="editSeries">View Details</button>
    </div>
    <br />
    <br />
    <br />
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Chapter from '@/components/ChapterPreview.vue'
import LogService from '@/services/LogService.js'
import PrototypeService from '@/services/PrototypeService.js'
import PublishService from '@/services/PublishService.js'
import NavBar from '@/components/NavBarAdmin.vue'

import { seriesMixin } from '@/mixins/SeriesMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { publishMixin } from '@/mixins/PublishMixin.js'
export default {
  mixins: [seriesMixin, authorizeMixin, publishMixin],
  props: ['country_code', 'language_iso', 'library_code', 'folder_name'],
  computed: mapState(['bookmark', 'user']),
  components: {
    Chapter,
    NavBar,
  },
  data() {
    return {
      readonly: false,
      write: false,
      publish: false,
      book_image: null,
      prototype_text: 'Prototype Series and Chapters',
      publish_text: 'Publish Series and Chapters',
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      download_ready: '',
      download_now: '',
      description: '',
      style: '',
    }
  },

  methods: {
    editSeries() {
      this.$router.push({
        name: 'editSeries',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
          folder_name: this.$route.params.folder_name,
        },
      })
    },
    sortSeries() {
      this.$router.push({
        name: 'sortSeries',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
          folder_name: this.$route.params.folder_name,
        },
      })
    },
    goBack() {
      window.history.back()
    },
    showPreview() {
      var root = process.env.VUE_APP_PROTOTYPE_CONTENT_URL
      var link =
        root +
        this.$route.params.country_code +
        '/' +
        this.$route.params.language_iso +
        '/' +
        this.$route.params.folder_name +
        '/index.html'
      window.open(link, '_blank')
    },
    returnToIndex() {
      this.$router.push({
        name: 'previewLibrary',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
        },
      })
    },
    async localPublish(location) {
      var response = null
      var params = []
      params.recnum = this.recnum
      params.route = JSON.stringify(this.$route.params)
      if (location == 'prototype') {
        this.prototype_text = 'Prototyping'
        response = await PrototypeService.publish('seriesAndChapters', params)
        this.prototype_text = 'Prototyped'
      }
      if (location == 'website') {
        this.publish_text = 'Publishing'
        response = await PublishService.publish('seriesAndChapters', params)
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
        LogService.consoleLogMessage(this.$route.params)
        await this.getSeries(this.$route.params)
        //if (this.recnum) {
        //  this.localBookmark(this.recnum)
        //}

        this.series_image_dir =
          this.$route.params.country_code +
          '/' +
          this.$route.params.language_iso +
          '/' +
          this.$route.params.folder_name
        this.readonly = this.authorize('readonly', this.$route.params)
        this.write = this.authorize('write', this.$route.params)

        // authorize for prototype and publish
        this.prototype = false
        this.publish = false
        if (this.recnum) {
          this.prototype = this.mayPrototypeSeries()
          if (this.prototype) {
            if (!this.prototype_date) {
              this.prototype_text = 'Prototype Series and Chapters'
            } else {
              this.prototype_text = 'Prototype Series and Chapters Again'
            }
          }
          LogService.consoleLogMessage(
            'this prototype date is ' + this.prototype_date
          )
          if (this.prototype_date) {
            this.publish = this.mayPublishSeries()
            if (this.publish) {
              if (this.publish_date) {
                this.publish_text = 'Publish Series and Chapters Again'
              } else {
                this.publish_text = 'Publish Series and Chapters'
              }
            }
          }
        }
        // end of Prototype and Publish

        this.loaded = true
        this.loading = false
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in SeriesEdit.vUe:',
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
