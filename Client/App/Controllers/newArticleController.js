app.controller('newArticleController', function ($scope, $http, Constants, AuthService, $window, $timeout, $rootScope, Popeye) {
    $scope.initialize = function () {
        $scope.loading = true;
        $scope.sizeMax = Constants.SIZEMAX;
        $scope.successful = false;
        $scope.seconds = 5;
        $scope.options = { url: '../Server/assets/php/' };
        $scope.loadingFiles = false;
        $scope.dataArticle = { photos: [] };
        $scope.loadImages = false;
        $scope.tp = "";
        $scope.sent = false;
        $scope.loadDepartments();

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
        $scope.form = { title: "", description: "", department: "0", category: "0", price: "", year: $scope.anio, state: "", operation: "", kilometers: 0, photos: [] };
    }

    $scope.destroy = function (name) {
        $http.get(Constants.APIURL + 'ArticleController/deletePhoto/' + name).then();
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

    $scope.newArticle = function (loadTP) {
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
                    $scope.confirmNewArticle();
                } else {
                    $timeout(function () { $scope.newArticle(false); }, 500);
                }
        }
    }

    $scope.confirmNewArticle = function () {
        var pictures = angular.copy($scope.dataArticle.photos);
        $scope.form.photos = preparatedPhotos(pictures);

        if ($scope.form.photos.length > 5) {
            $scope.loadImages = false;
            $scope.sent = false;
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
                    } else {
                        $scope.loadImages = false;
                        $scope.sent = false;
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

    $scope.initialize();
});