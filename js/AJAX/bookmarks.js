//TODO: write something much better than this.
function GetTaggedBookmarks(callback)
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
	console.log(evt);
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
	console.log(evt);
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
	console.log(evt);
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
function AddCurrentUserBookmark(callback)
{
  $.ajax(
	{
	url: "php/bookmarks/addCurrentUserBookmark.php",
	success: function(evt){callback();}
	});
}
