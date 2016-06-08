(function() {
  'use strict';
  angular.module('mainApp').controller('userCtrl', ["$rootScope", "$scope", "$cookies", "$window", "$grRestful", "$grModal", "$grAlert", "$cidadeEstado", "$timeout", "angularLoad", function($rootScope, $scope, $cookies, $window, $grRestful, $grModal, $grAlert, $cidadeEstado, $timeout, angularLoad) {
    var initNotification;
    initNotification = function() {
      var notify, permissionLevels;
      permissionLevels = {};
      notify = $window.notify;
      permissionLevels[notify.PERMISSION_GRANTED] = 0;
      permissionLevels[notify.PERMISSION_DEFAULT] = 1;
      permissionLevels[notify.PERMISSION_DENIED] = 2;
      $scope.isSupported = notify.isSupported;
      $scope.permissionLevel = permissionLevels[notify.permissionLevel()];
      $scope.requestPermissions = function() {
        return notify.requestPermission(function() {
          return $scope.$apply($scope.permissionLevel = permissionLevels[notify.permissionLevel()]);
        });
      };
      $scope.notify = function(order) {
        var status;
        if ($scope.isSupported && $scope.permissionLevel === 0) {
          status = void 0;
          if (order.status === 0) {
            status = 'Aguardando atendimento';
          } else if (order.status === 1) {
            status = 'Em produção';
          } else if (order.status === 2) {
            status = 'Em transporte';
          } else if (order.status === 3) {
            status = 'Entregue';
          } else if (order.status === 4) {
            status = 'Concluído, aguardando retirada';
          }
          return notify.createNotification('Status do pedido #' + order.idorder, {
            body: 'Alterado para "' + status + '"',
            icon: $rootScope.GRIFFO.templatePath + 'image/notification-status-' + order.status + '.png'
          });
        }
      };
      return $scope.requestPermissions();
    };
    $rootScope.gr.title = {
      icon: 'fa fa-fw fa-lock',
      text: 'Acesso restrito'
    };
    (function() {
      var alert, editable;
      alert = $grAlert["new"]();
      editable = false;
      $scope.editable = function(e) {
        if (e === void 0) {
          return editable;
        } else {
          editable = e;
        }
      };
      $scope.shops = [];
      $scope.findShop = function(id) {
        var name;
        name = void 0;
        angular.forEach($scope.shops, function(shop) {
          if (shop.value === id) {
            return name = shop.label;
          }
        });
        return name;
      };
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
        return $scope.states.push({
          value: e[0],
          label: e[1]
        });
      });
      $grRestful.find({
        module: 'client',
        action: 'get',
        id: $rootScope.GRIFFO.user.id
      }).then(function(r) {
        if (r.response) {
          $scope.formSettings.data = r.response;
          return $scope.form.updateDefaults();
        }
      });
      $scope.formSettings = {
        data: {},
        schema: [
          {
            property: 'name',
            type: 'text',
            label: 'Nome',
            columns: 12,
            attr: {
              required: true
            },
            msgs: {
              required: 'Preencha o nome'
            }
          }, {
            property: 'phone',
            type: 'phone',
            label: 'Telefone',
            columns: {
              xs: 12,
              sm: 12,
              md: 6
            },
            attr: {
              ngRequired: '!formSettings.data.mobilephone',
              required: true
            },
            msgs: {
              required: 'Informe o Telefone',
              mask: 'O telefone é inválido'
            }
          }, {
            property: 'mobilephone',
            type: 'mobilephone',
            label: 'Celular',
            columns: {
              xs: 12,
              sm: 12,
              md: 6
            },
            attr: {
              ngRequired: '!formSettings.data.phone',
              required: true
            },
            msgs: {
              required: 'Informe o Celular',
              mask: 'O celular é inválido'
            }
          }, {
            property: 'email',
            type: 'email',
            label: 'E-mail',
            placeholder: 'exemplo@exemplo.com',
            columns: 12,
            attr: {
              required: true
            },
            msgs: {
              required: 'Informe o e-mail',
              email: 'O formato do e-mail é inválido!'
            }
          }, {
            property: 'address',
            type: 'text',
            label: 'Endereço',
            columns: 8,
            attr: {
              required: true
            },
            msgs: {
              required: 'Preencha o endereço'
            }
          }, {
            property: 'number',
            type: 'text',
            label: 'Número',
            number: true,
            columns: 4,
            attr: {
              required: true
            },
            msgs: {
              required: 'Preencha o número'
            }
          }, {
            property: 'complement',
            type: 'text',
            label: 'Complemento',
            columns: 6
          }, {
            property: 'district',
            type: 'text',
            label: 'Bairro',
            columns: 6,
            attr: {
              required: true
            },
            msgs: {
              required: 'Preencha o bairro'
            }
          }, {
            property: 'city',
            type: 'select',
            label: 'Cidade',
            columns: 6,
            list: 'item.value as item.label for item in cities',
            attr: {
              required: true
            },
            msgs: {
              required: 'Selecione uma cidade'
            }
          }, {
            property: 'state',
            type: 'select',
            label: 'Estado',
            columns: 6,
            list: 'item.value as item.label for item in states',
            attr: {
              required: true
            },
            msgs: {
              required: 'Selecione um estado'
            }
          }, {
            property: 'fkidshop',
            type: 'select',
            label: 'Loja preferencial',
            list: 'item.value as item.label for item in shops',
            columns: 12,
            attr: {
              required: true
            },
            msgs: {
              required: 'Selecione uma loja preferencial'
            }
          }
        ],
        submit: function(data) {
          if (!$scope.form.$valid) {
            return;
          }
          return $grRestful.update({
            module: 'client',
            action: 'update_attributes',
            id: $rootScope.GRIFFO.user.id,
            post: data
          }).then(function(r) {
            if (r.response) {
              $scope.editable(false);
              $scope.form.updateDefaults();
            }
            return alert.show(r.status, r.message);
          });
        }
      };
      $scope.$watch('formSettings.data.state', function(e) {
        if (e) {
          if ($scope.formSettings.data.state) {
            $scope.cities = [];
            return angular.forEach($cidadeEstado.get.cidades($scope.formSettings.data.state), function(c) {
              return $scope.cities.push({
                value: c,
                label: c
              });
            });
          } else {
            return $scope.cities = [];
          }
        } else {
          return $scope.formSettings.data.city = void 0;
        }
      });
      return $scope.changePassword = function() {
        var modal;
        modal = $grModal["new"]({
          name: 'change-password',
          title: 'Alterar senha',
          size: 'sm',
          model: GRIFFO.templatePath + 'view/modal/change-password.php',
          define: {
            id: $scope.formSettings.data.idclient
          },
          buttons: [
            {
              type: 'primary',
              label: 'Alterar',
              onClick: function($scope, $element, controller) {
                $scope.form.submit();
              }
            }, {
              type: 'danger',
              label: 'Cancelar',
              onClick: function($scope, $element, controller) {
                controller.close();
              }
            }
          ]
        });
        return modal.open();
      };
    })();
    (function() {
      var getOrders, getOrdersTimeout;
      getOrders = function() {
        var getOrdersTimeout;
        if (!$scope.showCompleted) {
          $grRestful.find({
            module: 'order',
            action: 'get',
            params: 'fkidclient=' + $rootScope.GRIFFO.user.id
          }).then(function(r) {
            if (r.response) {
              angular.forEach(r.response, function(order, id) {
                return angular.forEach($scope.orders, function(_order, _id) {
                  if (_order.idorder === order.idorder && _order.status !== order.status) {
                    return $scope.notify(order);
                  }
                });
              });
              return $scope.orders = r.response;
            } else {
              return $scope.orders = [];
            }
          });
          return getOrdersTimeout = $timeout(function() {
            return getOrders();
          }, 30000);
        } else {
          return $grRestful.find({
            module: 'order',
            action: 'completed',
            params: 'fkidclient=' + $rootScope.GRIFFO.user.id
          }).then(function(r) {
            if (r.response) {
              return $scope.orders = r.response;
            } else {
              return $scope.orders = [];
            }
          });
        }
      };
      $scope.orders = [];
      getOrdersTimeout = $timeout;
      $scope.$watch('showCompleted', function() {
        $timeout.cancel(getOrdersTimeout);
        return getOrders();
      });
      getOrders();
      return $scope.orderInfo = function(order) {
        var modal;
        modal = $grModal["new"]({
          name: 'order-info',
          title: 'Informações do pedido #' + order.idorder,
          size: 'md',
          model: GRIFFO.templatePath + 'view/modal/order-info.php',
          define: {
            order: order,
            findShop: $scope.findShop
          },
          buttons: [
            {
              type: 'primary',
              label: 'Ok',
              onClick: function($scope, $element, controller) {
                return controller.close();
              }
            }
          ]
        });
        return modal.open();
      };
    })();
    delete $cookies.griffo_cart_ready;
    return angularLoad.loadScript($rootScope.GRIFFO.librariesPath + 'client/desktop-notify/desktop-notify.min.js').then(initNotification);
  }]);

  angular.module('mainApp').controller('changePasswordCtrl', ["$scope", "$timeout", "$grRestful", "$grAlert", function($scope, $timeout, $grRestful, $grAlert) {
    var alert;
    alert = $grAlert["new"]();
    $scope.formSettings = {
      data: {},
      schema: [
        {
          property: 'oldpassword',
          type: 'password',
          label: 'Senha atual',
          attr: {
            required: true,
            autofocus: true
          },
          msgs: {
            required: 'A senha atual é obrigatória'
          }
        }, {
          type: 'hr'
        }, {
          property: 'password',
          type: 'password',
          label: 'Nova senha',
          attr: {
            required: true,
            ngMinlength: 4,
            ngMaxlength: 16
          },
          msgs: {
            required: 'A nova senha é obrigatória',
            minlength: 'A senha deve possuir no mínimo 4 caractéres',
            maxlength: 'A senha não pode ultrapassar 16 caractéres',
            pattern: 'A nova senha é inválida'
          }
        }, {
          property: 'repassword',
          type: 'password',
          label: 'Confirmação da nova senha',
          attr: {
            required: true,
            confirmPassword: 'formSettings.data.password'
          },
          msgs: {
            required: 'Confirmar a nova senha é obrigatória',
            match: 'As senhas precisam ser iguais'
          }
        }
      ],
      submit: function(data) {
        var newData;
        newData = {
          'old-password': data.oldpassword,
          'password': data.password,
          're-password': data.repassword
        };
        return $grRestful.update({
          module: 'client',
          action: 'change_password',
          id: $scope.$parent.id,
          post: newData
        }).then(function(r) {
          if (r.response) {
            $scope.modal.close();
            return alert.show('success', 'ALERT.SUCCESS.CHANGE.PASSWORD');
          } else {
            return alert.show(r.status, r.message);
          }
        }, function() {
          return alert.show('danger', 'ERROR.FATAL');
        });
      }
    };
    return $scope.$watch('form', function(form) {
      return $scope.$parent.form = form;
    });
  }]);

  angular.module('mainApp').directive('confirmPassword', function() {
    return {
      restrict: 'A',
      require: 'ngModel',
      link: function(scope, element, attrs, ngModel) {
        var validate;
        validate = function(viewValue) {
          var password;
          password = scope.$eval(attrs.confirmPassword);
          ngModel.$setValidity('match', ngModel.$isEmpty(viewValue) || viewValue === password);
          return viewValue;
        };
        ngModel.$parsers.push(validate);
        return scope.$watch(attrs.confirmPassword, function(value) {
          return validate(ngModel.$viewValue);
        });
      }
    };
  });

}).call(this);

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvY3JhYi9jb2ZmZWUvY29udHJvbGxlcnMvdXNlckN0cmwuY29mZmVlIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0VBQUE7RUFDQSxPQUFPLENBQUMsTUFBUixDQUFlLFNBQWYsQ0FBeUIsQ0FBQyxVQUExQixDQUFxQyxVQUFyQyxFQUFpRCxTQUFDLFVBQUQsRUFBYSxNQUFiLEVBQXFCLFFBQXJCLEVBQStCLE9BQS9CLEVBQXdDLFVBQXhDLEVBQW9ELFFBQXBELEVBQThELFFBQTlELEVBQXdFLGFBQXhFLEVBQXVGLFFBQXZGLEVBQWlHLFdBQWpHO0FBRTdDLFFBQUE7SUFBQSxnQkFBQSxHQUFtQixTQUFBO0FBRWYsVUFBQTtNQUFBLGdCQUFBLEdBQW1CO01BQ25CLE1BQUEsR0FBUyxPQUFPLENBQUM7TUFDakIsZ0JBQWlCLENBQUEsTUFBTSxDQUFDLGtCQUFQLENBQWpCLEdBQThDO01BQzlDLGdCQUFpQixDQUFBLE1BQU0sQ0FBQyxrQkFBUCxDQUFqQixHQUE4QztNQUM5QyxnQkFBaUIsQ0FBQSxNQUFNLENBQUMsaUJBQVAsQ0FBakIsR0FBNkM7TUFDN0MsTUFBTSxDQUFDLFdBQVAsR0FBcUIsTUFBTSxDQUFDO01BQzVCLE1BQU0sQ0FBQyxlQUFQLEdBQXlCLGdCQUFpQixDQUFBLE1BQU0sQ0FBQyxlQUFQLENBQUEsQ0FBQTtNQUUxQyxNQUFNLENBQUMsa0JBQVAsR0FBNEIsU0FBQTtlQUN4QixNQUFNLENBQUMsaUJBQVAsQ0FBeUIsU0FBQTtpQkFDckIsTUFBTSxDQUFDLE1BQVAsQ0FBYyxNQUFNLENBQUMsZUFBUCxHQUF5QixnQkFBaUIsQ0FBQSxNQUFNLENBQUMsZUFBUCxDQUFBLENBQUEsQ0FBeEQ7UUFEcUIsQ0FBekI7TUFEd0I7TUFJNUIsTUFBTSxDQUFDLE1BQVAsR0FBZ0IsU0FBQyxLQUFEO0FBQ1osWUFBQTtRQUFBLElBQUcsTUFBTSxDQUFDLFdBQVAsSUFBdUIsTUFBTSxDQUFD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LGlCQUFwQyxFQUF1RCxTQUFBO1dBQ25EO01BQUEsUUFBQSxFQUFVLEdBQVY7TUFDQSxPQUFBLEVBQVMsU0FEVDtNQUVBLElBQUEsRUFBTSxTQUFDLEtBQUQsRUFBUSxPQUFSLEVBQWlCLEtBQWpCLEVBQXdCLE9BQXhCO0FBQ0YsWUFBQTtRQUFBLFFBQUEsR0FBVyxTQUFDLFNBQUQ7QUFDUCxjQUFBO1VBQUEsUUFBQSxHQUFXLEtBQUssQ0FBQyxLQUFOLENBQVksS0FBSyxDQUFDLGVBQWxCO1VBQ1gsT0FBTyxDQUFDLFlBQVIsQ0FBcUIsT0FBckIsRUFBOEIsT0FBTyxDQUFDLFFBQVIsQ0FBaUIsU0FBakIsQ0FBQSxJQUErQixTQUFBLEtBQWEsUUFBMUU7aUJBQ0E7UUFITztRQUlYLE9BQU8sQ0FBQyxRQUFRLENBQUMsSUFBakIsQ0FBc0IsUUFBdEI7ZUFDQSxLQUFLLENBQUMsTUFBTixDQUFhLEtBQUssQ0FBQyxlQUFuQixFQUFvQyxTQUFDLEtBQUQ7aUJBQ2hDLFFBQUEsQ0FBUyxPQUFPLENBQUMsVUFBakI7UUFEZ0MsQ0FBcEM7TUFORSxDQUZOOztFQURtRCxDQUF2RDtBQTlXQSIsImZpbGUiOiJjbGllbnQvbGF5ZXJzL3dlYnNpdGUvdGVtcGxhdGVzL2NyYWIvanMvY29udHJvbGxlcnMvdXNlckN0cmwuanMiLCJzb3VyY2VSb290IjoiL3NvdXJjZS8iLCJzb3VyY2VzQ29udGVudCI6WyIndXNlIHN0cmljdCdcbmFuZ3VsYXIubW9kdWxlKCdtYWluQXBwJykuY29udHJvbGxlciAndXNlckN0cmwnLCAoJHJvb3RTY29wZSwgJHNjb3BlLCAkY29va2llcywgJHdpbmRvdywgJGdyUmVzdGZ1bCwgJGdyTW9kYWwsICRnckFsZXJ0LCAkY2lkYWRlRXN0YWRvLCAkdGltZW91dCwgYW5ndWxhckxvYWQpIC0+XG5cbiAgICBpbml0Tm90aWZpY2F0aW9uID0gLT5cblxuICAgICAgICBwZXJtaXNzaW9uTGV2ZWxzID0ge31cbiAgICAgICAgbm90aWZ5ID0gJHdpbmRvdy5ub3RpZnlcbiAgICAgICAgcGVybWlzc2lvbkxldmVsc1tub3RpZnkuUEVSTUlTU0lPTl9HUkFOVEVEXSA9IDBcbiAgICAgICAgcGVybWlzc2lvbkxldmVsc1tub3RpZnkuUEVSTUlTU0lPTl9ERUZBVUxUXSA9IDFcbiAgICAgICAgcGVybWlzc2lvbkxldmVsc1tub3RpZnkuUEVSTUlTU0lPTl9ERU5JRURdID0gMlxuICAgICAgICAkc2NvcGUuaXNTdXBwb3J0ZWQgPSBub3RpZnkuaXNTdXBwb3J0ZWRcbiAgICAgICAgJHNjb3BlLnBlcm1pc3Npb25MZXZlbCA9IHBlcm1pc3Npb25MZXZlbHNbbm90aWZ5LnBlcm1pc3Npb25MZXZlbCgpXVxuXG4gICAgICAgICRzY29wZS5yZXF1ZXN0UGVybWlzc2lvbnMgPSAtPlxuICAgICAgICAgICAgbm90aWZ5LnJlcXVlc3RQZXJtaXNzaW9uIC0+XG4gICAgICAgICAgICAgICAgJHNjb3BlLiRhcHBseSAkc2NvcGUucGVybWlzc2lvbkxldmVsID0gcGVybWlzc2lvbkxldmVsc1tub3RpZnkucGVybWlzc2lvbkxldmVsKCldXG5cbiAgICAgICAgJHNjb3BlLm5vdGlmeSA9IChvcmRlcikgLT5cbiAgICAgICAgICAgIGlmICRzY29wZS5pc1N1cHBvcnRlZCBhbmQgJHNjb3BlLnBlcm1pc3Npb25MZXZlbCA9PSAwXG4gICAgICAgICAgICAgICAgc3RhdHVzID0gdW5kZWZpbmVkXG4gICAgICAgICAgICAgICAgaWYgb3JkZXIuc3RhdHVzID09IDBcbiAgICAgICAgICAgICAgICAgICAgc3RhdHVzID0gJ0FndWFyZGFuZG8gYXRlbmRpbWVudG8nXG4gICAgICAgICAgICAgICAgZWxzZSBpZiBvcmRlci5zdGF0dXMgPT0gMVxuICAgICAgICAgICAgICAgICAgICBzdGF0dXMgPSAnRW0gcHJvZHXDp8OjbydcbiAgICAgICAgICAgICAgICBlbHNlIGlmIG9yZGVyLnN0YXR1cyA9PSAyXG4gICAgICAgICAgICAgICAgICAgIHN0YXR1cyA9ICdFbSB0cmFuc3BvcnRlJ1xuICAgICAgICAgICAgICAgIGVsc2UgaWYgb3JkZXIuc3RhdHVzID09IDNcbiAgICAgICAgICAgICAgICAgICAgc3RhdHVzID0gJ0VudHJlZ3VlJ1xuICAgICAgICAgICAgICAgIGVsc2UgaWYgb3JkZXIuc3RhdHVzID09IDRcbiAgICAgICAgICAgICAgICAgICAgc3RhdHVzID0gJ0NvbmNsdcOtZG8sIGFndWFyZGFuZG8gcmV0aXJhZGEnXG4gICAgICAgICAgICAgICAgbm90aWZ5LmNyZWF0ZU5vdGlmaWNhdGlvbiAnU3RhdHVzIGRvIHBlZGlkbyAjJyArIG9yZGVyLmlkb3JkZXIsXG4gICAgICAgICAgICAgICAgICAgIGJvZHk6ICdBbHRlcmFkbyBwYXJhIFwiJyArIHN0YXR1cyArICdcIidcbiAgICAgICAgICAgICAgICAgICAgaWNvbjogJHJvb3RTY29wZS5HUklGRk8udGVtcGxhdGVQYXRoICsgJ2ltYWdlL25vdGlmaWNhdGlvbi1zdGF0dXMtJyArIG9yZGVyLnN0YXR1cyArICcucG5nJ1xuXG4gICAgICAgICRzY29wZS5yZXF1ZXN0UGVybWlzc2lvbnMoKVxuXG4gICAgJHJvb3RTY29wZS5nci50aXRsZSA9XG4gICAgICAgIGljb246ICdmYSBmYS1mdyBmYS1sb2NrJ1xuICAgICAgICB0ZXh0OiAnQWNlc3NvIHJlc3RyaXRvJ1xuXG4gICAgZG8gLT5cbiAgICAgICAgYWxlcnQgPSAkZ3JBbGVydC5uZXcoKVxuICAgICAgICBlZGl0YWJsZSA9IGZhbHNlXG5cbiAgICAgICAgJHNjb3BlLmVkaXRhYmxlID0gKGUpIC0+XG4gICAgICAgICAgICBpZiBlID09IHVuZGVmaW5lZFxuICAgICAgICAgICAgICAgIHJldHVybiBlZGl0YWJsZVxuICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgIGVkaXRhYmxlID0gZVxuICAgICAgICAgICAgcmV0dXJuXG5cbiAgICAgICAgJHNjb3BlLnNob3BzID0gW11cblxuICAgICAgICAkc2NvcGUuZmluZFNob3AgPSAoaWQpIC0+XG4gICAgICAgICAgICBuYW1lID0gdW5kZWZpbmVkXG4gICAgICAgICAgICBhbmd1bGFyLmZvckVhY2ggJHNjb3BlLnNob3BzLCAoc2hvcCkgLT5cbiAgICAgICAgICAgICAgICBuYW1lID0gc2hvcC5sYWJlbCBpZiBzaG9wLnZhbHVlID09IGlkXG4gICAgICAgICAgICBuYW1lXG5cbiAgICAgICAgJGdyUmVzdGZ1bC5maW5kKFxuICAgICAgICAgICAgbW9kdWxlOiAnc2hvcCdcbiAgICAgICAgICAgIGFjdGlvbjogJ3NlbGVjdCcpLnRoZW4gKHIpIC0+XG4gICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgJHNjb3BlLnNob3BzID0gci5yZXNwb25zZVxuXG4gICAgICAgICRzY29wZS5zdGF0ZXMgPSBbXVxuXG4gICAgICAgIGFuZ3VsYXIuZm9yRWFjaCAkY2lkYWRlRXN0YWRvLmdldC5lc3RhZG9zKCksIChlKSAtPlxuICAgICAgICAgICAgJHNjb3BlLnN0YXRlcy5wdXNoXG4gICAgICAgICAgICAgICAgdmFsdWU6IGVbMF1cbiAgICAgICAgICAgICAgICBsYWJlbDogZVsxXVxuXG4gICAgICAgICRnclJlc3RmdWwuZmluZChcbiAgICAgICAgICAgIG1vZHVsZTogJ2NsaWVudCdcbiAgICAgICAgICAgIGFjdGlvbjogJ2dldCdcbiAgICAgICAgICAgIGlkOiAkcm9vdFNjb3BlLkdSSUZGTy51c2VyLmlkKS50aGVuIChyKSAtPlxuICAgICAgICAgICAgaWYgci5yZXNwb25zZVxuICAgICAgICAgICAgICAgICRzY29wZS5mb3JtU2V0dGluZ3MuZGF0YSA9IHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAkc2NvcGUuZm9ybS51cGRhdGVEZWZhdWx0cygpXG5cbiAgICAgICAgJHNjb3BlLmZvcm1TZXR0aW5ncyA9XG4gICAgICAgICAgICBkYXRhOiB7fVxuICAgICAgICAgICAgc2NoZW1hOiBbXG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ25hbWUnXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICd0ZXh0J1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ05vbWUnXG4gICAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDEyXG4gICAgICAgICAgICAgICAgICAgIGF0dHI6IHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIG1zZ3M6IHJlcXVpcmVkOiAnUHJlZW5jaGEgbyBub21lJ1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAncGhvbmUnXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdwaG9uZSdcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdUZWxlZm9uZSdcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczpcbiAgICAgICAgICAgICAgICAgICAgICAgIHhzOiAxMlxuICAgICAgICAgICAgICAgICAgICAgICAgc206IDEyXG4gICAgICAgICAgICAgICAgICAgICAgICBtZDogNlxuICAgICAgICAgICAgICAgICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICAgICAgICAgbmdSZXF1aXJlZDogJyFmb3JtU2V0dGluZ3MuZGF0YS5tb2JpbGVwaG9uZSdcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIG1zZ3M6XG4gICAgICAgICAgICAgICAgICAgICAgICByZXF1aXJlZDogJ0luZm9ybWUgbyBUZWxlZm9uZSdcbiAgICAgICAgICAgICAgICAgICAgICAgIG1hc2s6ICdPIHRlbGVmb25lIMOpIGludsOhbGlkbydcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ21vYmlsZXBob25lJ1xuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnbW9iaWxlcGhvbmUnXG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiAnQ2VsdWxhcidcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczpcbiAgICAgICAgICAgICAgICAgICAgICAgIHhzOiAxMlxuICAgICAgICAgICAgICAgICAgICAgICAgc206IDEyXG4gICAgICAgICAgICAgICAgICAgICAgICBtZDogNlxuICAgICAgICAgICAgICAgICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICAgICAgICAgbmdSZXF1aXJlZDogJyFmb3JtU2V0dGluZ3MuZGF0YS5waG9uZSdcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIG1zZ3M6XG4gICAgICAgICAgICAgICAgICAgICAgICByZXF1aXJlZDogJ0luZm9ybWUgbyBDZWx1bGFyJ1xuICAgICAgICAgICAgICAgICAgICAgICAgbWFzazogJ08gY2VsdWxhciDDqSBpbnbDoWxpZG8nXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdlbWFpbCdcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ2VtYWlsJ1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0UtbWFpbCdcbiAgICAgICAgICAgICAgICAgICAgcGxhY2Vob2xkZXI6ICdleGVtcGxvQGV4ZW1wbG8uY29tJ1xuICAgICAgICAgICAgICAgICAgICBjb2x1bW5zOiAxMlxuICAgICAgICAgICAgICAgICAgICBhdHRyOiByZXF1aXJlZDogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICBtc2dzOlxuICAgICAgICAgICAgICAgICAgICAgICAgcmVxdWlyZWQ6ICdJbmZvcm1lIG8gZS1tYWlsJ1xuICAgICAgICAgICAgICAgICAgICAgICAgZW1haWw6ICdPIGZvcm1hdG8gZG8gZS1tYWlsIMOpIGludsOhbGlkbyEnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdhZGRyZXNzJ1xuICAgICAgICAgICAgICAgICAgICB0eXBlOiAndGV4dCdcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdFbmRlcmXDp28nXG4gICAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDhcbiAgICAgICAgICAgICAgICAgICAgYXR0cjogcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgbXNnczogcmVxdWlyZWQ6ICdQcmVlbmNoYSBvIGVuZGVyZcOnbydcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ251bWJlcidcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3RleHQnXG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiAnTsO6bWVybydcbiAgICAgICAgICAgICAgICAgICAgbnVtYmVyOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDRcbiAgICAgICAgICAgICAgICAgICAgYXR0cjogcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgbXNnczogcmVxdWlyZWQ6ICdQcmVlbmNoYSBvIG7Dum1lcm8nXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdjb21wbGVtZW50J1xuICAgICAgICAgICAgICAgICAgICB0eXBlOiAndGV4dCdcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdDb21wbGVtZW50bydcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczogNlxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAnZGlzdHJpY3QnXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICd0ZXh0J1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0JhaXJybydcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczogNlxuICAgICAgICAgICAgICAgICAgICBhdHRyOiByZXF1aXJlZDogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICBtc2dzOiByZXF1aXJlZDogJ1ByZWVuY2hhIG8gYmFpcnJvJ1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAnY2l0eSdcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3NlbGVjdCdcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdDaWRhZGUnXG4gICAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDZcbiAgICAgICAgICAgICAgICAgICAgbGlzdDogJ2l0ZW0udmFsdWUgYXMgaXRlbS5sYWJlbCBmb3IgaXRlbSBpbiBjaXRpZXMnXG4gICAgICAgICAgICAgICAgICAgIGF0dHI6IHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIG1zZ3M6IHJlcXVpcmVkOiAnU2VsZWNpb25lIHVtYSBjaWRhZGUnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdzdGF0ZSdcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3NlbGVjdCdcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdFc3RhZG8nXG4gICAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDZcbiAgICAgICAgICAgICAgICAgICAgbGlzdDogJ2l0ZW0udmFsdWUgYXMgaXRlbS5sYWJlbCBmb3IgaXRlbSBpbiBzdGF0ZXMnXG4gICAgICAgICAgICAgICAgICAgIGF0dHI6IHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIG1zZ3M6IHJlcXVpcmVkOiAnU2VsZWNpb25lIHVtIGVzdGFkbydcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ2ZraWRzaG9wJ1xuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnc2VsZWN0J1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0xvamEgcHJlZmVyZW5jaWFsJ1xuICAgICAgICAgICAgICAgICAgICBsaXN0OiAnaXRlbS52YWx1ZSBhcyBpdGVtLmxhYmVsIGZvciBpdGVtIGluIHNob3BzJ1xuICAgICAgICAgICAgICAgICAgICBjb2x1bW5zOiAxMlxuICAgICAgICAgICAgICAgICAgICBhdHRyOiByZXF1aXJlZDogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICBtc2dzOiByZXF1aXJlZDogJ1NlbGVjaW9uZSB1bWEgbG9qYSBwcmVmZXJlbmNpYWwnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXVxuICAgICAgICAgICAgc3VibWl0OiAoZGF0YSkgLT5cbiAgICAgICAgICAgICAgICByZXR1cm4gaWYgISRzY29wZS5mb3JtLiR2YWxpZFxuICAgICAgICAgICAgICAgICRnclJlc3RmdWwudXBkYXRlKFxuICAgICAgICAgICAgICAgICAgICBtb2R1bGU6ICdjbGllbnQnXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogJ3VwZGF0ZV9hdHRyaWJ1dGVzJ1xuICAgICAgICAgICAgICAgICAgICBpZDogJHJvb3RTY29wZS5HUklGRk8udXNlci5pZFxuICAgICAgICAgICAgICAgICAgICBwb3N0OiBkYXRhKS50aGVuIChyKSAtPlxuICAgICAgICAgICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuZWRpdGFibGUgZmFsc2VcbiAgICAgICAgICAgICAgICAgICAgICAgICRzY29wZS5mb3JtLnVwZGF0ZURlZmF1bHRzKClcbiAgICAgICAgICAgICAgICAgICAgYWxlcnQuc2hvdyByLnN0YXR1cywgci5tZXNzYWdlXG5cbiAgICAgICAgJHNjb3BlLiR3YXRjaCAnZm9ybVNldHRpbmdzLmRhdGEuc3RhdGUnLCAoZSkgLT5cbiAgICAgICAgICAgIGlmIGVcbiAgICAgICAgICAgICAgICBpZiAkc2NvcGUuZm9ybVNldHRpbmdzLmRhdGEuc3RhdGVcbiAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNpdGllcyA9IFtdXG4gICAgICAgICAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCAkY2lkYWRlRXN0YWRvLmdldC5jaWRhZGVzKCRzY29wZS5mb3JtU2V0dGluZ3MuZGF0YS5zdGF0ZSksIChjKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNpdGllcy5wdXNoXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWU6IGNcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsYWJlbDogY1xuICAgICAgICAgICAgICAgIGVsc2VcbiAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNpdGllcyA9IFtdXG4gICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgJHNjb3BlLmZvcm1TZXR0aW5ncy5kYXRhLmNpdHkgPSB1bmRlZmluZWRcblxuICAgICAgICAkc2NvcGUuY2hhbmdlUGFzc3dvcmQgPSAtPlxuICAgICAgICAgICAgbW9kYWwgPSAkZ3JNb2RhbC5uZXdcbiAgICAgICAgICAgICAgICBuYW1lOiAnY2hhbmdlLXBhc3N3b3JkJ1xuICAgICAgICAgICAgICAgIHRpdGxlOiAnQWx0ZXJhciBzZW5oYSdcbiAgICAgICAgICAgICAgICBzaXplOiAnc20nXG4gICAgICAgICAgICAgICAgbW9kZWw6IEdSSUZGTy50ZW1wbGF0ZVBhdGggKyAndmlldy9tb2RhbC9jaGFuZ2UtcGFzc3dvcmQucGhwJ1xuICAgICAgICAgICAgICAgIGRlZmluZTogaWQ6ICRzY29wZS5mb3JtU2V0dGluZ3MuZGF0YS5pZGNsaWVudFxuICAgICAgICAgICAgICAgIGJ1dHRvbnM6IFtcbiAgICAgICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3ByaW1hcnknXG4gICAgICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0FsdGVyYXInXG4gICAgICAgICAgICAgICAgICAgICAgICBvbkNsaWNrOiAoJHNjb3BlLCAkZWxlbWVudCwgY29udHJvbGxlcikgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuZm9ybS5zdWJtaXQoKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVyblxuXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICAgICAgdHlwZTogJ2RhbmdlcidcbiAgICAgICAgICAgICAgICAgICAgICAgIGxhYmVsOiAnQ2FuY2VsYXInXG4gICAgICAgICAgICAgICAgICAgICAgICBvbkNsaWNrOiAoJHNjb3BlLCAkZWxlbWVudCwgY29udHJvbGxlcikgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250cm9sbGVyLmNsb3NlKClcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm5cblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXVxuICAgICAgICAgICAgbW9kYWwub3BlbigpXG5cbiAgICBkbyAtPlxuXG4gICAgICAgIGdldE9yZGVycyA9IC0+XG4gICAgICAgICAgICBpZiAhJHNjb3BlLnNob3dDb21wbGV0ZWRcbiAgICAgICAgICAgICAgICAkZ3JSZXN0ZnVsLmZpbmRcbiAgICAgICAgICAgICAgICAgICAgbW9kdWxlOiAnb3JkZXInXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogJ2dldCdcbiAgICAgICAgICAgICAgICAgICAgcGFyYW1zOiAnZmtpZGNsaWVudD0nICsgJHJvb3RTY29wZS5HUklGRk8udXNlci5pZFxuICAgICAgICAgICAgICAgIC50aGVuIChyKSAtPlxuICAgICAgICAgICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgICAgICAgICBhbmd1bGFyLmZvckVhY2ggci5yZXNwb25zZSwgKG9yZGVyLCBpZCkgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBhbmd1bGFyLmZvckVhY2ggJHNjb3BlLm9yZGVycywgKF9vcmRlciwgX2lkKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiBfb3JkZXIuaWRvcmRlciA9PSBvcmRlci5pZG9yZGVyIGFuZCBfb3JkZXIuc3RhdHVzICE9IG9yZGVyLnN0YXR1c1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm5vdGlmeSBvcmRlclxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm9yZGVycyA9IHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm9yZGVycyA9IFtdXG5cbiAgICAgICAgICAgICAgICBnZXRPcmRlcnNUaW1lb3V0ID0gJHRpbWVvdXQgLT5cbiAgICAgICAgICAgICAgICAgICAgZ2V0T3JkZXJzKClcbiAgICAgICAgICAgICAgICAsIDMwMDAwXG4gICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgJGdyUmVzdGZ1bC5maW5kXG4gICAgICAgICAgICAgICAgICAgIG1vZHVsZTogJ29yZGVyJ1xuICAgICAgICAgICAgICAgICAgICBhY3Rpb246ICdjb21wbGV0ZWQnXG4gICAgICAgICAgICAgICAgICAgIHBhcmFtczogJ2ZraWRjbGllbnQ9JyArICRyb290U2NvcGUuR1JJRkZPLnVzZXIuaWRcbiAgICAgICAgICAgICAgICAudGhlbiAocikgLT5cbiAgICAgICAgICAgICAgICAgICAgaWYgci5yZXNwb25zZVxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm9yZGVycyA9IHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm9yZGVycyA9IFtdXG5cbiAgICAgICAgJHNjb3BlLm9yZGVycyA9IFtdXG4gICAgICAgIGdldE9yZGVyc1RpbWVvdXQgPSAkdGltZW91dFxuICAgICAgICAkc2NvcGUuJHdhdGNoICdzaG93Q29tcGxldGVkJywgLT5cbiAgICAgICAgICAgICR0aW1lb3V0LmNhbmNlbCBnZXRPcmRlcnNUaW1lb3V0XG4gICAgICAgICAgICBnZXRPcmRlcnMoKVxuICAgICAgICBnZXRPcmRlcnMoKVxuICAgICAgICAkc2NvcGUub3JkZXJJbmZvID0gKG9yZGVyKSAtPlxuICAgICAgICAgICAgbW9kYWwgPSAkZ3JNb2RhbC5uZXdcbiAgICAgICAgICAgICAgICBuYW1lOiAnb3JkZXItaW5mbydcbiAgICAgICAgICAgICAgICB0aXRsZTogJ0luZm9ybWHDp8O1ZXMgZG8gcGVkaWRvICMnICsgb3JkZXIuaWRvcmRlclxuICAgICAgICAgICAgICAgIHNpemU6ICdtZCdcbiAgICAgICAgICAgICAgICBtb2RlbDogR1JJRkZPLnRlbXBsYXRlUGF0aCArICd2aWV3L21vZGFsL29yZGVyLWluZm8ucGhwJ1xuICAgICAgICAgICAgICAgIGRlZmluZTpcbiAgICAgICAgICAgICAgICAgICAgb3JkZXI6IG9yZGVyXG4gICAgICAgICAgICAgICAgICAgIGZpbmRTaG9wOiAkc2NvcGUuZmluZFNob3BcbiAgICAgICAgICAgICAgICBidXR0b25zOiBbXG4gICAgICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHR5cGU6ICdwcmltYXJ5J1xuICAgICAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdPaydcbiAgICAgICAgICAgICAgICAgICAgICAgIG9uQ2xpY2s6ICgkc2NvcGUsICRlbGVtZW50LCBjb250cm9sbGVyKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRyb2xsZXIuY2xvc2UoKVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXVxuICAgICAgICAgICAgbW9kYWwub3BlbigpXG5cbiAgICBkZWxldGUgJGNvb2tpZXMuZ3JpZmZvX2NhcnRfcmVhZHlcbiAgICBhbmd1bGFyTG9hZC5sb2FkU2NyaXB0KCRyb290U2NvcGUuR1JJRkZPLmxpYnJhcmllc1BhdGggKyAnY2xpZW50L2Rlc2t0b3Atbm90aWZ5L2Rlc2t0b3Atbm90aWZ5Lm1pbi5qcycpLnRoZW4gaW5pdE5vdGlmaWNhdGlvblxuXG5hbmd1bGFyLm1vZHVsZSgnbWFpbkFwcCcpLmNvbnRyb2xsZXIgJ2NoYW5nZVBhc3N3b3JkQ3RybCcsICgkc2NvcGUsICR0aW1lb3V0LCAkZ3JSZXN0ZnVsLCAkZ3JBbGVydCkgLT5cbiAgICBhbGVydCA9ICRnckFsZXJ0Lm5ldygpXG4gICAgJHNjb3BlLmZvcm1TZXR0aW5ncyA9XG4gICAgICAgIGRhdGE6IHt9XG4gICAgICAgIHNjaGVtYTogW1xuICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgIHByb3BlcnR5OiAnb2xkcGFzc3dvcmQnXG4gICAgICAgICAgICAgICAgdHlwZTogJ3Bhc3N3b3JkJ1xuICAgICAgICAgICAgICAgIGxhYmVsOiAnU2VuaGEgYXR1YWwnXG4gICAgICAgICAgICAgICAgYXR0cjpcbiAgICAgICAgICAgICAgICAgICAgcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgYXV0b2ZvY3VzOiB0cnVlXG4gICAgICAgICAgICAgICAgbXNnczogcmVxdWlyZWQ6ICdBIHNlbmhhIGF0dWFsIMOpIG9icmlnYXTDs3JpYSdcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHsgdHlwZTogJ2hyJyB9XG4gICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgcHJvcGVydHk6ICdwYXNzd29yZCdcbiAgICAgICAgICAgICAgICB0eXBlOiAncGFzc3dvcmQnXG4gICAgICAgICAgICAgICAgbGFiZWw6ICdOb3ZhIHNlbmhhJ1xuICAgICAgICAgICAgICAgIGF0dHI6XG4gICAgICAgICAgICAgICAgICAgIHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIG5nTWlubGVuZ3RoOiA0XG4gICAgICAgICAgICAgICAgICAgIG5nTWF4bGVuZ3RoOiAxNlxuICAgICAgICAgICAgICAgIG1zZ3M6XG4gICAgICAgICAgICAgICAgICAgIHJlcXVpcmVkOiAnQSBub3ZhIHNlbmhhIMOpIG9icmlnYXTDs3JpYSdcbiAgICAgICAgICAgICAgICAgICAgbWlubGVuZ3RoOiAnQSBzZW5oYSBkZXZlIHBvc3N1aXIgbm8gbcOtbmltbyA0IGNhcmFjdMOpcmVzJ1xuICAgICAgICAgICAgICAgICAgICBtYXhsZW5ndGg6ICdBIHNlbmhhIG7Do28gcG9kZSB1bHRyYXBhc3NhciAxNiBjYXJhY3TDqXJlcydcbiAgICAgICAgICAgICAgICAgICAgcGF0dGVybjogJ0Egbm92YSBzZW5oYSDDqSBpbnbDoWxpZGEnXG4gICAgICAgICAgICB9XG4gICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgcHJvcGVydHk6ICdyZXBhc3N3b3JkJ1xuICAgICAgICAgICAgICAgIHR5cGU6ICdwYXNzd29yZCdcbiAgICAgICAgICAgICAgICBsYWJlbDogJ0NvbmZpcm1hw6fDo28gZGEgbm92YSBzZW5oYSdcbiAgICAgICAgICAgICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICAgICByZXF1aXJlZDogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICBjb25maXJtUGFzc3dvcmQ6ICdmb3JtU2V0dGluZ3MuZGF0YS5wYXNzd29yZCdcbiAgICAgICAgICAgICAgICBtc2dzOlxuICAgICAgICAgICAgICAgICAgICByZXF1aXJlZDogJ0NvbmZpcm1hciBhIG5vdmEgc2VuaGEgw6kgb2JyaWdhdMOzcmlhJ1xuICAgICAgICAgICAgICAgICAgICBtYXRjaDogJ0FzIHNlbmhhcyBwcmVjaXNhbSBzZXIgaWd1YWlzJ1xuICAgICAgICAgICAgfVxuICAgICAgICBdXG4gICAgICAgIHN1Ym1pdDogKGRhdGEpIC0+XG4gICAgICAgICAgICBuZXdEYXRhID1cbiAgICAgICAgICAgICAgICAnb2xkLXBhc3N3b3JkJzogZGF0YS5vbGRwYXNzd29yZFxuICAgICAgICAgICAgICAgICdwYXNzd29yZCc6IGRhdGEucGFzc3dvcmRcbiAgICAgICAgICAgICAgICAncmUtcGFzc3dvcmQnOiBkYXRhLnJlcGFzc3dvcmRcbiAgICAgICAgICAgICRnclJlc3RmdWwudXBkYXRlXG4gICAgICAgICAgICAgICAgbW9kdWxlOiAnY2xpZW50J1xuICAgICAgICAgICAgICAgIGFjdGlvbjogJ2NoYW5nZV9wYXNzd29yZCdcbiAgICAgICAgICAgICAgICBpZDogJHNjb3BlLiRwYXJlbnQuaWRcbiAgICAgICAgICAgICAgICBwb3N0OiBuZXdEYXRhXG4gICAgICAgICAgICAudGhlbiAocikgLT5cbiAgICAgICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgICAgICRzY29wZS5tb2RhbC5jbG9zZSgpXG4gICAgICAgICAgICAgICAgICAgIGFsZXJ0LnNob3cgJ3N1Y2Nlc3MnLCAnQUxFUlQuU1VDQ0VTUy5DSEFOR0UuUEFTU1dPUkQnXG4gICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICBhbGVydC5zaG93IHIuc3RhdHVzLCByLm1lc3NhZ2VcbiAgICAgICAgICAgICwgLT5cbiAgICAgICAgICAgICAgICBhbGVydC5zaG93ICdkYW5nZXInLCAnRVJST1IuRkFUQUwnXG5cbiAgICAkc2NvcGUuJHdhdGNoICdmb3JtJywgKGZvcm0pIC0+XG4gICAgICAgICRzY29wZS4kcGFyZW50LmZvcm0gPSBmb3JtXG5cbmFuZ3VsYXIubW9kdWxlKCdtYWluQXBwJykuZGlyZWN0aXZlICdjb25maXJtUGFzc3dvcmQnLCAtPlxuICAgIHJlc3RyaWN0OiAnQSdcbiAgICByZXF1aXJlOiAnbmdNb2RlbCdcbiAgICBsaW5rOiAoc2NvcGUsIGVsZW1lbnQsIGF0dHJzLCBuZ01vZGVsKSAtPlxuICAgICAgICB2YWxpZGF0ZSA9ICh2aWV3VmFsdWUpIC0+XG4gICAgICAgICAgICBwYXNzd29yZCA9IHNjb3BlLiRldmFsKGF0dHJzLmNvbmZpcm1QYXNzd29yZClcbiAgICAgICAgICAgIG5nTW9kZWwuJHNldFZhbGlkaXR5ICdtYXRjaCcsIG5nTW9kZWwuJGlzRW1wdHkodmlld1ZhbHVlKSBvciB2aWV3VmFsdWUgPT0gcGFzc3dvcmRcbiAgICAgICAgICAgIHZpZXdWYWx1ZVxuICAgICAgICBuZ01vZGVsLiRwYXJzZXJzLnB1c2ggdmFsaWRhdGVcbiAgICAgICAgc2NvcGUuJHdhdGNoIGF0dHJzLmNvbmZpcm1QYXNzd29yZCwgKHZhbHVlKSAtPlxuICAgICAgICAgICAgdmFsaWRhdGUgbmdNb2RlbC4kdmlld1ZhbHVlXG4iXX0=