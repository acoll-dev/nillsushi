(function() {
  'use strict';
  angular.module('mainApp').directive('breadcrumb', ["$rootScope", "$templateCache", "$window", "$timeout", function($rootScope, $templateCache, $window, $timeout) {
    return {
      restrict: 'E',
      template: function() {
        return $templateCache.get('griffo/breadcrumb.html');
      },
      scope: {},
      replace: true,
      link: function($scope, $element, $attrs) {
        var setBread;
        setBread = function() {
          if ($rootScope.GRIFFO.filter.page && $rootScope.GRIFFO.config && $rootScope.GRIFFO.filter.page.fileview !== 'home') {
            $scope.breadcrumbs = [];
            $scope.breadcrumbs.push({
              url: $rootScope.GRIFFO.filter.page.url,
              label: $rootScope.GRIFFO.filter.page.title,
              active: !$rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview] && !$rootScope.GRIFFO.filter.urlcategory && !$rootScope.GRIFFO.filter.category
            });
            if ($rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview]) {
              $scope.breadcrumbs.push({
                url: $rootScope.GRIFFO.filter.page.url + $rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview].url,
                label: $rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview].name || $rootScope.GRIFFO.filter[$rootScope.GRIFFO.filter.page.fileview].title,
                active: true
              });
            }
            if ($rootScope.GRIFFO.filter.urlcategory && $rootScope.GRIFFO.filter.category) {
              $scope.breadcrumbs.push({
                label: $rootScope.GRIFFO.filter.urlcategory,
                active: true
              });
              return $scope.breadcrumbs.push({
                url: $rootScope.GRIFFO.filter.page.url + $rootScope.GRIFFO.filter.urlcategory + '/' + $rootScope.GRIFFO.filter.category.url,
                label: $rootScope.GRIFFO.filter.category.name,
                active: true
              });
            }
          }
        };
        $rootScope.$watch('GRIFFO.filter.page', function() {
          return setBread();
        });
        return $rootScope.$watch('GRIFFO.config', function() {
          return setBread();
        });
      }
    };
  }]).run(["$templateCache", function($templateCache) {
    return $templateCache.put('griffo/breadcrumb.html', '<section class="breadcrumb-wrapper" ng-show="breadcrumbs.length > 0">\n    <div class="container">\n        <ol class="breadcrumb">\n            <li ng-repeat="bread in breadcrumbs" ng-class="{\'active\': bread.active}"><a ng-href="{{bread.url}}" ng-if="!bread.active && bread.url">{{bread.label}}</a><span ng-if="bread.active || !bread.url">{{bread.label}}</span></li>\n        </ol>\n    </div>\n</section>');
  }]);

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDIvY29mZmVlL2RpcmVjdGl2ZXMvYnJlYWRjcnVtYkRydC5jb2ZmZWUiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7RUFBQTtFQUNBLE9BQU8sQ0FBQyxNQUFSLENBQWUsU0FBZixDQUNJLENBQUMsU0FETCxDQUNlLFlBRGYsRUFDNkIsU0FBQyxVQUFELEVBQWEsY0FBYixFQUE2QixPQUE3QixFQUFzQyxRQUF0QztXQUNyQjtNQUNJLFFBQUEsRUFBVSxHQURkO01BRUksUUFBQSxFQUFVLFNBQUE7ZUFDTixjQUFjLENBQUMsR0FBZixDQUFtQix3QkFBbkI7TUFETSxDQUZkO01BSUksS0FBQSxFQUFPLEVBSlg7TUFLSSxPQUFBLEVBQVMsSUFMYjtNQU1JLElBQUEsRUFBTSxTQUFDLE1BQUQsRUFBUyxRQUFULEVBQW1CLE1BQW5CO0FBRUYsWUFBQTtRQUFBLFFBQUEsR0FBVyxTQUFBO1VBQ1AsSUFBRyxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxJQUF6QixJQUFrQyxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQXBELElBQStELFVBQVUsQ0FBQyxNQUFNLENBQUMsTUFBTSxDQUFDLElBQUksQ0FBQyxRQUE5QixLQUEwQyxNQUE1RztZQUNJLE1BQU0sQ0FBQyxXQUFQLEdBQXFCO1lBTXJCLE1BQU0sQ0FBQyxXQUFXLENBQUMsSUFBbkIsQ0FDSTtjQUFBLEdBQUEsRUFBSyxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxJQUFJLENBQUMsR0FBbkM7Y0FDQSxLQUFBLEVBQU8sVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLEtBRHJDO2NBRUEsTUFBQSxFQUFRLENBQUMsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFPLENBQUEsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLFFBQTlCLENBQTFCLElBQXNFLENBQUMsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsV0FBaEcsSUFBZ0gsQ0FBQyxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxRQUZsSjthQURKO1lBSUEsSUFBRyxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU8sQ0FBQSxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxJQUFJLENBQUMsUUFBOUIsQ0FBNUI7Y0FDSSxNQUFNLENBQUMsV0FBVyxDQUFDLElBQW5CLENBQ0k7Z0JBQUEsR0FBQSxFQUFLLFVBQVUsQ0FBQyxNQUFNLENBQUMsTUFBTSxDQUFDLElBQUksQ0FBQyxHQUE5QixHQUFvQyxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU8sQ0FBQSxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxJQUFJLENBQUMsUUFBOUIsQ0FBdUMsQ0FBQyxHQUExRztnQkFDQSxLQUFBLEVBQU8sVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFPLENBQUEsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLFFBQTlCLENBQXVDLENBQUMsSUFBakUsSUFBeUUsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFPLENBQUEsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLFFBQTlCLENBQXVDLENBQUMsS0FEako7Z0JBRUEsTUFBQSxFQUFRLElBRlI7ZUFESixFQURKOztZQUtBLElBQUcsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsV0FBekIsSUFBeUMsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsUUFBckU7Y0FDSSxNQUFNLENBQUMsV0FBVyxDQUFDLElBQW5CLENBQ0k7Z0JBQUEsS0FBQSxFQUFPLFVBQVUsQ0FBQyxNQUFNLENBQUMsTUFBTSxDQUFDLFdBQWhDO2dCQUNBLE1BQUEsRUFBUSxJQURSO2VBREo7cUJBR0EsTUFBTSxDQUFDLFdBQVcsQ0FBQyxJQUFuQixDQUNJO2dCQUFBLEdBQUEsRUFBSyxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxJQUFJLENBQUMsR0FBOUIsR0FBb0MsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsV0FBN0QsR0FBMkUsR0FBM0UsR0FBaUYsVUFBVSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsUUFBUSxDQUFDLEdBQXhIO2dCQUNBLEtBQUEsRUFBTyxVQUFVLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxRQUFRLENBQUMsSUFEekM7Z0JBRUEsTUFBQSxFQUFRLElBRlI7ZUFESixFQUpKO2FBaEJKOztRQURPO1FBMEJYLFVBQVUsQ0FBQyxNQUFYLENBQWtCLG9CQUFsQixFQUF3QyxTQUFBO2lCQUNwQyxRQUFBLENBQUE7UUFEb0MsQ0FBeEM7ZUFHQSxVQUFVLENBQUMsTUFBWCxDQUFrQixlQUFsQixFQUFtQyxTQUFBO2lCQUMvQixRQUFBLENBQUE7UUFEK0IsQ0FBbkM7TUEvQkUsQ0FOVjs7RUFEcUIsQ0FEN0IsQ0EyQ0ksQ0FBQyxHQTNDTCxDQTJDUyxTQUFDLGNBQUQ7V0FDRyxjQUFjLENBQUMsR0FBZixDQUFtQix3QkFBbkIsRUFBNkMsMFpBQTdDO0VBREgsQ0EzQ1Q7QUFEQSIsImZpbGUiOiJjbGllbnQvbGF5ZXJzL3dlYnNpdGUvdGVtcGxhdGVzL21vZGVsbzAyL2pzL2RpcmVjdGl2ZXMvYnJlYWRjcnVtYkRydC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUgJ21haW5BcHAnXG4gICAgLmRpcmVjdGl2ZSAnYnJlYWRjcnVtYicsICgkcm9vdFNjb3BlLCAkdGVtcGxhdGVDYWNoZSwgJHdpbmRvdywgJHRpbWVvdXQpIC0+XG4gICAgICAgIHtcbiAgICAgICAgICAgIHJlc3RyaWN0OiAnRSdcbiAgICAgICAgICAgIHRlbXBsYXRlOiAtPlxuICAgICAgICAgICAgICAgICR0ZW1wbGF0ZUNhY2hlLmdldCAnZ3JpZmZvL2JyZWFkY3J1bWIuaHRtbCdcbiAgICAgICAgICAgIHNjb3BlOiB7fVxuICAgICAgICAgICAgcmVwbGFjZTogdHJ1ZVxuICAgICAgICAgICAgbGluazogKCRzY29wZSwgJGVsZW1lbnQsICRhdHRycykgLT5cblxuICAgICAgICAgICAgICAgIHNldEJyZWFkID0gLT5cbiAgICAgICAgICAgICAgICAgICAgaWYgJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnBhZ2UgYW5kICRyb290U2NvcGUuR1JJRkZPLmNvbmZpZyBhbmQgJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnBhZ2UuZmlsZXZpZXcgIT0gJ2hvbWUnXG4gICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuYnJlYWRjcnVtYnMgPSBbXVxuICAgICAgICAgICAgICAgICAgICAgICAgIyAkc2NvcGUuYnJlYWRjcnVtYnMgPSBbe1xuICAgICAgICAgICAgICAgICAgICAgICAgIyAgICAgdXJsOiAnLi8nLFxuICAgICAgICAgICAgICAgICAgICAgICAgIyAgICAgbGFiZWw6ICdIb21lJyxcbiAgICAgICAgICAgICAgICAgICAgICAgICMgICAgIGFjdGl2ZTogZmFsc2VcbiAgICAgICAgICAgICAgICAgICAgICAgICMgfV07XG4gICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuYnJlYWRjcnVtYnMucHVzaFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHVybDogJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnBhZ2UudXJsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICRyb290U2NvcGUuR1JJRkZPLmZpbHRlci5wYWdlLnRpdGxlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYWN0aXZlOiAhJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyWyRyb290U2NvcGUuR1JJRkZPLmZpbHRlci5wYWdlLmZpbGV2aWV3XSBhbmQgISRyb290U2NvcGUuR1JJRkZPLmZpbHRlci51cmxjYXRlZ29yeSBhbmQgISRyb290U2NvcGUuR1JJRkZPLmZpbHRlci5jYXRlZ29yeVxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyWyRyb290U2NvcGUuR1JJRkZPLmZpbHRlci5wYWdlLmZpbGV2aWV3XVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICRzY29wZS5icmVhZGNydW1icy5wdXNoXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHVybDogJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnBhZ2UudXJsICsgJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyWyRyb290U2NvcGUuR1JJRkZPLmZpbHRlci5wYWdlLmZpbGV2aWV3XS51cmxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICRyb290U2NvcGUuR1JJRkZPLmZpbHRlclskcm9vdFNjb3BlLkdSSUZGTy5maWx0ZXIucGFnZS5maWxldmlld10ubmFtZSBvciAkcm9vdFNjb3BlLkdSSUZGTy5maWx0ZXJbJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnBhZ2UuZmlsZXZpZXddLnRpdGxlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFjdGl2ZTogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnVybGNhdGVnb3J5IGFuZCAkcm9vdFNjb3BlLkdSSUZGTy5maWx0ZXIuY2F0ZWdvcnlcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuYnJlYWRjcnVtYnMucHVzaFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBsYWJlbDogJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnVybGNhdGVnb3J5XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFjdGl2ZTogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICRzY29wZS5icmVhZGNydW1icy5wdXNoXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHVybDogJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnBhZ2UudXJsICsgJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLnVybGNhdGVnb3J5ICsgJy8nICsgJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLmNhdGVnb3J5LnVybFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBsYWJlbDogJHJvb3RTY29wZS5HUklGRk8uZmlsdGVyLmNhdGVnb3J5Lm5hbWVcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgYWN0aXZlOiB0cnVlXG5cbiAgICAgICAgICAgICAgICAkcm9vdFNjb3BlLiR3YXRjaCAnR1JJRkZPLmZpbHRlci5wYWdlJywgLT5cbiAgICAgICAgICAgICAgICAgICAgc2V0QnJlYWQoKVxuXG4gICAgICAgICAgICAgICAgJHJvb3RTY29wZS4kd2F0Y2ggJ0dSSUZGTy5jb25maWcnLCAtPlxuICAgICAgICAgICAgICAgICAgICBzZXRCcmVhZCgpXG5cbiAgICAgICAgfVxuICAgIC5ydW4gKCR0ZW1wbGF0ZUNhY2hlKSAtPlxuICAgICAgICAgICAgJHRlbXBsYXRlQ2FjaGUucHV0ICdncmlmZm8vYnJlYWRjcnVtYi5odG1sJywgJycnXG4gICAgICAgICAgICAgICAgPHNlY3Rpb24gY2xhc3M9XCJicmVhZGNydW1iLXdyYXBwZXJcIiBuZy1zaG93PVwiYnJlYWRjcnVtYnMubGVuZ3RoID4gMFwiPlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwiY29udGFpbmVyXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICA8b2wgY2xhc3M9XCJicmVhZGNydW1iXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxpIG5nLXJlcGVhdD1cImJyZWFkIGluIGJyZWFkY3J1bWJzXCIgbmctY2xhc3M9XCJ7XFwnYWN0aXZlXFwnOiBicmVhZC5hY3RpdmV9XCI+PGEgbmctaHJlZj1cInt7YnJlYWQudXJsfX1cIiBuZy1pZj1cIiFicmVhZC5hY3RpdmUgJiYgYnJlYWQudXJsXCI+e3ticmVhZC5sYWJlbH19PC9hPjxzcGFuIG5nLWlmPVwiYnJlYWQuYWN0aXZlIHx8ICFicmVhZC51cmxcIj57e2JyZWFkLmxhYmVsfX08L3NwYW4+PC9saT5cbiAgICAgICAgICAgICAgICAgICAgICAgIDwvb2w+XG4gICAgICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgICAgIDwvc2VjdGlvbj5cbiAgICAgICAgICAgICcnJ1xuIl19