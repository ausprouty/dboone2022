<template>
  <div class="app-link" v-on:click="showPage(language)">
    <div class="shadow-card -shadow">
      <img v-bind:src="language.flag" class="book" />
      <div class="card-names">
        <span class="card-name">{{ language.language_name }}</span>
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
  computed: mapState([]),
  methods: {
    showPage: function(language) {
      localStorage.setItem('lastPage', 'languages')
      this.$router.push({
        name: language.library,
        params: {
          country_code: language.country_code,
          language_iso: language.language_iso
        }
      })
    }
  }
}
</script>
