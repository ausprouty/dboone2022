window.CKEDITOR_BASEPATH = `/node_modules/ckeditor/`

// Load your custom config.js file for CKEditor.
require(`!file-loader?context=${__dirname}&outputPath=node_modules/ckeditor/&name=[path][name].[ext]!./config.js`)
require(`!file-loader?context=${__dirname}&outputPath=node_modules/ckeditor/&name=[path][name].[ext]!./styles.js`)


// Load files from plugins, excluding lang files.
// Limit to active plugins with
// Object.keys(CKEDITOR.plugins.registered).sort().toString().replace(/,/g, '|')
require.context(
  '!file-loader?name=[path][name].[ext]!ckeditor/plugins/',
  true
  // plugins|needed|by|ckeditor|whatever plugin your need
  // /^\.\/((wsc|scayt|copyformatting|tableselection|link|image|div|bidi)(\/(?!lang\/)[^/]+)*)?[^/]*$/
)

// Load lang files from plugins.
// Limit to active plugins with
// Object.keys(CKEDITOR.plugins.registered).sort().toString().replace(/,/g, '|')
require.context(
  '!file-loader?name=[path][name].[ext]!ckeditor/plugins/',
  true
  // plugins|needed|by|ckeditor|here is the same
  // /^\.\/(wsc|scayt|copyformatting|tableselection|link|image|div|bidi)\/(.*\/)*lang\/(en|es)\.js$/
)

// Load CKEditor lang files.
require.context(
  '!file-loader?name=[path][name].[ext]!ckeditor/lang',
  true,
  /(en|es|ar|pt|)\.js/
)

// Load skin.
require.context(
  '!file-loader?name=[path][name].[ext]!ckeditor/skins/moono-lisa',
  true,
  /.*/
)