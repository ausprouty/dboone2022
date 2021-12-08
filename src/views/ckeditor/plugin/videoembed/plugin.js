/*
 *   Plugin developed by CTRL+N.
 *
 *   LICENCE: GPL, LGPL, MPL
 *   NON-COMMERCIAL PLUGIN.
 *
 *   Website: https://www.ctrplusn.net/
 *   Facebook: https://www.facebook.com/ctrlplusn.net/
 *
 */
CKEDITOR.plugins.add('videoembed', {
  init: function(editor) {
    // Command
    editor.addCommand(
      'videoembed',
      new CKEDITOR.dialogCommand('videoembedDialog')
    )
    // Toolbar button
    editor.ui.addButton('VideoEmbed', {
      label: 'Video Embedded',
      command: 'videoembed',
      toolbar: 'insert',
      icon: '../../images/videoembed.png'
    })
    // Dialog window
    CKEDITOR.dialog.add(
      'videoembedDialog',
      this.path + 'dialogs/videoembedDialog.js'
    )
  }
})
