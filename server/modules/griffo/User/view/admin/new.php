<div class="view-header">{{'MODULE.USER.TITLE.NEW' | grTranslate}}</div>
<div class="view-content" ng-controller="formCtrl">
    <div class="view-content-inner">
        <div class="container-fluid">
            <form name="form" gr-autofields="formSettings"></form>
            <div class="alert alert-warning">
                <strong gr-translate>LABEL.WARNING</strong><br/>
                <small gr-translate>WARNING.PASSWORD.RULES.DESCRIPTION[[5,16]]</small>
            </div>
        </div>
    </div>
</div>
<div class="view-footer">
    <button ng-click="form.submit()" type="submit" class="btn btn-success">{{'BUTTON.SAVE' | grTranslate}}</button>
    <button ng-click="form.reset()" type="reset" class="btn btn-danger">{{'BUTTON.RESET' | grTranslate}}</button>
    <span class="pull-right gr-form-required-label">{{'LABEL.REQUIRED.FIELDS' | grTranslate}}</span>
</div>
