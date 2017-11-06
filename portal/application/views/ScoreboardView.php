<a href="javascript:;" ng-click="refresh_scoreboard('VN')">#VN</a>&nbsp;&nbsp;
<a href="javascript:;" ng-click="refresh_scoreboard('HN')">#Bắc</a>&nbsp;&nbsp;
<a href="javascript:;" ng-click="refresh_scoreboard('DN')">#Trung</a>&nbsp;&nbsp;
<a href="javascript:;" ng-click="refresh_scoreboard('HCM')">#Nam</a>
<table class="table table-responsive table-striped">
    <thead>
    <th>#</th>
    <th>Đội chơi</th>
    <th>Điểm</th>
    <th>Thử thách</th>
    <th>Trường</th>
    <th>Submit</th>
    </thead>
    <tbody>
    <tr ng-repeat="u in User">
        <td>{{$index + 1}}</td>
        <td>{{u.username}}</td>
        <td>{{u.score}}</td>
        <td>
            <span ng-repeat="c in u.challenge"><a class="btn btn-sm"
                                                  ng-class="c.solved == true?'btn-danger': 'btn-default'"
                                                  ng-show="!is_space(c)" title="{{c.name}}"></a><span
                        ng-show="is_space(c)">&nbsp;</span></span>
        </td>
        <td>{{u.organization}}</td>
        <td>{{u.last_submit}}</td>
    </tr>
    </tbody>
</table>