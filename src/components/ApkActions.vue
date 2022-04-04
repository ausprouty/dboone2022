<template>
  <div class="row">
    <div class="column">
      <button
        class="button"
        v-bind:class="progress.content"
        @click="localPublish('sdcard')"
      >
        {{ sdcard_text }}
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
import APKService from '@/services/APKService.js'

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
  inject: ['getApkSettings'],
  data() {
    return {
      sdcard_text: 'SD Card',
      nojs_text: 'No JS',
      pdf_text: 'PDF',
      videolist_text: 'Media List',
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

      params.apk_settings = JSON.stringify(this.getApkSettings())
      console.log(params.apk)
      console.log(params)
      if (location == 'cover') {
        this.cover_text = 'Publishing'
        await APKService.publish('cover', params)
        this.progress.cover = await APKService.verifyBookCover(params)
        this.cover_text = 'Media'
      }
      if (location == 'media') {
        this.media_text = 'Publishing'
        await APKService.publish('media', params)
        this.progress.media = await APKService.verifyBookMedia(params)
        this.media_text = 'Media'
      }

      if (location == 'content') {
        this.sdcard_text = 'Publishing'
        await APKService.publish('seriesAndChapters', params)
        this.progress.content = await APKService.verifyBookAPK(params)
        this.sdcard_text = 'SD Card'
      }
      if (location == 'videolist') {
        this.videolist_text = 'Publishing'
        await APKService.publish('videoMakeBatFileForAPK', params)
        this.progress.videolist = await APKService.verifyBookVideoList(params)
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
    this.progress = await APKService.checkStatusBook(params)
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
