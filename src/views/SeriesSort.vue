<template>
  <div>
    <NavBar called_by="series" />

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
          Sort {{ this.bookmark.book.title }}
          <a
            target="_blank"
            class="help"
            v-bind:href="this.prototype_url + 'HD/eng/help-1/series_sort.html'"
          >
            <img class="help-icon" src="/sites/default/images/icons/help.png" />
          </a>
        </h1>
        <div class="form">
          <draggable v-model="chapters">
            <transition-group>
              <div
                v-for="chapter in chapters"
                :key="chapter.title"
                :book="chapter"
              >
                <div class="shadow-card -shadow">
                  <img
                    src="/sites/default/images/icons/move2red.png"
                    class="sortable"
                  />
                  <span class="card-name">
                    {{ chapter.count }} {{ chapter.title }}</span
                  >
                </div>
              </div>
            </transition-group>
          </draggable>
        </div>
        <button class="button" @click="saveForm">Save</button>
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
</template>

<script>
import { mapState } from 'vuex'

import ContentService from '@/services/ContentService.js'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import NavBar from '@/components/NavBarAdmin.vue'
import draggable from 'vuedraggable'

import { seriesMixin } from '@/mixins/SeriesMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
export default {
  mixins: [seriesMixin, authorizeMixin],
  props: ['country_code', 'language_iso', 'library_code', 'folder_name'],
  computed: mapState(['bookmark']),
  components: {
    NavBar,
    draggable,
  },
  data() {
    return {
      authorized: false,
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
    }
  },
  methods: {
    addNewChapterForm() {
      this.chapters.push({
        id: '',
        title: '',
        description: '',
        count: '',
        filename: '',
      })
    },
    deleteChapterForm(id) {
      this.chapters.splice(id, 1)
    },
    async saveForm() {
      try {
        LogService.consoleLogMessage(this.content)
        var text = {}
        text.description = this.seriesDetails.description
        text.chapters = this.chapters
        var valid = ContentService.validate(text)
        this.content.text = JSON.stringify(valid)
        this.$route.params.filename = 'index'
        this.content.route = JSON.stringify(this.$route.params)
        this.content.filetype = 'json'
        //this.$store.dispatch('newBookmark', 'clear')
        var response = await AuthorService.createContentData(this.content)
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
      }
    },
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  async created() {
    try {
      this.getSeries(this.$route.params)
      this.authorized = this.authorize('write', this.$route.params)
    } catch (error) {
      LogService.consoleLogError('There was an error in SeriesEdit.vue:', error) // Logs out the error
    }
  },
}
</script>
