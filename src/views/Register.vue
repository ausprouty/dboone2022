<template>
  <div>
    <NavBar called_by="register" />
    <div v-if="!this.authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now
      </p>
    </div>
    <div v-if="this.authorized">
      <h2>Existing Editors</h2>
      <Users />
      <h2>Register New Editor</h2>
      <form @submit.prevent="saveForm">
        <BaseInput
          v-model="firstname"
          label="First Name"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.firstname.$error }"
          @blur="$v.firstname.$touch()"
        />
        <template v-if="$v.firstname.$error">
          <p v-if="!$v.firstname.required" class="errorMessage">
            First name is required.
          </p>
        </template>

        <BaseInput
          v-model="lastname"
          label="Last Name"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.lastname.$error }"
          @blur="$v.firstname.$touch()"
        />
        <template v-if="$v.lastname.$error">
          <p v-if="!$v.lastname.required" class="errorMessage">
            Last name is required.
          </p>
        </template>
        Country Scope:
        <v-select
          multiple
          :reduce="(display) => display.code"
          :options="this.scope_options"
          label="display"
          v-model="scope"
        ></v-select>
        Language Scope:
        <v-select
          multiple
          :reduce="(display) => display.code"
          :options="this.language_options"
          label="display"
          v-model="$v.languages.$model"
        ></v-select>
        <BaseInput
          v-model="$v.start_page.$model"
          label="Start Page"
          type="text"
          class="field"
          :class="{ error: $v.start_page.$error }"
          @mousedown="$v.start_page.$touch()"
        />
        <template v-if="$v.start_page.$error">
          <p v-if="!$v.start_page.required" class="errorMessage">
            Start Page is required
          </p>
        </template>

        <BaseInput
          v-model="username"
          label="Username"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.username.$error }"
          @blur="$v.username.$touch()"
        />
        <template v-if="$v.username.$error">
          <p v-if="!$v.username.required" class="errorMessage">
            Username is required.
          </p>
        </template>

        <BaseInput
          v-model="password"
          label="Password"
          type="password"
          placeholder
          class="field"
          :class="{ error: $v.password.$error }"
          @blur="$v.password.$touch()"
        />
        <template v-if="$v.password.$error">
          <p v-if="!$v.password.required" class="errorMessage">
            Password is required.
          </p>
        </template>
        <div v-if="!this.registered">
          <p class="errorMessage">{{ this.error_message }}</p>
        </div>

        <br />
        <br />
        <button class="button red" @click="saveUserForm">Register</button>
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
import Users from '@/views/Users.vue'
import { required } from 'vuelidate/lib/validators'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import { countriesMixin } from '@/mixins/CountriesMixin.js'
import store from '@/store/store.js'

export default {
  components: {
    NavBar,
    Users,
    'v-select': vSelect,
  },
  mixins: [authorizeMixin, countriesMixin],
  data() {
    return {
      default_start_page: '/preview/languages/M2',
      firstname: null,
      lastname: null,
      scope: null,
      languages: null,
      start_page: null,
      username: null,
      password: null,
      submitted: false,
      wrong: null,
      registered: true,
      scope_options: [],
      language_options: [],
    }
  },
  computed: mapState(['user']),
  validations: {
    firstname: { required },
    lastname: { required },
    scope: { required },
    languages: { required },
    start_page: { required },
    username: { required },
    password: { required },
  },

  methods: {
    async saveUserForm() {
      try {
        var params = {}
        params.authorizer = store.state.user.uid
        params.firstname = this.firstname
        params.lastname = this.lastname
        // format scope
        var length = this.scope.length
        var scope_formatted = ''
        var temp = ''
        for (var i = 0; i < length; i++) {
          temp = scope_formatted + this.scope[i].code
          scope_formatted = temp
        }
        temp = scope_formatted.replace(/\|\|/g, '|')
        params.scope = temp
        // format language
        length = this.languages.length
        var languages_formatted = ''
        temp = ''
        for (i = 0; i < length; i++) {
          temp = languages_formatted + this.languages[i].code
          languages_formatted = temp
        }
        temp = languages_formatted.replace(/\|\|/g, '|')
        params.languages = temp
        // remaining values
        params.start_page = this.start_page
        params.username = this.username
        params.password = this.password

        LogService.consoleLogMessage('params from SaveForm')
        LogService.consoleLogMessage(params)
        var res = null
        res = await AuthorService.registerUser(params)
        LogService.consoleLogMessage('res from Author Service')
        LogService.consoleLogMessage(res)
        if (res.data.error) {
          this.registered = false
          this.error_message = res.data.message
        } else {
          location.reload(true)
        }
      } catch (error) {
        LogService.consoleLogError('Register There was an error ', error) //
      }
    },
    startPageOptions() {
      if (!this.$v.start_page.$model) {
        this.$v.start_page.$model = this.default_start_page
      }
    },
    async languageOptions() {
      var options = [
        { display: 'Global', code: '|*|' },
        { display: 'English', code: '|eng|' },
        { display: 'French', code: '|fra|' },
        { display: 'Simplifed Chinese', computed: '|cmn|' },
      ]
      this.language_options = options
      return
    },
    async scopeOptions() {
      await this.getCountries()
      var options = []
      var option = {}
      LogService.consoleLogMessage(this.countries)
      var length = this.countries.length
      for (var i = 0; i < length; i++) {
        option = {}
        if (this.countries[i].english) {
          option.display = this.countries[i].english
        } else {
          option.display = this.countries[i].name
        }
        option.code = '|' + this.countries[i].code + '|'
        options.push(option)
      }
      option = {}
      option.display = 'Global'
      option.code = '*'
      options.push(option)
      LogService.consoleLogMessage(options)
      this.scope_options = options
      return
    },
  },
  async created() {
    //this.authorized = this.authorize('register', 'global')
    this.authorized = this.authorize('register', this.$route.params)
    await this.scopeOptions()
    await this.languageOptions()
    this.startPageOptions()
  },
}
</script>
