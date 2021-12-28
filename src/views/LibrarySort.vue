<template>
  <div>
    <NavBar />

    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">There was an error... {{ this.error }}</div>
    <div class="content" v-if="loaded">
      <div v-if="!this.authorized">
        <p>
          You have stumbled into a restricted page. Sorry I can not show it to
          you now
        </p>
      </div>

      <div v-if="this.authorized">
        <h1>
          Library
          <a
            target="_blank"
            class="help"
            v-bind:href="this.prototype_url + 'HD/eng/help-1/library_sort.html'"
          >
            <img class="help-icon" src="/sites/default/images/icons/help.png" />
          </a>
        </h1>
        <div>
          <draggable v-model="books">
            <transition-group>
              <div v-for="book in books" :key="book.id" :book="book">
                <div class="shadow-card -shadow">
                  <img
                    src="/sites/default/images/icons/move2red.png"
                    class="sortable"
                  />
                  <span class="card-name">{{ book.title }}</span>
                </div>
              </div>
            </transition-group>
          </draggable>
        </div>
        <button class="button" @click="saveForm">Save</button>
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
import NavBar from '@/components/NavBarAdmin.vue'
import ContentService from '@/services/ContentService.js'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import draggable from 'vuedraggable'
import { mapState } from 'vuex'

import { libraryMixin } from '@/mixins/LibraryMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
export default {
  mixins: [libraryMixin, authorizeMixin],
  props: ['country_code', 'language_iso', 'library_code'],
  computed: mapState(['bookmark', 'cssURL', 'standard']),
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
    addNewBookForm() {
      this.library.push({
        id: '',
        book: '',
        title: '',
        folder: '',
        index: '',
        style: process.env.VUE_APP_SITE_STYLE,
        image: process.env.VUE_APP_SITE_IMAGE,
        format: 'series',
      })
    },
    deleteBookForm(index) {
      this.library.splice(index, 1)
    },
    async saveForm() {
      try {
        var output = {}
        output.books = this.books
        output.format = this.format
        output.text = this.text
        var valid = ContentService.validate(output)
        this.content.text = JSON.stringify(valid)
        var route = this.$route.params
        route.filename = this.$route.params.library_code
        this.content.route = JSON.stringify(route)
        this.content.filetype = 'json'
        //this.$store.dispatch('newBookmark', 'clear')
        var response = await AuthorService.createContentData(this.content)
        if (response.data.error != true) {
          this.$router.push({
            name: 'previewLibrary',
            params: {
              country_code: this.$route.params.country_code,
              language_iso: this.$route.params.language_iso,
              library_code: this.$route.params.library_code,
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
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  async created() {
    try {
      await this.getLibrary(this.$route.params)
      this.authorized = this.authorize('write', this.$route.params)
      this.loaded = true
      this.loading = false
    } catch (error) {
      LogService.consoleLogError(
        'There was an error in LibraryEdit.vue:',
        error
      ) // Logs out the error
    }
  },
}
</script>
