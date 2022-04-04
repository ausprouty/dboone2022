<template>
  <div>
    <button class="button" v-bind:class="progress" @click="verifyLibraries()">
      {{ library_text }}
    </button>
  </div>
</template>
<script>
import ApkService from '@/services/ApkService.js'

export default {
  props: {
    language: Object,
  },
  inject: ['apk_settings'],
  data() {
    return {
      apk_setting: this.apk_settings,
      library_text: 'Create Library Index',
      progress: 'undone',
    }
  },
  methods: {
    async verifyLibraries() {
      var params = this.language
      console.log(this.apk_setting)
      params.apk_settings = JSON.stringify(this.apk_setting)
      this.library_text = 'Publishing'
      this.progress = await ApkService.publish('libraries', params)
      this.library_text = 'Published'
    },
  },
}
</script>
<style scoped>
button {
  font-size: 10px;
  padding: 10px;
}

.undone {
  background-color: black;
  padding: 10px;
  color: white;
}
.error {
  background-color: red;
  padding: 10px;
  color: white;
}

.ready {
  background-color: yellow;
  padding: 10px;
  color: black;
}

.done {
  background-color: green;
  padding: 10px;
  color: white;
}
</style>
