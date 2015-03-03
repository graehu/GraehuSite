function LogOut()
{
  $.ajax({
  url:"php/login.php?action=logout",
  data: {},
  success:function(evt)
  {
    $("#LoginArea").replaceWith("<div id='LoginArea'>" + evt + "</div>");
  }
  });
}

function LogIn()
{
  var input_user_name = $("#login_input_username").val();
  var input_user_password = $("#login_input_password").val();
  // console.log(input_user_name,input_user_password);
  $.ajax({
    type: "POST",
    url:"php/login.php",
    data:
    {
      login: "",
      user_name: input_user_name,
      user_password: input_user_password
    },
    success:function(evt){
      $("#LoginArea").replaceWith("<div id='LoginArea'>" + evt + "</div>");
      }
  });
}
function Register()
{
  var input_user_name = $("#login_input_username").val();
  var input_user_email = $("#login_input_email").val();
  var input_user_password_new = $("#login_input_password_new").val();
  var input_user_password_repeat = $("#login_input_password_repeat").val();
  $.ajax({
    type: "POST",
    url:"php/login.php?action=register",
    data:
    {
      register: "",
      user_name: input_user_name,
      user_email: input_user_email,
      user_password_new: input_user_password_new,
      user_password_repeat: input_user_password_repeat
    },
    success:function(evt){
      $("#LoginArea").replaceWith("<div id='LoginArea'>" + evt + "</div>");
    },
  });
}
function ShowLoginPage()
{
  $.ajax(
    {
      url: "php/login.php",
      success: function(evt)
      {
          $("#LoginArea").replaceWith("<div id='LoginArea'>" + evt + "</div>");
      }});

}
function ShowRegPage()
{
  $.ajax({
    url:"php/login.php?action=register",
    data: {},
    success:function(evt){
      $("#LoginArea").replaceWith("<div id='LoginArea'>" + evt + "</div>");
      }
  });
}
function GetUserStatus()
{
  $.ajax({
    url:"php/login.php",
    data:{functionname:"getUserLoginStatus"},
    success:function(evt){/*console.log(evt);*/},
    error:function(evt){console.log(evt);}
  });
}
