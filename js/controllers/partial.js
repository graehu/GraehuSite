define(
[
  'jQuery',
  'unity-webplayer',
  'highlight'
],
function($, unity, highlight) { 'use strict';
var PartialController = function ($scope, $modalInstance, $timeout)
{
  $scope.closePartial = function()
  {
    $modalInstance.dismiss('cancel');
  };
  $timeout(function()
  {
    $('pre code').each(function(i, block) {
      highlight.highlightBlock(block);
    });
  });
};

return [
  '$scope',
  '$modalInstance',
  '$timeout',
  PartialController
];

});
