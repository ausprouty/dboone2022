import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/store.js'
import Vuelidate from 'vuelidate'

import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'
import VueSidebarMenu from 'vue-sidebar-menu'

Vue.use(VueSidebarMenu)

Vue.use(Vuelidate)

const requireComponent = require.context(
  './components',
  false,
  /Base[A-Z]\w+\.(vue|js)$/
)

requireComponent.keys().forEach((fileName) => {
  const componentConfig = requireComponent(fileName)

  const componentName = upperFirst(
    camelCase(fileName.replace(/^\.\/(.*)\.\w+$/, '$1'))
  )

  Vue.component(componentName, componentConfig.default || componentConfig)
})
Vue.config.productionTip = false
Vue.prototype.$country = 'AU'
Vue.prototype.$language = 'eng'
// to allow  dev tools to see what is going on  see https://github.com/vuejs/vue-devtools/issues/190
Vue.config.devtools = true

Vue.mixin({
  methods: {
    toFormData(obj) {
      var form_data = new FormData()
      for (var key in obj) {
        form_data.append(key, obj[key])
      }
      console.log('form_data from mixin')
      // Display the key/value pairs
      for (var pair of form_data.entries()) {
        console.log(pair[0] + ', ' + pair[1])
      }
      return form_data
    },
  },
})

var vm = new Vue({
  router,
  store,
  render: function (h) {
    return h(App)
  },
}).$mount('#app')
