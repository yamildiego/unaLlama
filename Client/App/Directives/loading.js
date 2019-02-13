app.directive('loading', function () {
    return {
        restrict: 'E',
        scope: {
            color: '@',
            margin: '@',
            size: '@',
            logo: '@'
        },
        template: "<div style=\"text-align: center; width: 100%;\"><div style=\"margin-top: {{(margin) ? margin: '0';}}%;\"><div class=\"logo-load\">{{(logo) ? 'UnaLlama' : ''}}</div><div class=\"wjvl\">{{(logo) ? 'Donde Jujuy vende' : ''}}</div><i class=\"fas fa-spinner {{(size)? size : 'fa-4x'}}  fa-pulse\" style=\"margin-bottom:15%; color:{{(color && color != '') ? color : '#61a4ff'}}\"></i></div></div>"
    }
});