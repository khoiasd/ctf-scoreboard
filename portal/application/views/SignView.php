<div style="margin-top:30px;" class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">Đăng nhập</div>
            <div style="float:right; font-size: 80%; position: relative; top:-10px"><a>Quên mật khẩu</a>
                &nbsp;&nbsp;<a>Đăng ký</a>&nbsp;
            </div>
        </div>
        <div class="panel-body">
            <form role="form" ng-submit="doSignin()" name="form">
                <div class="input-group form-group"
                     ng-class="{ 'has-error' : form.username.$invalid && !form.username.$pristine }">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" name="username" placeholder="Tài khoản"
                           ng-model="Data.username" autocomplete="off" required ng-minlength="2" ng-maxlength="30">
                </div>
                <p ng-show="form.username.$error.minlength || form.username.$error.maxlength" class="text-danger">Tài
                    khoản từ 2 đến 30 ký tự.</p>

                <div class="input-group form-group"
                     ng-class="{ 'has-error' : form.password.$invalid && !form.password.$pristine }">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" ng-model="Data.password"
                           placeholder="Mật khẩu" autocomplete="off" required ng-minlength="6">
                </div>
                <p ng-show="form.password.$invalid && !form.password.$pristine" class="text-danger">Mật khẩu ít nhất 6
                    ký tự..</p>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" ng-disabled="form.$invalid">Đăng nhập</button>
                </div>
            </form>
        </div>
    </div>
</div>
