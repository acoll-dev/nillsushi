<div data-ng-controller="homeCtrl">
    <div class="jumbotron">
        <div class="container">
            <div gr-html="BLOCKS.welcome"></div>
            <p>
                <a class="btn btn-primary btn-lg" href="home" role="button">Saiba mais &raquo;</a>
            </p>
            <form class="sidebar-menu-filter" data-ng-submit="filter(searchFilter)">
                <div class="input-group">
                    <input type="text" data-ng-model="searchFilter" class="form-control" placeholder="{{'LABEL.SEARCH' | translate}}" />
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-search"></i></button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center">
                <h2>Título</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In eu malesuada mi. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nam vitae ligula sit amet quam maximus malesuada quis sed nulla.</p>
                <p><a class="btn btn-default" href="home" role="button">Saiba mais &raquo;</a>
                </p>
            </div>
            <div class="col-md-4 text-center">
                <h2>Título</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In eu malesuada mi. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nam vitae ligula sit amet quam maximus malesuada quis sed nulla.</p>
                <p><a class="btn btn-default" href="home" role="button">Saiba mais &raquo;</a>
                </p>
            </div>
            <div class="col-md-4 text-center">
                <h2>Título</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In eu malesuada mi. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nam vitae ligula sit amet quam maximus malesuada quis sed nulla.</p>
                <p><a class="btn btn-default" href="home" role="button">Saiba mais &raquo;</a>
                </p>
            </div>
        </div>
    </div>
</div>
