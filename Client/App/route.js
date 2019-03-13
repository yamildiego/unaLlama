app.config(function ($routeProvider) {
    $routeProvider
        .when('/', {
            cache: false,
            templateUrl: 'Views/home.html?v1',
            controller: 'homeController',
            activetab: 'home'
        })
        .when('/listado', {
            templateUrl: 'Views/list.html',
            controller: 'listController',
            activetab: 'list'
        })
        .when('/listado-por-categoria/:idCategory', {
            templateUrl: 'Views/list.html',
            controller: 'listForCategoryController',
            activetab: 'list'
        })
        .when('/listado-por-categoria-y-texto/:idCategory/:textSearch', {
            templateUrl: 'Views/list.html',
            controller: 'listForCategoryController',
            activetab: 'list'
        })
        .when('/publicar-anuncio', {
            templateUrl: 'Views/new-article.html',
            controller: 'newArticleController',
            activetab: 'new-article'
        })
        .when('/editar-anuncio/:idArticle', {
            templateUrl: 'Views/edit-article.html',
            controller: 'editArticleController',
            activetab: 'edit-article'
        })
        .when('/contacto', {
            templateUrl: 'Views/contact.html',
            controller: 'contactController',
            activetab: 'contact'
        })
        .when('/crear-cuenta', {
            templateUrl: 'Views/create-account.html',
            controller: 'createAccountController',
            activetab: 'create-account'
        })
        .when('/olvide-mi-contraseña', {
            templateUrl: 'Views/forgot-my-password.html',
            controller: 'forgotMyPasswordController',
            activetab: 'forgot-my-password'
        })
        .when('/restablecer-contraseña/:userId/:code', {
            templateUrl: 'Views/reset-password.html',
            controller: 'resetPasswordController',
            activetab: 'reset-password'
        })
        .when('/login', {
            templateUrl: 'Views/login.html',
            controller: 'loginController',
            activetab: 'login'
        })
        .when('/login/:tab', {
            templateUrl: 'Views/login.html',
            controller: 'loginController',
            activetab: 'login'
        })
        .when('/login/:tab/:id', {
            templateUrl: 'Views/login.html',
            controller: 'loginController',
            activetab: 'login'
        })
        .when('/favoritos', {
            templateUrl: 'Views/favorites.html',
            controller: 'favoritesController',
            activetab: 'favorites'
        })
        .when('/mis-anuncios', {
            templateUrl: 'Views/my-articles.html',
            controller: 'myArticlesController',
            activetab: 'my-articles'
        })
        .when('/ver-anuncio/:idArticle', {
            templateUrl: 'Views/view-article.html',
            controller: 'viewArticleController',
            activetab: 'view-article'
        })
        .when('/ver-anuncio/:idArticle/:fb', {
            templateUrl: 'Views/view-article.html',
            controller: 'viewArticleController',
            activetab: 'view-article'
        })
        .when('/perfil/:id', {
            templateUrl: 'Views/profile.html',
            controller: 'profileController'
        })
        .when('/terminos-y-condiciones', {
            templateUrl: 'Views/conditions.html'
        })
        .when('/quienes-somos', {
            templateUrl: 'Views/about-us.html'
        })
        .when('/publicidad', {
            templateUrl: 'Views/ad.html'
        })
        .when('/validateAccount/:userId/:code', {
            templateUrl: 'Views/validate-account.html',
            controller: 'validateAccountController'
        })
        .otherwise({
            redirectTo: '/'
        });
});