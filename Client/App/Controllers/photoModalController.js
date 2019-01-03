app.controller('photoModalController', function ($scope, photos, index) {
    $scope.photos = photos;
    $scope.indexPhoto = (index == undefined) ? 0 : index;
    $scope.indexModal = index;

    $scope.nextP = function () {
        if ($scope.indexPhoto) {
            if ($scope.indexPhoto >= ($scope.photos.length - 1))
                $scope.indexPhoto = 0;
            else
                $scope.indexPhoto = $scope.indexPhoto + 1;
        } else {
            $scope.indexPhoto = 1;
        }
    }

    $scope.backP = function () {
        if ($scope.indexPhoto) {
            if ($scope.indexPhoto <= 0)
                $scope.indexPhoto = $scope.photos.length - 1;
            else
                $scope.indexPhoto = $scope.indexPhoto - 1;
        } else {
            $scope.indexPhoto = $scope.photos.length - 1;
        }
    }
});