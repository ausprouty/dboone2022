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
          You will find all content in {{ this.sdroot
          }}{{ this.sdcard.subDirectory }}
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

    <div class="spacer"></div>
    <SDCardBooks
      v-for="language in sdcard.languages"
      :key="language.language_iso"
      :language="language"
    />
    <p>After you make the Video List Bat files:</p>
    <ul>
      <li>Check for Errors in the error log</li>
      <li>Correct errors in html and republish</li>
      <li>Download any missing files</li>
      <li>Update Reference File</li>
      <li>
        Download, move to M:MC2/sdcard/
        {{ this.$route.params.country_code }}, unzip and run the bat files -
        (They take too much processing time to run remotely.)
      </li>
      <li>Make a zip file of the audio and video directories</li>
      <li>Then upload the zip file to sites/{{ this.site }}/media/LANGUAGE_ISO</li>
      <li>Check to see that all audio files are in the audio directory</li>
    </ul>

    <div class="row">
      <div class="column">
        <button class="button" @click="zipMediaBatFiles()">
          {{ this.bat_text }}
        </button>
      </div>
    </div>
    <div class="row">
      <div class="column">
        <button class="button" @click="verifyCommonFiles()">
          {{ this.common_text }}
        </button>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect'

import SDCardBooks from '@/components/SDCardBooks.vue'
import SDCardService from '@/services/SDCardService.js'
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBarAdmin.vue'
import axios from 'axios'
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
      common_text: 'Check Common Files',
      languages: [],
      country_name: null,
      site: process.env.VUE_APP_SITE,
      dir_scard: null,
      language_data: [],
      footers: [],
      bat_text: 'Download Media Batch Files',
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
    async verifyCommonFiles(){
      this.common_text = 'Verifying'
      var params = this.$route.params
      var response = await SDCardService.verifyCommonFiles(params)
      console.log(response)
      this.common_text = 'Verified'

    },
    async zipMediaBatFiles() {
      this.bat_text = 'Downloading'
      var params = this.$route.params
      var response = await SDCardService.zipMediaBatFiles(params)
      console.log(response)
      var filename = response
      this.bat_text = 'Finished'
      this.downloadMediaBatFiles(filename)
    },
    async downloadMediaBatFiles(filename) {
      var download_name = 'MediaBatFiles' + this.sdcard.subDirectory + '.zip'
      axios({
        url: process.env.VUE_APP_URL + filename,
        method: 'GET',
        responseType: 'blob',
      }).then((response) => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]))
        var fileLink = document.createElement('a')
        fileLink.href = fileURL
        fileLink.setAttribute('download', download_name)
        document.body.appendChild(fileLink)
        fileLink.click()
      })
    },

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
