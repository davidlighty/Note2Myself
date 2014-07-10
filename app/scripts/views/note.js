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
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.remove);
            this.listenTo(this.model, 'error', this.handleError);
        },
        events: {
            'click .update-note':'save',
            'click .delete-note':'clear'
        },
        render: function() {
            console.log('this.$el', this.$el);
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
        save:function(){
            console.log('save note');
            // Set changes and Save
            this.model.set('title', this.$('.note-title').text());
            this.model.set('text', this.$('.note-text').text());
            console.log('this.model',this.model);
            this.model.save({
                wait:true
            });
        },
        clear: function() {
            console.log('delete note', this.model.toJSON());
            this.model.destroy({
                wait:true
            });
        },
        handleError:function(){
            console.log('Error!');
            this.$el.addClass('alert-warning');
        }
    });
})();