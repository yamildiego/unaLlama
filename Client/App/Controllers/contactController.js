app.controller('contactController', function ($scope, $http, Constants, $rootScope, AuthService, $timeout, $window) {

    $scope.initialize = function () {
        $scope.errors = [];
        $scope.seconds = 5;
        $scope.form = { name: "", email: "", query: "" };

        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
            $scope.userData = response.data.data;
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
        });
    }

    $scope.send = function () {
        $scope.loadForm = true;

        $http.post(Constants.APIURL + 'Home/sendMsg', $scope.form)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.successful = true;
                    $scope.loadForm = false;
                    $scope.end();
                }
            }, function onError(response) {
                $scope.loadForm = false;
                if (response.data.status == "errors_exists")
                    $scope.errors = response.data.errors;
                else
                    $scope.errors = [$scope.$parent.getTextError(response.data.status)];
            });
    }

    $scope.end = function () {
        if ($scope.seconds == 0)
            $window.location.href = Constants.FRONTURL + '#!/';
        else {
            $scope.seconds = $scope.seconds - 1;
            $timeout(function () {
                $scope.end();
            }, 1000);
        }
    }

    $scope.initialize();
});
