<?php
//TODO: make sure that only uppercase tags are stored.
//TODO: add checks to see if tag combination is already in existance.
//TODO: make updateBookmarkTags more efficent.
//TODO: add more checks to this document.
//TODO: comment things.
//TODO: handle failures better.
//TODO: add user based functionality.
//TODO: find repeated code and break into functions.

class bookmarksAccessor
{
  private $db_type = "sqlite";
  private $db_sqlite_path = "../../bookmarks.db";
  private $db_connection = null;
  public $feedback = "";

  public function simpleTest()
  {
    echo "Test Function Called";
  }

  private function createDatabaseConnection()
  {
      try {
          $this->db_connection = new PDO($this->db_type . ':' . $this->db_sqlite_path);
          return true;
      } catch (PDOException $e) {
          $this->feedback = "PDO database connection problem: " . $e->getMessage();
      } catch (Exception $e) {
          $this->feedback = "General problem: " . $e->getMessage();
      }
      return false;
  }

  public function __construct()
  {
      if ($this->performMinimumRequirementsCheck())
      {
        return true;
      }
  }

  private function performMinimumRequirementsCheck()
  {
      if (version_compare(PHP_VERSION, '5.3.7', '<')) {
          echo "Sorry, Simple PHP Login does not run on a PHP version older than 5.3.7 !";
      } elseif (version_compare(PHP_VERSION, '5.5.0', '<')) {
          require_once("libraries/password_compatibility_library.php");
          return true;
      } elseif (version_compare(PHP_VERSION, '5.5.0', '>=')) {
          return true;
      }
      // default return
      return false;
  }
  public function getBookMarksInJson()
  {
    if ($this->createDatabaseConnection()) {
      echo $this->feedback;
      return json_encode($this->getBookMarks());
    }
  }

  public function addCurrentUserBookmark()
  {
    if ($this->createDatabaseConnection())
    {
      $a = session_id();
      if(empty($a)) session_start();
      if($_SESSION["user_is_logged_in"])
      {
        $this->addBookmark($_SESSION["user_id"], "graehu.com", "http://graehu.com", "images/graehu-logo.png", strtotime("now"));
      }else echo "fail";
    }
  }
  public function getBookMarksWithTagsInJson()
  {
    if ($this->createDatabaseConnection()) {
      return $this->getBookMarksWithTags();
      }
  }

  public function getBookmarkTags()
  {
    if ($this->createDatabaseConnection()) {
    $sql = 'SELECT * FROM tags';
    $query = $this->db_connection->prepare($sql);

    if($query->execute())
    {
      $tags = array();
      $i = 0;
      for($entry = $query->fetchObject(); $entry != false; $entry = $query->fetchObject())
      {
        $tags[$i] = $entry;
        $i++;
      }
      $tags = array('tags' => $tags);
      return $tags;
    }
    else
    {
      return null;
    }
  }
  }
  private function getBookmarksByUserId($user_id)
  {
    $sql = 'SELECT * FROM bookmarks WHERE bookmarks.user_id = :user_id';
    $query = $this->db_connection->prepare($sql);

    $query->bindValue(":user_id", $user_id);

    if($query->execute())
    {
      $bookmarks = array();
      $i = 0;
      for($entry = $query->fetchObject(); $entry != false; $entry = $query->fetchObject())
      {
        $bookmarks[$i] = $entry;
        $i++;
      }
      $bookmarks = array('bookmarks' => $bookmarks);
      return $bookmarks;
    }
    else
    {
      return null;
    }
  }
  private function getTagsByBookmarkID($bookmark_id)
  {
    $sql = 'SELECT tag_id FROM bookmark_tags WHERE bookmark_tags.bookmark_id = :bookmark_id';
    $query = $this->db_connection->prepare($sql);

    $query->bindValue(":bookmark_id", $bookmark_id);

    if($query->execute())
    {
      $tag_ids = $query->fetchAll(PDO::FETCH_COLUMN);
      $string = implode(", ",$tag_ids);
      $sql = 'SELECT tag_name FROM tags WHERE tags.tag_id IN ('.$string.')';
      $query = $this->db_connection->prepare($sql);
      if($query->execute())
      {
        $tag_names = $query->fetchAll(PDO::FETCH_COLUMN);
        return  $tag_names;
      }
      else
      {
        return null;
      }
    }
    else
    {
      return null;
    }
  }

