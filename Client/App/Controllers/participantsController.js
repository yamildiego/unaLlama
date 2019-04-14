app.controller('participantsController', function ($scope, $http, Constants, $q) {

    $scope.initialize = function () {
        angular.element(document.querySelector('#navbarSupportedContent')).removeClass("show");

        $scope.loading = true;
        $scope.showList = false;
        $scope.participants = [];

        $scope.loadParticipants();
    }

    $scope.loadParticipants = function () {
        $http.get(Constants.APIURL + 'ParticipantController/getParticipants')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.participants = response.data.data;
                    $scope.loading = false;
                }
            }, function onError(response) {
            });
    }

    $scope.shared = function () {
        $scope.sharedFB().then(function (data) {
            $scope.showList = true;
        }, function (data) {
        });
    }

    $scope.sharedFB = function () {
        var deffered = $q.defer();
        $.ajaxSetup({ cache: true });
        $.getScript('//connect.facebook.net/en_US/sdk.js', function () {
            FB.init({
                appId: '373321473483138',
                version: 'v2.3' // or v2.0, v2.1, v2.0
            });
            FB.ui({
                method: 'share',
                quote: 'Concurso "UnaLlama" \n Entra en nuestro sitio web publica un aviso y participas por un Tv 32 Pulgadas \n No te lo pierdas es totalmente GRATIS! \n https://www.unallama.com.ar ',
                hashtag: '#dondejujuyvende',
                href: 'https://www.unallama.com.ar',
            }, function (response) {
                (response && !response.error_code) ? deffered.resolve() : deffered.reject();
            });
        });
        return deffered.promise;
    }

    $scope.initialize();
});