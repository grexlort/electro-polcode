'use strict';

/**
 * @ngdoc overview
 * @name frontApp
 * @description
 * # frontApp
 *
 * Main module of the application.
 */
angular
        .module('frontApp', [
            'ngAnimate',
            'ngCookies',
            'ngResource',
            'ngRoute',
            'ngSanitize',
            'ngTouch',
            'chart.js'
        ])
        .run(function ($rootScope) {
            $rootScope.Utils = {
                keys: Object.keys,
                values: function (obj) {
                    var values = [];

                    for (var k in obj) {
                        if (obj.hasOwnProperty(k)) {
                            values.push(obj[k]);
                        }
                    }

                    return values;
                }
            };
        }
        )
        .config(function ($routeProvider) {
            $routeProvider
                    .when('/', {
                        templateUrl: 'views/main.html',
                        controller: 'MainCtrl',
                        resolve: {
                            dates: function (dateService) {
                                return dateService.getDates();
                            }
                        }
                    })
                    .otherwise({
                        redirectTo: '/'
                    });
        });
