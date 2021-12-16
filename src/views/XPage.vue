<template>
  <div>
    <NavBar />
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">There was an error... {{ this.error }}</div>
    <div class="content" v-if="loaded">
      <link
        rel="stylesheet"
        v-bind:href="'/content/' + this.bookmark.book.style"
      />
      <div class="app-link">
        <div class="app-card -shadow">
          <div v-on:click="goBack()">
            <img v-bind:src="this.book_image" class="book" />
            <div class="book">
              <span class="title">{{ this.bookmark.book.title }}</span>
            </div>
          </div>
        </div>
      </div>

      <h1 v-if="this.bookmark.page.count">
        {{ this.bookmark.page.count }}. {{ this.bookmark.page.title }}
      </h1>
      <h1 v-else>{{ this.bookmark.page.title }}</h1>
      <p>
        <span v-html="pageText"></span>
      </p>
      <div class="version">
        <p class="version">Version 2.05</p>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import ContentService from '@/services/ContentService.js'
import LogService from '@/services/LogService.js'
import NavBar from '@/components/NavBarBack.vue'

import { pageMixin } from '@/mixins/PageMixin.js'
export default {
  mixins: [ pageMixin],
  props: ['country_code', 'language_iso', 'folder_name', 'filename'],
  components: {
    NavBar,
  },
  computed: mapState(['bookmark', 'cssURL', 'standard']),
  data() {
    return {
      image_dir: null,
      image: null,
      book_image: null,
      style: null,
    }
  },
  methods: {
    goBack() {
      window.history.back()
    },
  },
  beforeCreate() {
    this.$route.params.version = 'current'
  },
  async created() {
    try {
      LogService.consoleLogMessage('PAGE VIEW - route')
      LogService.consoleLogMessage(this.$route.params)
      this.getPage(this.$route.params)
    } catch (error) {
      LogService.consoleLogError(
        'There was AN error in Page.vue during created:',
        error
      ) // Logs out the error
    }
  },
}
</script>
<style></style>
