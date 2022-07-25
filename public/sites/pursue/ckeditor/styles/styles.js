/**
 * Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

// This file contains style definitions that can be used by CKEditor plugins.
//
// The most common use for it is the "stylescombo" plugin which shows the Styles drop-down
// list containing all styles in the editor toolbar. Other plugins, like
// the "div" plugin, use a subset of the styles for their features.
//
// If you do not have plugins that depend on this file in your editor build, you can simply
// ignore it. Otherwise it is strongly recommended to customize this file to match your
// website requirements and design properly.
//
// For more information refer to: https://ckeditor.com/docs/ckeditor4/latest/guide/dev_styles.html#style-rules

window.CKEDITOR.stylesSet.add('pursue', [
  {
    name: 'enrichment',
    element: 'span',
    attributes: { class: 'for-enrichment' },
  },
  {
    name: 'bible link',
    element: 'span',
    attributes: { class: 'bible-link' },
  },

  {
    name: 'popup paragraph',
    element: ['li', 'ul', 'ol', 'p'],
    attributes: { class: 'nobreak' },
  },
  {
    name: 'indent-1',
    element: ['h2', 'h3', 'h4', 'p', 'ol', 'ul', 'li'],
    attributes: { class: 'indent' },
  },
  {
    name: 'indent-2',
    element: ['h2', 'h3', 'h4', 'p', 'ol', 'ul', 'li'],
    attributes: { class: 'indent2' },
  },
  {
    name: 'indent-3',
    element: ['h2', 'h3', 'h4', 'p', 'ol', 'ul', 'li'],
    attributes: { class: 'indent3' },
  },
  {
    name: 'indent-4',
    element: ['h2', 'h3', 'h4', 'p', 'ol', 'ul', 'li'],
    attributes: { class: 'indent4' },
  },

  {
    name: 'year',
    element: 'p',
    attributes: { class: 'year' },
  },
  { name: 'quote-left', element: 'div', attributes: { class: 'quote-left' } },
  { name: 'quote-right', element: 'div', attributes: { class: 'quote-right' } },
  { name: 'block-quote', element: 'div', attributes: { class: 'block-quote' } },
  { name: 'bible', element: 'div', attributes: { class: 'bible' } },
  { name: 'lesson', element: 'div', attributes: { class: 'lesson' } },
  { name: 'lesson-icon', element: 'div', attributes: { class: 'lesson-icon' } },

  {
    name: 'lesson-image',
    element: 'img',
    attributes: { class: 'lesson-image' },
  },
  { name: 'readmore', element: 'a', attributes: { class: 'readmore' } },
])

window.CKEDITOR.stylesSet.add('default', [
  /* Block styles */

  // These styles are already available in the "Format" drop-down list ("format" plugin),
  // so they are not needed here by default. You may enable them to avoid
  // placing the "Format" combo in the toolbar, maintaining the same features.
  /*
	  { name: 'Paragraph',		element: 'p' },
	  { name: 'Heading 1',		element: 'h1' },
	  { name: 'Heading 2',		element: 'h2' },
	  { name: 'Heading 3',		element: 'h3' },
	  { name: 'Heading 4',		element: 'h4' },
	  { name: 'Heading 5',		element: 'h5' },
	  { name: 'Heading 6',		element: 'h6' },
	  { name: 'Preformatted Text',element: 'pre' },
	  { name: 'Address',			element: 'address' },
	  */

  { name: 'Italic Title', element: 'h2', styles: { 'font-style': 'italic' } },
  {
    name: 'Subtitle',
    element: 'h3',
    styles: { color: '#aaa', 'font-style': 'italic' },
  },
  {
    name: 'Special Container',
    element: 'div',
    styles: {
      padding: '5px 10px',
      background: '#eee',
      border: '1px solid #ccc',
      'font-size': '12px',
    },
  },

  /* Inline styles */

  // These are core styles available as toolbar buttons. You may opt enabling
  // some of them in the Styles drop-down list, removing them from the toolbar.
  // (This requires the "stylescombo" plugin.)
  /*
	  { name: 'Strong',			element: 'strong', overrides: 'b' },
	  { name: 'Emphasis',			element: 'em'	, overrides: 'i' },
	  { name: 'Underline',		element: 'u' },
	  { name: 'Strikethrough',	element: 'strike' },
	  { name: 'Subscript',		element: 'sub' },
	  { name: 'Superscript',		element: 'sup' },
	  */

  { name: 'Marker', element: 'span', attributes: { class: 'marker' } },

  { name: 'Big', element: 'big' },
  { name: 'Small', element: 'small' },
  { name: 'Typewriter', element: 'tt' },

  { name: 'Variable', element: 'var' },

  { name: 'Deleted Text', element: 'del' },
  { name: 'Inserted Text', element: 'ins' },

  { name: 'Inline Quotation', element: 'q' },

  { name: 'Language: RTL', element: 'span', attributes: { dir: 'rtl' } },
  { name: 'Language: LTR', element: 'span', attributes: { dir: 'ltr' } },

  /* Object styles */

  {
    name: 'Styled Image (left)',
    element: 'img',
    attributes: { class: 'left' },
  },

  {
    name: 'Styled Image (right)',
    element: 'img',
    attributes: { class: 'right' },
  },

  {
    name: 'Compact Table',
    element: 'table',
    attributes: {
      cellpadding: '5',
      cellspacing: '0',
      border: '1',
      bordercolor: '#ccc',
    },
    styles: {
      'border-collapse': 'collapse',
    },
  },

  {
    name: 'Borderless Table',
    element: 'table',
    styles: { 'border-style': 'hidden', 'background-color': '#E6E6FA' },
  },
  {
    name: 'Square Bulleted List',
    element: 'ul',
    styles: { 'list-style-type': 'square' },
  },

  /* Widget styles */

  {
    name: 'Clean Image',
    type: 'widget',
    widget: 'image',
    attributes: { class: 'image-clean' },
  },
  {
    name: 'Grayscale Image',
    type: 'widget',
    widget: 'image',
    attributes: { class: 'image-grayscale' },
  },

  {
    name: 'Featured Snippet',
    type: 'widget',
    widget: 'codeSnippet',
    attributes: { class: 'code-featured' },
  },

  {
    name: 'Featured Formula',
    type: 'widget',
    widget: 'mathjax',
    attributes: { class: 'math-featured' },
  },

  {
    name: '240p',
    type: 'widget',
    widget: 'embedSemantic',
    attributes: { class: 'embed-240p' },
    group: 'size',
  },
  {
    name: '360p',
    type: 'widget',
    widget: 'embedSemantic',
    attributes: { class: 'embed-360p' },
    group: 'size',
  },
  {
    name: '480p',
    type: 'widget',
    widget: 'embedSemantic',
    attributes: { class: 'embed-480p' },
    group: 'size',
  },
  {
    name: '720p',
    type: 'widget',
    widget: 'embedSemantic',
    attributes: { class: 'embed-720p' },
    group: 'size',
  },
  {
    name: '1080p',
    type: 'widget',
    widget: 'embedSemantic',
    attributes: { class: 'embed-1080p' },
    group: 'size',
  },

  // Adding space after the style name is an intended workaround. For now, there
  // is no option to create two styles with the same name for different widget types. See https://dev.ckeditor.com/ticket/16664.
  {
    name: '240p ',
    type: 'widget',
    widget: 'embed',
    attributes: { class: 'embed-240p' },
    group: 'size',
  },
  {
    name: '360p ',
    type: 'widget',
    widget: 'embed',
    attributes: { class: 'embed-360p' },
    group: 'size',
  },
  {
    name: '480p ',
    type: 'widget',
    widget: 'embed',
    attributes: { class: 'embed-480p' },
    group: 'size',
  },
  {
    name: '720p ',
    type: 'widget',
    widget: 'embed',
    attributes: { class: 'embed-720p' },
    group: 'size',
  },
  {
    name: '1080p ',
    type: 'widget',
    widget: 'embed',
    attributes: { class: 'embed-1080p' },
    group: 'size',
  },
])
