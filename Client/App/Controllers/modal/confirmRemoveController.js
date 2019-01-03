app.controller('confirmRemoveController', function ($scope, $http, Constants, yes, no, text, extraOneId, extraTwoId) {
    $scope.yes = yes;
    $scope.no = no;
    $scope.text = text;

    $scope.ok = function () {
        $http.post(Constants.APIURL + 'ArticleController/removeArticle', { articleId: extraOneId, userId: extraTwoId })
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.$close(true);
                }
            }, function onError(response) {
                if (response.data.status == "session_expired") {
                    $rootScope.$broadcast("disconnected", response.data.status);
                }
                $scope.$close();
            });
    }
});