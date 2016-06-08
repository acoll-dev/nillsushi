(function() {
  'use strict';
  angular.module('mainApp').directive('imgLiquid', ["$compile", "$timeout", function($compile, $timeout) {
    return {
      restrict: 'AC',
      link: function($scope, $element, $attrs) {
        $element.addClass('imgLiquidFill imgLiquid');
        return $timeout(function() {
          return $element.imgLiquid({
            verticalAlign: 'top'
          });
        });
      }
    };
  }]);

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvY3JhYi9jb2ZmZWUvZGlyZWN0aXZlcy9pbWdMaXF1aWREcnQuY29mZmVlIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0VBQUE7RUFDQSxPQUFPLENBQUMsTUFBUixDQUFlLFNBQWYsQ0FDSSxDQUFDLFNBREwsQ0FDZSxXQURmLEVBQzRCLFNBQUMsUUFBRCxFQUFXLFFBQVg7V0FDcEI7TUFBQSxRQUFBLEVBQVUsSUFBVjtNQUNBLElBQUEsRUFBTSxTQUFDLE1BQUQsRUFBUyxRQUFULEVBQW1CLE1BQW5CO1FBQ0YsUUFBUSxDQUFDLFFBQVQsQ0FBa0IseUJBQWxCO2VBQ0EsUUFBQSxDQUFTLFNBQUE7aUJBQ0wsUUFBUSxDQUFDLFNBQVQsQ0FBbUI7WUFBQSxhQUFBLEVBQWUsS0FBZjtXQUFuQjtRQURLLENBQVQ7TUFGRSxDQUROOztFQURvQixDQUQ1QjtBQURBIiwiZmlsZSI6ImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvY3JhYi9qcy9kaXJlY3RpdmVzL2ltZ0xpcXVpZERydC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUgJ21haW5BcHAnXG4gICAgLmRpcmVjdGl2ZSAnaW1nTGlxdWlkJywgKCRjb21waWxlLCAkdGltZW91dCkgLT5cbiAgICAgICAgcmVzdHJpY3Q6ICdBQydcbiAgICAgICAgbGluazogKCRzY29wZSwgJGVsZW1lbnQsICRhdHRycykgLT5cbiAgICAgICAgICAgICRlbGVtZW50LmFkZENsYXNzICdpbWdMaXF1aWRGaWxsIGltZ0xpcXVpZCdcbiAgICAgICAgICAgICR0aW1lb3V0IC0+XG4gICAgICAgICAgICAgICAgJGVsZW1lbnQuaW1nTGlxdWlkIHZlcnRpY2FsQWxpZ246ICd0b3AnXG4iXX0=