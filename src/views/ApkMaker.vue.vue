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
        <h1>APK Maker for {{ this.country_name }}</h1>
        <p>
          This page allows you to create the source code for an APK file which will have all the
          content and videos.
        </p>
        <p>For sensitive countries be sure to click "Remove External Links"</p>
        <p>
         <form>
            <BaseInput
              v-model="$v.apk_settings.$model.build"
              label="APK Build Name and Number"
              type="text"
              placeholder="engM1.346"
              class="field"
            />
        </p>
      </div>
      <div>
        <label for="remove_external_links">
          <h3>Remove External Links</h3>
        </label>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <input
          type="checkbox"
          id="remove_external_links"
          v-model="$v.apk_settings.$model.remove_external_links"
        />
        <h3>Footer</h3>
        <BaseSelect
          v-model="$v.apk_settings.$model.footer"
          :options="footers"
          class="field"
        />
      </div>

      <h3>Languages</h3>
      <multiselect
        v-model="$v.apk_settings.$model.languages"
        @input="sdSubDir"
        :options="language_data"
        :multiple="false"
        :close-on-select="false"
        :clear-on-select="false"
        :preserve-search="true"
        placeholder="Choose one"
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
    <div class="row">
      <div class="column">
        <button class="button" @click="verifyCommonFiles()">
          {{ this.common_text }}
        </button>
      </div>
    </div>
    <div class="row">
      <div class="column">
        <button class="button" @click="verifyLanguageIndex()">
          {{ this.language_text }}
        </button>
      </div>
    </div>

    <button class="button" @click="showProgress()">Show Progress</button>

    <div v-if="this.show_progress">
      <ApkBooks
        v-bind="this.apk_settings.language"
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
        <li>
          Then upload the zip file to sites/{{ this.site }}/media/LANGUAGE_ISO
        </li>
        <li>Check to see that all audio files are in the audio directory</li>
      </ul>

      <div class="row">
        <div class="column">
          <button class="button" @click="zipMediaBatFiles()">
            {{ this.bat_text }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Multiselect from 'vue-multiselect'

import ApkBooks from '@/components/ApkBooks.vue'
import ApkService from '@/services/ApkService.js'
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
    ApkBooks,
    Multiselect,
  },
  data() {
    return {
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      apk_root: process.env.VUE_APP_ROOT_APK,
      authorized: false,
      videolist_text: 'Create Media List for APK Card',
      common_text: 'Check Common Files',
      language_text: 'Create Language Index',
      languages: [],
      country_name: null,
      show_progress: false,
      site: process.env.VUE_APP_SITE,
      language_data: [],
      footers: [],
      bat_text: 'Download Media Batch Files',
      apk_settings: {
        language: null,
        footer: null,
        remove_external_links: false,
        action: 'apk',
        build: null,
        series: null,
      },
    }
  },
  computed: {
    bookmark() {
      return this.$store.state.bookmark
    },
  },
  provide: function () {  // see https://v2.vuejs.org/v2/guide/components-edge-cases.html#Dependency-Injection
    return {
      getApkSettings: this.apk_settings
    }
  },
  validations: {
    apk_settings: {
      required,
      $each: {
        language: { required },
        footer: { required },
        remove_external_links: { required },
        action: { required },
        build: { required },
        series: { required },
      },
    },
  },
  methods: {
    showProgress() {
      this.show_progress = true
    },
    async verifyLanguageIndex() {
      this.language_text = 'Verifying'
      var params = this.$route.params
      var response = await ApkService.verifyLanguageIndex(params)
      console.log(response)
      this.language_text = 'Verified'
    },

    async verifyCommonFiles() {
      this.common_text = 'Verifying'
      var params = this.$route.params
      var response = await ApkService.verifyCommonFiles(params)
      console.log(response)
      this.common_text = 'Verified'
    },
    async zipMediaBatFiles() {
      this.bat_text = 'Downloading'
      var params = this.$route.params
      var response = await ApkService.zipMediaBatFiles(params)
      console.log(response)
      var filename = response
      this.bat_text = 'Finished'
      this.downloadMediaBatFiles(filename)
    },
    async downloadMediaBatFiles(filename) {
      var download_name = 'MediaBatFiles' + this.apk.subDirectory + '.zip'
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


  async created() {
    this.authorized = this.authorize('write', this.$route.params)
    if (this.authorized) {
      await AuthorService.bookmark(this.$route.params)
      this.country_name = this.bookmark.country.name
      this.language_data = await ApkService.getLanguages(this.$route.params)
      this.$store.dispatch('setLanguages', [this.language_data])
      var len = this.language_data.length
      for (var i = 0; i < len; i++) {
        this.languages[i + 1] = this.language_data[i].language_name
      }
      this.footers = await ApkService.getFooters(this.$route.params)
    }
  },
}
</script>
<style scoped>
div.spacer {
  height: 30px;
}
</style>
