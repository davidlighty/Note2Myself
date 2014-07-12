/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.NoteTypeURL = Backbone.View.extend({
        template: JST['app/scripts/templates/notetype/website.ejs'],
        initialize: function() {
            console.log('NoteTypeURL :: Init');
        },
        render: function() {
            console.log('this.$el', this.$el);
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });
})();