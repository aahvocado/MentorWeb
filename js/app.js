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
  when('/mentorAgreement', {
    templateUrl: 'partials/mentor-user-agreement.html',
    controller: 'MentorUserAgreementController'
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
  when('/wishlist', {
    templateUrl: 'partials/wishlist.html',
    controller: 'WishListController'
  }).
  when('/editProfile', {
    templateUrl: 'partials/edit-profile.html',
    controller: 'EditProfileController'
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
  when('/dev', {
    templateUrl: 'partials/dev.html',
    controller: 'DevController'
  }).
  when('/requestingPeriod', {
    templateUrl: 'partials/requesting-period.html',
    controller: 'RequestingPeriodController'
  }).
  when('/approveMentors', {
    templateUrl: 'partials/approve-mentors.html',
    controller: 'ApproveMentorController'
  }).
  when('/setMentorMax', {
    templateUrl: 'partials/set-mentor-max.html',
    controller: 'SetMentorMaxController'
  }).
  when('/logout', {
    templateUrl: 'partials/logout.html',
    controller: 'LogoutController'
  }).
  otherwise({
    redirectTo: '/loading'
  });
}]);
