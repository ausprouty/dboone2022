<template>
  <div>
    <div v-if="!this.authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now
      </p>
    </div>
    <div v-if="this.authorized">
      <UserList v-for="user in users" :key="user.uid" :user="user" />
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/LogService.js'
import UserList from '@/components/UserList.vue'
import { authorizeMixin } from '@/mixins/AuthorizeMixin.js'

export default {
  components: {
    UserList,
  },

  mixins: [authorizeMixin],
  data() {
    return {
      authorized: false,
      users: [
        {
          firstname: null,
          lastname: null,
          countries: null,
          langauges: null,
          start_page: null,
          uid: null,
        },
      ],
    }
  },
  async created() {
    // this.authorized = this.authorize('register', 'global')
    this.authorized = this.authorize('register', this.$route.params)
    if (this.authorized) {
      try {
        var params = {}
        params.scope = '*'
        this.users = await AuthorService.getUsers(params)
        LogService.consoleLogMessage(this.users)
      } catch (error) {
        LogService.consoleLogError(
          'There was an error in Countries.vue:',
          error
        )
      }
    }
  },
}
</script>
