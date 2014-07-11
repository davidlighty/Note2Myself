/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.Home = Backbone.View.extend({
        el: $('.main'),
        template: JST['app/scripts/templates/home.ejs'],
        initialize: function() {
            console.log('Home View :: Init');
            this.render();
        },
        events: {},
        render: function() {
            console.log('Home View::Render');
            this.$el.html(this.template());
            return this;
        }
    });
})();