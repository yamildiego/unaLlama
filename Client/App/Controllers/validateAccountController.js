app.controller('validateAccountController', function ($scope, $http, Constants, $routeParams, $window, $timeout) {
    $scope.initialize = function () {
        $scope.loading = true;
        $scope.seconds = 5;
        $scope.successful = false;
        $scope.validateAccount();
    }

    $scope.validateAccount = function () {
        $scope.form = { userId: $routeParams.userId, code: $routeParams.code };

        $http.post(Constants.APIURL + 'UserController/validateAccount', $scope.form)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.successful = true;
                    $scope.loading = false;
                    $scope.end();
                }
            }, function onError(response) {
                $scope.loading = false;
            });
    }

    $scope.end = function () {
        if ($scope.seconds == 0)
            $window.location.href = Constants.FRONTURL + '#!/login';
        else {
            $scope.seconds = $scope.seconds - 1;
            $timeout(function () {
                $scope.end();
            }, 1000);
        }
    }

    $scope.initialize();
});
