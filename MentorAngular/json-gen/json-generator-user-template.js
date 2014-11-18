[
  '{{repeat(30, 30)}}',
  {
    index: '{{index()}}',
    firstName: '{{firstName()}}',
    lastName: '{{surname()}}',
    color: '{{random("auburn", "aqua", "golden", "rose", "purple", "emerald", "orange", "navy", "teal", "pink", "maroon", "forest", "salmon", "magenta", "midnight", "silver")}}',
    adj: '{{random("brainy", "bright", "careful", "brave", "delightful", "calm", "jolly", "fantastic", "good", "graceful", "kind")}}',
    animal: '{{random("deer", "eagle", "hawk", "bear", "kangaroo", "lemur", "raven", "lion", "owl", "panda", "robin")}}',
    uid: function (tags) {
      return (this.firstName.substring(0,1).toLowerCase(); + this.lastName.toLowerCase(););
    },
    email: function (tags) {
      // Email tag is deprecated, because now you can produce an email as simple as this:
      return (this.firstName.substring(0,1).toLowerCase(); + this.lastName.toLowerCase(); + '@gatech.edu');
    },
    phone: '{{phone()}}',
    breadthTrack: '{{random("pre-health", "research", "minor", "certificate")}}',
    depthFocus: '{{random("neuroengineering","cardiovascular-systems", "biomechanics", "biomaterials", "medical-imaging")}}',
    gender: '{{random("female", "male")}}',
    academicIntExp: '{{random("studied abroad", "transfer from china", "transfer from germany")}}',
    futurePlans: '{{random("graduate studies", "industry")}}',
    commMethod: '{{random("phone", "email")}}'
  }
]

  $.getJSON('json-gen/users.json', function(data) {
    var myData = data;
    console.log(myData);
    $.ajax({
      url: "http://dev.m.gatech.edu/d/mosborne8/w/mentoringweb-ng/content/api/gen_faux_users",
      dataType: "json",
          async: false,
      data: myData,
      type: 'POST',
          error: ajaxError
    });
  });


  //Old
  [
  '{{repeat(0, 30)}}',
  {
    index: '{{index()}}',
    firstName: '{{firstName()}}',
    lastName: '{{surname()}}',
    uid: function (tags) {
      return (this.firstName.substring(0,1) + this.lastName);
    },
    email: function (tags) {
      // Email tag is deprecated, because now you can produce an email as simple as this:
      return (this.firstName.substring(0,1) + this.lastName + '@gatech.edu');
    },
    phoneNumber: '+1 {{phone()}}',
    commMethod: '{{random("phone", "email")}}',
    color: '{{random("color-1", "color-2", "color-3", "color-4")}}'
  }
]