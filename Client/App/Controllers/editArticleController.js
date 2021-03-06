app.controller('editArticleController', function ($scope, $http, Constants, $routeParams, $window, $rootScope, $timeout, Popeye, AuthService) {

    $scope.initialize = function () {
        $scope.loading = true;
        $scope.sizeMax = Constants.SIZEMAX;
        $scope.seconds = 5;
        $scope.dataArticle = { photos: [] };
        $scope.sent = false;
        $scope.loadImages = false;
        $scope.tp = "";

        $scope.loadDepartments();

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

    $scope.loadDepartments = function () {
        $http.get(Constants.APIURL + 'ArticleController/getDepartments')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.departments = response.data.data;
                }
            }, function onError(response) {
                $scope.departments = [];
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
                    $scope.form.department = $scope.form.departmentId;
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

    $scope.loadTp = function (loadTP) {
        if (loadTP)
            $timeout(function () {
                if ($scope.tp.length >= 3) {
                    $scope.tp = ""
                } else {
                    $scope.tp += "."
                }

                if ($scope.loadImages)
                    $scope.loadTp(true);
            }, 500)
    }

    $scope.editArticle = function (loadTP) {

        var errors = [];

        if ($scope.form.title == "") {
            errors.push("required_title");
        }

        if ($scope.form.title.length >= 100) {
            errors.push("long_title");
        }

        if ($scope.form.description == "") {
            errors.push("required_description");
        }

        if ($scope.form.category == "0" || $scope.form.category == null || $scope.form.category == 0) {
            errors.push("required_category");
        }

        if ($scope.form.operation == "" || $scope.form.operation == null) {
            errors.push("required_operation");
        }

        if ($scope.form.category != "0" && $scope.form.category != "1" && $scope.form.category != "10" && $scope.form.category != "14") {
            if ($scope.form.state == "") {
                errors.push("required_state");
            }
        }

        if ($scope.form.department == "0" || $scope.form.department == null || $scope.form.department == 0) {
            errors.push("required_department");
        }

        if (errors.length > 0) {
            $scope.errors = $scope.$parent.getErrors({ "status": "errors_exists", "errors": errors });
        } else {
            $scope.errors = [];
            $scope.loadImages = true;
            $scope.loadTp(loadTP);

            var isLoading = false;
            $scope.dataArticle.photos.forEach(function (file) {
                if ((file.$state instanceof Function) && (file.$state() == "pending")) {
                    isLoading = true;
                }

                if (file.error != null || file.size > Constants.SIZEMAX) {
                    $scope.loadImages = false;
                    var isAdded = false;

                    $scope.errors.forEach(function (currentValue, index, arr) {
                        if (currentValue == "Hubo un problema al cargar una de sus imágenes, remplace la imagen para publicar.") {
                            isAdded = true;
                        }
                    });

                    if (isAdded == false) {
                        $scope.errors.push("Hubo un problema al cargar una de sus imágenes, remplace la imagen para publicar.")
                    }
                }
            });

            if (!$scope.sent && $scope.loadImages)
                if (isLoading === false) {
                    $scope.sent = true;
                    $scope.confirmEditArticle();
                } else {
                    $timeout(function () { $scope.editArticle(false); }, 500);
                }
        }
    }

    $scope.confirmEditArticle = function () {
        $scope.loading = true;

        var fm = angular.copy($scope.form);
        var pictures = preparatedPhotos(angular.copy($scope.dataArticle.photos));

        if ($scope.form.photos.length > 5) {
            $scope.loadImages = false;
            $scope.sent = false;
            $scope.errors = $scope.$parent.getErrors({ "status": "max_photo_limit" });
        } else {
            $scope.loading = true;
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
                    } else {
                        $scope.loadImages = false;
                        $scope.sent = false;
                    }
                    $scope.loading = false;
                    $scope.errors = $scope.$parent.getErrors(response.data);
                });
        }
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