define(
[
  'underscore',
  'jQuery'
],
function(_, $) { 'use strict';


/*
* Application controller.
*/
var MainController = function($scope, $routeParams, $filter, $modal, $location, $timeout,bookmarksStorage, appSettings, booleanSearchEngine, bookmarkServer, loginServer) {

  // Constant: default value of how many items we want to display on main page.
  var defaultTotalDisplayed = 20;

  $scope.searchText = ''; // Search text
  $scope.bookmarks = []; // All bookmarks
  $scope.filteredBookmarks = [];
  $scope.tags = []; // All tags
  $scope.orders = [ // Different sorting orders
                    {title:'Date', value: '-date'},
                    {title:'Title', value: 'title'},
                    {title:'URL', value: 'url'}
                  ];
  $scope.currentOrder = $scope.orders[0]; // date is default sorting order

  // Maximum number of items currently displayed
  $scope.totalDisplayed = defaultTotalDisplayed;

  $scope.selectedIndex = 0;

  $scope.hideTopLevelFolders = false;
  $scope.showThumbnails = true;
  $scope.loggedIn = false;



  $scope.addBookmark = function()
  {
    bookmarkServer.AddBookmark(loadBookmarks);
  }

  var updateLoginStatus = function()
  {
    loginServer.isLoggedIn(function(loggedIn)
    {
      if(loggedIn === '1')
        $scope.loggedIn =  true;
      else
        $scope.loggedIn =  false;
    });
  };
  updateLoginStatus();
  var scrollToSelected = function()
  {
    if(!$scope.$$phase) {
        $scope.$apply();
    }
    var cards = getAllCards();
    var selectedElement = cards.get($scope.selectedIndex);
    if (selectedElement) {
      var rect = selectedElement.getBoundingClientRect(); // If element is not visible - scroll to it
      if (!(rect.top >= 0 && rect.left >= 0 && rect.bottom <= $(window).height() && rect.right <= $(window).width())) {
        $("body").stop().animate({
          scrollTop: ($(cards.get($scope.selectedIndex)).offset().top - $(cards.get(0)).offset().top)
        }, 500);
      }
    }
  }

  // Auto add showing bookmarks when user scroll to page down
  var loadMorePlaceholder = $('#loadMorePlaceholder').get(0);
  $(window).scroll(function () {
    if ($scope.filteredBookmarks.length > $scope.totalDisplayed) {
      if (loadMorePlaceholder.getBoundingClientRect().top <= window.innerHeight) {
        $scope.totalDisplayed += defaultTotalDisplayed;
        $scope.$apply();
      }
    }
  });

  $(window).resize(function(){
    countItemsPerRow();
  });

  var getAllCards = function() {
    return $('.list-bookmarks div.card');
  };

  var countItemsPerRow = function() {
    var bookmarksList = angular.element('.list-bookmarks'),
    boxSize = bookmarksList.find('li:first-child').width(),
    bookmarksListW = bookmarksList.width(),
    perRow = Math.floor( bookmarksListW / boxSize);

    $scope.itemsPerRow = perRow;
  };

  var isElementInViewport = function(el) {
    var rect = el.getBoundingClientRect();
    return rect.top >= 0 && rect.left >= 0 && rect.bottom <= $(window).height() && rect.right <= $(window).width();
  };

  // Key down events handlers
  $('#mainContent').keydown(function(e)
  {
    if (e.isDefaultPrevented()) {
      return;
    }});

  var loadBookmarks = function() {

    bookmarkServer.GetTaggedBookmarks(function(bookmarks)
    {
      bookmarks.forEach(function (bookmark)
      {
        if(bookmark.url.indexOf("partial:") !== -1 ||
           bookmark.url.indexOf("youtube:") !== -1 ||
           bookmark.url.indexOf("unity:") !== -1 ||
           bookmark.url.indexOf("markdown:") !== -1)
          bookmark.iconurl = "graehu.com";
        else
          bookmark.iconurl = bookmark.url;
      });

      $scope.hideTopLevelFolders = appSettings.hideTopLevelFolders = false;
      $scope.showThumbnails = appSettings.showThumbnails = true;
      $scope.bookmarks = bookmarks;
      $scope.filteredBookmarks = bookmarks;

      $scope.tags = _.chain(bookmarks)
                      .map(function (item) { return item.tags; })
                      .flatten()
                      .groupBy(function(t){ return t.text; })
                      .map(function(tagsArray, text) {
                          return {tagText: text,  numberOfTags: tagsArray.length };
                      })
                      .value();
      //only happens once.
      if($routeParams.searchText)
      {
        $scope.searchText = $routeParams.searchText;
        $scope.filteredBookmarks =
          _.filter(
            $scope.bookmarks,
            function(bookmark){
              return booleanSearchEngine.filterBookmark(bookmark, $routeParams.searchText);
            }
          );
          $routeParams.searchText = null;
      }
      // ARG: improve in future
      // applyTagsAsString(bookmarks);
      if(!$scope.$$phase) {
          $scope.$apply();
      }

      countItemsPerRow();

      if($routeParams.bookmark)
      {
        $filter('orderBy')($scope.filteredBookmarks, $scope.currentOrder.value).forEach(
        function(bookmark, i)
        {
          if(bookmark.title === $routeParams.bookmark)
          {
            $scope.clickBookmark(i);
          }
        });
      }
    }.bind(this));
  }.bind(this);
  loadBookmarks();

  var applyTagsAsString = function(bookmarks){

      var separator = '|';
      _.each(bookmarks, function(item){

          item.tagsAsString = _.chain(item.tags)
                                .map(function(tag) { return tag.text; })
                                .join(separator)
                                .value();
          item.tagsAsString += separator;
      });
  };

  // Set maximum total displayed items to default and scroll to top of the page
  var resetView = function() {
    $scope.totalDisplayed = defaultTotalDisplayed;
    $scope.selectedIndex = 0;
    setTimeout(function() {
      window.scroll(0, 0);
    }, 10);
  };

  var updateTagsInfo = function(updatedTags, originalTags){
    var newTags = _.difference(updatedTags, originalTags);
    var deletedTags = _.difference(originalTags, updatedTags);

    _.each(newTags, function(item){
        var existingTag = _.find($scope.tags, function(t){return t.tagText == item; });
        if(_.isUndefined(existingTag)){
            $scope.tags.push({tagText: item, numberOfTags: 1, custom: true});
        }
        else{
            existingTag.numberOfTags++;
        }
    });

    _.each(deletedTags, function(item){
        var existingTag = _.find($scope.tags, function(t){return t.tagText == item; });
        if(!_.isUndefined(existingTag)){
          if(existingTag.numberOfTags > 1) {
              existingTag.numberOfTags--;
          }
          else{
              $scope.tags.splice(_.indexOf($scope.tags, existingTag), 1);
          }
        }
    });
  };

  // When user change search string we scroll to top of the page and set total displayed items to default
  $scope.$watch('searchText', function() {
    $scope.filteredBookmarks =
      _.filter(
        $scope.bookmarks,
        function(bookmark){
          return booleanSearchEngine.filterBookmark(bookmark, $scope.searchText);
        }
      );
    resetView();
  });

  // On tag click we set search text
  $scope.selectTag = function(tag) {
    _gaq.push(['_trackEvent', 'Navigation', 'selectTag']);
    $scope.searchText = 'tag:' + tag;
  };

  // Change sorting order
  $scope.changeOrder = function(order) {
    _gaq.push(['_trackEvent', 'Navigation', 'changeOrder',  'Change order to ' + order]);
    $scope.currentOrder = order;
    resetView();
  };

  // Show modal dialog for adding tags
  $scope.editBookmark = function(bookmark) {
    _gaq.push(['_trackEvent', 'Navigation', 'editBookmark']);

    var originalBookmark = _.clone(bookmark);

    $(".nav-wrap, .grid").addClass("scale-blur");

    var modalInstance = $modal.open({
      templateUrl: 'partials/editBookmark.tpl.html',
      controller: 'editBookmarkController',
      resolve: {
        bookmark: function() {
          return bookmark;
        }
      },
      keyboard: true,
      backdrop: false
    });

    modalInstance.result.then(function (updatedBookmark) {
      var updatedTags = [];
      if(!_.isNull(updatedBookmark)){
        updatedTags = _.map(updatedBookmark.tags, function(item) { return item.text; });
      }
      var originalTags = _.map(originalBookmark.tags, function(item) { return item.text; });
      updateTagsInfo(updatedTags, originalTags);

      if (!updatedBookmark) {
        // Bookmark was deleted
        $scope.bookmarks.splice(_.indexOf($scope.bookmarks, bookmark), 1);
      }
       $(".nav-wrap, .grid").removeClass("scale-blur");
    }, function() {
       $(".nav-wrap, .grid").removeClass("scale-blur");
    });

    return false;
  };

  $scope.selectBookmark = function(index) {
    $scope.selectedIndex = index;
  };
  $scope.clickBookmark = function(index) {
    $scope.selectedIndex = index;
    scrollToSelected();

    var result = $scope.filteredBookmarks;
    if (result.length > $scope.selectedIndex) {
      var bookmarkUrl = $filter('orderBy')(result, $scope.currentOrder.value)[$scope.selectedIndex].url;
      var url = "";
      _gaq.push(['_trackEvent', 'BookmarkClicked', 'title: ' +
      $filter('orderBy')(result, $scope.currentOrder.value)[$scope.selectedIndex].title]);

      if(bookmarkUrl.substring(0, 8) == "partial:")
        url =  "partials/"+bookmarkUrl.slice(8);
      else if(bookmarkUrl.substring(0, 9) == "markdown:")
        url = "partials/markdown.tpl.php?src="+bookmarkUrl.slice(9);
      else if(bookmarkUrl.substring(0, 6) == "unity:")
        url = "partials/unity.tpl.php?src="+bookmarkUrl.slice(6);
      else if(bookmarkUrl.substring(0, 8) == "youtube:")
        url = "partials/youtube.tpl.php?src="+bookmarkUrl.slice(8);



      if(url)
      {
        var modalInstance = $modal.open({
          templateUrl: url,
          controller: 'partialController',
          keyboard: true,
          backdrop: false
        });
        modalInstance.result.then(function ()
        {
          //fix stylings if I add any
        }, function()
        {
          //fix stylings if I add any
        });
      }
      else window.open(bookmarkUrl);
    }
  };

  $scope.setHideTopLevelFolders = function() {
    _gaq.push(['_trackEvent', 'ChangeSettings', 'HideTopLevelFolders changed to ' + !$scope.hideTopLevelFolders]);
    bookmarksStorage.setHideTopLevelFolders(!$scope.hideTopLevelFolders, loadBookmarks);
  };

  $scope.setShowThumbnails = function() {
    _gaq.push(['_trackEvent', 'ChangeSettings', 'ShowThumbnails changed to ' + !$scope.showThumbnails]);
    bookmarksStorage.setShowThumbnails(!$scope.showThumbnails, loadBookmarks);
  };

  $scope.getTypeheadSuggestions = function($viewValue) {
    var pattern = 'NONE';
    var searchText = $viewValue;
    var definedSearch = $viewValue;

    var expressionTree = booleanSearchEngine.generateExpressionTree($viewValue);
    if (expressionTree && expressionTree.length > 0) {
      var node = _.last(expressionTree);
      if (node) {
        var lastLiteral = _.last(node.literals);
        pattern = node.pattern;

        searchText = (lastLiteral && lastLiteral.expression === 'NONE' ? lastLiteral.text : '');

        definedSearch = $viewValue.replace(/\s+$/, '');
        definedSearch = definedSearch.substr(0, $viewValue.length - searchText.length);
        if (definedSearch.length > 0) {
          definedSearch += ' ';
        }
      }
    }

    if (pattern === 'NONE') {
      pattern = 'TITLE:';
    }

    var chain;

    if (pattern === 'TITLE:') {
      chain = _.chain(this.bookmarks)
        .map(function(b) {
          return b.title;
        });
    } else if (pattern === 'TAG:') {
       chain = _.chain(this.tags)
        .map(function(t) {
          return t.tagText;
        });
    } else if (pattern === 'URL:') {
      chain = _.chain(this.bookmarks)
        .map(function(b) {
          return b.url;
        });
    }

    if (!chain) {
      return [];
    }

    return chain
      .filter(function(t) {
        return t.toUpperCase().indexOf(searchText.toUpperCase()) >= 0;
      })
      .sortBy(function(t) {
        return t;
      })
      .first(25)
      .map(function(t) {
        return definedSearch + t;
      }).value();
  };

  $scope.toggleLogin = function() {
    $(".nav-wrap, .grid").addClass("scale-blur");
    $( ".settings" ).toggleClass( "no-scroll" );
    var modalInstance = $modal.open({
      templateUrl: 'partials/login.tpl.php',
      controller: 'loginController',
      keyboard: true,
      backdrop: false
    });
    modalInstance.result.then(function ()
    {
      updateLoginStatus();
      $(".nav-wrap, .grid").removeClass("scale-blur");
    }, function()
    {
      updateLoginStatus();
      $(".nav-wrap, .grid").removeClass("scale-blur");
    });
  };
};

return [
  '$scope',
  '$routeParams',
  '$filter',
  '$modal',
  '$location',
  '$timeout',
  'bookmarksStorage',
  'appSettings',
  'booleanSearchEngine',
  'bookmarkServer',
  'loginServer',
  MainController
];

});
