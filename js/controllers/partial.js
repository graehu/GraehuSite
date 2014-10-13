define(
[
  'jQuery',
  'unity-webplayer'
],
function($) { 'use strict';
var PartialController = function ($scope, $modalInstance)
{
  $scope.closePartial = function()
  {
    $modalInstance.dismiss('cancel');
  };
};

return [
  '$scope',
  '$modalInstance',
  PartialController
];

});