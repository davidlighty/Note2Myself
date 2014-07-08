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
            this.collection = new notesApp.Collections.Notes();
            // Don't call render until our xhr is finished
            this.collection.fetch().done(function() {
                console.log('initialize fetch finished');
                self.render();
            });
            this.listenTo(this.collection, 'error', this.errorHandler);
        },
        events: {
            'keypress #new-note-text': 'createOnEnter',
            'click #save-new-note': 'create'
        },
        render: function() {
            console.log('Notes View::Render');
            var self = this;
            console.log(this.collection.models);
            this.$el.html(this.template());
            this.$input = this.$('#new-note-text');
            this.$list = this.$('#notes-list');
            _.each(this.collection.models, function(note) {
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
        newAttributes:function(){
            return {
                    "title":  this.$('#new-note-title').val().trim()
                  , "description": ""
                  , "userid": "0001"
                  , "text": this.$('#new-note-text').val().trim()
                  , "type": "text"
                  , "created" : Date()

            }
        },
        clearAttributes:function(){
            this.$('#new-note-title').val('');
            this.$('#new-note-text').val('');
        },
        create: function() {
            console.log('Save new note');
            this.collection.create(this.newAttributes());
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