/*global notesApp, Backbone*/
notesApp.Collections = notesApp.Collections || {};
(function() {
    'use strict';
    notesApp.Collections.Notes = Backbone.Collection.extend({
        model: notesApp.Models.Note,
        url: 'api/notes',
        parse: function(response) {
            return response.results;
        }
    });

    notesApp.notes = new notesApp.Collections.Notes();
})();