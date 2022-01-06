<template>
  <div v-if="book.publish == 1">
    <div class="Xapp-link" v-on:click="showPage(book)">
      <div class="Xapp-card -Xshadow">
        <div v-if="!this.bookmark.language.titles">
          <img v-bind:src="book.image" class="book-large" />
          <div class="book">
            <span class="title"> {{ book.title }}</span>
          </div>
        </div>
        <div v-if="this.bookmark.language.titles">
          <img v-bind:src="book.image" class="book-large" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import LogService from '@/services/LogService.js'
export default {
  props: {
    book: Object,
  },
  computed: mapState(['bookmark']),
  data() {
    return {
      image_dir: '',
      site_content_dir: '',
    }
  },
  created() {
    this.image_dir = process.env.VUE_APP_IMAGE_DIR
    this.site_content_dir = process.env.VUE_APP_SITE_CONTENT
    if (typeof this.bookmark.language.image_dir != 'undefined') {
      LogService.consoleLogMessage('USING BOOKMARK')
      this.image_dir = this.bookmark.language.image_dir
      LogService.consoleLogMessage(this.image_dir)
    } else {
      console.log('can not find image dir')
      console.log(this.bookmark)
    }
  },
  methods: {
    showPage: function (book) {
      LogService.consoleLogMessage('book')
      LogService.consoleLogMessage(book)
      localStorage.setItem('lastPage', 'library/country/language')
      if (book.format == 'series') {
        LogService.consoleLogMessage('BOOK - this is a series')
        this.$router.push({
          name: 'series',
          params: {
            country_code: this.bookmark.country.code,
            language_iso: this.bookmark.language.iso,
            library_code: 'TODO',
            folder_name: this.book.code,
          },
        })
      } else if (book.format == 'page') {

        LogService.consoleLogMessage('BOOK - this is a NOT a series')
        this.$router.push({
          name: 'page',
          params: {
            country_code: this.bookmark.country.code,
            language_iso: this.bookmark.language.iso,
            library_code: 'TODO',
            folder_name: this.book.code,
            filename: this.book.code,
          },
        })
      } else if (book.format == 'library') {

        LogService.consoleLogMessage('BOOK - this is a LIBRARY')
        this.$router.push({
          name: 'previewLibrary2',
          params: {
            country_code: this.bookmark.country.code,
            language_iso: this.bookmark.language.iso,
            library_code: this.book.code,
          },
        })
      }
      alert('we fell though')
    },
  },
}
</script>
<style>
img.book {
  width: 25%;
}
div.book {
  vertical-align: top;
  width: 70%;
  font-size: 24px;
  float: right;
}
.book {
  text-align: left;
}
</style>
