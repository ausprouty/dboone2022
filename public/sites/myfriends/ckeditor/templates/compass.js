CKEDITOR.addTemplates('default', {
  imagesPath: CKEDITOR.getUrl('/sites/myfriends/ckeditor/templates/images/'),
  templates: [
    {
      title: 'FLASHBACK',
      image: 'FlashBack.png',
      description: 'Image and Title',
      html: `\x3cdiv class\x3e"lesson"\x3e\x3cimg class\x3e"lesson-icon" src\x3e"/images/compass/sharing-life.png" /\x3e
\x3cdiv class\x3e"lesson-subtitle"\x3eFLASHBACK\x3c/div\x3e
\x3c/div\x3e`
    },
    {
      title: 'BIBLE STUDY',
      image: 'BibleStudy.png',
      description: 'Image and Title',
      html: `\x3cdiv class\x3e"lesson"\x3e\x3cimg class\x3e"lesson-icon" src\x3e"/images/compass/bible-study.png" /\x3e
\x3cdiv class\x3e"lesson-subtitle"\x3eBIBLE STUDY\x3c/div\x3e
\x3c/div\x3e`
    },
    {
      title: 'SPECIFIC QUESTIONS',
      image: 'SpecificQuestions.png',
      description: 'Image and Title',
      html: `\x3cdiv class\x3e"lesson"\x3e\x3cimg class\x3e"lesson-icon" src\x3e"/images/compass/challenges.png" /\x3e
\x3cdiv class\x3e"lesson-subtitle"\x3eSPECIFIC QUESTIONS &amp; ACTION STEPS\x3c/div\x3e
\x3c/div\x3e`
    },
    {
      title: 'SCRIPTURE COMMENTS',
      image: 'ScriptureComments.png',
      description: 'Image and Title',
      html: `\x3cdiv class\x3e"lesson"\x3e\x3cimg class\x3e"lesson-icon" src\x3e"/images/compass/background.png" /\x3e
\x3cdiv class\x3e"lesson-subtitle"\x3eSCRIPTURE COMMENTS\x3c/div\x3e
\x3c/div\x3e`
    },
    {
      title: 'BiblePassage',
      image: '',
      description: 'Bible Passage and Link',
      html:'\x3cdiv id\x3d"bible"\x3e\x3cdiv class\x3d"bible"\x3e|PassageName|\x3cbr\x3e|Bible Text|\x3cbr /\x3e\x3ca class\x3d"readmore" href\x3d"|Reference|"\x3eRead More \x3c/a\x3e\x3c/div\x3e\x3c/div\x3e'
    },
    {
      title: 'Note Area',
      image: '',
      description: 'Area for students to write Notes',
      html:
        '\x3cdiv class\x3d"note-area" id\x3d"note#"\x3e\x3cform id \x3d "note#"\x3eNotes: (click outside box to save)\x3cbr\x3e\x3ctextarea rows\x3d"5"\x3e\x3c/textarea\x3e\x3c/form\x3e\x3c/div\x3e'
    }
  ]
})
