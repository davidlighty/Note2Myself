/*global notesApp, Backbone, JST*/

notesApp.Views = notesApp.Views || {};

(function () {
    'use strict';

    notesApp.Views.Notes = Backbone.View.extend({
        el:'.main',
        template: JST['app/scripts/templates/notes.ejs'],

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
