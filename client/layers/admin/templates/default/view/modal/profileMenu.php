<div class="popover-profile">
    <div class="popover-profile-image img-thumbnail" ng-if="!user.picture">
        <i class="fa fa-fw fa-user fa-4x"></i>
    </div>
    <div class="popover-profile-image img-thumbnail" ng-if="user.picture">
        <img ng-src="{{GRIFFO.uploadPath + user.picture}}" class="img-responsive" ng-if="user.picture"/>
    </div>
    <div class="popover-profile-info">
        <p><strong>{{user.nickname}}</strong></p>
        <hr/>
        <p>{{user.profile.label | grTranslate}}</p>
    </div>
</div>
<div class="popover-list list-group">
    <!-- <div class="list-group-item" ng-click="editProfile()">
        <i class="fa fa-fw fa-edit"></i> <gr-translate>{{'BUTTON.EDIT.PROFILE'}}</gr-translate>
    </div> -->
    <div class="list-group-item" ng-click="editInfo()">
        <i class="fa fa-fw fa-edit"></i> <gr-translate>{{'BUTTON.EDIT.INFORMATIONS'}}</gr-translate>
    </div>
    <div class="list-group-item" ng-click="changePassword()">
        <i class="fa fa-fw fa-lock"></i> <gr-translate>{{'BUTTON.CHANGE.PASSWORD'}}</gr-translate>
    </div>
    <div class="list-group-item list-group-item-drag" ng-click="GRIFFO.mainMenuDraggable = !GRIFFO.mainMenuDraggable" ng-class="{'active': GRIFFO.mainMenuDraggable}">
        <i class="fa fa-fw fa-bars"></i> <gr-translate>{{'BUTTON.REORDER.MENU'}}</gr-translate>
    </div>
    <div class="list-group-item list-group-item-theme">
        <label gr-translate>{{'LABEL.THEME' | grTranslate}}:</label>
<!--
        <select ng-model="GRIFFO.user.theme" class="form-control theme-select" ng-change="changeAppearance()" ng-options="theme.name as (theme.name | capitalizeFirst) for theme in themes"></select>
-->
        <div class="clearfix"></div>
        <ul class="color-list">
            <li class="color-list-item" ng-repeat="theme in themes" ng-class="{'active': theme.name === GRIFFO.user.theme}" ng-attr-title="{{theme.label}}" style="background:{{theme.color}}" ng-click="GRIFFO.user.theme = theme.name">
                <i class="fa fa-fw fa-check"></i>
            </li>
        </ul>
<!--
        <button class="btn btn-default btn-theme" ng-class="{'disabled': theme.name === user.theme}" ng-repeat="theme in themes" title="{{theme.label}}" ng-click="changeAppearance(theme.name, $event)">
            <span class="sr-only">{{theme.label}}</span>
            <i class="btn-theme-color" style="background: {{theme.color}}"></i>
        </button>
-->
    </div>
</div>
<div class="popover-footer">
    <button type="button" class="btn btn-default btn-help" ng-click="info()">
        <i class="fa fa-fw fa-question-circle"></i>
    </button>
    <button type="button" class="btn btn-default btn-logout" ng-click="logout()" ng-disabled="logouting">
        <i class="fa fa-fw" ng-class="{'fa-refresh fa-spin': logouting, 'fa-power-off': !logouting}"></i>
    </button>
</div>
