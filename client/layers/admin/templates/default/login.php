<?php
    global $PAGE;
    $PAGE->setTag(array(
        'css' => array(
            PATH_TEMPLATE . "css/bootstrap.css",
            PATH_TEMPLATE.'css/main.css',
            PATH_TEMPLATE.'css/login.css'
        ),
        'htmlAttr' => array(
            'ng-app'=>'loginApp',
            'ng-controller'=>'loginCtrl'
        )
    ));
    $PAGE->getHeader();
?>
<div class="wrapper container-fluid" ng-cloak>
    <div class="panel panel-default panel-login">
        <div class="panel-heading">
            <i class="acoll-griffo"></i>
        </div>
        <div class="panel-body">
            <form name="login" gr-autofields="loginForm"></form>
        </div>
        <div class="panel-footer">
            <div class="container-fluid">
                <button class="btn btn-primary pull-right" ng-click="login.submit()" gr-translate>BUTTON.LOGIN</button>
            </div>
        </div>
    </div>
    <div class="signature-login sig">
        <p class="small text-center text-muted ">
            <a href="http://www.acoll.com.br/" class="clearfix">
                <i class="acoll-sig "></i>
            </a>
            Copyright &copy; 2014, Acoll Dev Team.
        </p>
    </div>
</div>
<?php
    $PAGE->setTag(array(
        'js' => array(
            PATH_TEMPLATE.'js/loginApp.js',
            PATH_TEMPLATE.'js/controllers/loginCtrl.js'
        )
    ),'footer');
    $PAGE->getFooter();
?>
