var _gaq = _gaq || [];
//TODO Update google analytics
_gaq.push(['_setAccount', 'UA-48899273-2']);
_gaq.push(['_trackPageview']);

(function(){ 'use strict';

var ga = document.createElement('script');
ga.type = 'text/javascript';
ga.async = true;
ga.src = 'https://ssl.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ga, s);


require.config({
  baseUrl: 'js',
  paths: {
    'underscore': '../bower_components/underscore/underscore',
    'jQuery': '../bower_components/jquery/jquery',
    'angular': '../bower_components/angular/angular',
    'angular-route': '../bower_components/angular-route/angular-route',
    'bootstrap': '../bower_components/bootstrap/dist/js/bootstrap',
    'bootstrap-tagsinput': '../bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput',
    'bootstrap-tagsinput-angular': 'lib/bootstrap-tagsinput-angular',
    'ui.bootstrap': '../bower_components/ui-bootstrap/dist/ui-bootstrap-custom-tpls-0.10.0',
    'color-thief': '../bower_components/color-thief/js/color-thief',
    'highlight':'../bower_components/highlight/highlight.pack',
    'unity-webplayer':'../bower_components/unity-webplayer/unity-webplayer'
  },
  shim: {
    'jQuery': {
      exports : 'jQuery'
    },
    'underscore': {
      exports : '_'
    },
    'angular': {
      deps: ['jQuery'],
      exports : 'angular'
    },
    'angular-route': {
      deps: ['angular']
    },
    'bootstrap': {
      deps: ['jQuery'],
      exports : 'bootstrap'
    },
    'ui.bootstrap': {
      deps: ['jQuery','bootstrap', 'angular']
    },
    'bootstrap-tagsinput': {
      deps: ['bootstrap']
    },
    'bootstrap-tagsinput-angular': {
      deps: ['bootstrap-tagsinput', 'angular']
    },
    'color-thief': {
      exports: 'ColorThief'
    },
    'unity-webplayer': {
      deps:['jQuery'],
      exports: 'unity-webplayer'
    },
    'highlight': {
      deps:['jQuery'],
      exports: 'highlight'
    }
  }
});

require([
  'angular',
  './dewey'
],
function(angular) {
  angular.bootstrap(document, ['dewey']);
});

})();
