var appControllers = angular.module('appControllers', ['ngAnimate', 'ngResource']);

// appControllers.directive('accessibleForm', function () {
//     return {
//         restrict: 'A',
//         link: function (scope, elem) {

//             // set up event handler on the form element
//             elem.on('submit', function () {

//                 // find the first invalid element
//                 var firstInvalid = angular.element(
//                     elem[0].querySelector('.ng-invalid'))[0];

//                 // if we find one, set focus
//                 if (firstInvalid) {
//                     firstInvalid.focus();
//                 }
//             });
//         }
//     };
// });

appControllers.controller('mainController', ['$scope', '$http', '$location', function($scope, $http, $location) {
   $scope.go = function(path) {
    $location.path(path);
    //$location.reload(true);
    //$scope.$parent.$apply();
  };
  $scope.ajaxError = function ajaxError(jqXHR, textStatus, errorThrown){
    console.log('ajaxError '+jqXHR+' '+textStatus+' '+errorThrown);
    console.log("Main Controller Called");
  }
}]);

appControllers.controller('HeaderController', ['$scope', '$http', '$location', function($scope, $http, $location) {

  // if($window.location == "\welcome" || $window.location == "\register" || $window.location == "\menteeReg"){
    $scope.$parent.headerType = {none: 1,
    mentee: 0,
    mentor: 0,
    admin: 0};
    $scope.headerType = $scope.$parent.headerType;
  
  // $.get("api/user", function (data) {
  //       data = data;//$data = data;//$('#hello').tmpl(data).appendTo("#hello");
  //       console.log("data: " , data);
  // });
$scope.refreshHeader = function() {
  var data = {};
  if(window.location.href.indexOf("welcome") > -1 || window.location.href.indexOf("register") > -1){
    console.log("no get sent");
    $scope.$parent.headerType = {none: 1,
      mentee: 0,
      mentor: 0,
      admin: 0};
    //$scope.headerType = $scope.$parent.headerType;
  } else {
    $.ajax({
        url: "api/user",
        dataType: "json",
        async: false,
        success: function(result) {
          data = result;
        },
        type: 'GET'
        // error: ajaxError
      }); 
    if(data["Mentor"]) {
      $scope.$parent.headerType.none = 0;
      $scope.$parent.headerType.mentor = 1;
    }
    if(data["Mentee"]) {
      $scope.$parent.headerType.none = 0;
      $scope.$parent.headerType.mentee = 1;
    }
    if(data["Admin"]) {
      $scope.$parent.headerType.none = 0;
      $scope.$parent.headerType.admin = 1;
    }
  }
}
$scope.$parent.refreshHeader = $scope.refreshHeader;
}]);

appControllers.controller('EditProfileController', ['$scope', '$http', '$location', function($scope, $http, $location) {
  var data = {};
  $.ajax({
    url: "api/user",
    dataType: "json",
    async: false,
    success: function(result) {
      data = result;
      // $scope.username = data['username'];
    },
    type: 'GET'
    // error: ajaxError
  }); 
  if(data["Mentor"]) {
    $scope.viewMentorForm = 1;
    $scope.viewMenteeForm = 0;
  }
  if(data["Mentee"]) {
    $scope.viewMenteeForm = 1;
    $scope.viewMentorForm = 0;
  }

  $scope.form = [];

  $.get('api/mentor/' + $scope.$parent.username).success(function(data) {
    $scope.data = JSON.parse(data)[0];
    console.log($scope.data);
    $scope.form.fname = $scope.data["first_name"];
    $scope.form.lname = $scope.data["last_name"];
    $scope.form.phone = $scope.data["phone_num"];
    $scope.form.email = $scope.data["email"];
    $scope.form.load = true;
    $scope.$apply();
  });  

 // $.ajax({
 //    url: "api/mentor/" + $scope.$parent.username,
 //    dataType: "json",
 //    async: false,
 //    success: function(result) {
 //      data = result;
 //    },
 //    type: 'GET'
 //    // error: ajaxError
 //  });

}]);

appControllers.controller('WelcomeController', ['$scope', '$http', '$location', function($scope, $http, $location) {
  // window.location.reload(true);

  $scope.go = function() {
    window.location.replace("https://login.gatech.edu/cas/login?service=http%3A%2F%2Fdev.m.gatech.edu%2Fd%2Famorlan3%2Fw%2FMentorAngular%2Fcontent%2F")
  };
}]);

