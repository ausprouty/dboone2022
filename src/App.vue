<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script></script>

<style>

:root {
  --color-back: #f0555a;
  --color-background: #f8f5f1;
  --color-badge-gradient-1: red;
  --color-badge-gradient-2: purple;
  --color-black: black;
  --color-button: #007a9a;
  --color-button-warning: #d04f53;
  --color-button-neutral: grey;
  --color-card: #efefef;
  --color-forward: #1ca0a6;
  --color-error: red;
  --color-heading: #007a9a;
  --color-not-published: yellow;
  --color-shadow: grey;
  --color-text-border: purple;
  --color-up: #f38832;
  --color-version: #002f33;
}
:root {
  --color-back: #f0555a;
  --color-background: #f8f5f1;
  --color-badge-gradient-1: red;
  --color-badge-gradient-2: purple;
  --color-button: red;
  --color-button-neutral: grey;
  --color-button-warning: red;
  --color-card: #efefef;
  --color-black: black;
  --color-error: red;
  --color-forward: #1ca0a6;
  --color-heading: red;
  --color-not-published: yellow;
  --color-shadow: grey;
  --color-text-border: purple;
  --color-up: #f38832;
  --color-version: rgb(110, 107, 241);
}


#offline-ready {
  color: var(--color-up);
}
.cache-series {
  background-color: var(--color-up);
}
a.black {
  color: var(--color-black);
  text-decoration: underline;
}
.preview {
  background-color: var(--color-background);
}
.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 60px;
}
img.nav-icon {
  width: 95%;
  max-width: 650px;
}
.hidden {
  visibility: hidden;
}
html {
  -webkit-text-size-adjust: 100%;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
body {
  margin: 0;
  width: 95%;
  font-family: Open Sans, Arial, Helvetica, sans-serif;
  font-size: 16px;
  line-height: 1.5;
}

#app {
  box-sizing: border-box;
  width: 90%;

  padding: 0px 0px 0px 0px;
  margin: 0 auto;
}
.content {
  max-width: 640px;
}
hr {
  box-sizing: content-box;
  height: 0;
  overflow: visible;
}

img {
  border-style: none;
  width: 100%;
}
h1,
h2,
h3,
h4,
h5,
h6 {
  display: flex;
  align-items: center;
  font-family: Open Sans, Arial, Helvetica, sans-serif;
  color: var(--color-heading);
}
h1 {
  font-size: 24px;
  font-weight: 700;
}

