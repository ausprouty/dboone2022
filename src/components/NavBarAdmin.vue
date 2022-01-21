<template>
  <div id="nav">
    <link rel="stylesheet" href="/sites/default/styles/appGLOBAL.css" />
    <link rel="stylesheet" href="/sites/default/styles/cardGLOBAL.css" />
    <link rel="stylesheet" v-bind:href="standardCSS" />

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
</template>

<script>
import { mapState } from 'vuex'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import LogService from '@/services/LogService.js'
export default {
  computed: mapState(['bookmark', 'user']),
  props: ['called_by'],
  mixins: [authorizeMixin],
  data() {
    return {
      headerImage: process.env.VUE_APP_SITE_MENU_DIR + 'header-admin.png',
      showMenu: false,
      authorized: false,
      administrator: false,
      standardCSS: process.env.VUE_APP_SITE_STYLE,
      menu: [
        {
          value: 'Login',
          link: 'login',
          index: 0,
          show: false,
        },
        {
          value: 'Countries',
          link: 'countries',
          index: 1,
          show: false,
        },
        {
          value: 'Languages',
          link: 'languages',
          index: 2,
          show: false,
        },
        {
          value: 'Library',
          link: 'library',
          index: 3,
          show: false,
        },
        {
          value: 'Editors',
          link: 'register',
          index: 4,
          show: false,
        },
        {
          value: 'Admin',
          link: 'admin',
          index: 5,
          show: false,
        },
        {
          value: 'Logout',
          link: 'logout',
          index: 6,
          show: false,
        },
      ],
    }
  },
  created() {
    this.authorized = this.authorize('read', this.$route.params)
    this.administrator = this.authorize('register', this.$route.params)
    LogService.consoleLogMessage('I finished authorization')
    var arrayLength = this.menu
    for (var i = 0; i < arrayLength; i++) {
      this.menu[i].show = false
    }
    if (this.authorized) {
      this.menu[1].show = true
      if (
        typeof this.$route.params.country_code != 'undefined' &&
        this.called_by !== 'language'
      ) {
        this.menu[2].show = true
      }
      // library
      if (this.$route.params.language_iso && this.called_by !== 'library') {
        this.menu[3].show = true
      }
      this.menu[6].show = true
    }
    if (this.administrator) {
      this.menu[4].show = true
      this.menu[5].show = true
    }
    if (!this.authorized) {
      this.menu[0].show = true
    }
  },
  methods: {
    goBack() {
      window.history.back()
    },
    toggleMenu() {
      LogService.consoleLogMessage('tried to toggle')
      if (this.showMenu) {
        this.showMenu = false
      } else {
        this.showMenu = true
      }
    },
    setNewSelectedOption(selectedOption) {
      switch (selectedOption) {
        case 'login':
          this.$router.push({
            name: 'login',
          })
          break
        case 'logout':
          this.$store.dispatch('logoutUser')
          this.$router.push({
            name: 'login',
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
              country_code: this.$route.params.country_code,
              language_iso: this.$route.params.language_iso,
              library_code: this.$route.params.library_code,
            },
          })
          break
        case 'register':
          this.$router.push({
            name: 'farm',
          })
          break
        case 'admin':
          this.$router.push({
            name: 'testmc2',
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
