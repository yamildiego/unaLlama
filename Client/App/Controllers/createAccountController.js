app.controller('createAccountController', function ($scope, $http, Constants, $timeout, $window) {

    $scope.initialize = function () {
        $scope.form = { name: "", username: "", email: "", password: "", repeatPassword: "" };
        $scope.errors = [];
        $scope.successful = false;
        $scope.seconds = 8;
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