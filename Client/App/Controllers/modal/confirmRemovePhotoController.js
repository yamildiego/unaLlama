app.controller('confirmRemovePhotoController', function ($scope, $http, Constants, $rootScope, yes, no, text, extraOneId) {
    $scope.initialize = function () {
        $scope.yes = yes;
        $scope.no = no;
        $scope.text = text;
    }

    $scope.ok = function () {
        $http.get(Constants.APIURL + 'UserController/removePhoto/' + extraOneId)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.$close(true);
                }
            }, function onError(response) {
                if (response.data.status == "session_expired") {
                    $rootScope.$broadcast("disconnected", response.data.status);
                }
                $scope.$close();

            });
    }

    $scope.initialize();
});
