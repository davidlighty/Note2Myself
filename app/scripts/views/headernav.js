/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.HeaderNav = Backbone.View.extend({
        el: $('#header-nav'),
        template: JST['app/scripts/templates/nav.ejs'],
        events: {
            'click .note-text': 'textnote',
            'click .note-web': 'webnote',
            'click .note-todo': 'todonote',
            'click .note-img': 'imgnote',
        },
        initialize: function() {
            console.log('HeaderNav View :: Init');
            this.render();
        },
        render: function() {
            console.log('HeaderNav View::Render');
            this.$el.html(this.template());
            return this;
        },
        textnote: function() {
            console.log('New Text Note');
            var addnote = new notesApp.Views.AddNoteView();
            $('.hero-unit').append(addnote.render().el);
        },
        webnote: function() {
            console.log('New Web Note');
            var addnote = new notesApp.Views.AddWebNoteView();
            $('.hero-unit').append(addnote.render().el);
        },
        todonote: function() {
            console.log('New Todo Note');
            var addnote = new notesApp.Views.AddTodoNoteView();
            $('.hero-unit').append(addnote.render().el);
        },
        imgnote: function() {
            console.log('New Image Note');
            var addnote = new notesApp.Views.AddImgNoteView();
            $('.hero-unit').append(addnote.render().el);
        }
    });
})();
