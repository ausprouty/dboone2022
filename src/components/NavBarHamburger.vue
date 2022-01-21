<template>
  <div>
    <link rel="stylesheet" v-bind:href="standardCSS" />
    <div v-if="!authorized">
      <div id="nav">
        <router-link to="/">
          <img class="nav-icon" alt="Home" v-bind:src="this.headerImage" />
        </router-link>
      </div>
    </div>
    <div v-if="authorized">
      <div v-on:click="toggleMenu()">
        <img v-bind:src="this.headerImage" class="nav-icon" alt="Home" />
      </div>
      <div v-if="showMenu">
        <div
          v-for="menuItem in this.menu"
          :key="menuItem.link"
          :menuItem="menuItem"
        >
          <div class="menu-card -shadow" v-if="menuItem.show">
            <div
              class="float-left"
              style="cursor: pointer"
              @click="setNewSelectedOption(menuItem.link)"
            >
              {{ menuItem.value }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import LogService from '@/services/LogService.js'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
export default {
  props: ['called_by'],
  computed: mapState(['bookmark']),
  mixins: [authorizeMixin],
  created() {
    this.authorized = this.authorize('read', this.$route.params)
  },
  data() {
    return {
      authorized: false,
      showMenu: false,
      headerImage: process.env.VUE_APP_SITE_MENU_DIR + 'header-hamburger.png',
      standardCSS: process.env.VUE_APP_SITE_STYLE,
      menu: [
        {
          value: 'Edit this page',
          link: 'page',
          index: 0,
          show: true,
        },
        {
          value: 'Preview Latest Countries',
          link: 'countries',
          index: 1,
          show: true,
        },
        {
          value: 'Preview Latest Languages',
          link: 'languages',
          index: 2,
          show: false,
        },
        {
          value: 'Edit Latest Library',
          link: 'library',
          index: 3,
          show: false,
        },
      ],
    }
  },

  methods: {
    goBack() {
      window.history.back()
    },
    toggleMenu() {
      LogService.consoleLogMessage('tried to toggle this')
      if (this.showMenu) {
        this.showMenu = false
        LogService.consoleLogMessage('toggle off')
      } else {
        LogService.consoleLogMessage('toggle on')
        this.showMenu = true
      }
    },
    setNewSelectedOption(selectedOption) {
      this.showMenu = false
      switch (selectedOption) {
        case 'page':
          LogService.consoleLogMessage('this route')
          LogService.consoleLogMessage(this.$route)
          this.$router.push({
            path: '/edit/' + this.$route.path,
          })
          break
        case 'countries':
          this.$router.push({
            name: 'previewCountries',
          })
          break
        case 'languages':
          this.$router.push({
            name: 'previewLanguages',
            params: {
              country_code: this.bookmark.country.code,
            },
          })
          break
        case 'library':
          this.$router.push({
            name: 'previewLibrary',
            params: {
              country_code: this.bookmark.country.code,
              language_iso: this.bookmark.language.iso,
              library_code: 'library',
            },
          })
          break
        default:
          LogService.consoleLogMessage('Can not find route in NavBarAdmin')
        // code block
      }
    },
  },
}
</script>

<style></style>
