app.controller('createAccountController', function ($scope, $http, Constants, $timeout, $window, FaceService, $routeParams) {

    $scope.initialize = function () {
        $scope.form = { name: "", username: "", email: "", password: "", repeatPassword: "" };
        $scope.errors = [];
        $scope.successful = false;
        $scope.seconds = 8;
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

    $scope.newUser = function () {
        $scope.errors = [];

        if ($scope.form.password != $scope.form.repeatPassword) {
            $scope.errors = ["El campo contraseña y repita la contraseña deben ser iguales"];
        } else {
            $scope.loadForm = true;
            $http.post(Constants.APIURL + 'UserController/newUser', $scope.form)
                .then(function onSuccess(response) {
                    if (response.data.status == 'OK') {
                        $scope.form = { name: "", username: "", email: "", password: "", repeatPassword: "" };
                        $scope.loadForm = false;
                        $scope.successful = true;
                        $scope.end();
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
    }

    $scope.end = function (pId) {
        if ($scope.seconds == 0)
            $window.location.href = Constants.FRONTURL;
        else {
            $scope.seconds = $scope.seconds - 1;
            $timeout(function () {
                $scope.end(pId);
            }, 1000);
        }
    }

    $scope.initialize();
});