define(
[
  'jQuery',
  'color-thief'
],
function($, ColorThief) { 'use strict';

var myUpdateBackgroundFactory = function(appSettings) {
  var thief = new ColorThief();

  return function(scope, element, attrs) {
    scope.$watch(attrs.dLoad, function(value) {
      element.on('load', function() {
        var color = null;

        try {
          color = thief.getColor(element.get(0));
        } catch(e) {}


        var backgrounds = [];

        if (appSettings.showThumbnails) {
          if(scope.bookmark.imgurl)
          {
            backgrounds.push(
              'url('+scope.bookmark.imgurl+')'
            );
            color = [256, 256, 256];
          }
          else
          {
            backgrounds.push(
              'url(\'http://api.snapito.io/v2/webshot/spu-913309-tx3f-zfnbbpfrpzeeom7i?url=' +
                scope.bookmark.url +
                '&size=250x188&screen=1000x752&quality=low&type=jpg\')'
            );
          }
        }

        backgrounds.push(color ? 'rgb(' + color.join(',') + ')' : 'white');

        var thumbnail = $('.thumbnail-loading', element.parent().parent().parent());
        thumbnail
          .removeClass('thumbnail-loading')
          .addClass('thumbnail')
          .css('background', backgrounds.join(', '));
      });
    });
  };
};

return [
  'appSettings',
  myUpdateBackgroundFactory
];

});
