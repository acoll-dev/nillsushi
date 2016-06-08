(function() {
  'use strict';
  angular.module('mainApp').directive('imgLiquid', ["$compile", "$timeout", function($compile, $timeout) {}]);

  ({
    restrict: 'AC',
    link: function($scope, $element, $attrs) {
      $element.addClass('imgLiquidFill imgLiquid');
      return $timeout(function() {
        return $element.imgLiquid({
          verticalAlign: 'top'
        });
      });
    }
  });

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDIvY29mZmVlL2RpcmVjdGl2ZXMvaW1nTGlxdWlkRHJ0LmNvZmZlZSJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtFQUFBO0VBQ0EsT0FBTyxDQUFDLE1BQVIsQ0FBZSxTQUFmLENBQ0ksQ0FBQyxTQURMLENBQ2UsV0FEZixFQUM0QixTQUFDLFFBQUQsRUFBVyxRQUFYLEdBQUEsQ0FENUI7O0VBRUksQ0FBQTtJQUNJLFFBQUEsRUFBVSxJQURkO0lBRUksSUFBQSxFQUFNLFNBQUMsTUFBRCxFQUFTLFFBQVQsRUFBbUIsTUFBbkI7TUFDRixRQUFRLENBQUMsUUFBVCxDQUFrQix5QkFBbEI7YUFDQSxRQUFBLENBQVMsU0FBQTtlQUNMLFFBQVEsQ0FBQyxTQUFULENBQW1CO1VBQUEsYUFBQSxFQUFlLEtBQWY7U0FBbkI7TUFESyxDQUFUO0lBRkUsQ0FGVjtHQUFBO0FBSEoiLCJmaWxlIjoiY2xpZW50L2xheWVycy93ZWJzaXRlL3RlbXBsYXRlcy9tb2RlbG8wMi9qcy9kaXJlY3RpdmVzL2ltZ0xpcXVpZERydC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUgJ21haW5BcHAnXG4gICAgLmRpcmVjdGl2ZSAnaW1nTGlxdWlkJywgKCRjb21waWxlLCAkdGltZW91dCkgLT5cbiAgICB7XG4gICAgICAgIHJlc3RyaWN0OiAnQUMnXG4gICAgICAgIGxpbms6ICgkc2NvcGUsICRlbGVtZW50LCAkYXR0cnMpIC0+XG4gICAgICAgICAgICAkZWxlbWVudC5hZGRDbGFzcyAnaW1nTGlxdWlkRmlsbCBpbWdMaXF1aWQnXG4gICAgICAgICAgICAkdGltZW91dCAtPlxuICAgICAgICAgICAgICAgICRlbGVtZW50LmltZ0xpcXVpZCB2ZXJ0aWNhbEFsaWduOiAndG9wJ1xuICAgIH1cbiJdfQ==