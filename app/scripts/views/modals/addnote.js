/*global notesApp, Backbone, JST*/
// Add Note View using the Backbone.Modal class from /vendor/backbone.modal.js 
// https://github.com/awkward/backbone.modal
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.AddNoteView = Backbone.Modal.extend({
        template: JST['app/scripts/templates/addnote_modal.ejs'],
        cancelEl: '.bbm-button',
        submitEl: '#addButton',
        events: {

        },
        attrs: {},
        onRender: function() {
            var self = this;
            this.$('#new-note-title').liveUrl({
                success: function(data) {
                    console.log(data);
                    // this return the first found url data
                    self.model.set('type', 'website');
                    self.$('#new-note-title').val(data.url);
                    self.$('#new-note-text').val(data.title);
                    self.attrs.desc = data.description;
                }
            });
        },
        newAttributes: function() {
            return {
                'title': this.$('#new-note-title').val().trim(),
                'text': this.$('#new-note-text').val().trim(),
                'description': this.attrs.desc || '',
                'type': 'text'
            };
        },
        submit: function() {
            console.log('Add new text note');
            // Must have at least some text.
            if (this.$('#new-note-text').val().trim()) {
                console.log('Save new note');
                notesApp.notes.create(this.newAttributes());
                //notesApp.router.navigate('#/');
            }
        }
    });
})();
