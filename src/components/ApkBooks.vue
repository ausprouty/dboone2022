<template>
  <div>
    <h2>{{ language.language_name }}</h2>

    <ApkLanguage v-bind:language="language" />

    <div v-for="(book, id) in books" :key="id" :book="book">
      <div>
        <h3>{{ book.title }} ({{ book.library_code }})</h3>
      </div>
      <div><ApkActions v-bind:book="book" /></div>
    </div>
  </div>
</template>

<script>
import ApkService from '@/services/ApkService.js'
import ApkLanguage from '@/components/ApkLanguage.vue'
import ApkActions from '@/components/ApkActions.vue'
export default {
  props: {
    language: Object,
  },
  components: {
    ApkActions,
    ApkLanguage,
  },
  inject: ['apk_settings'],
  data() {
    return {
      books: [],
      apk_setting: this.apk_settings,
    }
  },
  methods: {},
  async created() {
    this.books = []
    var params = this.language
    console.log(this.apk_setting)
    params.apk_settings = JSON.stringify(this.apk_setting)
    this.books = await ApkService.getBooks(params)
    console.log(this.books)
  },
}
</script>
