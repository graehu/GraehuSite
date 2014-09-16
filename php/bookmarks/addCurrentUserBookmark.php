<?php
//header("Content-Type: application/json");

include "bookmarks.php";
//
// $fp = fopen('../../bookmarks.json', 'w');
// fwrite($fp, json_encode($bookmarks->getBookMarksWithTagsInJson()));
// fclose($fp);

//echo json_encode($bookmark_id);
$bookmarks->addCurrentUserBookmark();
