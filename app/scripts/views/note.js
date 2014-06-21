/*global notesApp, Backbone, JST*/

notesApp.Views = notesApp.Views || {};

(function () {
    'use strict';

    notesApp.Views.Note = Backbone.View.extend({
        el:'.note-list-section'
        template: JST['app/scripts/templates/note.ejs'],

        tagName: 'div',

        id: '',

        className: '',

        events: {},

        initialize: function () {
            this.listenTo(this.model, 'change', this.render);
        },

        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
        }

    });

})();
