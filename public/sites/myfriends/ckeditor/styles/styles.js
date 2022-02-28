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

window.CKEDITOR.stylesSet.add('compass', [
  { name: 'Compass', element: 'h2', styles: { 'font-style': 'italic' } },
  { name: 'note-area', element: 'div', attributes: { class: 'note-area' } },
])

window.CKEDITOR.stylesSet.add('firststeps', [
  { name: 'background', element: 'div', attributes: { class: 'background' } },
  { name: 'bible', element: 'div', attributes: { class: 'bible-container' } },
  { name: 'lesson', element: 'div', attributes: { class: 'lesson' } },
  { name: 'lesson-icon', element: 'div', attributes: { class: 'lesson-icon' } },
  { name: 'note-area', element: 'div', attributes: { class: 'note-area' } },

  {
    name: 'lesson-point',
    element: 'img',
    attributes: { class: 'lesson-point' },
  },
  {
    name: 'list-back',
    element: 'ol',
    attributes: { class: 'ol-back' },
  },
  {
    name: 'list-up',
    element: 'ol',
    attributes: { class: 'ol-up' },
  },
  {
    name: 'list-forward',
    element: 'ol',
    attributes: { class: 'ol-forward' },
  },
  {
    name: 'popup paragraph',
    element: ['li', 'ul', 'ol', 'p'],
    attributes: { class: 'nobreak' },
  },
  { name: 'readmore', element: 'a', attributes: { class: 'readmore' } },
])

window.CKEDITOR.stylesSet.add('myfriends', [
  {
    name: 'enrichment',
    element: 'span',
    attributes: { class: 'for-enrichment' },
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
    name: 'year',
    element: 'p',
    attributes: { class: 'year' },
  },
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

window.CKEDITOR.stylesSet.add('multiply', [
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
    name: 'Enrichment Div',
    element: 'div',
    attributes: { class: 'for-enrichment' },
  },

  { name: 'background', element: 'div', attributes: { class: 'background' } },
  { name: 'bible', element: 'div', attributes: { class: 'bible' } },
  { name: 'note-area', element: 'div', attributes: { class: 'note-area' } },
  { name: 'lesson', element: 'div', attributes: { class: 'lesson' } },
  { name: 'lesson-icon', element: 'div', attributes: { class: 'lesson-icon' } },
  {
    name: 'lesson-section',
    element: 'div',
    attributes: { class: 'lesson-section' },
  },
  { name: 'readmore', element: 'a', attributes: { class: 'readmore' } },
])
