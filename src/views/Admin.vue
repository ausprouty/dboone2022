<template>
  <div>
    <NavBar called_by="admin" />
    <h2>Copy Books</h2>
    <form @submit.prevent="saveForm">
      <BaseSelect
        label="Copy from"
        :options="source_options"
        v-model="source"
        class="field"
      />
      <BaseSelect
        label="Copy to"
        :options="destination_options"
        v-model="destination"
        class="field"
      />

      <BaseInput
        v-model="password"
        label="Password"
        type="password"
        placeholder
        class="field"
      />

      <br />
      <br />
      <button class="button red" @click="saveForm">Copy Study</button>
    </form>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import NavBar from '@/components/NavBarAdmin.vue'
export default {
  components: {
    NavBar,
  },
  data() {
    return {
      source: null,
      destination: null,
      password: null,
      source_options: [],
      destination_options: [],
    }
  },
  methods: {
    async saveForm() {
      var param = {}
      param.source = this.source
      param.destination = this.destination
      this.destination = ''
      this.source = ''
      await AuthorService.copyBook(param)
    },
  },
  async created() {
    var response = await AuthorService.getCurrentBooks()
    this.source_options = JSON.parse(response.data)
    LogService.consoleLogMessage(this.source_options)
    response = await AuthorService.getCurrentLanguages()
    this.destination_options = JSON.parse(response.data)
    LogService.consoleLogMessage(this.destination_options)
  },
}
</script>
