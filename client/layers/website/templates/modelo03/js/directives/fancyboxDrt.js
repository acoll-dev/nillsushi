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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDMvY29mZmVlL2RpcmVjdGl2ZXMvZmFuY3lib3hEcnQuY29mZmVlIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0VBQUE7RUFDQSxPQUFPLENBQUMsTUFBUixDQUFlLFNBQWYsQ0FDSSxDQUFDLFNBREwsQ0FDZSxVQURmLEVBQzJCLFNBQUMsVUFBRCxFQUFhLFFBQWI7V0FDbkI7TUFDSSxRQUFBLEVBQVUsR0FEZDtNQUVJLElBQUEsRUFBTSxTQUFDLE1BQUQsRUFBUyxRQUFULEVBQW1CLE1BQW5CO0FBRUYsWUFBQTtRQUFBLFdBQUEsR0FBYyxTQUFBO0FBQ1YsY0FBQTtVQUFBLEVBQUEsR0FBSyxRQUFRLENBQUMsSUFBVCxDQUFjLFdBQWQ7VUFDTCxJQUFHLE1BQU0sQ0FBQyxVQUFWO1lBQ0ksRUFBRSxDQUFDLElBQUgsQ0FBUSxLQUFSLEVBQWUsTUFBTSxDQUFDLFVBQXRCLEVBREo7O2lCQUVBLEVBQUUsQ0FBQyxRQUFILENBQ0k7WUFBQSxJQUFBLEVBQU0sTUFBTSxDQUFDLElBQWI7WUFDQSxTQUFBLEVBQVcsS0FEWDtZQUVBLFFBQUEsRUFBVSxNQUZWO1lBR0EsR0FBQSxFQUNJO2NBQUEsUUFBQSxFQUFVLG1MQUFWO2FBSko7V0FESjtRQUpVO1FBaUJkLE1BQU0sQ0FBQyxJQUFQLEdBQWM7UUFDZCxRQUFBLENBQVMsV0FBVDtlQUNBLE1BQU0sQ0FBQyxNQUFQLENBQWMsTUFBTSxDQUFDLFNBQXJCLEVBQWdDLFNBQUMsSUFBRDtVQUM1QixJQUFHLElBQUg7WUFDSSxNQUFNLENBQUMsSUFBUCxHQUFjO21CQUNkLFdBQUEsQ0FBQSxFQUZKOztRQUQ0QixDQUFoQztNQXJCRSxDQUZWOztFQURtQixDQUQzQjtBQURBIiwiZmlsZSI6ImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDMvanMvZGlyZWN0aXZlcy9mYW5jeWJveERydC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUgJ21haW5BcHAnXG4gICAgLmRpcmVjdGl2ZSAnZmFuY3lib3gnLCAoJHJvb3RTY29wZSwgJHRpbWVvdXQpIC0+XG4gICAgICAgIHtcbiAgICAgICAgICAgIHJlc3RyaWN0OiAnQSdcbiAgICAgICAgICAgIGxpbms6ICgkc2NvcGUsICRlbGVtZW50LCAkYXR0cnMpIC0+XG5cbiAgICAgICAgICAgICAgICBzZXRGYW5jeWJveCA9IC0+XG4gICAgICAgICAgICAgICAgICAgIGVsID0gJGVsZW1lbnQuZmluZCgnLmZhbmN5Ym94JylcbiAgICAgICAgICAgICAgICAgICAgaWYgJGF0dHJzLmZhbmN5R3JvdXBcbiAgICAgICAgICAgICAgICAgICAgICAgIGVsLmF0dHIgJ3JlbCcsICRhdHRycy5mYW5jeUdyb3VwXG4gICAgICAgICAgICAgICAgICAgIGVsLmZhbmN5Ym94XG4gICAgICAgICAgICAgICAgICAgICAgICB0eXBlOiAkc2NvcGUudHlwZVxuICAgICAgICAgICAgICAgICAgICAgICAgZml0VG9WaWV3OiBmYWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgbWF4V2lkdGg6ICcxMDAlJ1xuICAgICAgICAgICAgICAgICAgICAgICAgdHBsOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNsb3NlQnRuOiAnJydcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGEgdGl0bGU9J0ZlY2hhcicgY2xhc3M9J2ZhbmN5Ym94LWljb25lLWNsb3NlJyBocmVmPSdqYXZhc2NyaXB0OjsnPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0nZmFuY3lib3gtaWNvbmUtYmFja2dyb3VuZCc+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGkgY2xhc3M9J2ZhIGZhLXRpbWVzJz48L2k+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9hPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICcnJ1xuXG4gICAgICAgICAgICAgICAgJHNjb3BlLnR5cGUgPSAnaW1hZ2UnXG4gICAgICAgICAgICAgICAgJHRpbWVvdXQgc2V0RmFuY3lib3hcbiAgICAgICAgICAgICAgICAkc2NvcGUuJHdhdGNoICRhdHRycy5mYW5jeVR5cGUsICh0eXBlKSAtPlxuICAgICAgICAgICAgICAgICAgICBpZiB0eXBlXG4gICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUudHlwZSA9IHR5cGVcbiAgICAgICAgICAgICAgICAgICAgICAgIHNldEZhbmN5Ym94KClcblxuICAgICAgICB9XG4iXX0=