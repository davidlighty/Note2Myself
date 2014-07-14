/*global notesApp, Backbone*/
notesApp.Models = notesApp.Models || {};
(function() {
    'use strict';
    notesApp.Models.Background = Backbone.Model.extend({
        defaults: {
            'photoURL': '',
            'photoDesc': ''
        },
        initialize: function() {
            console.log('User Model :: Init', this.url);
            this.getPhotoURL();
        },
        getPhotoURL: function() {
            var url = './api/bingphoto';
            var self = this;
            // $.ajax({
            //     url: url,
            //     type: 'GET',
            //     dataType: 'json',
            //     crossDomain: true,
            //     success: function(response) {
            //         console.log('photo data', response);
            //         // parse and populate!
            //     },
            //     error: function(xhr, status) {
            //         console.log('photo error', xhr);
            //     }
            // });

            $.getJSON(url, function(json) {
                console.log('json', json);
                var image = json.images[0];
                console.log('image data', image);
                self.set('photoURL', 'http://www.bing.com' + image.url);
            });

        }
    });

    // Init now!
    notesApp.background = new notesApp.Models.Background();
})();
