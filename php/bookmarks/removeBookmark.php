<?php
//header("Content-Type: application/json");

include "bookmarks.php";
//
// $fp = fopen('../../bookmarks.json', 'w');
// fwrite($fp, json_encode($bookmarks->getBookMarksWithTagsInJson()));
// fclose($fp);

$bookmark_id = $_POST["bookmark_id"];
//echo json_encode($bookmark_id);
$bookmarks->removeBookmark($bookmark_id);
