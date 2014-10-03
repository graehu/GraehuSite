define(
[
'angular',
'./main',
'./editBookmark',
'./login'
],
function(angular, mainCtrl, editBookmarkCtrl, loginCtrl) { 'use strict';

// Creates `dewey.controllers` module
var module = angular.module('dewey.controllers', ['dewey.services']);

// Register main controller
module.controller('mainController', mainCtrl);

// Register editBookmark controller
module.controller('editBookmarkController', editBookmarkCtrl);

//Register settings controllers
module.controller('loginController', loginCtrl);

});
