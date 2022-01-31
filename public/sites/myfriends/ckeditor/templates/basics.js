CKEDITOR.addTemplates('default', {
  imagesPath: CKEDITOR.getUrl('/sites/myfriends/ckeditor/templates/images/basics'),
  templates: [
    {
      title: 'Sharing Life',
      image: 'SharingLife.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/images/review.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3eSHARING LIFE\x3c/div\x3e\x3c/div\x3e'
    },
    {
      title: 'Bible Study',
      image: 'review.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/images/bible-study.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3eBIBLE STUDY\x3c/div\x3e\x3c/div\x3e'
    },
    {
      title: 'Challenges',
      image: 'Challenges.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/images/bible-study.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3eREAD\x3c/div\x3e\x3c/div\x3e'
    },
    {
      title: 'Commentary',
      image: 'Commentary.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/images/background.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3eCOMMENTARY ON THE TEXT:&nbsp;\x3c/div\x3e\x3c/div\x3e'
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
