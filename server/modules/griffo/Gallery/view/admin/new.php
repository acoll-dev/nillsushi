<div class="view-header">{{'Register Gallery' | grTranslate}}</div>
<div class="view-content">
    <div class="view-content-inner">
        <gr-form gr-name="register-gallery">
            <gr-input gr-name="name" gr-label="Text" gr-validate="required: true" gr-icon="fa fa-fw fa-file" class="col-xs-12 col-sm-6 col-md-4 col-lg-4"></gr-input>

            <gr-input gr-name="path" gr-type="text" gr-validate="required: true" gr-label="Path" class="col-xs-12 col-sm-6 col-md-4 col-lg-4"></gr-input>
            <gr-input gr-name="idgalleryparent" gr-type="select"  gr-label="Parent" class="col-xs-12 col-sm-6 col-md-4 col-lg-4"></gr-input>
            <gr-input gr-name="fkidthumb" gr-type="select"  gr-label="Thumb" class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            </gr-input>
        </gr-form>
        <script type="grForm/form">
            [{'register-layer': function ($scope) {
                return {
                    inject: ['grRestful'],
                    submit: ['$grRestful', '$timeout',
                    function (REST, $timeout, data, controller) {
                            REST.create({
                                module: 'layer',
                                action: 'insert',
                                post: data
                            }).then(function (r) {
                                controller.reset();
                                controller.$message.show(r.status, r.message);
                            },
                            function (r) {
                                controller.$message.show('danger', 'Fatal error, contact a system administrator!');
                            });
                    }]
                }
            }}]
        </script>
    </div>
</div>
<div class="view-footer">
    <button ng-click="grForm['register-gallery'].submit()" type="submit" class="btn btn-success">{{'Save' | grTranslate}}</button>
    <button ng-click="grForm['register-gallery'].reset()" type="reset" class="btn btn-default">{{'Reset' | grTranslate}}</button>
    <span class="pull-right gr-form-required-label">{{'Required fields' | grTranslate}}</span>
</div>
