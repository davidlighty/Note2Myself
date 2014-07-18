/*global notesApp, Backbone, JST*/
notesApp.Views = notesApp.Views || {};
(function() {
    'use strict';
    notesApp.Views.NoteTypeImage = Backbone.View.extend({
        template: JST['app/scripts/templates/notetype/image.ejs'],
        events: {
            'click img': 'showImage'
        },
        initialize: function() {
            console.log('Init Image Note');
            // To re-render ourself with image.
            //this.listenTo('change', this.render);
        },
        render: function() {
            console.log('this.$el', this.$el);
            this.$el.html(this.template(this.model.toJSON()));
            // this.getImage();
            return this;
        },
        getImage: function() {
            console.log('Get Image');
            var url = './api/image/' + this.model.get('imageId');
            $.get(url, function(data) {
                console.log('Got Data', data);
            });

        },
        showImage: function() {
            var iid = this.model.get('imageId');
            console.log('show: ' + iid);
            var fullImageModal = new notesApp.Views.FullImage({
                model: this.model
            });
            //fullImageModal.model.set('imageId', iid);
            console.log('model', fullImageModal.model);
            $('.hero-unit').append(fullImageModal.render().el);
        }
    });
})();
