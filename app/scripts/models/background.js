/*global notesApp, Backbone*/
notesApp.Models = notesApp.Models || {};
(function() {
    'use strict';
    notesApp.Models.Background = Backbone.Model.extend({
        defaults: {
            'photoURL': '',
            'photoCopy': '',
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
                self.set('photoCopy', image.copyright);
                self.set('photoDesc', image.hs.desc);
                // set a nice background Bing photo of the day.
                $('.content-wrap').css('background-image', 'url(' + notesApp.background.get('photoURL') + ')');
                $('.img-info').html('<small>Bing Image: &copy; ' + notesApp.background.get('photoCopy') + '</small>');
            });

        }
    });

    // Init now!
    notesApp.background = new notesApp.Models.Background();
})();
