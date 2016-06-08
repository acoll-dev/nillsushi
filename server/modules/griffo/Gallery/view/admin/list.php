<div class="view-header">{{'Edit Gallery' | grTranslate}}</div>
<div class="view-content">
    <table gr-table remote="{module: 'gallery', action: 'get', onelevel: true}" export-csv="GRIFFO_GalleryList">
        <tr gr-repeat="(key, data) in $data">
            <td data-title="'#'" sortable="'idgallery'" filter="{'idgallery': 'text'}" header-class="col-1" class="col-1 text-center">
                {{data.idgallery}}
            </td>
            <td data-title="'Name' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-2" class="col-2 text-center">
                {{data.name}}
            </td>
            <td data-title="'Path' | grTranslate" sortable="'path'" filter="{'path': 'text'}" header-class="col-2" class="col-2 text-center">
                {{data.path}}
            </td>
            <td data-title="'Edit' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.idgallery,'view/admin/edit.php')"><i class="fa fa-fw fa-edit"></i></button>
            </td>
            <td data-title="'Delete' | grTranslate" header-class="col-1" class="col-1 text-center">
                <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.idgallery)"><i class="fa fa-fw fa-times"></i></button>
            </td>
        </tr>    
    </table>
</div>
<div class="view-footer">
    <div class="view-footer-header">
        <gr-table-pager></gr-table-pager>

        <div class="pull-right">
            <label class="text-muted hidden-xs hidden-sm">{{'Rows per page' | grTranslate}}:</label>
            <gr-table-count></gr-table-count>
        </div>
    </div>

    <gr-table-clear-sorting class="btn btn-warning" title="{{'Clear sorting' | grTranslate}}"><i class="fa fa-fw fa-unsorted"></i><span class="hidden-xs hidden-sm">  {{'Clear sorting' | grTranslate}}</span>
    </gr-table-clear-sorting>

    <gr-table-clear-filter class="btn btn-warning" title="{{'Clear filter' | grTranslate}}"><i class="fa fa-fw fa-filter"></i><span class="hidden-xs hidden-sm">  {{'Clear filter' | grTranslate}}</span>
    </gr-table-clear-filter>

    <gr-table-export-csv class="btn btn-primary pull-right" title="{{'Export table to CSV' | grTranslate}}"><i class="fa fa-fw fa-download"></i><span class="hidden-xs hidden-sm"> {{'Export table to CSV' | grTranslate}}</span>
    </gr-table-export-csv>
</div>

