app.config(function ($routeProvider) {
    $routeProvider
        .when('/', {
            cache: false,
            templateUrl: 'Views/home.html?v1.2',
            controller: 'homeController',
            activetab: 'home'
        })
        .when('/listado', {
            cache: false,
            templateUrl: 'Views/list.html?v1.2',
            controller: 'listController',
            activetab: 'list'
        })
        .when('/listado-por-categoria/:idCategory', {
            cache: false,
            templateUrl: 'Views/list.html?v1.2',
            controller: 'listForCategoryController',
            activetab: 'list'
        })
        .when('/listado-por-categoria-y-texto/:idCategory/:textSearch', {
            cache: false,
            templateUrl: 'Views/list.html?v1.2',
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
            templateUrl: 'Views/my-articles.html?v2',
            controller: 'myArticlesController',
            activetab: 'my-articles'
        })
        .when('/ver-anuncio/:idArticle', {
            templateUrl: 'Views/view-article.html?v2',
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
        .when('/participantes-del-concurso', {
            templateUrl: 'Views/participants.html',
            controller: 'participantsController'
        })
        .when('/validateAccount/:userId/:code', {
            templateUrl: 'Views/validate-account.html',
            controller: 'validateAccountController'
        })
        .otherwise({
            redirectTo: '/'
        });
});