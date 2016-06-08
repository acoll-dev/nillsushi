<div class="view-header">{{'MODULE.PROFILE.TITLE.ACCESSCONTROL' | grTranslate}}</div>
<div class="view-content" ng-controller="accessCtrl">
    <div class="view-content-inner">
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
            <label>{{'LABEL.SELECT.PROFILE' | grTranslate}}</label>
            <select class="form-control" ng-model="curProfile" ng-options="profile.value as (profile.label | grTranslate) for profile in profiles"></select>
        </div>
        <div class="col-xs-12">
            <hr/>
            <ul class="access-control-list list-group" ng-if="control">
                <li class="list-group-item" ng-repeat="item in control">
                    <h4>
                        <a ng-click="item.collapse = !item.collapse">{{'MODULE.' + item.name.toUpperCase() + '.NAME' | grTranslate}} <i class="fa fa-fw" ng-class="{'fa-angle-down': item.collapse, 'fa-angle-up': !item.collapse}"></i></a>
                    </h4>
                    <input toggle-switch type="checkbox"
                           ng-model="item.status"
                           on-label="ON"
                           off-label="OFF"
                           class="pull-right switch-mini switch-on-success switch-off-danger"
                           ng-disabled="saving"

                       ></input>
                    <div class="visibility pull-right" ng-class="{'active': item.visible === 1}" ng-click="item.visible = (item.visible === 1 ? 0 : 1)" ng-attr-title="{{'LABEL.VISIBILITY' | grTranslate}}">
                        <i class="fa fa-fw fa-lg" ng-class="{'fa-eye': item.visible, 'fa-eye-slash': !item.visible}"></i>
                    </div>
                    <div class="clearfix"></div>
                    <ul class="list-group" ng-if="length(item.resources) > 0" ng-show="!item.collapse">
                        <li class="list-group-item list-group-item-header"><strong>{{'LABEL.RESOURCES' | grTranslate}}</strong></li>
                        <li class="list-group-item" ng-repeat="citem in item.resources">
                            <h5>
                                {{'MODULE.' + item.name.toUpperCase() + '.RESOURCE.' + citem.name.toUpperCase() | grTranslate}}
                            </h4>
                            <input toggle-switch type="checkbox"
                                   ng-model="citem.status"
                                   on-label="ON"
                                   off-label="OFF"
                                   class="pull-right switch-mini switch-on-success switch-off-danger"
                                   ng-disabled="!item.status || saving"
                               ></input>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="text-muted well" ng-if="!control || control.length === 0">
                {{'MODULE.PROFILE.NOTFOUND.SELECTED.PROFILE' | grTranslate}}
            </div>
        </div>
    </div>
</div>
<div class="view-footer">
    <button ng-click="save()" ng-disabled="saving()" type="submit" class="btn btn-success">{{'BUTTON.SAVE' | grTranslate}}</button>
</div>
