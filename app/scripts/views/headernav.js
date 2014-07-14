/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.HeaderNav = Backbone.View.extend({
        el: $('#header-nav'),
        template: JST['app/scripts/templates/nav.ejs'],
        initialize: function() {
            console.log('HeaderNav View :: Init');
            this.render();
        },
        events: {},
        render: function() {
            console.log('HeaderNav View::Render');
            this.$el.html(this.template());
            return this;
        }
    });
})();