  private function getBookMarks()
  {
    $sql = 'SELECT * FROM bookmarks';
    $query = $this->db_connection->prepare($sql);

    if($query->execute())
    {
      $bookmarks = array();
      $i = 0;
      for($entry = $query->fetchObject(); $entry != false; $entry = $query->fetchObject())
      {
        $bookmarks[$i] =  $entry;
        $i++;
      }
      $bookmarks = array('bookmarks' => $bookmarks);
      return $bookmarks;
    }
    else
    {
      return null;
    }

  }
  private function getBookMarksWithTags()
  {
    $sql = 'SELECT bookmarks.bookmark_id, tags.tag_id
            FROM bookmarks
            INNER JOIN bookmark_tags
            ON bookmark_tags.bookmark_id = bookmarks.bookmark_id
            INNER JOIN tags
            ON tags.tag_id = bookmark_tags.tag_id';

    $query = $this->db_connection->prepare($sql);
    if($query->execute())
    {
      $bookmark_tags = array();
      $i = 0;
      for($entry = $query->fetchObject(); $entry != false; $entry = $query->fetchObject())
      {
        $bookmark_tags[$i] = $entry;
        $i++;
      }
      $bookmarks = $this->getBookMarks();
      $taggedBookmarks = array();
      for($ii = 0; $ii < sizeof($bookmarks["bookmarks"]); $ii++)
      {
        $bookmark = new stdClass;
        $bookmark->id = $bookmarks["bookmarks"][$ii]->bookmark_id;
        $bookmark->user_id = $bookmarks["bookmarks"][$ii]->user_id;
        $bookmark->title = $bookmarks["bookmarks"][$ii]->bookmark_title;
        $bookmark->url = $bookmarks["bookmarks"][$ii]->bookmark_url;
        $bookmark->imgurl = $bookmarks["bookmarks"][$ii]->bookmark_imgurl;
        $bookmark->date = $bookmarks["bookmarks"][$ii]->bookmark_date;

        $bookmark->tags = array();

        $bookmarkTags = $this->getTagsByBookmarkID($bookmark->id);

        for($iii = 0; $iii < sizeof($bookmarkTags); $iii++)
        {
          $bookmark->tags[$iii]["text"] = $bookmarkTags[$iii];
          $bookmark->tags[$iii]["custom"] = true;
        }

        $taggedBookmarks[$ii] = $bookmark;
      }
      return $taggedBookmarks;
    }
    else
    {
      return null;
    }
  }

  /////////////////////////////////////////
  ////DATA BASE UPDATE FUNCTIONS///////////
  /////////////////////////////////////////

