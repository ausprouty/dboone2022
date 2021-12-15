<template>
  <div v-if="chapter.publish == 1">
    <div class="app-link" v-on:click="showPage(chapter)">
      <div class="app-card -shadow">
        <div class="chapter">
          <div v-if="chapter.count" class="chapter-title">
            {{ chapter.count }}. {{ chapter.title }}
          </div>
          <div v-else class="chapter-title">{{ chapter.title }}</div>
          <div class="chapter-description">{{ chapter.description }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    chapter: Object,
  },
  computed: mapState(['bookmark']),
  methods: {
    showPage: function (chapter) {
      localStorage.setItem('lastPage', 'language/' + this.chapter.filename)
      this.$router.push({
        name: 'page',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
          folder_name: this.bookmark.book.code,
          filename: chapter.filename,
        },
      })
    },
  },
}
</script>
