<template>
  <div v-if="language.publish == 1">
    <div class="app-link" v-on:click="showPage(language)">
      <div class="shadow-card -shadow">
        <div class="book">
          <span class="title">{{ language.name }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    language: Object
  },
  computed: mapState(['bookmark']),
  methods: {
    showPage: function(language) {
      localStorage.setItem('lastPage', 'language/' + language)
      this.$router.push({
        name: 'library',
        params: {
          country_code: this.$route.params.country_code,
          language_iso: language.iso
        }
      })
    }
  }
}
</script>
