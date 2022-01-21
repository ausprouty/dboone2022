<template>
  <div id="nav">
    <link rel="stylesheet" v-bind:href="this.standardCSS" />
    <router-link to="/">
      <img class="nav-icon" alt="Home" v-bind:src="headerImage" />
    </router-link>
  </div>
</template>

<script>
export default {
  props: ['called_by'],
  data() {
    return {
      standardCSS: process.env.VUE_APP_SITE_STYLE,
      headerImage: process.env.VUE_APP_SITE_MENU_DIR + 'header-front.png',
    }
  },
}
</script>

<style></style>
