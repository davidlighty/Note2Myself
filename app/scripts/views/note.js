/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.Note = Backbone.View.extend({
        tagName: 'li',
        template: JST['app/scripts/templates/note.ejs'],
        className: 'note-item',
        initialize: function() {
            console.log('Note View :: Init');
            //this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);
            this.listenTo(this.model, 'error', this.handleError);
        },
        events: {
            'click .update-note': 'save',
            'click .delete-note': 'clear',
            'click .edit-note': 'edit'
        },
        render: function() {
            console.log('this.$el', this.$el);
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
        clearAttributes: function() {
            this.$('.note-title.edit').val('');
            this.$('.note-text.edit').val('');
        },
        editing: false,
        edit: function() {
            var self = this;
            this.editing = !this.editing;
            // Switch to inputs
            this.$('.note-title,.note-text,.note-title-edit,.note-text-edit').toggle();
            if (!this.editing) {
                if (this.modelsave) {
                    this.model = this.modelsave;
                }
                this.$('.edit-note').html('Edit');
                this.render();
            } else {
                this.modelsave = this.model;
                this.$('.edit-note').html('Cancel');
                // wire up liveUrl detection
                this.$('.note-text-edit').liveUrl({
                    success: function(data) {
                        console.log(data);
                        // this return the first found url data
                        self.model.set('type', 'website');
                        self.model.set('title', data.url);
                        self.model.set('text', data.title);
                        self.model.set('description', data.description);
                    }
                });
            }
        },
        save: function() {
            console.log('update note');
            // Set changes and Save
            this.model.set('title', this.$('.note-title-edit').val().trim());
            this.model.set('text', this.$('.note-text-edit').val().trim());
            console.log('this.model', this.model);
            this.model.save();
            this.render();
        },
        clear: function() {
            console.log('delete note', this.model.toJSON());
            this.model.destroy({
                wait: true
            });
        },
        handleError: function() {
            console.log('Error!');
            this.$el.addClass('alert-warning');
        }
    });
})();