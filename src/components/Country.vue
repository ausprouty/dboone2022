<template>
  <div v-if="country.publish">
    <div class="app-link" v-on:click="showPage(country)">
      <div class="shadow-card -shadow">
        <img
          v-bind:src="'/sites/default/images/country/' + country.image"
          class="book"
        />
        <div class="card-names">
          <span class="card-name">{{ country.name }}</span>
          <br />
          <span class="card-name-english">{{ country.english }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { countriesMixin } from '@/mixins/CountriesMixin.js'

export default {
  mixins: [countriesMixin],
  props: {
    country: Object,
  },
  data: function () {
    return {
      saving: false,
      bMark: this.$store.state.bookmark,
    }
  },
  computed: mapState(['bookmark', ]),
}
</script>
