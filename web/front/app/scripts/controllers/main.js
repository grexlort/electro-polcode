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
                var startDate = new Date($scope.input.startDate.getTime()) / 1000;
                var endDate = new Date($scope.input.endDate.getTime()) / 1000;
                console.log(startDate);
                console.log(endDate);
                dateService.getDatesWithRange(startDate, endDate).then(function (data) {
                    $scope.labels = $rootScope.Utils.keys(data);
                    $scope.data = [$rootScope.Utils.values(data)];
                });
                //getDatesWithRange
            };
        });
