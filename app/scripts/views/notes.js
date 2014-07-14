/*global notesApp, Backbone, JST,ENTER_KEY*/
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
        },
        events: {
            'keypress #new-note-text': 'createOnEnter',
            'click .note-add': 'showNewNoteModal'
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
                    self.quickNoteType = 'website';
                    self.website = data;
                }
            });
            this.$list = this.$('#notes-list');
            _.each(notesApp.notes.models, function(note) {
                // console.log('note::' + note.toJSON());
                self.renderNote(note);
            }, this);
            notesApp.router.notloading();
            // Tooltips
            this.$('.quick-note-text').tooltip();

            return this;
        },
        renderNote: function(note) {
            console.log('Notes View::RenderNote', this.$list);
            var noteView = new notesApp.Views.Note({
                model: note
            });
            this.$list.append(noteView.render().el);
        },
        website: {},
        quickNoteType: 'text',
        newAttributes: function() {
            var title = this.website.url || "",
                description = this.website.description || "";
            return {
                "title": title,
                "text": this.$('#new-note-text').val().trim(),
                "description": description,
                "type": this.quickNoteType
            };
        },
        clearAttributes: function() {
            this.$('#new-note-text').val('');
            this.website = {};
            this.quickNoteType = 'text';
        },
        create: function() {
            // Must have at least some text.
            if (this.$input.val().trim()) {
                console.log('Save new note');
                notesApp.notes.create(this.newAttributes());
                this.clearAttributes();
            }
        },
        createOnEnter: function(e) {
            // New model, and save.
            if (e.which === ENTER_KEY && this.$input.val().trim()) {
                console.log('Save new note : ENTER_KEY');
                this.create();
                this.render();
            }
        },
        showNewNoteModal: function() {
            var addnote = new notesApp.Views.AddNoteView();
            $('.hero-unit').append(addnote.render().el);
        },
        errorHandler: function(model, error) {
            console.log('Error in Collection', error);
            // Show error in footer "message" area.
            notesApp.vent.trigger('error:event', error);
        }
    });
})();
