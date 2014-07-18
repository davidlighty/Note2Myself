/*global notesApp, Backbone, JST*/
// Add Note View using the Backbone.Modal class from /vendor/backbone.modal.js 
// https://github.com/awkward/backbone.modal
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.FullImage = Backbone.Modal.extend({
        template: JST['app/scripts/templates/fullimage_modal.ejs'],
        cancelEl: '.bbm-button',
        onRender: function() {
            console.log('Render');
        }
    });
})();