appControllers.controller('ForkController', ['$scope', '$http', function($scope, $http) {

}]);

appControllers.controller('UserController', ['$scope', '$http', function($scope, $http) {
  $.get('api/welcome').success(function(data) {
    $scope.user = data;
    $scope.userType = data['userType'];
    $scope.$parent.username = data['username'];
    console.log(data);
  });
}]);

appControllers.controller('LoadingController', ['$scope', '$http', function($scope, $http) {
  // $http.get('http://dev.m.gatech.edu/d/mosborne8/w/mentoringweb/content/api/welcome').success(function(data) {
  //   $scope.user = data['username'];
  //   $scope.userType = data['userType'];
  // });
}]);

appControllers.controller('HomeController', ['$scope', '$http', '$location', function($scope, $http, $location) {

  $scope.refreshHeader();

  $scope.user = {type:[],
    none: 1,
    mentee: 0,
    mentor: 0,
    admin: 0,
    id: ''};
  var data = {};
  
  // $.get("api/user", function (data) {
  //       data = data;//$data = data;//$('#hello').tmpl(data).appendTo("#hello");
  //       console.log("data: " , data);
  // });
  $.ajax({
      url: "api/user",
      dataType: "json",
      async: false,
      success: function(result) {
        data = result;
      },
      type: 'GET'
      // error: ajaxError
    }); 
  console.log("homescreen user:");
  console.log(data);
  $scope.user.name = data["Name"];
  $scope.$parent.username = data["Name"];
  // $scope.user.id = data["Id"];
  $scope.user.mentor = data["Mentor"];
  $scope.user.mentee = data["Mentee"];
  $scope.user.admin = data["Admin"];
  if ($scope.user.mentor == 1 || $scope.user.mentee == 1 || $scope.user.admin ==1) {
    $scope.user.none = 0;
  }

  if(data["Mentor"]) {
    $scope.user.type.push("Mentor");
  }
  if(data["Mentee"]) {
    $scope.user.type.push("Mentee");

    // $.ajax({
    //   url: "api/mentor",
    //   dataType: "json",
    //   async: true,
    //   success: function(result) {
    //     //data = result;
    //     $scope.myMentor = result;
    //     console.log("getMentor");
    //     console.log($scope.myMentor);
    //   },
    //   type: 'GET',
    //   error: $scope.ajaxError
    //   // error: ajaxError
    // });

    $.ajax({
      url: "api/getMenteeMatch",
      dataType: "json",
          async: true,
          success: function(data, textStatus, jqXHR) {
            console.log("getMenteeMatch");
            console.log(data);
            $scope.$apply();
            //Create The New Rows From Template
            //$scope.myMentor = data;
          },
          error: $scope.ajaxError
    });

    // $.ajax({
    //   url: "api/mentor/" + user + "/comment",
    //   dataType: "json",
    //       async: false,
    //       success: function(data, textStatus, jqXHR) {
    //     console.log(data);
    //         //Create The New Rows From Template
    //         $scope.myMentor = data;
    //       },
    //       error: ajaxError
    //});
  }

  if(data["Admin"]) {
    $scope.user.type.push("Admin");
  }

}]);

