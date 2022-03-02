<template>
  <div class="row">
    <div class="column">
      <button
        class="button"
        v-bind:class="sdcard_class"
        @click="localPublish('sdcard')"
      >
        {{ sdcard_text }}
      </button>
    </div>
    <div class="column">
      <button
        class="button"
        v-bind:class="nojs_class"
        @click="localPublish('nojs')"
      >
        {{ nojs_text }}
      </button>
    </div>
    <div class="column">
      <button
        class="button"
        v-bind:class="pdf_class"
        @click="localPublish('pdf')"
      >
        {{ pdf_text }}
      </button>
    </div>
  </div>
</template>

<script>
import NoJSService from '@/services/NoJSService.js'
import SDCardService from '@/services/SDCardService.js'
import PDFService from '@/services/PDFService.js'
export default {
  props: {
    book: Object,
  },

  /*
      {
		"id": "4",
		"code": "multiply2",
		"title": "Being like Jesus",
		"style": "/sites/mc2/styles/mc2GLOBAL.css",
		"styles_set": "mc2",
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
      sdcard_class: 'ready',
      nojs_class: 'ready',
      pdf_class: 'ready',
    }
  },
  methods: {
    async localPublish(location) {
      var response = null
      var params = this.book
      console.log(params)
      if (location == 'nojs') {
        this.nojs_text = 'Publishing'
        response = await NoJSService.publish('seriesAndChapters', params)
        this.nojs_text = 'No JS'
        this.nojs_class = 'done'
      }
      if (location == 'pdf') {
        this.pdf_text = 'Publishing'
        response = await PDFService.publish('seriesAndChapters', params)
        this.pdf_text = 'PDF'
        this.pdf_class = 'done'
      }
      if (location == 'sdcard') {
        this.sdcard_text = 'Publishing'
        response = await SDCardService.publish('seriesAndChapters', params)
        this.sdcard_text = 'SD Card'
        this.sdcard_class = 'done'
      }
      if (response == 'error'){
         alert('There was an error')
      }
    },
    async loadView() {},
  },
  async created() {
    console.log(this.book)
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
  color: black;
}
.done {
  background-color: green;
}
</style>
