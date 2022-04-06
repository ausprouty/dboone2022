<template>
  <div>
    <NavBar called_by="ApkMaker" />
    <div v-if="!this.authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now
      </p>
    </div>
    <div v-if="this.authorized">
      <form>
        <div>
          <h1>Apk Maker for {{ this.country_name }}</h1>
          <p>
            This page allows you to create the source code for an Apk file which
            will have all the content and videos.
          </p>
          <p>
            For sensitive countries be sure to click "Remove External Links"
          </p>
        </div>

        <div>
          <label for="language">
            <h3>Language</h3>
          </label>
          <v-select
            label="language_name"
            :options="language_options"
            placeholder="Select"
            v-model="$v.apk_settings.$model.language"
          >
          </v-select>
        </div>
        <h4>Existing Builds</h4>

        <div v-for="build in builds" :key="build">
          {{ build }}
        </div>
        <div>
          <label for="build">
            <h3>Build</h3>
          </label>
          <BaseInput
            v-model="$v.apk_settings.$model.build"
            label=""
            type="text"
            default="eng.m1.1"
            class="field"
          />
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
            v-model="$v.apk_settings.$model.langauge_footer"
            :options="footers"
            class="field"
          />
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
      </form>
      <button class="button" @click="showProgress()">Show Progress</button>

      <div v-if="this.show_progress">
        <h1>{{ this.apk_settings.language.language_name }}</h1>
        <div>
          <button
            class="button"
            v-bind:class="progress"
            @click="verifyLibraries()"
          >
            {{ library_text }}
          </button>
        </div>
        <ApkBooks :apk_settings="apk_settings" />
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
  </div>
</template>
<script>
import ApkService from '@/services/ApkService.js'
import ApkBooks from '@/components/ApkBooks.vue'
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBarAdmin.vue'
import axios from 'axios'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { required } from 'vuelidate/lib/validators'
import vSelect from 'vue-select'
export default {
  mixins: [authorizeMixin],
  props: ['country_code'],
  components: {
    NavBar,
    ApkBooks,
    'v-select': vSelect,
  },
  data() {
    return {
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      apk_root: process.env.VUE_APP_ROOT_Apk,
      authorized: false,
      videolist_text: 'Create Media List for Apk Card',
      common_text: 'Check Common Files',
      language_text: 'Create Language Index',
      library_text: 'Create Library Index',
      language_options: [],
      builds: [],
      country_name: null,
      show_progress: false,
      site: process.env.VUE_APP_SITE,
      language_data: [],
      footers: [],
      bat_text: 'Download Media Batch Files',
      apk_settings: {
        language: {
          country_code: null,
          flag: null,
          folder: null,
          language_iso: null,
          language_name: null,
          library: null,
        },
        langauge_footer: null,
        remove_external_links: false,
        build: null,
        action: 'apk',
      },
    }
  },
  computed: {
    bookmark() {
      return this.$store.state.bookmark
    },
  },
  validations: {
    apk_settings: {
      required,
      $each: {
        language: { required },
        langauge_footer: { required },
        remove_external_links: { required },
        build: { required },
        action: { required },
      },
    },
  },
  methods: {
    showProgress() {
      this.show_progress = true
      console.log(this.apk_settings.language)
    },

    async verifyCommonFiles() {
      this.common_text = 'Verifying'
      var params = this.$route.params
      var response = await ApkService.verifyCommonFiles(params)
      console.log(response)
      this.common_text = 'Verified'
    },
    async verifyLanguageIndex() {
      this.language_text = 'Verifying'
      var params = this.$route.params
      var response = await ApkService.verifyLanguageIndex(params)
      console.log(response)
      this.language_text = 'Verified'
    },
    async verifyLibraries() {
      var params = this.language
      console.log(this.apk_setting)
      params.apk_settings = JSON.stringify(this.apk_setting)
      this.library_text = 'Publishing'
      this.progress = await ApkService.publish('libraries', params)
      this.library_text = 'Published'
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
  },
  async created() {
    this.authorized = this.authorize('write', this.$route.params)
    if (this.authorized) {
      await AuthorService.bookmark(this.$route.params)
      this.country_name = this.bookmark.country.name
      this.footers = await ApkService.getFooters(this.$route.params)
      this.language_options = await ApkService.getLanguages(this.$route.params)
      this.builds = await ApkService.getBuilds(this.$route.params)
    }
  },
}
</script>
<style scoped>
div.spacer {
  height: 30px;
}
</style>