appControllers.controller('SearchController', ['$scope', '$http', function($scope, $http) {
  $scope.refreshHeader();

  $('.ui.checkbox').checkbox();
  $('.ui.accordion').accordion();

  $scope.$parent.wishList = ($scope.$parent.wishList || []);

  // $http.get('json-gen/users120.json').success(function(data) {
  //   $scope.userData = data;
  //   $scope.miniProfileData = $scope.userData[0];
  //   $scope.wishButton = {};
  //   $scope.renderButton($scope.miniProfileData.favorited);
  //   $scope.refreshUI();
  // }).
  // error(function(data, status, headers, config) {
  //   // called asynchronously if an error occurs
  //   // or server returns response with an error status.
  //   console.log("Error getting userData");
  // });

  $.ajax({
    url: "api/listMentors",
    dataType: "json",
      async: true,
      success: function(data, textStatus, jqXHR) {
        console.log("listMentors");
        console.log(data);
        $scope.userData = data;
        $scope.miniProfileData = $scope.userData[0];
        $scope.wishButton = {};
        $scope.renderButton($scope.miniProfileData.favorited);
        $scope.refreshUI();
        //Create The New Rows From Template
        //$scope.myMentor = data;
        $scope.$apply();

      },
      error: $scope.ajaxError
    });

  $scope.miniProfileSet = function(user) {
    //console.log("yo ");
    //console.log(user);
    $scope.miniProfileData = user;
    $scope.renderButton($scope.miniProfileData.favorited);
  }
  $scope.addToWishlist = function() {
    $scope.miniProfileData.favorited = "favorited";
    $scope.renderButton($scope.miniProfileData.favorited);
    $scope.$parent.wishList.push($scope.miniProfileData);
  }
  $scope.removeFromWishlist = function() {
    $scope.miniProfileData.favorited = "";
    $scope.renderButton($scope.miniProfileData.favorited);
    $scope.$parent.wishList.splice($.inArray($scope.miniProfileData, $scope.$parent.wishList), 1 );
  }
  $scope.refreshUI = function() {
    $scope.userData.forEach(function(element) {
      var user = element;
      $scope.$parent.wishList.forEach(function(element) {
        if (user.username === element.username) { //JSON.stringify(user) === JSON.stringify(element)
          user.favorited = "favorited";
        }
      });
    });
  }
  $scope.$on('$routeChangeStart', function () { //For some reason the isotope ul must be emptied or page change lags
    $('#isotopeContainer').empty();
  });
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

appControllers.controller('WishListController', ['$scope', '$http', function($scope, $http) {
  $scope.refreshHeader();

  $scope.userData = $scope.$parent.wishList;
  if ($scope.userData) {
    $scope.miniProfileData = $scope.userData[0];
  }
  console.log('yo');
  $scope.miniProfileSet = function(user) {
    $scope.miniProfileData = user;
  }
  $scope.notification = function() {
    $('#mentor-note').dimmer('toggle');
  }
  $scope.removeFromWishlist = function() {
    $scope.miniProfileData.favorited = "";
    //$scope.userData.splice($.inArray($scope.miniProfileData, $scope.userData), 1 );
    $.each($scope.userData, function(i){
      if($scope.userData[i].username === $scope.miniProfileData.username) {
        console.log("splice");
        console.log($scope.userData[i].username);
        console.log($scope.miniProfileData.username);
        $scope.userData.splice(i,1);
        return false;
      }
    });
    $scope.$parent.wishList = $scope.userData;

    //$scope.$parent.wishList.splice($.inArray($scope.miniProfileData, $scope.$parent.wishList), 1 );
  }
  $scope.chooseMentor = function() {
    $scope.$parent.myMentor = $scope.miniProfileData;
    $scope.myMentor = $scope.$parent.myMentor;

    $.ajax({
      url: "api/chooseMentor",
      dataType: "json",
          async: false,
      data: {'mentor': $scope.myMentor.username}, //$scope.$parent.myMentor
      type: 'POST'
      // error: ajaxError
    }); 

    console.log("chooseMentor");
    $scope.go('/user-profile');
  }
  $scope.refreshUI = function() {
    $scope.userData.forEach(function(element) {
      var user = element;
      $scope.$parent.wishList.forEach(function(element) {
        if (user.username === element.username) { //JSON.stringify(user) === JSON.stringify(element)
          user.favorited = "favorited";
        }
      });
    });
  }
  if ($scope.userData) {
    //$scope.refreshUI();
  }
}]);

appControllers.controller('UserProfileController', ['$scope', '$http', '$location', function($scope, $http, $location) {
  $scope.myMentor = $scope.$parent.myMentor;

  $scope.reset = function() {
    $.ajax({
      url: "api/resetUser",
      dataType: "json",
      async: true,
      success: function(result) {
        //data = result;
      },
      type: 'GET'
      // error: ajaxError
    }); 
  }
}]);

appControllers.controller('RegisterController', ['$scope', '$http', '$location', function($scope, $http, $location) {
}]);

appControllers.controller('RegisterMenteeController', ['$scope', '$http', '$filter', '$location', function($scope, $http, $filter, $location) {
  $('.ui.radio.checkbox').checkbox();
  $('.ui.checkbox').checkbox();
  $('.ui.dropdown').dropdown();
  $scope.showNext = $scope.$parent.showNext;

  $scope.form = { 
      dfocus: '', 
      breadth_track:[],
      bme_organization: [],
      tutor_teacher_program: [],
      bme_academ_exp: [],
      international_experience: [],
      career_dev_program: [],
      other_major: null
  };

  //error messages
  $('.ui.form')
  .form({
    fname: {
      identifier  : 'fname',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your first name'
        }
      ]
    },
    lname: {
      identifier  : 'lname',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your last name'
        }
      ]
    },
    email: {
      identifier  : 'email',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your email'
        },{
          type: 'email',
          prompt: 'Please enter a valid email'
        }
      ]
    },
    prefComm: {
      identifier  : 'prefComm',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your preferred communication method'
        }
      ]
    }
  },
  {
    inline: true,
    on: 'blur',
    transition: 'fade down', 
  });

   $scope.comms = [{
        id: 1,
        name: 'Phone'
    }, {
        id: 2,
        name: 'Email'
    }];

   $scope.yesno = [{
      id:1,
      name: 'Yes',
      value: 1
    }, {
      id:2, 
      name: 'No',
      value: 0
    }];

  $scope.breadthTracks = [{
    id:1,
    name:'Pre-health',
    desc: ''
  }, {
    id:2,
    name:'Research Option',
    desc:''
  }, {
    id:3, 
    name:'Minor',
    desc:''
  }, {
    id:4, 
    name:'Certificate',
    desc:''
  }, {
    id:5, 
    name:'Not Sure',
    desc:''
  }];

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

  $scope.internationalPrograms = [{
    id:1, 
    name:'International Plan'
  }, {
    id:2, 
    name:'Study Abroad'
  }, {
    id:3, 
    name:'Work Abroad'
  }, {
    id:4,
    name:'Research Abroad'
  }, {
    id:5, 
    name:'Volunteer Abroad'
  }];

  $scope.bmeOrganizations = [{
    id:1,
    name:'Alpha Eta Mu (AEMB)'
  }, {
    id:2,
    name:'Biomedical Engineering Society (BMES)'
  }, {
    id:3,
    name:'Biomedical Research & Opportunities Society (BROS)'
  }, {
    id:4, 
    name:'BMED Futures'
  }, {
    id:5, 
    name:'Engineering World Health (EWH)'
  }, {
    id:6, 
    name:'Medical Device Entrepreneurship Association (MDEA)'
  }, {
    id:7, 
    name:'Pioneer'
  }];

  $scope.tutorTeachPrograms = [{
    id:1, 
    name:'PLUS Leader (Center for Academic Success)'
  }, {
    id:2, 
    name:'1 On 1 Tutoring'
  }, {
    id:3, 
    name:'Tutoring in BME with a Student Organization'
  }, {
    id:4, 
    name:'Ad Hoc Tutoring (That You Arranged on Your Own)'
  }, {
    id:5,
    name:'BMED 1300 Co-Facilitator'
  }, {
    id:6, 
    name:'Undergraduate Grader or Teaching Assistant for a BME Course'
  }];

  $scope.bmeAcademicPrograms = [{
    id:1,
    name:'Inventure Prize'
  }, {
    id:2,
    name:'Design Expo'
  }, {
    id:3,
    name:'Multidisciplinary Capstone Design Course'
  }, {
    id:4, 
    name:'The Clinical Observation and Design Experience (CODE) Course  (BMED 4813)'
  }];

  $scope.internationalPrograms = [{
    id:1, 
    name:'International Plan'
  }, {
    id:2, 
    name:'Study Abroad'
  }, {
    id:3, 
    name:'Work Abroad'
  }, {
    id:4,
    name:'Research Abroad'
  }, {
    id:5, 
    name:'Volunteer Abroad'
  }];

  $scope.carrerDevPrograms = [{
    id:1,
    name:'Co-op'
  }, {
    id:2, 
    name:'Internship'
  }, {
    id:1,
    name:'Shadowing in a Medical Environment'
  }];

  $scope.postGradPlans = [{
    id:1,
    name:'Industry'
  }, {
    id:2,
    name:'Pursue Professional Degree in Healthcare'
  }, {
    id:3, 
    name:'Graduate School'
  }, {
    id:4, 
    name:'Entrepreneur'
  }, {
    id:5,
    name:'I\'m Not Sure'
  }, {
    id:6,
    name:'Other'
  }];

  $scope.toggleSelection = function toggleSelection (opt, attr) {
    var idx = $scope.form[attr].indexOf(opt)
    if(idx > -1) {
      $scope.form[attr].splice(idx, 1);
    }
    else {
      $scope.form[attr].push(opt);
    }
  };

  $scope.newValue = function(value, attr) {
    console.log('new value', value);
    $scope.form[attr] = value;
    if (value == "Other" && attr == "dfocus") {
      $scope.form.dfocusother = $scope.form.dfocusother; //left side was $scope.form.dfocus.other
    } else if (value != "Other" && attr == "dfocus") {
      $scope.form.dfocusother = null;
    }
    if (!value && attr == "majorHelper") { //if they do not want another major, set other_major = 0
      $scope.form.other_major = null;
    } 
    if (value && attr == "transfer_from_within") {
      $scope.form.transfer_from_outside = 0;
      $scope.form.institution_name = "";
    } else if (value && attr == "transfer_from_outside") {
      $scope.form.transfer_from_within = 0;
      $scope.form.prev_major = "";
    }
  }

  $scope.addMentee = function() {
    $.ajax({
      url: "api/mentee",
      dataType: "json",
          async: false,
      data: {'fname': $scope.form.fname,
             'lname': $scope.form.lname,
             'email': $scope.form.email,
             'phone':$scope.form.phone,
             'pref_comm': $scope.form.prefComm,
             'dfocus': $scope.form.dfocus,
             'dfocusother': $scope.form.dfocusother,
             'international_student': $scope.form.international_student,
             'transfer_from_within': $scope.form.transfer_from_within,
             'prev_major': $scope.form.prev_major,
             'transfer_from_outside': $scope.form.transfer_from_outside,
             'institution_name': $scope.form.institution_name,
             'international_student': $scope.form.international_student,
             'expec_graduation': $scope.form.expec_graduation,
             'other_major': $scope.form.other_major,
             'breadth_track': $scope.form.breadth_track,
             'undergrad_research': $scope.form.undergrad_research,
             'bme_academ_exp': $scope.form.bme_academ_exp,
             'bme_organization': $scope.form.bme_organization,
             'tutor_teacher_program': $scope.form.tutor_teacher_program,
             'international_experience': $scope.form.international_experience,
             'career_dev_program': $scope.form.career_dev_program,
             'post_grad_plan': $scope.form.post_grad_plan,
             'post_grad_plan_desc': $scope.form.post_grad_plan_desc,
             'personal_hobby': $scope.form.personal_hobby
            },
      type: 'POST',
      success: success()
      // error: ajaxError
    }); 
  };

  function success() {
    $scope.$parent.showNext = true;
    $scope.showNext = $scope.$parent.showNext;
  }

}]);


