<template>
  <div>
    <NavBar called_by="series" />
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">There was an error... {{ this.error }}</div>
    <div class="content" v-if="loaded">
      <div v-bind:class="this.rldir">
        <link
          rel="stylesheet"
          v-bind:href="'/content/' + this.bookmark.book.style"
        />
        <div class="app-link">
          <div class="app-card -shadow">
            <img
              v-on:click="returnToIndex()"
              v-bind:src="
                process.env.VUE_APP_SITE_CONTENT +
                this.bookmark.language.image_dir +
                '/' +
                this.bookmark.book.image
              "
              class="app-img-header"
            />
          </div>
        </div>
        <h2>{{ this.bookmark.book.title }}</h2>
        <div v-if="this.description">{{ this.description }}</div>
        <br />
        <br />

        <Chapter
          v-for="chapter in chapters"
          :key="chapter.id"
          :chapter="chapter"
        />
        <div class="version">
          <p class="version">Version 1.11</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Chapter from '@/components/Chapter.vue'
import LogService from '@/services/LogService.js'
import NavBar from '@/components/NavBarBack.vue'

import { seriesMixin } from '@/mixins/SeriesMixin.js'
export default {
  mixins: [seriesMixin],
  props: ['country_code', 'language_iso', 'library_code', 'folder_name'],
  computed: mapState(['bookmark']),
  components: {
    Chapter,
    NavBar,
  },
  data() {
    return {
      seriesDetails: {
        series: '',
        language: '',
        description: '',
      },
      chapters: [
        {
          id: '',
          title: '',
          desciption: '',
          count: '',
          filename: '',
        },
      ],
      description: '',
      dir: 'ltr',
      loading: false,
      loaded: null,
      error: null,
    }
  },
  methods: {
    returnToIndex() {
      this.$router.push({
        name: 'library',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
        },
      })
    },
  },
  beforeCreate() {
    this.$route.params.version = 'current'
  },
  async created() {
    try {
      this.getSeries(this.$route.params)
    } catch (error) {
      LogService.consoleLogError('There was an error in SeriesEdit.vue:', error)
    }
  },
}
</script>
