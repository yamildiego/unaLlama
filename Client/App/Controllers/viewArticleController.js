app.controller('viewArticleController', function ($scope, $http, Constants, $routeParams, $rootScope, AuthService, $window, Popeye, moment) {

    $scope.initialize = function () {
        moment.locale('es');
        $scope.loadDepartments();

        $scope.loading = true;
        $scope.loadArticle();
        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
            $scope.userData = response.data.data;
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
        });
    }

    $scope.postFB = function () {
        $http.post(Constants.APIURL + 'FBController/index', { articleId: $routeParams.idArticle })
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    //AVISARLE AL USUARIO QUE SE PUBLICO CON EXITO EN FB
                }
            }, function onError(response) {
                console.error(response);
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
                    $scope.article = response.data.data;
                    $scope.categories = $scope.$parent.categories;
                    $scope.loading = false;

                    if ($routeParams.fb === 'postFB')
                        $scope.postFB();
                }
            }, function onError(response) {
                $window.location.href = Constants.FRONTURL;
            });

    }

    $scope.addRemoveFb = function (article, operation) {
        article.loadingFavorite = true;

        if ($scope.userData == null) {
            $scope.sessionExpired = true;
            article.loadingFavorite = false;
        } else {
            $http.post(Constants.APIURL + 'ArticleController/addRemoveFb', { articleId: article.id, userId: Number.parseInt($scope.userData.id), operation: operation })
                .then(function onSuccess(response) {
                    if (response.data.status == 'OK') {
                        article.loadingFavorite = false;
                        if (response.data.data)
                            article.isFb = !article.isFb;
                    }
                }, function onError(response) {
                    article.loadingFavorite = false;
                    if (response.data.status == "session_expired")
                        $scope.sessionExpired = true;
                });
        }
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

    $scope.initialize();
});