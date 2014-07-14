/*global notesApp, Backbone*/
notesApp.Routers = notesApp.Routers || {};
(function() {
    'use strict';
    notesApp.Routers.Router = Backbone.Router.extend({
        routes: {
            'notes': 'notes',
            'login': 'login',
            'logout': 'logout',
            '*path': 'notes'
        },
        initialize: function() {
            console.log('Router Init');
        },
        notes: function() {
            console.log('Router:Notes');
            this.isloading();
            // Init the main notes view
            notesApp.notesView.initfetch();
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
