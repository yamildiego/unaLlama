app.directive('longText', function (Popeye) {
    return {
        scope: {
            data: '='
        },

        link: function (scope) {

            scope.dataShow = "";
            scope.link = false;
            scope.focusMe = false;

            if (scope.data.length >= 180) {
                scope.link = true;
                scope.dataShow = scope.data.substring(0, 180);
            } else {
                scope.dataShow = scope.data;
            }

            scope.openModal = function () {
                var modal = Popeye.openModal({
                    template: '<p>{{text}}</p>',
                    controller: function ($scope, text) {
                        $scope.text = text;
                    },
                    locals: { text: scope.data }
                });
            }

        },
        templateUrl: "./App/Directives/longText/long-text.html"
    }
});