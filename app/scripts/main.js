/*global notesApp, $*/
var ENTER_KEY = 13;
var ESC_KEY = 27;

$.ajaxSetup({
    statusCode: {
        401: function(){
            // Redirec the to the login page.
            window.location.replace('#login');
         
        },
        403: function() {
            // 403 -- Access denied
            window.location.replace('#denied');
        }
    }
});

window.notesApp = {
    Models: {},
    Collections: {},
    Views: {},
    Routers: {},
    init: function() {
        'use strict';
        console.log('Notes App Init');
        // Init a main event obj
        this.vent = _.extend({}, Backbone.Events);
        // Init the main notes view
        var notesView = new notesApp.Views.Notes();
        console.log('notesView', notesView);
        var alertView = new notesApp.Views.Alert();
        console.log('alertView',alertView);
    }
};
$(document).ready(function() {
    'use strict';
    notesApp.init();
});