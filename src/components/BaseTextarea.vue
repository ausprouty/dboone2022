<template>
  <div>
    <label v-if="label">{{ label }}</label>
    <textarea
      :value="value"
      @input="updateValue"
      v-bind="$attrs"
      v-on="listeners"
    ></textarea>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    label: {
      type: String,
      default: ''
    },
    value: [String, Number]
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        input: this.updateValue
      }
    }
  },
  methods: {
    updateValue(event) {
      this.$emit('input', event.target.value)
    }
  }
}
</script>
