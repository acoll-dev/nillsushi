(function() {
  'use strict';
  angular.module('mainApp').directive('ellipsis', ["$rootScope", "$compile", "$window", "$timeout", function($rootScope, $compile, $window, $timeout) {
    return {
      restrict: 'A',
      scope: {
        rows: '=',
        suffix: '=',
        ngBind: '='
      },
      link: function($scope, $element, $attrs) {
        var $el, ajustText, ajustTimeout, defaults, ellipsis, getRows, newContent, noUnit, setHeight;
        newContent = '';
        ajustTimeout = $timeout(function() {});
        defaults = {
          rows: {
            xs: 0,
            sm: 0,
            md: 0,
            lg: 0
          },
          suffix: '...'
        };
        $el = void 0;
        ellipsis = function() {
          if (!$el) {
            $element.html('<p id="ellipsis-container" style="margin: 0; padding: 0;"></p>');
            $el = $element.children('#ellipsis-container').html(angular.copy($scope.ngBind));
          }
          if ($scope.limit && $scope.ngBind && $rootScope.GRIFFO.viewPort) {
            newContent = angular.copy($scope.ngBind).split(' ');
            $el.html(newContent.join(' '));
            setHeight();
            if (getRows() && getRows() > $scope.limit[$rootScope.GRIFFO.viewPort.bs] && $scope.limit[$rootScope.GRIFFO.viewPort.bs] !== 0) {
              $scope.ajusting = true;
              ajustText();
            }
          }
        };
        ajustText = function() {
          $timeout.cancel(ajustTimeout);
          return ajustTimeout = $timeout(function() {
            newContent = newContent.slice(0, -1);
            $el.html(newContent.join(' ') + ($scope.suffix ? $scope.suffix : defaults.suffix));
            if (getRows() > $scope.limit[$rootScope.GRIFFO.viewPort.bs]) {
              return ajustText();
            } else {
              $scope.ajusting = false;
              return setHeight();
            }
          });
        };
        getRows = function() {
          if (noUnit($el.css('height'))) {
            return parseInt(noUnit($el.css('height')) / parseInt(noUnit($el.css('line-height'))));
          } else {
            return false;
          }
        };
        noUnit = function(str) {
          return Number(str.split('px')[0].split('%')[0]);
        };
        setHeight = function() {
          if ($scope.limit[$rootScope.GRIFFO.viewPort.bs] !== 0) {
            $element.css('height', $scope.limit[$rootScope.GRIFFO.viewPort.bs] * noUnit($el.css('line-height')) + noUnit($element.css('padding-top')) + noUnit($element.css('padding-bottom'))).css('overflow', 'hidden');
          } else {
            $element.css('height', '').css('overflow', 'visible');
          }
        };
        $scope.limit = defaults.rows;
        $scope.$watch('ngBind', ellipsis);
        $scope.$watch('rows', function(rows) {
          var newRows;
          if (rows) {
            if (angular.isObject(rows)) {
              if (!$scope.limit) {
                $scope.limit = {};
              }
              angular.forEach(defaults.rows, function(r, id) {
                if (angular.isDefined(rows[id])) {
                  $scope.limit[id] = rows[id];
                }
              });
            } else {
              newRows = {};
              angular.forEach(defaults.rows, function(r, id) {
                newRows[id] = rows;
              });
              $scope.limit = newRows;
            }
            return ellipsis();
          }
        });
        $scope.$watch('suffix', ellipsis);
        return $rootScope.$watch('GRIFFO.viewPort.width', function() {
          return $timeout(ellipsis, 100);
        });
      }
    };
  }]);

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDMvY29mZmVlL2RpcmVjdGl2ZXMvZWxsaXBzaXNEcnQuY29mZmVlIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0VBQUE7RUFDQSxPQUFPLENBQUMsTUFBUixDQUFlLFNBQWYsQ0FDSSxDQUFDLFNBREwsQ0FDZSxVQURmLEVBQzJCLFNBQUMsVUFBRCxFQUFhLFFBQWIsRUFBdUIsT0FBdkIsRUFBZ0MsUUFBaEM7V0FDbkI7TUFDSSxRQUFBLEVBQVUsR0FEZDtNQUVJLEtBQUEsRUFDSTtRQUFBLElBQUEsRUFBTSxHQUFOO1FBQ0EsTUFBQSxFQUFRLEdBRFI7UUFFQSxNQUFBLEVBQVEsR0FGUjtPQUhSO01BTUksSUFBQSxFQUFNLFNBQUMsTUFBRCxFQUFTLFFBQVQsRUFBbUIsTUFBbkI7QUFDRixZQUFBO1FBQUEsVUFBQSxHQUFhO1FBQ2IsWUFBQSxHQUFlLFFBQUEsQ0FBUyxTQUFBLEdBQUEsQ0FBVDtRQUNmLFFBQUEsR0FDSTtVQUFBLElBQUEsRUFDSTtZQUFBLEVBQUEsRUFBSSxDQUFKO1lBQ0EsRUFBQSxFQUFJLENBREo7WUFFQSxFQUFBLEVBQUksQ0FGSjtZQUdBLEVBQUEsRUFBSSxDQUhKO1dBREo7VUFLQSxNQUFBLEVBQVEsS0FMUjs7UUFNSixHQUFBLEdBQU07UUFFTixRQUFBLEdBQVcsU0FBQTtVQUNQLElBQUcsQ0FBQyxHQUFKO1lBQ0ksUUFBUSxDQUFDLElBQVQsQ0FBYyxnRUFBZDtZQUNBLEdBQUEsR0FBTSxRQUFRLENBQUMsUUFBVCxDQUFrQixxQkFBbEIsQ0FBd0MsQ0FBQyxJQUF6QyxDQUE4QyxPQUFPLENBQUMsSUFBUixDQUFhLE1BQU0sQ0FBQyxNQUFwQixDQUE5QyxFQUZWOztVQUdBLElBQUcsTUFBTSxDQUFDLEtBQVAsSUFBaUIsTUFBTSxDQUFDLE1BQXhCLElBQW1DLFVBQVUsQ0FBQyxNQUFNLENBQUMsUUFBeEQ7WUFDSSxVQUFBLEdBQWEsT0FBTyxDQUFD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QjtRQUNBLE1BQU0sQ0FBQyxNQUFQLENBQWMsTUFBZCxFQUFzQixTQUFDLElBQUQ7QUFDbEIsY0FBQTtVQUFBLElBQUcsSUFBSDtZQUNJLElBQUcsT0FBTyxDQUFDLFFBQVIsQ0FBaUIsSUFBakIsQ0FBSDtjQUNJLElBQUcsQ0FBQyxNQUFNLENBQUMsS0FBWDtnQkFDSSxNQUFNLENBQUMsS0FBUCxHQUFlLEdBRG5COztjQUVBLE9BQU8sQ0FBQyxPQUFSLENBQWdCLFFBQVEsQ0FBQyxJQUF6QixFQUErQixTQUFDLENBQUQsRUFBSSxFQUFKO2dCQUMzQixJQUFHLE9BQU8sQ0FBQyxTQUFSLENBQWtCLElBQUssQ0FBQSxFQUFBLENBQXZCLENBQUg7a0JBQ0ksTUFBTSxDQUFDLEtBQU0sQ0FBQSxFQUFBLENBQWIsR0FBbUIsSUFBSyxDQUFBLEVBQUEsRUFENUI7O2NBRDJCLENBQS9CLEVBSEo7YUFBQSxNQUFBO2NBUUksT0FBQSxHQUFVO2NBQ1YsT0FBTyxDQUFDLE9BQVIsQ0FBZ0IsUUFBUSxDQUFDLElBQXpCLEVBQStCLFNBQUMsQ0FBRCxFQUFJLEVBQUo7Z0JBQzNCLE9BQVEsQ0FBQSxFQUFBLENBQVIsR0FBYztjQURhLENBQS9CO2NBR0EsTUFBTSxDQUFDLEtBQVAsR0FBZSxRQVpuQjs7bUJBYUEsUUFBQSxDQUFBLEVBZEo7O1FBRGtCLENBQXRCO1FBaUJBLE1BQU0sQ0FBQyxNQUFQLENBQWMsUUFBZCxFQUF3QixRQUF4QjtlQUNBLFVBQVUsQ0FBQyxNQUFYLENBQWtCLHVCQUFsQixFQUEyQyxTQUFBO2lCQUN2QyxRQUFBLENBQVMsUUFBVCxFQUFtQixHQUFuQjtRQUR1QyxDQUEzQztNQXRFRSxDQU5WOztFQURtQixDQUQzQjtBQURBIiwiZmlsZSI6ImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvbW9kZWxvMDMvanMvZGlyZWN0aXZlcy9lbGxpcHNpc0RydC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUgJ21haW5BcHAnXG4gICAgLmRpcmVjdGl2ZSAnZWxsaXBzaXMnLCAoJHJvb3RTY29wZSwgJGNvbXBpbGUsICR3aW5kb3csICR0aW1lb3V0KSAtPlxuICAgICAgICB7XG4gICAgICAgICAgICByZXN0cmljdDogJ0EnXG4gICAgICAgICAgICBzY29wZTpcbiAgICAgICAgICAgICAgICByb3dzOiAnPSdcbiAgICAgICAgICAgICAgICBzdWZmaXg6ICc9J1xuICAgICAgICAgICAgICAgIG5nQmluZDogJz0nXG4gICAgICAgICAgICBsaW5rOiAoJHNjb3BlLCAkZWxlbWVudCwgJGF0dHJzKSAtPlxuICAgICAgICAgICAgICAgIG5ld0NvbnRlbnQgPSAnJ1xuICAgICAgICAgICAgICAgIGFqdXN0VGltZW91dCA9ICR0aW1lb3V0KC0+KVxuICAgICAgICAgICAgICAgIGRlZmF1bHRzID1cbiAgICAgICAgICAgICAgICAgICAgcm93czpcbiAgICAgICAgICAgICAgICAgICAgICAgIHhzOiAwXG4gICAgICAgICAgICAgICAgICAgICAgICBzbTogMFxuICAgICAgICAgICAgICAgICAgICAgICAgbWQ6IDBcbiAgICAgICAgICAgICAgICAgICAgICAgIGxnOiAwXG4gICAgICAgICAgICAgICAgICAgIHN1ZmZpeDogJy4uLidcbiAgICAgICAgICAgICAgICAkZWwgPSB1bmRlZmluZWRcblxuICAgICAgICAgICAgICAgIGVsbGlwc2lzID0gLT5cbiAgICAgICAgICAgICAgICAgICAgaWYgISRlbFxuICAgICAgICAgICAgICAgICAgICAgICAgJGVsZW1lbnQuaHRtbCAnPHAgaWQ9XCJlbGxpcHNpcy1jb250YWluZXJcIiBzdHlsZT1cIm1hcmdpbjogMDsgcGFkZGluZzogMDtcIj48L3A+J1xuICAgICAgICAgICAgICAgICAgICAgICAgJGVsID0gJGVsZW1lbnQuY2hpbGRyZW4oJyNlbGxpcHNpcy1jb250YWluZXInKS5odG1sKGFuZ3VsYXIuY29weSgkc2NvcGUubmdCaW5kKSlcbiAgICAgICAgICAgICAgICAgICAgaWYgJHNjb3BlLmxpbWl0IGFuZCAkc2NvcGUubmdCaW5kIGFuZCAkcm9vdFNjb3BlLkdSSUZGTy52aWV3UG9ydFxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3Q29udGVudCA9IGFuZ3VsYXIuY29weSgkc2NvcGUubmdCaW5kKS5zcGxpdCgnICcpXG4gICAgICAgICAgICAgICAgICAgICAgICAkZWwuaHRtbCBuZXdDb250ZW50LmpvaW4oJyAnKVxuICAgICAgICAgICAgICAgICAgICAgICAgc2V0SGVpZ2h0KClcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIGdldFJvd3MoKSBhbmQgZ2V0Um93cygpID4gJHNjb3BlLmxpbWl0WyRyb290U2NvcGUuR1JJRkZPLnZpZXdQb3J0LmJzXSBhbmQgJHNjb3BlLmxpbWl0WyRyb290U2NvcGUuR1JJRkZPLnZpZXdQb3J0LmJzXSAhPSAwXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmFqdXN0aW5nID0gdHJ1ZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFqdXN0VGV4dCgpXG4gICAgICAgICAgICAgICAgICAgIHJldHVyblxuXG4gICAgICAgICAgICAgICAgYWp1c3RUZXh0ID0gLT5cbiAgICAgICAgICAgICAgICAgICAgJHRpbWVvdXQuY2FuY2VsIGFqdXN0VGltZW91dFxuICAgICAgICAgICAgICAgICAgICBhanVzdFRpbWVvdXQgPSAkdGltZW91dCgtPlxuICAgICAgICAgICAgICAgICAgICAgICAgbmV3Q29udGVudCA9IG5ld0NvbnRlbnQuc2xpY2UoMCwgLTEpXG4gICAgICAgICAgICAgICAgICAgICAgICAkZWwuaHRtbCBuZXdDb250ZW50LmpvaW4oJyAnKSArIChpZiAkc2NvcGUuc3VmZml4IHRoZW4gJHNjb3BlLnN1ZmZpeCBlbHNlIGRlZmF1bHRzLnN1ZmZpeClcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIGdldFJvd3MoKSA+ICRzY29wZS5saW1pdFskcm9vdFNjb3BlLkdSSUZGTy52aWV3UG9ydC5ic11cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBhanVzdFRleHQoKVxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICRzY29wZS5hanVzdGluZyA9IGZhbHNlXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2V0SGVpZ2h0KClcbiAgICAgICAgICAgICAgICAgICAgKVxuXG4gICAgICAgICAgICAgICAgZ2V0Um93cyA9IC0+XG4gICAgICAgICAgICAgICAgICAgIGlmIG5vVW5pdCgkZWwuY3NzKCdoZWlnaHQnKSkgdGhlbiBwYXJzZUludChub1VuaXQoJGVsLmNzcygnaGVpZ2h0JykpIC8gcGFyc2VJbnQobm9Vbml0KCRlbC5jc3MoJ2xpbmUtaGVpZ2h0JykpKSkgZWxzZSBmYWxzZVxuXG4gICAgICAgICAgICAgICAgbm9Vbml0ID0gKHN0cikgLT5cbiAgICAgICAgICAgICAgICAgICAgTnVtYmVyIHN0ci5zcGxpdCgncHgnKVswXS5zcGxpdCgnJScpWzBdXG5cbiAgICAgICAgICAgICAgICBzZXRIZWlnaHQgPSAtPlxuICAgICAgICAgICAgICAgICAgICBpZiAkc2NvcGUubGltaXRbJHJvb3RTY29wZS5HUklGRk8udmlld1BvcnQuYnNdICE9IDBcbiAgICAgICAgICAgICAgICAgICAgICAgICRlbGVtZW50LmNzcygnaGVpZ2h0JywgJHNjb3BlLmxpbWl0WyRyb290U2NvcGUuR1JJRkZPLnZpZXdQb3J0LmJzXSAqIG5vVW5pdCgkZWwuY3NzKCdsaW5lLWhlaWdodCcpKSArIG5vVW5pdCgkZWxlbWVudC5jc3MoJ3BhZGRpbmctdG9wJykpICsgbm9Vbml0KCRlbGVtZW50LmNzcygncGFkZGluZy1ib3R0b20nKSkpLmNzcyAnb3ZlcmZsb3cnLCAnaGlkZGVuJ1xuICAgICAgICAgICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgICAgICAgICAkZWxlbWVudC5jc3MoJ2hlaWdodCcsICcnKS5jc3MgJ292ZXJmbG93JywgJ3Zpc2libGUnXG4gICAgICAgICAgICAgICAgICAgIHJldHVyblxuXG4gICAgICAgICAgICAgICAgJHNjb3BlLmxpbWl0ID0gZGVmYXVsdHMucm93c1xuICAgICAgICAgICAgICAgICRzY29wZS4kd2F0Y2ggJ25nQmluZCcsIGVsbGlwc2lzXG4gICAgICAgICAgICAgICAgJHNjb3BlLiR3YXRjaCAncm93cycsIChyb3dzKSAtPlxuICAgICAgICAgICAgICAgICAgICBpZiByb3dzXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiBhbmd1bGFyLmlzT2JqZWN0KHJvd3MpXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgISRzY29wZS5saW1pdFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUubGltaXQgPSB7fVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCBkZWZhdWx0cy5yb3dzLCAociwgaWQpIC0+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIGFuZ3VsYXIuaXNEZWZpbmVkKHJvd3NbaWRdKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmxpbWl0W2lkXSA9IHJvd3NbaWRdXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVyblxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld1Jvd3MgPSB7fVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCBkZWZhdWx0cy5yb3dzLCAociwgaWQpIC0+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld1Jvd3NbaWRdID0gcm93c1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUubGltaXQgPSBuZXdSb3dzXG4gICAgICAgICAgICAgICAgICAgICAgICBlbGxpcHNpcygpXG5cbiAgICAgICAgICAgICAgICAkc2NvcGUuJHdhdGNoICdzdWZmaXgnLCBlbGxpcHNpc1xuICAgICAgICAgICAgICAgICRyb290U2NvcGUuJHdhdGNoICdHUklGRk8udmlld1BvcnQud2lkdGgnLCAtPlxuICAgICAgICAgICAgICAgICAgICAkdGltZW91dCBlbGxpcHNpcywgMTAwXG4gICAgICAgIH1cbiJdfQ==