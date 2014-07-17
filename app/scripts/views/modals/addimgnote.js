/*global notesApp, Backbone, JST*/
// Add Note View using the Backbone.Modal class from /vendor/backbone.modal.js 
// https://github.com/awkward/backbone.modal
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.AddImgNoteView = Backbone.Modal.extend({
        template: JST['app/scripts/templates/addimagenote_modal.ejs'],
        cancelEl: '.bbm-button',
        submitEl: '#addButton',
        events: {

        },
        attrs: {},
        onRender: function() {
            console.log('Render');
        },
        newAttributes: function() {
            return {
                'title': this.$('#new-note-title').val().trim(),
                'type': 'image'
            };
        },
        beforeSubmit: function() {
            // Validate filetypes
            var uploadFile = this.$('#new-note-image');
            var retext = /\.jpg|\.jpeg|\.gif|\.svg|\.png/i;
            var filename = uploadFile.val();
            if (filename.search(retext) === -1) {
                console.log('Unsupported file type.');
                uploadFile.addClass('invalid');
                uploadFile.form.reset();
                return false;
            }
        },
        submit: function() {
            console.log('Save image', this.$('form :file'));
            var self = this;
            $.ajax('./api/imageupload', {
                data: this.newAttributes(),
                files: this.$('form :file'),
                iframe: true,
                processData: false
            }).complete(function(data) {
                console.log(data);
                var $data = JSON.parse(data.responseText);

                if ($data.success) {
                    // Image uploaded, now link to a note.
                    console.log('$data', $data);
                    var imgNote = self.newAttributes();
                    imgNote.imageId = $data.success.id;
                    imgNote.fileName = $data.success.filename;
                    console.log('imgNote', imgNote);
                    notesApp.notes.create(imgNote);
                }
            });
        }
    });
})();
