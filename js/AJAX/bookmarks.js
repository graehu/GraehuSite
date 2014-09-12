function GetTaggedBookmarks(callback)
{
  $.ajax(
    {
      url: "php/bookmarks/getTaggedBookmarks.php",
      type:"GET",
      dataType : "json",
      success: function(evt)
      {
        //console.log(evt);
        callback(evt);
      }
    }
  );
}
