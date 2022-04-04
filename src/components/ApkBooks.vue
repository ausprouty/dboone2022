<template>
  <div>
    <h2>{{ language.language_name }}</h2>

    <APKLanguage v-bind:language="language" />

    <div v-for="(book, id) in books" :key="id" :book="book">
      <div>
        <h3>{{ book.title }} ({{ book.library_code }})</h3>
      </div>
      <div><APKActions v-bind:book="book" /></div>
    </div>
  </div>
</template>

<script>
import APKService from '@/services/APKService.js'
import APKLanguage from '@/components/APKLanguage.vue'
import APKActions from '@/components/APKActions.vue'
export default {
  props: {
    language: Object,
  },
  components: {
    APKActions,
    APKLanguage,
  },
  inject: ['getApkSettings'],
  data() {
    return {
      books: [],
    }
  },
  methods: {},
  async created() {
    this.books = []
    var params = this.language
    console.log (this.getApkSettings())
    params.apk_settings = JSON.stringify(this.getApkSettings())
    this.books = await APKService.getBooks(params)
    console.log(this.books)
  },
}
</script>
