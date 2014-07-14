/*global notesApp, Backbone*/
notesApp.Routers = notesApp.Routers || {};
(function() {
    'use strict';
    notesApp.Routers.Router = Backbone.Router.extend({
        routes: {
            '': 'notes',
            'notes': 'notes',
            'note/text': 'textnote',
            'note/web': 'webnote',
            'note/todo': 'todonote',
            'note/img': 'imgnote',
            'login': 'login',
            'logout': 'logout'
        },
        initialize: function() {
            console.log('Router Init');
        },
        notes: function() {
            console.log('Router:Notes');
            this.isloading();
            // Init the main notes view
            var notesView = new notesApp.Views.Notes();
            console.log('notesView', notesView);
        },
        textnote: function() {
            console.log('New Text Note');
        },
        webnote: function() {
            console.log('New Web Note');
        },
        todonote: function() {
            console.log('New Todo Note');
        },
        imgnote: function() {
            console.log('New Image Note');
        },
        login: function() {
            // open the login modal view
            console.log('Router:Login');
            var login = new notesApp.Views.LoginView();
            var loginEl = login.render().el;
            console.log('loginEl', loginEl);
            $('.content-wrap').append(loginEl);
        },
        logout: function() {
            console.log('Logout!');
            this.isloading();
            notesApp.currentUser = new notesApp.Models.User();
            notesApp.currentUser.logout();
        },
        notloading: function() {
            $('.hero-unit').show();
            $('.hero-unit-loading').hide();
        },
        isloading: function() {
            $('.hero-unit').hide();
            $('.hero-unit-loading').show();
        }
    });
    // Init
    notesApp.router = new notesApp.Routers.Router();
})();
