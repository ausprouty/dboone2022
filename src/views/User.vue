<template>
  <div>
    <NavBar called_by="user" />
    <div v-if="!this.authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now
      </p>
    </div>
    <div v-if="this.authorized">
      <h2>Update User {{ member.uid }}</h2>
      <form @submit.prevent="saveForm">
        <BaseInput
          v-model="$v.member.firstname.$model"
          label="First Name"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.member.firstname.$error }"
          @mousedown="$v.member.firstname.$touch()"
        />
        <template v-if="$v.member.firstname.$error">
          <p v-if="!$v.member.firstname.required" class="errorMessage">
            First Name is required
          </p>
        </template>

        <BaseInput
          v-model="$v.member.lastname.$model"
          label="Last Name"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.member.lastname.$error }"
          @mousedown="$v.member.lastname.$touch()"
        />
        <template v-if="$v.member.lastname.$error">
          <p v-if="!$v.member.lastname.required" class="errorMessage">
            Last Name is required
          </p>
        </template>
        Countries:
        <v-select
          multiple
          :reduce="(display) => display.code"
          :options="this.country_options"
          label="display"
          v-model="$v.member.scope_countries.$model"
        ></v-select>
        <v-select
          multiple
          :reduce="(display) => display.code"
          :options="this.language_options"
          label="display"
          v-model="$v.member.scope_languages.$model"
        ></v-select>
        <BaseInput
          v-model="$v.member.start_page.$model"
          label="Start Page"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.member.start_page.$error }"
          @mousedown="$v.member.start_page.$touch()"
        />
        <template v-if="$v.member.start_page.$error">
          <p v-if="!$v.member.start_page.required" class="errorMessage">
            Start Page is required
          </p>
        </template>

        <BaseInput
          v-model="$v.member.username.$model"
          label="Username"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.member.username.$error }"
          @mousedown="$v.member.username.$touch()"
        />
        <template v-if="$v.member.username.$error">
          <p v-if="!$v.member.username.required" class="errorMessage">
            Username is required
          </p>
        </template>

        <BaseInput
          v-model="$v.member.password.$model"
          label="Password"
          type="password"
          placeholder
          class="field"
          :class="{ error: $v.member.password.$error }"
          @mousedown="$v.member.password.$touch()"
        />
        <template v-if="$v.member.password.$error">
          <p v-if="!$v.member.password.required" class="errorMessage">
            Password is required
          </p>
        </template>

        <br />
        <br />
        <button class="button green" @click="saveForm">Update</button>
        <button class="button red" @click="deleteForm">Delete</button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import NavBar from '@/components/NavBarAdmin.vue'
import vSelect from 'vue-select'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { userMixin } from '@/mixins/UserMixin.js'
import { countriesMixin } from '@/mixins/CountriesMixin.js'
import { languageMixin } from '@/mixins/LanguageMixin.js'
import store from '@/store/store.js'

export default {
  components: {
    NavBar,
    'v-select': vSelect,
  },
  props: ['uid'],
  mixins: [authorizeMixin, countriesMixin, languageMixin, userMixin],
  data() {
    return {
      submitted: false,
      wrong: null,
      registered: true,
    }
  },
  computed: mapState(['user']),
  methods: {
    formatCountryArray() {
      var length = this.$v.member.scope_countries.$model.length
      var countries_formatted = ''
      var temp = ''
      for (var i = 0; i < length; i++) {
        temp =
          countries_formatted + this.$v.member.scope_countries.$model[i].code
        countries_formatted = temp
      }
      temp = countries_formatted.replace(/\|\|/g, '|')
      return temp
    },
    formatLanguageArray() {
      var length = this.$v.member.scope_languages.$model.length
      var formatted = ''
      var temp = ''
      for (var i = 0; i < length; i++) {
        temp = formatted + this.$v.member.scope_languages.$model[i].code
        formatted = temp
      }
      temp = formatted.replace(/\|\|/g, '|')
      return temp
    },
    async saveForm() {
      try {
        var params = this.member
        LogService.consoleLogMessage('Save Form')
        LogService.consoleLogMessage(this.member)
        LogService.consoleLogMessage(this.$v.member.scope_countries.$model)
        // for some strange reason it shows up as an array sometimes and other times as a string
        if (Array.isArray(this.$v.member.scope_countries.$model)) {
          params.scope_countries = this.formatCountryArray()
        } else {
          params.scope_countries = this.$v.member.scope_countries.$model
        }
        if (Array.isArray(this.$v.member.scope_languages.$model)) {
          params.scope_languages = this.formatLanguageArray()
        } else {
          params.scope_languages = this.$v.member.scope_languages.$model
        }
        params.start_page = this.$v.member.start_page.$model
        params.member_uid = this.member.uid
        params.authorizer = store.state.user.uid
        LogService.consoleLogMessage('params for SaveForm')
        LogService.consoleLogMessage(params)
        let res = null
        res = await AuthorService.updateUser(params)
        LogService.consoleLogMessage('res from Author Service')
        LogService.consoleLogMessage(res)
        if (res.data.error) {
          this.registered = false
          this.error_message = res.data.message
        } else {
          this.registered = true
          this.$router.push({
            name: 'farm',
          })
        }
      } catch (error) {
        LogService.consoleLogError('Update There was an error ', error)
      }
    },
    async deleteForm() {
      try {
        var params = {}
        params.authorizer = store.state.user.uid
        params.member_uid = this.member.uid
        params.member_username = this.member.username
        LogService.consoleLogMessage('params from DeleteForm')
        LogService.consoleLogMessage(params)
        let res = await AuthorService.deleteUser(params)
        LogService.consoleLogMessage('res from Author Service')
        LogService.consoleLogMessage(res)
        if (res.data.error) {
          this.registered = false
          this.error_message = res.data.message
        } else {
          this.registered = true
          this.$router.push({
            name: 'farm',
          })
        }
      } catch (error) {
        LogService.consoleLogError('Delete There was an error ', error)
      }
    },
  },
  async created() {
    this.authorized = this.authorize('register', this.$route.params)
    if (this.authorized) {
      try {
        var params = {}
        params.uid = this.$route.params.uid
        this.member = await AuthorService.getUser(params)
        await this.countryOptions()
        await this.languageOptions()
        this.member.password = null
        LogService.consoleLogMessage(this.member)
      } catch (error) {
        LogService.consoleLogError('There was an error in User.vue:', error)
      }
    }
  },
}
</script>
