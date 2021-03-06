/*global notesApp, Backbone*/
notesApp.Models = notesApp.Models || {};
(function() {
    'use strict';
    notesApp.Models.User = Backbone.Model.extend({
        urlRoot: 'api/login',
        idAttribute: '_id', // Mongo id attribute
        defaults: {
            "email": "",
            "loggedIn": false
        },
        initialize: function() {
            console.log('User Model :: Init', this.url);
        },
        parse: function(data) {
            console.log('data', data);
            return data;
        },
        logout: function() {
            console.log('Logout this session');
            $.post('./api/logout', function(data) {
                notesApp.router.navigate('#/');
            });
        }
    });
})();
