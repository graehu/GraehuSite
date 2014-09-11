<?php
//Do bookmarky things!
class bookmarksAccessor
{
  private $db_type = "sqlite";
  private $db_sqlite_path = "../bookmarks.db";
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
      $this->getBookMarks();
    }
  }

  public function getBookMarksWithTagsInJson()
  {
    if ($this->createDatabaseConnection()) {
      $this->getBookMarks();
    }
  }

  private function getBookMarks()
  {
    $sql = 'SELECT * FROM bookmarks';
    $query = $this->db_connection->prepare($sql);
    if($query->execute())
    {
      for($entry = $query->fetchObject(); $entry != false; $entry = $query->fetchObject())
      {
        echo json_encode($entry);
      }
    }
    else
    {
      echo "fail";
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
      for($entry = $query->fetchObject(); $entry != false; $entry = $query->fetchObject())
      {
        echo json_encode($entry);
      }
    }
    else
    {
      echo "fail!";
    }
  }
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
      echo "success!";
    }
    else
    {
      echo "fail!";
    }
  }
  private function addTag($tag_id, $tag)
  {
    //TODO: add checks to see if tag is already in existance
    // TODO: learn to use better ids
    $sql = 'INSERT INTO tags ("tag_id", "tag")
            VALUES (:tag_id, :tag)';
    $query = $this->db_connection->prepare($sql);

    $query->bindValue(':tag_id', $tag_id);
    $query->bindValue(':tag', $tag);

    if($query->execute())
    {
      echo "success!";
    }
    else
    {
      echo "fail!";
    }
  }
  private function addBookmark($bookmark_id, $user_id, $bookmark_title, $bookmark_url, $bookmark_date)
  {
    //TODO: add checks to see if bookmark is already in existance
    // TODO: learn to use better ids
    $sql = 'INSERT INTO bookmarks("bookmark_id", "user_id", "bookmark_title", "bookmark_url", "bookmark_date")
    VALUES(:bookmark_id, :user_id, :bookmark_title, :bookmark_url, :bookmark_date)';

    $query = $this->db_connection->prepare($sql);
    $query->bindValue(':bookmark_id', $bookmark_id);
    $query->bindValue(':user_id', $user_id);
    $query->bindValue(':bookmark_title', $bookmark_title);
    $query->bindValue(':bookmark_url', $bookmark_url);
    $query->bindValue(':bookmark_date', $bookmark_date);
    if($query->execute())
    {
      echo "success!";
    }
    else
    {
      echo "fail!";
    }
  }

}
$bookmarks = new bookmarksAccessor();
