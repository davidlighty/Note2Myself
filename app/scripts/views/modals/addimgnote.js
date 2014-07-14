/*global notesApp, Backbone, JST*/
// Add Note View using the Backbone.Modal class from /vendor/backbone.modal.js 
// https://github.com/awkward/backbone.modal
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.AddNoteView = Backbone.Modal.extend({
        template: JST['app/scripts/templates/addnote_modal.ejs'],
        cancelEl: '.bbm-button',
        events: {
            'click #loginButton': 'login'
        }
    });
})();
