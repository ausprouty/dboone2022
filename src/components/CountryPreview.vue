<template>
  <div class="app-link" v-on:click="showLanguagePage(country)">
    <div
      class="shadow-card -shadow"
      v-bind:class="{ notpublished: !country.publish }"
    >
      <img
        v-bind:src="'/sites/default/images/country/' + country.image"
        class="flag"
      />
      <div class="card-names">
        <span class="card-name">{{ country.name }}</span>
        <br />
        <span class="card-name-english">{{ country.english }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import LogService from '@/services/LogService.js'
import { mapState } from 'vuex'
import { countriesMixin } from '@/mixins/CountriesMixin.js'
import store from '@/store/store.js'

export default {
  mixins: [countriesMixin],
  props: {
    country: Object,
  },
  data: function () {
    return {
      saving: false,
      bMark: store.state.bookmark,
    }
  },
  computed: mapState(['bookmark']),
  methods: {
    showLanguagePage(country) {
      LogService.consoleLogMessage('country')
      LogService.consoleLogMessage(country)
      LogService.consoleLogMessage(country.code)
      localStorage.setItem('lastPage', 'countries')
      this.$route.params.country_code = country.code
      var route = 'previewLanguages'
      this.$router.push({
        name: route,
        params: {
          country_code: country.code,
        },
      })
    },
  },
}
</script>
