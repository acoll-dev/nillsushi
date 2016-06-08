(function() {
  'use strict';
  angular.module('mainApp').directive('categoryList', ["$rootScope", "$templateCache", "$compile", "$timeout", "$window", "$grRestful", function($rootScope, $templateCache, $compile, $timeout, $window, $grRestful) {
    return {
      restrict: 'AE',
      template: function() {
        return $templateCache.get('category/list.html');
      },
      scope: {
        params: '='
      },
      replace: true,
      link: function($scope, $element, $attrs) {
        var ajustIconHeight, setCategories;
        setCategories = function(params) {
          var _loop;
          if ($scope.categories.length > 0) {
            _loop = function(categories) {
              angular.forEach(categories, function(category) {
                var hasChildActive;
                hasChildActive = false;
                category.href = (params.url && params.url.prefix ? params.url.prefix : '') + category.url + (params.url && params.url.suffix ? params.url.suffix : '');
                if (category.child && category.child.length > 0) {
                  category.child = _loop(angular.copy(category.child));
                  angular.forEach(category.child, function(c) {
                    if (c.active) {
                      hasChildActive = true;
                    }
                  });
                  $timeout(ajustIconHeight);
                }
                if (hasChildActive) {
                  category.active = true;
                  category.open = true;
                } else if ($rootScope.GRIFFO.filter.category && category.url === $rootScope.GRIFFO.filter.category.url) {
                  category.active = true;
                  category.open = false;
                }
              });
              return categories;
            };
            $scope.categories = _loop(angular.copy($scope.categories));
            $timeout(ajustIconHeight);
          }
        };
        ajustIconHeight = function() {
          var icons;
          icons = $element.find('.parent-icon');
          return angular.forEach(icons, function(icon) {
            icon = angular.element(icon);
            return icon.css({
              height: icon.siblings('a').eq(0).innerHeight() + 'px',
              lineHeight: icon.siblings('a').eq(0).innerHeight() + 'px'
            });
          });
        };
        $scope.categories = [];
        $scope.$watch('params', function(params) {
          if (params) {
            if (params.parent) {
              return $grRestful.find({
                module: 'category',
                action: 'get',
                id: params.parent
              }).then(function(r) {
                if (r.response) {
                  $scope.categories = r.response;
                  return setCategories(params);
                }
              });
            } else if (params.module) {
              return $grRestful.find({
                module: 'category',
                action: 'module',
                params: 'name=' + params.module
              }).then(function(r) {
                if (r.response) {
                  $scope.categories = r.response;
                  return setCategories(params);
                }
              });
            }
          }
        });
        $scope.toggleOpen = function(category) {
          category.open = !category.open;
          return $timeout(ajustIconHeight);
        };
        return angular.element($window).on('resize', ajustIconHeight);
      }
    };
  }]).run(["$templateCache", function($templateCache) {
    $templateCache.put('category/list.html', '<div class="category-list-wrapper">\n    <h2>{{params.title || \'Categorias\'}}</h2>\n    <ul class="category-list">\n        <li ng-repeat="category in categories" ng-attr-title="{{category.name}}" ng-class="{\'parent\': category.child.length > 0, \'open\': category.open, \'active\': category.active}">\n            <a ng-href="{{category.href}}">\n                {{category.name}}\n                <span class="fa fa-fw fa-lg fa-angle-right" ng-if="category.active && category.child.length === 0"></span>\n            </a>\n            <span class="parent-icon fa fa-fw" ng-class="{\'fa-minus\': category.open, \'fa-plus\': !category.open}" ng-if="category.child.length > 0" ng-click="toggleOpen(category)" ng-attr-title="{{params.category.toggle.title.prefix + category.name + params.category.toggle.title.suffix}}"></span>\n            <ul class="category-list" ng-if="category.child.length > 0" ng-include="\'category/sublist.html\'"></ul>\n        </li>\n        <li class="category-empty" ng-if="!categories || categories.length === 0">\n            <p class="text-muted">{{params.empty}}</p>\n        </li>\n    </ul>\n</div>');
    return $templateCache.put('category/sublist.html', '<li ng-repeat="category in category.child" ng-attr-title="{{category.name}}" ng-class="{\'parent\': category.child.length > 0, \'open\': category.open, \'active\': category.active}">\n    <a ng-href="{{category.href}}">\n        {{category.name}}\n        <span class="fa fa-fw fa-lg fa-angle-right" ng-if="category.active && category.child.length === 0"></span>\n    </a>\n    <span class="parent-icon fa fa-fw" ng-class="{\'fa-minus\': category.open, \'fa-plus\': !category.open}" ng-if="category.child.length > 0" ng-click="toggleOpen(category)" ng-attr-title="{{params.category.toggle.title.prefix + category.name + params.category.toggle.title.suffix}}"></span>\n    <ul class="category-list" ng-if="category.child.length > 0" ng-include="\'category/sublist.html\'"></ul>\n</li>');
  }]);

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDIvY29mZmVlL2RpcmVjdGl2ZXMvY2F0ZWdvcnlMaXN0RHJ0LmNvZmZlZSJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtFQUFBO0VBQ0EsT0FBTyxDQUFDLE1BQVIsQ0FBZSxTQUFmLENBQ0ksQ0FBQyxTQURMLENBQ2UsY0FEZixFQUMrQixTQUFDLFVBQUQsRUFBYSxjQUFiLEVBQTZCLFFBQTdCLEVBQXVDLFFBQXZDLEVBQWlELE9BQWpELEVBQTBELFVBQTFEO1dBRXZCO01BQ0ksUUFBQSxFQUFVLElBRGQ7TUFFSSxRQUFBLEVBQVUsU0FBQTtlQUNOLGNBQWMsQ0FBQyxHQUFmLENBQW1CLG9CQUFuQjtNQURNLENBRmQ7TUFJSSxLQUFBLEVBQU87UUFBQSxNQUFBLEVBQVEsR0FBUjtPQUpYO01BS0ksT0FBQSxFQUFTLElBTGI7TUFNSSxJQUFBLEVBQU0sU0FBQyxNQUFELEVBQVMsUUFBVCxFQUFtQixNQUFuQjtBQUVGLFlBQUE7UUFBQSxhQUFBLEdBQWdCLFNBQUMsTUFBRDtBQUNaLGNBQUE7VUFBQSxJQUFHLE1BQU0sQ0FBQyxVQUFVLENBQUMsTUFBbEIsR0FBMkIsQ0FBOUI7WUFDSSxLQUFBLEdBQVEsU0FBQyxVQUFEO2NBQ0osT0FBTyxDQUFDLE9BQVIsQ0FBZ0IsVUFBaEIsRUFBNEIsU0FBQyxRQUFEO0FBQ3hCLG9CQUFBO2dCQUFBLGNBQUEsR0FBaUI7Z0JBQ2pCLFFBQVEsQ0FBQyxJQUFULEdBQWdCLENBQUksTUFBTSxDQUFDLEdBQVAsSUFBZSxNQUFNLENBQUMsR0FBRyxDQUFDLE1BQTdCLEdBQXlDLE1BQU0sQ0FBQyxHQUFHLENBQUMsTUFBcEQsR0FBZ0UsRUFBakUsQ0FBQSxHQUF1RSxRQUFRLENBQUMsR0FBaEYsR0FBc0YsQ0FBSSxNQUFNLENBQUMsR0FBUCxJQUFlLE1BQU0sQ0FBQyxHQUFHLENBQUMsTUFBN0IsR0FBeUMsTUFBTSxDQUFDLEdBQUcsQ0FBQyxNQUFw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LENBQXhCO1FBbUJBLE1BQU0sQ0FBQyxVQUFQLEdBQW9CLFNBQUMsUUFBRDtVQUNoQixRQUFRLENBQUMsSUFBVCxHQUFnQixDQUFDLFFBQVEsQ0FBQztpQkFDMUIsUUFBQSxDQUFTLGVBQVQ7UUFGZ0I7ZUFJcEIsT0FBTyxDQUFDLE9BQVIsQ0FBZ0IsT0FBaEIsQ0FBd0IsQ0FBQyxFQUF6QixDQUE0QixRQUE1QixFQUFzQyxlQUF0QztNQTVERSxDQU5WOztFQUZ1QixDQUQvQixDQXdFSSxDQUFDLEdBeEVMLENBd0VTLFNBQUMsY0FBRDtJQUVELGNBQWMsQ0FBQyxHQUFmLENBQW1CLG9CQUFuQixFQUF5QyxpbkNBQXpDO1dBbUJBLGNBQWMsQ0FBQyxHQUFmLENBQW1CLHVCQUFuQixFQUE0QyxpeEJBQTVDO0VBckJDLENBeEVUO0FBREEiLCJmaWxlIjoiY2xpZW50L2xheWVycy93ZWJzaXRlL3RlbXBsYXRlcy9tb2RlbG8wMi9qcy9kaXJlY3RpdmVzL2NhdGVnb3J5TGlzdERydC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUgJ21haW5BcHAnXG4gICAgLmRpcmVjdGl2ZSAnY2F0ZWdvcnlMaXN0JywgKCRyb290U2NvcGUsICR0ZW1wbGF0ZUNhY2hlLCAkY29tcGlsZSwgJHRpbWVvdXQsICR3aW5kb3csICRnclJlc3RmdWwpIC0+XG5cbiAgICAgICAge1xuICAgICAgICAgICAgcmVzdHJpY3Q6ICdBRSdcbiAgICAgICAgICAgIHRlbXBsYXRlOiAtPlxuICAgICAgICAgICAgICAgICR0ZW1wbGF0ZUNhY2hlLmdldCAnY2F0ZWdvcnkvbGlzdC5odG1sJ1xuICAgICAgICAgICAgc2NvcGU6IHBhcmFtczogJz0nXG4gICAgICAgICAgICByZXBsYWNlOiB0cnVlXG4gICAgICAgICAgICBsaW5rOiAoJHNjb3BlLCAkZWxlbWVudCwgJGF0dHJzKSAtPlxuXG4gICAgICAgICAgICAgICAgc2V0Q2F0ZWdvcmllcyA9IChwYXJhbXMpIC0+XG4gICAgICAgICAgICAgICAgICAgIGlmICRzY29wZS5jYXRlZ29yaWVzLmxlbmd0aCA+IDBcbiAgICAgICAgICAgICAgICAgICAgICAgIF9sb29wID0gKGNhdGVnb3JpZXMpIC0+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYW5ndWxhci5mb3JFYWNoIGNhdGVnb3JpZXMsIChjYXRlZ29yeSkgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaGFzQ2hpbGRBY3RpdmUgPSBmYWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjYXRlZ29yeS5ocmVmID0gKGlmIHBhcmFtcy51cmwgYW5kIHBhcmFtcy51cmwucHJlZml4IHRoZW4gcGFyYW1zLnVybC5wcmVmaXggZWxzZSAnJykgKyBjYXRlZ29yeS51cmwgKyAoaWYgcGFyYW1zLnVybCBhbmQgcGFyYW1zLnVybC5zdWZmaXggdGhlbiBwYXJhbXMudXJsLnN1ZmZpeCBlbHNlICcnKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiBjYXRlZ29yeS5jaGlsZCBhbmQgY2F0ZWdvcnkuY2hpbGQubGVuZ3RoID4gMFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2F0ZWdvcnkuY2hpbGQgPSBfbG9vcChhbmd1bGFyLmNvcHkoY2F0ZWdvcnkuY2hpbGQpKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgYW5ndWxhci5mb3JFYWNoIGNhdGVnb3J5LmNoaWxkLCAoYykgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiBjLmFjdGl2ZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBoYXNDaGlsZEFjdGl2ZSA9IHRydWVcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICR0aW1lb3V0IGFqdXN0SWNvbkhlaWdodFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiBoYXNDaGlsZEFjdGl2ZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2F0ZWdvcnkuYWN0aXZlID0gdHJ1ZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2F0ZWdvcnkub3BlbiA9IHRydWVcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSBpZiAkcm9vdFNjb3BlLkdSSUZGTy5maWx0ZXIuY2F0ZWdvcnkgYW5kIGNhdGVnb3J5LnVybCA9PSAkcm9vdFNjb3BlLkdSSUZGTy5maWx0ZXIuY2F0ZWdvcnkudXJsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjYXRlZ29yeS5hY3RpdmUgPSB0cnVlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjYXRlZ29yeS5vcGVuID0gZmFsc2VcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY2F0ZWdvcmllc1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuY2F0ZWdvcmllcyA9IF9sb29wKGFuZ3VsYXIuY29weSgkc2NvcGUuY2F0ZWdvcmllcykpXG4gICAgICAgICAgICAgICAgICAgICAgICAkdGltZW91dCBhanVzdEljb25IZWlnaHRcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuXG5cbiAgICAgICAgICAgICAgICBhanVzdEljb25IZWlnaHQgPSAtPlxuICAgICAgICAgICAgICAgICAgICBpY29ucyA9ICRlbGVtZW50LmZpbmQoJy5wYXJlbnQtaWNvbicpXG4gICAgICAgICAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCBpY29ucywgKGljb24pIC0+XG4gICAgICAgICAgICAgICAgICAgICAgICBpY29uID0gYW5ndWxhci5lbGVtZW50KGljb24pXG4gICAgICAgICAgICAgICAgICAgICAgICBpY29uLmNzc1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGhlaWdodDogaWNvbi5zaWJsaW5ncygnYScpLmVxKDApLmlubmVySGVpZ2h0KCkgKyAncHgnXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGluZUhlaWdodDogaWNvbi5zaWJsaW5ncygnYScpLmVxKDApLmlubmVySGVpZ2h0KCkgKyAncHgnXG5cbiAgICAgICAgICAgICAgICAkc2NvcGUuY2F0ZWdvcmllcyA9IFtdXG4gICAgICAgICAgICAgICAgJHNjb3BlLiR3YXRjaCAncGFyYW1zJywgKHBhcmFtcykgLT5cbiAgICAgICAgICAgICAgICAgICAgaWYgcGFyYW1zXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiBwYXJhbXMucGFyZW50XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJGdyUmVzdGZ1bC5maW5kKFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtb2R1bGU6ICdjYXRlZ29yeSdcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiAnZ2V0J1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZDogcGFyYW1zLnBhcmVudCkudGhlbiAocikgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgci5yZXNwb25zZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNhdGVnb3JpZXMgPSByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBzZXRDYXRlZ29yaWVzIHBhcmFtc1xuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSBpZiBwYXJhbXMubW9kdWxlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJGdyUmVzdGZ1bC5maW5kKFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtb2R1bGU6ICdjYXRlZ29yeSdcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgYWN0aW9uOiAnbW9kdWxlJ1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBwYXJhbXM6ICduYW1lPScgKyBwYXJhbXMubW9kdWxlKS50aGVuIChyKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuY2F0ZWdvcmllcyA9IHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNldENhdGVnb3JpZXMgcGFyYW1zXG5cbiAgICAgICAgICAgICAgICAkc2NvcGUudG9nZ2xlT3BlbiA9IChjYXRlZ29yeSkgLT5cbiAgICAgICAgICAgICAgICAgICAgY2F0ZWdvcnkub3BlbiA9ICFjYXRlZ29yeS5vcGVuXG4gICAgICAgICAgICAgICAgICAgICR0aW1lb3V0IGFqdXN0SWNvbkhlaWdodFxuXG4gICAgICAgICAgICAgICAgYW5ndWxhci5lbGVtZW50KCR3aW5kb3cpLm9uICdyZXNpemUnLCBhanVzdEljb25IZWlnaHRcbiAgICAgICAgfVxuXG4gICAgLnJ1biAoJHRlbXBsYXRlQ2FjaGUpIC0+XG5cbiAgICAgICAgJHRlbXBsYXRlQ2FjaGUucHV0ICdjYXRlZ29yeS9saXN0Lmh0bWwnLCAnJydcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJjYXRlZ29yeS1saXN0LXdyYXBwZXJcIj5cbiAgICAgICAgICAgICAgICA8aDI+e3twYXJhbXMudGl0bGUgfHwgXFwnQ2F0ZWdvcmlhc1xcJ319PC9oMj5cbiAgICAgICAgICAgICAgICA8dWwgY2xhc3M9XCJjYXRlZ29yeS1saXN0XCI+XG4gICAgICAgICAgICAgICAgICAgIDxsaSBuZy1yZXBlYXQ9XCJjYXRlZ29yeSBpbiBjYXRlZ29yaWVzXCIgbmctYXR0ci10aXRsZT1cInt7Y2F0ZWdvcnkubmFtZX19XCIgbmctY2xhc3M9XCJ7XFwncGFyZW50XFwnOiBjYXRlZ29yeS5jaGlsZC5sZW5ndGggPiAwLCBcXCdvcGVuXFwnOiBjYXRlZ29yeS5vcGVuLCBcXCdhY3RpdmVcXCc6IGNhdGVnb3J5LmFjdGl2ZX1cIj5cbiAgICAgICAgICAgICAgICAgICAgICAgIDxhIG5nLWhyZWY9XCJ7e2NhdGVnb3J5LmhyZWZ9fVwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHt7Y2F0ZWdvcnkubmFtZX19XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJmYSBmYS1mdyBmYS1sZyBmYS1hbmdsZS1yaWdodFwiIG5nLWlmPVwiY2F0ZWdvcnkuYWN0aXZlICYmIGNhdGVnb3J5LmNoaWxkLmxlbmd0aCA9PT0gMFwiPjwvc3Bhbj5cbiAgICAgICAgICAgICAgICAgICAgICAgIDwvYT5cbiAgICAgICAgICAgICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwicGFyZW50LWljb24gZmEgZmEtZndcIiBuZy1jbGFzcz1cIntcXCdmYS1taW51c1xcJzogY2F0ZWdvcnkub3BlbiwgXFwnZmEtcGx1c1xcJzogIWNhdGVnb3J5Lm9wZW59XCIgbmctaWY9XCJjYXRlZ29yeS5jaGlsZC5sZW5ndGggPiAwXCIgbmctY2xpY2s9XCJ0b2dnbGVPcGVuKGNhdGVnb3J5KVwiIG5nLWF0dHItdGl0bGU9XCJ7e3BhcmFtcy5jYXRlZ29yeS50b2dnbGUudGl0bGUucHJlZml4ICsgY2F0ZWdvcnkubmFtZSArIHBhcmFtcy5jYXRlZ29yeS50b2dnbGUudGl0bGUuc3VmZml4fX1cIj48L3NwYW4+XG4gICAgICAgICAgICAgICAgICAgICAgICA8dWwgY2xhc3M9XCJjYXRlZ29yeS1saXN0XCIgbmctaWY9XCJjYXRlZ29yeS5jaGlsZC5sZW5ndGggPiAwXCIgbmctaW5jbHVkZT1cIlxcJ2NhdGVnb3J5L3N1Ymxpc3QuaHRtbFxcJ1wiPjwvdWw+XG4gICAgICAgICAgICAgICAgICAgIDwvbGk+XG4gICAgICAgICAgICAgICAgICAgIDxsaSBjbGFzcz1cImNhdGVnb3J5LWVtcHR5XCIgbmctaWY9XCIhY2F0ZWdvcmllcyB8fCBjYXRlZ29yaWVzLmxlbmd0aCA9PT0gMFwiPlxuICAgICAgICAgICAgICAgICAgICAgICAgPHAgY2xhc3M9XCJ0ZXh0LW11dGVkXCI+e3twYXJhbXMuZW1wdHl9fTwvcD5cbiAgICAgICAgICAgICAgICAgICAgPC9saT5cbiAgICAgICAgICAgICAgICA8L3VsPlxuICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICcnJ1xuXG4gICAgICAgICR0ZW1wbGF0ZUNhY2hlLnB1dCAnY2F0ZWdvcnkvc3VibGlzdC5odG1sJywgJycnXG4gICAgICAgICAgICA8bGkgbmctcmVwZWF0PVwiY2F0ZWdvcnkgaW4gY2F0ZWdvcnkuY2hpbGRcIiBuZy1hdHRyLXRpdGxlPVwie3tjYXRlZ29yeS5uYW1lfX1cIiBuZy1jbGFzcz1cIntcXCdwYXJlbnRcXCc6IGNhdGVnb3J5LmNoaWxkLmxlbmd0aCA+IDAsIFxcJ29wZW5cXCc6IGNhdGVnb3J5Lm9wZW4sIFxcJ2FjdGl2ZVxcJzogY2F0ZWdvcnkuYWN0aXZlfVwiPlxuICAgICAgICAgICAgICAgIDxhIG5nLWhyZWY9XCJ7e2NhdGVnb3J5LmhyZWZ9fVwiPlxuICAgICAgICAgICAgICAgICAgICB7e2NhdGVnb3J5Lm5hbWV9fVxuICAgICAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cImZhIGZhLWZ3IGZhLWxnIGZhLWFuZ2xlLXJpZ2h0XCIgbmctaWY9XCJjYXRlZ29yeS5hY3RpdmUgJiYgY2F0ZWdvcnkuY2hpbGQubGVuZ3RoID09PSAwXCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgIDwvYT5cbiAgICAgICAgICAgICAgICA8c3BhbiBjbGFzcz1cInBhcmVudC1pY29uIGZhIGZhLWZ3XCIgbmctY2xhc3M9XCJ7XFwnZmEtbWludXNcXCc6IGNhdGVnb3J5Lm9wZW4sIFxcJ2ZhLXBsdXNcXCc6ICFjYXRlZ29yeS5vcGVufVwiIG5nLWlmPVwiY2F0ZWdvcnkuY2hpbGQubGVuZ3RoID4gMFwiIG5nLWNsaWNrPVwidG9nZ2xlT3BlbihjYXRlZ29yeSlcIiBuZy1hdHRyLXRpdGxlPVwie3twYXJhbXMuY2F0ZWdvcnkudG9nZ2xlLnRpdGxlLnByZWZpeCArIGNhdGVnb3J5Lm5hbWUgKyBwYXJhbXMuY2F0ZWdvcnkudG9nZ2xlLnRpdGxlLnN1ZmZpeH19XCI+PC9zcGFuPlxuICAgICAgICAgICAgICAgIDx1bCBjbGFzcz1cImNhdGVnb3J5LWxpc3RcIiBuZy1pZj1cImNhdGVnb3J5LmNoaWxkLmxlbmd0aCA+IDBcIiBuZy1pbmNsdWRlPVwiXFwnY2F0ZWdvcnkvc3VibGlzdC5odG1sXFwnXCI+PC91bD5cbiAgICAgICAgICAgIDwvbGk+XG4gICAgICAgICcnJ1xuIl19