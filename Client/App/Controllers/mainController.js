app.controller('mainController', function ($scope, $http, AuthService, Constants, $location, $window, $route, $rootScope, FaceService) {
    $scope.load = true;
    FaceService.initialize();

    $rootScope.$on("disconnected", function (evt, data) {
        if (data === "session_expired") {
            $scope.userData = null;
        }
    });

    $rootScope.$on("connected", function (evt, data) {
        $scope.checkAuth();
    });

    $scope.getEncode = function (text) {
        if (text != undefined)
            return encodeURIComponent(text.replace(/\//g, ""));
        else
            return "";
    }

    $scope.preload = { "display": "block" };

    $scope.initialize = function () {
        $scope.$route = $route;

        $scope.userData = null;

        $scope.search = { category: { id: 0, name: "Categoría" }, text: "" };

        $scope.getCategories();
        $scope.checkAuth();
    }

    $scope.checkAuth = function () {
        AuthService.checkAuthInside().then(function (response) {
            $scope.userData = response.data.data;
            $scope.load = false;
        }, function (response) {
            $scope.load = false;
            $rootScope.$broadcast("disconnected", response.data.status);
        });
    }

    $scope.logout = function () {
        $http.get(Constants.APIURL + 'UserController/logout')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.load = true;
                    $scope.userData = null;
                    $window.location.href = Constants.FRONTURL;
                }
            }, function onError(response) {

            });
    }

    $scope.getCategories = function () {
        $http.get(Constants.APIURL + 'ArticleController/getCategories')
            .then(function onSuccess(response) {
                if (response.data.status == 'OK') {
                    $scope.categories = response.data.data;
                }
            }, function onError(response) {

            });
    }

    $scope.initialize();

    $scope.getTextError = function (key_error) {
        var msg = "";
        switch (key_error) {
            case 'max_photo_limit':
                msg = "El limite máximo de fotos por anuncio es cinco.";
                break;
            case 'required_title':
                msg = "El campo título es obligatorio.";
                break;
            case 'required_description':
                msg = "El campo descripción es obligatorio.";
                break;
            case 'required_category':
                msg = "El campo categoria es obligatorio.";
                break;
            case 'required_state':
                msg = "El campo estado es obligatorio.";
                break;
            case 'required_category_job':
                msg = "El campo yo estoy es obligatorio.";
                break;
            case 'required_operation':
                msg = "El campo operación es obligatorio.";
                break;
            case 'incorrect_data':
                msg = "Los datos ingresados son incorrectos.";
                break;
            case 'no_active':
                msg = "El usuario no se encuentra activo, ingrese a su correo para activar la cuenta.";
                break;
            case 'expired_request':
                msg = "La solicitud a caducado.";
                break;
            default:
                msg = "Ha ocurrido un error, contacte al administrador.";
                break;
        }
        return msg;
    }

    $scope.getErrors = function (data) {
        var result = [];
        if (data.status == "errors_exists") {
            data.errors.forEach(element => {
                result.push($scope.getTextError(element));
            });
        } else
            result = [$scope.getTextError(data.status)]
        return result;
    }

    $scope.btnSearch = function () {
        if ($scope.search.text != null & $scope.search.text != "")
            $window.location.href = Constants.FRONTURL + '#!/listado-por-categoria-y-texto/' + $scope.search.category.id + '/' + $scope.getEncode($scope.search.text);
        else
            $window.location.href = Constants.FRONTURL + '#!/listado-por-categoria/' + $scope.search.category.id;
    }

    $scope.goTo = function (path) {
        if (path == $location.path())
            $window.location.reload();
        else
            $window.location.href = Constants.FRONTURL + '#!' + path;
    }
});