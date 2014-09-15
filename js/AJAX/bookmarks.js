function GetTaggedBookmarks(callback)
{
  $.ajax(
    {
      url: "php/bookmarks/getTaggedBookmarks.php",
      type:"GET",
      dataType : "json",
      success: function(evt)
      {
        // console.log("GetTaggedBookmarks: called")
        // console.log(evt);
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
        // console.log("GetBookmarkTags: called")
        // console.log(evt);
        callback(evt);
      }
    }
  );
}

function UpdateBookmark(update, callback)
{
  $.ajax(
    {
      url: "php/bookmarks/updateBookmarks.php",
      type:"POST",
      //dataType : "json",
      data: update,
      success: function(evt)
      {
        callback(evt);
      }
    }
  );
}
