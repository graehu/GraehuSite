define(
[
'angular',
'./bookmarksStorage',
'./booleanSearchEngine',
'./settings',
'./bookmarkServer'
],
function(angular, bookmarksStorage, booleanSearchEngine, settings, bookmarkServer) { 'use strict';

// Creates new module 'dewey.filters'
var module = angular.module('dewey.services', []);

// Register bookmarksStorage service
module.factory('bookmarksStorage', bookmarksStorage);

// Register booleanSearchEngine service
module.factory('booleanSearchEngine', booleanSearchEngine);

// Register login service
// module.factory('loginServer', login);

// Register bookmarks service
module.factory('bookmarkServer', bookmarkServer);

// Register settings service
module.value('appSettings', settings);

});
