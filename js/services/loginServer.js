define(
['jQuery'],
function($)
{
    var LoginServer = function()
    {
      this.isLoggedIn = function(callback)
      {
      $.ajax(
        {
          url: "php/isLoggedIn.php",
          type:"GET",
          success: function(evt)
          {
            callback(evt);
          }
        }
      );
      }
    }
    /*
    * BookmarkServer factory method.
    */
    var LoginServerFactory = function() {
      return new LoginServer();
    };

    return [
      LoginServerFactory
    ];

  }
);
