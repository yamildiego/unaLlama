app.directive('viewArticle', function (Popeye, $http, Constants) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            article: '=',
            user: '@'
        },
        link: function (scope) {
            scope.operations = { 1: "Compro", 2: "Vendo", 3: "Alquilo", 4: "Regalo", 5: "Busco", 6: "Busco" };
            scope.sessionExpired = false;

            scope.getOperation = function (key) {
                return scope.operations[key];
            }

            scope.getNext = function (article) {
                if (article.indexPhoto) {
                    if (article.indexPhoto >= (article.photos.length - 1))
                        article.indexPhoto = 0;
                    else
                        article.indexPhoto = article.indexPhoto + 1;
                } else {
                    article.indexPhoto = 1;
                }
            }

            scope.getBack = function (article) {
                if (article.indexPhoto) {
                    if (article.indexPhoto <= 0)
                        article.indexPhoto = article.photos.length - 1;
                    else
                        article.indexPhoto = article.indexPhoto - 1;
                } else {
                    article.indexPhoto = article.photos.length - 1;
                }
            }

            scope.getFormatedPriceDecimal = function (priceData) {
                var price = ''
                if (priceData < 1000000) {
                    price = Math.trunc((priceData - Math.trunc(priceData)) * 100)
                    price = (price <= 9) ? '0' + price : price + '';
                } else {
                    if (priceData == 1000000) {
                        price = 'Millon';
                    } else {
                        price = 'Millones';
                    }
                }
                return price;
            }

            scope.isValidPrice = function (price) {
                return (price != 0 && price != null && price != undefined);
            }

            scope.getFormatedPriceWhole = function (priceData) {
                if (priceData >= 1000000) {
                    if (priceData >= 1000000 && priceData < 2000000) {
                        price = Math.trunc((priceData / 1000000) * 100) / 100;
                    } else {
                        price = Math.trunc((priceData / 1000000) * 100) / 100;
                    }
                } else {
                    var price = Math.trunc(priceData);
                }

                return price;
            }

            scope.openPhoto = function (photos, index) {
                var photo = photos[(index == undefined) ? 0 : index].original;
                if (photo != './Content/images/default-image.png')
                    var modal = Popeye.openModal({
                        templateUrl: './Views/image-modal.html',
                        controller: "photoModalController",
                        locals: { photos: photos, index: index }
                    });
            }

            scope.addRemoveFb = function (article, operation) {
                article.loadingFavorite = true;

                $http.post(Constants.APIURL + 'ArticleController/addRemoveFb', { articleId: article.id, userId: Number.parseInt(scope.user), operation: operation })
                    .then(function onSuccess(response) {
                        if (response.data.status == 'OK') {
                            article.loadingFavorite = false;
                            if (response.data.data)
                                article.isFb = !article.isFb;
                        }
                    }, function onError(response) {
                        article.loadingFavorite = false;
                        if (response.data.status == "session_expired")
                            scope.sessionExpired = true;
                    });
            }
        },
        templateUrl: "./App/Directives/viewArticle/viewArticle.html"
    }
});