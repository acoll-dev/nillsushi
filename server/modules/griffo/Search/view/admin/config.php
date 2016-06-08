<div class="view-header">{{'MODULE.SEARCH.TITLE.CONFIG'| grTranslate}}</div>
<div class="view-content" ng-controller="formCtrl">
    <div class="view-content-inner">
        <div class="container-fluid">
            <form name="form" gr-autofields="formSettings"></form>
        </div>
    </div>
</div>
<div class="view-footer">
    <button ng-click="form.submit()" type="submit" class="btn btn-success">{{'BUTTON.SAVE' | grTranslate}}</button>
    <button ng-click="form.reset()" type="reset" class="btn btn-danger">{{'BUTTON.RESET' | grTranslate}}</button>
    <span class="pull-right gr-form-required-label">{{'LABEL.REQUIRED.FIELDS' | grTranslate}}</span>
</div>
