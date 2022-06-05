document.addEventListener("DOMContentLoaded", displayTeacherNotes);

function toggleTeacherNotes(){
	var showNotes = 'show';
	if (localStorage.getItem("generations-teaching-notes")){
		if (localStorage.getItem("generations-teaching-notes") == "show"){
			showNotes = 'hide';
		}
	}
	localStorage.setItem("generations-teaching-notes", showNotes);
	displayTeacherNotes();
}

function displayTeacherNotes(){
	var showNotes = 'show';
	if (localStorage.getItem("generations-teaching-notes")){
		showNotes = localStorage.getItem("generations-teaching-notes");
	}
	localStorage.setItem("generations-teaching-notes", showNotes);
	if (showNotes == 'show'){showTeacherNotes();}
	else{hideTeacherNotes();}
}

function hideTeacherNotes(){
	var teacherNoteCount = document.getElementById("TrainerNoteCount");
	if (teacherNoteCount !== null) {
		var count = teacherNoteCount.value;
		for (var i = 1; i <= count; i++) {
			notePlace = document.getElementById('TrainerNote' + i);
			if (notePlace){
			  notePlace.className = "trainer-hide";
			}
		}
		// change Link
		var id = 'toggle-teacher';
		document.getElementById(id).innerHTML = 'Show Teacher Notes';
	}
	
	
}

function showTeacherNotes(){
	var teacherNoteCount = document.getElementById("TrainerNoteCount");
	if (teacherNoteCount !== null) {
		var count = teacherNoteCount.value;
		for (var i = 1; i <= count; i++) {
			notePlace = document.getElementById('TrainerNote' + i);
			if (notePlace){
			  notePlace.className = "trainer";
			}
		}
		// change Link
		var id = 'toggle-teacher';
		document.getElementById(id).innerHTML = 'Hide Teacher Notes';
	}
	
}