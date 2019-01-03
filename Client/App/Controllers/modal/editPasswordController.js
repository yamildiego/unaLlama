app.controller('editPasswordController', function ($scope, $http, Constants, $rootScope, AuthService, userId) {
    $scope.initialize = function () {
        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
        });
        $scope.loadForm = false;
        $scope.form = { password: "", newPassword: "", repeaNewPassword: "" };
        $scope.successful = false;
    }

    $scope.ok = function () {
        $scope.errors = [];
        if ($scope.form.repeatNewPassword == null || $scope.form.repeatNewPassword == "")
            $scope.errors = ["El campo repita la nueva contraseña es obligatorio."];
        else if ($scope.form.newPassword != $scope.form.repeatNewPassword) {
            $scope.errors = ["El campo nueva contraseña y repita la nueva contraseña deben ser iguales."];
        } else {
            $scope.loadForm = true;

            $http.post(Constants.APIURL + 'UserController/editPassword', { userId: userId, password: $scope.form.password, newPassword: $scope.form.newPassword })
                .then(function onSuccess(response) {
                    if (response.data.status == 'OK') {
                        $scope.successful = true;
                        $scope.loadForm = false;
                    }
                }, function onError(response) {
                    $scope.loadForm = false;
                    $scope.form = { password: "", newPassword: "", repeaNewPassword: "" };
                    if (response.data.status == "errors_exists")
                        $scope.errors = response.data.errors;
                    else {
                        if (response.data.status == "session_expired") {
                            $rootScope.$broadcast("disconnected", response.data.status);
                        }
                        $scope.$close();
                    }
                });
        }
    }

    $scope.initialize();
});
