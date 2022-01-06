<template>
  <div class="Xapp-link" v-on:click="showPage(book)">
    <div
      class="Xapp-card -Xshadow"
      v-bind:class="{ notpublished: !book.publish }"
    >
      <div v-if="!this.bookmark.language.titles">
        <img v-bind:src="book.image.image" class="book" />
        <div class="book" v-bind:class="this.bookmark.language.rldir">
          <span class="title" v-bind:class="this.bookmark.language.rldir">
            {{ book.title }}
          </span>
        </div>
      </div>
      <div v-if="this.bookmark.language.titles">
        <img v-bind:src="book.image.image" class="book-large" />
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
  computed: mapState(['bookmark', 'standard']),
  data() {
    return {
      image_dir: '',
      rldir: 'ltr',
      site_dir: process.env.VUE_APP_SITE_DIR,
    }
  },
  created() {
    if (typeof this.bookmark.language != 'undefined') {
      LogService.consoleLogMessage('BOOK  PREVIEW -using bookmark')
      this.image_dir = this.bookmark.language.image_dir
      console.log(this.image_dir)
    } else {
      LogService.consoleLogMessage('BOOK  PREVIEW -using standard directory')
      this.image_dir = process.env.VUE_APP_SITE_IMAGE_DIR
    }
  },
  methods: {
    showPage: function (book) {
      localStorage.setItem('lastPage', 'library/country/language')
      // dealing with legacy data
      var code = ''
      var my_params = {}
      if (typeof book.code !== 'undefined') {
        code = book.code
      } else {
        code = book.name
      }
      if (book.format == 'series') {
        this.$router.push({
          name: 'previewSeries',
          params: {
            country_code: this.$route.params.country_code,
            language_iso: this.$route.params.language_iso,
            library_code: this.$route.params.library_code,
            folder_name: code,
          },
        })
      }
      if (book.format == 'library') {
        this.$router.push({
          name: 'previewLibrary2',
          params: {
            country_code: this.bookmark.country.code,
            language_iso: this.bookmark.language.iso,
            library_code: this.book.code,
          },
        })
      }
      if (book.format == 'page') {
        my_params = {
          country_code: this.$route.params.country_code,
          language_iso: this.$route.params.language_iso,
          library_code: this.$route.params.library_code,
          folder_name: 'pages',
          filename: code,
        }
        LogService.consoleLogMessage(my_params)
        this.$router.push({
          name: 'previewPage',
          params: my_params,
        })
      }
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
div.book.rtl {
  width: 30%;
  text-align: left;
}
.book {
  text-align: left;
}
.rtl {
  text-align: right;
  direction: rtl;
}
</style>
