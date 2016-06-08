<div class="view-header">{{'MODULE.PAGE.TITLE.LIST' | grTranslate}}</div>
<div class="view-content" ng-controller="tableCtrl">
    <table gr-table remote="{module: 'page', action: 'get', onelevel: true}" export-csv="GRIFFO_PageList">
        <tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'idpage'" filter="{'idpage': 'text'}" header-class="col-1" class="col-1 text-center">
                {{data.idpage}}
            </td>
            <td data-title="'LABEL.TITLE' | grTranslate" sortable="'title'" filter="{'title': 'text'}" header-class="col-2" class="col-2">
                {{data.title}}
            </td>
            <td data-title="'LABEL.URL' | grTranslate" sortable="'url'" filter="{'url': 'text'}" header-class="col-2" class="col-2">
                {{data.url}}
            </td>
            <td data-title="'LABEL.PAGE.PARENT' | grTranslate" sortable="'pageparent'" filter="{'pageparent': 'text'}" header-class="col-2" class="col-2">
                {{data.pageparent}}
            </td>
            <td data-title="'LABEL.STATUS' | grTranslate" sortable="'status'" class="col-1 text-center">
                <span class="badge badge-{{data.status === 1 ? 'success' : 'danger'}}">&nbsp;</span>
            </td>
            <td data-title="'BUTTON.EDIT' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="editPage(grTable,data.idpage,'view/admin/edit.php')"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'BUTTON.DELETE' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.idpage)"><i class="fa fa-fw fa-times"></i></button>
            </td>
        </tr>
    </table>
</div>
<div class="view-footer">
    <div class="view-footer-header">
        <gr-table-pager></gr-table-pager>
        <gr-table-count class="pull-right"></gr-table-count>
    </div>
    <gr-table-clear-sorting class="btn btn-warning" title="{{'BUTTON.CLEAR.SORTING' | grTranslate}}"><i class="fa fa-fw fa-unsorted"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.SORTING' | grTranslate}}</span>
    </gr-table-clear-sorting>
    <gr-table-clear-filter class="btn btn-warning" title="{{'BUTTON.CLEAR.FILTER' | grTranslate}}"><i class="fa fa-fw fa-filter"></i><span class="hidden-xs hidden-sm">  {{'BUTTON.CLEAR.FILTER' | grTranslate}}</span>
    </gr-table-clear-filter>
    <gr-table-export-csv class="btn btn-primary pull-right" title="{{'BUTTON.EXPORT.TABLE.CSV' | grTranslate}}"><i class="fa fa-fw fa-download"></i><span class="hidden-xs hidden-sm"> {{'BUTTON.EXPORT.TABLE.CSV' | grTranslate}}</span>
    </gr-table-export-csv>
</div>
