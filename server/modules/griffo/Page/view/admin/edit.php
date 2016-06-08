<div class="view-content" ng-controller="formCtrl">
    <div class="view-content-inner">
        <div class="container-fluid">
            <form name="form" gr-autofields="formSettings"></form>
            <hr/>
            <label style="padding-top: 9px;">{{'LABEL.CONTENTBLOCKS' | grTranslate}}</label>
            <button type="button" class="btn btn-success btn-xs pull-right" ng-click="blocks.new()" style="margin: 1px 0 8px;"><i class="fa fa-fw fa-plus"></i>&nbsp; {{'BUTTON.ADD.BLOCK' | grTranslate}}</button>
            <div class="table-responsive" style="overflow: auto;" ng-show="blocks.items.length > 0">
                <table gr-table name="tableBlocks" list="blocks.items" style="min-width: 0;">
                    <tr gr-repeat="(key, data) in $data">
                        <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-8" class="col-8 text-left">
                            {{data.name}}
                        </td>
                        <td data-title="'BUTTON.EDIT' | grTranslate" header-class="col-2" class="col-2 text-center">
                            <button type="button" class="btn btn-primary btn-xs btn-table" ng-click="blocks.edit(data.id)"><i class="fa fa-fw fa-edit"></i></button>
                        </td>
                        <td data-title="'BUTTON.DELETE' | grTranslate" header-class="col-2" class="col-2 text-center">
                            <button type="button" class="btn btn-danger btn-xs btn-table" ng-click="blocks.remove(data.id)"><i class="fa fa-fw fa-times"></i></button>
                        </td>
                    </tr>
                    <tfoot ng-show="tableBlocks.allData > tableBlocks.data">
                        <tr>
                            <td colspan="3">
                                <gr-table-pager for="tableBlocks"></gr-table-pager>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="well text-muted" ng-if="blocks.items.length === 0">{{'MODULE.PAGE.NOTFOUND.ADDED.BLOCK' | grTranslate}}</div>
            <hr/>
            <label style="padding-top: 9px;">{{'LABEL.METATAGS' | grTranslate}}</label>
            <button type="button" class="btn btn-success btn-xs pull-right" ng-click="metatags.new()" style="margin: 1px 0 8px;"><i class="fa fa-fw fa-plus"></i>&nbsp; {{'BUTTON.ADD.METATAG' | grTranslate}}</button>
            <div class="table-responsive" style="overflow: auto;" ng-show="metatags.items.length > 0">
                <table gr-table name="tableMetatags" list="metatags.items" style="min-width: 0;">
                    <tr gr-repeat="(key, data) in $data">
                        <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-8" class="col-8 text-left">
                            {{data.name}}
                        </td>
                        <td data-title="'BUTTON.EDIT' | grTranslate" header-class="col-2" class="col-2 text-center">
                            <button type="button" class="btn btn-primary btn-xs btn-table" ng-click="metatags.edit(data.id)"><i class="fa fa-fw fa-edit"></i></button>
                        </td>
                        <td data-title="'BUTTON.DELETE' | grTranslate" header-class="col-2" class="col-2 text-center">
                            <button type="button" class="btn btn-danger btn-xs btn-table" ng-click="metatags.remove(data.id)"><i class="fa fa-fw fa-times"></i></button>
                        </td>
                    </tr>
                    <tfoot ng-show="tableMetatags.allData > tableMetatags.data">
                        <tr>
                            <td colspan="3">
                                <gr-table-pager for="tableMetatags"></gr-table-pager>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="well text-muted" ng-if="metatags.items.length === 0">{{'MODULE.PAGE.NOTFOUND.ADDED.METATAG' | grTranslate}}</div>
        </div>
    </div>
</div>
