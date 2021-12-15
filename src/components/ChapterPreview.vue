<template>
  <div class="app-link" v-on:click="showPage(chapter)">
    <div
      class="app-card -shadow"
      v-bind:class="{ notpublished: !chapter.publish }"
    >
      <div v-if="!this.chapter.image">
        <div class="chapter">
          <div
            v-if="chapter.count"
            class="chapter-title"
            v-bind:class="this.rldir"
          >
            {{ chapter.count }}. {{ chapter.title }}
          </div>
          <div v-else class="chapter-title" v-bind:class="this.rldir">
            {{ chapter.title }}
          </div>
          <div class="chapter-description" v-bind:class="this.rldir">
            {{ chapter.description }}
          </div>
        </div>
      </div>
      <div v-if="this.chapter.image">
        <img
          v-bind:src="
            process.env.VUE_APP_SITE_CONTENT +
            this.series_image_dir +
            '/' +
            chapter.image
          "
          class="book-large"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import LogService from '@/services/LogService.js'
export default {
  props: {
    chapter: Object,
  },
  data() {
    return {
      image_dir: '',
      rldir: 'ltr',
    }
  },
  computed: mapState(['bookmark']),
  methods: {
    showPage: function (chapter) {
      LogService.consoleLogMessage('chapter')
      LogService.consoleLogMessage(chapter)
      localStorage.setItem('lastPage', 'language/' + this.chapter.filename)
      var folder_name = ''
      // this section needed for legacy code
      if (typeof this.bookmark.book.name !== 'undefined') {
        folder_name = this.bookmark.book.name
      }
      if (typeof this.bookmark.book.code !== 'undefined') {
        folder_name = this.bookmark.book.code
      }
      var my_params = {
        country_code: this.$route.params.country_code,
        language_iso: this.$route.params.language_iso,
        library_code: this.$route.params.library_code,
        folder_name: folder_name,
        filename: chapter.filename,
      }
      LogService.consoleLogMessage('my_params')
      LogService.consoleLogMessage(my_params)
      this.$router.push({
        name: 'previewPage',
        params: my_params,
      })
    },
  },
  created() {
    this.series_image_dir =
      this.$route.params.country_code +
      '/' +
      this.$route.params.language_iso +
      '/' +
      this.$route.params.folder_name
    if (typeof this.bookmark.language != 'undefined') {
      LogService.consoleLogMessage('BOOK  PREVIEW -using bookmark')
      this.image_dir = this.bookmark.language.image_dir
    } else {
      LogService.consoleLogMessage('BOOK  PREVIEW -using standard directory')
      this.image_dir = process.env.VUE_APP_SITE_IMAGE_DIR
    }
    this.rldir = this.bookmark.language.rldir
  },
}
</script>
