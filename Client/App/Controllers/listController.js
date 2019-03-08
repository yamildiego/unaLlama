app.controller('listController', function ($scope, $http, Constants, AuthService, $rootScope) {

    $scope.initialize = function () {
        angular.element(document.querySelector('#navbarSupportedContent')).removeClass("show");

        $scope.$parent.search = { category: { id: 0, name: "Categoría" }, text: "" };
        $scope.loading = true;
        $scope.loadBtnData = false;
        $scope.loadingFilter = true;
        $scope.loadingArticles = true;
        $scope.categoryTitle = 0;
        $scope.clean();
        $scope.loadDepartments();

        $scope.getFilter();
        AuthService.checkAuthInside().then(function (response) {
            $rootScope.$broadcast("connected", response.data.status);
            $scope.userData = response.data.data;
        }, function (response) {
            $rootScope.$broadcast("disconnected", response.data.status);
        });
    }

    $scope.loadDepartments = function () {
        $http.get(Constants.APIURL + 'ArticleController/getDepartments')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.departments = response.data.data;
                }
            }, function onError(response) {
                $scope.departments = [];
            });
    }

    $scope.getFilter = function () {
        $http.get(Constants.APIURL + 'ArticleController/getFilter')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.filter = response.data.data;
                    $scope.loadingFilter = false;
                    $scope.loading = false;
                    $scope.loadData();
                }
            }, function onError(response) { });
    }

    $scope.resetFilter = function () {
        $scope.filter = { department: "0", category: "0", kilometersMax: "", kilometersMin: "", operation: "0", priceMax: "", priceMin: "", search: "", state: "0", yearMax: "", yearMin: "" };
        $scope.loadingArticles = true;
        $http.post(Constants.APIURL + 'ArticleController/setFilter', $scope.filter)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.clean();
                    $scope.loadData();
                }
            }, function onError(response) { });
    };

    $scope.btnFilter = function () {
        $scope.loadingArticles = true;
        $http.post(Constants.APIURL + 'ArticleController/setFilter', $scope.filter)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.clean();
                    $scope.loadData();
                }
            }, function onError(response) { });
    }

    $scope.loadData = function () {
        $scope.loadBtnData = true;
        $scope.quantity = 10;
        $scope.page++;
        $http.get(Constants.APIURL + 'ArticleController/getArticles/' + $scope.quantity + '/' + $scope.page)
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    if (response.data.data.length < $scope.quantity) {
                        $scope.hideBtn = true;
                    }

                    $scope.articles = $scope.articles.concat(response.data.data);
                    $scope.loading = false;
                    $scope.loadBtnData = false;
                    $scope.loadingArticles = false;
                    $scope.categoryTitle = $scope.filter.category;
                    $scope.categories = $scope.$parent.categories;
                }
            }, function onError(response) { });
    }

    $scope.clean = function () {
        $scope.hideBtn = false;
        $scope.articles = [];
        $scope.page = 0;
    }

    $scope.initialize();
});