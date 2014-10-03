define(
[
  'jQuery'
],
function($) { 'use strict';
var EditBookmarkController = function ($scope, $modalInstance, bookmark, bookmarksStorage) {

  $scope.modalClass = "modal-dialog";
  console.log($scope.modalClass);
  $scope.bookmarkModel = {
    title: bookmark.title,
    url: bookmark.url,
    folders: _.map(_.filter(bookmark.tags, function(t) { return t.custom === false; }), function(t) { return t.text; }),
    customTags: _.map(_.filter(bookmark.tags, function(t) { return t.custom === true; }), function(t) { return t.text; }),
  };

  $scope.save = function() {
    _gaq.push(['_trackEvent', 'BookmarkEdit', 'editBookmark-save']);
    bookmarksStorage.update(bookmark, $scope.bookmarkModel);
    $modalInstance.close(bookmark);
  };

  $scope.cancel = function() {
    _gaq.push(['_trackEvent', 'BookmarkEdit', 'editBookmark-cancel']);
    $modalInstance.dismiss('cancel');
  };

  $scope.delete = function() {
    _gaq.push(['_trackEvent', 'editBookmark-delete']);
    if (confirm('Are you sure that you want to delete this bookmark?')) {
      _gaq.push(['_trackEvent', 'BookmarkEdit', 'editBookmark-delete-deleted']);
      bookmarksStorage.remove(bookmark);
      $modalInstance.close(null);
    }
  };
};

return [
  '$scope',
  '$modalInstance',
  'bookmark',
  'bookmarksStorage',
  EditBookmarkController
];

});
