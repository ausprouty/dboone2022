<template>
  <div>
    <NavBar />
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
import LogService from '@/services/LogService.js'
import { bookMarkMixin } from '@/mixins/BookmarkMixin.js'

export default {
  mixins: [bookMarkMixin],

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
      await this.CheckBookmarks(this.$route.params)
      var response = await ContentService.getCountries(this.$route.params)
      this.countries = response.text
    } catch (error) {
      LogService.consoleLogError('There was an error in Countries.vue:', error)
    }
  },
}
</script>

<style></style>
