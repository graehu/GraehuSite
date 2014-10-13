define(
[
'angular',
'./main',
'./editBookmark',
'./login',
'./partial'
],
function(angular, mainCtrl, editBookmarkCtrl, loginCtrl, partialCtrl) { 'use strict';

// Creates `dewey.controllers` module
var module = angular.module('dewey.controllers', ['dewey.services']);

// Register main controller
module.controller('mainController', mainCtrl);

// Register editBookmark controller
module.controller('editBookmarkController', editBookmarkCtrl);

//Register login controller
module.controller('loginController', loginCtrl);

//Register partial controller
module.controller('partialController', partialCtrl);

});
