export const ckEditMixin = {
  data() {
    return {
      template_filename: this.$route.params.template,
      config: {
        extraPlugins: [
          'bidi',
          'uploadimage',
          'iframe',
          'uploadwidget',
          'clipboard',
          'videoembed',
          'templates',
          'panelbutton',
          'floatpanel',
          'colorbutton',
          'justify'
        ],
        extraAllowedContent: 'ol(*)',
        contentsCss: this.$route.params.css,
        stylesSet: this.$route.params.styles_set,
        templates_replaceContent: false,
        templates_files: [
          '/sites/' + process.env.VUE_APP_SITE + '/ckeditor/templates/' +
            this.$route.params.styles_set +
            '.js',
        ],
        // Configure your file manager integration. This example uses CKFinder 3 for PHP.
        // https://ckeditor.com/docs/ckfinder/ckfinder3-php/howto.html#howto_private_folders
        filebrowserBrowseUrl:
          process.env.VUE_APP_SITE_CKFINDER_URL + 'ckfinder.html',
        filebrowserUploadUrl:
          process.env.VUE_APP_SITE_CKFINDER_URL +
          'core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=' +
          this.languageDirectory,


        // end Configuration
        toolbarGroups: [
          { name: 'styles', groups: ['styles'] },
          { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
          {
            name: 'editing',
            groups: ['find', 'selection', 'spellchecker', 'editing'],
          },
          { name: 'links', groups: ['links'] },
          { name: 'insert', groups: ['insert'] },
          { name: 'forms', groups: ['forms'] },
          { name: 'tools', groups: ['tools'] },
          { name: 'document', groups: ['mode', 'document', 'doctools'] },
          { name: 'clipboard', groups: ['clipboard', 'undo'] },
          { name: 'others', groups: ['others'] },
          '/',
          {
            name: 'paragraph',
            groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'],
          },
          { name: 'colors', groups: ['colors'] },
          { name: 'about', groups: ['about'] },
        ],
        height: 600,
        removeButtons:
          'About,Button,Checkbox,CreatePlaceholder,DocProps,Flash,Form,HiddenField,Iframe,NewPage,PageBreak,Preview,Print,Radio,Save,Scayt,Select,Smiley,SpecialChar,TextField,Textarea',
      },
    }
  }
}
