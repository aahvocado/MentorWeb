var appControllers = angular.module('appControllers', ['ngAnimate', 'ngResource']);

appControllers.controller('WelcomeController', ['$scope', '$http', function($scope, $http) {

  $(document).on('click', '.welcome .welcome-message .button', function() {
    window.location.replace("https://login.gatech.edu/cas/login?service=http%3A%2F%2Fdev.m.gatech.edu%2Fd%2Fmosborne8%2Fw%2FMentorAngular%2Fcontent%2F")
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
  // $http.get('http://dev.m.gatech.edu/d/mosborne8/w/mentoringweb/content/api/welcome').success(function(data) {
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

appControllers.controller('RegisterMentorController', ['$scope', '$http', '$filter', function($scope, $http, $filter) {
  // $('.ui.dropdown').dropdown();
  $('.ui.radio.checkbox').checkbox();
  $('.ui.checkbox').checkbox();

  $scope.form = { dfocus: '-', ethnicity: [] };
  $scope.comms = [{
        id: 1,
        name: 'Phone'
    }, {
        id: 2,
        name: 'Email'
    }];

  $scope.genders = [{
      id: 1,
      name: 'Female'
  }, {
      id: 2,
      name: 'Male'
  }];

  $scope.ethnicities = [{
      id: 1,
      name: 'American Indian or Alaskan Native'
  }, {
      id: 2,
      name: 'Asian or Pacific Islander'
  }, {
      id: 3,
      name: 'Black or African American'
  }, {
      id: 4,
      name: 'Hispanic or Latino'
  }, {
      id: 5,
      name: 'White/Caucasian'
  }];

  $scope.toggleSelection = function toggleSelection (eth) {
    var idx = $scope.form.ethnicity.indexOf(eth)

    if(idx > -1) {
      $scope.form.ethnicity.splice(idx, 1);
    }
    else {
      $scope.form.ethnicity.push(eth);
    }
  }

  $scope.dfocusVals = [{
    id:1,
    name: "Neuroengineering"
  }, {
    id:2,
    name: "Cardiovascular Systems"
  }, {
    id:3, 
    name: "Biomechanics"
  }, {
    id:4,
    name: "Biomaterials/Tissue Engineering"
  }, {
    id:5, 
    name: "Medical Imaging"
  }, {
    id:6,
    name: "Some of Everything"
  }, {
    id:7,
    name: "Other",
    other: ""
  }];

 // $scope.selected = function (s, ind) {
 //      console.log('s', s);
 //        $scope.form.ethnicity = $filter('filter')($scope.ethnicities, {id: ind});
 //  }

  $scope.newValue = function(value, attr) {
    console.log('new value', value);
    $scope.form[attr] = value;

    if (value == "Other" && attr == "dfocus") {
      $scope.form.dfocus.other= $scope.form.dfocusother;
    } else if (value != "Other" && attr == "dfocus") {
      $scope.form.dfocusother = null;
    }

  }

  // $scope.$watch('form.dfocus', function (mVal) {
  //   if (angular.isUndefined($scope.form.dfocus)) return;
  //   $scope.form.dfocus = mVal;
  //   if (mVal === 'Other') {
  //       $scope.form.dfocus = $scope.form.dfocusother;
  //   } else {
  //       $scope.form.dfocusother = null;
  //   }
// });

  $scope.addMentor = function() {
    $.ajax({
      url: "api/registerMentor",
      dataType: "json",
          async: false,
      data: {'fname': $scope.form.fname,
             'lname': $scope.form.lname,
             'email': $scope.form.email,
             'phone':$scope.form.phone,
             'pref_comm': $scope.form.prefComm,//use this if drop down $scope.form.prefComm.name,
             'dfocus': $scope.form.dfocus,
             'dfocusother': $scope.form.dfocusother,
             'gender': $scope.form.gender
            },
      type: 'POST'
      // error: ajaxError
    });
  };
  
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


