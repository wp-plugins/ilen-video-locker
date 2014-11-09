(function() {
  tinymce.PluginManager.add('ilenvideolock', function( editor, url ) {
    editor.addButton( 'ilenvideolock', {
      icon: 'icon ilenvideolocker-icon',
      title: "iLen Video Locker",
      type: 'menubutton',
      menu: [
        {
          text: 'Video Youtube',
          onclick: function() {
            editor.windowManager.open( {
              title: 'Insert url Youtube',
              body: [
                {
                  type: 'textbox',
                  name: 'text_url_video',
                  label: 'URL youtube',
                  value: ''
                },
              ],
              onsubmit: function( e ) {
                editor.insertContent( '[ilenvideolock]'+e.data.text_url_video+'[/ilenvideolock]');
              }
            });
          }
        }
        
      ]
    });
  });
})();