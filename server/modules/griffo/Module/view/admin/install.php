<div class="view-header">{{'MODULE.MODULE.TITLE.INSTALL' | grTranslate}}</div>
<div class="view-content" ng-controller="formCtrl">
    <div class="view-content-inner">
        <div class="container-fluid">
            <form name="form" gr-autofields="formInstall"></form>
        </div>
    </div>
</div>
<div class="view-footer">
    <button ng-click="form.submit()" type="submit" class="btn btn-success" ng-disabled="installing">{{'BUTTON.INSTALL' | grTranslate}}</button>
    <span class="pull-right gr-form-required-label">{{'LABEL.REQUIRED.FIELDS' | grTranslate}}</span>
</div>
