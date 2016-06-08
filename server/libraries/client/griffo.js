'use strict';
(function(){
    angular.module('griffo', ['gr.core', 'gr.directive', 'gr.filter']);
}());
(function(){
    angular.module('gr.core', ['gr.ui', 'gr.restful', 'gr.filemanager', 'textAngular', 'ui.codemirror', 'pascalprecht.translate', 'angularLoad', 'ngPrint', 'angular-images-loaded', 'ngCookies', 'ui.select', 'cidade-estado'])
        .factory('$griffo', ['$rootScope', '$timeout', '$window', '$http', '$translate', function ($rootScope, $timeout, $window, $http, $translate) {
                var griffo,
                    wnd = angular.element($window),
                    getViewPort = function(){
                        var w = $window,
                            d = $window.document,
                            viewPort = {
                                width: 0,
                                height: 0
                            },
                            setBs = function(){
                                if(viewPort.width < 768){
                                    viewPort.bs = 'xs';
                                }
                                if(viewPort.width >= 768){
                                    viewPort.bs = 'sm';
                                }
                                if(viewPort.width >= 990){
                                    viewPort.bs = 'md';
                                }
                                if(viewPort.width >= 1200){
                                    viewPort.bs = 'lg';
                                };
                            };
                        if (w.innerWidth != null){
                            viewPort.width = w.innerWidth;
                            viewPort.height = w.innerHeight;
                            setBs();
                        }else if (document.compatMode == "CSS1Compat"){
                            viewPort.width =  d.documentElement.clientWidth;
                            viewPort.height = d.documentElement.clientHeight;
                            setBs();
                            return viewPort;
                        }else{
                            viewPort.width = d.body.clientWidth;
                            viewPort.height = d.body.clientHeight;
                            setBs();
                        }
                        return viewPort;
                    },
                    setViewPort = function(){
                        $timeout(function(){
                            $rootScope.GRIFFO.viewPort = getViewPort();
                            $rootScope.$apply();
                        });
                    };
                $rootScope._ = function(str){
                    return $translate(str);
                };
                griffo = {
                    init: function () {
                        wnd.on('resize', setViewPort);
                        $http.get($rootScope.GRIFFO.templatePath + 'config.json').success(function(r){ if(r){ $rootScope.GRIFFO.config = r; } });
                        setViewPort();
                    }
                };
                return {
                    init: griffo.init
                }
        }])
        .config(['$provide', '$grAutofieldsProvider', '$grModalProvider', '$grTableProvider', '$grAlertProvider', '$translateProvider', '$grFilemanagerProvider', function ($provide, $grAutofieldsProvider, $grModalProvider, $grTableProvider, $grAlertProvider, $translateProvider, $grFilemanagerProvider) {
            if(GRIFFO.language){
                $translateProvider.useCookieStorage().useStaticFilesLoader({
                    prefix: GRIFFO.applicationPath + 'language/',
                    suffix: '.json'
                }).preferredLanguage(GRIFFO.language).useSanitizeValueStrategy(null);
            }
            $grModalProvider.setButtons(function(button){
                button.confirm = 'BUTTON.CONFIRM';
                button.cancel = 'BUTTON.CANCEL';
                button.close = 'BUTTON.CLOSE';
                return button;
            });
            $grTableProvider.setMessages(function(messages){
                messages['ALERT.LOADING.TABLE.DATA'] = 'ALERT.LOADING.TABLE.DATA';
                messages['ALERT.RELOADING.TABLE.DATA'] = 'ALERT.RELOADING.TABLE.DATA';
                messages['ALERT.SUCCESS.LOAD.TABLE.DATA'] = 'ALERT.SUCCESS.LOAD.TABLE.DATA';
                messages['ALERT.ERROR.LOAD.TABLE.DATA'] = 'ALERT.ERROR.LOAD.TABLE.DATA';
                messages['NOTFOUND.DATA'] = 'NOTFOUND.DATA';
                return messages;
            });
            $grTableProvider.registerFunctions({
                edit: function($scope){
                    return {
                        fn: ['$grModal', function($grModal, grTable, id, templatePath, mod){
                            if(!mod){ mod = GRIFFO.module; }
                            var modal = $grModal.new({
                                    name: 'edit',
                                    title: 'MODULE.' + mod.toUpperCase() + '.TITLE.EDIT',
                                    size: 'lg',
                                    model: GRIFFO.baseUrl + GRIFFO.modulePath + templatePath,
                                    preload: true,
                                    define: {
                                        grTableImport:{
                                            id: id,
                                            grTable: grTable
                                        }
                                    },
                                    buttons: [
                                        {
                                            type: 'success',
                                            label: 'BUTTON.SAVE',
                                            attr: {
                                                'ng-disabled': '!contentReady'
                                            },
                                            onClick: function(scope, element, controller){
                                                scope.form.submit();
                                            }
                                        }, {
                                            type: 'default',
                                            label: 'BUTTON.RESET',
                                            attr: {
                                                'ng-disabled': '!contentReady'
                                            },
                                            onClick: function(scope, element, controller){
                                                scope.form.reset();
                                            }
                                        }, {
                                            type: 'danger',
                                            label: 'BUTTON.CLOSE',
                                            onClick: function(scope, element, controller){
                                                controller.close();
                                            }
                                        }
                                    ]
                                });
                            modal.open();
                        }]
                    }
                },
                delete: function($scope){
                    return {
                        fn: ['$grModal', '$grAlert', '$grRestful', function($grModal, $grAlert, $grRestful, grTable, id, mod){
                            if(!mod){ mod = GRIFFO.module; }
                            var alert = $grAlert.new(),
                                modal = $grModal.new({
                                    name: 'delete',
                                    title: 'MODULE.' + mod.toUpperCase() + '.TITLE.DELETE',
                                    size: 'xs',
                                    text: 'CONFIRM.DELETE',
                                    define: {
                                        grTableImport:{
                                            id: id,
                                            grTable: grTable
                                        }
                                    },
                                    buttons: [
                                        {
                                            type: 'danger',
                                            label: 'BUTTON.CONFIRM',
                                            onClick: function(scope, element, controller){
                                                $grRestful.delete({
                                                    module: mod,
                                                    id: id
                                                }).then(function(data){
                                                    if(data.response){
                                                        controller.close();
                                                        scope.grTableImport.grTable.reloadData();
                                                    }else{
                                                        controller.close();
                                                        alert.show('danger', data.message);
                                                    }
                                                }, function(e){
                                                    alert.show('danger', e);
                                                });
                                            }
                                        },{
                                            type: 'default',
                                            label: 'BUTTON.CANCEL',
                                            onClick: function(scope, element, controller){
                                                controller.close();
                                            }
                                        }
                                    ]
                                });
                            modal.open();
                        }]
                     }
                }
            });
            $grAlertProvider.registerHandler(['$grTranslate', '$object', function($grTranslate, $object){
                angular.forEach($object, function(o, id){
                    $object[id] = $grTranslate(o);
                });
                return $object;
            }]);
            $grAutofieldsProvider.settings.classes.container.push('col-xs-12');
            $grAutofieldsProvider.registerHandler('hr', function(directive, field){
                var wrapper = angular.element('<div class="form-hr"/>'),
                    hr = angular.element('<hr/>');
                hr.attr(field.attr || {});
                if(field.columns){
                    if(angular.isObject(field.columns)){
                        angular.forEach(field.columns, function(col, id){
                            wrapper.addClass('col-' + id + '-' + col);
                        });
                    }else{
                        wrapper.addClass('col-sm-' + field.columns);
                    }
                }else{
                    wrapper.addClass('col-xs-12');
                }
                wrapper.append(hr);
                return wrapper;
            });
            $grAutofieldsProvider.registerHandler('label', function(directive, field){
                var wrapper = angular.element('<div class="form-label"/>'),
                    label = angular.element('<label gr-translate>');
                label.html(field.value).attr(field.attr || {});
                if(field.columns){
                    if(angular.isObject(field.columns)){
                        angular.forEach(field.columns, function(col, id){
                            wrapper.addClass('col-' + id + '-' + col);
                        });
                    }else{
                        wrapper.addClass('col-sm-' + field.columns);
                    }
                }else{
                    wrapper.addClass('col-xs-12');
                }
                wrapper.append(label);
                return wrapper;
            });
            $grAutofieldsProvider.registerHandler('filemanager', function(directive, field, index){
                if(!field.addons){
                    field.addons = [];
                }
                if(!field.attr){
                    field.attr = {};
                }
                var fieldElements = $grAutofieldsProvider.field(directive, field, '<gr-file-manager-button></gr-file-manager-button');
                if(field.attr.multiple){
                    var listButton = angular.element('<div class="gr-input-item gr-input-item-btn" ng-if="' + directive.dataStr + '.' + field.property + '"><button type="button" class="btn" ng-class="{\'active btn-default\': $parent.displayVisible, \'btn-primary\': !$parent.displayVisible}" ng-attr-title="{{\'BUTTON.LIST\' | grTranslate}}" ng-click="$parent.displayVisible = !$parent.displayVisible" ng-init="$parent.displayVisible = false"><i class="fa fa-fw fa-th"></i><span ng-if="' + directive.dataStr + '.' + field.property + '"> ({{' + directive.dataStr + '.' + field.property + '.split(\';\').length}})</span></button></div>'),
                        clearButton = angular.element('<div class="gr-input-item gr-input-item-btn" ng-if="' + directive.dataStr + '.' + field.property + '"><button type="button" class="btn btn-danger" ng-attr-title="{{\'BUTTON.CLEAR\' | grTranslate}}" ng-click="' + directive.dataStr + '.' + field.property + ' = \'\'"><i class="fa fa-fw fa-times"></i></button></div>'),
                        display = [
                            '<section class="gr-filemanager-display" ng-if="' + directive.dataStr + '.' + field.property + '" ng-show="displayVisible">',
                                '<div fancybox class="img-wrapper col-xs-3 col-lg-2" ng-repeat="image in ' + directive.dataStr + '.' + field.property + '.split(\';\')" gr-autoscale="\'1:1\'" gr-autoscale-if="displayVisible">',
                                    '<a class="fancybox" rel="gallery" ng-href="{{GRIFFO.uploadPath + image}}">',
                                        '<img ng-src="{{GRIFFO.uploadPath + image}}"/>',
                                    '</a>',
                                '</div>',
                            '</section>'
                        ].join('');
                    display = angular.element(display);
                    fieldElements.input.attr({
                        'ng-model': directive.dataStr + '.' + field.property,
                        'label': (field.attr ? directive.dataStr + '.' + field.property + ' ? (\'' + field.attr.label.change + '\' | grTranslate) : (\'' + field.attr.label.select + '\' | grTranslate)' : undefined) || field.label,
                        'allow-multiple': '\'' + (field.attr ? field.attr.multiple : false) + '\'',
                        'filter': '\'' + (field.attr ? (field.attr.filter ? field.attr.filter : 'all') : 'all') + '\'',
                        'label-icon': '\'' + (field.attr ? field.attr.label.icon : '') + '\'',
                        'type': 'button',
                        'callback': true
                    }).after('<input type="text" class="hidden" ' + ((field.attr && field.attr.required) ? 'required' : '') + ' ng-model="' + directive.dataStr + '.' + field.property + '" name="' + field.property + '"/>');

                    display.insertAfter(fieldElements.input.parent('.gr-input-wrapper'));
                    clearButton.insertAfter(fieldElements.input.parent('.gr-input-wrapper'));
                    listButton.insertAfter(fieldElements.input.parent('.gr-input-wrapper'));
                }else{
                    var clearButton = angular.element('<div class="gr-input-item gr-input-item-btn" ng-if="' + directive.dataStr + '.' + field.property + '"><button type="button" class="btn btn-danger" ng-attr-title="{{\'BUTTON.CLEAR\' | grTranslate}}" ng-click="' + directive.dataStr + '.' + field.property + ' = \'\'"><i class="fa fa-fw fa-times"></i></button></div>'),
                        display = [
                            '<div fancybox class="gr-input-item gr-input-item-addon gr-filemanager-display-single" ng-if="' + directive.dataStr + '.' + field.property + '">',
                                '<a class="fancybox" ng-href="{{GRIFFO.uploadPath + ' + directive.dataStr + '.' + field.property + '}}">',
                                    '<img ng-src="{{GRIFFO.uploadPath + ' + directive.dataStr + '.' + field.property + '}}"/>',
                                '</a>',
                            '</div>'
                        ].join('');
                    display = angular.element(display);
                    fieldElements.input.attr({
                        'ng-model': directive.dataStr + '.' + field.property,
                        'label': (field.attr ? directive.dataStr + '.' + field.property + ' ? (\'' + field.attr.label.change + '\' | grTranslate) : (\'' + field.attr.label.select + '\' | grTranslate)' : undefined) || field.label,
                        'allow-multiple': '\'' + (field.attr ? field.attr.multiple : false) + '\'',
                        'filter': '\'' + (field.attr ? (field.attr.filter ? field.attr.filter : 'all') : 'all') + '\'',
                        'label-icon': '\'' + (field.attr ? field.attr.label.icon : '') + '\'',
                        'type': 'button',
                        'callback': true
                    }).after('<input type="text" class="hidden" ' + ((field.attr && field.attr.required) ? 'required' : '') + ' ng-model="' + directive.dataStr + '.' + field.property + '" name="' + field.property + '"/>');

                    fieldElements.input.removeAttr('name');

                    clearButton.insertAfter(fieldElements.input.parent('.gr-input-wrapper'));
                    display.insertAfter(fieldElements.input.parent('.gr-input-wrapper'));
                }

                angular.element('<i class="fa fa-fw fa-lg fa-check-circle success-icon"></i>').prependTo(fieldElements.fieldContainer);
                angular.element('<i class="fa fa-fw fa-lg fa-times-circle error-icon"></i>').prependTo(fieldElements.fieldContainer);

                fieldElements.fieldContainer.find('label').remove();

                fieldElements.label = angular.element('<div class="gr-input-item gr-input-label gr-input-item-addon"><label gr-translate>' + field.label + '</label></div>');

                fieldElements.label.prependTo(fieldElements.fieldContainer.children('.gr-input-container'));

                return fieldElements.fieldContainer;
            });
            $grAutofieldsProvider.registerHandler('null', function(directive, field, index){ return ''; });

            /* ui-select2 */

            try{
                $grAutofieldsProvider.registerHandler('select2', function(directive, field, index){

                    if(field.list){
                        var defaultOption = (field.defaultOption ? field.defaultOption : directive.options.defaultOption);

                        var inputHtml = [
                            '<ui-select ng-model="' + field.property + '" theme="bootstrap" gr-bind-parent-events>',
                                '<ui-select-match placeholder="{{\'' + (field.placeholder ? field.placeholder : '') + '\' | grTranslate }}">{{' + (field.choice.value ? '$select.selected.' + field.choice.value : '$select.selected') + '}}</ui-select-match>',
                                '<ui-select-choices ' + (field.groupby ? 'group-by="\'' + field.groupby + '\'" ' : '') + 'repeat="item in ' + field.list + ' | filter: $select.search">',
                                    (field.choice && field.choice.item.big ? '<div ng-bind-html="item.' + field.choice.item.big + ' | highlight: $select.search"></div>' : ''),
                                    (field.choice && field.choice.item.small ? '<span ng-bind-html="item.' + field.choice.item.small + ' | highlight: $select.search"></span>' : ''),
                                '</ui-select-choices>',
                            '</ui-select>'].join('');

                        var fieldElements = $grAutofieldsProvider.field(directive, field, inputHtml, {});

                        return fieldElements.fieldContainer;
                    }else{
                        return false;
                    }
                });
            }catch(r){}

            /* textAngular */

            try{
                $grAutofieldsProvider.registerHandler('html_basic', function(directive, field, index){
                    var fieldElements = $grAutofieldsProvider.field(directive, field, '<text-angular/>');
                    fieldElements.fieldContainer.append(toolbar);
                    $grAutofieldsProvider.settings.scope.codemirrorOpts = {
                        mode: 'htmlmixed',
                        fixedGutters: true,
                        lineNumbers: true,
                        lineWrapping : true,
                        theme: 'material',
                        indentUnit: 4,
                        indentWithTabs: true,
                        autoCloseTags: true,
                        matchTags: {bothTags: true},
                        autoCloseBrackets: true,
                        matchBrackets: true,
                        foldGutter: true,
                        gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
                        extraKeys: {
                            'F11': function(cm) {
                                cm.setOption('fullScreen', !cm.getOption('fullScreen'));
                            },
                            'Esc': function(cm) {
                                if (cm.getOption('fullScreen')){
                                    cm.setOption('fullScreen', false);
                                }
                            },
                            'Ctrl-J': "toMatchingTag"
                        }
                    };
                    fieldElements.input.removeClass('form-control').attr({
                        'codemirror': true,
                        'codemirror-opts': 'codemirrorOpts',
                        'ta-toolbar': [
                            '{{[',
                                '[\'html\', \'insertLink\'],',
                                '[\'h1\', \'h2\', \'h3\', \'h4\', \'p\'],',
                                '[\'bold\', \'italics\', \'underline\', \'ul\', \'ol\', \'clear\'],',
                                '[\'justifyLeft\',\'justifyCenter\',\'justifyRight\']',
                            ']}}'
                        ].join('')
                    });
                    angular.element('<i class="fa fa-fw fa-lg fa-check-circle success-icon"></i>').insertBefore(fieldElements.input);
                    angular.element('<i class="fa fa-fw fa-lg fa-times-circle error-icon"></i>').insertBefore(fieldElements.input);
                    return fieldElements.fieldContainer;
                });
                $grAutofieldsProvider.registerHandler('html', function(directive, field, index){
                    var fieldElements = $grAutofieldsProvider.field(directive, field, '<text-angular/>');
                    fieldElements.fieldContainer.append(toolbar);
                    $grAutofieldsProvider.settings.scope.codemirrorOpts = {
                        mode: 'htmlmixed',
                        fixedGutters: true,
                        lineNumbers: true,
                        lineWrapping : true,
                        theme: 'material',
                        indentUnit: 4,
                        indentWithTabs: true,
                        autoCloseTags: true,
                        matchTags: {bothTags: true},
                        autoCloseBrackets: true,
                        matchBrackets: true,
                        foldGutter: true,
                        gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
                        extraKeys: {
                            'F11': function(cm) {
                                cm.setOption('fullScreen', !cm.getOption('fullScreen'));
                            },
                            'Esc': function(cm) {
                                if (cm.getOption('fullScreen')){
                                    cm.setOption('fullScreen', false);
                                }
                            },
                            'Ctrl-J': "toMatchingTag"
                        }
                    };
                    fieldElements.input.removeClass('form-control').attr({
                        'codemirror': true,
                        'codemirror-opts': 'codemirrorOpts'
                    });
                    angular.element('<i class="fa fa-fw fa-lg fa-check-circle success-icon"></i>').insertBefore(fieldElements.input);
                    angular.element('<i class="fa fa-fw fa-lg fa-times-circle error-icon"></i>').insertBefore(fieldElements.input);
                    return fieldElements.fieldContainer;
                });
                $provide.decorator('taOptions', ['taRegisterTool', '$delegate', '$filter', '$grModal', function(taRegisterTool, taOptions, $filter, $grModal){
                    try{
                        var imgOnSelectAction = function(event, $element, editorScope){
                                var finishEdit = function(){
                                    editorScope.updateTaBindtaTextElement();
                                    editorScope.hidePopover();
                                };
                                event.preventDefault();
                                editorScope.displayElements.popover.css('width', '375px');
                                var container = editorScope.displayElements.popoverContainer;
                                container.empty();
                                var buttonGroup = angular.element('<div class="btn-group" style="padding-right: 6px;">');
                                var fullButton = angular.element('<button type="button" class="btn btn-default btn-sm btn-small" unselectable="on" tabindex="-1">100% </button>');
                                fullButton.on('click', function(event){
                                    event.preventDefault();
                                    $element.css({
                                        'width': '100%',
                                        'height': ''
                                    });
                                    finishEdit();
                                });
                                var halfButton = angular.element('<button type="button" class="btn btn-default btn-sm btn-small" unselectable="on" tabindex="-1">50% </button>');
                                halfButton.on('click', function(event){
                                    event.preventDefault();
                                    $element.css({
                                        'width': '50%',
                                        'height': ''
                                    });
                                    finishEdit();
                                });
                                var quartButton = angular.element('<button type="button" class="btn btn-default btn-sm btn-small" unselectable="on" tabindex="-1">25% </button>');
                                quartButton.on('click', function(event){
                                    event.preventDefault();
                                    $element.css({
                                        'width': '25%',
                                        'height': ''
                                    });
                                    finishEdit();
                                });
                                var resetButton = angular.element('<button type="button" class="btn btn-default btn-sm btn-small" unselectable="on" tabindex="-1">Reset</button>');
                                resetButton.on('click', function(event){
                                    event.preventDefault();
                                    $element.css({
                                        width: '',
                                        height: ''
                                    });
                                    finishEdit();
                                });
                                buttonGroup.append(fullButton);
                                buttonGroup.append(halfButton);
                                buttonGroup.append(quartButton);
                                buttonGroup.append(resetButton);
                                container.append(buttonGroup);
                                buttonGroup = angular.element('<div class="btn-group" style="padding-right: 6px;">');
                                var floatLeft = angular.element('<button type="button" class="btn btn-default btn-sm btn-small" unselectable="on" tabindex="-1"><i class="fa fa-align-left"></i></button>');
                                floatLeft.on('click', function(event){
                                    event.preventDefault();
                                    $element.css('float', 'left');
                                    $element.css('cssFloat', 'left');
                                    $element.css('styleFloat', 'left');
                                    finishEdit();
                                });
                                var floatRight = angular.element('<button type="button" class="btn btn-default btn-sm btn-small" unselectable="on" tabindex="-1"><i class="fa fa-align-right"></i></button>');
                                floatRight.on('click', function(event){
                                    event.preventDefault();
                                    $element.css('float', 'right');
                                    $element.css('cssFloat', 'right');
                                    $element.css('styleFloat', 'right');
                                    finishEdit();
                                });
                                var floatNone = angular.element('<button type="button" class="btn btn-default btn-sm btn-small" unselectable="on" tabindex="-1"><i class="fa fa-align-justify"></i></button>');
                                floatNone.on('click', function(event){
                                    event.preventDefault();
                                    $element.css('float', '');
                                    $element.css('cssFloat', '');
                                    $element.css('styleFloat', '');
                                    finishEdit();
                                });
                                buttonGroup.append(floatLeft);
                                buttonGroup.append(floatNone);
                                buttonGroup.append(floatRight);
                                container.append(buttonGroup);
                                buttonGroup = angular.element('<div class="btn-group">');
                                var remove = angular.element('<button type="button" class="btn btn-default btn-sm btn-small" unselectable="on" tabindex="-1"><i class="fa fa-trash-o"></i></button>');
                                remove.on('click', function(event){
                                    event.preventDefault();
                                    $element.remove();
                                    finishEdit();
                                });
                                buttonGroup.append(remove);
                                container.append(buttonGroup);
                                editorScope.showPopover($element);
                                editorScope.showResizeOverlay($element);
                            };
                        taRegisterTool('grImage', {
                            iconclass: 'fa fa-image',
                            tooltiptext: 'Select image',
                            action: function($deferred, restoreSelection){
                                var _this = this,
                                    $editor = _this.$editor();
                                $grFilemanagerProvider.open({
                                    id: 'textAngular-grImage',
                                    filter: 'image',
                                    onGetSelection: function(file){
                                        if(file){
                                            restoreSelection();
                                            $editor.wrapSelection('insertHtml', '<img src="' + GRIFFO.uploadPath + file + '"/>', true);
                                            $deferred.resolve();
                                        }
                                    }
                                });
                                return false;
                            },
                            onElementSelect: {
                                element: 'img',
                                action: imgOnSelectAction
                            }
                        });
                        taOptions.toolbar = [
                            ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre', 'quote'],
                            ['bold', 'italics', 'underline', 'ul', 'ol', 'redo', 'undo', 'clear'],
                            ['justifyLeft','justifyCenter','justifyRight','indent','outdent'],
                            ['html', 'grImage', 'insertVideo', 'insertLink']
                        ];
                    }catch(e){
                        taOptions.toolbar = [
                            ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'pre', 'quote'],
                            ['bold', 'italics', 'underline', 'ul', 'ol', 'redo', 'undo', 'clear'],
                            ['justifyLeft','justifyCenter','justifyRight','indent','outdent'],
                            ['html', 'insertImage', 'insertVideo', 'insertLink']
                        ];
                    }
                    function createTable(tableParams) {
                        var row = angular.copy(parseInt(tableParams.row)),
                            col = angular.copy(parseInt(tableParams.col));
                        if(row && col && row > 0 && col > 0){
                            var table = "<table class='table "
                                + (tableParams.class ? tableParams.class : '')
                                + (tableParams.style ? "border-" + tableParams.style : '')
                                + "'>";
                            var colWidth = 100/col;
                            for (var idxRow = 0; idxRow < row; idxRow++) {
                                var row = "<tr>";
                                for (var idxCol = 0; idxCol < col; idxCol++) {
                                    row += "<td"
                                        + (idxRow == 0 ? ' style="width: ' + colWidth + '%;"' : '')
                                        +">&nbsp;</td>";
                                }
                                table += row + "</tr>";
                            }
                            return table + "</table>";
                        }
                    }
                    taOptions.toolbar.push(['insertTable']);
                    taRegisterTool('insertTable', {
                        iconclass: 'fa fa-table',
                        tooltiptext: 'Insert table',
                        action: function($deferred, restoreSelection){
                            var _this = this,
                                $editor = _this.$editor(),
                                modal = $grModal.new({
                                    name: 'insertTable',
                                    title: $filter('grTranslate')('LABEL.ADD.TABLE'),
                                    size: 'sm',
                                    template: '<div class="container-fluid"><form name="form" gr-autofields="formSettings"></form></div>',
                                    define: {
                                        formSettings: {
                                            data: {},
                                            schema: [
                                                {
                                                    property: 'row',
                                                    label: 'LABEL.ROWS',
                                                    columns: 6,
                                                    number: true,
                                                    attr:{
                                                        required: true
                                                    },
                                                    msgs: {
                                                        required: 'FORM.MESSAGES.REQUIRED.ROWS'
                                                    }
                                                }, {
                                                    property: 'col',
                                                    label: 'LABEL.COLS',
                                                    columns: 6,
                                                    number: true,
                                                    attr:{
                                                        required: true
                                                    },
                                                    msgs: {
                                                        required: 'FORM.MESSAGES.REQUIRED.COLS'
                                                    }
                                                }, {
                                                    property: 'class',
                                                    columns: 12
                                                }
                                            ],
                                            submit: function(data){
                                                if(data.row && data.col){
                                                    restoreSelection();
                                                    $editor.wrapSelection('insertHtml', createTable(data), true);
                                                    $deferred.resolve();
                                                    curModal.close();
                                                }
                                            }
                                        }
                                    },
                                    buttons: [{
                                        type: 'success',
                                        label: $filter('grTranslate')('BUTTON.ADD'),
                                        onClick: function($scope, controller){
                                            $scope.form.submit();
                                        }
                                    }, {
                                        type: 'danger',
                                        label: $filter('grTranslate')('BUTTON.CANCEL'),
                                        onClick: function($scope, $element, controller){
                                            controller.close();
                                        }
                                    }]
                                }),
                                curModal = modal.open();
                            return false;
                        }
                    });
                    taOptions.classes = {
                        focussed: 'focussed',
                        toolbar: 'btn-toolbar',
                        toolbarGroup: 'btn-group',
                        toolbarButton: 'btn btn-default',
                        toolbarButtonActive: 'active',
                        disabled: 'disabled',
                        textEditor: 'form-control',
                        htmlEditor: 'form-control'
                    };
                    return taOptions;
                }]);
            }catch(r){}
        }])
        .run(['$griffo', '$rootScope', function ($griffo, $rootScope) {
                $rootScope.GRIFFO = GRIFFO;
                $griffo.init();
                $rootScope.GRIFFO.system.ui = griffoUI.version;
                $rootScope.GRIFFO.system.jquery = jQuery().jquery;
                $rootScope.GRIFFO.system.angular = angular.version.full;
                $rootScope._ = _;
        }]);
}());
(function(){
    angular.module('gr.directive', [])
        .directive('grHtml', ['$compile', function($compile){
            return {
                restrict: 'A',
                scope: {
                    html: '=grHtml'
                },
                link: function($scope, $element, $attrs){
                    $scope.$watch('html', function(html){
                        $element.html(html);
                    });
                }
            }
        }]);
}());
(function(){
    angular.module('gr.filter', [])
        .filter('parseLang', function(){
            return function(lang){
                return lang.replace('_', '-');
            }
        })
        .filter('limitString', function () {
            return function (value, wordwise, max, tail) {
                if (!value) return '';
                max = parseInt(max, 10);
                if (!max) return value;
                if (value.length <= max) return value;
                value = value.substr(0, max);
                if (wordwise) {
                    var lastspace = value.lastIndexOf(' ');
                    if (lastspace != -1) {
                        value = value.substr(0, lastspace);
                    }
                }
                return value + (tail || '…');
            };
        })
        .filter('limitArray', function () {
            return function (value, max) {
                if (!value) return '';
                if (!max) return value;
                if (value.length <= max) return value;
                value = value.splice(max, value.length);
                return value;
            };
        });
}());
