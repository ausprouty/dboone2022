CKEDITOR.addTemplates('default', {
  imagesPath: CKEDITOR.getUrl('/sites/training/ckeditor/templates/images/'),
  templates: [
    {
      title: 'Anchor Verse',
      image: 'anchor.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/anchor.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eANCHOR VERSE\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Background',
      image: 'background.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/background.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eBACKGROUND\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Bible Study',
      image: 'bible.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/bible.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eBIBLE STUDY\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Challenge',
      image: 'challenge.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/challenge.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eCHALLENGE\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Discover',
      image: 'discover.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/discover.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eDISCOVER\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Notes',
      image: 'notes.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/notes.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eNOTES\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Review',
      image: 'review.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/review.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eREVIEW\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Sharing',
      image: 'sharing.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/sharing.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eSHARING\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Video',
      image: 'video.png',
      description: 'Video Block',
      html:
        '\x3chr /\x3e\x3c/div\x3e\x3cdiv class\x3d"reveal film"\x3e&nbsp;\x3chr /\x3e\x3ctable class\x3d"video" border\x3d"1"\x3e\x3ctbody  class\x3d"video"\x3e\x3ctr class\x3d"video" \x3e\x3ctd class\x3d"video label" \x3e\x3cstrong\x3eTitle:\x3c/strong\x3e\x3c/td\x3e\x3ctd class\x3d"video" \x3e\x3c/td\x3e\x3c/tr\x3e\x3ctr class\x3d"video" \x3e\x3ctd class\x3d"video label" \x3e\x3cstrong\x3eURL:\x3c/strong\x3e\x3c/td\x3e\x3ctd class\x3d"video" \x3ehttps://api.arclight.org/videoPlayerUrl?refId\x3d\x3c/td\x3e\x3c/tr\x3e\x3ctr class\x3d"video" \x3e\x3ctd class\x3d"video instruction"  colspan\x3d"2" style\x3d"text-align:center"\x3e\x3ch2\x3e\x3cstrong\x3eSet times if you do not want to play the entire video\x3c/strong\x3e\x3c/h2\x3e\x3c/td\x3e\x3c/tr\x3e\x3ctr class\x3d"video" \x3e\x3ctd class\x3d"video label" \x3eStart Time (seconds) :\x3c/td\x3e\x3ctd class\x3d"video" \x3estart\x3c/td\x3e\x3c/tr\x3e\x3ctr class\x3d"video" \x3e\x3ctd class\x3d"video label" \x3eEnd Time (seconds):\x3c/td\x3e\x3ctd class\x3d"video" \x3eend\x3c/td\x3e\x3c/tr\x3e\x3c/tbody\x3e\x3c/table\x3e\x3chr /\x3e\x3c/div\x3e',
    },
    {
      title: 'Look Back',
      image: 'lookback.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/lookback.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"back"\x3eLOOK BACK\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Look Up',
      image: 'lookup.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/lookup.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"up"\x3eLOOK UP\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },
    {
      title: 'Look Forward',
      image: 'lookforward.png',
      description: 'Image and Title',
      html:
        '\x3cdiv class\x3d"lesson"\x3e\x3cimg class\x3d"lesson-icon" src\x3d"/templates/images/train/lookforward.png" /\x3e\x3cdiv class\x3d"lesson-subtitle"\x3e\x3cspan class\x3d"forward"\x3eLOOK FORWARD\x3c/span\x3e\x3c/div\x3e\x3c/div\x3e',
    },

    {
      title: 'Note Area',
      image: 'notesblock.png',
      description: 'Area for students to write NOTES',
      html:
        '\x3cdiv class\x3d"note-area" id\x3d"note#"\x3e\x3cform id \x3d "note#"\x3eNotes: (click outside box to save)\x3cbr\x3e\x3ctextarea rows\x3d"5"\x3e\x3c/textarea\x3e\x3c/form\x3e\x3c/div\x3e',
    },
    {
      title: 'Reveal',
      image: 'revealblock.png',
      description:
        'Students can reveal content by pressing on area. Best used for summaries',
      html:
        '\x3cdiv class\x3d"reveal"\x3e&nbsp;\x3chr\x3e Put summary or other text to reveal here \x3chr\x3e\x3c/div\x3e',
    },
    {
      title: 'Bible Passage',
      image: 'bibleblock.png',
      description: 'Bible Passage Set',
      html:
        '\x3cdiv class\x3d"reveal bible"\x3e&nbsp;\x3chr\x3e[BiblePassage]\x3chr\x3e\x3c/div\x3e',
    },
    {
      title: 'Video',
      image: 'videoblock.png',
      description: 'Video Block',
      html:
        '\x3chr /\x3e\x3c/div\x3e\x3cdiv class\x3d"reveal video"\x3e&nbsp;\x3chr /\x3e\x3ctable class\x3d"video"\x3e\x3ctbody\x3e\x3ctr\x3e\x3ctd\x3e\x3cstrong\x3eTitle:\x3c/strong\x3e\x3c/td\x3e\x3ctd\x3e\x3c/td\x3e\x3c/tr\x3e\x3ctr\x3e\x3ctd\x3e\x3cstrong\x3eURL:\x3c/strong\x3e\x3c/td\x3e\x3ctd\x3e\x3c/td\x3e\x3c/tr\x3e\x3ctr\x3e\x3ctd colspan\x3d"2" style\x3d"text-align:center"\x3e\x3ch2\x3e\x3cstrong\x3eSet times if you do not want to play the entire video\x3c/strong\x3e\x3c/h2\x3e\x3c/td\x3e\x3c/tr\x3e\x3ctr\x3e\x3ctd\x3eStart Time (seconds) :\x3c/td\x3e\x3ctd\x3e&nbsp;\x3c/td\x3e\x3c/tr\x3e\x3ctr\x3e\x3ctd\x3eEnd Time (seconds):\x3c/td\x3e\x3ctd\x3e&nbsp;\x3c/td\x3e\x3c/tr\x3e\x3c/tbody\x3e\x3c/table\x3e\x3chr /\x3e\x3c/div\x3e',
    },
    {
      title: 'Trainer Notes',
      image: 'trainerblock.png',
      description: 'Only shown to trainers',
      html:
        '\x3cdiv class\x3d"trainer"\x3e&nbsp;\x3chr\x3ePut Trainer Notes Here \x3chr\x3e\x3c/div\x3e',
    },
  ],
})
