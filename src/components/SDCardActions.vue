<template>
  <div>
    <h2>Books</h2>
    {{ this.language.language_iso }}
    <div v-for="book in books" :key="book.id">
      <div>
        {{ book.title }}
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import SDCardService from '@/services/SDCardService.js'
export default {
  props: {
    language: Object,
  },
  computed: mapState(['bookmark']),
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
      books: [],
    }
  },
  async created() {
    console.log(this.language)
    var params = []
    params['language_iso'] = this.language.language_iso
    this.books = await SDCardService.getBooks(params)
    console.log(this.books)
  },
}
</script>
