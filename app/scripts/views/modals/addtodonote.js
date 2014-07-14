/*global notesApp, Backbone, JST*/
// Add Note View using the Backbone.Modal class from /vendor/backbone.modal.js 
// https://github.com/awkward/backbone.modal
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.AddTodoNoteView = Backbone.Modal.extend({
        template: JST['app/scripts/templates/addtodonote_modal.ejs'],
        cancelEl: '.bbm-button',
        submitEl: '#addButton',
        events: {
            'click #add-todo-item': 'addtodo'
        },
        attrs: {
            list: []
        },
        onRender: function() {},
        addtodo: function() {
            console.log('Add Todo Item');
            var todo = {
                'text': this.$('#new-note-todo').val().trim()
            };
            this.attrs.list.push(todo);
            this.$('#new-todo-list').append('<div>' + todo.text + '</div>');
            this.$('#new-note-todo').val('');
            this.$('#addButton').removeAttr('disabled');
        },
        newAttributes: function() {
            return {
                'title': this.$('#new-note-title').val().trim(),
                'items': this.attrs.list,
                'description': this.attrs.desc || '',
                'type': 'todo'
            };
        },
        submit: function() {
            console.log('Add new text note');
            // Must have at least some text.
            console.log('Save new note');
            notesApp.notes.create(this.newAttributes());
        }
    });
})();
