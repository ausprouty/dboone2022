<template>
  <div>
    <NavBar called_by ="language" />

    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">
      There was an error... {{ this.error_message }}
    </div>
    <div class="content" v-if="loaded">
      <div v-if="this.authorized">
        <h1>
          Languages for {{ this.$route.params.country_code }}
          <a
            target="_blank"
            class="help"
            v-bind:href="
              this.prototype_url + 'HD/eng/help-1/languages_edit.html'
            "
          >
            <img class="help-icon" src="/sites/default/images/icons/help.png" />
          </a>
        </h1>
        <h2>Common Terms</h2>
        <div class="form">
          <BaseInput
            v-model="choose_language"
            label="Choose Language:"
            type="text"
            placeholder="Choose Language"
            class="field"
          />
        </div>
        <div class="form">
          <BaseInput
            v-model="more_languages"
            label="More Languages:"
            type="text"
            placeholder="More Languages"
            class="field"
          />
        </div>
        <br />
        <hr />

        <div>
          <button class="button" @click="publishAll">
            Select ALL to publish?
          </button>
          <LanguageEdit
            v-for="language in $v.languages.$each.$iter"
            :language="language"
            @clicked="onClickChild"
          />

          <button class="button" @click="addNewLanguageForm">
            New Language
          </button>
        </div>
        <div v-if="!$v.$anyError">
          <button class="button red" @click="saveForm">Save Changes</button>
        </div>
        <div v-if="$v.$anyError">
          <button class="button grey">Disabled</button>
          <p v-if="$v.$anyError" class="errorMessage">
            Please fill out the required field(s).
          </p>
        </div>
      </div>
      <div v-if="!this.authorized">
        <p>
          You need to
          <a href="/login">login to make changes</a> here
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import NavBar from '@/components/NavBarAdmin.vue'
import LanguageEdit from '@/components/LanguageEdit.vue'
import ContentService from '@/services/ContentService.js'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import { mapState } from 'vuex'

import { languageMixin } from '@/mixins/LanguageMixin.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { required } from 'vuelidate/lib/validators'
//import { validFoldername } from '@/validators/Validator.js'
export default {
  mixins: [languageMixin, authorizeMixin],
  props: ['country_code'],
  components: {
    NavBar,
    LanguageEdit,
  },
  computed: mapState(['bookmark']),
  data() {
    return {
      choose_language: 'Choose Language',
      more_languages: 'More Languages',
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      languages: {
        name: null,
        iso: null,
        id: null,
        folder: null,
        image_dir: null,
        titles: null,
        lrdir: null,
        custom: null,
        download: 'Download for offline use',
        download_ready: 'Ready for offline use',
        read_more: 'Read More',
        read_more_online: 'Read More Online',
        read: 'Read or watch',
        send_notes: 'Send Notes and Action Points',
        notes: 'Notes: (click outside box to save)',
        watch: 'Watch (uses data)',
        watch_offline: 'Watch',
        listen: 'Listen online',
        listen_offline: 'Listen',
        bible_nt: null,
        bible_ot: null,
        prototype: null,
        publish: null,
      },

      authorized: false,
    }
  },
  validations: {
    languages: {
      required,
      $each: {
        name: { required },
        iso: { required },
        id: {},
        folder: { required },
        image_dir: { required },
        custom: {},
        download: {},
        download_ready: {},
        read_more: {},
        read_more_online: {},
        read: {},
        send_notes: {},
        notes: {},
        watch: {},
        watch_offline: {},
        listen: {},
        listen_offline: {},
        bible_nt: {},
        bible_ot: {},
        titles: {},
        rldir: {},
        prototype: {},
        publish: {},
      },
    },
  },
  methods: {
    // this should delete on record
    onClickChild(value) {
      this.languages.splice(value, 1)
    },
    addNewLanguageForm() {
      if (!this.languages) {
        this.languages = []
      }
      var idvalue = this.languages.length + 1
      this.languages.push({
        id: idvalue,
        folder: null,
        iso: null,
        name: null,
        image_dir: null,
        custom: null,
        download: null,
        download_ready: null,
        read_more: null,
        read_more_online: null,
        read: null,
        send_notes: null,
        notes: null,
        watch: null,
        watch_offline: null,
        listen: null,
        listen_offline: null,
        bible_ot: null,
        bible_nt: null,
        titles: null,
        rldir: 'ltr',
        prototype: null,
        publish: null,
      })
    },
    publishAll() {
      var arrayLength = this.languages.length
      LogService.consoleLogMessage(' Item count:' + arrayLength)
      for (var i = 0; i < arrayLength; i++) {
        this.$v.languages.$each.$iter[i].publish.$model = true
      }
    },

    async saveForm() {
      try {
        //this.$store.dispatch('newBookmark', 'clear')
        var output = {}
        output.languages = this.languages
        output.more_languages = this.more_languages
        output.choose_language = this.choose_language
        LogService.consoleLogMessage('output')
        LogService.consoleLogMessage(output)
        var valid = ContentService.validate(output)
        this.content.text = JSON.stringify(valid)
        this.$route.params.filename = 'languages'
        this.content.filetype = 'json'
        this.content.route = JSON.stringify(this.$route.params)
        LogService.consoleLogMessage('going to directory languages')
        LogService.consoleLogMessage(this.content)
        AuthorService.createDirectoryLanguages(this.content)
        var response = await AuthorService.createContentData(this.content)
        if (response.data.error != true) {
          this.$router.push({
            name: 'previewLanguages',
            params: {
              country_code: this.$route.params.country_code,
            },
          })
        } else {
          this.error = true
          this.loaded = false
          this.error_message = response.data.message
        }
      } catch (error) {
        LogService.consoleLogError('LANGUAGES EDIT There was an error ', error)
        this.error = true
        this.loaded = false
        this.error_message = error
      }
    },
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  async created() {
    try {
      this.authorized = this.authorize('write', this.$route.params)
      LogService.consoleLogMessage('this authorized')
      if (this.authorized) {
        await this.getLanguages(this.$route.params)
      }
      this.loaded = true
      this.loading = false
    } catch (error) {
      LogService.consoleLogError(
        'There was an error in LanguagesEdit.vue:',
        error
      ) // Logs out the error
    }
  },
}
</script>
