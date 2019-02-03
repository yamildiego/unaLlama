app.directive('comments', function ($http, Constants, $timeout) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            article: '=',
            user: '=',
            edit: '='
        },
        link: function (scope) {
            scope.initialize = function () {
                scope.load = true;
                scope.loading = false;
                scope.sentMsg = false;
                scope.commets = [];
                scope.form = { msg: "" };
                scope.limitComments = 0;
                scope.loadComments();
            }

            scope.loadMoreInReply = function (index) {
                if (scope.comments[index].limitComments <= 1)
                    scope.comments[index].limitComments = 0;
                else
                    scope.comments[index].limitComments = scope.comments[index].limitComments - 1;
            }

            scope.loadMore = function () {
                if (scope.limitComments <= 2)
                    scope.limitComments = 0;
                else
                    scope.limitComments = scope.limitComments - 2;
            }

            scope.setReplyComment = function (comment) {
                scope.comments.forEach(function (e) {
                    if (e.id == comment.id) {
                        e.msg = '@' + comment.user.name + ' ';
                        e.reply = true;
                        e.focusMe = true;
                    }
                })
            }

            scope.sendComment = function (id) {
                var comment = scope.getComment(id);
                if (comment != null) {
                    comment.loading = true;
                } else {
                    scope.loading = true;
                }
                $http.post(Constants.APIURL + 'ArticleController/sendComment', { articleId: scope.article.id, commentId: (comment ? comment.id : null), text: (comment ? comment.msg : scope.form.msg) })
                    .then(function onSuccess(response) {
                        scope.loading = false;
                        if (response.data.status == 'OK') {
                            scope.loadComments();
                            if (comment != null) {
                                comment.sentMsg = true;
                                comment.reply = false;
                            } else {
                                scope.form.msg = "";
                                scope.sentMsg = true;
                            }
                        }
                    }, function onError(response) {
                        scope.loading = false;
                    });
            }

            scope.getComment = function (id) {
                var comment = null;
                if (id != null)
                    scope.comments.forEach(function (e) {
                        if (e.id == id) {
                            comment = e;
                        }
                    });
                return comment;
            }

            scope.loadComments = function () {
                $http.get(Constants.APIURL + 'ArticleController/getComments/' + scope.article.id)
                    .then(function onSuccess(response) {
                        if (response.data.status == 'OK') {
                            scope.comments = response.data.data;
                            scope.limitComments = (response.data.data.length <= 4) ? 0 : (response.data.data.length - 4);
                            scope.load = false;
                        }
                    }, function onError(response) {
                        scope.commets = [];
                    });
            }

            scope.$watch('article', function () {
                if (scope.article != null)
                    scope.initialize();
            });
        },
        templateUrl: "./App/Directives/comments/comments.html"
    }
});