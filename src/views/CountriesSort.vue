<template>
  <div>
    <NavBar />
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">There was an error... {{ this.error }}</div>
    <div class="content" v-if="loaded">
      <div v-if="this.authorized">
        <h1>
          Countries
          <a
            target="_blank"
            class="help"
            v-bind:href="
              this.prototype_url + 'HD/eng/help-1/countries_sort.html'
            "
          >
            <img class="help-icon" src="/sites/default/images/icons/help.png" />
          </a>
        </h1>
        <div>
          <draggable v-model="countries">
            <transition-group>
              <div v-for="country in countries" :key="country.code">
                <div class="shadow-card -shadow">
                  <img
                    src="/sites/default/images/icons/move2red.png"
                    class="sortable"
                  />
                  <span class="card-name">{{ country.name }}</span>
                </div>
              </div>
            </transition-group>
          </draggable>
          <button class="button red" @click="saveForm">Save</button>
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
import draggable from 'vuedraggable'
import { mapState } from 'vuex'
import { bookMarkMixin } from '@/mixins/BookmarkMixin.js'
import { countriesMixin } from '@/mixins/CountriesMixin.js'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  mixins: [bookMarkMixin, countriesMixin, authorMixin],
  components: {
    NavBar,
    draggable,
  },
  computed: mapState(['bookmark', 'revision']),
  data() {
    return {
      authorized: false,
      prototype_url: process.env.VUE_APP_PROTOTYPE_CONTENT_URL,
    }
  },

  methods: {
    deleteCountryForm(index) {
      this.countries.splice(index, 1)
    },
    addNewCountryForm() {
      this.countries.push({
        code: '',
        english: '',
        name: '',
        index: '',
      })
    },
    async saveForm() {
      try {
        //this.$store.dispatch('newBookmark', 'clear')
        var valid = ContentService.validate(this.countries)
        this.content.text = JSON.stringify(valid)
        this.content.filename = 'countries'
        this.content.filetype = 'json'
        var response = await AuthorService.createContentData(this.content)
        if (response.data.error != true) {
          this.$router.push({
            name: 'previewCountries',
          })
        } else {
          this.error = true
          this.loaded = false
          this.error_message = response.data.message
        }
      } catch (error) {
        LogService.consoleLogError('COUNTRIES SORT There was an error ', error)
        this.error = true
        this.loaded = false
        this.error_message = response.data.message
      }
    },

    beforeCreate() {
      this.$route.params.version = 'latest'
    },
  },
  async created() {
    try {
      // this.authorized = this.authorize('write', 'countries')
      this.authorized = this.authorize('write', this.$route.params)
      LogService.consoleLogMessage('Authorized: ' + this.authorized)
      await this.getCountries()
    } catch (error) {
      LogService.consoleLogError(
        'There was an error in CountriesSort.vue:',
        error
      ) // Logs out the error
    }
  },
}
</script>
