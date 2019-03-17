app.controller('listController', function ($scope, AuthService, $rootScope) {

    $scope.initialize = function () {
        angular.element(document.querySelector('#navbarSupportedContent')).removeClass("show");

        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
            scope.userData = response.data.data;
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
        });
    }
});