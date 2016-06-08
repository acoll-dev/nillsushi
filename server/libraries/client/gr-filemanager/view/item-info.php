<div class="ger-info" ng-if="item.type === 'file'">
    <div class="row">
        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><strong>{{'MODULE.FILEMANAGER.LABEL.FILE.NAME' | grTranslate}}</strong></label>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">{{item.basename}}</div>
    </div>
    <div class="row">
        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><strong>{{'MODULE.FILEMANAGER.LABEL.FILE.SIZE' | grTranslate}}</strong></label>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">{{item.size/1024/1024 | number:2}} MB</div>
    </div>
    <div class="row">
        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><strong>{{'MODULE.FILEMANAGER.LABEL.FILE.LOCATION' | grTranslate}}</strong></label>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">{{item.dirname !== '' ? '/' + item.dirname : '/'}}</div>
    </div>
    <div class="row">
        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><strong>{{'MODULE.FILEMANAGER.LABEL.FILE.URL' | grTranslate}}</strong></label>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <a ng-href="{{GRIFFO.baseUrl + GRIFFO.uploadPath + item.path}}" target="_blank">{{GRIFFO.baseUrl + GRIFFO.uploadPath + item.path}}</a>
        </div>
    </div>
    <div class="row">
        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><strong>{{'MODULE.FILEMANAGER.LABEL.FILE.LAST.CHANGE' | grTranslate}}</strong></label>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">{{item.timestamp | timestamp | date:'longDate'}} - {{item.timestamp | timestamp | date:'mediumTime'}}</div>
    </div>
</div>
<div class="ger-info" ng-if="item.type === 'dir'">
    <div class="row">
        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><strong>{{'MODULE.FILEMANAGER.LABEL.FOLDER.NAME' | grTranslate}}</strong></label>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">{{item.basename}}</div>
    </div>
    <div class="row">
        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><strong>{{'MODULE.FILEMANAGER.LABEL.FOLDER.LOCATION' | grTranslate}}</strong></label>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">{{item.dirname !== '' ? '/' + item.dirname : '/'}}</div>
    </div>
    <div class="row">
        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4"><strong>{{'MODULE.FILEMANAGER.LABEL.FOLDER.LAST.CHANGE' | grTranslate}}</strong></label>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">{{item.timestamp | timestamp | date:'longDate'}} - {{item.timestamp | timestamp | date:'mediumTime'}}</div>
    </div>
</div>
