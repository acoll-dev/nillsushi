(function() {
  'use strict';
  angular.module('mainApp').directive('includeReplace', function() {
    return {
      require: 'ngInclude',
      restrict: 'A',
      link: function(scope, el, attrs) {
        return el.replaceWith(el.children());
      }
    };
  });

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvY3JhYi9jb2ZmZWUvZGlyZWN0aXZlcy9pbmNsdWRlUmVwbGFjZURydC5jb2ZmZWUiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7RUFBQTtFQUNBLE9BQU8sQ0FBQyxNQUFSLENBQWUsU0FBZixDQUF5QixDQUFDLFNBQTFCLENBQW9DLGdCQUFwQyxFQUFzRCxTQUFBO1dBQ3BEO01BQUEsT0FBQSxFQUFTLFdBQVQ7TUFDQSxRQUFBLEVBQVUsR0FEVjtNQUVBLElBQUEsRUFBTSxTQUFDLEtBQUQsRUFBUSxFQUFSLEVBQVksS0FBWjtlQUNKLEVBQUUsQ0FBQyxXQUFILENBQWUsRUFBRSxDQUFDLFFBQUgsQ0FBQSxDQUFmO01BREksQ0FGTjs7RUFEb0QsQ0FBdEQ7QUFEQSIsImZpbGUiOiJjbGllbnQvbGF5ZXJzL3dlYnNpdGUvdGVtcGxhdGVzL2NyYWIvanMvZGlyZWN0aXZlcy9pbmNsdWRlUmVwbGFjZURydC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUoJ21haW5BcHAnKS5kaXJlY3RpdmUgJ2luY2x1ZGVSZXBsYWNlJywgLT5cbiAgcmVxdWlyZTogJ25nSW5jbHVkZSdcbiAgcmVzdHJpY3Q6ICdBJ1xuICBsaW5rOiAoc2NvcGUsIGVsLCBhdHRycykgLT5cbiAgICBlbC5yZXBsYWNlV2l0aCBlbC5jaGlsZHJlbigpXG4iXX0=