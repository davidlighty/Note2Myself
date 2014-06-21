/*global notesApp, Backbone*/

notesApp.Collections = notesApp.Collections || {};

(function () {
    'use strict';

    notesApp.Collections.Notes = Backbone.Collection.extend({

        model: notesApp.Models.Notes

    });

})();
