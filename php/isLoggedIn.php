<?php
//header("Content-Type: application/json");
ob_start();
include "login.php";

$isLoggedIn = $login->getUserLoginStatus();
ob_end_clean();
//
// $fp = fopen('../../bookmarks.json', 'w');
// fwrite($fp, json_encode($bookmarks->getBookMarksWithTagsInJson()));
// fclose($fp);

//echo json_encode($bookmark_id);
echo $isLoggedIn;
