<template>
  <div class="row">
    <div class="column">
      <button
        class="button"
        v-bind:class="progress.sdcard"
        @click="localPublish('sdcard')"
      >
        {{ sdcard_text }}
      </button>
    </div>
    <div class="column">
      <button
        class="button"
        v-bind:class="progress.nojs"
        @click="localPublish('nojs')"
      >
        {{ nojs_text }}
      </button>
    </div>
    <div class="column">
      <button
        class="button"
        v-bind:class="progress.pdf"
        @click="localPublish('pdf')"
      >
        {{ pdf_text }}
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
  </div>
</template>

<script>
import NoJSService from '@/services/NoJSService.js'
import SDCardService from '@/services/SDCardService.js'
import PDFService from '@/services/PDFService.js'
import store from '@/store/store.js'
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
  data() {
    return {
      sdcard_text: 'SD Card',
      nojs_text: 'No JS',
      pdf_text: 'PDF',
      videolist_text: 'Video List',
      progress: {
        sdcard: 'ready',
        nojs: 'ready',
        pdf: 'ready',
        videolist: 'ready',
      },
    }
  },
  methods: {
    async localPublish(location) {
      var response = null
      var params = this.book
      params.sdSubDir = store.state.sdSubDir
      console.log(params)
      if (location == 'nojs') {
        this.nojs_text = 'Publishing'
        response = await NoJSService.publish('seriesAndChapters', params)
        this.nojs_text = 'No JS'
        this.progress.nojs = 'done'
      }
      if (location == 'pdf') {
        this.pdf_text = 'Publishing'
        response = await PDFService.publish('seriesAndChapters', params)
        this.pdf_text = 'PDF'
        this.progress.pdf = 'done'
      }
      if (location == 'sdcard') {
        this.sdcard_text = 'Publishing'
        response = await SDCardService.publish('seriesAndChapters', params)
        this.sdcard_text = 'SD Card'
        this.progress.sdcard = 'done'
      }
      if (location == 'videolist') {
        this.videolist_text = 'Publishing'
        response = await SDCardService.publish(
          'videoMakeBatFileForSDCard',
          params
        )
        this.videolist_text = 'Video List'
        this.progress.videolist = 'done'
      }
      if (response == 'error') {
        alert('There was an error')
      }
    },
    async loadView() {},
  },
  async created() {
    console.log(this.book)
    var params = this.book
    params.sdSubDir = this.$store.sdSubDir
    params.progress = this.progress
    this.progress = SDCardService.checkStatusBook(params)
  },
}
</script>
<style scoped>
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

.ready {
  background-color: yellow;
  padding: 10px;
  color: black;
}

.done {
  background-color: green;
  padding: 10px;
}
</style>
