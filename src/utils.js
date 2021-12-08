export function saveStatePlugin(store) {
  store.subscribe((mutation, state) => {
    localStorage.setItem('bookmark', JSON.stringify(state.bookmark))
  })
}
