(function() {
  'use strict';
  angular.module('mainApp').directive('signature', ["$rootScope", "$templateCache", function($rootScope, $templateCache) {
    return {
      restrict: 'E',
      template: function() {
        return $templateCache.get('griffo/signature.html');
      },
      replace: true,
      link: function($scope, $element, $attrs) {
        if (!$attrs.type) {
          $attrs.$set('type', 'black');
        }
        return $attrs.$observe('type', function(type) {
          return $scope.type = type;
        });
      }
    };
  }]).run(["$templateCache", function($templateCache) {
    return $templateCache.put('griffo/signature.html', '<div class="signature">\n    <a href="http://www.acoll.com.br" title="Made with GRIFFO Framework - by Acoll">\n        <img ng-src="{{GRIFFO.templatePath + \'image/griffo-sig-\' + type + \'.png\'}}"/>\n    </a>\n</div>');
  }]);

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDMvY29mZmVlL2RpcmVjdGl2ZXMvc2lnbmF0dXJlRHJ0LmNvZmZlZSJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtFQUFBO0VBQ0EsT0FBTyxDQUFDLE1BQVIsQ0FBZSxTQUFmLENBQ0ksQ0FBQyxTQURMLENBQ2UsV0FEZixFQUM0QixTQUFDLFVBQUQsRUFBYSxjQUFiO1dBQ3BCO01BQ0ksUUFBQSxFQUFVLEdBRGQ7TUFFSSxRQUFBLEVBQVUsU0FBQTtlQUNOLGNBQWMsQ0FBQyxHQUFmLENBQW1CLHVCQUFuQjtNQURNLENBRmQ7TUFJSSxPQUFBLEVBQVMsSUFKYjtNQUtJLElBQUEsRUFBTSxTQUFDLE1BQUQsRUFBUyxRQUFULEVBQW1CLE1BQW5CO1FBQ0YsSUFBRyxDQUFDLE1BQU0sQ0FBQyxJQUFYO1VBQ0ksTUFBTSxDQUFDLElBQVAsQ0FBWSxNQUFaLEVBQW9CLE9BQXBCLEVBREo7O2VBRUEsTUFBTSxDQUFDLFFBQVAsQ0FBZ0IsTUFBaEIsRUFBd0IsU0FBQyxJQUFEO2lCQUNwQixNQUFNLENBQUMsSUFBUCxHQUFjO1FBRE0sQ0FBeEI7TUFIRSxDQUxWOztFQURvQixDQUQ1QixDQWNJLENBQUMsR0FkTCxDQWNTLFNBQUMsY0FBRDtXQUNHLGNBQWMsQ0FBQyxHQUFmLENBQW1CLHVCQUFuQixFQUE0Qyw0TkFBNUM7RUFESCxDQWRUO0FBREEiLCJmaWxlIjoiY2xpZW50L2xheWVycy93ZWJzaXRlL3RlbXBsYXRlcy9tb2RlbG8wMy9qcy9kaXJlY3RpdmVzL3NpZ25hdHVyZURydC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUgJ21haW5BcHAnXG4gICAgLmRpcmVjdGl2ZSAnc2lnbmF0dXJlJywgKCRyb290U2NvcGUsICR0ZW1wbGF0ZUNhY2hlKSAtPlxuICAgICAgICB7XG4gICAgICAgICAgICByZXN0cmljdDogJ0UnXG4gICAgICAgICAgICB0ZW1wbGF0ZTogLT5cbiAgICAgICAgICAgICAgICAkdGVtcGxhdGVDYWNoZS5nZXQgJ2dyaWZmby9zaWduYXR1cmUuaHRtbCdcbiAgICAgICAgICAgIHJlcGxhY2U6IHRydWVcbiAgICAgICAgICAgIGxpbms6ICgkc2NvcGUsICRlbGVtZW50LCAkYXR0cnMpIC0+XG4gICAgICAgICAgICAgICAgaWYgISRhdHRycy50eXBlXG4gICAgICAgICAgICAgICAgICAgICRhdHRycy4kc2V0ICd0eXBlJywgJ2JsYWNrJ1xuICAgICAgICAgICAgICAgICRhdHRycy4kb2JzZXJ2ZSAndHlwZScsICh0eXBlKSAtPlxuICAgICAgICAgICAgICAgICAgICAkc2NvcGUudHlwZSA9IHR5cGVcbiAgICAgICAgfVxuXG4gICAgLnJ1biAoJHRlbXBsYXRlQ2FjaGUpIC0+XG4gICAgICAgICAgICAkdGVtcGxhdGVDYWNoZS5wdXQgJ2dyaWZmby9zaWduYXR1cmUuaHRtbCcsICcnJ1xuICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJzaWduYXR1cmVcIj5cbiAgICAgICAgICAgICAgICAgICAgPGEgaHJlZj1cImh0dHA6Ly93d3cuYWNvbGwuY29tLmJyXCIgdGl0bGU9XCJNYWRlIHdpdGggR1JJRkZPIEZyYW1ld29yayAtIGJ5IEFjb2xsXCI+XG4gICAgICAgICAgICAgICAgICAgICAgICA8aW1nIG5nLXNyYz1cInt7R1JJRkZPLnRlbXBsYXRlUGF0aCArIFxcJ2ltYWdlL2dyaWZmby1zaWctXFwnICsgdHlwZSArIFxcJy5wbmdcXCd9fVwiLz5cbiAgICAgICAgICAgICAgICAgICAgPC9hPlxuICAgICAgICAgICAgICAgIDwvZGl2PlxuICAgICAgICAgICAgJycnXG4iXX0=