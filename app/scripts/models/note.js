/*global notesApp, Backbone*/
notesApp.Models = notesApp.Models || {};
(function() {
    'use strict';
    notesApp.Models.Note = Backbone.Model.extend({
        urlRoot: 'api/notes',
        idAttribute: '_id', // Mongo id attribute
        defaults: {
            "title":"",
            "description": "",
            "text":"",
            "type":"text",
            "created": Date()
        },
        initialize: function() {
            console.log('Notes Model :: Init', this.url);
        },
        parse: function(data) {
            console.log('data', data);
            return data;
        }
    });
})();