<?php
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
  public function addDummyValues()
  {
    if ($this->createDatabaseConnection())
    {
      $this->addTag("Erin");
      $this->addTag("Graehu");
      $this->addBookmark(1, "youtube", "www.youtube.com", "21.01.2112");
      $this->addBookmark(1, "pinterest", "www.pinterest.com", "21.02.2112");
      $this->addBookmark(2, "google", "www.google.com", "21.03.2112");
      $this->addBookmarkTag(1, 1);
      $this->addBookmarkTag(2, 1);
      $this->addBookmarkTag(2, 2);
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
      //echo json_encode($bookmarks);
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

  ////DATA BASE ENTRY FUNCTIONS///////////

  private function addBookmarkTag($bookmark_id, $tag_id)
  {
    //TODO: add checks to see if tag_bookmark combination is already in existance
    // TODO: learn to use better ids
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
    //TODO: add checks to see if tag is already in existance
    // TODO: learn to use better ids
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
  private function addBookmark($user_id, $bookmark_title, $bookmark_url, $bookmark_date)
  {
    //TODO: add checks to see if bookmark is already in existance
    // TODO: learn to use better ids
    $sql = 'INSERT INTO bookmarks ("user_id", "bookmark_title", "bookmark_url", "bookmark_date")
    VALUES (:user_id, :bookmark_title, :bookmark_url, :bookmark_date)';

    $query = $this->db_connection->prepare($sql);
    $query->bindValue(':user_id', $user_id);
    $query->bindValue(':bookmark_title', $bookmark_title);
    $query->bindValue(':bookmark_url', $bookmark_url);
    $query->bindValue(':bookmark_date', $bookmark_date);
    if($query->execute())
    {
      return true;
    }
    else
    {
      return false;
    }
  }

}
$bookmarks = new bookmarksAccessor();
