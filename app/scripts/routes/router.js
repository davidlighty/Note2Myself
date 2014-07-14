/*global notesApp, Backbone*/
notesApp.Routers = notesApp.Routers || {};
(function() {
    'use strict';
    notesApp.Routers.Router = Backbone.Router.extend({
        routes: {
            '': 'notes',
            'notes': 'notes',
            'login': 'login'
        },
        initialize: function() {
            console.log('Router Init');
        },
        home: function() {
            console.log('Router:Home');
            if (notesApp.User) {
                // Notes
                this.notes();
            } else {
                // Home
                var homeView = new notesApp.Views.Home();
                this.notloading();
            }
        },
        notes: function() {
            console.log('Router:Notes');
            this.isloading();
            // Init the main notes view
            var notesView = new notesApp.Views.Notes();
            console.log('notesView', notesView);
        },
        login: function() {
            // open the login modal view
            console.log('Router:Login');
            var login = new notesApp.Views.LoginView();
            var loginEl = login.render().el;
            console.log('loginEl', loginEl);
            $('.content-wrap').append(loginEl);
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