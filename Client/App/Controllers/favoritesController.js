app.controller('favoritesController', function ($scope, AuthService, $http, Constants, $window, $rootScope) {

    $scope.initialize = function () {
        $scope.loading = true;
        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
            $scope.userData = response.data.data;
            $scope.getArticles();
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
            if (response.data.status == "session_expired") {
                $window.location.href = Constants.FRONTURL + '#!/login/favoritos';
            }
        });
    }

    $scope.getArticles = function () {
        $http.post(Constants.APIURL + 'ArticleController/getMyFavoritesArticles', { userId: $scope.userData.id })
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.articles = response.data.data;
                    $scope.loading = false;
                }
            }, function onError(response) {

            });
    }

    $scope.initialize();
});