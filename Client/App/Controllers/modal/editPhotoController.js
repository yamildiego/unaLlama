app.controller('editPhotoController', function ($scope, $http, Constants, $rootScope, AuthService, userId, photo) {
    $scope.initialize = function () {
        $scope.photo = photo;
        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
        });
        $scope.loadForm = false;
        // $scope.form = { password: "", newPassword: "", repeaNewPassword: "" };
        $scope.successful = false;
        $scope.dataArticle = { photos: [] };

        $scope.options = {
            url: '../Server/assets/php/'
        };
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
