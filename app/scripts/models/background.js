/*global notesApp, Backbone*/
notesApp.Models = notesApp.Models || {};
(function() {
    'use strict';
    notesApp.Models.Background = Backbone.Model.extend({
        defaults: {
            'photoURL': '',
            'photoCopy': '',
            'photoCopyURL': ''
        },
        initialize: function() {
            console.log('User Model :: Init', this.url);
            this.getPhotoURL();
        },
        getPhotoURL: function() {
            var url = './api/bingphoto';
            var self = this;
            $.getJSON(url, function(json) {
                console.log('json', json);
                var image = json.images[0];
                console.log('image data', image);
                self.set('photoURL', 'http://www.bing.com' + image.url);
                self.set('photoCopy', image.copyright);
                self.set('photoCopyURL', image.copyrightlink);
                // set a nice background Bing photo of the day.
                $('.content-wrap').css('background-image', 'url(' + self.get('photoURL') + ')');
                $('.img-info').html('<a href="' + self.get('photoCopyURL') + '" target="_blank"><small>Bing Image: &copy; ' + self.get('photoCopy') + '</small></a>');
            });

        }
    });

    // Init now!
    notesApp.background = new notesApp.Models.Background();
})();
