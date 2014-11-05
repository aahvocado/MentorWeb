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
  when('/user-profile', {
    templateUrl: 'partials/user-profile.html',
    controller: 'UserProfileController'
  }).
  when('/register', {
    templateUrl: 'partials/register.html',
    controller: 'RegisterController'
  }).
  when('/menteeReg', {
    templateUrl: 'partials/mentee-reg.html',
    controller: 'RegisterMenteeController'//change to Mentee when
  }).
  when('/mentorReg', {
    templateUrl: 'partials/mentor-reg.html',
    controller: 'RegisterMentorController'
  }).
  when('/mentorAlias', {
    templateUrl: 'partials/mentor-alias.html',
    controller: 'MentorAliasController'
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
  when('/wishlist', {
    templateUrl: 'partials/wishlist.html',
    controller: 'WishlistController'
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