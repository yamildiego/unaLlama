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
                    $scope.myArticles.forEach(function (element) {
                        element.show = ($scope.getDays(element.date_publication) == 0);
                    });

                    $scope.loading = false;
                }
            }, function onError(response) {
                if (response.data.status == "session_expired") {
                    $window.location.href = Constants.FRONTURL + '#!/login/mis-anuncios';
                }
            });
    }

    $scope.getDays = function (pDatePublication) {
        var date_publication = new Date(pDatePublication * 1000);
        var today = new Date();
        var days = 30 - (Math.round((today.getTime() - date_publication.getTime()) / 60 / 60 / 24 / 1000));

        return (days < 0 ? 0 : days);
    }

    $scope.remove = function (pArticle) {
        var userId = ($scope.$parent.userData && $scope.$parent.userData.id) ? $scope.$parent.userData.id : 0;

        var modal = Popeye.openModal({
            templateUrl: './Views/confirm-modal.html',
            controller: "confirmRemoveController",
            locals: { text: "¿Estas seguro que quieres eliminar el articulo '" + pArticle.title + "' ?", yes: "Confirmar", no: "Cancelar", extraOneId: pArticle.id, extraTwoId: userId }
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