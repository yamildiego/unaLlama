app.directive('articles', function ($http, Constants, CommonsService) {
    return {
        restrict: 'E',
        replace: true,
        link: function (scope) {
            scope.initialize = function () {
                scope.loadBtnData = false;
                scope.loadingFilter = true;
                scope.loadingArticles = true;
                scope.categoryTitle = 0;
                scope.clean();
                CommonsService.loadDepartments().then(function onSuccess(response) {
                    if (response.data.status == 'OK') {
                        scope.departments = response.data.data;
                    } else {
                        scope.departments = [];
                    }
                }, function onError(response) {
                    scope.departments = [];
                });
                scope.getFilter();
            }

            scope.getFilter = function () {
                $http.get(Constants.APIURL + 'ArticleController/getFilter')
                    .then(function onSuccess(response) {
                        if (response.data.status == 'OK') {
                            scope.filter = response.data.data;
                            scope.loadingFilter = false;
                            scope.loadData();
                        }
                    }, function onError(response) { });
            }

            scope.resetFilter = function () {
                scope.filter = { department: "0", category: "0", kilometersMax: "", kilometersMin: "", operation: "0", priceMax: "", priceMin: "", search: "", state: "0", yearMax: "", yearMin: "" };
                scope.loadingArticles = true;
                $http.post(Constants.APIURL + 'ArticleController/setFilter', scope.filter)
                    .then(function onSuccess(response) {
                        if (response.data.status == 'OK') {
                            scope.clean();
                            scope.loadData();
                        }
                    }, function onError(response) { });
            };

            scope.btnFilter = function () {
                scope.loadingArticles = true;
                $http.post(Constants.APIURL + 'ArticleController/setFilter', scope.filter)
                    .then(function onSuccess(response) {
                        if (response.data.status == 'OK') {
                            scope.clean();
                            scope.loadData();
                        }
                    }, function onError(response) { });
            }

            scope.loadData = function () {
                scope.loadBtnData = true;
                scope.quantity = 10;
                scope.page++;
                $http.get(Constants.APIURL + 'ArticleController/getArticles/' + scope.quantity + '/' + scope.page)
                    .then(function onSuccess(response) {
                        if (response.data.status == 'OK') {
                            if (response.data.data.length < scope.quantity) {
                                scope.hideBtn = true;
                            }

                            scope.articles = scope.articles.concat(response.data.data);
                            scope.loadBtnData = false;
                            scope.loadingArticles = false;
                            scope.categoryTitle = scope.filter.category;
                            CommonsService.getCategories().then(function (response) {
                                scope.categories = response.data.data;
                            });
                        }
                    }, function onError(response) { });
            }

            scope.clean = function () {
                scope.hideBtn = false;
                scope.articles = [];
                scope.page = 0;
            }

            scope.initialize();

        },
        templateUrl: "./App/Directives/articles/articles.html"
    }
});