<template>
  <div>
    <h1> {{this.passage_name}} </h1>
     <span v-html="this.bible"></span><br/></br>
    Link:     {{ this.link }}
  </div>
</template>
<script>

import PrototypeService from '@/services/PrototypeService.js'
import { mapState } from 'vuex'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  mixins: [authorMixin],
  computed: mapState(['bookmark', 'cssURL', 'standard']),
  data() {
    return {
      authorized: true,
      link: null,
      passage_name: null,
      bible: null
    }
  },
  async created() {
    //this.authorized = this.authorize('write', 'countries')
    this.authorized = this.authorize('write', this.$route.params)
    if (this.authorized) {
      var params = {}
      params.recnum = 387
      params.library_code = 'friends'

      var response = await PrototypeService.publish('bookmark', params)
      console.log(response)

    }
  }
}
</script>
