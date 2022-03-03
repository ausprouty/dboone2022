<template>
  <div>
    <h2>{{ language.language_name }}</h2>

    <div v-for="(book, id) in books" :key="id" :book="book">
      <div>
        <h3>{{ book.title }} ({{ book.library }})</h3>
      </div>
      <div><SDCardActions v-bind:book="book" /></div>
    </div>
  </div>
</template>

<script>
import SDCardService from '@/services/SDCardService.js'
import SDCardActions from '@/components/SDCardActions.vue'
export default {
  props: {
    language: Object,
  },
  components: {
    SDCardActions,
  },
  data() {
    return {
      books: [],
    }
  },
  methods: {},
  async created() {
    this.books = []
    var params = this.language
    this.books = await SDCardService.getBooks(params)
    console.log(this.books)
  },
}
</script>
