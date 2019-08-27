var DtsApp = angular.module("DtsApp", []);

DtsApp.controller('LoadListCtrl', ($scope, $http) => {
    $scope.getLoads = () => {
        $http.get('api.php', { params: params }).
        success((data, status, headers, config) => {
            $scope.loads = data.loads;
            $scope.page = data.page;
            // $('#dup_list').stopwait();
        }).
        error((data, status, headers, config) => {
            display_feedback({ success: false, feedback: 'An error ocurred. ' + data })
                // $('#dup_list').stopwait();
        });
    }
});