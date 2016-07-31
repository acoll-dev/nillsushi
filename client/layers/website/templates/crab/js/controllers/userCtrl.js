(function() {
  'use strict';
  angular.module('mainApp').controller('userCtrl', ["$rootScope", "$scope", "$localStorage", "$window", "$grRestful", "$grModal", "$grAlert", "$cidadeEstado", "$timeout", function($rootScope, $scope, $localStorage, $window, $grRestful, $grModal, $grAlert, $cidadeEstado, $timeout) {
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
              required: true,
              ngShow: 'shops.length > 2'
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
    delete $localStorage.griffo_cart_ready;
    return initNotification();
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

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImNsaWVudC9sYXllcnMvd2Vic2l0ZS90ZW1wbGF0ZXMvY3JhYi9jb2ZmZWUvY29udHJvbGxlcnMvdXNlckN0cmwuY29mZmVlIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0VBQUE7RUFDQSxPQUFPLENBQUMsTUFBUixDQUFlLFNBQWYsQ0FBeUIsQ0FBQyxVQUExQixDQUFxQyxVQUFyQyxFQUFpRCxTQUFDLFVBQUQsRUFBYSxNQUFiLEVBQXFCLGFBQXJCLEVBQW9DLE9BQXBDLEVBQTZDLFVBQTdDLEVBQXlELFFBQXpELEVBQW1FLFFBQW5FLEVBQTZFLGFBQTdFLEVBQTRGLFFBQTVGO0FBRTdDLFFBQUE7SUFBQSxnQkFBQSxHQUFtQixTQUFBO0FBRWYsVUFBQTtNQUFBLGdCQUFBLEdBQW1CO01BQ25CLE1BQUEsR0FBUyxPQUFPLENBQUM7TUFDakIsZ0JBQWlCLENBQUEsTUFBTSxDQUFDLGtCQUFQLENBQWpCLEdBQThDO01BQzlDLGdCQUFpQixDQUFBLE1BQU0sQ0FBQyxrQkFBUCxDQUFqQixHQUE4QztNQUM5QyxnQkFBaUIsQ0FBQSxNQUFNLENBQUMsaUJBQVAsQ0FBakIsR0FBNkM7TUFDN0MsTUFBTSxDQUFDLFdBQVAsR0FBcUIsTUFBTSxDQUFDO01BQzVCLE1BQU0sQ0FBQyxlQUFQLEdBQXlCLGdCQUFpQixDQUFBLE1BQU0sQ0FBQyxlQUFQLENBQUEsQ0FBQTtNQUUxQyxNQUFNLENBQUMsa0JBQVAsR0FBNEIsU0FBQTtlQUN4QixNQUFNLENBQUMsaUJBQVAsQ0FBeUIsU0FBQTtpQkFDckIsTUFBTSxDQUFDLE1BQVAsQ0FBYyxNQUFNLENBQUMsZUFBUCxHQUF5QixnQkFBaUIsQ0FBQSxNQUFNLENBQUMsZUFBUCxDQUFBLENBQUEsQ0FBeEQ7UUFEcUIsQ0FBekI7TUFEd0I7TUFJNUIsTUFBTSxDQUFDLE1BQVAsR0FBZ0IsU0FBQyxLQUFEO0FBQ1osWUFBQTtRQUFBLElBQUcsTUFBTSxDQUFDLFdBQVAsSUFBdUIsTUFBTSxDQUFDLGVBQVAsS0FBMEIsQ0FBcEQ7VUFDSSxNQUFBLEdBQVM7VUFDVCxJQUFHLEtBQUssQ0FBQyxNQUFOLEtBQWdCLENBQW5CO1lBQ0ksTUFBQSxHQUFTLHlCQURiO1dBQUEsTUFFSyxJQUFHLEtBQUssQ0FBQyxNQUFOLEtBQWdCLENBQW5CO1lBQ0QsTUFBQSxHQUFTLGNBRFI7V0FBQSxNQUVBLElBQUcsS0FBSyxDQUFDLE1BQU4sS0FBZ0IsQ0FBbkI7WUFDRCxNQUFBLEdBQVMsZ0JBRFI7V0FBQSxNQUVBLElBQUcsS0FBSyxDQUFDLE1BQU4sS0FBZ0IsQ0FBbkI7WUFDRCxNQUFBLEdBQVMsV0FEUjtXQUFBLE1BRUEsSUFBRyxLQUFLLENBQUMsTUFBTixLQUFnQixDQUFuQjtZQUNELE1BQUEsR0FBUyxpQ0FEUjs7aUJBRUwsTUFBTSxDQUFDLGtCQUFQLENBQTBCLG9CQUFBLEdBQXVCLEtBQUssQ0FBQyxPQUF2RCxFQUNJO1lBQUEsSUFBQSxFQUFNLGlCQUFBLEdBQW9CLE1BQXBCLEdBQTZCLEdBQW5DO1lBQ0EsSUFBQSxFQUFNLFVBQVUsQ0FBQyxNQUFNLENBQUMsWUFBbEIsR0FBaUMsNEJBQWpDLEdBQWdFLEtBQUssQ0FBQyxNQUF0RSxHQUErRSxNQURyRjtXQURKLEVBWko7O01BRFk7YUFpQmhCLE1BQU0sQ0FBQyxrQkFBUCxDQUFBO0lBL0JlO0lBaUNuQixVQUFVLENBQUMsRUFBRSxDQUFDLEtBQWQsR0FDSTtNQUFBLElBQUEsRUFBTSxrQkFBTjtNQUNBLElBQUEsRUFBTSxpQkFETjs7SUFHRCxDQUFBLFNBQUE7QUFDQyxVQUFBO01BQUEsS0FBQSxHQUFRLFFBQVEsQ0FBQyxLQUFELENBQVIsQ0FBQTtNQUNSLFFBQUEsR0FBVztNQUVYLE1BQU0sQ0FBQyxRQUFQLEdBQWtCLFNBQUMsQ0FBRDtRQUNkLElBQUcsQ0FBQSxLQUFLLE1BQVI7QUFDSSxpQkFBTyxTQURYO1NBQUEsTUFBQTtVQUdJLFFBQUEsR0FBVyxFQUhmOztNQURjO01BT2xCLE1BQU0sQ0FBQyxLQUFQLEdBQWU7TUFFZixNQUFNLENBQUMsUUFBUCxHQUFrQixTQUFDLEVBQUQ7QUFDZCxZQUFBO1FBQUEsSUFBQSxHQUFPO1FBQ1AsT0FBTyxDQUFDLE9BQVIsQ0FBZ0IsTUFBTSxDQUFDLEtBQXZCLEVBQThCLFNBQUMsSUFBRDtVQUMxQixJQUFxQixJQUFJLENBQUMsS0FBTCxLQUFjLEVBQW5DO21CQUFBLElBQUEsR0FBTyxJQUFJLENBQUMsTUFBWjs7UUFEMEIsQ0FBOUI7ZUFFQTtNQUpjO01BTWxCLFVBQVUsQ0FBQyxJQUFYLENBQ0k7UUFBQSxNQUFBLEVBQVEsTUFBUjtRQUNBLE1BQUEsRUFBUSxRQURSO09BREosQ0FFcUIsQ0FBQyxJQUZ0QixDQUUyQixTQUFDLENBQUQ7UUFDdkIsSUFBRyxDQUFDLENBQUMsUUFBTDtpQkFDSSxNQUFNLENBQUMsS0FBUCxHQUFlLENBQUMsQ0FBQyxTQURyQjs7TUFEdUIsQ0FGM0I7TUFNQSxNQUFNLENBQUMsTUFBUCxHQUFnQjtNQUVoQixPQUFPLENBQUMsT0FBUixDQUFnQixhQUFhLENBQUMsR0FBRyxDQUFDLE9BQWxCLENBQUEsQ0FBaEIsRUFBNkMsU0FBQyxDQUFEO2VBQ3pDLE1BQU0sQ0FBQyxNQUFNLENBQUMsSUFBZCxDQUNJO1VBQUEsS0FBQSxFQUFPLENBQUUsQ0FBQSxDQUFBLENBQVQ7VUFDQSxLQUFBLEVBQU8sQ0FBRSxDQUFBLENBQUEsQ0FEVDtTQURKO01BRHlDLENBQTdDO01BS0EsVUFBVSxDQUFDLElBQVgsQ0FDSTtRQUFBLE1BQUEsRUFBUSxRQUFSO1FBQ0EsTUFBQSxFQUFRLEtBRFI7UUFFQSxFQUFBLEVBQUksVUFBVSxDQUFDLE1BQU0sQ0FBQyxJQUFJLENBQUMsRUFGM0I7T0FESixDQUdrQyxDQUFDLElBSG5DLENBR3dDLFNBQUMsQ0FBRDtRQUNwQyxJQUFHLENBQUMsQ0FBQyxRQUFMO1VBQ0ksTUFBTSxDQUFDLFlBQVksQ0FBQyxJQUFwQixHQUEyQixDQUFDLENBQUM7aUJBQzdCLE1BQU0sQ0FBQyxJQUFJLENBQUMsY0FBWixDQUFBLEVBRko7O01BRG9DLENBSHhDO01BUUEsTUFBTSxDQUFDLFlBQVAsR0FDSTtRQUFBLElBQUEsRUFBTSxFQUFOO1FBQ0EsTUFBQSxFQUFRO1VBQ0o7WUFDSSxRQUFBLEVBQVUsTUFEZDtZQUVJLElBQUEsRUFBTSxNQUZWO1lBR0ksS0FBQSxFQUFPLE1BSFg7WUFJSSxPQUFBLEVBQVMsRUFKYjtZQUtJLElBQUEsRUFBTTtjQUFBLFFBQUEsRUFBVSxJQUFWO2FBTFY7WUFNSSxJQUFBLEVBQU07Y0FBQSxRQUFBLEVBQVUsaUJBQVY7YUFOVjtXQURJLEVBU0o7WUFDSSxRQUFBLEVBQVUsT0FEZDtZQUVJLElBQUEsRUFBTSxPQUZWO1lBR0ksS0FBQSxFQUFPLFVBSFg7WUFJSSxPQUFBLEVBQ0k7Y0FBQSxFQUFBLEVBQUksRUFBSjtjQUNBLEVBQUEsRUFBSSxFQURKO2NBRUEsRUFBQSxFQUFJLENBRko7YUFMUjtZQVFJLElBQUEsRUFDSTtjQUFBLFVBQUEsRUFBWSxnQ0FBWjtjQUNBLFFBQUEsRUFBVSxJQURWO2FBVFI7WUFXSSxJQUFBLEVBQ0k7Y0FBQSxRQUFBLEVBQVUsb0JBQVY7Y0FDQSxJQUFBLEVBQU0sdUJBRE47YUFaUjtXQVRJLEVBd0JKO1lBQ0ksUUFBQSxFQUFVLGFBRGQ7WUFFSSxJQUFBLEVBQU0sYUFGVjtZQUdJLEtBQUEsRUFBTyxTQUhYO1lBSUksT0FBQSxFQUNJO2NBQUEsRUFBQSxFQUFJLEVBQUo7Y0FDQSxFQUFBLEVBQUksRUFESjtjQUVBLEVBQUEsRUFBSSxDQUZKO2FBTFI7WUFRSSxJQUFBLEVBQ0k7Y0FBQSxVQUFBLEVBQVksMEJBQVo7Y0FDQSxRQUFBLEVBQVUsSUFEVjthQVRSO1lBV0ksSUFBQSxFQUNJO2NBQUEsUUFBQSxFQUFVLG1CQUFWO2NBQ0EsSUFBQSxFQUFNLHNCQUROO2FBWlI7V0F4QkksRUF1Q0o7WUFDSSxRQUFBLEVBQVUsT0FEZDtZQUVJLElBQUEsRUFBTSxPQUZWO1lBR0ksS0FBQSxFQUFPLFFBSFg7WUFJSSxXQUFBLEVBQWEscUJBSmpCO1lBS0ksT0FBQSxFQUFTLEVBTGI7WUFNSSxJQUFBLEVBQU07Y0FBQSxRQUFBLEVBQVUsSUFBVjthQU5WO1lBT0ksSUFBQSxFQUNJO2NBQUEsUUFBQSxFQUFVLGtCQUFWO2NBQ0EsS0FBQSxFQUFPLGlDQURQO2FBUlI7V0F2Q0ksRUFrREo7WUFDSSxRQUFBLEVBQVUsU0FEZDtZQUVJLElBQUEsRUFBTSxNQUZWO1lBR0ksS0FBQSxFQUFPLFVBSFg7WUFJSSxPQUFBLEVBQVMsQ0FKYjtZQUtJLElBQUEsRUFBTTtjQUFBLFFBQUEsRUFBVSxJQUFWO2FBTFY7WUFNSSxJQUFBLEVBQU07Y0FBQSxRQUFBLEVBQVUscUJBQVY7YUFOVjtXQWxESSxFQTBESjtZQUNJLFFBQUEsRUFBVSxRQURkO1lBRUksSUFBQSxFQUFNLE1BRlY7WUFHSSxLQUFBLEVBQU8sUUFIWDtZQUlJLE1BQUEsRUFBUSxJQUpaO1lBS0ksT0FBQSxFQUFTLENBTGI7WUFNSSxJQUFBLEVBQU07Y0FBQSxRQUFBLEVBQVUsSUFBVjthQU5WO1lBT0ksSUFBQSxFQUFNO2NBQUEsUUFBQSxFQUFVLG1CQUFWO2FBUFY7V0ExREksRUFtRUo7WUFDSSxRQUFBLEVBQVUsWUFEZDtZQUVJLElBQUEsRUFBTSxNQUZWO1lBR0ksS0FBQSxFQUFPLGFBSFg7WUFJSSxPQUFBLEVBQVMsQ0FKYjtXQW5FSSxFQXlFSjtZQUNJLFFBQUEsRUFBVSxVQURkO1lBRUksSUFBQSxFQUFNLE1BRlY7WUFHSSxLQUFBLEVBQU8sUUFIWDtZQUlJLE9BQUEsRUFBUyxDQUpiO1lBS0ksSUFBQSxFQUFNO2NBQUEsUUFBQSxFQUFVLElBQVY7YUFMVjtZQU1JLElBQUEsRUFBTTtjQUFBLFFBQUEsRUFBVSxtQkFBVjthQU5WO1dBekVJLEVBaUZKO1lBQ0ksUUFBQSxFQUFVLE1BRGQ7WUFFSSxJQUFBLEVBQU0sUUFGVjtZQUdJLEtBQUEsRUFBTyxRQUhYO1lBSUksT0FBQSxFQUFTLENBSmI7WUFLSSxJQUFBLEVBQU0sNkNBTFY7WUFNSSxJQUFBLEVBQU07Y0FBQSxRQUFBLEVBQVUsSUFBVjthQU5WO1lBT0ksSUFBQSxFQUFNO2NBQUEsUUFBQSxFQUFVLHNCQUFWO2FBUFY7V0FqRkksRUEwRko7WUFDSSxRQUFBLEVBQVUsT0FEZDtZQUVJLElBQUEsRUFBTSxRQUZWO1lBR0ksS0FBQSxFQUFPLFFBSFg7WUFJSSxPQUFBLEVBQVMsQ0FKYjtZQUtJLElBQUEsRUFBTSw2Q0FMVjtZQU1JLElBQUEsRUFBTTtjQUFBLFFBQUEsRUFBVSxJQUFWO2FBTlY7WUFPSSxJQUFBLEVBQU07Y0FBQSxRQUFBLEVBQVUscUJBQVY7YUFQVjtXQTFGSSxFQW1HSjtZQUNJLFFBQUEsRUFBVSxVQURkO1lBRUksSUFBQSxFQUFNLFFBRlY7WUFHSSxLQUFBLEVBQU8sbUJBSFg7WUFJSSxJQUFBLEVBQU0sNENBSlY7WUFLSSxPQUFBLEVBQVMsRUFMYjtZQU1JLElBQUEsRUFBTTtjQUFBLFFBQUEsRUFBVSxJQUFWO2NBQWdCLE1BQUEsRUFBUSxrQkFBeEI7YUFOVjtZQU9JLElBQUEsRUFBTTtjQUFBLFFBQUEsRUFBVSxpQ0FBVjthQVBWO1dBbkdJO1NBRFI7UUE4R0EsTUFBQSxFQUFRLFNBQUMsSUFBRDtVQUNKLElBQVUsQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLE1BQXZCO0FBQUEsbUJBQUE7O2lCQUNBLFVBQVUsQ0FBQyxNQUFYLENBQ0k7WUFBQSxNQUFBLEVBQVEsUUFBUjtZQUNBLE1BQUEsRUFBUSxtQkFEUjtZQUVBLEVBQUEsRUFBSSxVQUFVLENBQUMsTUFBTSxDQUFDLElBQUksQ0FBQyxFQUYzQjtZQUdBLElBQUEsRUFBTSxJQUhOO1dBREosQ0FJZSxDQUFDLElBSmhCLENBSXFCLFNBQUMsQ0FBRDtZQUNqQixJQUFHLENBQUMsQ0FBQyxRQUFMO2NBQ0ksTUFBTSxDQUFDLFFBQVAsQ0FBZ0IsS0FBaEI7Y0FDQSxNQUFNLENBQUMsSUFBSSxDQUFDLGNBQVosQ0FBQSxFQUZKOzttQkFHQSxLQUFLLENBQUMsSUFBTixDQUFXLENBQUMsQ0FBQyxNQUFiLEVBQXFCLENBQUMsQ0FBQyxPQUF2QjtVQUppQixDQUpyQjtRQUZJLENBOUdSOztNQTBISixNQUFNLENBQUMsTUFBUCxDQUFjLHlCQUFkLEVBQXlDLFNBQUMsQ0FBRDtRQUNyQyxJQUFHLENBQUg7VUFDSSxJQUFHLE1BQU0sQ0FBQyxZQUFZLENBQUMsSUFBSSxDQUFDLEtBQTVCO1lBQ0ksTUFBTSxDQUFDLE1BQVAsR0FBZ0I7bUJBQ2hCLE9BQU8sQ0FBQyxPQUFSLENBQWdCLGFBQWEsQ0FBQyxHQUFHLENBQUMsT0FBbEIsQ0FBMEIsTUFBTSxDQUFDLFlBQVksQ0FBQyxJQUFJLENBQUMsS0FBbkQsQ0FBaEIsRUFBMkUsU0FBQyxDQUFEO3FCQUN2RSxNQUFNLENBQUMsTUFBTSxDQUFDLElBQWQsQ0FDSTtnQkFBQSxLQUFBLEVBQU8sQ0FBUDtnQkFDQSxLQUFBLEVBQU8sQ0FEUDtlQURKO1lBRHVFLENBQTNFLEVBRko7V0FBQSxNQUFBO21CQU9JLE1BQU0sQ0FBQyxNQUFQLEdBQWdCLEdBUHBCO1dBREo7U0FBQSxNQUFBO2lCQVVJLE1BQU0sQ0FBQyxZQUFZLENBQUMsSUFBSSxDQUFDLElBQXpCLEdBQWdDLE9BVnBDOztNQURxQyxDQUF6QzthQWFBLE1BQU0sQ0FBQyxjQUFQLEdBQXdCLFNBQUE7QUFDcEIsWUFBQTtRQUFBLEtBQUEsR0FBUSxRQUFRLENBQUMsS0FBRCxDQUFSLENBQ0o7VUFBQSxJQUFBLEVBQU0saUJBQU47VUFDQSxLQUFBLEVBQU8sZUFEUDtVQUVBLElBQUEsRUFBTSxJQUZOO1VBR0EsS0FBQSxFQUFPLE1BQU0sQ0FBQyxZQUFQLEdBQXNCLGdDQUg3QjtVQUlBLE1BQUEsRUFBUTtZQUFBLEVBQUEsRUFBSSxNQUFNLENBQUMsWUFBWSxDQUFDLElBQUksQ0FBQyxRQUE3QjtXQUpSO1VBS0EsT0FBQSxFQUFTO1lBQ0w7Y0FDSSxJQUFBLEVBQU0sU0FEVjtjQUVJLEtBQUEsRUFBTyxTQUZYO2NBR0ksT0FBQSxFQUFTLFNBQUMsTUFBRCxFQUFTLFFBQVQsRUFBbUIsVUFBbkI7Z0JBQ0wsTUFBTSxDQUFDLElBQUksQ0FBQyxNQUFaLENBQUE7Y0FESyxDQUhiO2FBREssRUFTTDtjQUNJLElBQUEsRUFBTSxRQURWO2NBRUksS0FBQSxFQUFPLFVBRlg7Y0FHSSxPQUFBLEVBQVMsU0FBQyxNQUFELEVBQVMsUUFBVCxFQUFtQixVQUFuQjtnQkFDTCxVQUFVLENBQUMsS0FBWCxDQUFBO2NBREssQ0FIYjthQVRLO1dBTFQ7U0FESTtlQXdCUixLQUFLLENBQUMsSUFBTixDQUFBO01BekJvQjtJQWhMekIsQ0FBQSxDQUFILENBQUE7SUEyTUcsQ0FBQSxTQUFBO0FBRUMsVUFBQTtNQUFBLFNBQUEsR0FBWSxTQUFBO0FBQ1IsWUFBQTtRQUFBLElBQUcsQ0FBQyxNQUFNLENBQUMsYUFBWDtVQUNJLFVBQVUsQ0FBQyxJQUFYLENBQ0k7WUFBQSxNQUFBLEVBQVEsT0FBUjtZQUNBLE1BQUEsRUFBUSxLQURSO1lBRUEsTUFBQSxFQUFRLGFBQUEsR0FBZ0IsVUFBVSxDQUFDLE1BQU0sQ0FBQyxJQUFJLENBQUMsRUFGL0M7V0FESixDQUlBLENBQUMsSUFKRCxDQUlNLFNBQUMsQ0FBRDtZQUNGLElBQUcsQ0FBQyxDQUFDLFFBQUw7Y0FDSSxPQUFPLENBQUMsT0FBUixDQUFnQixDQUFDLENBQUMsUUFBbEIsRUFBNEIsU0FBQyxLQUFELEVBQVEsRUFBUjt1QkFDeEIsT0FBTyxDQUFDLE9BQVIsQ0FBZ0IsTUFBTSxDQUFDLE1BQXZCLEVBQStCLFNBQUMsTUFBRCxFQUFTLEdBQVQ7a0JBQzNCLElBQUcsTUFBTSxDQUFDLE9BQVAsS0FBa0IsS0FBSyxDQUFDLE9BQXhCLElBQW9DLE1BQU0sQ0FBQyxNQUFQLEtBQWlCLEtBQUssQ0FBQyxNQUE5RDsyQkFDSSxNQUFNLENBQUMsTUFBUCxDQUFjLEtBQWQsRUFESjs7Z0JBRDJCLENBQS9CO2NBRHdCLENBQTVCO3FCQUlBLE1BQU0sQ0FBQyxNQUFQLEdBQWdCLENBQUMsQ0FBQyxTQUx0QjthQUFBLE1BQUE7cUJBT0ksTUFBTSxDQUFDLE1BQVAsR0FBZ0IsR0FQcEI7O1VBREUsQ0FKTjtpQkFjQSxnQkFBQSxHQUFtQixRQUFBLENBQVMsU0FBQTttQkFDeEIsU0FBQSxDQUFBO1VBRHdCLENBQVQsRUFFakIsS0FGaUIsRUFmdkI7U0FBQSxNQUFBO2lCQW1CSSxVQUFVLENBQUMsSUFBWCxDQUNJO1lBQUEsTUFBQSxFQUFRLE9BQVI7WUFDQSxNQUFBLEVBQVEsV0FEUjtZQUVBLE1BQUEsRUFBUSxhQUFBLEdBQWdCLFVBQVUsQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLEVBRi9DO1dBREosQ0FJQSxDQUFDLElBSkQsQ0FJTSxTQUFDLENBQUQ7WUFDRixJQUFHLENBQUMsQ0FBQyxRQUFMO3FCQUNJLE1BQU0sQ0FBQyxNQUFQLEdBQWdCLENBQUMsQ0FBQyxTQUR0QjthQUFBLE1BQUE7cUJBR0ksTUFBTSxDQUFDLE1BQVAsR0FBZ0IsR0FIcEI7O1VBREUsQ0FKTixFQW5CSjs7TUFEUTtNQThCWixNQUFNLENBQUMsTUFBUCxHQUFnQjtNQUNoQixnQkFBQSxHQUFtQjtNQUNuQixNQUFNLENBQUMsTUFBUCxDQUFjLGVBQWQsRUFBK0IsU0FBQTtRQUMzQixRQUFRLENBQUMsTUFBVCxDQUFnQixnQkFBaEI7ZUFDQSxTQUFBLENBQUE7TUFGMkIsQ0FBL0I7TUFHQSxTQUFBLENBQUE7YUFDQSxNQUFNLENBQUMsU0FBUCxHQUFtQixTQUFDLEtBQUQ7QUFDZixZQUFBO1FBQUEsS0FBQSxHQUFRLFFBQVEsQ0FBQyxLQUFELENBQVIsQ0FDSjtVQUFBLElBQUEsRUFBTSxZQUFOO1VBQ0EsS0FBQSxFQUFPLHlCQUFBLEdBQTRCLEtBQUssQ0FBQyxPQUR6QztVQUVBLElBQUEsRUFBTSxJQUZOO1VBR0EsS0FBQSxFQUFPLE1BQU0sQ0FBQyxZQUFQLEdBQXNCLDJCQUg3QjtVQUlBLE1BQUEsRUFDSTtZQUFBLEtBQUEsRUFBTyxLQUFQO1lBQ0EsUUFBQSxFQUFVLE1BQU0sQ0FBQyxRQURqQjtXQUxKO1VBT0EsT0FBQSxFQUFTO1lBQ0w7Y0FDSSxJQUFBLEVBQU0sU0FEVjtjQUVJLEtBQUEsRUFBTyxJQUZYO2NBR0ksT0FBQSxFQUFTLFNBQUMsTUFBRCxFQUFTLFFBQVQsRUFBbUIsVUFBbkI7dUJBQ0wsVUFBVSxDQUFDLEtBQVgsQ0FBQTtjQURLLENBSGI7YUFESztXQVBUO1NBREk7ZUFnQlIsS0FBSyxDQUFDLElBQU4sQ0FBQTtNQWpCZTtJQXRDcEIsQ0FBQSxDQUFILENBQUE7SUF5REEsT0FBTyxhQUFhLENBQUM7V0FFbEIsZ0JBQUgsQ0FBQTtFQTdTNkMsQ0FBakQ7O0VBK1NBLE9BQU8sQ0FBQyxNQUFSLENBQWUsU0FBZixDQUF5QixDQUFDLFVBQTFCLENBQXFDLG9CQUFyQyxFQUEyRCxTQUFDLE1BQUQsRUFBUyxRQUFULEVBQW1CLFVBQW5CLEVBQStCLFFBQS9CO0FBQ3ZELFFBQUE7SUFBQSxLQUFBLEdBQVEsUUFBUSxDQUFDLEtBQUQsQ0FBUixDQUFBO0lBQ1IsTUFBTSxDQUFDLFlBQVAsR0FDSTtNQUFBLElBQUEsRUFBTSxFQUFOO01BQ0EsTUFBQSxFQUFRO1FBQ0o7VUFDSSxRQUFBLEVBQVUsYUFEZDtVQUVJLElBQUEsRUFBTSxVQUZWO1VBR0ksS0FBQSxFQUFPLGFBSFg7VUFJSSxJQUFBLEVBQ0k7WUFBQSxRQUFBLEVBQVUsSUFBVjtZQUNBLFNBQUEsRUFBVyxJQURYO1dBTFI7VUFPSSxJQUFBLEVBQU07WUFBQSxRQUFBLEVBQVUsNkJBQVY7V0FQVjtTQURJLEVBVUo7VUFBRSxJQUFBLEVBQU0sSUFBUjtTQVZJLEVBV0o7VUFDSSxRQUFBLEVBQVUsVUFEZDtVQUVJLElBQUEsRUFBTSxVQUZWO1VBR0ksS0FBQSxFQUFPLFlBSFg7VUFJSSxJQUFBLEVBQ0k7WUFBQSxRQUFBLEVBQVUsSUFBVjtZQUNBLFdBQUEsRUFBYSxDQURiO1lBRUEsV0FBQSxFQUFhLEVBRmI7V0FMUjtVQVFJLElBQUEsRUFDSTtZQUFBLFFBQUEsRUFBVSw0QkFBVjtZQUNBLFNBQUEsRUFBVyw2Q0FEWDtZQUVBLFNBQUEsRUFBVyw0Q0FGWDtZQUdBLE9BQUEsRUFBUyx5QkFIVDtXQVRSO1NBWEksRUF5Qko7VUFDSSxRQUFBLEVBQVUsWUFEZDtVQUVJLElBQUEsRUFBTSxVQUZWO1VBR0ksS0FBQSxFQUFPLDJCQUhYO1VBSUksSUFBQSxFQUNJO1lBQUEsUUFBQSxFQUFVLElBQVY7WUFDQSxlQUFBLEVBQWlCLDRCQURqQjtXQUxSO1VBT0ksSUFBQSxFQUNJO1lBQUEsUUFBQSxFQUFVLHNDQUFWO1lBQ0EsS0FBQSxFQUFPLCtCQURQO1dBUlI7U0F6Qkk7T0FEUjtNQXNDQSxNQUFBLEVBQVEsU0FBQyxJQUFEO0FBQ0osWUFBQTtRQUFBLE9BQUEsR0FDSTtVQUFBLGNBQUEsRUFBZ0IsSUFBSSxDQUFDLFdBQXJCO1VBQ0EsVUFBQSxFQUFZLElBQUksQ0FBQyxRQURqQjtVQUVBLGFBQUEsRUFBZSxJQUFJLENBQUMsVUFGcEI7O2VBR0osVUFBVSxDQUFDLE1BQVgsQ0FDSTtVQUFBLE1BQUEsRUFBUSxRQUFSO1VBQ0EsTUFBQSxFQUFRLGlCQURSO1VBRUEsRUFBQSxFQUFJLE1BQU0sQ0FBQyxPQUFPLENBQUMsRUFGbkI7VUFHQSxJQUFBLEVBQU0sT0FITjtTQURKLENBS0EsQ0FBQyxJQUxELENBS00sU0FBQyxDQUFEO1VBQ0YsSUFBRyxDQUFDLENBQUMsUUFBTDtZQUNJLE1BQU0sQ0FBQyxLQUFLLENBQUMsS0FBYixDQUFBO21CQUNBLEtBQUssQ0FBQyxJQUFOLENBQVcsU0FBWCxFQUFzQiwrQkFBdEIsRUFGSjtXQUFBLE1BQUE7bUJBSUksS0FBSyxDQUFDLElBQU4sQ0FBVyxDQUFDLENBQUMsTUFBYixFQUFxQixDQUFDLENBQUMsT0FBdkIsRUFKSjs7UUFERSxDQUxOLEVBV0UsU0FBQTtpQkFDRSxLQUFLLENBQUMsSUFBTixDQUFXLFFBQVgsRUFBcUIsYUFBckI7UUFERixDQVhGO01BTEksQ0F0Q1I7O1dBeURKLE1BQU0sQ0FBQyxNQUFQLENBQWMsTUFBZCxFQUFzQixTQUFDLElBQUQ7YUFDbEIsTUFBTSxDQUFDLE9BQU8sQ0FBQyxJQUFmLEdBQXNCO0lBREosQ0FBdEI7RUE1RHVELENBQTNEOztFQStEQSxPQUFPLENBQUMsTUFBUixDQUFlLFNBQWYsQ0FBeUIsQ0FBQyxTQUExQixDQUFvQyxpQkFBcEMsRUFBdUQsU0FBQTtXQUNuRDtNQUFBLFFBQUEsRUFBVSxHQUFWO01BQ0EsT0FBQSxFQUFTLFNBRFQ7TUFFQSxJQUFBLEVBQU0sU0FBQyxLQUFELEVBQVEsT0FBUixFQUFpQixLQUFqQixFQUF3QixPQUF4QjtBQUNGLFlBQUE7UUFBQSxRQUFBLEdBQVcsU0FBQyxTQUFEO0FBQ1AsY0FBQTtVQUFBLFFBQUEsR0FBVyxLQUFLLENBQUMsS0FBTixDQUFZLEtBQUssQ0FBQyxlQUFsQjtVQUNYLE9BQU8sQ0FBQyxZQUFSLENBQXFCLE9BQXJCLEVBQThCLE9BQU8sQ0FBQyxRQUFSLENBQWlCLFNBQWpCLENBQUEsSUFBK0IsU0FBQSxLQUFhLFFBQTFFO2lCQUNBO1FBSE87UUFJWCxPQUFPLENBQUMsUUFBUSxDQUFDLElBQWpCLENBQXNCLFFBQXRCO2VBQ0EsS0FBSyxDQUFDLE1BQU4sQ0FBYSxLQUFLLENBQUMsZUFBbkIsRUFBb0MsU0FBQyxLQUFEO2lCQUNoQyxRQUFBLENBQVMsT0FBTyxDQUFDLFVBQWpCO1FBRGdDLENBQXBDO01BTkUsQ0FGTjs7RUFEbUQsQ0FBdkQ7QUEvV0EiLCJmaWxlIjoiY2xpZW50L2xheWVycy93ZWJzaXRlL3RlbXBsYXRlcy9jcmFiL2pzL2NvbnRyb2xsZXJzL3VzZXJDdHJsLmpzIiwic291cmNlUm9vdCI6Ii9zb3VyY2UvIiwic291cmNlc0NvbnRlbnQiOlsiJ3VzZSBzdHJpY3QnXG5hbmd1bGFyLm1vZHVsZSgnbWFpbkFwcCcpLmNvbnRyb2xsZXIgJ3VzZXJDdHJsJywgKCRyb290U2NvcGUsICRzY29wZSwgJGxvY2FsU3RvcmFnZSwgJHdpbmRvdywgJGdyUmVzdGZ1bCwgJGdyTW9kYWwsICRnckFsZXJ0LCAkY2lkYWRlRXN0YWRvLCAkdGltZW91dCkgLT5cblxuICAgIGluaXROb3RpZmljYXRpb24gPSAtPlxuXG4gICAgICAgIHBlcm1pc3Npb25MZXZlbHMgPSB7fVxuICAgICAgICBub3RpZnkgPSAkd2luZG93Lm5vdGlmeVxuICAgICAgICBwZXJtaXNzaW9uTGV2ZWxzW25vdGlmeS5QRVJNSVNTSU9OX0dSQU5URURdID0gMFxuICAgICAgICBwZXJtaXNzaW9uTGV2ZWxzW25vdGlmeS5QRVJNSVNTSU9OX0RFRkFVTFRdID0gMVxuICAgICAgICBwZXJtaXNzaW9uTGV2ZWxzW25vdGlmeS5QRVJNSVNTSU9OX0RFTklFRF0gPSAyXG4gICAgICAgICRzY29wZS5pc1N1cHBvcnRlZCA9IG5vdGlmeS5pc1N1cHBvcnRlZFxuICAgICAgICAkc2NvcGUucGVybWlzc2lvbkxldmVsID0gcGVybWlzc2lvbkxldmVsc1tub3RpZnkucGVybWlzc2lvbkxldmVsKCldXG5cbiAgICAgICAgJHNjb3BlLnJlcXVlc3RQZXJtaXNzaW9ucyA9IC0+XG4gICAgICAgICAgICBub3RpZnkucmVxdWVzdFBlcm1pc3Npb24gLT5cbiAgICAgICAgICAgICAgICAkc2NvcGUuJGFwcGx5ICRzY29wZS5wZXJtaXNzaW9uTGV2ZWwgPSBwZXJtaXNzaW9uTGV2ZWxzW25vdGlmeS5wZXJtaXNzaW9uTGV2ZWwoKV1cblxuICAgICAgICAkc2NvcGUubm90aWZ5ID0gKG9yZGVyKSAtPlxuICAgICAgICAgICAgaWYgJHNjb3BlLmlzU3VwcG9ydGVkIGFuZCAkc2NvcGUucGVybWlzc2lvbkxldmVsID09IDBcbiAgICAgICAgICAgICAgICBzdGF0dXMgPSB1bmRlZmluZWRcbiAgICAgICAgICAgICAgICBpZiBvcmRlci5zdGF0dXMgPT0gMFxuICAgICAgICAgICAgICAgICAgICBzdGF0dXMgPSAnQWd1YXJkYW5kbyBhdGVuZGltZW50bydcbiAgICAgICAgICAgICAgICBlbHNlIGlmIG9yZGVyLnN0YXR1cyA9PSAxXG4gICAgICAgICAgICAgICAgICAgIHN0YXR1cyA9ICdFbSBwcm9kdcOnw6NvJ1xuICAgICAgICAgICAgICAgIGVsc2UgaWYgb3JkZXIuc3RhdHVzID09IDJcbiAgICAgICAgICAgICAgICAgICAgc3RhdHVzID0gJ0VtIHRyYW5zcG9ydGUnXG4gICAgICAgICAgICAgICAgZWxzZSBpZiBvcmRlci5zdGF0dXMgPT0gM1xuICAgICAgICAgICAgICAgICAgICBzdGF0dXMgPSAnRW50cmVndWUnXG4gICAgICAgICAgICAgICAgZWxzZSBpZiBvcmRlci5zdGF0dXMgPT0gNFxuICAgICAgICAgICAgICAgICAgICBzdGF0dXMgPSAnQ29uY2x1w61kbywgYWd1YXJkYW5kbyByZXRpcmFkYSdcbiAgICAgICAgICAgICAgICBub3RpZnkuY3JlYXRlTm90aWZpY2F0aW9uICdTdGF0dXMgZG8gcGVkaWRvICMnICsgb3JkZXIuaWRvcmRlcixcbiAgICAgICAgICAgICAgICAgICAgYm9keTogJ0FsdGVyYWRvIHBhcmEgXCInICsgc3RhdHVzICsgJ1wiJ1xuICAgICAgICAgICAgICAgICAgICBpY29uOiAkcm9vdFNjb3BlLkdSSUZGTy50ZW1wbGF0ZVBhdGggKyAnaW1hZ2Uvbm90aWZpY2F0aW9uLXN0YXR1cy0nICsgb3JkZXIuc3RhdHVzICsgJy5wbmcnXG5cbiAgICAgICAgJHNjb3BlLnJlcXVlc3RQZXJtaXNzaW9ucygpXG5cbiAgICAkcm9vdFNjb3BlLmdyLnRpdGxlID1cbiAgICAgICAgaWNvbjogJ2ZhIGZhLWZ3IGZhLWxvY2snXG4gICAgICAgIHRleHQ6ICdBY2Vzc28gcmVzdHJpdG8nXG5cbiAgICBkbyAtPlxuICAgICAgICBhbGVydCA9ICRnckFsZXJ0Lm5ldygpXG4gICAgICAgIGVkaXRhYmxlID0gZmFsc2VcblxuICAgICAgICAkc2NvcGUuZWRpdGFibGUgPSAoZSkgLT5cbiAgICAgICAgICAgIGlmIGUgPT0gdW5kZWZpbmVkXG4gICAgICAgICAgICAgICAgcmV0dXJuIGVkaXRhYmxlXG4gICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgZWRpdGFibGUgPSBlXG4gICAgICAgICAgICByZXR1cm5cblxuICAgICAgICAkc2NvcGUuc2hvcHMgPSBbXVxuXG4gICAgICAgICRzY29wZS5maW5kU2hvcCA9IChpZCkgLT5cbiAgICAgICAgICAgIG5hbWUgPSB1bmRlZmluZWRcbiAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCAkc2NvcGUuc2hvcHMsIChzaG9wKSAtPlxuICAgICAgICAgICAgICAgIG5hbWUgPSBzaG9wLmxhYmVsIGlmIHNob3AudmFsdWUgPT0gaWRcbiAgICAgICAgICAgIG5hbWVcblxuICAgICAgICAkZ3JSZXN0ZnVsLmZpbmQoXG4gICAgICAgICAgICBtb2R1bGU6ICdzaG9wJ1xuICAgICAgICAgICAgYWN0aW9uOiAnc2VsZWN0JykudGhlbiAocikgLT5cbiAgICAgICAgICAgIGlmIHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAkc2NvcGUuc2hvcHMgPSByLnJlc3BvbnNlXG5cbiAgICAgICAgJHNjb3BlLnN0YXRlcyA9IFtdXG5cbiAgICAgICAgYW5ndWxhci5mb3JFYWNoICRjaWRhZGVFc3RhZG8uZ2V0LmVzdGFkb3MoKSwgKGUpIC0+XG4gICAgICAgICAgICAkc2NvcGUuc3RhdGVzLnB1c2hcbiAgICAgICAgICAgICAgICB2YWx1ZTogZVswXVxuICAgICAgICAgICAgICAgIGxhYmVsOiBlWzFdXG5cbiAgICAgICAgJGdyUmVzdGZ1bC5maW5kKFxuICAgICAgICAgICAgbW9kdWxlOiAnY2xpZW50J1xuICAgICAgICAgICAgYWN0aW9uOiAnZ2V0J1xuICAgICAgICAgICAgaWQ6ICRyb290U2NvcGUuR1JJRkZPLnVzZXIuaWQpLnRoZW4gKHIpIC0+XG4gICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgJHNjb3BlLmZvcm1TZXR0aW5ncy5kYXRhID0gci5yZXNwb25zZVxuICAgICAgICAgICAgICAgICRzY29wZS5mb3JtLnVwZGF0ZURlZmF1bHRzKClcblxuICAgICAgICAkc2NvcGUuZm9ybVNldHRpbmdzID1cbiAgICAgICAgICAgIGRhdGE6IHt9XG4gICAgICAgICAgICBzY2hlbWE6IFtcbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAnbmFtZSdcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3RleHQnXG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiAnTm9tZSdcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczogMTJcbiAgICAgICAgICAgICAgICAgICAgYXR0cjogcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgbXNnczogcmVxdWlyZWQ6ICdQcmVlbmNoYSBvIG5vbWUnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdwaG9uZSdcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3Bob25lJ1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ1RlbGVmb25lJ1xuICAgICAgICAgICAgICAgICAgICBjb2x1bW5zOlxuICAgICAgICAgICAgICAgICAgICAgICAgeHM6IDEyXG4gICAgICAgICAgICAgICAgICAgICAgICBzbTogMTJcbiAgICAgICAgICAgICAgICAgICAgICAgIG1kOiA2XG4gICAgICAgICAgICAgICAgICAgIGF0dHI6XG4gICAgICAgICAgICAgICAgICAgICAgICBuZ1JlcXVpcmVkOiAnIWZvcm1TZXR0aW5ncy5kYXRhLm1vYmlsZXBob25lJ1xuICAgICAgICAgICAgICAgICAgICAgICAgcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgbXNnczpcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlcXVpcmVkOiAnSW5mb3JtZSBvIFRlbGVmb25lJ1xuICAgICAgICAgICAgICAgICAgICAgICAgbWFzazogJ08gdGVsZWZvbmUgw6kgaW52w6FsaWRvJ1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAnbW9iaWxlcGhvbmUnXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdtb2JpbGVwaG9uZSdcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdDZWx1bGFyJ1xuICAgICAgICAgICAgICAgICAgICBjb2x1bW5zOlxuICAgICAgICAgICAgICAgICAgICAgICAgeHM6IDEyXG4gICAgICAgICAgICAgICAgICAgICAgICBzbTogMTJcbiAgICAgICAgICAgICAgICAgICAgICAgIG1kOiA2XG4gICAgICAgICAgICAgICAgICAgIGF0dHI6XG4gICAgICAgICAgICAgICAgICAgICAgICBuZ1JlcXVpcmVkOiAnIWZvcm1TZXR0aW5ncy5kYXRhLnBob25lJ1xuICAgICAgICAgICAgICAgICAgICAgICAgcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgbXNnczpcbiAgICAgICAgICAgICAgICAgICAgICAgIHJlcXVpcmVkOiAnSW5mb3JtZSBvIENlbHVsYXInXG4gICAgICAgICAgICAgICAgICAgICAgICBtYXNrOiAnTyBjZWx1bGFyIMOpIGludsOhbGlkbydcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ2VtYWlsJ1xuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnZW1haWwnXG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiAnRS1tYWlsJ1xuICAgICAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogJ2V4ZW1wbG9AZXhlbXBsby5jb20nXG4gICAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDEyXG4gICAgICAgICAgICAgICAgICAgIGF0dHI6IHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIG1zZ3M6XG4gICAgICAgICAgICAgICAgICAgICAgICByZXF1aXJlZDogJ0luZm9ybWUgbyBlLW1haWwnXG4gICAgICAgICAgICAgICAgICAgICAgICBlbWFpbDogJ08gZm9ybWF0byBkbyBlLW1haWwgw6kgaW52w6FsaWRvISdcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ2FkZHJlc3MnXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICd0ZXh0J1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0VuZGVyZcOnbydcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczogOFxuICAgICAgICAgICAgICAgICAgICBhdHRyOiByZXF1aXJlZDogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICBtc2dzOiByZXF1aXJlZDogJ1ByZWVuY2hhIG8gZW5kZXJlw6dvJ1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAnbnVtYmVyJ1xuICAgICAgICAgICAgICAgICAgICB0eXBlOiAndGV4dCdcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdOw7ptZXJvJ1xuICAgICAgICAgICAgICAgICAgICBudW1iZXI6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczogNFxuICAgICAgICAgICAgICAgICAgICBhdHRyOiByZXF1aXJlZDogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICBtc2dzOiByZXF1aXJlZDogJ1ByZWVuY2hhIG8gbsO6bWVybydcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ2NvbXBsZW1lbnQnXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICd0ZXh0J1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0NvbXBsZW1lbnRvJ1xuICAgICAgICAgICAgICAgICAgICBjb2x1bW5zOiA2XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdkaXN0cmljdCdcbiAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3RleHQnXG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiAnQmFpcnJvJ1xuICAgICAgICAgICAgICAgICAgICBjb2x1bW5zOiA2XG4gICAgICAgICAgICAgICAgICAgIGF0dHI6IHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIG1zZ3M6IHJlcXVpcmVkOiAnUHJlZW5jaGEgbyBiYWlycm8nXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgcHJvcGVydHk6ICdjaXR5J1xuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnc2VsZWN0J1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0NpZGFkZSdcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczogNlxuICAgICAgICAgICAgICAgICAgICBsaXN0OiAnaXRlbS52YWx1ZSBhcyBpdGVtLmxhYmVsIGZvciBpdGVtIGluIGNpdGllcydcbiAgICAgICAgICAgICAgICAgICAgYXR0cjogcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgbXNnczogcmVxdWlyZWQ6ICdTZWxlY2lvbmUgdW1hIGNpZGFkZSdcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ3N0YXRlJ1xuICAgICAgICAgICAgICAgICAgICB0eXBlOiAnc2VsZWN0J1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0VzdGFkbydcbiAgICAgICAgICAgICAgICAgICAgY29sdW1uczogNlxuICAgICAgICAgICAgICAgICAgICBsaXN0OiAnaXRlbS52YWx1ZSBhcyBpdGVtLmxhYmVsIGZvciBpdGVtIGluIHN0YXRlcydcbiAgICAgICAgICAgICAgICAgICAgYXR0cjogcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgbXNnczogcmVxdWlyZWQ6ICdTZWxlY2lvbmUgdW0gZXN0YWRvJ1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgIHByb3BlcnR5OiAnZmtpZHNob3AnXG4gICAgICAgICAgICAgICAgICAgIHR5cGU6ICdzZWxlY3QnXG4gICAgICAgICAgICAgICAgICAgIGxhYmVsOiAnTG9qYSBwcmVmZXJlbmNpYWwnXG4gICAgICAgICAgICAgICAgICAgIGxpc3Q6ICdpdGVtLnZhbHVlIGFzIGl0ZW0ubGFiZWwgZm9yIGl0ZW0gaW4gc2hvcHMnXG4gICAgICAgICAgICAgICAgICAgIGNvbHVtbnM6IDEyXG4gICAgICAgICAgICAgICAgICAgIGF0dHI6IHJlcXVpcmVkOiB0cnVlLCBuZ1Nob3c6ICdzaG9wcy5sZW5ndGggPiAyJ1xuICAgICAgICAgICAgICAgICAgICBtc2dzOiByZXF1aXJlZDogJ1NlbGVjaW9uZSB1bWEgbG9qYSBwcmVmZXJlbmNpYWwnXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgXVxuICAgICAgICAgICAgc3VibWl0OiAoZGF0YSkgLT5cbiAgICAgICAgICAgICAgICByZXR1cm4gaWYgISRzY29wZS5mb3JtLiR2YWxpZFxuICAgICAgICAgICAgICAgICRnclJlc3RmdWwudXBkYXRlKFxuICAgICAgICAgICAgICAgICAgICBtb2R1bGU6ICdjbGllbnQnXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogJ3VwZGF0ZV9hdHRyaWJ1dGVzJ1xuICAgICAgICAgICAgICAgICAgICBpZDogJHJvb3RTY29wZS5HUklGRk8udXNlci5pZFxuICAgICAgICAgICAgICAgICAgICBwb3N0OiBkYXRhKS50aGVuIChyKSAtPlxuICAgICAgICAgICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuZWRpdGFibGUgZmFsc2VcbiAgICAgICAgICAgICAgICAgICAgICAgICRzY29wZS5mb3JtLnVwZGF0ZURlZmF1bHRzKClcbiAgICAgICAgICAgICAgICAgICAgYWxlcnQuc2hvdyByLnN0YXR1cywgci5tZXNzYWdlXG5cbiAgICAgICAgJHNjb3BlLiR3YXRjaCAnZm9ybVNldHRpbmdzLmRhdGEuc3RhdGUnLCAoZSkgLT5cbiAgICAgICAgICAgIGlmIGVcbiAgICAgICAgICAgICAgICBpZiAkc2NvcGUuZm9ybVNldHRpbmdzLmRhdGEuc3RhdGVcbiAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNpdGllcyA9IFtdXG4gICAgICAgICAgICAgICAgICAgIGFuZ3VsYXIuZm9yRWFjaCAkY2lkYWRlRXN0YWRvLmdldC5jaWRhZGVzKCRzY29wZS5mb3JtU2V0dGluZ3MuZGF0YS5zdGF0ZSksIChjKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNpdGllcy5wdXNoXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFsdWU6IGNcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBsYWJlbDogY1xuICAgICAgICAgICAgICAgIGVsc2VcbiAgICAgICAgICAgICAgICAgICAgJHNjb3BlLmNpdGllcyA9IFtdXG4gICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgJHNjb3BlLmZvcm1TZXR0aW5ncy5kYXRhLmNpdHkgPSB1bmRlZmluZWRcblxuICAgICAgICAkc2NvcGUuY2hhbmdlUGFzc3dvcmQgPSAtPlxuICAgICAgICAgICAgbW9kYWwgPSAkZ3JNb2RhbC5uZXdcbiAgICAgICAgICAgICAgICBuYW1lOiAnY2hhbmdlLXBhc3N3b3JkJ1xuICAgICAgICAgICAgICAgIHRpdGxlOiAnQWx0ZXJhciBzZW5oYSdcbiAgICAgICAgICAgICAgICBzaXplOiAnc20nXG4gICAgICAgICAgICAgICAgbW9kZWw6IEdSSUZGTy50ZW1wbGF0ZVBhdGggKyAndmlldy9tb2RhbC9jaGFuZ2UtcGFzc3dvcmQucGhwJ1xuICAgICAgICAgICAgICAgIGRlZmluZTogaWQ6ICRzY29wZS5mb3JtU2V0dGluZ3MuZGF0YS5pZGNsaWVudFxuICAgICAgICAgICAgICAgIGJ1dHRvbnM6IFtcbiAgICAgICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICAgICAgdHlwZTogJ3ByaW1hcnknXG4gICAgICAgICAgICAgICAgICAgICAgICBsYWJlbDogJ0FsdGVyYXInXG4gICAgICAgICAgICAgICAgICAgICAgICBvbkNsaWNrOiAoJHNjb3BlLCAkZWxlbWVudCwgY29udHJvbGxlcikgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAkc2NvcGUuZm9ybS5zdWJtaXQoKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVyblxuXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgICAgICAgICAgdHlwZTogJ2RhbmdlcidcbiAgICAgICAgICAgICAgICAgICAgICAgIGxhYmVsOiAnQ2FuY2VsYXInXG4gICAgICAgICAgICAgICAgICAgICAgICBvbkNsaWNrOiAoJHNjb3BlLCAkZWxlbWVudCwgY29udHJvbGxlcikgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250cm9sbGVyLmNsb3NlKClcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICByZXR1cm5cblxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXVxuICAgICAgICAgICAgbW9kYWwub3BlbigpXG5cbiAgICBkbyAtPlxuXG4gICAgICAgIGdldE9yZGVycyA9IC0+XG4gICAgICAgICAgICBpZiAhJHNjb3BlLnNob3dDb21wbGV0ZWRcbiAgICAgICAgICAgICAgICAkZ3JSZXN0ZnVsLmZpbmRcbiAgICAgICAgICAgICAgICAgICAgbW9kdWxlOiAnb3JkZXInXG4gICAgICAgICAgICAgICAgICAgIGFjdGlvbjogJ2dldCdcbiAgICAgICAgICAgICAgICAgICAgcGFyYW1zOiAnZmtpZGNsaWVudD0nICsgJHJvb3RTY29wZS5HUklGRk8udXNlci5pZFxuICAgICAgICAgICAgICAgIC50aGVuIChyKSAtPlxuICAgICAgICAgICAgICAgICAgICBpZiByLnJlc3BvbnNlXG4gICAgICAgICAgICAgICAgICAgICAgICBhbmd1bGFyLmZvckVhY2ggci5yZXNwb25zZSwgKG9yZGVyLCBpZCkgLT5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBhbmd1bGFyLmZvckVhY2ggJHNjb3BlLm9yZGVycywgKF9vcmRlciwgX2lkKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiBfb3JkZXIuaWRvcmRlciA9PSBvcmRlci5pZG9yZGVyIGFuZCBfb3JkZXIuc3RhdHVzICE9IG9yZGVyLnN0YXR1c1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm5vdGlmeSBvcmRlclxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm9yZGVycyA9IHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm9yZGVycyA9IFtdXG5cbiAgICAgICAgICAgICAgICBnZXRPcmRlcnNUaW1lb3V0ID0gJHRpbWVvdXQgLT5cbiAgICAgICAgICAgICAgICAgICAgZ2V0T3JkZXJzKClcbiAgICAgICAgICAgICAgICAsIDMwMDAwXG4gICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgJGdyUmVzdGZ1bC5maW5kXG4gICAgICAgICAgICAgICAgICAgIG1vZHVsZTogJ29yZGVyJ1xuICAgICAgICAgICAgICAgICAgICBhY3Rpb246ICdjb21wbGV0ZWQnXG4gICAgICAgICAgICAgICAgICAgIHBhcmFtczogJ2ZraWRjbGllbnQ9JyArICRyb290U2NvcGUuR1JJRkZPLnVzZXIuaWRcbiAgICAgICAgICAgICAgICAudGhlbiAocikgLT5cbiAgICAgICAgICAgICAgICAgICAgaWYgci5yZXNwb25zZVxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm9yZGVycyA9IHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAgICAgZWxzZVxuICAgICAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm9yZGVycyA9IFtdXG5cbiAgICAgICAgJHNjb3BlLm9yZGVycyA9IFtdXG4gICAgICAgIGdldE9yZGVyc1RpbWVvdXQgPSAkdGltZW91dFxuICAgICAgICAkc2NvcGUuJHdhdGNoICdzaG93Q29tcGxldGVkJywgLT5cbiAgICAgICAgICAgICR0aW1lb3V0LmNhbmNlbCBnZXRPcmRlcnNUaW1lb3V0XG4gICAgICAgICAgICBnZXRPcmRlcnMoKVxuICAgICAgICBnZXRPcmRlcnMoKVxuICAgICAgICAkc2NvcGUub3JkZXJJbmZvID0gKG9yZGVyKSAtPlxuICAgICAgICAgICAgbW9kYWwgPSAkZ3JNb2RhbC5uZXdcbiAgICAgICAgICAgICAgICBuYW1lOiAnb3JkZXItaW5mbydcbiAgICAgICAgICAgICAgICB0aXRsZTogJ0luZm9ybWHDp8O1ZXMgZG8gcGVkaWRvICMnICsgb3JkZXIuaWRvcmRlclxuICAgICAgICAgICAgICAgIHNpemU6ICdtZCdcbiAgICAgICAgICAgICAgICBtb2RlbDogR1JJRkZPLnRlbXBsYXRlUGF0aCArICd2aWV3L21vZGFsL29yZGVyLWluZm8ucGhwJ1xuICAgICAgICAgICAgICAgIGRlZmluZTpcbiAgICAgICAgICAgICAgICAgICAgb3JkZXI6IG9yZGVyXG4gICAgICAgICAgICAgICAgICAgIGZpbmRTaG9wOiAkc2NvcGUuZmluZFNob3BcbiAgICAgICAgICAgICAgICBidXR0b25zOiBbXG4gICAgICAgICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHR5cGU6ICdwcmltYXJ5J1xuICAgICAgICAgICAgICAgICAgICAgICAgbGFiZWw6ICdPaydcbiAgICAgICAgICAgICAgICAgICAgICAgIG9uQ2xpY2s6ICgkc2NvcGUsICRlbGVtZW50LCBjb250cm9sbGVyKSAtPlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnRyb2xsZXIuY2xvc2UoKVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXVxuICAgICAgICAgICAgbW9kYWwub3BlbigpXG5cbiAgICBkZWxldGUgJGxvY2FsU3RvcmFnZS5ncmlmZm9fY2FydF9yZWFkeVxuICAgICMgYW5ndWxhckxvYWQubG9hZFNjcmlwdCgkcm9vdFNjb3BlLkdSSUZGTy5saWJyYXJpZXNQYXRoICsgJ2NsaWVudC9kZXNrdG9wLW5vdGlmeS9kZXNrdG9wLW5vdGlmeS5taW4uanMnKS50aGVuIGluaXROb3RpZmljYXRpb25cbiAgICBkbyBpbml0Tm90aWZpY2F0aW9uXG5cbmFuZ3VsYXIubW9kdWxlKCdtYWluQXBwJykuY29udHJvbGxlciAnY2hhbmdlUGFzc3dvcmRDdHJsJywgKCRzY29wZSwgJHRpbWVvdXQsICRnclJlc3RmdWwsICRnckFsZXJ0KSAtPlxuICAgIGFsZXJ0ID0gJGdyQWxlcnQubmV3KClcbiAgICAkc2NvcGUuZm9ybVNldHRpbmdzID1cbiAgICAgICAgZGF0YToge31cbiAgICAgICAgc2NoZW1hOiBbXG4gICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgcHJvcGVydHk6ICdvbGRwYXNzd29yZCdcbiAgICAgICAgICAgICAgICB0eXBlOiAncGFzc3dvcmQnXG4gICAgICAgICAgICAgICAgbGFiZWw6ICdTZW5oYSBhdHVhbCdcbiAgICAgICAgICAgICAgICBhdHRyOlxuICAgICAgICAgICAgICAgICAgICByZXF1aXJlZDogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICBhdXRvZm9jdXM6IHRydWVcbiAgICAgICAgICAgICAgICBtc2dzOiByZXF1aXJlZDogJ0Egc2VuaGEgYXR1YWwgw6kgb2JyaWdhdMOzcmlhJ1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgeyB0eXBlOiAnaHInIH1cbiAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ3Bhc3N3b3JkJ1xuICAgICAgICAgICAgICAgIHR5cGU6ICdwYXNzd29yZCdcbiAgICAgICAgICAgICAgICBsYWJlbDogJ05vdmEgc2VuaGEnXG4gICAgICAgICAgICAgICAgYXR0cjpcbiAgICAgICAgICAgICAgICAgICAgcmVxdWlyZWQ6IHRydWVcbiAgICAgICAgICAgICAgICAgICAgbmdNaW5sZW5ndGg6IDRcbiAgICAgICAgICAgICAgICAgICAgbmdNYXhsZW5ndGg6IDE2XG4gICAgICAgICAgICAgICAgbXNnczpcbiAgICAgICAgICAgICAgICAgICAgcmVxdWlyZWQ6ICdBIG5vdmEgc2VuaGEgw6kgb2JyaWdhdMOzcmlhJ1xuICAgICAgICAgICAgICAgICAgICBtaW5sZW5ndGg6ICdBIHNlbmhhIGRldmUgcG9zc3VpciBubyBtw61uaW1vIDQgY2FyYWN0w6lyZXMnXG4gICAgICAgICAgICAgICAgICAgIG1heGxlbmd0aDogJ0Egc2VuaGEgbsOjbyBwb2RlIHVsdHJhcGFzc2FyIDE2IGNhcmFjdMOpcmVzJ1xuICAgICAgICAgICAgICAgICAgICBwYXR0ZXJuOiAnQSBub3ZhIHNlbmhhIMOpIGludsOhbGlkYSdcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICBwcm9wZXJ0eTogJ3JlcGFzc3dvcmQnXG4gICAgICAgICAgICAgICAgdHlwZTogJ3Bhc3N3b3JkJ1xuICAgICAgICAgICAgICAgIGxhYmVsOiAnQ29uZmlybWHDp8OjbyBkYSBub3ZhIHNlbmhhJ1xuICAgICAgICAgICAgICAgIGF0dHI6XG4gICAgICAgICAgICAgICAgICAgIHJlcXVpcmVkOiB0cnVlXG4gICAgICAgICAgICAgICAgICAgIGNvbmZpcm1QYXNzd29yZDogJ2Zvcm1TZXR0aW5ncy5kYXRhLnBhc3N3b3JkJ1xuICAgICAgICAgICAgICAgIG1zZ3M6XG4gICAgICAgICAgICAgICAgICAgIHJlcXVpcmVkOiAnQ29uZmlybWFyIGEgbm92YSBzZW5oYSDDqSBvYnJpZ2F0w7NyaWEnXG4gICAgICAgICAgICAgICAgICAgIG1hdGNoOiAnQXMgc2VuaGFzIHByZWNpc2FtIHNlciBpZ3VhaXMnXG4gICAgICAgICAgICB9XG4gICAgICAgIF1cbiAgICAgICAgc3VibWl0OiAoZGF0YSkgLT5cbiAgICAgICAgICAgIG5ld0RhdGEgPVxuICAgICAgICAgICAgICAgICdvbGQtcGFzc3dvcmQnOiBkYXRhLm9sZHBhc3N3b3JkXG4gICAgICAgICAgICAgICAgJ3Bhc3N3b3JkJzogZGF0YS5wYXNzd29yZFxuICAgICAgICAgICAgICAgICdyZS1wYXNzd29yZCc6IGRhdGEucmVwYXNzd29yZFxuICAgICAgICAgICAgJGdyUmVzdGZ1bC51cGRhdGVcbiAgICAgICAgICAgICAgICBtb2R1bGU6ICdjbGllbnQnXG4gICAgICAgICAgICAgICAgYWN0aW9uOiAnY2hhbmdlX3Bhc3N3b3JkJ1xuICAgICAgICAgICAgICAgIGlkOiAkc2NvcGUuJHBhcmVudC5pZFxuICAgICAgICAgICAgICAgIHBvc3Q6IG5ld0RhdGFcbiAgICAgICAgICAgIC50aGVuIChyKSAtPlxuICAgICAgICAgICAgICAgIGlmIHIucmVzcG9uc2VcbiAgICAgICAgICAgICAgICAgICAgJHNjb3BlLm1vZGFsLmNsb3NlKClcbiAgICAgICAgICAgICAgICAgICAgYWxlcnQuc2hvdyAnc3VjY2VzcycsICdBTEVSVC5TVUNDRVNTLkNIQU5HRS5QQVNTV09SRCdcbiAgICAgICAgICAgICAgICBlbHNlXG4gICAgICAgICAgICAgICAgICAgIGFsZXJ0LnNob3cgci5zdGF0dXMsIHIubWVzc2FnZVxuICAgICAgICAgICAgLCAtPlxuICAgICAgICAgICAgICAgIGFsZXJ0LnNob3cgJ2RhbmdlcicsICdFUlJPUi5GQVRBTCdcblxuICAgICRzY29wZS4kd2F0Y2ggJ2Zvcm0nLCAoZm9ybSkgLT5cbiAgICAgICAgJHNjb3BlLiRwYXJlbnQuZm9ybSA9IGZvcm1cblxuYW5ndWxhci5tb2R1bGUoJ21haW5BcHAnKS5kaXJlY3RpdmUgJ2NvbmZpcm1QYXNzd29yZCcsIC0+XG4gICAgcmVzdHJpY3Q6ICdBJ1xuICAgIHJlcXVpcmU6ICduZ01vZGVsJ1xuICAgIGxpbms6IChzY29wZSwgZWxlbWVudCwgYXR0cnMsIG5nTW9kZWwpIC0+XG4gICAgICAgIHZhbGlkYXRlID0gKHZpZXdWYWx1ZSkgLT5cbiAgICAgICAgICAgIHBhc3N3b3JkID0gc2NvcGUuJGV2YWwoYXR0cnMuY29uZmlybVBhc3N3b3JkKVxuICAgICAgICAgICAgbmdNb2RlbC4kc2V0VmFsaWRpdHkgJ21hdGNoJywgbmdNb2RlbC4kaXNFbXB0eSh2aWV3VmFsdWUpIG9yIHZpZXdWYWx1ZSA9PSBwYXNzd29yZFxuICAgICAgICAgICAgdmlld1ZhbHVlXG4gICAgICAgIG5nTW9kZWwuJHBhcnNlcnMucHVzaCB2YWxpZGF0ZVxuICAgICAgICBzY29wZS4kd2F0Y2ggYXR0cnMuY29uZmlybVBhc3N3b3JkLCAodmFsdWUpIC0+XG4gICAgICAgICAgICB2YWxpZGF0ZSBuZ01vZGVsLiR2aWV3VmFsdWVcbiJdfQ==