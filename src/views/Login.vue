<template>
  <div>
    <NavBar called_by="login" />
    <h2>Login</h2>
    <form @submit.prevent="saveForm">
      <BaseInput
        v-model="username"
        label="Username"
        type="text"
        placeholder
        class="field"
        :class="{ error: $v.username.$error }"
        @blur="$v.username.$touch()"
      />
      <template v-if="$v.username.$error">
        <p v-if="!$v.username.required" class="errorMessage">
          Username is required.
        </p>
      </template>

      <BaseInput
        v-model="password"
        label="Password"
        type="password"
        placeholder
        class="field"
        :class="{ error: $v.password.$error }"
        @blur="$v.password.$touch()"
      />
      <template v-if="$v.password.$error">
        <p v-if="!$v.password.required" class="errorMessage">
          Password is required.
        </p>
      </template>
      <br />
      <br />
      <button class="button red" @click="saveForm">Login</button>
      <template v-if="wrong">
        <p class="errorMessage">Wrong username or password. Try again</p>
      </template>
    </form>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import NavBar from '@/components/NavBarAdmin.vue'
import { required } from 'vuelidate/lib/validators'

export default {
  components: {
    NavBar,
  },
  data() {
    return {
      username: null,
      password: null,
      submitted: false,
      wrong: null,
    }
  },
  computed: mapState(['user']),
  validations: {
    username: { required },
    password: { required },
  },
  created() {},
  methods: {
    async saveForm() {
      if (this.submitted == false) {
        try {
          this.submitted = true
          var params = {}
          //var response = {}
          params.username = this.username
          params.password = this.password
          let res = await AuthorService.login(params)
          console.log(res)
          this.$store.dispatch('loginUser', [res])
          localStorage.setItem('user', JSON.stringify(res))
          var start_page = process.env.VUE_APP_SITE_START_PAGE
          if (typeof res.start_page !== 'undefined') {
            start_page = res.start_page
          }
          console.log(start_page)
          this.$router.push(start_page)
        } catch (error) {
          LogService.consoleLogError('Login There was an error ', error) //
        }
      }
    },
  },
}
</script>
