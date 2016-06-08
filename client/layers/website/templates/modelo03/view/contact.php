<div class="block block-large" ng-controller="contactCtrl">
    <div class="container">
        <!-- <h1 class="title" ng-if="GRIFFO.config.page.contact.title">{{GRIFFO.config.page.contact.title}}</h1> -->
        <div class="col-xs-12 col-sm-6">
            <h2 class="subtitle">{{GRIFFO.config.page.contact.subtitle.form}}</h2>
            <form name="form" gr-autofields="formSettings"></form>
            <div class="container-fluid">
                <button class="btn" ng-class="GRIFFO.config.page.contact.form.button.submit.class" ng-click="form.submit()" ng-if="GRIFFO.config.page.contact.form.button.submit"><i ng-class="GRIFFO.config.page.contact.form.button.submit.icon" ng-if="GRIFFO.config.page.contact.form.button.submit.icon"></i><span ng-if="GRIFFO.config.page.contact.form.button.submit.icon"> </span><span ng-if="GRIFFO.config.page.contact.form.button.submit.label">{{GRIFFO.config.page.contact.form.button.submit.label}}</span></button>
                <button class="btn" ng-class="GRIFFO.config.page.contact.form.button.reset.class" ng-click="form.reset()" ng-if="GRIFFO.config.page.contact.form.button.reset"><i ng-class="GRIFFO.config.page.contact.form.button.reset.icon" ng-if="GRIFFO.config.page.contact.form.button.reset.icon"></i><span ng-if="GRIFFO.config.page.contact.form.button.reset.icon"> </span><span ng-if="GRIFFO.config.page.contact.form.button.reset.label">{{GRIFFO.config.page.contact.form.button.reset.label}}</span></button>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <h2 class="subtitle">{{GRIFFO.config.page.contact.subtitle.info}}</h2>
            <address class="address">
                <dl class="dl-horizontal">
                    <dt>TELEFONE</dt>
                    <dd><a data-ng-href="phoneto:{{GRIFFO.customer.phone[0]}}">{{GRIFFO.customer.phone[0] | phone}}</a><span ng-if="GRIFFO.customer.phone[1]"> / </span><a data-ng-href="phoneto:{{GRIFFO.customer.phone[1]}}" ng-if="GRIFFO.customer.phone[1]">{{GRIFFO.customer.phone[1] | phone}}</a></dd>
                    <dt data-ng-if="GRIFFO.customer.phone[2]">CELULAR</dt>
                    <dd data-ng-if="GRIFFO.customer.phone[2]"><a data-ng-href="phoneto:{{GRIFFO.customer.phone[2]}}">{{GRIFFO.customer.phone[2] | phone}}</a></dd>
                    <dt>ENDEREÇO</dt>
                    <dd>{{GRIFFO.customer.address}}</dd>
                    <dt>E-MAIL</dt>
                    <dd><a data-ng-href="mailto:{{GRIFFO.customer.email[0]}}">{{GRIFFO.customer.email[0]}}</a></dd>
                </dl>
            </address>
            <div class="social" ng-if="_.size(GRIFFO.customer.social) > 0">
                <ul ng-include="'social-li.html'"></ul>
            </div>
        </div>
    </div>
</div>