appControllers.controller('RegisterMentorController', ['$scope', '$http', '$filter', '$location',
  function($scope, $http, $filter, $location) {
  $('.ui.radio.checkbox').checkbox();
  $('.ui.checkbox').checkbox();
  $('select.dropdown').dropdown();
  $scope.showNext = $scope.$parent.showNext;
  var validation = {
    fname: {
      identifier  : 'fname',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your first name'
        }
      ]
    },
    lname: {
      identifier  : 'lname',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your last name'
        }
      ]
    },
    email: {
      identifier  : 'email',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your email'
        },{
          type: 'email',
          prompt: 'Please enter a valid email'
        }
      ]
    },
    phone: {
      identifier  : 'phone',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your phone number'
        },
        {
          type   : 'length[10]',
          prompt : "Please enter a correct phone number"
        }
      ]
    },
    prefComm: {
      identifier  : 'prefComm',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your preferred communication method'
        }
      ]
    },
    live_before_tech: {
      identifier  : 'live_before_tech',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter where you lived before'
        }
      ]
    },
    home_country: {
      identifier  : 'home_country',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your home country'
        }
      ]
    },
    expec_graduation: {
      identifier  : 'expec_graduation',
      rules: [
        {
          type   : 'empty',
          prompt : 'Please enter your expected graduation'
        }, 
        {
          type   : 'contains[20]',
          prompt : 'Please enter the year correctly'
        }
      ]
    }

  };
  var settings = {
    // inline: true,
    on: 'blur',
    // transition: 'fade down'
    // onSuccess: successForm,
    // onFailure: failureForm
  };

  $('.ui.form').form(validation, settings);
  // $('.ui.form').form('validate form');

  $('form').submit(function(e){
    e.preventDefault();
    $('.ui.form').form('validate form');

  });

  $scope.form = { 
      dfocus: null, 
      ethnicity: [],
      honor_program: [],
      breadth_track:[],
      bme_organization: [],
      mm_org: [],
      tutor_teacher_program: [],
      bme_academ_exp: [],
      international_experience: [],
      career_dev_program: []
  };

  $scope.yesno = [{
      id:1,
      name: 'Yes',
      value: 1
  }, {
      id:2, 
      name: 'No',
      value: 0
  }];

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

  $scope.breadthTracks = [{
    id:1,
    name:'Pre-health',
    desc: ''
  }, {
    id:2,
    name:'Research Option',
    desc:''
  }, {
    id:3, 
    name:'Minor',
    desc:''
  }, {
    id:4, 
    name:'Certificate',
    desc:''
  }, {
    id:5, 
    name:'Not Sure',
    desc:''
  }];

  $scope.honorPrograms = [{
    id:1,
    name: 'Presidents Scholarship Program'
  }, {
    id:2,
    name: 'Honors Program'
  }, {
    id:3,
    name: 'Grand Challenges'
  }];

  $scope.bmeOrganizations = [{
    id:1,
    name:'Alpha Eta Mu (AEMB)'
  }, {
    id:2,
    name:'Biomedical Engineering Society (BMES)'
  }, {
    id:3,
    name:'Biomedical Research & Opportunities Society (BROS)'
  }, {
    id:4, 
    name:'BMED Futures'
  }, {
    id:5, 
    name:'Engineering World Health (EWH)'
  }, {
    id:6, 
    name:'Medical Device Entrepreneurship Association (MDEA)'
  }, {
    id:7, 
    name:'Pioneer'
  }];

  $scope.menteeMentorOrgs =[{
    id:1,
    name:'Mentor Jackets'
  }, {
    id:2,
    name:'M&M Mentoring'
  }, {
    id:3, 
    name:'Ceismic Academic Mentoring'
  }, {
    id:4, 
    name:'Office of Minority Education (OMED) Mentor'
  }, {
    id:5,
    name:'BMED 1000 Mentor'
  }];

  $scope.tutorTeachPrograms = [{
    id:1, 
    name:'PLUS Leader (Center for Academic Success)'
  }, {
    id:2, 
    name:'1 On 1 Tutoring'
  }, {
    id:3, 
    name:'Tutoring in BME with a Student Organization'
  }, {
    id:4, 
    name:'Ad Hoc Tutoring (That You Arranged on Your Own)'
  }, {
    id:5,
    name:'BMED 1300 Co-Facilitator'
  }, {
    id:6, 
    name:'Undergraduate Grader or Teaching Assistant for a BME Course'
  }];

  $scope.bmeAcademicPrograms = [{
    id:1,
    name:'Inventure Prize'
  }, {
    id:2,
    name:'Design Expo'
  }, {
    id:3,
    name:'Multidisciplinary Capstone Design Course'
  }, {
    id:4, 
    name:'The Clinical Observation and Design Experience (CODE) Course  (BMED 4813)'
  }];

  $scope.internationalPrograms = [{
    id:1, 
    name:'International Plan'
  }, {
    id:2, 
    name:'Study Abroad'
  }, {
    id:3, 
    name:'Work Abroad'
  }, {
    id:4,
    name:'Research Abroad'
  }, {
    id:5, 
    name:'Volunteer Abroad'
  }];

  $scope.careerDevPrograms = [{
    id:1,
    name:'Co-op'
  }, {
    id:2, 
    name:'Internship'
  }, {
    id:3,
    name:'Shadowing in a Medical Environment'
  }];

  $scope.postGradPlans = [{
    id:1,
    name:'Industry'
  }, {
    id:2,
    name:'Pursue Professional Degree in Healthcare'
  }, {
    id:3, 
    name:'Graduate School'
  }, {
    id:4, 
    name:'Entrepreneur'
  }, {
    id:5,
    name:'I\'m Not Sure'
  }, {
    id:6,
    name:'Other'
  }];

  $scope.toggleSelection = function toggleSelection (opt, attr) {
    var idx = $scope.form[attr].indexOf(opt)
    if(idx > -1) {
      $scope.form[attr].splice(idx, 1);
    }
    else {
      $scope.form[attr].push(opt);
    }
  };

  $scope.newValue = function(value, attr) {
    console.log('new value', value);
    $scope.form[attr] = value;
    if (value == "Other" && attr == "dfocus") {
      $scope.form.dfocusother = $scope.form.dfocusother; //left side was $scope.form.dfocus.other
    } else if (value != "Other" && attr == "dfocus") {
      $scope.form.dfocusother = null;
    }
    if (value && attr == "transfer_from_within") {
      $scope.form.transfer_from_outside = 0;
      $scope.form.institution_name = null;
    } else if (value && attr == "transfer_from_outside") {
      $scope.form.transfer_from_within = 0;
      $scope.form.prev_major = null;
    } else if (!value && attr == "transfer_from_outside") {
      $scope.form.transfer_from_outside = 0;
      $scope.form.transfer_from_within = 0;
      $scope.form.institution_name = null;
      $scope.form.prev_major = null;
    }
    if (!value && attr == "majorHelper") { //if they do not want another major, set other_major = 0
      $scope.form.other_major = null;
    } 
    if (!value && attr == "undergrad_research") {
      $scope.form.undergrad_research_desc = null;
    } 
  }

  $scope.addMentor = function addMentor(validation) {
    console.log("addMentor Function");

    if(validation) {
      console.log("validation is true");
      $.ajax({
        url: "api/mentor",
        dataType: "json",
        async: false,
        data: {'fname': $scope.form.fname,
               'lname': $scope.form.lname,
               'email': $scope.form.email,
               'phone':$scope.form.phone,
               'pref_communication': $scope.form.prefComm,
               'dfocus': $scope.form.dfocus,
               'dfocusother': $scope.form.dfocusother,
               'gender': $scope.form.gender,
               'ethnicity': $scope.form.ethnicity,
               'live_before_tech': $scope.form.live_before_tech,
               'live_on_campus': $scope.form.live_on_campus,
               'first_gen_college_student': $scope.form.first_gen_college_student,
               'transfer_from_within': $scope.form.transfer_from_within,  
               'prev_major': $scope.form.prev_major,
               'transfer_from_outside': $scope.form.transfer_from_outside,
               'institution_name': $scope.form.institution_name,
               'international_student': $scope.form.international_student,
               'home_country': $scope.form.home_country,
               'expec_graduation': $scope.form.expec_graduation,
               'honor_program': $scope.form.honor_program,
               'other_major': $scope.form.other_major,
               'breadth_track': $scope.form.breadth_track,
               'undergrad_research': $scope.form.undergrad_research,
               'undergrad_research_desc':$scope.form.undergrad_research_desc,
               'other_organization1':$scope.form.other_organization1,
               'other_organization2':$scope.form.other_organization2,
               'other_organization3':$scope.form.other_organization3, 
               'bme_organization': $scope.form.bme_organization,
               'bme_org_other': $scope.form.bme_org_other,
               'mm_org': $scope.form.mm_org,
               'mm_org_other': $scope.form.mm_org_other,
               'tutor_teacher_program': $scope.form.tutor_teacher_program,
               'tutor_teacher_program_other': $scope.form.tutor_teacher_program_other,
               'bme_academ_exp': $scope.form.bme_academ_exp,
               'bme_academ_exp_other': $scope.form.bme_academ_exp_other,
               'international_experience': $scope.form.international_experience,
               'international_experience_other':$scope.form.international_experience_other,
               'career_dev_program': $scope.form.career_dev_program,
               'career_dev_program_other': $scope.form.career_dev_program_other,
               'post_grad_plan': $scope.form.post_grad_plan,
               'post_grad_plan_desc': $scope.form.post_grad_plan_desc,
               'personal_hobby': $scope.form.personal_hobby
              },
        type: 'POST',
        success: success()
        // error: ajaxError
      });
    }
    console.log("outside if statement" );
  }

  function success() {
    $scope.$parent.showNext = true;
    $scope.showNext = $scope.$parent.showNext;
  }
  
}]);

