/*global notesApp, Backbone, JST*/

// Login View using the Backbone.Modal class from /vendor/backbone.modal.js 
// https://github.com/awkward/backbone.modal
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.LoginView = Backbone.Modal.extend({
        template: JST['app/scripts/templates/login.ejs'],
        cancelEl: '.bbm-button',
        submitEl:'#loginButton',
        events: {
            'click #loginButton': 'login'
        },
        login: function(event) {
            event.preventDefault(); // Don't let this button submit the form
            $('.alert-error').hide(); // Hide any errors on a new submit
            var url = './api/login';
            console.log('Loggin in... ');
            var formValues = {
                email: this.$('#inputEmail').val(),
                password: this.$('#inputPassword').val()
            };
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: formValues,
                success: function(data) {
                    console.log(['Login request details: ', data]);
                    if (data.error) { // If there is an error, show the error messages
                        $('.alert-error').text(data.error.text).show();
                    } else { // If not, send them back to the home page
                        notesApp.router.navigate('#/');
                        notesApp.User = data;
                    }
                }
            });
        }
    });
})();