h2 {
  font-size: 24px;
  font-weight: 700;
}
h3 {
  font-size: 18px;
  font-weight: 700;
}
h4 {
  font-size: 16px;
  font-weight: 700;
}
h5 {
  font-size: 16px;
  font-weight: 700;
}
h6 {
  font-size: 14px;
  font-weight: 700;
}
b,
strong {
  font-weight: bolder;
}
small {
  font-size: 80%;
}
.shadow-card {
  background-color: var(--color-shadow);
  cursor: pointer;
  margin-bottom: 12px;
  padding: 10px;
  text-align: left;
  transition: all 0.2s linear;
  width: 95%;
}
div.card-names {
  float: right;
  font-size: 18px;
  vertical-align: top;
  width: 70%;
}
.card-name {
  font-weight: bold;
  line-height: 20px;
}
.error {
  border: 1px solid;
  color: var(--color-error);
}
.errorMessage {
  color: var(--color-error);
}
.eyebrow {
  font-size: 20px;
}
.-text-primary {
  color: var(--color-black);
}
.-text-base {
  color: var(--color-black);
}
.-text-error {
  color: var(--color-error);
}
.-text-gray {
  color: rgba(0, 0, 0, 0.5);
}
.-shadow {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.13);
}
.badge {
  display: inline-flex;
  height: 26px;
  width: auto;
  padding: 0 7px;
  margin: 0 5px;
  background: transparent;
  border-radius: 13px;
  font-size: 13px;
  font-weight: 400;
  line-height: 26px;
}
.badge.-fill-gradient {
  background: linear-gradient(
    to right,
    var(--color-badge-gradient-1),
    var(--color-badge-gradient-2)
  );
  color: white;
}
.rtl.heb-verse {
  direction: ltr;
  unicode-bidi: embed;
}
.italics {
  font-style: italic;
}
button,
label,
input,
optgroup,
select,
textarea {
  display: inline-flex;
  font-family: Open Sans, Arial, Helvetica, sans-serif;
  font-size: 100%;
  line-height: 1.15;
  margin: 0;
}
button,
input {
  overflow: visible;
}
button,
select {
  text-transform: none;
}
button,
[type='button'],
[type='reset'],
[type='submit'] {
  -webkit-appearance: none;
}
button::-moz-focus-inner,
[type='button']::-moz-focus-inner,
[type='reset']::-moz-focus-inner,
[type='submit']::-moz-focus-inner {
  border-style: none;
  padding: 0;
}
button:-moz-focusring,
[type='button']:-moz-focusring,
[type='reset']:-moz-focusring,
[type='submit']:-moz-focusring {
  outline: 2px var(--color-button);
}
button {
  margin-bottom: 20px;
  background-color: var(--color-button);
  color: var(--color-background);
  font-weight: bold;
  border-radius: 6px;
  text-align: center;
  padding: 0 40px;
  height: 36px;
}
.button.red {
  background-color: var(--color-button-warning);
  height: 48px;
}
.button.grey {
  background-color: var(--color-button-neutral);
  height: 48px;
}
img.sortable {
  height: 30px;
  width: 30px;
}
img.screen-shot {
  border-style: solid;
  padding: 10px;
}
label {
  color: rgba(0, 0, 0, 0.5);
  font-weight: 700;
}
p.rtl {
  direction: rtl;
}
input,
textarea {
  box-sizing: border-box;
  border: solid 1px rgba(0, 0, 0, 0.4);
}
textarea {
  width: 100%;
  overflow: auto;
  font-size: 20px;
}
[type='checkbox'],
[type='radio'] {
  box-sizing: border-box;
  padding: 0;
}
[type='number']::-webkit-inner-spin-button,
[type='number']::-webkit-outer-spin-button {
  height: auto;
}
[type='search'] {
  -webkit-appearance: textfield;
  outline-offset: -2px;
}
[type='search']::-webkit-search-decoration {
  -webkit-appearance: none;
}
[type='text'],
[type='number'],
[type='search'],
[type='password'] {
  height: 52px;
  width: 100%;
  padding: 0 10px;
  font-size: 20px;
}
[type='text']:focus,
[type='number']:focus,
[type='search']:focus,
[type='password']:focus {
  border-color: var(--color-text-border);
}
::-webkit-file-upload-button {
  -webkit-appearance: button;
  font: inherit;
}
[hidden] {
  display: none;
}
select {
  width: 100%;
  height: 52px;
  padding: 0 24px 0 10px;
  vertical-align: middle;
  background: white
    url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E")
    no-repeat right 12px center;
  background-size: 8px 10px;
  border: solid 1px rgba(0, 0, 0, 0.4);
  border-radius: 0;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}
select:focus {
  border-color: var(--color-text-border);
  outline: 0;
}
select:focus::ms-value {
  color: var(--color-black);
  background: var(--color-background);
}
select::ms-expand {
  opacity: 0;
}
.app-card {
  padding: 10px;
  width: 81%;
  max-width: 520px;
  background-color: var(--color-card);
  margin-bottom: 12px;
  transition: all 0.2s linear;
  cursor: pointer;
}
.app-card:hover {
  transform: scale(1.05);
  box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.2), 0 1px 15px 0 rgba(0, 0, 0, 0.19);
}
.app-card > .title {
  margin: 0;
}
li {
  text-align: left;
}
div.bible {
  text-align: left;
}
.menu-card {
  margin-left: 12px;
  padding: 10px;
  width: 50%;
  background-color: red;
  text-align: left;
  color: var(--color-background);
  margin-bottom: 5px;
  transition: all 0.2s linear;
  cursor: pointer;
}
img.app-img-header {
  width: 100%;
}
.app-link {
  color: black;

  text-decoration: none;
  font-weight: 100;
}
img.help-icon {
  height: 30px;
  width: 30px;
  padding-left: 30px;
  padding-right: 30px;
}
label,
.bold {
  font-weight: bold;
}
p.version {
  text-align: center;
  color: var(--color-version);
  font-size: 10pt;
}

div.lesson {
  margin-bottom: 30px;
  margin-top: 30px;
  position: relative;
  text-align: left;
}
div.lesson-lookback {
  font-size: 24px;
  line-height: 24px;
  margin-left: 30px;
  position: absolute;
  color: var(--color-back);
  display: inline;
  text-align: left;
}
div.lesson-lookup {
  font-size: 24px;
  line-height: 24px;
  margin-left: 30px;
  position: absolute;
  color: var(--color-up);
  display: inline;
  text-align: left;
}
div.lesson-lookforward {
  font-size: 24px;
  line-height: 24px;
  margin-left: 30px;
  position: absolute;
  color: var(--color-forward);
  display: inline;
  text-align: left;
}
.notpublished {
  background-color: var(--color-not-published);
}
</style>
