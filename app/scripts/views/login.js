/*global notesApp, Backbone, JST*/
// Login View using the Backbone.Modal class from /vendor/backbone.modal.js 
// https://github.com/awkward/backbone.modal
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.LoginView = Backbone.Modal.extend({
        template: JST['app/scripts/templates/login.ejs'],
        cancelEl: '.bbm-button',
        events: {
            'click #loginButton':'login',
            'click #registerButton':'register',
            'click #forgotpassword': 'forgotpassword',
            'click .close-login': 'gohome'
        },
        beforeSubmit: function() {
            console.log('beforeSubmit');
            return this.$('#login-form')[0].checkValidity();
        },
        forgotpassword:function(){
            console.log('forgot password');
            var self=this;
            $.post('./api/forgot',JSON.stringify({ "email" : this.$('#inputEmail').val() }), 
                function(data) {
                    console.log('data',data);
                self.$('.alert-error').html(data.error.text).show();
            }, 'json').error(function(){
                console.log('failed');
                self.$('.alert-error').html('Failed to send email, internal error.').show();
            });
        },
        login: function() {
            $('.alert-error').hide(); // Hide any errors on a new submit
            var self = this;
            console.log('Loggin in... ');
            notesApp.currentUser = new notesApp.Models.User();
            notesApp.currentUser.save({
                email: this.$('#inputEmail').val(),
                password: this.$('#inputPassword').val()
            }, {
                success: function(model, data) {
                    console.log('login response', data);
                    if (data.error) {
                        // Error.
                        console.log('error', self);
                        //var loginEl = self.render().el;
                        self.$('.alert-error').html(data.error.text).show();
                    } else {
                        console.log('success');
                        notesApp.currentUser.loggedIn = true;
                        self.close();
                        notesApp.router.navigate('#/');
                    }
                },
                error: function(model, data) {
                    console.log('data', data);
                }
            });
        },
        register:function(){
            console.log('register');
            var self = this;
            notesApp.currentUser = new notesApp.Models.User();
            notesApp.currentUser.save({
                email: this.$('#inputEmail').val(),
                password: this.$('#inputPassword').val(),
                register: true
            }, {
                success: function(model, data) {
                    console.log('login response', data);
                    if (data.error) {
                        // Error.
                        console.log('error', self);
                        //var loginEl = self.render().el;
                        self.$('.alert-error').html(data.error.text).show();
                    } else {
                        console.log('success');
                        notesApp.currentUser.loggedIn = true;
                        self.close();
                        notesApp.router.navigate('#/');
                    }
                },
                error: function(model, data) {
                    console.log('data', data);
                }
            });
        },
        beforeCancel: function() {
            console.log('Login Cancel');
            // Show a required alert.
            // alert-error
            $('.alert-error').text('Please sign-up or register.').show();
            return false;
        }
    });
})();