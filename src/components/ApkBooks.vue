<template>
  <div>
    <div v-for="(book, id) in books" :key="id" :book="book">
      <div>
        <h3>{{ book.title }} ({{ book.library_code }})</h3>
      </div>
      <div><ApkActions :book="book" :apk_settings="apk_settings" /></div>
    </div>
  </div>
</template>

<script>
import ApkService from '@/services/ApkService.js'
import ApkActions from '@/components/ApkActions.vue'
export default {
  props: {
    apk_settings: Object,
  },
  components: {
    ApkActions,
  },
  data() {
    return {
      books: [],
    }
  },
  methods: {},
  async created() {
    this.books = []
    var params = {}
    params.apk_settings = JSON.stringify(this.apk_settings)
    console.log(params)
    this.books = await ApkService.getBooks(params)
    console.log(this.books)
  },
}
</script>
