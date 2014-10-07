//TODO: write something much better than this.

define(
[
  'jQuery',
  'underscore'
],
function($, _) { 'use strict';

var BookmarkServer = function()
{
  this.GetTaggedBookmarks = function (callback)
  {
  console.log("calling GetTaggedBookmarks");
    $.ajax(
      {
        url: "php/bookmarks/getTaggedBookmarks.php",
        type:"GET",
        dataType : "json",
        success: function(evt)
        {
           console.log(evt);
          callback(evt);
        }
      }
    );
  };

  this.GetBookmarkTags = function(callback)
  {
    $.ajax(
      {
        url: "php/bookmarks/getTags.php",
        type:"GET",
        dataType : "json",
        success: function(evt)
        {
           console.log(evt);
          callback(evt);
        }
      }
    );
  };

  this.UpdateBookmark = function(update)
  {
    $.ajax(
      {
        url: "php/bookmarks/updateBookmarks.php",
        type:"POST",
        data: update,
        success: function(evt)
        {
           console.log(evt);
        }
      }
    );
  };

  this.UpdateBookmarkTags = function(update)
  {
    $.ajax(
      {
        url: "php/bookmarks/updateBookmarkTags.php",
        type:"POST",
        data: update,
        success: function(evt)
        {
           console.log(evt);
        }
      }
    );
  };
  this.RemoveBookmark = function(bookmark_id)
  {
    $.ajax(
      {
        url: "php/bookmarks/removeBookmark.php",
        type:"POST",
        data: bookmark_id,
        success: function(evt)
        {
          console.log(evt);
        }
      }
    );
  };
  this.AddBookmark = function(callback)
  {
    $.ajax(
    {
    url: "php/bookmarks/addCurrentUserBookmark.php",
    success: function(evt){callback();}
    });
  };
  this.Update = function(bookmark, changes) {
    var update = {};  // Prepare update document

    if (changes.url !== bookmark.url) {  // If url is different add it to update
      update.url = changes.url;
      update.bookmark_url = changes.url;
    }

    if (changes.imgurl !== bookmark.imgurl) {  // If imgurl is different add it to update
      update.imgurl = changes.imgurl;
      update.bookmark_imgurl = changes.imgurl;
    }

    if (changes.title !== bookmark.title) {  // If title different add it to update
      update.title = changes.title;
      update.bookmark_title = changes.title;
    }

    if (_.keys(update).length > 0) {  // If we have something to change (title or url) let's do it
      update.bookmark_id = bookmark.id;
      this.UpdateBookmark(update);
    }

    bookmark.tags = _.filter(bookmark.tags, function(t) { return t.custom === false; });

    if (changes.customTags && changes.customTags.length > 0) {
      var tagsUpdate = {}
      tagsUpdate.bookmark_id = bookmark.id;
      tagsUpdate.tags = changes.customTags;
      this.UpdateBookmarkTags(tagsUpdate);
    }
    };
};

/*
* BookmarkServer factory method.
*/
var BookmarkServerFactroy = function() {
  return new BookmarkServer();
};

return [
  BookmarkServerFactroy
];

});
