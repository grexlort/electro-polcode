'use strict';

/**
 * @ngdoc function
 * @name frontApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the frontApp
 */
angular.module('frontApp')
        .controller('MainCtrl', function ($rootScope, $scope, dateService, dates) {
            $scope.dates = dates;

            $scope.reloadChart = function () {
                $scope.labels = $rootScope.Utils.keys($scope.dates);
                $scope.data = [$rootScope.Utils.values($scope.dates)];
            };

            $scope.reloadChart();
//            $scope.labels = $rootScope.Utils.keys($scope.dates);
//            $scope.data = [$rootScope.Utils.values($scope.dates)];

            console.log($rootScope.Utils.keys($scope.dates));
            console.log($rootScope.Utils.values($scope.dates));

            $scope.change = function () {

                if ($scope.input.startDate && $scope.input.endDate) {

                    console.log($scope.input.startDate);
                    console.log($scope.input.endDate);

                    var startDate = dateService.toTimeStamp($scope.input.startDate);
                    var endDate = dateService.toTimeStamp($scope.input.endDate);

                    if (startDate >= endDate) {
                        return;
                    }

                    console.log(startDate);
                    console.log(endDate);
                    dateService.getDatesWithRange(startDate, endDate).then(function (data) {
                        $scope.labels = $rootScope.Utils.keys(data);
                        $scope.data = [$rootScope.Utils.values(data)];
                    });
                }
            };
        });
