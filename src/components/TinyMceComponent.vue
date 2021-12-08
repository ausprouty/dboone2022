<template>
  <textarea class="form-control">This is {{ initValue }}</textarea>
</template>


<script>
import tinymce from 'tinymce'

export default {
  props: ['initValue', 'disabled'],

  mounted: function() {
    var vm = this

    // Init tinymce
    tinymce.init({
      selector: '#' + vm.$el.id,
      menubar: false,
      toolbar: 'bold italic underline | bullist numlist',
      skin_url: 'node_modules/tinymce/skins/ui/oxide-dark'
    })
  },
  setup: function(editor) {
    // If the Vue model is disabled, we want to set the Tinymce readonly
    editor.settings.readonly = vm.disabled

    if (!vm.disabled) {
      editor.on('blur', function() {
        var newContent = editor.getContent()

        // Fire an event to let its parent know
        vm.$emit('content-updated', newContent)
      })
    }
  },
  updated: function() {
    // Since we're using Ajax to load data, hence we have to use this hook because when parent's data got loaded, it will fire this hook.
    // Depends on your use case, you might not need this
    var vm = this

    if (vm.initValue) {
      var editor = tinymce.get(vm.$el.id)
      editor.setContent(vm.initValue)
    }
  }
}
</script>
