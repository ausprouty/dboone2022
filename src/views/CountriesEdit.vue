<template>
  <div>
    <NavBar />
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">
      There was an error... {{ this.error_message }}
    </div>
    <div class="content" v-if="loaded">
      <h1>
        Countries
        <a
          target="_blank"
          class="help"
          v-bind:href="this.prototype_url + 'HD/eng/help-1/countries_edit.html'"
        >
          <img class="help-icon" src="/sites/default/images/icons/help.png" />
        </a>
      </h1>
      <div
        v-for="(country, index) in $v.countries.$each.$iter"
        :key="country.code.$model"
        :country="country"
      >
        <div
          class="app-card -shadow"
          v-bind:class="{ notpublished: !country.publish.$model }"
        >
          <div
            class="float-right"
            style="cursor: pointer"
            @click="deleteCountryForm(index)"
          >
            X
          </div>
          <form>
            <BaseInput
              v-model="country.name.$model"
              label="Country Name"
              type="text"
              placeholder="Country Name"
              class="field"
              :class="{ error: country.name.$error }"
              @blur="country.name.$touch()"
            />
            <template v-if="country.name.$error">
              <div class="errorMessage" v-if="!country.name.required">
                Country Name is required.
              </div>
            </template>

            <BaseInput
              v-model="country.english.$model"
              label="English Name"
              type="text"
              placeholder="English Name"
              class="field"
            />

            <BaseInput
              v-model="country.code.$model"
              label="Country ISO Code"
              type="text"
              placeholder="2 letter ISO code"
              class="field"
              :class="{ error: country.code.$error }"
              @blur="country.code.$touch()"
              @input="forceUpperCODE(country.code.$model)"
            />

            <br />
            <br />
            <div v-if="!country.code.$model">
              <p>
                <a
                  target="a_blank"
                  href="https://en.wikipedia.org/wiki/List_of_ISO_3166_country_codes"
                  >Country Reference File</a
                >
              </p>
            </div>
            <template v-if="country.code.$error">
              <div class="errorMessage" v-if="!country.code.required">
                Country Code is required.
              </div>
            </template>

            <BaseInput
              v-model="country.language.$model"
              label="Default Language"
              type="text"
              placeholder
              class="field"
              :class="{ error: country.language.$error }"
              @blur="country.language.$touch()"
            />
            <div v-if="!country.language.$model">
              <p>
                <a
                  target="a_blank"
                  href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes"
                  >Language Reference File -- use 639-2/B</a
                >
              </p>
            </div>
            <BaseInput
              v-model="country.website.$model"
              label="Website for footer"
              type="text"
              placeholder="https://"
              class="field"
            />
            <BaseInput
              v-model="country.url.$model"
              label="Url for footer"
              type="text"
              placeholder="https://"
              class="field"
            />

            <div v-if="!country.image.$model">
              <p class="errorMessage">Upload Country Flag (300px wide)</p>
            </div>

            <div v-if="country.image.$model">
              <br />
              <img
                v-bind:src="
                  '/sites/default/images/country/' + country.image.$model
                "
                class="book"
              />
              <br />
            </div>
            <div v-if="country.code.$model">
              <label>
                <input
                  type="file"
                  v-bind:id="country.code.$model"
                  ref="file"
                  v-on:change="handleFileUpload(country.code.$model)"
                />
              </label>
            </div>
            <input
              type="checkbox"
              id="checkbox"
              v-model="country.prototype.$model"
            />
            <label for="checkbox">
              <h2>Prototype?</h2>
            </label>
            <br />
            <input
              type="checkbox"
              id="checkbox"
              v-model="country.publish.$model"
            />
            <label for="checkbox">
              <h2>Publish?</h2>
            </label>
          </form>
        </div>
      </div>
      <div v-if="this.authorized">
        <div>
          <button class="button" @click="addNewCountryForm">New Country</button>
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
import ContentService from '@/services/ContentService.js'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import { mapState } from 'vuex'
import { bookMarkMixin } from '@/mixins/BookmarkMixin.js'
import { countriesMixin } from '@/mixins/CountriesMixin.js'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import { required } from 'vuelidate/lib/validators'
export default {
  mixins: [bookMarkMixin, countriesMixin, authorMixin],
  components: {
    NavBar,
  },
  computed: mapState(['bookmark', 'revision']),
  data() {
    return {
      file: null,
      error_message: null,
      countries: {
        name: null,
        english: null,
        code: null,
        language: null,
        website: null,
        url: null,
        index: null,
        custom: null,
        image: null,
        prototype: null,
        publish: null,
      },
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
      authorized: false,
    }
  },
  validations: {
    countries: {
      required,
      $each: {
        name: { required },
        english: {},
        code: { required },
        language: {},
        website: {},
        url: {},
        index: {},
        custom: {},
        image: {},
        prototype: {},
        publish: {},
      },
    },
  },
  methods: {
    deleteCountryForm(index) {
      this.countries.splice(index, 1)
    },
    addNewCountryForm() {
      this.countries.push({
        name: null,
        english: null,
        code: null,
        language: null,
        website: null,
        url: null,
        index: null,
        custom: null,
        image: null,
        prototype: null,
        publish: null,
      })
    },
    forceUpperCODE(value) {
      var change = this.$v.countries.$model
      var arrayLength = change.length
      for (var i = 0; i < arrayLength; i++) {
        var checkfile = this.$v.countries.$model[i]
        if (checkfile.code == value) {
          this.$v.countries.$each[i].$model.code = value.toUpperCase()
        }
      }
    },
    handleFileUpload(code) {
      LogService.consoleLogMessage('code in handle:' + code)
      var checkfile = ''
      var i = 0
      var arrayLength = this.$refs.file.length
      for (i = 0; i < arrayLength; i++) {
        checkfile = this.$refs.file[i]['files']
        if (checkfile.length == 1) {
          // LogService.consoleLogMessage(checkfile)
          LogService.consoleLogMessage(checkfile[0])
          var type = AuthorService.typeImage(checkfile[0])
          if (type) {
            var params = {}
            params.directory = 'images/country'
            params.rename = code
            AuthorService.imageStore(params, checkfile[0])

            for (i = 0; i < arrayLength; i++) {
              checkfile = this.$v.countries.$each[i]
              if (checkfile.code.$model == code) {
                this.$v.countries.$each[i].$model.image = 'default.png'
                this.$v.countries.$each[i].$model.image = code + type
                LogService.consoleLogMessage(
                  ' I reset ' + i + 'to' + code + type
                )
                LogService.consoleLogMessage(this.$v.countries.$each)
              }
            }
          }
        }
      }
      this.saveForm('stay')
    },
    async setupCountries() {
      try {
        this.$store.dispatch('newBookmark', 'clear')
        var res = await AuthorService.setupCountries(this.countries)
        console.log(res)
      } catch (error) {
        LogService.consoleLogError(
          'setupCountries failed in CountriesEdit ',
          error
        )
      }
    },
    async saveForm(action) {
      await this.setupCountries()
      try {
        var valid = ContentService.validate(this.countries)
        this.content.text = JSON.stringify(valid)
        this.content.filename = 'countries'
        this.content.filetype = 'json'
        var response = await AuthorService.createContentData(this.content)
        if (response.data.error != true && action != 'stay') {
          this.$router.push({
            name: 'previewCountries',
          })
        }
        if (response.data.error != true && action == 'stay') {
          this.showForm()
        } else {
          this.error = true
          this.loaded = false
          this.error_message = response.data.message
        }
      } catch (error) {
        LogService.consoleLogError(
          'COUNTRIES EDIT Unable to createContentData ',
          error
        )
        this.error = true
        this.loaded = false
        this.error_message = error
      }
    },
    async showForm() {
      try {
        //this.authorized = this.authorize('write', 'countries')
        this.authorized = this.authorize('write', this.$route.params)
        LogService.consoleLogMessage('Authorized: ' + this.authorized)
        await this.getCountries()
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in CountriesEdit.vue:',
          error
        )
      }
    },
  },
  beforeCreate() {
    this.$route.params.version = 'latest'
  },
  async created() {
    this.showForm()
  },
}
</script>
