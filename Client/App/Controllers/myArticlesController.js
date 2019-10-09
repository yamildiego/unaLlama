app.controller('myArticlesController', function ($scope, AuthService, $window, $http, Constants, Popeye, AuthService) {

    $scope.initialize = function () {
        $scope.loading = true;
        $scope.getMyArticles();
    }

    AuthService.checkAuthInside().then(function (response) {
        $scope.userData = response.data.data;
        $scope.loading = false;
        $scope.categories = $scope.$parent.categories;
    }, function () {
        $window.location.href = Constants.FRONTURL + '#!/login/mis-anuncios';
    });

    $scope.getMyArticles = function () {
        $scope.loading = true;

        $http.get(Constants.APIURL + 'ArticleController/getMyArticles')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.myArticles = response.data.data;
                    $scope.loading = false;
                }
            }, function onError(response) {
                if (response.data.status == "session_expired") {
                    $window.location.href = Constants.FRONTURL + '#!/login/mis-anuncios';
                }
            });
    }

    $scope.active = function (pArticle) {
        var userId = ($scope.$parent.userData && $scope.$parent.userData.id) ? $scope.$parent.userData.id : 0;

        var modal = Popeye.openModal({
            templateUrl: './Views/confirm-modal.html',
            controller: "confirmRemoveController",
            locals: { text: "¿Estas seguro que quieres dar de alta el articulo '" + pArticle.title + "' ?", yes: "Confirmar", no: "Cancelar", extraOneId: pArticle.id, extraTwoId: userId }
        });

        modal.closed.then(function (value) {
            if (value === true)
                $scope.getMyArticles();
        });
    }

    $scope.inactive = function (pArticle) {
        var userId = ($scope.$parent.userData && $scope.$parent.userData.id) ? $scope.$parent.userData.id : 0;

        var modal = Popeye.openModal({
            templateUrl: './Views/confirm-modal.html',
            controller: "confirmRemoveController",
            locals: { text: "¿Estas seguro que quieres dar de baja el articulo '" + pArticle.title + "' ?", yes: "Confirmar", no: "Cancelar", extraOneId: pArticle.id, extraTwoId: userId }
        });

        modal.closed.then(function (value) {
            if (value === true)
                $scope.getMyArticles();
        });
    }

    $scope.republish = function (pArticle) {
        var userId = ($scope.$parent.userData && $scope.$parent.userData.id) ? $scope.$parent.userData.id : 0;

        var modal = Popeye.openModal({
            templateUrl: './Views/confirm-modal.html',
            controller: "confirmRepublishController",
            locals: { text: "¿Estas seguro que quieres volver a publicar el articulo '" + pArticle.title + "' ?", yes: "Confirmar", no: "Cancelar", extraOneId: pArticle.id, extraTwoId: userId }
        });

        modal.closed.then(function (value) {
            if (value === true)
                $scope.getMyArticles();
        });
    }

    $scope.initialize();
});