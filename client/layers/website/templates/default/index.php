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
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Griffo Framework</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<main>
    <div class="jumbotron">
        <div class="container">
            <h1>Hello, world!</h1>
            <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
            <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <h2>Heading</h2>
                <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
            </div>
        </div>
    </div>
    <hr/>
    <footer class="container-fluid text-center">
        <div class="image-footer" title="Powered by Griffo Framework">
            <a href="http://www.acoll.com.br">
               <img src="{{GRIFFO.templatePath}}image/griffo-footer-black.png" />
           </a>
        </div>
    </footr>
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
                URL_LIBRARIES_PATH . "client/angular-seo/js/angular-seo.js",
                PATH_TEMPLATE . "js/mainApp.js",
                PATH_TEMPLATE . "js/controllers/mainCtrl.js"//,
                // PATH_TEMPLATE . "js/directives/directive.js",
                // PATH_TEMPLATE . "js/filters/filter.js",
                // PATH_TEMPLATE . "js/services/service.js"
            ),
            "analyticsCode"=>"UA-XXXXX-X"
        ),'footer'
    );
    $PAGE->getFooter();
?>
