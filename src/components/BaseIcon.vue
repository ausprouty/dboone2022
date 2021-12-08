<template>
  <div class="icon-wrapper">
    <svg class="icon" :width="width" :height="height">
      <use v-bind="{'xlink:href':'/feather-sprite.svg#' + name}"></use>
    </svg>
  </div>
</template>

<script>
export default {
  name: 'Icon',
  props: {
    name: String,
    width: {
      type: [Number, String],
      default: 24
    },
    height: {
      type: [Number, String],
      default: 24
    }
  }
}
</script>


