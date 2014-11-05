var appControllers = angular.module('appControllers', ['ngAnimate', 'ngResource', 'ngSanitize'])
.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });
 
                event.preventDefault();
            }
        });
    };
})
.directive('ngClassAlt', function() {
  return {
    restrict: 'A',
    scope: {
      ngClassAlt: '=ngClassAlt'
    },
    link: function(scope, elm, attrs) {
      scope.$watch('ngClassAlt', function (newVal) {
        _.each(scope.ngClassAlt, function(val, key) {
          if(val)
            elm.addClass(key);
          else
            elm.removeClass(key);
        });
      }, true);
    }
  };
});

appControllers.controller('WelcomeController', ['$scope', '$http', function($scope, $http) {

  $scope.go = function() {
    window.location.replace("https://login.gatech.edu/cas/login?service=http%3A%2F%2Fdev.m.gatech.edu%2Fd%2Faarrowood3%2Fw%2FMentorAngular%2Fcontent%2F")
  };
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
  $('.ui.checkbox').checkbox();
  $('.ui.accordion').accordion();

  $http.get('json-gen/users.json').success(function(data) {
    $scope.userData = data;
    $scope.miniProfileData = $scope.userData[0];
    $scope.wishButton = {};
    $scope.renderButton($scope.miniProfileData.favorited);
  }).
  error(function(data, status, headers, config) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    console.log("Error getting userData");
  });
  $scope.miniProfileSet = function(user) {
    //console.log("yo ");
    //console.log(user);
    $scope.miniProfileData = user;
    $scope.renderButton($scope.miniProfileData.favorited);
  }
  $scope.addToWishlist = function() {
    // $http.post('api/registerMentor',
    // {
    //     'fname': $scope.fname,
    //     'lname': $scope.lname,
    //     'phone': $scope.phone,
    //     'email': $scope.email,
    //     'pref_comm': $scope.pref_comm
    //   }
    //   )
    // .success (function (data, status, headers, config) {
    //   $scope.data = data;
    //   console.log(data);
    // })
    // .error (function (data, status, headers, config) {
    //   $scope.status = status;
    // });

    $scope.miniProfileData.favorited = "favorited";
    $scope.renderButton($scope.miniProfileData.favorited);
  }
  $scope.removeFromWishlist = function() {
    $scope.miniProfileData.favorited = "";
    $scope.renderButton($scope.miniProfileData.favorited);
  }

  $scope.renderButton = function(favorited) {
    if (favorited == "favorited") {
      $scope.wishButton.contentText = "Remove from Wishlist";
      $scope.wishButton.fn = $scope.removeFromWishlist;
    } else {
      $scope.wishButton.contentText = "Add to Wishlist";
      $scope.wishButton.fn = $scope.addToWishlist;
      console.log("wishButton text: " + $scope.wishButton);
    }
  }
  
}]);

appControllers.controller('WishlistController', ['$scope', '$http', function($scope, $http) {
  $http.get('json-gen/users.json').success(function(data) {
    $scope.userData = data;
    $scope.miniProfileData = $scope.userData[0];
    $scope.wishButton = {};
    $scope.renderButton($scope.miniProfileData.favorited);
  }).
  error(function(data, status, headers, config) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    console.log("Error getting userData");
  });
  $scope.miniProfileSet = function(user) {
    //console.log("yo ");
    //console.log(user);
    $scope.miniProfileData = user;
    $scope.renderButton($scope.miniProfileData.favorited);
  }
}]);

appControllers.controller('RegisterMentorController', ['$scope', '$http', function($scope, $http) {
  $('.ui.dropdown').dropdown();
  console.log({
        'fname': $scope.fname,
        'lname': $scope.lname,
        'phone': $scope.phone,
        'email': $scope.email,
        'pref_comm': $scope.pref_comm
      });
  $scope.addMentor = function() {
    $http.post('api/registerMentor',
    {
        'fname': $scope.fname,
        'lname': $scope.lname,
        'phone': $scope.phone,
        'email': $scope.email,
        'pref_comm': $scope.pref_comm
      }
      )
    .success (function (data, status, headers, config) {
      $scope.data = data;
      console.log(data);
    })
    .error (function (data, status, headers, config) {
      $scope.status = status;
    });
  }
  // $scope.addMentor = function() {
  //   $http({
  //     method: "POST",
  //     url: "api/registerMentor",
  //     //"index.html#/addMentor.php", 
  //     data: {
  //       'fname': $scope.fname,
  //       'lname': $scope.lname,
  //       'phone': $scope.phone,
  //       'email': $scope.email,
  //       'pref_comm': $scope.pref_comm
  //     }
  //   }
  //   )
  //   .success (function (data, status, headers, config) {
  //     console.log(data);
  //   })
  //   .error (function (data, status, headers, config) {

  //   });
  // }


//end RegisterMentorController

  // $(document).on('click', '#reg_submit_button', function() {
  //   console.log("Reg Button");
  //   console.log($('#comm_method')[0].value);
  //   $.ajax({
  //     url: "api/register",
  //     dataType: "json",
  //         async: false,
  //     data: {'regForm':{'firstName': $('#first_name')[0].value,
  //            'lastName': $('#last_name')[0].value,
  //            'email': $('#email')[0].value,
  //            'phoneNumber': $('#phone_number')[0].value,
  //            'commMethod': $('#comm_method')[0].value}
  //           },
  //     type: 'POST',
  //     error: ajaxError
  //   });
  // });
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


