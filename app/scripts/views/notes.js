/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.Notes = Backbone.View.extend({
        el:$('.main'),
        template: JST['app/scripts/templates/notes.ejs'],
        tagName: 'div',
        initialize: function() {
            console.log('Notes View :: Init');
            var self = this;
            this.collection = new notesApp.Collections.Notes();
            // Don't call render until our xhr is finished
            this.collection.fetch().done(function() {
                console.log('initialize fetch finished');
               self.render();
            });
        },
        render: function() {
            console.log('Notes View::Render');
            var self = this;
            console.log(this.collection.models);
             this.$el.html(this.template());
            _.each(this.collection.models, function(note) {
               // console.log('note::' + note.toJSON());
                self.renderNote(note);
            }, this);
            return this;
        },
        renderNote: function(note) {
            console.log('Notes View::RenderNote');
            var noteView = new notesApp.Views.Note({
                model: note
            });
            this.$el.append(noteView.render().el);
        }
    });
})();