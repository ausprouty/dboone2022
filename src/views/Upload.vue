
 <template>
  <div class="container">
    <div class="large-12 medium-12 small-12 cell">
      <label>
        <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" />
      </label>
      <button v-on:click="submitFile()">Submit</button>
    </div>
  </div>
</template>


<script>
import axios from 'axios'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  mixins: [authorMixin],
  data() {
    return {
      file: null,
    }
  },
  methods: {
    handleFileUpload() {
      this.file = this.$refs.file.files[0]
      LogService.consoleLogMessage(this.$refs)
    },
    submitFile() {
      var params = {}
      params.directory = 'flag'
      params.name = 'TEST'
      AuthorService.imageStore(params, this.file)
    },
  },
}
</script>

<style lang="scss" scoped>
</style>
