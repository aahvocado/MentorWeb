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

  $.getJSON('json-gen/users.json', function(data) {
    var myData = data;
    console.log(myData);
    $.ajax({
      url: "http://dev.m.gatech.edu/d/aarrowood3/w/mentoringweb-ng/content/api/gen_faux_users",
      dataType: "json",
          async: false,
      data: myData,
      type: 'POST',
          error: ajaxError
    });
  });