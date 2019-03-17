app.service('CommonsService', function (Constants, $http) {
    return {
        getCategories: function () {
            return $http.get(Constants.APIURL + 'ArticleController/getCategories');
        },
        loadDepartments: function () {
            return $http.get(Constants.APIURL + 'ArticleController/getDepartments');
        }
    }
});