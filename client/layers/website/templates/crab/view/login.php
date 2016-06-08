<div class="page">
    <div class="container">
        <div class="col-xs-12 col-md-6 form-box" ng-controller="loginCtrl">
            <div class="form-box-inner">
                <h2 class="page-header"><i class="fa fa-fw fa-sign-in fa-lg"></i>Acessar conta</h2>
                <div class="row">
                    <form name="form" gr-autofields="formSettings" autocomplete="off"></form>
                </div>
                <div class="toolbar">
                    <button class="btn btn-success" ng-click="form.submit()">Entrar</button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6 form-box" ng-controller="signupCtrl">
            <div class="form-box-inner">
                <h2 class="page-header"><i class="fa fa-fw fa-pencil-square-o fa-lg"></i>Novo cadastro</h2>
                <div class="row">
                    <form name="form" gr-autofields="formSettings" autocomplete="off"></form>
                </div>
                <div class="toolbar">
                    <button class="btn btn-success" ng-click="form.submit()">Cadastrar</button>
                    <button class="btn btn-default" ng-click="form.reset()">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>
