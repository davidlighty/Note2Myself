/*global notesApp, $*/


window.notesApp = {
    Models: {},
    Collections: {},
    Views: {},
    Routers: {},
    init: function () {
        'use strict';
        console.log('Notes App Init');
        // Init the main notes view
        var notesView = new notesApp.Views.Notes();
    }
};

$(document).ready(function () {
    'use strict';
    notesApp.init();
});
