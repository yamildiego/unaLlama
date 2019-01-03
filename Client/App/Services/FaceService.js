app.factory('FaceService', function ($window, $q, $http, Constants) {
    return {
        getLoginStatus: function (callBackOk, callBackError) {
            var _self = this;

            if (typeof (FB) != 'undefined' && FB != null) {
                FB.getLoginStatus(function (response) {
                    if (response.status === 'connected') {
                        if (callBackOk)
                            callBackOk(response);
                    } else if (response.status === 'not_authorized') {
                        if (callBackOk)
                            callBackOk(response);
                    } else if (callBackError) {
                        callBackError(response);
                    }
                }, true);
            } else {
                _self.initialize().then(function () {
                    _self.getLoginStatus(callBackOk, callBackError);
                });
            }
        },
        login: function (callBackOk, callBackError) {
            // var _self = this;
            // if (typeof FB == "undefined") {
            //     _self.getLoginStatus(function () {
            //         _self.login(callBackOk, callBackError);
            //     });
            // } else {
            // FB.login(function (response) {

            // if (response.status === 'connected') {
            // FB.api('/me?fields=id,first_name,email,picture.width(100).height(100)', function (response) {
            // if (!response.hasOwnProperty('error')) {

            var response = { "id": "10217456540392511", "first_name": "Yamil", "email": "yamildiego@gmail.com", "picture": { "data": { "height": 100, "is_silhouette": false, "url": "https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=10217456540392511&height=100&width=100&ext=1548641501&hash=AeTDLQr97-xNPprz", "width": 100 } } }

            $http.post(Constants.APIURL + 'UserController/loginFb', response)
                .then(function onSuccess(response) {
                    if (callBackOk)
                        callBackOk(response);
                });
            // }
            // }, { scope: 'public_profile, email, user_friends' });
            // } else if (callBackError)
            // callBackError(response);
            // }, { scope: 'public_profile, email, user_friends' });
            // }
        },
        // initialize: function () {
        //     var defered = $q.defer();
        //     var promise = defered.promise;

        //     $window.fbAsyncInit = function () {
        //         FB.init({
        //             appId: '240845259944211',
        //             cookie: true,
        //             xfbml: true,
        //             version: 'v2.8'
        //         });

        //         defered.resolve();
        //     };

        //     (function (d, s, id) {
        //         var js, fjs = d.getElementsByTagName(s)[0];
        //         if (d.getElementById(id)) return;
        //         js = d.createElement(s);
        //         js.id = id;
        //         js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
        //         fjs.parentNode.insertBefore(js, fjs);
        //     }(document, 'script', 'facebook-jssdk'));

        //     return promise;
        // }
    }
});