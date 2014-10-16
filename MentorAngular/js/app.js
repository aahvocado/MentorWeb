var myApp = angular.module('myApp', [
  'ngRoute',
  'appControllers',
  'iso.directives'
]);

myApp.config(['$routeProvider', function($routeProvider) {
  $routeProvider.
  when('/welcome', {
    templateUrl: 'partials/welcome.html',
    controller: 'WelcomeController'
  }).
  when('/fork', {
    templateUrl: 'partials/fork.html',
    controller: 'ForkController'
  }).
  when('/register', {
    templateUrl: 'partials/register.html',
    controller: 'RegisterController'
  }).
  when('/menteeReg', {
    templateUrl: 'partials/mentee-reg.html',
    controller: 'RegisterController'
  }).
  when('/mentorReg', {
    templateUrl: 'partials/mentor-reg.html',
    controller: 'RegisterController'
  }).

  when('/homescreen', {
    templateUrl: 'partials/homescreen.html',
    controller: 'UserController'
  }).
  when('/searchmentors', {
    templateUrl: 'partials/searchmentors.html',
    controller: 'SearchController'
  }).
  when('/list', {
    templateUrl: 'partials/list.html',
    controller: 'ListController'
  }).
  when('/details/:itemId', {
    templateUrl: 'partials/details.html',
    controller: 'DetailsController'
  }).
  when('/loading', {
    templateUrl: 'partials/loading.html',
    controller: 'LoadingController'
  }).
  otherwise({
    redirectTo: '/loading'
  });
}]);