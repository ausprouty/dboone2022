
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
window.CKEDITOR.stylesSet.add('generations', [
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
    element: ['li', 'ul', 'ol'],
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
  { name: 'note-area', element: 'div', attributes: { class: 'note-area' } },
  {
    name: 'lesson-image',
    element: 'img',
    attributes: { class: 'lesson-image' },
  },
  { name: 'readmore', element: 'a', attributes: { class: 'readmore' } },

  {
    name: 'lesson-subtitle',
    element: 'div',
    attributes: { class: 'lesson-subtitle' },
  },
])

