define(
[
/* No dependencies */
],
function() { 'use strict';

var routesConfiguration = function($routeProvider, $locationProvider) {
  $routeProvider.when('/main', {
    templateUrl: 'partials/main.tpl.php',
    controller: 'mainController'
  });
  $routeProvider.otherwise({redirectTo: '/main'});
};

return ['$routeProvider', '$locationProvider', routesConfiguration];

});
