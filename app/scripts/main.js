/*global notesApp, $*/
var ENTER_KEY = 13;
var ESC_KEY = 27;

$.ajaxSetup({
    statusCode: {
        401: function() {
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
    Title: 'NoteApp',
    Author: 'David Lighty',
    Description: 'Sample PHP API + BackboneJS note taking app.',
    Models: {},
    Collections: {},
    Views: {},
    User: null,
    Routers: {},
    init: function() {
        'use strict';
        console.log(this.Title + ':' + this.Author + ':' + this.Description);
        // Init a main event obj
        this.vent = _.extend({}, Backbone.Events);
        // Init the alert view which will catch/watch events
        notesApp.alertView = new notesApp.Views.Alert();
        // Init header + nav
        notesApp.headernav = new notesApp.Views.HeaderNav();
        // Start Router
        Backbone.history.start();
    }
};

function respondContent() {
    var winHeight = $(window).height();
    //console.log('winHeight', winHeight);
    var docHeight = $(document).height();
    //console.log('docHeight', docHeight);
    var wrapHeight = (docHeight > winHeight) ? docHeight : winHeight;
    //$('.hero-unit').attr('width', $(window).width()); //max width
    wrapHeight -= 50; // Compensate for margin....
    wrapHeight = wrapHeight + 'px';
    $('.content-wrap').css('height', wrapHeight); //max height
}

$(document).ready(function() {
    'use strict';
    respondContent();
    notesApp.init();
});

$(window).resize(respondContent);
