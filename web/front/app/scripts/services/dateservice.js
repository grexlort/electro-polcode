'use strict';

/**
 * @ngdoc service
 * @name frontApp.dateService
 * @description
 * # dateService
 * Factory in the frontApp.
 */
angular.module('frontApp')
        .factory('dateService', function ($http, $q) {
            return {
                getDates: function () {
                    var deffered = $q.defer();
                    var _this = this;

                    $http.get('app.php/test').
                            success(function (data) {
                                deffered.resolve(data);
                            }).
                            error(function (data, status, headers, config) {
                                console.log(data);
                                deffered.reject();
                            });

                    return deffered.promise;
                },
                getDatesWithRange: function (startDate, endDate) {
                    var deffered = $q.defer();
                    var _this = this;

                    $http.get('app.php/api/dates', {
                        params: {
                            startDate: startDate,
                            endDate: endDate
                        }
                    }).
                            success(function (data) {
                                deffered.resolve(data);
                            }).
                            error(function (data, status, headers, config) {
                                console.log(data);
                                deffered.reject();
                            });

                    return deffered.promise;
                },
                toTimeStamp: function(date){
                    return new Date(date.getTime()) / 1000;
                }
            };
        });
