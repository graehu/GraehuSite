define(
[
  'jQuery'
],
function($) { 'use strict';
var LoginController = function ($scope, $modalInstance)
{
  $scope.closeLogin = function()
  {
    $modalInstance.dismiss('cancel');
  };
};

return [
  '$scope',
  '$modalInstance',
  LoginController
];

});
