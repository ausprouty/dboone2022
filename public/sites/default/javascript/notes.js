window.onload = function () {
  var notes_page = document.getElementById('notes_page')
  if (notes_page !== null) {
    var notes = notes_page.value
    console.log(notes)
    showNotes(notes)
  }
}

function showNotes(page) {
  console.log('this will show notes for ' + page)
  var response = localStorage.getItem(page)
  if (!response) {
    return
  }
  var notes = JSON.parse(response)
  var len = notes.length
  var notePlace = null
  for (var i = 0; i < len; i++) {
    console.log(notes[i].key)
    // sometimes people change the number of notes on a page after we publish
    notePlace = document.getElementById(notes[i].key)
    if (notePlace) {
      document.getElementById(notes[i].key).value = notes[i].value
    }
  }
  document.getElementById('sendAction').classList.remove('hidden')

  return
}

function addNote() {
  console.log('In add_note')
  document.getElementById('sendAction').classList.remove('hidden') // show link at bottom to send Action
  console.log('this should show button at bottom')
  var notesPage = document.getElementById('notes_page').value
  console.log('Notes Page' + notesPage)
  // find ids of all textareas
  var txtAreas = document.getElementsByTagName('textarea')
  var len = txtAreas.length
  var ids = new Array()
  var notes = new Array()
  for (i = 0; i < len; i++) {
    ids.push(txtAreas[i].id)
  }
  for (var i = 0; i < len; i++) {
    note = document.getElementById(ids[i])
    var entry = new Object()
    entry.key = ids[i]
    entry.value = note.value
    console.log(entry)
    notes[i] = entry
  }

  localStorage.setItem(notesPage, JSON.stringify(notes)) //put the object back
}
