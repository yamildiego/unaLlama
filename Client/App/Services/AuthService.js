app.service('AuthService', function ($rootScope, Constants, $http, $q) {
    return {
        checkAuthInside: function () {
            var deferred = $q.defer();

            $http.get(Constants.APIURL + 'UserController/getLoginStatus')
                .then(function onSuccess(response) {
                    if (response.data.status == 'OK') {
                        $rootScope.userData = response.data.data;
                        deferred.resolve(response);
                    }
                }, function onError(response) {
                    deferred.reject(response);
                });

            return deferred.promise;
        }
    }
});