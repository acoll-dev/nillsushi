(function() {
  'use strict';
  angular.module('mainApp').directive('fancybox', ["$rootScope", "$timeout", function($rootScope, $timeout) {
    return {
      restrict: 'A',
      link: function($scope, $element, $attrs) {
        var setFancybox;
        setFancybox = function() {
          var el;
          el = $element.find('.fancybox');
          if ($attrs.fancyGroup) {
            el.attr('rel', $attrs.fancyGroup);
          }
          return el.fancybox({
            type: $scope.type,
            fitToView: false,
            maxWidth: '100%',
            tpl: {
              closeBtn: '<a title=\'Fechar\' class=\'fancybox-icone-close\' href=\'javascript:;\'>\n    <div class=\'fancybox-icone-background\'>\n        <i class=\'fa fa-times\'></i>\n    </div>\n</a>'
            }
          });
        };
        $scope.type = 'image';
        $timeout(setFancybox);
        return $scope.$watch($attrs.fancyType, function(type) {
          if (type) {
            $scope.type = type;
            return setFancybox();
          }
        });
      }
    };
  }]);

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvY3JhYi9jb2ZmZWUvZGlyZWN0aXZlcy9mYW5jeWJveERydC5jb2ZmZWUiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7RUFBQTtFQUNBLE9BQU8sQ0FBQyxNQUFSLENBQWUsU0FBZixDQUNJLENBQUMsU0FETCxDQUNlLFVBRGYsRUFDMkIsU0FBQyxVQUFELEVBQWEsUUFBYjtXQUNuQjtNQUFBLFFBQUEsRUFBVSxHQUFWO01BQ0EsSUFBQSxFQUFNLFNBQUMsTUFBRCxFQUFTLFFBQVQsRUFBbUIsTUFBbkI7QUFFRixZQUFBO1FBQUEsV0FBQSxHQUFjLFNBQUE7QUFDVixjQUFBO1VBQUEsRUFBQSxHQUFLLFFBQVEsQ0FBQyxJQUFULENBQWMsV0FBZDtVQUNMLElBQUcsTUFBTSxDQUFDLFVBQVY7WUFDSSxFQUFFLENBQUMsSUFBSCxDQUFRLEtBQVIsRUFBZSxNQUFNLENBQUMsVUFBdEIsRUFESjs7aUJBRUEsRUFBRSxDQUFDLFFBQUgsQ0FDSTtZQUFBLElBQUEsRUFBTSxNQUFNLENBQUMsSUFBYjtZQUNBLFNBQUEsRUFBVyxLQURYO1lBRUEsUUFBQSxFQUFVLE1BRlY7WUFHQSxHQUFBLEVBQ0k7Y0FBQSxRQUFBLEVBQVUsbUxBQVY7YUFKSjtXQURKO1FBSlU7UUFpQmQsTUFBTSxDQUFDLElBQVAsR0FBYztRQUNkLFFBQUEsQ0FBUyxXQUFUO2VBQ0EsTUFBTSxDQUFDLE1BQVAsQ0FBYyxNQUFNLENBQUMsU0FBckIsRUFBZ0MsU0FBQyxJQUFEO1VBQzVCLElBQUcsSUFBSDtZQUNJLE1BQU0sQ0FBQyxJQUFQLEdBQWM7bUJBQ2QsV0FBQSxDQUFBLEVBRko7O1FBRDRCLENBQWhDO01BckJFLENBRE47O0VBRG1CLENBRDNCO0FBREEiLCJmaWxlIjoiY2xpZW50L2xheWVycy93ZWJzaXRlL3RlbXBsYXRlcy9jcmFiL2pzL2RpcmVjdGl2ZXMvZmFuY3lib3hEcnQuanMiLCJzb3VyY2VSb290IjoiL3NvdXJjZS8iLCJzb3VyY2VzQ29udGVudCI6WyIndXNlIHN0cmljdCdcbmFuZ3VsYXIubW9kdWxlICdtYWluQXBwJ1xuICAgIC5kaXJlY3RpdmUgJ2ZhbmN5Ym94JywgKCRyb290U2NvcGUsICR0aW1lb3V0KSAtPlxuICAgICAgICByZXN0cmljdDogJ0EnXG4gICAgICAgIGxpbms6ICgkc2NvcGUsICRlbGVtZW50LCAkYXR0cnMpIC0+XG5cbiAgICAgICAgICAgIHNldEZhbmN5Ym94ID0gLT5cbiAgICAgICAgICAgICAgICBlbCA9ICRlbGVtZW50LmZpbmQoJy5mYW5jeWJveCcpXG4gICAgICAgICAgICAgICAgaWYgJGF0dHJzLmZhbmN5R3JvdXBcbiAgICAgICAgICAgICAgICAgICAgZWwuYXR0ciAncmVsJywgJGF0dHJzLmZhbmN5R3JvdXBcbiAgICAgICAgICAgICAgICBlbC5mYW5jeWJveFxuICAgICAgICAgICAgICAgICAgICB0eXBlOiAkc2NvcGUudHlwZVxuICAgICAgICAgICAgICAgICAgICBmaXRUb1ZpZXc6IGZhbHNlXG4gICAgICAgICAgICAgICAgICAgIG1heFdpZHRoOiAnMTAwJSdcbiAgICAgICAgICAgICAgICAgICAgdHBsOlxuICAgICAgICAgICAgICAgICAgICAgICAgY2xvc2VCdG46ICcnJ1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxhIHRpdGxlPSdGZWNoYXInIGNsYXNzPSdmYW5jeWJveC1pY29uZS1jbG9zZScgaHJlZj0namF2YXNjcmlwdDo7Jz5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0nZmFuY3lib3gtaWNvbmUtYmFja2dyb3VuZCc+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aSBjbGFzcz0nZmEgZmEtdGltZXMnPjwvaT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9hPlxuICAgICAgICAgICAgICAgICAgICAgICAgJycnXG5cbiAgICAgICAgICAgICRzY29wZS50eXBlID0gJ2ltYWdlJ1xuICAgICAgICAgICAgJHRpbWVvdXQgc2V0RmFuY3lib3hcbiAgICAgICAgICAgICRzY29wZS4kd2F0Y2ggJGF0dHJzLmZhbmN5VHlwZSwgKHR5cGUpIC0+XG4gICAgICAgICAgICAgICAgaWYgdHlwZVxuICAgICAgICAgICAgICAgICAgICAkc2NvcGUudHlwZSA9IHR5cGVcbiAgICAgICAgICAgICAgICAgICAgc2V0RmFuY3lib3goKVxuIl19