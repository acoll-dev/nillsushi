<?php
    global $PAGE;
    $PAGE->setTag(
        array(
            "css"=>array(
                PATH_TEMPLATE . "css/bootstrap.css",
                PATH_TEMPLATE . "css/main.css"
            ),
            "htmlAttr"=>array(
                "data-ng-app"=>"mainApp",
                "data-ng-controller"=>"mainCtrl"
            )
        )
    );
    $PAGE->getHeader();
?>
<main class="container-fluid">
    <div ng-include="GRIFFO.templatePath + 'view/blocks/header.php'" include-replace></div>
	<div class="content" ng-include="GRIFFO.curView"></div>
    <div ng-include="GRIFFO.templatePath + 'view/blocks/footer.php'" include-replace></div>
</main>
<script>
	WebFontConfig = {
		google: {
			families: ['Droid+Sans:400,700,300:latin']
		}
	};
</script>
<script src="//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
<?php
    $PAGE->setTag(
        array(
            "js"=>array(
                PATH_TEMPLATE . "js/mainApp.js",
                PATH_TEMPLATE . "js/controllers/mainCtrl.js",
                PATH_TEMPLATE . "js/directives/imgLiquidDrt.js",
                PATH_TEMPLATE . "js/directives/includeReplaceDrt.js",

                PATH_LIBRARIES . "client/vendor/imgLiquid/js/imgLiquid.js",

                PATH_LIBRARIES . "client/desktop-notify/desktop-notify.min.js"
            )//,
//            "analyticsCode"=>"UA-XXXXX-X"
        ),'footer'
    );
    $PAGE->getFooter();
?>
