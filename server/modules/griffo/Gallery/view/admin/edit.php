<div class="view-content">
    <div class="view-content-inner">
        <gr-form gr-name="edit-gallery">
            <gr-input gr-name="name" gr-label="Text" gr-validate="required: true" gr-icon="fa fa-fw fa-file" class="col-xs-12 col-sm-6 col-md-4 col-lg-4"></gr-input>

            <gr-input gr-name="path" gr-type="text" gr-validate="required: true" gr-label="Path" class="col-xs-12 col-sm-6 col-md-4 col-lg-4"></gr-input>
            <gr-input gr-name="idparent" gr-type="select"  gr-label="Parent" class="col-xs-12 col-sm-6 col-md-4 col-lg-4"></gr-input>
            <gr-input gr-name="idthumb" gr-type="select"  gr-label="Thumb" class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            </gr-input>
            <gr-input gr-type="text" gr-name="idgallery" class="hidden" gr-value="{{grTableImport.id}}"></gr-input>
        </gr-form>
        <script type="grForm/form">
            [{'edit-gallery': function($scope){
            var idgallery = $scope.grTableImport.id;
            return{
                inject: ['grRestful'],
                submit: ['$grRestful', '$timeout',
                    function (REST, $timeout, data, controller) {
                        REST.update({
                            module: 'gallery',
                            action: 'update_attributes',
                            id: idgallery,
                            post: data
                        }).then(
                                function (success) {
                                    if(success.response){
                                            $scope.grTableImport.grTable.reloadData();
                                    }
                                    controller.$message.show(success.status, success.message);
                                },
                                function (error) {
                                    controller.$message.show('danger', 'Fatal error, contact a system administrator!');
                                }
                            );
                }],
                'data-source': GRIFFO.baseUrl + GRIFFO.restPath + 'gallery/' + idgallery
            }
        }}]
        </script>
    </div>
</div>
