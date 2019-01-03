app.controller('editArticleController', function ($scope, $http, Constants, $routeParams, $window, $rootScope, $timeout, Popeye, AuthService) {

    $scope.initialize = function () {
        $scope.loading = true;
        $scope.seconds = 5;
        $scope.dataArticle = { photos: [] };
        $scope.options = {
            url: '../Server/assets/php/'
        };

        $scope.loadArticle();

        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
            $scope.userData = response.data.data;
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
            if (response.data.status == "session_expired") {
                $window.location.href = Constants.FRONTURL + '#!/login/mis-anuncios';
            }
        });
    }

    $scope.loadArticle = function () {
        $http.post(Constants.APIURL + 'ArticleController/getArticle', { articleId: $routeParams.idArticle, isViewing: true })
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.categories = $scope.$parent.categories;
                    $scope.loading = false;
                    $scope.form = response.data.data;
                    $scope.form.category = $scope.form.categoryId;
                }
            }, function onError(response) {
                $window.location.href = Constants.FRONTURL + '#!/listado';
            });

    }

    $scope.openPhoto = function (photos, index) {
        var photo = photos[(index == undefined) ? 0 : index].original;

        var modal = Popeye.openModal({
            templateUrl: './Views/image-modal.html',
            controller: "photoModalController",
            locals: { photos: photos, index: index }
        });
    };

    $scope.back = function () {
        $window.history.back();
    }

    $scope.deletePhoto = function (file) {
        file.forRemove = true;
    }

    $scope.myFilter = function (item) {
        return (item.forRemove === undefined);
    };

    $scope.editArticle = function () {
        $scope.loading = true;

        var fm = angular.copy($scope.form);
        var pictures = preparatedPhotos(angular.copy($scope.dataArticle.photos));

        delete fm["photos"];
        fm.photos = pictures.concat($scope.form.photos);
        fm.userId = $scope.userData.id;

        $http.post(Constants.APIURL + 'ArticleController/editArticle', fm)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.loading = false;
                    $scope.successful = true;
                    $scope.end(response.data.data);
                }
            }, function onError(response) {
                $rootScope.$broadcast("disconnected", response.data.status);
                if (response.data.status == "session_expired") {
                    $window.location.href = Constants.FRONTURL + '#!/login/mis-anuncios';
                }
                $scope.loading = false;
                $scope.errors = $scope.$parent.getErrors(response.data);
            });
    }

    $scope.end = function (pId) {
        if ($scope.seconds == 0)
            $window.location.href = Constants.FRONTURL + '#!/ver-anuncio/' + pId;
        else {
            $scope.seconds = $scope.seconds - 1;
            $timeout(function () {
                $scope.end(pId);
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

    $scope.initialize();
});