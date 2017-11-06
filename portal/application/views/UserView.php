<div>
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <h4>Tài khoản</h4>
            <form role="form" ng-submit="doUpdate()" name="form">
                <div class="input-group form-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" ng-model="User.username" disabled=""/>
                </div>
                <div class="input-group form-group"
                     ng-class="{'has-error': form.password.$invalid && !form.password.$pristine }">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" ng-model="User.password"
                           placeholder="Mật khẩu" autocomplete="off" required ng-minlength="6" name="password">
                </div>
                <p ng-show="form.password.$invalid && !form.password.$pristine" class="text-danger">Mật khẩu ít
                    nhất 6 ký tự..</p>

                <div class="input-group form-group"
                     ng-class="{'has-error': form.newpassword.$invalid && !form.repassword.$pristine }">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" ng-model="User.newpassword" name="newpassword"
                           placeholder="Mật khẩu mới" autocomplete="off" ng-minlength="6" required>
                </div>
                <p ng-show="form.newpassword.$invalid && !form.newpassword.$pristine" class="text-danger">Mật
                    khẩu ít nhất 6 ký tự.</p>

                <div class="input-group form-group"
                     ng-class="{'has-error': form.repassword.$invalid && !form.repassword.$pristine }">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control" name="repassword" ng-model="User.repassword"
                           placeholder="Nhập lại mật khẩu" autocomplete="off" ng-minlength="6" required>
                </div>
                <p ng-show="form.repassword.$invalid && !form.repassword.$pristine" class="text-danger">Mật khẩu
                    ít nhất 6 ký tự.</p>
                <div class="input-group form-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" ng-model="User.organization" disabled=""/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" ng-disabled="form.$invalid">Cập nhật</button>
                </div>
            </form>
        </div>
</div>

