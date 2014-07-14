/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.NoteTypeTodo = Backbone.View.extend({
        template: JST['app/scripts/templates/notetype/todo.ejs'],
        render: function() {
            console.log('this.$el', this.$el);
            this.$el.html(this.template(this.model.toJSON()));
            this.renderItems();
            return this;
        },
        renderItems: function() {
            console.log('Render todo items', this.model.get('items'));
            _.each(this.model.get('items'), function(note) {
                console.log('note', note);
                this.$('.note-items').append('<li>' + note.text + '</li>'); // This really should be a view with the ability to remove/mark done.
            }, this);
        }
    });
})();
