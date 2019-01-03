app.controller('forgotMyPasswordController', function ($scope, $http, Constants) {
    $scope.form = { email: "" };
    $scope.errors = [];
    $scope.successful = false;

    $scope.forgotMyPassword = function () {
        $scope.errors = [];
        $scope.loadForm = true;

        $http.post(Constants.APIURL + 'UserController/forgotMyPassword', $scope.form)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.form = { email: "" };
                    $scope.loadForm = false;
                    $scope.successful = true;
                }
            }, function onError(response) {
                if (response.data.status == "errors_exists")
                    $scope.errors = response.data.errors;
                else
                    $scope.errors = [$scope.$parent.getTextError(response.data.status)];

                if (response.data.status == "incorrect_data")
                    $scope.form.password = "";
                $scope.loadForm = false;
            });
    }
});