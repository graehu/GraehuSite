//TODO: write something much better than this.
function GetTaggedBookmarks(callback)
{
  $.ajax(
    {
      url: "php/bookmarks/getTaggedBookmarks.php",
      type:"GET",
      dataType : "json",
      success: function(evt)
      {
        callback(evt);
      }
    }
  );
}

function GetBookmarkTags(callback)
{
  $.ajax(
    {
      url: "php/bookmarks/getTags.php",
      type:"GET",
      dataType : "json",
      success: function(evt)
      {
        callback(evt);
      }
    }
  );
}

function UpdateBookmark(update)
{
  $.ajax(
    {
      url: "php/bookmarks/updateBookmarks.php",
      type:"POST",
      data: update,
      success: function(evt)
      {
      }
    }
  );
}

function UpdateBookmarkTags(update)
{
  $.ajax(
    {
      url: "php/bookmarks/updateBookmarkTags.php",
      type:"POST",
      data: update,
      success: function(evt)
      {
      }
    }
  );
}
function RemoveBookmark(bookmark_id)
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
}
