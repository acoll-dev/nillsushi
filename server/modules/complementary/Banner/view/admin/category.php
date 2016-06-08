<div class="view-header">{{'MODULE.BANNER.TITLE.CATEGORY' | grTranslate}}</div>
<div class="view-content">
	<div class="view-content-inner" ng-controller="formCtrl">
		<div class="container-fluid">
			<h4>{{'MODULE.BANNER.TITLE.CATEGORY.NEW' | grTranslate}}</h4>
            <div class="well">
                <form name="form" gr-autofields="formSettings"></form>
                <button ng-click="form.submit()" type="submit" class="btn btn-success">{{'BUTTON.SAVE' | grTranslate}}</button>
                <button ng-click="form.reset()" type="reset" class="btn btn-danger">{{'BUTTON.RESET' | grTranslate}}</button>
                <span class="pull-right gr-form-required-label">{{'LABEL.REQUIRED.FIELDS' | grTranslate}}</span>
            </div>
		</div>
	</div>
	<div ng-controller="tableCtrl">
        <table gr-table list="categoriesList" reload="updateCategories(true)" export-csv="GRIFFO_categoryBannerList">
            <tr gr-repeat="(key, data) in $data">
                <td data-title="'#'" sortable="'idcategory'" filter="{'idcategory': 'text'}" header-class="col-1" class="col-1 text-center">
                    {{data.idcategory}}
                </td>
                <td data-title="'LABEL.NAME' | grTranslate" sortable="'name'" filter="{'name': 'text'}" header-class="col-5" class="col-5">
                    {{data.name}}
                </td>
                <td data-title="'LABEL.CATEGORY.PARENT' | grTranslate" sortable="'parent.name'" filter="{'parent.name': 'text'}" header-class="col-3" class="col-3">
                    {{data.parent.name}}
                </td>
                <td data-title="'LABEL.SORT' | grTranslate" sortable="'sort'" header-class="col-1" class="col-1">
                    <input type="number" class="form-control input-sm" ng-model="data.sort" ng-blur="updateOrder(data)" ng-change="data.changed = true" />
                </td>
                <td data-title="'BUTTON.EDIT' | grTranslate" header-class="col-1" class="col-1 text-center">
                    <button class="btn btn-xs btn-table btn-primary" ng-click="grTable.fn.edit(grTable,data.idcategory,'view/admin/edit_category.php','category')"><i class="fa fa-fw fa-edit"></i></button>
                </td>
                <td data-title="'BUTTON.DELETE' | grTranslate" header-class="col-1" class="col-1 text-center">
                    <button class="btn btn-xs btn-table btn-danger" ng-click="grTable.fn.delete(grTable,data.idcategory,'category')"><i class="fa fa-fw fa-times"></i></button>
                </td>
            </tr>
        </table>
	</div>
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
