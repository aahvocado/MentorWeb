var appControllers = angular.module('appControllers', ['ngAnimate']);

appControllers.controller('WelcomeController', ['$scope', '$http', function($scope, $http) {

  $(document).on('click', '.welcome .welcome-message .button', function() {
    window.location.replace("https://login.gatech.edu/cas/login?service=http%3A%2F%2Fdev.m.gatech.edu%2Fd%2Faarrowood3%2Fw%2Fmentorangular%2Fcontent%2F")
  })
}]);

appControllers.controller('ForkController', ['$scope', '$http', function($scope, $http) {

}]);

appControllers.controller('UserController', ['$scope', '$http', function($scope, $http) {
  $http.get('api/welcome').success(function(data) {
    $scope.user = data['username'];
    $scope.userType = data['userType'];
  });
}]);

appControllers.controller('LoadingController', ['$scope', '$http', function($scope, $http) {
  // $http.get('http://dev.m.gatech.edu/d/aarrowood3/w/mentoringweb/content/api/welcome').success(function(data) {
  //   $scope.user = data['username'];
  //   $scope.userType = data['userType'];
  // });
}]);

appControllers.controller('SearchController', ['$scope', '$http', function($scope, $http) {
  $('.ui.checkbox')
    .checkbox()
  ;
    $('.ui.accordion')
    .accordion()
  ;
  // $http.get('json-gen/users.json').success(function(data) {
  //   $scope.userdata = data;
  // });
  
}]);

appControllers.controller('RegisterController', ['$scope', '$http', function($scope, $http) {
  $('.ui.dropdown').dropdown();

  $(document).on('click', '#reg_submit_button', function() {
    console.log("Reg Button");
    console.log($('#comm_method')[0].value);
    $.ajax({
      url: "api/register",
      dataType: "json",
          async: false,
      data: {'regForm':{'firstName': $('#first_name')[0].value,
             'lastName': $('#last_name')[0].value,
             'email': $('#email')[0].value,
             'phoneNumber': $('#phone_number')[0].value,
             'commMethod': $('#comm_method')[0].value}
            },
      type: 'POST',
          error: ajaxError
    });
  });
}]);


appControllers.controller('ListController', ['$scope', '$http', function($scope, $http) {
  $http.get('js/data.json').success(function(data) {
    $scope.apps = data;
    $scope.appOrder = 'name';
  });
}]);

appControllers.controller('DetailsController', ['$scope', '$http','$routeParams', function($scope, $http, $routeParams) {
  $http.get('js/data.json').success(function(data) {
    $scope.apps = data;
    $scope.whichItem = $routeParams.itemId;

    if ($routeParams.itemId > 0) {
      $scope.prevItem = Number($routeParams.itemId)-1;
    } else {
      $scope.prevItem = $scope.apps.length-1;
    }

    if ($routeParams.itemId < $scope.apps.length-1) {
      $scope.nextItem = Number($routeParams.itemId)+1;
    } else {
      $scope.nextItem = 0;
    }

  });
}]);


