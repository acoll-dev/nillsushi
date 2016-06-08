<label>{{'MODULE.FILEMANAGER.CONFIRM.MOVE' | grTranslate}}</label>
<select name="target" ng-model="target" class="form-control">
    <option value="" disabled selected>{{'MODULE.FILEMANAGER.LABEL.FIELD.SELECT.TARGET' | grTranslate}}</option>
    <option value="{{item.path}}" ng-repeat="item in items">{{item.label}}</option>
</select>
