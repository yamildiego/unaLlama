app.directive('carrusel', function (Popeye, $http, Constants) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            articles: '=',
            title: '@',
            type: '@',
            load: '='
        },
        link: function (scope) {
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
        },
        templateUrl: "./App/Directives/carrusel/carrusel.html"
    }
});