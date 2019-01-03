app.controller('resetPasswordController', function ($scope, $http, Constants, $routeParams) {
    $scope.loadForm = true;
    $scope.form = { userId: $routeParams.userId, code: $routeParams.code, password: "", repeatPassword: "" };
    $scope.errors = [];
    $scope.successful = false;
    $scope.expired = false;

    $scope.checkCode = function () {
        $http.post(Constants.APIURL + 'UserController/checkCode', $scope.form)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.loadForm = false;
                    $scope.expired = false;
                }
            }, function onError(response) {
                $scope.expired = true;
                $scope.loadForm = false;
            });
    }

    $scope.checkCode();

    $scope.resetPassword = function () {
        $scope.errors = [];

        if ($scope.form.repeatPassword == null || $scope.form.repeatPassword == "")
            $scope.errors = ["El campo repita la contraseña es obligatorio."];
        else if ($scope.form.password != $scope.form.repeatPassword) {
            $scope.errors = ["El campo contraseña y repita la contraseña deben ser iguales"];
        } else {
            $http.post(Constants.APIURL + 'UserController/resetPassword', $scope.form)
                .then(function onSuccess(response) {
                    if (response.data.status == 'OK') {
                        $scope.form = { password: "", repeatPassword: "" };
                        $scope.loadForm = false;
                        $scope.successful = true;
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
});