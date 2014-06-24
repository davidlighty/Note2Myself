/*global notesApp, Backbone*/

notesApp.Models = notesApp.Models || {};

(function () {
    'use strict';

    notesApp.Models.Note = Backbone.Model.extend({
        initialize: function() {
            console.log('Notes Model :: Init');
        },
        parse:function(data){
            console.log('data',data);
            return data;
        }
    });

})();
