<template>
  <div>
    <div v-if="!authorized">
      <div id="nav">
        <link rel="stylesheet" v-bind:href="{ standardCSS }" />
        <router-link to="/">
          <img v-bind:src="this.headerImage" class="nav-icon" alt="Home" />
        </router-link>
      </div>
    </div>
    <div v-if="authorized">
      <div v-on:click="toggleMenu()">
        <img class="nav-icon" alt="Home" v-bind:src="this.headerImage" />
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
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'
import LogService from '@/services/LogService.js'
export default {
  props: ['text'],
  computed: mapState(['bookmark']),
  mixins: [authorizeMixin],
  created() {
    this.authorized = this.authorize('read', this.$route.params)
    console.log (this.text)
  },
  data() {
    return {
      authorized: false,
      showMenu: false,
      headerImage: process.env.VUE_APP_SITE_MENU_DIR + 'header-hamburger.png',
      standardCSS: process.env.VUE_APP_SITE_STYLE,
      menu: [
        {
          value: 'Countries',
          link: 'countries',
          index: 0,
          show: true,
        },
        {
          value: 'Language Settings',
          link: 'language',
          index: 1,
          show: true,
        },
        {
          value: 'Logout',
          link: 'logout',
          index: 2,
          show: true,
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
        case 'countries':
          this.$router.push({
            name: 'previewCountries',
          })
          break
        case 'language':
          this.$router.push({
            name: 'previewLanguages',
            params: {
              country_code: this.$route.params.country_code,
            },
          })
          break
        case 'logout':
          this.$store.dispatch('logoutUser')
          this.$router.push({
            name: 'login',
          })
          break
        default:
          LogService.consoleLogMessage('Can not find route in NavBarCountry')
        // code block
      }
    },
  },

}
</script>

<style></style>
