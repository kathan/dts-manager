angular.module("DtsApp", ['ngRoute', 'ngCookies'])
    .controller('DtsCtrl', ($scope, $http, $cookies) => {
        $scope.user = { name: $cookies.get('dts_username') };
        const setHeadBuffer = () => {
            const navMenuContainer = document.querySelector('#nav-menu-cont');
            const navMenuContainerHeight = navMenuContainer.offsetHeight;
            var i = 1;
        }
        $scope.$on('$viewContentLoaded', () => {
            setHeadBuffer();
        });
    });