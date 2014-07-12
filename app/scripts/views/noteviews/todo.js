/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.NoteTypeTodo = Backbone.View.extend({
        template: JST['app/scripts/templates/notetype/text.ejs'],
        render: function() {
            console.log('this.$el', this.$el);
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });
})();