appControllers.controller('MentorUserAgreementController', ['$scope', '$http', '$location', function($scope, $http, $location) {
  $('.ui.radio.checkbox').checkbox();
  var ind = 0;
  $scope.active = ind;
  $scope.form= {
    "q1": 0,
    "q2": 0,
    "q3": 0,
    "q4": 0,
    "q5": 0,
    "q6": 0,
    "q7": 0,
    "q8": 0,
    "q9": 0,
    "q10": 0,
    "q11": 0,
    "q12": 0,
    "q13": 0,
    "q14": 0,
    "q15": 0
  };
  $scope.yes = false;


  $scope.yesno = [{
      id:1,
      name: 'Yes',
      value: 1
    }, {
      id:2, 
      name: 'No',
      value: 0
    }];

    $scope.yesnoOptIn = [{
      id:1,
      name: 'Yes, I commit to serving as a mentor next fall.',
      value: 1
    }, {
      id:2, 
      name: 'No, I do not wish to serve as a mentor next fall.',
      value: 0
    }];

  $scope.newValue = function(value, attr) {
    $scope.form[attr] = value;
  };

  $scope.setStep = function(index) {
    // if (!$(this).hasClass('disabled')) {
    $('.ui.steps div').removeClass('active');
    $('.ui.steps div:eq(' + index + ')').addClass('active');
    $scope.active = index;
  };

  $scope.allYes = function() {
    var numTrue = 0;
    $.each($scope.form, function(key, value) {
      console.log($scope.form);
      if (value === 1 && value !== 0) {
        numTrue++;
        console.log("true" + value);
      } else {
        console.log("false" + value);
      }
    });
    if (numTrue == 15) {
      $scope.yes = true;
      $location.path('/mentorReg');
      window.scrollTo(0,0);
    }
  };
}]);

