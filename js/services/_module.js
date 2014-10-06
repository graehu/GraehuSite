define(
[
'angular',
'./bookmarksStorage',
'./booleanSearchEngine',
'./settings',
'./bookmarkServer',
'./loginServer'
],
function(angular, bookmarksStorage, booleanSearchEngine, settings, bookmarkServer, loginServer) { 'use strict';

// Creates new module 'dewey.filters'
var module = angular.module('dewey.services', []);

// Register bookmarksStorage service
module.factory('bookmarksStorage', bookmarksStorage);

// Register booleanSearchEngine service
module.factory('booleanSearchEngine', booleanSearchEngine);

// Register bookmarks service
module.factory('bookmarkServer', bookmarkServer);

// Register login service
module.factory('loginServer', loginServer);

// Register settings service
module.value('appSettings', settings);

});
