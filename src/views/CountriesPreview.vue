<template>
  <div class="preview">
    <NavBar />

    <div v-if="this.publish">
      <button class="button" @click="localPublish('website')">
        {{ this.publish_text }}
      </button>
    </div>
    <div v-if="this.prototype">
      <div>
        <button class="button" @click="localPublish('prototype')">
          {{ this.prototype_text }}
        </button>
      </div>
      <div>
        <button class="button" @click="localPublish('sdcard')">
          {{ this.sdcard_text }}
        </button>
      </div>
    </div>
    <h1>
      Select Country
      <a
        target="_blank"
        class="help"
        v-bind:href="this.prototype_url + 'HD/eng/help-1/countries_select.html'"
      >
        <img class="help-icon" src="/sites/default/images/icons/help.png" />
      </a>
      <img
        @click="showPreview()"
        class="help-icon"
        src="/sites/default/images/icons/preview.png"
      />
    </h1>
    <Country
      v-for="country in countries"
      :key="country.code"
      :country="country"
    />
    <p class="version">Version 2.05</p>

    <div v-if="this.authorized">
      <button class="button" @click="editCountries">Edit</button>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <button class="button" @click="sortCountries">Sort</button>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import NavBar from '@/components/NavBarAdmin.vue'
import Country from '@/components/CountryPreview.vue'
import LogService from '@/services/LogService.js'
import PrototypeService from '@/services/PrototypeService.js'
import PublishService from '@/services/PublishService.js'
import SDCardService from '@/services/SDCardService.js'
import { countriesMixin } from '@/mixins/CountriesMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { publishMixin } from '@/mixins/PublishMixin.js'

export default {
  mixins: [countriesMixin, authorizeMixin, publishMixin],
  components: {
    Country,
    NavBar,
  },
  data() {
    return {
      authorized: false,
      publish: false,
      prototype_text: 'Prototype Languages',
      publish_text: 'Publish Languages',
      sdcard_text: 'Update SD Card',
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
    }
  },
  computed: mapState(['user']),

  methods: {
    showPreview() {
      var root = process.env.VUE_APP_PROTOTYPE_CONTENT_URL
      var link = root + 'languages.html'
      window.open(link, '_blank')
    },
    editCountries() {
      this.$router.push({
        name: 'editCountries',
      })
    },
    sortCountries() {
      this.$router.push({
        name: 'sortCountries',
      })
    },
    goBack() {
      window.history.back()
    },
    async localPublish(location) {
      var response = null
      var params = {}
      params.recnum = this.recnum
      params.route = JSON.stringify(this.$route.params)
      if (location == 'prototype') {
        this.prototype_text = 'Prototyping'
        response = await PrototypeService.publish('countries', params)
        this.prototype_text = 'Prototyped'
      }
      if (location == 'sdcard') {
        this.sdcard_text = 'Publishing'
        response = await SDCardService.publish('countries', params)
        this.sdcard_text = 'Published'
      }
      if (location == 'website') {
        this.publish_text = 'Publishing'
        response = await PublishService.publish('countries', params)
        this.publish_text = 'Published'
      }
      if (response['error']) {
        this.error = response['message']
        this.loaded = false
      } else {
        //this.UnsetBookmarks()
        this.recnum = null
        this.loaded = false
        this.loading = true
        this.publish = false
        await this.loadView()
      }
    },
    /*
    async localBookmark(recnum) {
      var param = {}
      param.recnum = recnum
      param.library_code = this.$route.params.library_code
      var bm = await PrototypeService.publish('bookmark', param)
      LogService.consoleLogMessage('localBookmark')
      LogService.consoleLogMessage(bm)
    },
    */
    async loadView() {
      try {
        await this.getCountries()
        /* if (this.recnum) {
          this.localBookmark(this.recnum)
        }
        */
        this.authorized = this.authorize('write', this.$route.params)
        // authorize for prototype and publish
        this.publish = false
        this.prototype = false
        if (this.recnum && !this.prototype_date) {
          this.prototype = this.mayPrototypeCountries
          if (this.prototype) {
            this.prototype_text = 'Prototype Languages'
          }
        }
        if (this.recnum && this.prototype_date) {
          LogService.consoleLogMessage('I am checking to see if I can publish')
          this.publish = this.mayPublishCountries
          if (this.publish) {
            LogService.consoleLogMessage('I can publish and prototype again')
            this.prototype = true
            this.prototype_text = 'Prototype Languages Again'
            if (this.publish_date) {
              this.publish_text = 'Publish Languages Again'
            }
          }
        }
        // end authorization for prototype and publish
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in Countries.vue:',
          error
        ) // Logs out the error
      }
    },
    /*toFormData(obj) {
      var form_data = new FormData()
      for (var key in obj) {
        form_data.append(key, obj[key])
      }
      LogService.consoleLogMessage('form_data')
      // Display the key/value pairs
      for (var pair of form_data.entries()) {
        LogService.consoleLogMessage(pair[0] + ', ' + pair[1])
      }
      return form_data
    },
    */
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  created() {
    this.loadView()
  },
}
</script>

<style></style>
