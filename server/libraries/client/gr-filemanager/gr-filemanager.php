<div class="ger-wrapper" file-manager>
    <div class="ger-toolbar navbar navbar-default">
        <div class="btn-group ger-toolbar-item pull-left" ng-if="grManager.settings.filter === 'all'">
            <button type="button" class="btn btn-primary" data-toggle="dropdown">
                 <span class="visible-md visible-lg">
                     <i class="fa fa-fw fa-filter"></i>&nbsp;
                     <gr-translate>{{grManager.filter.byType[grManager.filterType].label}}</gr-translate>
                 </span>
                 <span class="visible-xs visible-sm">
                     <i class="fa fa-fw fa-filter"></i>
                 </span>
            </button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li ng-repeat="filter in grManager.filter.byType" ng-if="filter.enable" ng-class="{'active': grManager.filterType === filter.extType}">
                    <a ng-click="grManager.filterType = filter.extType" ng-attr-title="{{filter.label | grTranslate}}">
                        <i ng-class="filter.iconClass"></i>&nbsp; <gr-translate>{{filter.label}}</gr-translate>
                    </a>
                </li>
            </ul>
        </div>
        <div class="btn-group ger-toolbar-item pull-left">
            <button type="button" class="btn btn-primary" data-toggle="dropdown">
                 <span class="visible-md visible-lg">
                     <i class="fa fa-fw fa-reorder"></i>&nbsp;
                     <gr-translate>{{grManager.order[grManager.orderBy].label}}</gr-translate>
                 </span>
                 <span class="visible-xs visible-sm">
                     <i class="fa fa-fw fa-reorder"></i>
                 </span>
            </button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li ng-repeat="order in grManager.order" ng-class="{'active': grManager.orderBy === order.value}">
                    <a ng-click="grManager.orderBy = order.value" ng-attr-title="{{order.label | grTranslate}}" gr-translate>
                        {{order.label}}
                    </a>
                </li>
            </ul>
        </div>
        <div class="btn-group ger-toolbar-item pull-left">
            <button type="button" class="btn btn-primary" data-toggle="dropdown">
                 <span class="visible-md visible-lg">
                     <i class="fa fa-fw fa-folder"></i>&nbsp;
                     <gr-translate>{{'MODULE.FILEMANAGER.BUTTON.ACTIONS.OF.FOLDER'}}</gr-translate>
                 </span>
                 <span class="visible-xs visible-sm">
                     <i class="fa fa-fw fa-folder"></i>
                 </span>
            </button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a ng-attr-title="{{'MODULE.FILEMANAGER.ACTION.FOLDER.NEW' | grTranslate}}" ng-click="grManager.addFolder()">
                        <i class="fa fa-fw fa-plus"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FOLDER.NEW'}}</gr-translate>
                    </a>
                </li>
                <li>
                    <a ng-attr-title="{{'MODULE.FILEMANAGER.ACTION.FOLDER.RELOAD' | grTranslate}}" ng-click="grManager.reload(grManager.dir.current.path)">
                        <i class="fa fa-fw fa-refresh"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FOLDER.RELOAD'}}</gr-translate>
                    </a>
                </li>
                <li>
                    <a ng-attr-title="{{'MODULE.FILEMANAGER.ACTION.FOLDER.RENAME' | grTranslate}}" ng-click="grManager.renameFolder()" ng-if="grManager.dir.current.path !== '/'">
                        <i class="fa fa-fw fa-pencil"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FOLDER.RENAME'}}</gr-translate>
                    </a>
                </li>
                <li>
                    <a ng-attr-title="{{'MODULE.FILEMANAGER.ACTION.FOLDER.DELETE' | grTranslate}}" ng-click="grManager.deleteFolder()" ng-if="grManager.dir.current.path !== '/'">
                        <i class="fa fa-fw fa-trash"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FOLDER.DELETE'}}</gr-translate>
                    </a>
                </li>
            </ul>
        </div>
        <div class="btn-group ger-toolbar-item pull-left">
            <button type="button" class="btn btn-primary" data-toggle="dropdown">
                 <span class="visible-md visible-lg">
                     <i class="fa fa-fw fa-file"></i>&nbsp;
                     <gr-translate>{{'MODULE.FILEMANAGER.BUTTON.ACTIONS.OF.FILE'}}</gr-translate>
                 </span>
                 <span class="visible-xs visible-sm">
                     <i class="fa fa-fw fa-file"></i>
                 </span>
            </button>
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li ng-if="grManager.filesLength > 1 && grManager.settings.multiple">
                    <a ng-attr-title="{{('MODULE.FILEMANAGER.ACTION.FILE.' + (grManager.allSelected ? 'UNSELECT' : 'SELECT') + '.ALL') | grTranslate}}" ng-click="grManager.toggleSelection('all')">
                        <i class="fa fa-fw" ng-class="{'fa-check-square-o': !grManager.allSelected, 'fa-square-o': grManager.allSelected}"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FILE.' + (grManager.allSelected ? 'UNSELECT' : 'SELECT') + '.ALL'}}</gr-translate>
                    </a>
                </li>
                <li>
                    <a ng-attr-title="{{'MODULE.FILEMANAGER.ACTION.FILE.UPLOAD' | grTranslate}}" ng-click="grManager.uploadFile()">
                        <i class="fa fa-fw fa-arrow-circle-o-up"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FILE.UPLOAD'}}</gr-translate>
                    </a>
                </li>
                <li ng-if="grManager.selection.length > 0">
                    <a ng-attr-title="{{'MODULE.FILEMANAGER.ACTION.FILE.MOVE' | grTranslate}}" ng-click="grManager.moveFiles()">
                        <i class="fa fa-fw fa-share-square-o"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FILE.MOVE'}}</gr-translate>
                    </a>
                </li>
                <li ng-if="grManager.selection.length > 1">
                    <a ng-attr-title="{{'MODULE.FILEMANAGER.ACTION.FILE.DOWNLOAD' | grTranslate}}" ng-click="grManager.download()">
                        <i class="fa fa-fw fa-download"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FILE.DOWNLOAD'}}</gr-translate>
                    </a>
                </li>
                <li ng-if="grManager.selection.length > 1">
                    <a ng-attr-title="{{'MODULE.FILEMANAGER.ACTION.FILE.DELETE' | grTranslate}}" ng-click="grManager.deleteFiles()">
                        <i class="fa fa-fw fa-trash"></i> <gr-translate>{{'MODULE.FILEMANAGER.ACTION.FILE.DELETE'}}</gr-translate>
                    </a>
                </li>
            </ul>
        </div>
        <div class="input-group col-xs-12 col-sm-6 col-md-4 col-lg-4 ger-toolbar-item pull-left">
            <span class="input-group-addon">
                <i class="fa fa-fw fa-search"></i>
            </span>
            <input type="search" class="form-control" name="ger-type-text" placeholder="{{'MODULE.FILEMANAGER.SEARCH.FILE' | grTranslate}}" ng-model="grManager.filterName" />
        </div>
    </div>
    <div class="ger-breadcrumb">
        <ol class="breadcrumb" ng-attr-title="Você está em: {{grManager.dir.current.path !== '/' ? '/' + grManager.dir.current.path : grManager.dir.current.path}}">
            <li ng-repeat="bread in grManager.breadcrumb.breads" ng-attr-title="{{bread.path}}" ng-class="{'active': bread.current}">
                <a ng-if="!bread.current" ng-click="grManager.reload(bread.path)">
                    <i class="fa fa-fw fa-home" ng-if="bread.label === '/'"></i>
                    <span ng-if="bread.label !== '/'">{{bread.label}}</span>
                </a>
                <span ng-if="bread.current && bread.label !== '/'">{{bread.label}}</span>
                <span ng-if="bread.current && bread.label === '/'">
                    <i class="fa fa-fw fa-home"></i>
                </span>
            </li>
        </ol>
    </div>
    <div class="ger-content">
        <div class="ger-content-list">
            <div class="ger-content-list-inner" ng-if="!grManager.isLoading">
                <div class="ger-list-item ger-list-item-back" ng-attr-title="Voltar" ng-if="grManager.dir.current.path !== '/'">
                    <div class="ger-list-item-inner" ng-click="grManager.reload(grManager.dir.prev)">
                        <div class="ger-list-item-content">
                            <div class="ger-list-item-content-inner">
                                <i class="fa fa-fw fa-arrow-left fa-5x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ger-list-item" ng-repeat="item in grManager.items" ng-class="{'ger-type-folder': item.type === 'dir', 'ger-type-{{item.fileType}}': (item.type !== 'dir' && item.fileType), 'selected': item.selected}" ng-attr-title="{{item.basename}}">
                    <div class="ger-list-item-inner">
                        <div class="ger-list-item-content" ng-click="(item.type === 'dir') ? grManager.reload(item.path) : grManager.toggleSelection(item.path)">
                            <div class="ger-list-item-content-inner">
                                <i class="fa fa-fw fa-folder fa-4x" ng-if="item.type === 'dir'"></i>
                                <i class="fa fa-fw fa-custom fa-3x" ng-if="item.type === 'file' && item.fileType !== 'image'"></i>
                                <div class="ger-list-item-img-wrapper imgLiquidFill imgLiquid" ng-if="item.fileType === 'image'">
                                    <img ng-src="{{GRIFFO.baseUrl + GRIFFO.uploadPath + (item.dirname ? item.dirname + '/' : '')}}{{item.basename}}" class="img-responsive" />
                                </div>
                            </div>
                        </div>
                        <div class="ger-list-item-toolbar text-center">
                            <button class="ger-list-item-toolbar-button btn btn-link" ng-attr-title="{{'MODULE.FILEMANAGER.ITEM.TOOLBAR.RENAME.' + (item.type !== 'dir' ? 'FILE' : 'FOLDER') | grTranslate}}" ng-click="item.type === 'dir' ? grManager.renameFolder(item.path) : grManager.renameFile(item.path)">
                                <i class="fa fa-fw fa-pencil"></i>
                            </button>
                            <button class="ger-list-item-toolbar-button btn btn-link" ng-attr-title="{{'MODULE.FILEMANAGER.ITEM.TOOLBAR.MOVE.FOLDER' | grTranslate}}" ng-click="grManager.moveFolder(item)" ng-if="item.type === 'dir'">
                                <i class="fa fa-fw fa-share-square-o"></i>
                            </button>
                            <button class="ger-list-item-toolbar-button btn btn-link" ng-attr-title="{{'MODULE.FILEMANAGER.ITEM.TOOLBAR.DOWNLOAD.FILE' | grTranslate}}" ng-click="grManager.download(item)" ng-if="item.type !== 'dir'">
                                <i class="fa fa-fw fa-download"></i>
                            </button>
                            <button class="ger-list-item-toolbar-button btn btn-link" ng-attr-title="{{'MODULE.FILEMANAGER.ITEM.TOOLBAR.DELETE.' + (item.type !== 'dir' ? 'FILE' : 'FOLDER') | grTranslate}}" ng-click="item.type === 'dir' ? grManager.deleteFolder(item.path) : grManager.deleteFile(item.path)">
                                <i class="fa fa-fw fa-trash"></i>
                            </button>
                            <button class="ger-list-item-toolbar-button btn btn-link" ng-attr-title="{{'MODULE.FILEMANAGER.ITEM.TOOLBAR.INFO.' + (item.type !== 'dir' ? 'FILE' : 'FOLDER') | grTranslate}}" ng-click="grManager.itemInfo(item)">
                                <i class="fa fa-fw fa-info-circle"></i>
                            </button>
                        </div>
                        <div class="ger-list-item-extension text-center" ng-if="item.extension">
                            <span>{{item.extension}}</span>
                        </div>
                        <div class="ger-list-item-name text-center">
                            <span>{{item.filename | cutFilename: true: 15}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ger-content-list-inner ger-content-list-inner-loading text-center text-muted" ng-if="grManager.isLoading">
                <i class="fa fa-fw fa-refresh fa-3x fa-spin"></i>
            </div>
        </div>
        <div class="ger-content-toolbar navbar navbar-default">
            <div class="ger-content-toolbar-item pull-left text-muted" ng-if="grManager.items.length > 0">
                <small>{{grManager.items.length}} <gr-translate>{{grManager.items.length > 1 ? 'MODULE.FILEMANAGER.LABEL.ITEMS' : 'MODULE.FILEMANAGER.LABEL.ITEM'}}</gr-translate></small>
            </div>
            <div class="ger-content-toolbar-item pull-right text-muted" ng-if="grManager.selection.length > 0 && grManager.settings.multiple">
                <small>{{grManager.selection.length}} <gr-translate>{{grManager.selection.length > 1 ? 'MODULE.FILEMANAGER.LABEL.SELECTED.FILES' : 'MODULE.FILEMANAGER.LABEL.SELECTED.FILE'}}</gr-translate></small>
            </div>
        </div>
    </div>
</div>
