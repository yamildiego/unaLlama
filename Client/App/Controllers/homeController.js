app.controller('homeController', function ($scope, AuthService, $http, Constants, $rootScope) {

    $scope.initialize = function () {
        $scope.getCategories();
        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
            $scope.userData = response.data.data;
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
        });
        $scope.loadMostVisited();
        $scope.loadMostPopular();
        $scope.loadMostRecent();
    }

    $scope.loadMostVisited = function () {
        $scope.mostVisited = [];
        $scope.loadVisited = true;

        $http.get(Constants.APIURL + 'ArticleController/getArticlesMostVisited')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.loadVisited = false;
                    $scope.mostVisited = response.data.data;
                }
            }, function onError(response) { });
    }

    $scope.loadMostPopular = function () {
        $scope.mostPopular = [];
        $scope.loadPopular = true;

        $http.get(Constants.APIURL + 'ArticleController/getArticlesMostPopular')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.loadPopular = false;
                    $scope.mostPopular = response.data.data;
                }
            }, function onError(response) { });
    }

    $scope.loadMostRecent = function () {
        $scope.mostRecent = [];
        $scope.loadRecent = true;

        $http.get(Constants.APIURL + 'ArticleController/getArticlesMostRecent')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.loadRecent = false;
                    $scope.mostRecent = response.data.data;
                }
            }, function onError(response) { });
    }

    $scope.getCategories = function () {
        $http.get(Constants.APIURL + 'ArticleController/getCategories')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.categoriess = response.data.data;
                }
            }, function onError(response) {

            });
    }

    $scope.initialize();
});