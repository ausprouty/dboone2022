<template>
  <div class="row">
    <div class="column">
      <button
        class="button"
        v-bind:class="progress.content"
        @click="localPublish('sdcard')"
      >
        {{ content_text }}
      </button>
    </div>

    <div class="column">
      <button
        class="button"
        v-bind:class="progress.videolist"
        @click="localPublish('videolist')"
      >
        {{ videolist_text }}
      </button>
    </div>
    <div class="column">
      <button
        class="button"
        v-bind:class="progress.media"
        @click="localPublish('media')"
      >
        {{ media_text }}
      </button>
    </div>
    <div class="column">
      <button
        class="button"
        v-bind:class="progress.cover"
        @click="localPublish('cover')"
      >
        {{ cover_text }}
      </button>
    </div>
  </div>
</template>

<script>
import ApkService from '@/services/ApkService.js'

export default {
  props: {
    book: Object,
  },

  /*
      {
		"id": "4",
		"code": "multiply2",
		"title": "Being like Jesus",
		"progress": "/sites/mc2/progresss/mc2GLOBAL.css",
		"progresss_set": "mc2",
		"image": {
			"title": "Multiply2.png",
			"image": "/sites/mc2/content/M2/cmn/images/standard/Multiply2.png"
		},
		"format": "series",
		"pages": "one",
		"template": "multiply2/phase1.html",
		"publish": false,
		"prototype": true
    }
*/
  inject: ['apk_settings'],
  data() {
    return {
      apk_setting: this.apk_settings,
      videolist_text: 'Media List',
      content_text: 'Content',
      media_text: 'Media',
      cover_text: 'Cover',
      progress: {
        content: '',
        videolist: '',
        media: '',
        cover: '',
      },
    }
  },
  methods: {
    async localPublish(location) {
      var response = null
      var params = this.book
      params.apk_settings = JSON.stringify(this.apk_setting)
      console.log(params.apk)
      console.log(params)
      if (location == 'cover') {
        this.cover_text = 'Publishing'
        await ApkService.publish('cover', params)
        this.progress.cover = await ApkService.verifyBookCover(params)
        this.cover_text = 'Media'
      }
      if (location == 'media') {
        this.media_text = 'Publishing'
        await ApkService.publish('media', params)
        this.progress.media = await ApkService.verifyBookMedia(params)
        this.media_text = 'Media'
      }

      if (location == 'content') {
        this.sdcard_text = 'Publishing'
        await ApkService.publish('seriesAndChapters', params)
        this.progress.content = await ApkService.verifyBookApk(params)
        this.sdcard_text = 'SD Card'
      }
      if (location == 'videolist') {
        this.videolist_text = 'Publishing'
        await ApkService.publish('videoMakeBatFileForApk', params)
        this.progress.videolist = await ApkService.verifyBookVideoList(params)
        this.videolist_text = 'Media List'
      }
      if (response == 'error') {
        alert('There was an error')
      }
    },
    async loadView() {},
  },
  async created() {
    var params = this.book
    params.progress = JSON.stringify(this.progress)

    console.log(params)
    this.progress = await ApkService.checkStatusBook(params)
    console.log(this.progress)
  },
}
</script>
<style scoped>
button {
  font-size: 10px;
  padding: 10px;
}
div.actions {
  flex: 1 1 0px;
}
div.parent {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
}
.row {
  display: table;
  width: 100%; /*Optional*/
  table-layout: fixed; /*Optional*/
  border-spacing: 10px; /*Optional*/
}
.column {
  display: table-cell;
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
