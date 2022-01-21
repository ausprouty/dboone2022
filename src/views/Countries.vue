<template>
  <div>
    <NavBar called_by="country" />
    <h1>Select Country</h1>
    <Country
      v-for="country in countries"
      :key="country.code"
      :country="country"
    />
    <div class="version">
      <p class="version">Version 2.05</p>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import NavBar from '@/components/NavBarFront.vue'
import Country from '@/components/Country.vue'
import ContentService from '@/services/ContentService.js'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'

export default {
  components: {
    Country,
    NavBar,
  },
  computed: mapState([]),
  data() {
    return {
      countries: [],
    }
  },
  beforeCreate() {
    this.$route.params.version = 'current'
  },
  async created() {
    try {
      await AuthorService.bookmark(this.$route.params)
      var response = await ContentService.getCountries(this.$route.params)
      this.countries = response.text
    } catch (error) {
      LogService.consoleLogError('There was an error in Countries.vue:', error)
    }
  },
}
</script>

<style></style>
