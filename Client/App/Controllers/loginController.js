
app.controller('loginController', function ($scope, $http, Constants, $window, $routeParams, $timeout, $rootScope, FaceService) {

    $scope.initialize = function () {
        FaceService.initialize();
        $scope.loadForm = false;
        $scope.errors = [];
        $scope.form = { username: "", password: "" };
    }

    $scope.login = function () {
        $scope.loadForm = true;
        $http.post(Constants.APIURL + 'UserController/login', $scope.form)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $rootScope.$broadcast("connected", response.data.status);

                    $scope.loadForm = false;

                    var paramId = (($routeParams.hasOwnProperty('id')) && $routeParams.id != null) ? '/' + $routeParams.id : '';

                    if ($routeParams.tab)
                        $window.location.href = Constants.FRONTURL + '#!/' + $routeParams.tab + paramId;
                    else
                        $window.location.href = Constants.FRONTURL;
                }
            }, function onError(response) {
                if (response.data.status == "errors_exists")
                    $scope.errors = response.data.errors;
                else
                    $scope.errors = [$scope.$parent.getTextError(response.data.status)];

                if (response.data.status == "incorrect_data")
                    $scope.form.password = "";
                $scope.loadForm = false;
            });
    }

    $scope.loginFB = function () {
        $scope.loadForm = true;

        FaceService.login(function (responseFB) {
            $timeout(function () {
                $scope.loadForm = false;

                var paramId = (($routeParams.hasOwnProperty('id')) && $routeParams.id != null) ? '/' + $routeParams.id : '';

                if ($routeParams.tab)
                    $window.location.href = Constants.FRONTURL + '#!/' + $routeParams.tab + paramId;
                else
                    $window.location.href = Constants.FRONTURL;
            });
        }, function (responseFB) {
            $timeout(function () {
                if (responseFB.status === 'not_authorized')
                    $scope.errors = ["Oops! No tienes permiso para acceder con Facebook."];
                else
                    $scope.errors = ["Oops! No pudimos conectar con facebook."];
                $scope.loadForm = false;
            });
        });
    }

    $scope.initialize();
});

