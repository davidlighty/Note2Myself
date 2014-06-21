/*global notesApp, Backbone*/

notesApp.Models = notesApp.Models || {};

(function () {
    'use strict';

    notesApp.Models.Note = Backbone.Model.extend({

        url: '',

        initialize: function() {
        },

        defaults: {
        },

        validate: function(attrs, options) {
        },

        parse: function(response, options)  {
            return response;
        }
    });

})();
