const removeConsolePlugin = []
if (process.env.NODE_ENV === 'production') {
  removeConsolePlugin.push('transform-remove-console')
}
module.exports = {
  presets: [
    '@vue/app'
  ],
  plugins: removeConsolePlugin
}

// https://stackoverflow.com/questions/48502827/vuejs-2-how-to-remove-console-log-from-production-builds