  public function updateBookmark($update)
  {
    $a = session_id();
    if(empty($a)) session_start();
    if($_SESSION["user_is_logged_in"])
    {
      if($this->createDatabaseConnection())
      {

        $sqlset = "";

        if(array_key_exists("bookmark_title", $update))
          $sqlset = $sqlset."bookmark_title="."'".$update["bookmark_title"]."'";

        if(array_key_exists("user_id", $update))
        {
          if($sqlset != "") $sqlset = $sqlset . ", ";
          $sqlset = $sqlset."user_id="."'".$update["user_id"]."'";
        }

        if(array_key_exists("bookmark_url", $update))
        {
          if($sqlset != "") $sqlset = $sqlset . ", ";
          $sqlset = $sqlset."bookmark_url="."'".$update["bookmark_url"]."'";
        }

        if(array_key_exists("bookmark_imgurl", $update))
        {
          if($sqlset != "") $sqlset = $sqlset . ", ";
          $sqlset = $sqlset."bookmark_imgurl="."'".$update["bookmark_imgurl"]."'";
        }

        if(array_key_exists("bookmark_date", $update))
        {
          if($sqlset != "") $sqlset = $sqlset . ", ";
          $sqlset = $sqlset."bookmark_date="."".$update["bookmark_date"]."";
        }

        $sql = 'UPDATE bookmarks
                SET ' . $sqlset . '
                WHERE bookmark_id= '.$update["bookmark_id"];
        $query = $this->db_connection->prepare($sql);
        if($query->execute())
        {
          echo "success";
        }
        else
        {
          echo "fail";
        }
      }else echo "fail";
    }else echo "fail";
  }
  public function updateBookmarkTags($update)
  {
    $a = session_id();
    if(empty($a)) session_start();
    if($_SESSION["user_is_logged_in"])
    {
      if($this->createDatabaseConnection())
      {
        $tag_names = implode('"),("', $update["tags"]);
        $sql = 'INSERT INTO tags ("tag_name")
                VALUES ("'.$tag_names.'")';

        $query = $this->db_connection->prepare($sql);
        if($query->execute())
        {
          $sql = 'DELETE FROM tags
                  WHERE tag_id NOT IN
                  (SELECT MIN(tag_id) FROM tags GROUP BY tag_name)';

          $query = $this->db_connection->prepare($sql);
          if($query->execute())
          {
            $tag_names = implode('","', $update["tags"]);
            $sql = 'SELECT tag_id FROM tags WHERE tags.tag_name IN ("'.$tag_names.'")';
            // echo $sql;
            $query = $this->db_connection->prepare($sql);

            if($query->execute())
            {
              $bookmark_tag_ids = $query->fetchAll(PDO::FETCH_COLUMN);
              $bookmark_tag_ids = implode("),(".$update["bookmark_id"] .", ", $bookmark_tag_ids);

              $sql = 'DELETE FROM bookmark_tags
                      WHERE bookmark_id= '.$update["bookmark_id"];
              // echo $sql;
              $query = $this->db_connection->prepare($sql);

              if($query->execute())
              {
                $sql = 'INSERT INTO bookmark_tags ("bookmark_id", "tag_id")
                        VALUES ('.$update["bookmark_id"].', '.$bookmark_tag_ids.')';
                // echo $sql;
                $query = $this->db_connection->prepare($sql);
                if($query->execute())
                {
                  echo "success";
                } else echo "fail";
              } else echo "fail";
            }
            else echo "fail";
          }
          else echo "fail";
        }
        else echo "fail";
      }
    }else echo "fail";
  }
    /////////////////////////////////////////
    ////DATA BASE ENTRY FUNCTIONS////////////
    /////////////////////////////////////////

  private function addBookmarkTag($bookmark_id, $tag_id)
  {
    $sql = 'INSERT INTO bookmark_tags ("bookmark_id", "tag_id")
            VALUES (:bookmark_id, :tag_id)';

    $query = $this->db_connection->prepare($sql);

    $query->bindValue(':bookmark_id', $bookmark_id);
    $query->bindValue(':tag_id', $tag_id);

    if($query->execute())
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  private function addTag($tag_name)
  {
    $sql = 'INSERT INTO tags ("tag_name")
            VALUES (:tag_name)';
    $query = $this->db_connection->prepare($sql);

    $query->bindValue(':tag_name', $tag_name);

    if($query->execute())
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  private function addBookmark($user_id, $bookmark_title, $bookmark_url, $bookmark_imgurl, $bookmark_date)
  {
    $sql = 'INSERT INTO bookmarks ("user_id", "bookmark_title", "bookmark_url", "bookmark_imgurl", "bookmark_date")
    VALUES (:user_id, :bookmark_title, :bookmark_url, :bookmark_imgurl, :bookmark_date)';

    $query = $this->db_connection->prepare($sql);
    $query->bindValue(':user_id', $user_id);
    $query->bindValue(':bookmark_title', $bookmark_title);
    $query->bindValue(':bookmark_url', $bookmark_url);
    $query->bindValue(':bookmark_imgurl', $bookmark_imgurl);
    $query->bindValue(':bookmark_date', $bookmark_date);
    if($query->execute())
    {
	echo "success";
      return true;
    }
    else
    {
	echo "fail";
	echo json_encode($query->errorInfo());
      return false;
    }
  }

  /////////////////////////////////////////
  ////DATA BASE REMOVAL FUNCTIONS//////////
  /////////////////////////////////////////

  public function removeBookmark($bookmark_id)
  {
    $a = session_id();
    if(empty($a)) session_start();
    if($_SESSION["user_is_logged_in"])
    {
      if($this->createDatabaseConnection())
      {
      $sql = 'DELETE FROM bookmarks
              WHERE bookmark_id= ' . $bookmark_id;
      $query = $this->db_connection->prepare($sql);
      if($query->execute())
      {
        $sql = 'DELETE FROM bookmark_tags
                WHERE bookmark_id= ' . $bookmark_id;
        $query = $this->db_connection->prepare($sql);
        if($query->execute())
        {
          echo "success";
        }else echo "fail";
      }else echo "fail";
      }
      else echo "fail";
    }
  }

}
$bookmarks = new bookmarksAccessor();
