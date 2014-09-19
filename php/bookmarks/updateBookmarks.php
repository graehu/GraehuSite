<?php
//header("Content-Type: application/json");

include "bookmarks.php";
//
// $fp = fopen('../../bookmarks.json', 'w');
// fwrite($fp, json_encode($bookmarks->getBookMarksWithTagsInJson()));
// fclose($fp);
//echo "</br> dicks </br>";
$bookmarkUpdate = $_POST;

$bookmarks->updateBookmark($bookmarkUpdate);
