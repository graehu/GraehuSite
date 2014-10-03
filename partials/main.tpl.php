<main id="mainContent">
  <script src="js/AJAX/bookmarks.js"/>
  <header class="nav-wrap">
    <nav class="search-wrap">
      <form class="search" role="search">
        <div class="form-group search-group">
          <input
            autofocus="true"
            type="search"
            class="form-control"
            placeholder="graehu.com"
            ng-model="searchText">
            <!--typeahead="tag for tag in getTypeheadSuggestions($viewValue)"-->
        </div>
      </form>
    </nav>
    <a class="settings-toggle"
       ng-click="toggleLogin()"></a>
  </header>


  <div class="grid">

    <div class="tags-wrap">
      <a class="tags-toggle" data-toggle="dropdown"></a>
      <ui class="tags-list">
        <li ng-repeat="tag in tags | orderBy: 'tagText'">
          <a target="_blank" ng-click="selectTag(tag.tagText)">{{tag.tagText}}</a>
        </li>
      </ui>
    </div>
    <a class='add-bookmark' ng-click='addBookmark()'></a>
    <ul class="sort">
      <li ng-cloak>
        <p>Sort:</p>
        <a ng-repeat="order in orders" ng-class="{ 'sort-active': order == currentOrder }" ng-click="changeOrder(order)">{{order.title}}</a>
      </li>
    </ul>



    <ul class="list-bookmarks">
      <li ng-repeat="bookmark in (filteredBookmarks | orderBy:currentOrder.value | limitTo:totalDisplayed)" ng-cloak>

        <div class="card" ng-class="{'card-primary': ($index == selectedIndex), 'card-small': !showThumbnails}" ng-click="selectBookmark($index)">
          <!-- If you're logged in you can edit bookmarks -->
          <div class="edit-toggle" ng-click="editBookmark(bookmark)"></div>
          <a class="bookmark-link" href="{{bookmark.url}}"></a>

          <div class="thumbnail-loading" style="background: white url(images/loader.gif) no-repeat center center;" title="{{bookmark.url}}"></div>

          <div class="details">

            <img class="favicon" src="http://g.etfv.co/{{bookmark.url}}?defaulticon=lightpng" alt="{{bookmark.url}}" my-update-background crossOrigin="anonymous" />
            <h2>{{bookmark.title}}</h2>

            <div class="tag-links">
              <span ng-repeat="tag in bookmark.tags">
                <a ng-class="{'badge-custom': tag.custom}" ng-click="selectTag(tag.text)">{{tag.text}}</a>
              </span>
            </div><!-- tags -->

          </div><!-- details -->
        </div><!-- card -->
      </li><!-- bookmarks in bookmarks -->
    </ul><!-- list-bookmarks -->
  </div><!-- grid -->
</main>
