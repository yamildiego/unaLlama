app.directive('comments', function ($location, $anchorScroll, $http, Constants, $timeout) {
    return {
        restrict: 'E',
        replace: true,
        scope: {
            article: '=',
            edit: '='
        },
        link: function (scope) {
            scope.initialize = function () {
                scope.load = true;
                scope.sentMsg = false;
                scope.replyComment = null;
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

            scope.setReplyComment = function (comment, reply) {
                scope.replyComment = comment;

                if (reply == null)
                    scope.form.msg = '@' + scope.replyComment.user.name + ' ';
                else
                    scope.form.msg = '@' + reply.user.name + ' ';

                if ($location.hash() !== 'query') {
                    $location.hash('query');
                    scope.focusMe = true;
                } else {
                    $anchorScroll();
                    scope.focusMe = true;
                }
            }

            scope.sendComment = function () {
                var commentId = (scope.replyComment == null) ? null : scope.replyComment.id;
                $http.post(Constants.APIURL + 'ArticleController/sendComment', { articleId: scope.article.id, commentId: commentId, text: scope.form.msg })
                    .then(function onSuccess(response) {
                        if (response.data.status == 'OK') {
                            scope.form.msg = "";
                            scope.sentMsg = true;
                            scope.loadComments();
                        }
                    }, function onError(response) { });
            }

            scope.loadComments = function () {
                $http.get(Constants.APIURL + 'ArticleController/getComments/' + scope.article.id)
                    .then(function onSuccess(response) {
                        if (response.data.status == 'OK') {
                            scope.comments = response.data.data;
                            scope.limitComments = (response.data.data.length <= 2) ? 0 : (response.data.data.length - 2);

                            scope.comments.forEach(function (element) {
                                element.limitComments = (element.replys.length <= 1) ? 0 : (element.replys.length - 1);
                            });

                            scope.load = false;
                        }
                    }, function onError(response) {
                        scope.commets = [];
                    });
            }

            $timeout(function () {
                scope.initialize();
            }, 1000);
        },
        templateUrl: "./App/Directives/comments/comments.html"
    }
});