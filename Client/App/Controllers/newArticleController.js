app.controller('newArticleController', function ($scope, $http, Constants, AuthService, $window, $timeout, $rootScope) {
    $scope.loading = true;
    $scope.successful = false;
    $scope.seconds = 5;
    $scope.options = {
        url: '../Server/assets/php/'
    };
    $scope.loadingFiles = false;
    $scope.dataArticle = { photos: [] };


    $scope.destroy = function (name) {
        $http.get(Constants.APIURL + 'ArticleController/deletePhoto/' + name).then();
    }

    AuthService.checkAuthInside().then(function (response) {
        $rootScope.$broadcast("connected", response.data.status);
        $scope.userData = response.data.data;
        $scope.loading = false;
        $scope.categories = $scope.$parent.categories;
    }, function (response) {
        $rootScope.$broadcast("disconnected", response.data.status);
        if (response.data.status == "session_expired") {
            $window.location.href = Constants.FRONTURL + '#!/login/publicar-anuncio';
        }
    });

    $scope.anio = (new Date).getFullYear();
    $scope.form = { title: "", description: "", category: "0", price: "", year: $scope.anio, kilometers: 0, photos: [] };
    $scope.newArticle = function () {
        var pictures = angular.copy($scope.dataArticle.photos);
        $scope.form.photos = preparatedPhotos(pictures);

        if ($scope.form.photos.length > 5) {
            $scope.errors = $scope.$parent.getErrors({ "status": "max_photo_limit" });
        } else {
            $scope.loading = true;

            $http.post(Constants.APIURL + 'ArticleController/newArticle', $scope.form)
                .then(function onSuccess(response) {
                    if (response.data.status == 'OK') {
                        $scope.loading = false;
                        $scope.successful = true;
                        $scope.end();
                    }
                }, function onError(response) {
                    $scope.loading = false;
                    $scope.errors = $scope.$parent.getErrors(response.data);
                    $rootScope.$broadcast("disconnected", response.data.status);
                    if (response.data.status == "session_expired") {
                        $window.location.href = Constants.FRONTURL + '#!/login/publicar-anuncio';
                    }
                });
        }
    }

    $scope.end = function () {
        if ($scope.seconds == 0)
            $window.location.href = Constants.FRONTURL + '#!/listado-por-categoria/0';
        else {
            $scope.seconds = $scope.seconds - 1;
            $timeout(function () {
                $scope.end();
            }, 1000);
        }
    }

    function preparatedPhotos(photos) {
        photos.forEach(function (element, index) {
            if (typeof element["error"] !== 'undefined') {
                photos.splice(index, 1);
            } else {
                delete element["size"];
                delete element["type"];
                delete element["url"];
                delete element["thumbnailUrl"];
                delete element["deleteUrl"];
                delete element["deleteType"];
                delete element["$$hashKey"];
            }
        });

        return photos;
    }
});