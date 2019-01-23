app.controller('editPhotoController', function ($scope, $http, Constants, $rootScope, AuthService, userId, $timeout, photo) {
    $scope.qq = { "data": [] };

    $scope.initialize = function () {
        $scope.photo = photo;
        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
        });
        $scope.loadForm = false;
        $scope.successful = false;
        $scope.dataArticle = { photos: [] };
        $scope.options = {
            url: '../Server/assets/php/'
        };

        $scope.neverSend = 0;
    }

    $scope.updateNeverSend = function () {
        $timeout(function () {
            $scope.neverSend = 1;
        }, 2000);
    }

    $scope.iEjecuted = false;
    $scope.isLoading = function () {
        var status = false;
        $scope.qq.data.forEach(element => {
            if ((element.$state instanceof Function) && (element.$state() == "pending")) {
                $scope.iEjecuted = true;
                status = true;
            }

            if ($scope.iEjecuted && (element.error != undefined)) {
                $scope.iEjecuted = false;
            }
        });

        if (!status && $scope.iEjecuted == 1 && $scope.neverSend == 1) {
            $timeout(function () {
                $scope.neverSend = 2;
            }, 1500);
        }
        return status;
    }

    $scope.ok = function () {
        $scope.loadForm = true;
        $scope.errors = [];

        var photo = null;

        if ($scope.dataArticle.photos && $scope.dataArticle.photos[0] !== 'undefined') {

            photo = $scope.dataArticle.photos[0].name;

            $http.post(Constants.APIURL + 'UserController/editPhoto', { userId: userId, photo: photo })
                .then(function onSuccess(response) {
                    if (response.data.status == 'OK') {
                        $scope.$close(photo);
                    }
                }, function onError(response) {
                    $scope.loadForm = false;
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
