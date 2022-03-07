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
      <div>
        <h1>SD Card Maker for {{ this.country_name }}</h1>
        <p>
          This page allows you to create an SD Card which will have all the
          content and videos.
        </p>
        <p>For sensitive countries be sure to click "Remove External Links"</p>
        <p>
          You will find all content in {{ this.sdroot }}{{  this.sdcard.subDirectory }}
        </p>
      </div>
      <div>
        <label for="external_links">
          <h3>Remove External Links</h3>
        </label>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <input type="checkbox" id="external_links" checked />
        <h3>Footer</h3>
        <BaseSelect
          v-model="$v.sdcard.$model.footer"
          :options="footers"
          class="field"
        />
      </div>

      <h3>Languages</h3>
      <multiselect
        v-model="$v.sdcard.$model.languages"
        @input="sdSubDir"
        :options="language_data"
        :multiple="true"
        :close-on-select="false"
        :clear-on-select="false"
        :preserve-search="true"
        placeholder="Choose one or more"
        label="language_name"
        track-by="language_name"
        :preselect-first="false"
      >
        <template slot="selection" slot-scope="{ values, search, isOpen }"
          ><span
            class="multiselect__single"
            v-if="values.length &amp;&amp; !isOpen"
            >{{ values.length }} options selected</span
          ></template
        >
      </multiselect>
    </div>
    <div class="spacer"></div>

    <SDCardBooks
      v-for="language in sdcard.languages"
      :key="language.language_iso"
      :language="language"
    />
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect'

import SDCardBooks from '@/components/SDCardBooks.vue'
import SDCardService from '@/services/SDCardService.js'
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBarAdmin.vue'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { required } from 'vuelidate/lib/validators'
export default {
  mixins: [authorizeMixin],
  props: ['country_code'],
  components: {
    NavBar,
    SDCardBooks,
    Multiselect,
  },
  data() {
    return {
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      sdroot: process.env.VUE_APP_ROOT_SDCARD,
      authorized: false,
      videolist_text: 'Create Media List for SD Card',
      languages: [],
      country_name: null,
      dir_scard: null,
      language_data: [],
      footers: [],
      sdcard: {
        languages: [],
        footer: null,
        external_links: false,
        action: 'sdcard',
        series: null,
        subDirectory: null,
      },
    }
  },
  computed: {
    bookmark() {
      return this.$store.state.bookmark
    },
  },
  validations: {
    sdcard: {
      required,
      $each: {
        languages: { required },
        footer: { required },
        external_links: { required },
        actions: { required },
        series: { required },
        subDirectory: {},
      },
    },
  },
  methods: {
    sdSubDir() {
      var sub = ''
      var temp = ''
      var len = this.sdcard.languages.length
      for (var i = 0; i < len; i++) {
        temp = sub.concat('.', this.sdcard.languages[i].language_iso)
        sub = temp
      }
      this.sdcard.subDirectory = sub
      this.$store.dispatch('setSDCardSettings', this.sdcard)
      return sub
    },
  },
  async created() {
    this.authorized = this.authorize('write', this.$route.params)
    if (this.authorized) {
      await AuthorService.bookmark(this.$route.params)
      this.country_name = this.bookmark.country.name
      this.language_data = await SDCardService.getLanguages(this.$route.params)
      this.$store.dispatch('setLanguages', [this.language_data])
      var len = this.language_data.length
      for (var i = 0; i < len; i++) {
        this.languages[i + 1] = this.language_data[i].language_name
      }
      this.footers = await SDCardService.getFooters(this.$route.params)
    }
  },
}
</script>
<style scoped>
div.spacer {
  height: 30px;
}
</style>
