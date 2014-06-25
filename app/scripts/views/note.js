/*global notesApp, Backbone, JST*/

notesApp.Views = notesApp.Views || {};

(function () {
    'use strict';

    notesApp.Views.Note = Backbone.View.extend({
       // el:$('.notes-list'),
        template: JST['app/scripts/templates/note.ejs'],
        tagName: 'li',
        id: '',
        className: 'note-item',
        events: {},
        initialize: function () {
            console.log('Note View :: Init');
            this.listenTo(this.model, 'change', this.render);
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
            console.log('el',this.el);
            return this;
        }

    });

})();
