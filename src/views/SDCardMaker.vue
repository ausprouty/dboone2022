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
        <h1>SD Card Maker</h1>
        <div>
          <label for="external_links">
            <h3>Remove External Links</h3>
          </label>
          &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
          <input type="checkbox" id="external_links" />
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

      <SDCardBooks
        v-for="language in sdcard.languages"
        :key="language.language_iso"
        :language="language"
      />
    </div>
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect'
import { mapState } from 'vuex'
import SDCardBooks from '@/components/SDCardBooks.vue'
import SDCardService from '@/services/SDCardService.js'
import NavBar from '@/components/NavBarAdmin.vue'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { required } from 'vuelidate/lib/validators'
export default {
  mixins: [authorizeMixin],
  props: ['country_code'],
  computed: mapState(['bookmark']),
  components: {
    NavBar,
    SDCardBooks,
    Multiselect,
  },
  data() {
    return {
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      authorized: false,
      videolist_text: 'Create Media List for SD Card',
      languages: [],
      language_data: [],
      footers: [],
      sdcard: {
        languages: [],
        footers: null,
        external_links: false,
        action: 'sdcard',
        series: null,
      },
    }
  },
  validations: {
    sdcard: {
      required,
      $each: {
        languages: { required },
        footers: { required },
        external_links: { required },
        actions: { required },
        series: { required },
      },
    },
  },
  methods: {
    takeAction() {
      console.log('take action')
    },
  },
  async created() {
    this.authorized = this.authorize('write', this.$route.params)
    if (this.authorized) {
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
