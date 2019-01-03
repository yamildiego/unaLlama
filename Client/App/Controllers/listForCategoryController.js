app.controller('listForCategoryController', function ($scope, $window, $http, Constants, $routeParams) {

    $scope.loading = true;

    var category = (($routeParams.idCategory != undefined && Number.isInteger(Number.parseInt($routeParams.idCategory)) && $routeParams.idCategory <= 15 && $routeParams.idCategory > 0) ? $routeParams.idCategory : 0);
    var text = ($routeParams.textSearch != undefined) ? $routeParams.textSearch : "";

    var filter = { category: category, search: text, kilometersMax: "", kilometersMin: "", operation: "0", priceMax: "", priceMin: "", state: "0", yearMax: "", yearMin: "" };

    $http.post(Constants.APIURL + 'ArticleController/setFilter', filter)
        .then(function onSuccess(response) {
            if (response.data.status == 'OK') {
                $window.location.href = Constants.FRONTURL + '#!/listado';
            }
        }, function onError(response) { });
});