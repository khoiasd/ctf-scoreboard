/* global toastr, angular, TOKEN_NAME, TOKEN_VALUE */
toastr.options = {
    "closeButton": false,
    "debug": false,
    "preventDuplicates": true,
    "positionClass": "toast-bottom-left",
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};
var api_signin = 'api/sign-in';
var app = angular.module('Ctf-Portal',
    ['ui.router', 'ngSanitize', 'angular-loading-bar', 'ncy-angular-breadcrumb', 'timer']
);
app.config(function ($breadcrumbProvider) {
    $breadcrumbProvider.setOptions({
        prefixStateName: 'home',
        template: '<div class="breadcrumb"><i class="fa fa-home"></i> <li ng-repeat="step in steps | limitTo:(steps.length-1)"> <a href="{{step.ncyBreadcrumbLink}}">{{step.ncyBreadcrumbLabel}}</a> </li> <li ng-repeat="step in steps | limitTo:-1"> <span class="ng-cloak">{{step.ncyBreadcrumbLabel}}</span> </li></div>'
    });
});
app.run(['$rootScope', '$state', '$stateParams', function ($rootScope, $state, $stateParams) {
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
}]);
app.config(['$stateProvider', '$urlRouterProvider', 'cfpLoadingBarProvider',
    function ($stateProvider, $urlRouterProvider, cfpLoadingBarProvider) {
        $urlRouterProvider
            .when('/home', '/')
            .otherwise('/home');
        // State Configurations
        $stateProvider
            .state("home", {
                url: "/",
                templateUrl: "view/home",
                controller: 'HomeCtrl',
                ncyBreadcrumb: {
                    label: 'Trang chủ'
                }
            })
            .state({
                name: 'sign-in',
                url: "/sign-in",
                templateUrl: "view/sign-in",
                controller: 'SigninCtrl',
                ncyBreadcrumb: {
                    label: 'Đăng nhập'
                }
            })
            .state({
                name: 'user',
                url: "/user",
                templateUrl: "view/user",
                controller: 'UserCtrl',
                ncyBreadcrumb: {
                    label: 'Tài khoản'
                }
            })
            .state({
                name: 'challenge',
                url: "/challenge",
                templateUrl: "view/challenge",
                controller: 'ChallengeCtrl',
                ncyBreadcrumb: {
                    label: 'Thử thách'
                }
            })
            .state({
                name: 'scoreboard',
                url: "/scoreboard",
                templateUrl: "view/scoreboard",
                controller: 'ScoreBoardCtrl',
                ncyBreadcrumb: {
                    label: 'Bảng điểm'
                }
            });
        cfpLoadingBarProvider.includeSpinner = false;
    }
]);
app.controller('HeaderCtrl', function ($scope, $http, $window) {
    $scope.DoSignOut = function () {
        var data = {};
        data[TOKEN_NAME] = TOKEN_VALUE;
        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: 'api/sign-out',
            method: "POST",
            data: $.param(data)
        }).then(function (resp) {
            if (resp.data.error) {
                toastr.error(resp.data.message);
            }
            else {
                toastr.success(resp.data);
                $window.location.href = base_url();
            }
        });
    };
});

app.controller('HomeCtrl', function ($scope, $http) {
    var refresh_notify = function () {
        $http.get('api/notify')
            .then(function (resp) {
                $scope.Notify = resp.data;
            });
    };
    refresh_notify();
});

/* signin Controller*/
app.controller('SigninCtrl', function ($scope, $window, $http) {
    $scope.doSignin = function () {
        $scope.Data[TOKEN_NAME] = TOKEN_VALUE;
        $http({
            url: api_signin,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            method: "POST",
            data: $.param($scope.Data)
        }).then(function (resp) {
            if (resp.data.error) {
                $scope.Data.password = '';
                $scope.form.$setPristine();
                toastr.error(resp.data.message);
            }
            else {
                $window.location.href = base_url();
            }
        });
    };
});
/* update account */
app.controller('UserCtrl', function ($scope, $http) {
    $http.get('api/user-detail').then(function (resp) {
        $scope.User = resp.data;
    });
    $scope.doUpdate = function () {
        $scope.User[TOKEN_NAME] = TOKEN_VALUE;
        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: 'api/user-password',
            method: "POST",
            data: $.param($scope.User)
        }).then(function (resp) {
            if (resp.data.error) {
                toastr.error(resp.data.message);
            } else {
                toastr.success(resp.data);
            }
            $scope.User.password = '';
            $scope.User.repassword = '';
            $scope.User.newpassword = '';
            $scope.form.$setPristine();
        });
    };
});

app.controller('ChallengeCtrl', function ($scope, $http) {
    var refresh_challenges = function () {
        $http.get('api/challenge-list')
            .then(function (resp) {
                $scope.Categories = resp.data.categories;
                $scope.Playing = resp.data.playing;
                $scope.CountDown = resp.data.countdown;
                $scope.Score = resp.data.score;
            })
    };
    refresh_challenges();
    $scope.DoDetail = function (id) {
        $http.get('api/challenge-detail/' + id)
            .then(function (resp) {
                if (resp.data.error) {
                    toastr.error(resp.data.message);
                } else {
                    $scope.challenge = resp.data;
                    if ($scope.challenge.id) {
                        $('.modal').modal('show');
                    }
                }
            });
    };
    var refresh_notify = function () {
        $http.get('api/notify', {params: {limit: 7}})
            .then(function (resp) {
                $scope.Notify = resp.data;
            });
    };
    refresh_notify();
    $scope.DoSubmit = function (id) {
        $scope.Data[TOKEN_NAME] = TOKEN_VALUE;
        $scope.Data['id'] = id;
        $http({
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            url: 'api/challenge-submit',
            method: "POST",
            data: $.param($scope.Data)
        }).then(function (resp) {
            if (resp.data.error) {
                toastr.error(resp.data.message);
            } else {
                toastr.success(resp.data);
                $('.modal').modal('hide');
                refresh_challenges();
                // $scope.load_announcements();
            }
            $scope.Data.flag = '';
        });
    };
});

app.controller('ScoreBoardCtrl', function ($scope, $http, $interval) {
    $scope.location = 'VN';
    $scope.refresh_scoreboard = function (l) {
        $scope.location = l;
        $http.get('api/scoreboard', {params: {l: l}})
            .then(function (resp) {
                $scope.User = resp.data;
            });
    };
    var theInterval = $interval(function () {
        $scope.refresh_scoreboard($scope.location);
    }, 60000);
    $scope.$on('$destroy', function () {
        $interval.cancel(theInterval)
    });
    $scope.refresh_scoreboard($scope.location);
    $scope.is_space = function (c) {
        return c.name === undefined;
    }
});
