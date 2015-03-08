define(
[
  'underscore'
],
function(_) { "use strict";

/*
* Bookmarks storage.
*/
var BookmarksStorage = function () {

  var bookmarks = {};
  var customTagsStorage = [];

/*
* Find chunk which stores custom tags for bookmark and remove this information.
*/
var removeCustomTags = function(bookmarkUrl) {
  _.each(customTagsStorage, function(chunk, index) {
    if (chunk.d[bookmarkUrl]) {
      delete chunk.d[bookmarkUrl];
      saveCustomTagsChunk(index, chunk);
    }
  });
};
this.update = function(bookmark, changes) {
    var update = {};  // Prepare update document

    if (changes.url !== bookmark.url) {  // If url is different add it to update
      update.url = changes.url;
      update.bookmark_url = changes.url;
    }

    if (changes.title !== bookmark.title) {  // If title different add it to update
      update.title = changes.title;
      update.bookmark_title = changes.title;
    }

    if (_.keys(update).length > 0) {  // If we have something to change (title or url) let's do it
      update.bookmark_id = bookmark.id;
      _.extend(bookmark, update);  // Copy all updates to bookmark after updating chrome bookmarks
    }

    removeCustomTags(bookmark.url);
    bookmark.tags = _.filter(bookmark.tags, function(t) { return t.custom === false; });
    if (changes.customTags && changes.customTags.length > 0) {
      var tagsUpdate = {}
      tagsUpdate.bookmark_id = bookmark.id;
      tagsUpdate.tags = changes.customTags;
      _.each(changes.customTags, function(tag){
      bookmark.tags.push({text: tag, custom: true});
      });
    }
  };
};

/*
* Bookmarks storage factory method.
*/
var BookmarksStorageFactory = function() {
  return new BookmarksStorage();
};

return [
  BookmarksStorageFactory
];

});
