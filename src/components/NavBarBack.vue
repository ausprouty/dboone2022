<template>
  <div id="nav" v-on:click="goBack()">
    <img v-bind:src="this.headerImage" class="nav-icon" alt="Home" />
  </div>
</template>

<script>
export default {
  props: ['called_by'],
  data() {
    return {
      headerImage: process.env.VUE_APP_SITE_MENU_DIR + 'header-back.png',
    }
  },
  methods: {
    goBack() {
      window.history.back()
    },
  },
}
</script>

<style>
.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 60px;
}
img.nav-icon {
  width: 95%;
  max-width: 650px;
}
</style>
