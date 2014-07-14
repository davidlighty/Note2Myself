/*global notesApp, Backbone, JST*/
// Add Note View using the Backbone.Modal class from /vendor/backbone.modal.js 
// https://github.com/awkward/backbone.modal
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.AddImgNoteView = Backbone.Modal.extend({
        template: JST['app/scripts/templates/addimagenote_modal.ejs'],
        cancelEl: '.bbm-button',
        events: {

        }
    });
})();
