/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.Notes = Backbone.View.extend({
        el: $('.main'),
        template: JST['app/scripts/templates/notes.ejs'],
        initialize: function() {
            console.log('Notes View :: Init');
            var self = this;
            // Don't call render until our xhr is finished
            notesApp.notes.fetch({
                add: true
            }).done(function() {
                console.log('initialize fetch finished');
                self.render();
            });
            this.listenTo(notesApp.notes, 'error', this.errorHandler);
            this.listenTo(notesApp.notes, 'add', this.render);
        },
        events: {
            'keypress #new-note-text': 'createOnEnter',
            'click #save-new-note': 'create'
        },
        render: function() {
            console.log('Notes View::Render');
            var self = this;
            console.log(notesApp.notes.models);
            this.$el.html(this.template());
            this.$input = this.$('#new-note-text');
            this.$input.liveUrl({
                success: function(data) {
                    console.log(data);
                    // this return the first found url data
                    // self.model.set('type', 'website');
                    // self.model.set('title', data.title);
                    // self.model.set('text', data.url);
                    // self.model.set('description', data.description);
                }
            });
            this.$list = this.$('#notes-list');
            _.each(notesApp.notes.models, function(note) {
                // console.log('note::' + note.toJSON());
                self.renderNote(note);
            }, this);
            return this;
        },
        renderNote: function(note) {
            console.log('Notes View::RenderNote', this.$list);
            var noteView = new notesApp.Views.Note({
                model: note
            });
            this.$list.append(noteView.render().el);
        },
        newAttributes: function() {
            return {
                "title": this.$('#new-note-title').val().trim(),
                "description": "",
                "userid": "0001",
                "text": this.$('#new-note-text').val().trim(),
                "type": "text",
                "created": Date()
            }
        },
        clearAttributes: function() {
            this.$('#new-note-title').val('');
            this.$('#new-note-text').val('');
        },
        create: function() {
            // Must have at least some text.
            if (this.$('#new-note-text').val().trim()) {
                console.log('Save new note');
                notesApp.notes.create(this.newAttributes());
                this.clearAttributes();
            }
        },
        createOnEnter: function(e) {
            // New model, and save.
            if (e.which === ENTER_KEY && this.$input.val().trim()) {
                console.log('Save new note');
                this.create();
            }
        },
        errorHandler: function(model, error) {
            console.log('Error in Collection', error);
            // Show error in footer "message" area.
            notesApp.vent.trigger('error:event', error);
        }
    });
})();