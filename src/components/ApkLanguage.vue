<template>
  <div>
    <button class="button" v-bind:class="progress" @click="verifyLibraries()">
      {{ library_text }}
    </button>
  </div>
</template>
<script>
import APKService from '@/services/APKService.js'

export default {
  props: {
    language: Object,
  },
  inject: ['getApkSettings'],
  data() {
    return {
      library_text: 'Create Library Index',
      progress: 'undone',
    }
  },
  methods: {
    async verifyLibraries() {
      var params = this.language
      params.apk_settings = JSON.stringify(this.getApkSettings())
      console.log(params)
      this.library_text = 'Publishing'
      this.progress = await APKService.publish('libraries', params)
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
