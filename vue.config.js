// see http://vuejs-templates.github.io/webpack for documentation.
//const path = require("path");
const BundleAnalyzerPlugin =
  require('webpack-bundle-analyzer').BundleAnalyzerPlugin

var fs = require('fs')
var projects = fs.readdirSync('./public/sites/')

module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? '/' : '/',
  configureWebpack: {
    plugins: [new BundleAnalyzerPlugin()],
    optimization: {
      splitChunks: {
        minSize: 10000,
        maxSize: 250000,
      },
    },
  },
  // https://stackoverflow.com/questions/61122635/vue-js-exclude-folders-from-webpack-bundle
  // this should remove all sites except default and the one you are compiling
  chainWebpack: (config) => {
    config.plugin('copy').tap(([options]) => {
      let site = ''
      for (let i = 0; i < projects.length; i++) {
        if (projects[i] !== 'default' && projects[i] !== 'logs') {
          if (projects[i] !== process.env.VUE_APP_SITE) {
            site = 'sites/' + projects[i] + '/**'
            options[0].ignore.push(site)
          }
        }
      }
      site = 'sites/' + process.env.VUE_APP_SITE + '/.env.api.local.php'
      options[0].ignore.push(site)
      if (process.env.VUE_APP_UPGRADE == 'minor') {
        site = 'node_modules/*'
        options[0].ignore.push(site)
        site = 'sites/' + process.env.VUE_APP_SITE + '/content/**'
        options[0].ignore.push(site)
        site = 'sites/default/ckfinder/**'
        options[0].ignore.push(site)
        site = 'sites/default/ckeditor/**'
        options[0].ignore.push(site)
        site = 'sites/default/vendor/**'
        options[0].ignore.push(site)
      }
      return [options]
    })
  },
}
