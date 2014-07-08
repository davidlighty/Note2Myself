/*global notesApp, Backbone, JST*/

notesApp.Views = notesApp.Views || {};
(function() {
	'use strict';
    notesApp.Views.Alert = Backbone.View.extend({
    	el:'div',
        initialize: function() {
            console.log('Alert View :: Init');
            var self = this;
            notesApp.vent.on('error:event',this.showError);
        },
        render:function(){

        },
        showError:function(error){
        	console.log('Got Error Event',error);
        }
    });
})();