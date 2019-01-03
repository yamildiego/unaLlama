app.controller('profileController', function ($scope, AuthService, $window, $http, Constants, $routeParams, $rootScope, Popeye) {
    $scope.initialize = function () {
        $scope.loading = true;
        $scope.loadingArticles = true;

        $scope.getUser($routeParams.id);

        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
            $scope.userData = response.data.data;
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
            if (response.data.status == "session_expired") {
                // $window.location.href = Constants.FRONTURL + '#!/login/perfil/' + $routeParams.id;
            }
        });

        $scope.getArticles($routeParams.id);
    }

    $scope.getUser = function (id) {
        $http.get(Constants.APIURL + 'UserController/getUserProfileData/' + id)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.userProfileData = response.data.data;
                    $scope.loading = false;
                }
            }, function onError(response) {
                $window.location.href = Constants.FRONTURL + '#!/listado';
            });
    }

    $scope.getArticles = function (id) {
        $scope.loading = true;

        $http.get(Constants.APIURL + 'ArticleController/getUserArticles/' + id)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.myArticles = response.data.data;
                    $scope.loadingArticles = false;
                }
            }, function onError(response) {
                if (response.data.status == "session_expired") {
                    $window.location.href = Constants.FRONTURL + '#!/login/perfil/' + id;
                }
            });
    }

    $scope.removePhoto = function () {
        if ($scope.userData != null && $scope.userData.id == $scope.userProfileData.id) {
            $scope.loadingPhoto = true;
            var modal = Popeye.openModal({
                templateUrl: './Views/confirm-modal.html',
                controller: "confirmRemovePhotoController",
                locals: { text: "¿Estas seguro de que quieres eliminar tu foto de perfil de UnaLlama?", yes: "Eliminar", no: "Cancelar", extraOneId: $scope.userData.id }
            });

            modal.closed.then(function (value) {
                $scope.loadingPhoto = false;
                if (value === true) {
                    $scope.userProfileData.photo = "user.png";
                    if ($scope.$parent.userData)
                        $scope.$parent.userData.photo = "user.png";
                }
            });
        }
    }

    $scope.editPhoto = function () {
        if ($scope.userData != null && $scope.userData.id == $scope.userProfileData.id) {
            var modal = Popeye.openModal({
                templateUrl: './Views/edit-photo-modal.html',
                controller: "editPhotoController",
                locals: { userId: $scope.userData.id, photo: $scope.userData.photo }
            });

            modal.closed.then(function (value) {
                if (value != null && (typeof value == "string")) {
                    $scope.userProfileData.photo = value;
                    if ($scope.$parent.userData)
                        $scope.$parent.userData.photo = value;
                }
            });
        }
    }

    $scope.editPassword = function () {
        if ($scope.userData != null && $scope.userData.id == $scope.userProfileData.id) {
            var modal = Popeye.openModal({
                templateUrl: './Views/edit-password-modal.html',
                controller: "editPasswordController",
                locals: { userId: $scope.userData.id }
            });
        }
    }

    $scope.initialize();
});