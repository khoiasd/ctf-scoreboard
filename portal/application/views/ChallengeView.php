<div class="row">
    <div class="col-lg-7">
        <div ng-repeat="category in Categories">
            <div class='panel panel-default' ng-show="category.challenges.length !== 0">
                <div class='panel-heading text-uppercase'><strong>{{category.name}}</strong></div>
                <div class='panel-body'>
                    <span ng-repeat="challenge in category.challenges">
                       <button class="btn {{challenge.solved == 1 ? 'btn-danger':'btn-primary'}}"
                               ng-disabled="challenge.active == 0 || Playing == false || Playing == null"
                               title="{{challenge.name}} | Trả lời: {{challenge.count_solved}} đội"
                               ng-click="DoDetail(challenge.id)">{{challenge.score}}
                       </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel panel-default">
            <div class="panel-heading">
                        <span class="text-info text-uppercase">
                            <i class='fa fa-clock-o'></i><strong> Thời gian</strong>
                        </span>
                <span class="pull-right text-danger"><strong>Điểm: {{Score}}</strong></span>
            </div>
            <div class="panel-body">
                <div ng-if="Playing === null">
                    <h3 class="text-center">
                        <timer start-time="CountDown">{{days}} days {{hours}}:{{minutes}}:{{seconds}}</timer>
                    </h3>
                    <h3 class="text-center text-danger">Cuộc thi đã kết thúc</h3>
                </div>
                <div ng-if="Playing === true">
                    <h3 class="text-center">
                        <timer end-time="CountDown">{{days}} days {{hours}}:{{minutes}}:{{seconds}}</timer>
                    </h3>
                    <h4 class="text-center text-danger">Đang diễn ra</h4>
                </div>
                <div ng-if="Playing === false">
                    <h3 class="text-center">
                        <timer end-time="CountDown">{{days}} days {{hours}}:{{minutes}}:{{seconds}}</timer>
                    </h3>
                    <h4 class="text-center text-danger">Chưa bắt đầu</h4>
                </div>
            </div>
        </div>
        <!-- thông báo-->
        <div class='panel panel-default' ng-if="Notify.admin || Notify.user">
            <div class="panel-heading">
                <span class="text-info text-uppercase">
                    <i class='fa fa-bullhorn'></i><strong> Thông báo</strong>
                </span>
                <span class="pull-right"><a ui-sref="home">Xem thêm</a></span>
            </div>
            <div class='panel-body'>
                <div ng-show="Notify.admin.length > 0">
                    <strong class="text-danger">Ban tổ chức:</strong>
                    <div ng-repeat="news in Notify.admin"  style="padding-bottom: 3px;">
                        <span class="text-primary">{{news.updated_at}}</span>:{{news.text}}
                    </div>
                </div>
                <div ng-show="Notify.user.length > 0">
                    <hr/>
                    <strong class="text-danger">Đội chơi:</strong>
                    <div ng-repeat="alert in Notify.user"  style="padding-bottom: 3px;">
                        <span class="text-primary">{{alert.updated_at}}</span>: {{alert.text}}
                    </div>
                </div>
                <p ng-if="initShow || (Announcements.news.length == 0 && Announcements.alerts.length == 0)">Không có
                    thông báo nào</p>
            </div>
        </div>
    </div>
</div>
</div>

<!--Detail modal-->
<div class=" modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{challenge.name}} <span class="badge badge-danger">{{challenge.score}}</span>
                </h4>
                <small class="text-primary" ng-if="challenge.count_solved > 0">
                    Hoàn thành: {{challenge.count_solved}}
                </small>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" ng-show="challenge.solved">
                    Bạn đã hoàn thành thử thách này
                </div>
                <span class="label label-primary">Đề bài:</span>
                <p ng-bind-html="challenge.description" style="margin-top: 10px; word-wrap: break-word;"></p>
                <div ng-if="challenge.hints.length > 0">
                    <span class="label label-primary">Gợi ý:</span>
                    <div ng-repeat="hint in challenge.hints" style="margin-top: 10px; word-wrap: break-word;">
                        {{$index+ 1}}.{{hint.text}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form ng-submit="DoSubmit(challenge.id)" name="form" novalidate>
                    <div class="input-group form-group">
                        <input type="text" class="form-control" name="flag"
                               ng-model="Data.flag"
                               placeholder="Điền flag" autocomplete="off" required
                               ng-disabled="challenge.solved"
                               maxlength="100">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary"
                                    ng-disabled="form.$invalid">Gửi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
