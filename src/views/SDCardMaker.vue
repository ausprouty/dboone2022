<template>
  <div>
    <NavBar called_by="SDCardMaker" />
    <div v-if="!this.authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now
      </p>
    </div>
    <div v-if="this.authorized">
      <BaseSelect label="Languages" :options="languages" class="field" />
      <BaseSelect label="Footer" :options="footers" class="field" />
      <label for="external_links">
        <h2>Remove External Links</h2>
      </label>
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
      <input type="checkbox" id="external_links" />

      <SDCardActions
        v-for="language in languages_selected"
        :key="language.iso"
        :language="language"
      />
    </div>
  </div>
</template>
<script>
import { mapState } from 'vuex'

import SDCardService from '@/services/SDCardService.js'
import NavBar from '@/components/NavBarAdmin.vue'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
export default {
  mixins: [authorizeMixin],
  props: ['country_code'],
  computed: mapState(['bookmark']),
  components: {
    NavBar,
  },
  data() {
    return {
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      authorized: false,
      sdcard: {
        languages: [],
        footers: null,
        external_links: false,
        action: 'sdcard',
        series: null,
      },
      languages_selected: [],
    }
  },
  methods: {
    takeAction() {
      console.log('take action')
    },
  },
  async created() {
    this.authorized = this.authorize('write', this.$route.params)
    if (this.authorized) {
      this.languages = await SDCardService.getLanguages(this.$route.params)
      console.log (this.languages)
      this.footers = await SDCardService.getFooters(this.$route.params)
      console.log (this.footers)
    }
  },
}
</script>
