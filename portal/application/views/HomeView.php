<div class="row">
    <div class="col-lg-6 ">
        <div class='panel panel-default'>
            <div class="panel-heading">
                    <span class="text-info text-uppercase">
                        <i class='fa fa-bullhorn'></i><strong> Ban tổ chức: </strong>
                    </span>
            </div>
            <div class="panel-body" style="max-height: 450px;overflow-y: scroll;">
                <div ng-show="Notify.admin.length > 0">
                    <div ng-repeat="news in Notify.admin"  style="padding-bottom: 3px;">
                        <span class="text-primary">{{news.updated_at}}</span>:{{news.text}}
                    </div>
                </div>
                <p ng-if="Notify.admin.length == 0">Không có thông báo nào</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 ">
        <div class='panel panel-default'>
            <div class="panel-heading">
                    <span class="text-info text-uppercase">
                        <i class='fa fa-rss'></i><strong> Đội chơi</strong>
                    </span>
            </div>
            <div class="panel-body" style="max-height: 450px;overflow-y: scroll;">
                <div ng-show="Notify.user.length > 0">
                    <div ng-repeat="alert in Notify.user"  style="padding-bottom: 3px;">
                        <span class="text-primary">{{alert.updated_at}}</span>: {{alert.text}}
                    </div>
                </div>
                <p ng-if="Notify.user.length == 0">Không có thông báo
                    nào</p>
            </div>
        </div>
    </div>
</div>
