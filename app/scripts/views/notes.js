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
            console.log('Notes View :: Init');
             var self = this;
            this.collection = new notesApp.Collections.Notes();
            // Don't call render until our xhr is finished
            this.collection.fetch().done(function() {
                self.render();
            });
        },
        render: function () {
            this.$el.html(this.template(this.model.toJSON()));
        }

    });

})();
