<template>
  <div>
    <NavBar />
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">There was an error... {{ this.error }}</div>
    <div class="content" v-if="loaded">
      <a v-bind:href="'/languages/' + this.bookmark.country.code">
        <img
          v-bind:src="process.env.VUE_APP_SITE_CONTENT + this.image_dir + '/journey.jpg'"
          class="app-img-header"
        />
      </a>
      <div>
        <a href="/content/HD/images/standard/standard.zip"
          >Download Standard Images to Edit</a
        >
      </div>
      <Book v-for="book in library" :key="book.title" :book="book" />
      <div class="version">
        <p class="version">Version 2.05</p>
      </div>
    </div>
  </div>
</template>

<script>
import Book from '@/components/Book.vue'
import { mapState } from 'vuex'
import NavBar from '@/components/NavBarHamburger.vue'
import ContentService from '@/services/ContentService.js'
import LogService from '@/services/LogService.js'
import { bookMarkMixin } from '@/mixins/BookmarkMixin.js'
import { libraryMixin } from '@/mixins/LibraryMixin.js'

export default {
  mixins: [bookMarkMixin, libraryMixin],
  props: ['country_code', 'language_iso', 'library_code'],
  computed: mapState(['bookmark', 'cssURL', 'standard']),
  components: {
    Book,
    NavBar,
  },
  data() {
    return {
      library: [
        {
          id: '',
          book: '',
          title: '',
          folder: '',
          index: '',
          style: process.env.VUE_APP_SITE_STYLE,
          image: process.env.VUE_APP_SITE_IMAGE,
          format: 'series',
          pages: 1,
          instructions: '',
        },
      ],
      image_dir: null,
      images: null,
      loading: false,
      loaded: null,
      error: null,
    }
  },
  beforeCreate() {
    this.$route.params.version = 'current'
  },
  async created() {
    try {
      this
      await this.getLibrary()
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
