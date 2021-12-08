<template>
  <div class="app-link" v-on:click="showPage(language)">
    <div
      class="app-card -shadow"
      v-bind:class="{
        notpublished: !language.publish,
        custom: language.custom
      }"
    >
      <div class="language">
        <span class="bold">{{ language.name }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import LogService from '@/services/LogService.js'
export default {
  props: {
    language: Object,
  },
  computed: mapState(['bookmark']),
  methods: {
    showPage: function (language) {
      LogService.consoleLogMessage('showPage')
      LogService.consoleLogMessage(language)
      LogService.consoleLogMessage(this.bookmark.country.code)
      localStorage.setItem('lastPage', 'language/' + language)
      var route = 'previewLibrary'
      if (language.custom) {
        route = 'previewLibraryIndex'
      }
      this.$router.push({
        name: route,
        params: {
          country_code: this.$route.params.country_code,
          language_iso: language.iso,
          library_code: 'library',
        },
      })
    },
  },
}
</script>
