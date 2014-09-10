<main id="mainContent">
  <header class="nav-wrap">
    <nav class="search-wrap">
      <form class="search" role="search">
        <div class="form-group search-group">
          <input
            autofocus="true"
            type="search"
            class="form-control"
            ng-model="searchText">
            <!--typeahead="tag for tag in getTypeheadSuggestions($viewValue)"-->
        </div>
      </form>
    </nav>
    <a class="settings-toggle"
       ng-click="toggleSettings()"></a>
  </header>

  <div class="settings">
    <a class="close"
       ng-click="toggleSettings()"></a>
    <div class="settings-wrap">
      <ul class="col-1">
        <!--<li><a class="docs" href="https://github.com/deweyapp/dewey-website/blob/master/README.md" target="_blank">How To</a></li>
        <li><a class="feedback" href="https://github.com/deweyapp/dewey/issues" target="_blank">Feedback</a></li>
        <li><a class="review" href="https://chrome.google.com/webstore/detail/dewey-bookmarks/aahpfefkmihhdabllidnlipghcjgpkdm/reviews" target="_blank">Review</a></li>
        <li><a class="donate" href="https://github.com/deweyapp/dewey-website/blob/master/README.md#donate" target="_blank">Donate</a></li>
        <li><a class="email" href="mailto:support@deweyapp.io?Subject=Hello, Dewey.">Email Us</a></li>
        <li><a class="website" href="http://deweyapp.io" target="_blank">Website</a></li>-->
        <li><div id="LoginArea"><?php include "../php/login.php";?></div></li>
      </ul> <!-- .col-1 -->

      <ul class="col-2">

        <li>
          <form>
            <div><h2>Credits:</h2></div>
            <div class="credits"><p>This is a modded version of <a class="important" href="http://deweyapp.io" target="_blank">Dewey</a> an awesome bookmark extension for chrome.</p></div>

            <!--<div class="checkbox" title="Don&rsquo;t show thumbnails for bookmarks" ng-click="setShowThumbnails()">
              <input id="hide-thumbs" class="css-checkbox" type="checkbox" ng-checked="showThumbnails" />
              <label for="hide-thumbs" class="css-label">Show Screenshots</label>
            </div>-->

            <!--<div class="checkbox" title="Don&rsquo;t show top-level folders as tags (Mobile Bookmarks, Other Bookmarks, ...)" ng-click="setHideTopLevelFolders()">
              <input id="hide-folders" class="css-checkbox" type="checkbox" ng-checked="hideTopLevelFolders" />
              <label for="hide-folders" class="css-label">Hide Top-level Folders as Tags</label>
            </div>-->
            </br> 
            <div class="credits"><p>Screenshots by <a class="important" href="http://snapito.io" target="_blank">Snapito</a></p></div>
          </form>
        </li>
      </ul> <!-- .col-2 -->
    </div> <!-- .settings-wrap -->

    <div class="credits makers">
      Built by:
      <a class="important" href="https://twitter.com/outcoldman" target="_blank">Denis</a>,
      <a class="important" href="https://twitter.com/jmwlsn" target="_blank">Jamie</a>
      <a class="important" href="https://twitter.com/artemgrygor" target="_blank">Artem</a>
      and <a class="important" href="https://twitter.com/graehu" target="_blank">Me</a>
    </div>

  </div> <!-- settings -->

  <div class="grid">

    <div class="tags-wrap">
      <a class="tags-toggle" data-toggle="dropdown"></a>
      <ui class="tags-list">
        <li ng-repeat="tag in tags | orderBy: 'tagText'">
          <a target="_blank" ng-click="selectTag(tag.tagText)">{{tag.tagText}}</a>
        </li>
      </ui>
    </div>

    <ul class="sort">
      <li ng-cloak>
        <p>Sort:</p>
        <a ng-repeat="order in orders" ng-class="{ 'sort-active': order == currentOrder }" ng-click="changeOrder(order)">{{order.title}}</a>
      </li>
    </ul>

    <ul class="list-bookmarks">
      <li ng-repeat="bookmark in (filteredBookmarks | orderBy:currentOrder.value | limitTo:totalDisplayed)" ng-cloak>

        <div class="card" ng-class="{'card-primary': ($index == selectedIndex), 'card-small': !showThumbnails}" ng-click="selectBookmark($index)">

          <div class="edit-toggle" ng-click="editBookmark(bookmark)"></div>
          <a class="bookmark-link" href="{{bookmark.url}}"></a>

          <div class="thumbnail-loading" style="background: white url(images/loader.gif) no-repeat center center;" title="{{bookmark.url}}"></div>

          <div class="details">

            <img class="favicon" src="http://g.etfv.co/{{bookmark.url}}?defaulticon=lightpng" alt="{{bookmark.url}}" my-update-background crossOrigin="anonymous" />
            <h2>{{bookmark.title}}</h2>

            <div class="tag-links">
              <span ng-repeat="tag in bookmark.tag">
                <a ng-class="{'badge-custom': tag.custom}" ng-click="selectTag(tag.text)">{{tag.text}}</a>
              </span>
            </div><!-- tags -->

          </div><!-- details -->
        </div><!-- card -->
      </li><!-- bookmarks in bookmarks -->
    </ul><!-- list-bookmarks -->
  </div><!-- grid -->

</main>