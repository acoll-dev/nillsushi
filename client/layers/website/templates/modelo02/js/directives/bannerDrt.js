(function() {
  'use strict';
  angular.module('mainApp').directive('banner', ["$rootScope", "$templateCache", "$window", "$timeout", "$grRestful", function($rootScope, $templateCache, $window, $timeout, $grRestful) {
    return {
      restrict: 'E',
      scope: {
        category: '=',
        delay: '='
      },
      replace: true,
      template: function() {
        return $templateCache.get('griffo/banner.html');
      },
      link: function($scope, $element, $attrs) {
        $scope.GRIFFO = $rootScope.GRIFFO;
        $scope.banners = [];
        $scope.preloading = true;
        $scope.imagesLoaded = {
          always: function(instance) {
            $scope.preloading = false;
            return $timeout(function() {
              return angular.element($window).trigger('resize');
            });
          }
        };
        return $scope.$watch($attrs.category, function(category) {
          return $grRestful.find({
            module: 'banner',
            action: category ? 'category' : 'get',
            params: category ? 'name=' + category : ''
          }).then(function(r) {
            if (r.response) {
              $scope.banners = r.response;
              return angular.forEach($scope.banners, function(banner) {
                if (banner.link && (banner.link.indexOf('http://') > -1 || banner.link.indexOf('https://') > -1)) {
                  return banner.target = '_blank';
                } else {
                  return banner.target = '_self';
                }
              });
            }
          });
        });
      }
    };
  }]).run(["$templateCache", function($templateCache) {
    return $templateCache.put('griffo/banner.html', '<div class="banner">\n    <gr-carousel id="banner" autoplay="{{delay || 4000}}" data-ng-if="banners.length > 0" data-ng-show="!preloading" images-loaded="imagesLoaded">\n        <gr-carousel-item data-ng-repeat="banner in banners">\n            <img data-ng-src="{{GRIFFO.uploadPath + banner.picture}}" />\n            <section class="banner-content container">\n                <h1 data-ng-if="banner.title">{{banner.title}}</h1>\n                <h2 data-ng-if="banner.description">{{banner.description}}</h2>\n                <a class="btn btn-griffo-1" data-ng-class="{\'btn-xs\': GRIFFO.viewPort.width <= 990}" data-ng-href="{{banner.link}}" target="{{banner.target}}" data-ng-if="banner.link">Saber mais</a>\n            </section>\n        </gr-carousel-item>\n        <gr-carousel-indicators for="banner" data-ng-show="banners.length > 1"></gr-carousel-indicators>\n    </gr-carousel>\n    <div class="preloader" data-ng-if="banners.length <= 0 || preloading">\n        <div class="preloader-inner">\n            <i class="fa fa-fw fa-refresh fa-spin fa-3x"></i>\n        </div>\n    </div>\n</div>');
  }]);

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDIvY29mZmVlL2RpcmVjdGl2ZXMvYmFubmVyRHJ0LmNvZmZlZSJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtFQUFBO0VBQ0EsT0FBTyxDQUFDLE1BQVIsQ0FBZSxTQUFmLENBQ0ksQ0FBQyxTQURMLENBQ2UsUUFEZixFQUN5QixTQUFDLFVBQUQsRUFBYSxjQUFiLEVBQTZCLE9BQTdCLEVBQXNDLFFBQXRDLEVBQWdELFVBQWhEO1dBQ2pCO01BQ0ksUUFBQSxFQUFVLEdBRGQ7TUFFSSxLQUFBLEVBQ0k7UUFBQSxRQUFBLEVBQVUsR0FBVjtRQUNBLEtBQUEsRUFBTyxHQURQO09BSFI7TUFLSSxPQUFBLEVBQVMsSUFMYjtNQU1JLFFBQUEsRUFBVSxTQUFBO2VBQ04sY0FBYyxDQUFDLEdBQWYsQ0FBbUIsb0JBQW5CO01BRE0sQ0FOZDtNQVFJLElBQUEsRUFBTSxTQUFDLE1BQUQsRUFBUyxRQUFULEVBQW1CLE1BQW5CO1FBQ0YsTUFBTSxDQUFDLE1BQVAsR0FBZ0IsVUFBVSxDQUFDO1FBQzNCLE1BQU0sQ0FBQyxPQUFQLEdBQWlCO1FBQ2pCLE1BQU0sQ0FBQyxVQUFQLEdBQW9CO1FBQ3BCLE1BQU0sQ0FBQyxZQUFQLEdBQXNCO1VBQUEsTUFBQSxFQUFRLFNBQUMsUUFBRDtZQUMxQixNQUFNLENBQUMsVUFBUCxHQUFvQjttQkFDcEIsUUFBQSxDQUFTLFNBQUE7cUJBQ0wsT0FBTyxDQUFDLE9BQVIsQ0FBZ0IsT0FBaEIsQ0FBd0IsQ0FBQyxPQUF6QixDQUFpQyxRQUFqQztZQURLLENBQVQ7VUFGMEIsQ0FBUjs7ZUFJdEIsTUFBTSxDQUFDLE1BQVAsQ0FBYyxNQUFNLENBQUMsUUFBckIsRUFBK0IsU0FBQyxRQUFEO2lCQUMzQixVQUFVLENBQUMsSUFBWCxDQUNJO1lBQUEsTUFBQSxFQUFRLFFBQVI7WUFDQSxNQUFBLEVBQVcsUUFBSCxHQUFpQixVQUFqQixHQUFpQyxLQUR6QztZQUVBLE1BQUEsRUFBVyxRQUFILEdBQWlCLE9BQUEsR0FBVSxRQUEzQixHQUF5QyxFQUZqRDtXQURKLENBR3dELENBQUMsSUFIekQsQ0FHOEQsU0FBQyxDQUFEO1lBQzFELElBQUcsQ0FBQyxDQUFDLFFBQUw7Y0FDSSxNQUFNLENBQUMsT0FBUCxHQUFpQixDQUFDLENBQUM7cUJBQ25CLE9BQU8sQ0FBQyxPQUFSLENBQWdCLE1BQU0sQ0FBQyxPQUF2QixFQUFnQyxTQUFDLE1BQUQ7Z0JBQzVCLElBQUcsTUFBTSxDQUFDLElBQVAsSUFBZ0IsQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLE9BQVosQ0FBb0IsU0FBcEIsQ0FBQSxHQUFpQyxDQUFDLENBQWxDLElBQXVDLE1BQU0sQ0FBQyxJQUFJLENBQUMsT0FBWixDQUFvQixVQUFwQixDQUFBLEdBQWtDLENBQUMsQ0FBM0UsQ0FBbkI7eUJBQ0ksTUFBTSxDQUFDLE1BQVAsR0FBZ0IsU0FEcEI7aUJBQUEsTUFBQTt5QkFHSSxNQUFNLENBQUMsTUFBUCxHQUFnQixRQUhwQjs7Y0FENEIsQ0FBaEMsRUFGSjs7VUFEMEQsQ0FIOUQ7UUFEMkIsQ0FBL0I7TUFSRSxDQVJWOztFQURpQixDQUR6QixDQWdDSSxDQUFDLEdBaENMLENBZ0NTLFNBQUMsY0FBRDtXQUNELGNBQWMsQ0FBQyxHQUFmLENBQW1CLG9CQUFuQixFQUF5QyxvbENBQXpDO0VBREMsQ0FoQ1Q7QUFEQSIsImZpbGUiOiJjbGllbnQvbGF5ZXJzL3dlYnNpdGUvdGVtcGxhdGVzL21vZGVsbzAyL2pzL2RpcmVjdGl2ZXMvYmFubmVyRHJ0LmpzIiwic291cmNlUm9vdCI6Ii9zb3VyY2UvIiwic291cmNlc0NvbnRlbnQiOlsiJ3VzZSBzdHJpY3QnXG5hbmd1bGFyLm1vZHVsZSAnbWFpbkFwcCdcbiAgICAuZGlyZWN0aXZlICdiYW5uZXInLCAoJHJvb3RTY29wZSwgJHRlbXBsYXRlQ2FjaGUsICR3aW5kb3csICR0aW1lb3V0LCAkZ3JSZXN0ZnVsKSAtPlxuICAgICAgICB7XG4gICAgICAgICAgICByZXN0cmljdDogJ0UnXG4gICAgICAgICAgICBzY29wZTpcbiAgICAgICAgICAgICAgICBjYXRlZ29yeTogJz0nXG4gICAgICAgICAgICAgICAgZGVsYXk6ICc9J1xuICAgICAgICAgICAgcmVwbGFjZTogdHJ1ZVxuICAgICAgICAgICAgdGVtcGxhdGU6IC0+XG4gICAgICAgICAgICAgICAgJHRlbXBsYXRlQ2FjaGUuZ2V0ICdncmlmZm8vYmFubmVyLmh0bWwnXG4gICAgICAgICAgICBsaW5rOiAoJHNjb3BlLCAkZWxlbWVudCwgJGF0dHJzKSAtPlxuICAgICAgICAgICAgICAgICRzY29wZS5HUklGRk8gPSAkcm9vdFNjb3BlLkdSSUZGT1xuICAgICAgICAgICAgICAgICRzY29wZS5iYW5uZXJzID0gW11cbiAgICAgICAgICAgICAgICAkc2NvcGUucHJlbG9hZGluZyA9IHRydWVcbiAgICAgICAgICAgICAgICAkc2NvcGUuaW1hZ2VzTG9hZGVkID0gYWx3YXlzOiAoaW5zdGFuY2UpIC0+XG4gICAgICAgICAgICAgICAgICAgICRzY29wZS5wcmVsb2FkaW5nID0gZmFsc2VcbiAgICAgICAgICAgICAgICAgICAgJHRpbWVvdXQgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgIGFuZ3VsYXIuZWxlbWVudCgkd2luZG93KS50cmlnZ2VyICdyZXNpemUnXG4gICAgICAgICAgICAgICAgJHNjb3BlLiR3YXRjaCAkYXR0cnMuY2F0ZWdvcnksIChjYXRlZ29yeSkgLT5cbiAgICAgICAgICAgICAgICAgICAgJGdyUmVzdGZ1bC5maW5kKFxuICAgICAgICAgICAgICAgICAgICAgICAgbW9kdWxlOiAnYmFubmVyJ1xuICAgICAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiBpZiBjYXRlZ29yeSB0aGVuICdjYXRlZ29yeScgZWxzZSAnZ2V0J1xuICAgICAgICAgICAgICAgICAgICAgICAgcGFyYW1zOiBpZiBjYXRlZ29yeSB0aGVuICduYW1lPScgKyBjYXRlZ29yeSBlbHNlICcnKS50aGVuIChyKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgci5yZXNwb25zZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICRzY29wZS5iYW5uZXJzID0gci5yZXNwb25zZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCAkc2NvcGUuYmFubmVycywgKGJhbm5lcikgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgYmFubmVyLmxpbmsgYW5kIChiYW5uZXIubGluay5pbmRleE9mKCdodHRwOi8vJykgPiAtMSBvciBiYW5uZXIubGluay5pbmRleE9mKCdodHRwczovLycpID4gLTEpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBiYW5uZXIudGFyZ2V0ID0gJ19ibGFuaydcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgYmFubmVyLnRhcmdldCA9ICdfc2VsZidcblxuICAgICAgICB9XG4gICAgLnJ1biAoJHRlbXBsYXRlQ2FjaGUpIC0+XG4gICAgICAgICR0ZW1wbGF0ZUNhY2hlLnB1dCAnZ3JpZmZvL2Jhbm5lci5odG1sJywgJycnXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiYmFubmVyXCI+XG4gICAgICAgICAgICAgICAgPGdyLWNhcm91c2VsIGlkPVwiYmFubmVyXCIgYXV0b3BsYXk9XCJ7e2RlbGF5IHx8IDQwMDB9fVwiIGRhdGEtbmctaWY9XCJiYW5uZXJzLmxlbmd0aCA+IDBcIiBkYXRhLW5nLXNob3c9XCIhcHJlbG9hZGluZ1wiIGltYWdlcy1sb2FkZWQ9XCJpbWFnZXNMb2FkZWRcIj5cbiAgICAgICAgICAgICAgICAgICAgPGdyLWNhcm91c2VsLWl0ZW0gZGF0YS1uZy1yZXBlYXQ9XCJiYW5uZXIgaW4gYmFubmVyc1wiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPGltZyBkYXRhLW5nLXNyYz1cInt7R1JJRkZPLnVwbG9hZFBhdGggKyBiYW5uZXIucGljdHVyZX19XCIgLz5cbiAgICAgICAgICAgICAgICAgICAgICAgIDxzZWN0aW9uIGNsYXNzPVwiYmFubmVyLWNvbnRlbnQgY29udGFpbmVyXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPGgxIGRhdGEtbmctaWY9XCJiYW5uZXIudGl0bGVcIj57e2Jhbm5lci50aXRsZX19PC9oMT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aDIgZGF0YS1uZy1pZj1cImJhbm5lci5kZXNjcmlwdGlvblwiPnt7YmFubmVyLmRlc2NyaXB0aW9ufX08L2gyPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxhIGNsYXNzPVwiYnRuIGJ0bi1ncmlmZm8tMVwiIGRhdGEtbmctY2xhc3M9XCJ7XFwnYnRuLXhzXFwnOiBHUklGRk8udmlld1BvcnQud2lkdGggPD0gOTkwfVwiIGRhdGEtbmctaHJlZj1cInt7YmFubmVyLmxpbmt9fVwiIHRhcmdldD1cInt7YmFubmVyLnRhcmdldH19XCIgZGF0YS1uZy1pZj1cImJhbm5lci5saW5rXCI+U2FiZXIgbWFpczwvYT5cbiAgICAgICAgICAgICAgICAgICAgICAgIDwvc2VjdGlvbj5cbiAgICAgICAgICAgICAgICAgICAgPC9nci1jYXJvdXNlbC1pdGVtPlxuICAgICAgICAgICAgICAgICAgICA8Z3ItY2Fyb3VzZWwtaW5kaWNhdG9ycyBmb3I9XCJiYW5uZXJcIiBkYXRhLW5nLXNob3c9XCJiYW5uZXJzLmxlbmd0aCA+IDFcIj48L2dyLWNhcm91c2VsLWluZGljYXRvcnM+XG4gICAgICAgICAgICAgICAgPC9nci1jYXJvdXNlbD5cbiAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwicHJlbG9hZGVyXCIgZGF0YS1uZy1pZj1cImJhbm5lcnMubGVuZ3RoIDw9IDAgfHwgcHJlbG9hZGluZ1wiPlxuICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPVwicHJlbG9hZGVyLWlubmVyXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICA8aSBjbGFzcz1cImZhIGZhLWZ3IGZhLXJlZnJlc2ggZmEtc3BpbiBmYS0zeFwiPjwvaT5cbiAgICAgICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgJycnXG4iXX0=