appControllers.controller('MentorAliasController', ['$scope', '$http', '$location', function($scope, $http, $location) {
   $scope.aliasNames;
   var color;
   var adjective;
   var animal; 
   var alias;
  $scope.generate = function() {
    $scope.generateClicked = true;
    var validName = false;
    function nameRequest() {
      $http.get('aliasNames.json').success(function(data){
           $scope.aliasNames = data;
          // console.log(Math.floor(Math.random()*$scope.aliasNames[0].color.length));
          var randoNum = Math.random();
          $scope.color = $scope.aliasNames[0].color[Math.floor(randoNum * $scope.aliasNames[0].color.length)].name;
          $scope.hex = $scope.aliasNames[0].color[Math.floor(randoNum * $scope.aliasNames[0].color.length)].hex;
          $scope.adjective = $scope.aliasNames[1].adjective[Math.floor(Math.random() * $scope.aliasNames[1].adjective.length)];
          $scope.animal = $scope.aliasNames[2].animal[Math.floor(Math.random() * $scope.aliasNames[2].animal.length)];
          alias = $scope.color + " " + $scope.adjective + " " + $scope.animal;
          $http.get('api/alias/'+ alias).success( function(data) {
            // adjust error message from GET RED ERROR to be specially defined
            console.log("there is an existing name, can't be used");
            return validName = false;
          })
          .error( function(data) {
            console.log("there's no existing name, this one can be used");
            return validName = true;
          });
          //console.log(validName);
      }); 
    } 
  if (!validName) {
    nameRequest();
  }
  }

  $scope.addAliasName = function() {
    var name = $scope.color + " " + $scope.adjective + " " + $scope.animal;
    console.log(alias);
    console.log(name);
    $.ajax({
          url: "api/alias/" + name,
          dataType: "json",
          async: false,
          data: name,
          type: 'PUT'
          // error: ajaxError
        });
  }
  
}]);

appControllers.controller('DevController', ['$scope', '$http', function($scope, $http) {
  $http.get('json-gen/mentors25.json').success(function(data) {
    $scope.mentors = data;
    console.log($scope.mentors);
  }).
  error(function(data, status, headers, config) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    console.log("Error getting userData");
  });
  
  $scope.postMentors = function() {
    console.log("length: " + $scope.mentors.length);
    $.ajax({
      url: "api/genFauxMentors",
      dataType: "json",
      async: true,
      data: {'mentors': $scope.mentors}, //$scope.mentors
      type: 'POST',
      success: function(data, textStatus, jqXHR) {
        console.log("Posted Mentors");
      }
      //error: $scope.ajaxError
    });
  }
$scope.deleteMentors = function() {
  $.ajax({
      url: "api/deleteMentors",
      async: true,
      type: 'PUT',
      success: function(data, textStatus, jqXHR) {
        console.log("Deleted Mentors");
      }
      //error: $scope.ajaxError
    });
}
$scope.reset = function() {
    $.ajax({
      url: "api/resetUser",
      dataType: "json",
      async: true,
      success: function(result) {
        //data = result;
      },
      type: 'GET'
      // error: ajaxError
    }); 
  }

}]);


