(function() {
  'use strict';
  angular.module('mainApp').controller('finishCtrl', ["$rootScope", "$scope", "$localStorage", "$grRestful", "$grModal", "$grAlert", "$cidadeEstado", "$timeout", function($rootScope, $scope, $localStorage, $grRestful, $grModal, $grAlert, $cidadeEstado, $timeout) {
    var alert, cartEmpty, finished;
    alert = $grAlert["new"]();
    finished = false;
    cartEmpty = function(url) {
      alert.show('info', 'Sua lista de pedidos está vazia, você será redirecionado!');
      return $timeout(function() {
        return location.href = $rootScope.GRIFFO.curAlias + '/' + (url ? url : '');
      }, 5000);
    };
    if ($rootScope.gr.cart.length() === 0) {
      return cartEmpty();
    } else {
      $rootScope.gr.title = {
        icon: 'fa fa-fw fa-check',
        text: 'Finalizar pedido'
      };
      $scope.payments = ['Dinheiro', 'Cartão de Crédito/Débito'];
      $scope.addresses = [$rootScope.GRIFFO.user.address + ', ' + $rootScope.GRIFFO.user.number + ($rootScope.GRIFFO.user.complement ? ', ' + $rootScope.GRIFFO.user.complement : '') + ', ' + $rootScope.GRIFFO.user.district, 'Outro...'];
      $scope.shops = [];
      $grRestful.find({
        module: 'shop',
        action: 'select'
      }).then(function(r) {
        if (r.response) {
          return $scope.shops = r.response;
        }
      });
      $scope.states = [];
      angular.forEach($cidadeEstado.get.estados(), function(e) {
        $scope.states.push({
          value: e[0],
          label: e[1]
        });
      });
      delete $localStorage.griffo_cart_ready;
      $scope.formSettings = {
        data: {
          status: 0,
          fetch: true,
          date: new Date,
          time: moment(new Date).format("HH:mm"),
          formpayment: 'Dinheiro',
          address: $scope.addresses[0],
          state: $rootScope.GRIFFO.user.state,
          city: $rootScope.GRIFFO.user.city,
          fkidshop: $rootScope.GRIFFO.user.fkidshop
        },
        schema: [
          {
            property: 'fkidshop',
            type: 'select',
            label: 'Loja',
            list: 'item.value as item.label for item in shops',
            columns: 12,
            attr: {
              required: true,
              ngShow: 'shops.length > 2'
            },
            msgs: {
              required: 'A loja é obrigatória'
            }
          }, {
            property: 'date',
            type: 'date',
            label: 'Data do pedido',
            columns: 6,
            attr: {
              required: true,
              min: moment(new Date).format("YYYY-MM-DD")
            },
            msgs: {
              required: 'A data é obrigatória',
              min: "Precisa ser " + (moment(new Date).format("DD/MM/YYYY")) + " ou depois"
            }
          }, {
            property: 'time',
            type: 'text',
            label: 'Hora do pedido',
            columns: 6,
            attr: {
              required: true,
              uiTimeMask: "short"
            },
            msgs: {
              required: 'A hora é obrigatória',
              time: 'A hora é inválida'
            }
          }, {
            property: 'fetch',
            type: 'checkbox',
            label: 'Vai buscar?',
            columns: 12,
            attr: {
              disabled: true
            }
          }
        ],
        submit: function(data) {
          var order, time;
          if (!$scope.form.$valid) {
            return;
          }
          time = data.time.split(':');
          order = {
            fkidclient: $rootScope.GRIFFO.user.id,
            fetch: data.fetch,
            subtotal: $rootScope.gr.cart.total(),
            fkidshop: data.fkidshop,
            created: moment(data.date).hour(time[0]).minute(time[1]).second(0).format('YYYY-MM-DD HH:mm:ss'),
            products: []
          };
          angular.forEach($rootScope.gr.cart.items, function(item) {
            if (item.count > 0) {
              return order.products.push({
                idproduct: item.id,
                fkidshop: data.fkidshop,
                quantity: item.count
              });
            }
          });
          if (!data.fetch) {
            order.formpayment = data.formpayment ? data.formpayment : void 0;
            order.change = data.change ? data.change : void 0;
            if (data.address === 'Outro...') {
              order.address = data.address1;
              order.number = data.number;
              order.complement = data.complement;
              order.district = data.district;
              order.city = data.city;
              order.state = data.state;
            } else {
              order.address = $rootScope.GRIFFO.user.address;
              order.number = $rootScope.GRIFFO.user.number;
              order.complement = $rootScope.GRIFFO.user.complement;
              order.district = $rootScope.GRIFFO.user.district;
              order.city = $rootScope.GRIFFO.user.city;
              order.state = $rootScope.GRIFFO.user.state;
            }
          }
          return $grRestful.create({
            module: 'order',
            action: 'insert',
            post: order
          }).then(function(r) {
            if (r.response) {
              finished = true;
              $scope.form.reset();
              $rootScope.gr.cart.clear();
              alert.show(r.status, 'Seu pedido foi enviado com sucesso, você será redirecionado para acompanhar seu pedido.');
              $timeout((function() {
                location.href = $rootScope.GRIFFO.curAlias + '/user';
              }), 5000);
            } else {
              alert.show(r.status, r.message);
            }
          }, function() {
            return alert.show('danger', 'ERROR.FATAL');
          });
        }
      };
      $scope.$watch('formSettings.data.state', function(e) {
        if (e) {
          if ($scope.formSettings.data.state) {
            $scope.cities = [];
            angular.forEach($cidadeEstado.get.cidades($scope.formSettings.data.state), function(c) {
              $scope.cities.push({
                value: c,
                label: c
              });
            });
          } else {
            $scope.cities = [];
          }
          if ($scope.formSettings.data.state === 'SP') {
            return $scope.formSettings.data.city = 'Itapeva';
          } else {
            return $scope.formSettings.data.city = void 0;
          }
        } else {
          return $scope.formSettings.data.city = void 0;
        }
      });
      $scope.$watch(function() {
        return $rootScope.gr.cart.length();
      }, function() {
        if ($rootScope.gr.cart.length() === 0 && !finished) {
          return cartEmpty();
        }
      });
      return $scope.$watch('form', function(form) {
        return $rootScope.finishForm = form;
      });
    }
  }]);

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvY3JhYi9jb2ZmZWUvY29udHJvbGxlcnMvZmluaXNoQ3RybC5jb2ZmZWUiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7RUFBQTtFQUNBLE9BQU8sQ0FBQyxNQUFSLENBQWUsU0FBZixDQUF5QixDQUFDLFVBQTFCLENBQXFDLFlBQXJDLEVBQW1ELFNBQUMsVUFBRCxFQUFhLE1BQWIsRUFBcUIsYUFBckIsRUFBb0MsVUFBcEMsRUFBZ0QsUUFBaEQsRUFBMEQsUUFBMUQsRUFBb0UsYUFBcEUsRUFBbUYsUUFBbkY7QUFFL0MsUUFBQTtJQUFBLEtBQUEsR0FBUSxRQUFRLENBQUMsS0FBRCxDQUFSLENBQUE7SUFDUixRQUFBLEdBQVc7SUFFWCxTQUFBLEdBQVksU0FBQyxHQUFEO01BQ1IsS0FBSyxDQUFDLElBQU4sQ0FBVyxNQUFYLEVBQW1CLDJEQUFuQjthQUNBLFFBQUEsQ0FBUyxTQUFBO2VBQ0wsUUFBUSxDQUFDLElBQVQsR0FBZ0IsVUFBVSxDQUFDLE1BQU0sQ0FBQyxRQUFsQixHQUE2QixHQUE3QixHQUFtQyxDQUFJLEdBQUgsR0FBWSxHQUFaLEdBQXFCLEVBQXRCO01BRDlDLENBQVQsRUFFRSxJQUZGO0lBRlE7SUFNWixJQUFHLFVBQVUsQ0FBQyxFQUFFLENBQUMsSUFBSSxDQUFDLE1BQW5CLENBQUEsQ0FBQSxLQUErQixDQUFsQzthQUNJLFNBQUEsQ0FBQSxFQURKO0tBQUEsTUFBQTtNQUdJLFVBQVUsQ0FBQyxFQUFFLENBQUMsS0FBZCxHQUNJO1FBQUEsSUFBQSxFQUFNLG1CQUFOO1FBQ0EsSUFBQSxFQUFNLGtCQUROOztNQUVKLE1BQU0sQ0FBQyxRQUFQLEdBQWtCLENBQ2QsVUFEYyxFQUVkLDBCQUZjO01BSWxCLE1BQU0sQ0FBQyxTQUFQLEdBQW1CLENBQ2YsVUFBVSxDQUFDLE1BQU0sQ0FBQyxJQUFJLENBQUMsT0FBdkIsR0FBaUMsSUFBakMsR0FBd0MsVUFBVSxDQUFDLE1BQU0sQ0FB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RHVFLENBQTNFLEVBRko7V0FBQSxNQUFBO1lBUUksTUFBTSxDQUFDLE1BQVAsR0FBZ0IsR0FScEI7O1VBU0EsSUFBRyxNQUFNLENBQUMsWUFBWSxDQUFDLElBQUksQ0FBQyxLQUF6QixLQUFrQyxJQUFyQzttQkFDSSxNQUFNLENBQUMsWUFBWSxDQUFDLElBQUksQ0FBQyxJQUF6QixHQUFnQyxVQURwQztXQUFBLE1BQUE7bUJBR0ksTUFBTSxDQUFDLFlBQVksQ0FBQyxJQUFJLENBQUMsSUFBekIsR0FBZ0MsT0FIcEM7V0FWSjtTQUFBLE1BQUE7aUJBZUksTUFBTSxDQUFDLFlBQVksQ0FBQyxJQUFJLENBQUMsSUFBekIsR0FBZ0MsT0FmcEM7O01BRHFDLENBQXpDO01Ba0JBLE1BQU0sQ0FBQyxNQUFQLENBQWMsU0FBQTtlQUNWLFVBQVUsQ0FBQyxFQUFFLENBQUMsSUFBSSxDQUFDLE1BQW5CLENBQUE7TUFEVSxDQUFkLEVBRUUsU0FBQTtRQUNFLElBQWUsVUFBVSxDQUFDLEVBQUUsQ0FBQyxJQUFJLENBQUMsTUFBbkIsQ0FBQSxDQUFBLEtBQStCLENBQS9CLElBQXFDLENBQUMsUUFBckQ7aUJBQUEsU0FBQSxDQUFBLEVBQUE7O01BREYsQ0FGRjthQUtBLE1BQU0sQ0FBQyxNQUFQLENBQWMsTUFBZCxFQUFzQixTQUFDLElBQUQ7ZUFDbEIsVUFBVSxDQUFDLFVBQVgsR0FBd0I7TUFETixDQUF0QixFQXZQSjs7RUFYK0MsQ0FBbkQ7QUFEQSIsImZpbGUiOiJjbGllbnQvbGF5ZXJzL3dlYnNpdGUvdGVtcGxhdGVzL2NyYWIvanMvY29udHJvbGxlcnMvZmluaXNoQ3RybC5qcyIsInNvdXJjZVJvb3QiOiIvc291cmNlLyIsInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0J1xuYW5ndWxhci5tb2R1bGUoJ21haW5BcHAnKS5jb250cm9sbGVyICdmaW5pc2hDdHJsJywgKCRyb290U2NvcGUsICRzY29wZSwgJGxvY2FsU3RvcmFnZSwgJGdyUmVzdGZ1bCwgJGdyTW9kYWwsICRnckFsZXJ0LCAkY2lkYWRlRXN0YWRvLCAkdGltZW91dCkgLT5cblxuICAgIGFsZXJ0ID0gJGdyQWxlcnQubmV3KClcbiAgICBmaW5pc2hlZCA9IGZhbHNlXG5cbiAgICBjYXJ0RW1wdHkgPSAodXJsKSAtPlxuICAgICAgICBhbGVydC5zaG93ICdpbmZvJywgJ1N1YSBsaXN0YSBkZSBwZWRpZG9zIGVzdMOhIHZhemlhLCB2b2PDqiBzZXLDoSByZWRpcmVjaW9uYWRvISdcbiAgICAgICAgJHRpbWVvdXQgLT5cbiAgICAgICAgICAgIGxvY2F0aW9uLmhyZWYgPSAkcm9vdFNjb3BlLkdSSUZGTy5jdXJBbGlhcyArICcvJyArIChpZiB1cmwgdGhlbiB1cmwgZWxzZSAnJylcbiAgICAgICAgLCA1MDAwXG5cbiAgICBpZiAkcm9vdFNjb3BlLmdyLmNhcnQubGVuZ3RoKCkgPT0gMFxuICAgICAgICBjYXJ0RW1wdHkoKVxuICAgIGVsc2VcbiAgICAgICAgJHJvb3RTY29wZS5nci50aXRsZSA9XG4gICAgICAgICAgICBpY29uOiAnZmEgZmEtZncgZmEtY2hlY2snXG4gICAgICAgICAgICB0ZXh0OiAnRmluYWxpemFyIHBlZGlkbydcbiAgICAgICAgJHNjb3BlLnBheW1lbnRzID0gW1xuICAgICAgICAgICAgJ0RpbmhlaXJvJ1xuICAgICAgICAgICAgJ0NhcnTDo28gZGUgQ3LDqWRpdG8vRMOpYml0bydcbiAgICAgICAgXVxuICAgICAgICAkc2NvcGUuYWRkcmVzc2VzID0gW1xuICAgICAgICAgICAgJHJvb3RTY29wZS5HUklGRk8udXNlci5hZGRyZXNzICsgJywgJyArICRyb290U2NvcGUuR1JJRkZPLnVzZXIubnVtYmVyICsgKGlmICRyb290U2NvcGUuR1JJRkZPLnVzZXIuY29tcGxlbWVudCB0aGVuICcsICcgKyAkcm9vdFNjb3BlLkdSSUZGTy51c2VyLmNvbXBsZW1lbnQgZWxzZSAnJykgKyAnLCAnICsgJHJvb3RTY29wZS5HUklGRk8udXNlci5kaXN0cmljdFxuICAgICAgICAgICAgJ091dHJvLi4uJ1xuICAgICAgICBdXG4gICAgICAgICRzY29wZS5zaG9wcyA9IFtdXG5cbiAgICAgICAgJGdyUmVzdGZ1bC5maW5kKFxuICAgICAgICAgICAgbW9kdWxlOiAnc2hvcCdcbiAgICAgICAgICAgIGFjdGlvbjogJ3NlbGVjdCcpLnRoZW4gKHIpIC0+XG4gICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgJHNjb3BlLnNob3BzID0gci5yZXNwb25zZVxuXG4gICAgICAgICRzY29wZS5zdGF0ZXMgPSBbXVxuXG4gICAgICAgIGFuZ3VsYXIuZm9yRWFjaCAkY2lkYWRlRXN0YWRvLmdldC5lc3RhZG9zKCksIChlKSAtPlxuICAgICAgICAgICAgJHNjb3BlLnN0YXRlcy5wdXNoXG4gICAgICAgICAgICAgICAgdmFsdWU6IGVbMF1cbiAgICAgICAgICAgICAgICBsYWJlbDogZVsxXVxuICAgICAgICAgICAgcmV0dXJuXG5cbiAgICAgICAgZGVsZXRlICRsb2NhbFN0b3JhZ2UuZ3JpZmZvX2NhcnRfcmVhZHlcblxuICAgICAgICAkc2NvcGUuZm9ybVNldHRpbmdzID1cbiAgICAgICAgICAgIGRhdGE6XG4gICAgICAgICAgICAgICAgc3RhdHVzOiAwXG4gICAgICAgICAgICAgICAgZmV0Y2g6IHllc1xuICAgICAgICAgICAgICAgIGRhdGU6IG5ldyBEYXRlXG4gICAgICAgICAgICAgICAgdGltZTogbW9tZW50KG5ldyBEYXRlKS5mb3JtYXQoXCJISDptbVwiKVxuICAgICAgICAgICAgICAgICMgdGltZTogJzAwOjAwJ1xuICAgICAgICAgICAgICAgIGZvcm1wYXltZW50OiAnRGluaGVpcm8nXG4gICAgICAgICAgICAgICAgYWRkcmVzczogJHNjb3BlLmFkZHJlc3Nlc1swXVxuICAgICAgICAgICAgICAgIHN0YXRlOiAkcm9vdFNjb3BlLkdSSUZGTy51c2VyLnN0YXRlXG4gICAgICAgICAgICAgICAgY2l0eTogJHJvb3RTY29wZS5HUklGRk8udXNlci5jaXR5XG4gICAgICAgICAgICAgICAgZmtpZHNob3A6ICRyb290U2NvcGUuR1JJRkZPLnVzZXIuZmtpZHNob3BcbiAgICAgICAgICAgIHNjaGVtYTogW1xuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdma2lkc2hvcCdcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3NlbGVjdCdcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdMb2phJ1xuICAgICAgICAgICAgICAgICAgICBsaXN0OiAnaXRlbS52YWx1ZSBhcyBpdGVtLmxhYmVsIGZvciBpdGVtIGluIHNob3BzJ1xuICAgICAgICAgICAgICAgICAgICBjb2x1bW5zOiAxMlxuICAgICAgICAgICAgICAgICAgICBhdHRyOiByZXF1aXJlZDogeWVzLCBuZ1Nob3c6ICdzaG9wcy5sZW5ndGggPiAyJ1xuICAgICAgICAgICAgICAgICAgICBtc2dzOiByZXF1aXJlZDogJ0EgbG9qYSDDqSBvYnJpZ2F0w7NyaWEnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAnZGF0ZSdcbiAgICAgICAgICAgICAgICAgIHR5cGU6ICdkYXRlJ1xuICAgICAgICAgICAgICAgICAgbGFiZWw6ICdEYXRhIGRvIHBlZGlkbydcbiAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDZcbiAgICAgICAgICAgICAgICAgIGF0dHI6IHJlcXVpcmVkOiB5ZXMsIG1pbjogbW9tZW50KG5ldyBEYXRlKS5mb3JtYXQoXCJZWVlZLU1NLUREXCIpXG4gICAgICAgICAgICAgICAgICBtc2dzOiByZXF1aXJlZDogJ0EgZGF0YSDDqSBvYnJpZ2F0w7NyaWEnLCBtaW46IFwiUHJlY2lzYSBzZXIgI3ttb21lbnQobmV3IERhdGUpLmZvcm1hdChcIkREL01NL1lZWVlcIil9IG91IGRlcG9pc1wiXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAndGltZSdcbiAgICAgICAgICAgICAgICAgIHR5cGU6ICd0ZXh0J1xuICAgICAgICAgICAgICAgICAgbGFiZWw6ICdIb3JhIGRvIHBlZGlkbydcbiAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDZcbiAgICAgICAgICAgICAgICAjICAgYXR0cjogcmVxdWlyZWQ6IHllcywgdWlUaW1lTWFzazogXCJzaG9ydFwiLCBuZ1BhdHRlcm46IFwiL14oMFswLTldfDFbMC05XXwyWzAtM10pOlswLTVdWzAtOV0kL2QrXCJcbiAgICAgICAgICAgICAgICAgIGF0dHI6IHJlcXVpcmVkOiB5ZXMsIHVpVGltZU1hc2s6IFwic2hvcnRcIlxuICAgICAgICAgICAgICAgICAgbXNnczogcmVxdWlyZWQ6ICdBIGhvcmEgw6kgb2JyaWdhdMOzcmlhJywgdGltZTogJ0EgaG9yYSDDqSBpbnbDoWxpZGEnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdmZXRjaCdcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ2NoZWNrYm94J1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ1ZhaSBidXNjYXI/J1xuICAgICAgICAgICAgICAgICAgICBjb2x1bW5zOiAxMlxuICAgICAgICAgICAgICAgICAgICBhdHRyOiBkaXNhYmxlZDogeWVzXG4gICAgICAgICAgICAgICAgICAgICMgY29sdW1uczogNlxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAjIHtcbiAgICAgICAgICAgICAgICAjICAgICBwcm9wZXJ0eTogJ2Zvcm1wYXltZW50J1xuICAgICAgICAgICAgICAgICMgICAgIHR5cGU6ICdzZWxlY3QnXG4gICAgICAgICAgICAgICAgIyAgICAgbGFiZWw6ICdGb3JtYSBkZSBwYWdhbWVudG8nXG4gICAgICAgICAgICAgICAgIyAgICAgbGlzdDogJ2l0ZW0gYXMgaXRlbSBmb3IgaXRlbSBpbiBwYXltZW50cydcbiAgICAgICAgICAgICAgICAjICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICMgICAgICAgICByZXF1aXJlZDogeWVzXG4gICAgICAgICAgICAgICAgIyAgICAgICAgIG5nSWY6ICchZm9ybVNldHRpbmdzLmRhdGEuZmV0Y2gnXG4gICAgICAgICAgICAgICAgIyAgICAgbXNnczogcmVxdWlyZWQ6ICdBIGZvcm1hIGRlIHBhZ2FtZW50byDDqSBvYnJpZ2F0w7NyaWEnXG4gICAgICAgICAgICAgICAgIyB9XG4gICAgICAgICAgICAgICAgIyB7XG4gICAgICAgICAgICAgICAgIyAgICAgcHJvcGVydHk6ICdjaGFuZ2UnXG4gICAgICAgICAgICAgICAgIyAgICAgdHlwZTogJ21vbmV5J1xuICAgICAgICAgICAgICAgICMgICAgIGxhYmVsOiAnTEFCRUwuQ0hBTkdFLkZPUidcbiAgICAgICAgICAgICAgICAjICAgICBwbGFjZWhvbGRlcjogJzAsMDAnXG4gICAgICAgICAgICAgICAgIyAgICAgY29sdW1uczogNlxuICAgICAgICAgICAgICAgICMgICAgIGF0dHI6IG5nSWY6ICdmb3JtU2V0dGluZ3MuZGF0YS5mb3JtcGF5bWVudCA9PT0gXFwnRGluaGVpcm9cXCcgJiYgIWZvcm1TZXR0aW5ncy5kYXRhLmZldGNoJ1xuICAgICAgICAgICAgICAgICMgfVxuICAgICAgICAgICAgICAgICMge1xuICAgICAgICAgICAgICAgICMgICAgIHR5cGU6ICdocidcbiAgICAgICAgICAgICAgICAjICAgICBhdHRyOiAnbmctaWYnOiAnIWZvcm1TZXR0aW5ncy5kYXRhLmZldGNoJ1xuICAgICAgICAgICAgICAgICMgfVxuICAgICAgICAgICAgICAgICMge1xuICAgICAgICAgICAgICAgICMgICAgIHByb3BlcnR5OiAnYWRkcmVzcydcbiAgICAgICAgICAgICAgICAjICAgICB0eXBlOiAnc2VsZWN0J1xuICAgICAgICAgICAgICAgICMgICAgIGxhYmVsOiAnRW5kZXJlw6dvIGRlIGVudHJlZ2EnXG4gICAgICAgICAgICAgICAgIyAgICAgbGlzdDogJ2l0ZW0gYXMgaXRlbSBmb3IgaXRlbSBpbiBhZGRyZXNzZXMnXG4gICAgICAgICAgICAgICAgIyAgICAgYXR0cjpcbiAgICAgICAgICAgICAgICAjICAgICAgICAgcmVxdWlyZWQ6IHllc1xuICAgICAgICAgICAgICAgICMgICAgICAgICBuZ0lmOiAnIWZvcm1TZXR0aW5ncy5kYXRhLmZldGNoJ1xuICAgICAgICAgICAgICAgICMgICAgIG1zZ3M6IHJlcXVpcmVkOiAnTyBlbmRlcmXDp28gZGUgZW50cmVnYSDDqSBvYnJpZ2F0w7NyaW8nXG4gICAgICAgICAgICAgICAgIyB9XG4gICAgICAgICAgICAgICAgIyB7XG4gICAgICAgICAgICAgICAgIyAgICAgcHJvcGVydHk6ICdhZGRyZXNzMSdcbiAgICAgICAgICAgICAgICAjICAgICBsYWJlbDogJ05vdm8gZW5kZXJlw6dvJ1xuICAgICAgICAgICAgICAgICMgICAgIGNvbHVtbnM6IDhcbiAgICAgICAgICAgICAgICAjICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICMgICAgICAgICByZXF1aXJlZDogeWVzXG4gICAgICAgICAgICAgICAgIyAgICAgICAgIG5nSWY6ICdmb3JtU2V0dGluZ3MuZGF0YS5hZGRyZXNzID09PSBcXCdPdXRyby4uLlxcJyAmJiAhZm9ybVNldHRpbmdzLmRhdGEuZmV0Y2gnXG4gICAgICAgICAgICAgICAgIyAgICAgbXNnczogcmVxdWlyZWQ6ICdPIG5vdm8gZW5kZXJlw6dvIMOpIG9icmlnYXTDs3JpbydcbiAgICAgICAgICAgICAgICAjIH1cbiAgICAgICAgICAgICAgICAjIHtcbiAgICAgICAgICAgICAgICAjICAgICBwcm9wZXJ0eTogJ251bWJlcidcbiAgICAgICAgICAgICAgICAjICAgICB0eXBlOiAnbnVtYmVyJ1xuICAgICAgICAgICAgICAgICMgICAgIGxhYmVsOiAnTsO6bWVybydcbiAgICAgICAgICAgICAgICAjICAgICBjb2x1bW5zOiA0XG4gICAgICAgICAgICAgICAgIyAgICAgYXR0cjpcbiAgICAgICAgICAgICAgICAjICAgICAgICAgcmVxdWlyZWQ6IHllc1xuICAgICAgICAgICAgICAgICMgICAgICAgICBuZ0lmOiAnZm9ybVNldHRpbmdzLmRhdGEuYWRkcmVzcyA9PT0gXFwnT3V0cm8uLi5cXCcgJiYgIWZvcm1TZXR0aW5ncy5kYXRhLmZldGNoJ1xuICAgICAgICAgICAgICAgICMgICAgIG1zZ3M6IHJlcXVpcmVkOiAnTyBuw7ptZXJvIMOpIG9icmlnYXTDs3JpbydcbiAgICAgICAgICAgICAgICAjIH1cbiAgICAgICAgICAgICAgICAjIHtcbiAgICAgICAgICAgICAgICAjICAgICBwcm9wZXJ0eTogJ2NvbXBsZW1lbnQnXG4gICAgICAgICAgICAgICAgIyAgICAgbGFiZWw6ICdDb21wbGVtZW50bydcbiAgICAgICAgICAgICAgICAjICAgICBjb2x1bW5zOiA2XG4gICAgICAgICAgICAgICAgIyAgICAgYXR0cjogbmdJZjogJ2Zvcm1TZXR0aW5ncy5kYXRhLmFkZHJlc3MgPT09IFxcJ091dHJvLi4uXFwnICYmICFmb3JtU2V0dGluZ3MuZGF0YS5mZXRjaCdcbiAgICAgICAgICAgICAgICAjIH1cbiAgICAgICAgICAgICAgICAjIHtcbiAgICAgICAgICAgICAgICAjICAgICBwcm9wZXJ0eTogJ2Rpc3RyaWN0J1xuICAgICAgICAgICAgICAgICMgICAgIGxhYmVsOiAnQmFpcnJvJ1xuICAgICAgICAgICAgICAgICMgICAgIGNvbHVtbnM6IDZcbiAgICAgICAgICAgICAgICAjICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICMgICAgICAgICByZXF1aXJlZDogeWVzXG4gICAgICAgICAgICAgICAgIyAgICAgICAgIG5nSWY6ICdmb3JtU2V0dGluZ3MuZGF0YS5hZGRyZXNzID09PSBcXCdPdXRyby4uLlxcJyAmJiAhZm9ybVNldHRpbmdzLmRhdGEuZmV0Y2gnXG4gICAgICAgICAgICAgICAgIyAgICAgbXNnczogcmVxdWlyZWQ6ICdPIGJhaXJybyDDqSBvYnJpZ2F0w7NyaW8nXG4gICAgICAgICAgICAgICAgIyB9XG4gICAgICAgICAgICAgICAgIyB7XG4gICAgICAgICAgICAgICAgIyAgICAgcHJvcGVydHk6ICdzdGF0ZSdcbiAgICAgICAgICAgICAgICAjICAgICB0eXBlOiAnc2VsZWN0J1xuICAgICAgICAgICAgICAgICMgICAgIGxhYmVsOiAnRXN0YWRvJ1xuICAgICAgICAgICAgICAgICMgICAgIGNvbHVtbnM6IDZcbiAgICAgICAgICAgICAgICAjICAgICBsaXN0OiAnaXRlbS52YWx1ZSBhcyBpdGVtLmxhYmVsIGZvciBpdGVtIGluIHN0YXRlcydcbiAgICAgICAgICAgICAgICAjICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICMgICAgICAgICByZXF1aXJlZDogeWVzXG4gICAgICAgICAgICAgICAgIyAgICAgICAgIG5nSWY6ICdmb3JtU2V0dGluZ3MuZGF0YS5hZGRyZXNzID09PSBcXCdPdXRyby4uLlxcJyAmJiAhZm9ybVNldHRpbmdzLmRhdGEuZmV0Y2gnXG4gICAgICAgICAgICAgICAgIyAgICAgbXNnczogcmVxdWlyZWQ6ICdTZWxlY2lvbmUgdW0gZXN0YWRvJ1xuICAgICAgICAgICAgICAgICMgfVxuICAgICAgICAgICAgICAgICMge1xuICAgICAgICAgICAgICAgICMgICAgIHByb3BlcnR5OiAnY2l0eSdcbiAgICAgICAgICAgICAgICAjICAgICB0eXBlOiAnc2VsZWN0J1xuICAgICAgICAgICAgICAgICMgICAgIGxhYmVsOiAnQ2lkYWRlJ1xuICAgICAgICAgICAgICAgICMgICAgIGNvbHVtbnM6IDZcbiAgICAgICAgICAgICAgICAjICAgICBsaXN0OiAnaXRlbS52YWx1ZSBhcyBpdGVtLmxhYmVsIGZvciBpdGVtIGluIGNpdGllcydcbiAgICAgICAgICAgICAgICAjICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICMgICAgICAgICByZXF1aXJlZDogeWVzXG4gICAgICAgICAgICAgICAgIyAgICAgICAgIG5nSWY6ICdmb3JtU2V0dGluZ3MuZGF0YS5hZGRyZXNzID09PSBcXCdPdXRyby4uLlxcJyAmJiAhZm9ybVNldHRpbmdzLmRhdGEuZmV0Y2gnXG4gICAgICAgICAgICAgICAgIyAgICAgbXNnczogcmVxdWlyZWQ6ICdTZWxlY2lvbmUgdW1hIGNpZGFkZSdcbiAgICAgICAgICAgICAgICAjIH1cbiAgICAgICAgICAgIF1cbiAgICAgICAgICAgIHN1Ym1pdDogKGRhdGEpIC0+XG4gICAgICAgICAgICAgICAgcmV0dXJuIGlmICEkc2NvcGUuZm9ybS4kdmFsaWRcbiAgICAgICAgICAgICAgICB0aW1lID0gZGF0YS50aW1lLnNwbGl0KCc6JylcbiAgICAgICAgICAgICAgICBvcmRlciA9XG4gICAgICAgICAgICAgICAgICAgIGZraWRjbGllbnQ6ICRyb290U2NvcGUuR1JJRkZPLnVzZXIuaWRcbiAgICAgICAgICAgICAgICAgICAgZmV0Y2g6IGRhdGEuZmV0Y2hcbiAgICAgICAgICAgICAgICAgICAgc3VidG90YWw6ICRyb290U2NvcGUuZ3IuY2FydC50b3RhbCgpXG4gICAgICAgICAgICAgICAgICAgIGZraWRzaG9wOiBkYXRhLmZraWRzaG9wXG4gICAgICAgICAgICAgICAgICAgIGNyZWF0ZWQ6IG1vbWVudChkYXRhLmRhdGUpLmhvdXIodGltZVswXSkubWludXRlKHRpbWVbMV0pLnNlY29uZCgwKS5mb3JtYXQoJ1lZWVktTU0tREQgSEg6bW06c3MnKVxuICAgICAgICAgICAgICAgICAgICBwcm9kdWN0czogW11cblxuICAgICAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCAkcm9vdFNjb3BlLmdyLmNhcnQuaXRlbXMsIChpdGVtKSAtPlxuICAgICAgICAgICAgICAgICAgICBpZiBpdGVtLmNvdW50ID4gMFxuICAgICAgICAgICAgICAgICAgICAgICAgb3JkZXIucHJvZHVjdHMucHVzaFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlkcHJvZHVjdDogaXRlbS5pZFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGZraWRzaG9wOiBkYXRhLmZraWRzaG9wXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcXVhbnRpdHk6IGl0ZW0uY291bnRcblxuICAgICAgICAgICAgICAgIGlmICFkYXRhLmZldGNoXG4gICAgICAgICAgICAgICAgICAgIG9yZGVyLmZvcm1wYXltZW50ID0gaWYgZGF0YS5mb3JtcGF5bWVudCB0aGVuIGRhdGEuZm9ybXBheW1lbnQgZWxzZSB1bmRlZmluZWRcbiAgICAgICAgICAgICAgICAgICAgb3JkZXIuY2hhbmdlID0gaWYgZGF0YS5jaGFuZ2UgdGhlbiBkYXRhLmNoYW5nZSBlbHNlIHVuZGVmaW5lZFxuICAgICAgICAgICAgICAgICAgICBpZiBkYXRhLmFkZHJlc3MgPT0gJ091dHJvLi4uJ1xuICAgICAgICAgICAgICAgICAgICAgICAgb3JkZXIuYWRkcmVzcyA9IGRhdGEuYWRkcmVzczFcbiAgICAgICAgICAgICAgICAgICAgICAgIG9yZGVyLm51bWJlciA9IGRhdGEubnVtYmVyXG4gICAgICAgICAgICAgICAgICAgICAgICBvcmRlci5jb21wbGVtZW50ID0gZGF0YS5jb21wbGVtZW50XG4gICAgICAgICAgICAgICAgICAgICAgICBvcmRlci5kaXN0cmljdCA9IGRhdGEuZGlzdHJpY3RcbiAgICAgICAgICAgICAgICAgICAgICAgIG9yZGVyLmNpdHkgPSBkYXRhLmNpdHlcbiAgICAgICAgICAgICAgICAgICAgICAgIG9yZGVyLnN0YXRlID0gZGF0YS5zdGF0ZVxuICAgICAgICAgICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgICAgICAgICBvcmRlci5hZGRyZXNzID0gJHJvb3RTY29wZS5HUklGRk8udXNlci5hZGRyZXNzXG4gICAgICAgICAgICAgICAgICAgICAgICBvcmRlci5udW1iZXIgPSAkcm9vdFNjb3BlLkdSSUZGTy51c2VyLm51bWJlclxuICAgICAgICAgICAgICAgICAgICAgICAgb3JkZXIuY29tcGxlbWVudCA9ICRyb290U2NvcGUuR1JJRkZPLnVzZXIuY29tcGxlbWVudFxuICAgICAgICAgICAgICAgICAgICAgICAgb3JkZXIuZGlzdHJpY3QgPSAkcm9vdFNjb3BlLkdSSUZGTy51c2VyLmRpc3RyaWN0XG4gICAgICAgICAgICAgICAgICAgICAgICBvcmRlci5jaXR5ID0gJHJvb3RTY29wZS5HUklGRk8udXNlci5jaXR5XG4gICAgICAgICAgICAgICAgICAgICAgICBvcmRlci5zdGF0ZSA9ICRyb290U2NvcGUuR1JJRkZPLnVzZXIuc3RhdGVcblxuICAgICAgICAgICAgICAgICRnclJlc3RmdWwuY3JlYXRlXG4gICAgICAgICAgICAgICAgICAgIG1vZHVsZTogJ29yZGVyJ1xuICAgICAgICAgICAgICAgICAgICBhY3Rpb246ICdpbnNlcnQnXG4gICAgICAgICAgICAgICAgICAgIHBvc3Q6IG9yZGVyXG4gICAgICAgICAgICAgICAgLnRoZW4gKHIpIC0+XG4gICAgICAgICAgICAgICAgICAgIGlmIHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAgICAgICAgIGZpbmlzaGVkID0geWVzXG4gICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuZm9ybS5yZXNldCgpXG4gICAgICAgICAgICAgICAgICAgICAgICAkcm9vdFNjb3BlLmdyLmNhcnQuY2xlYXIoKVxuICAgICAgICAgICAgICAgICAgICAgICAgYWxlcnQuc2hvdyByLnN0YXR1cywgJ1NldSBwZWRpZG8gZm9pIGVudmlhZG8gY29tIHN1Y2Vzc28sIHZvY8OqIHNlcsOhIHJlZGlyZWNpb25hZG8gcGFyYSBhY29tcGFuaGFyIHNldSBwZWRpZG8uJ1xuICAgICAgICAgICAgICAgICAgICAgICAgJHRpbWVvdXQgKC0+XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbG9jYXRpb24uaHJlZiA9ICRyb290U2NvcGUuR1JJRkZPLmN1ckFsaWFzICsgJy91c2VyJ1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVyblxuICAgICAgICAgICAgICAgICAgICAgICAgKSwgNTAwMFxuICAgICAgICAgICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgICAgICAgICBhbGVydC5zaG93IHIuc3RhdHVzLCByLm1lc3NhZ2VcbiAgICAgICAgICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgICAgICAgICAgLCAtPlxuICAgICAgICAgICAgICAgICAgICBhbGVydC5zaG93ICdkYW5nZXInLCAnRVJST1IuRkFUQUwnXG5cbiAgICAgICAgJHNjb3BlLiR3YXRjaCAnZm9ybVNldHRpbmdzLmRhdGEuc3RhdGUnLCAoZSkgLT5cbiAgICAgICAgICAgIGlmIGVcbiAgICAgICAgICAgICAgICBpZiAkc2NvcGUuZm9ybVNldHRpbmdzLmRhdGEuc3RhdGVcbiAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNpdGllcyA9IFtdXG4gICAgICAgICAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCAkY2lkYWRlRXN0YWRvLmdldC5jaWRhZGVzKCRzY29wZS5mb3JtU2V0dGluZ3MuZGF0YS5zdGF0ZSksIChjKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNpdGllcy5wdXNoXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWU6IGNcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsYWJlbDogY1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuXG4gICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICAkc2NvcGUuY2l0aWVzID0gW11cbiAgICAgICAgICAgICAgICBpZiAkc2NvcGUuZm9ybVNldHRpbmdzLmRhdGEuc3RhdGUgPT0gJ1NQJ1xuICAgICAgICAgICAgICAgICAgICAkc2NvcGUuZm9ybVNldHRpbmdzLmRhdGEuY2l0eSA9ICdJdGFwZXZhJ1xuICAgICAgICAgICAgICAgIGVsc2VcbiAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmZvcm1TZXR0aW5ncy5kYXRhLmNpdHkgPSB1bmRlZmluZWRcbiAgICAgICAgICAgIGVsc2VcbiAgICAgICAgICAgICAgICAkc2NvcGUuZm9ybVNldHRpbmdzLmRhdGEuY2l0eSA9IHVuZGVmaW5lZFxuXG4gICAgICAgICRzY29wZS4kd2F0Y2ggLT5cbiAgICAgICAgICAgICRyb290U2NvcGUuZ3IuY2FydC5sZW5ndGgoKVxuICAgICAgICAsIC0+XG4gICAgICAgICAgICBjYXJ0RW1wdHkoKSBpZiAkcm9vdFNjb3BlLmdyLmNhcnQubGVuZ3RoKCkgPT0gMCBhbmQgIWZpbmlzaGVkXG5cbiAgICAgICAgJHNjb3BlLiR3YXRjaCAnZm9ybScsIChmb3JtKSAtPlxuICAgICAgICAgICAgJHJvb3RTY29wZS5maW5pc2hGb3JtID0gZm9ybVxuIl19