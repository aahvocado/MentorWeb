[
  '{{repeat(25, 25)}}',
  {
    first_name: '{{firstName()}}',
    last_name: '{{surname()}}',
    username: function (tags) {
      return (this.first_name.substring(0,1).toLowerCase() + this.last_name.toLowerCase());
    },
    color: '{{random("Auburn", "Aqua", "Golden", "Rose", "Purple", "Emerald", "Orange", "Navy", "Teal", "Pink", "Maroon", "Forest", "Salmon", "Magenta", "Midnight", "Silver")}}',
    adj: '{{random("Brainy", "Bright", "Careful", "Brave", "Delightful", "Calm", "Jolly", "Fantastic", "Good", "Graceful", "Kind")}}',
    animal: '{{random("Deer", "Eagle", "Hawk", "Bear", "Kangaroo", "Lemur", "Raven", "Lion", "Owl", "Panda", "Robin")}}',
    alias: function (tags) {
      return (this.color + " " + this.adj + " " + this.animal);
    },
    email: function (tags) {
      // Email tag is deprecated, because now you can produce an email as simple as this:
      return (this.first_name.substring(0,1).toLowerCase() + this.last_name.toLowerCase() + '@gatech.edu');
    },
    phone: '{{phone("xxxxxxxxxx")}}',
    pref_communication: '{{random("Phone", "Email")}}',
    opt_in: '{{random("0", "1")}}',
    approvedb_by: 'NULL',
    depth_focus: '{{random("Neuroengineering","Cardiovascular-systems", "Biomechanics", "Biomaterials", "Medical-imaging")}}',
    post_grad_plan: '{{random("Graduate School", "Work in industry")}}',
    post_grad_plan_desc: '{{random("Study study study", "Graduate School")}}',
    expec_graduation: '{{random("Fall 2014", "Spring 2014", "Fall 2015", "Spring 2015")}}',
    transfer_from_outside: '{{nrandom("0", "1")}}',
    institution_name: '{{random("Georgia Institute of Technology", "Georgia State University")}}',
    transfer_from_inside: '{{random("0", "1")}}',
    prev_major: '{{random("Architecture", "Computer Science", "Computational Media")}}',
    international_student: '{{random("0", "1")}}',
    first_gen_college_student: '{{random("0", "1")}}',
    live_before_tech: '{{random("Marietta", "Duluth", "New York", "Boston", "Los Angeles")}}',
    live_on_campus: '{{random("0", "1")}}',
    undergrad_research: '{{random("0", "1")}}',
    undergrad_research_desc: '{{random("Biomedical Tissue Adaptation", "Vitamin Effect on Cognition")}}',
    home_country: '{{random("China", "India", "United States", "Spain")}}',
    personal_hobby: '{{random("Rock Climbing", "Biking", "Soccer")}}',
    gender: '{{random("female", "male")}}',
    other_major: '{{random("Architecture", "Computer Science", "Computational Media")}}',
    breadth_track: function(tags) {
      return [{name: random("Pre-health", "Research", "Minor", "Certifcate"),
    desc: random("Descripton1", "Description2")}];
    },
    bme_organization: function(tags) {
      return [{name: random("Alpha Eta Mu (AEMB)", "Biomedical Engineering Society (BMES)", "Biomedical Research & Opportunities Society (BROS)", "BMED Futures", "Engineering World Health (EWH)", "Medical Device Entrepreneurship Association (MDEA)", "Pioneer"),
    desc: random("descripton1", "description2")}];
    },
    tutor_teacher_program: function(tags) {
      return [{name: random("PLUS Leader (Center for Academic Success)", "1 On 1 Tutoring", "Tutoring in BME with a Student Organization", "Ad Hoc Tutoring (That You Arranged on Your Own)", "BMED 1300 Co-Facilitator", "Undergraduate Grader or Teaching Assistant for a BME Course"),
    desc: random("descripton1", "description2")}];
    },
    bme_academ_exp: function(tags) {
      return [{name: random("Inventure Prize", "Design Expo", "Multidisciplinary Capstone Design Course", "The Clinical Observation and Design Experience (CODE) Course (BMED 4813)"),
    desc: random("descripton1", "description2")}];
    },
    international_experience: function(tags) {
      return [{name: random("International Plan", "Study Abroad", "Work Abroad", "Research Abroad","Volunteer Abroad"),
    desc: random("descripton1", "description2")}];
    },
    career_dev_program: function(tags) {
      return [{name: random("Co-op", "Internship", "Shadowing in a Medical Environment"),
    desc: random("descripton1", "description2")}];
    }
  }
]

//Singular Mentor gen

[
  '{{repeat(120, 120)}}',
  {
    firstName: '{{firstName()}}',
    lastName: '{{surname()}}',
    uid: function (tags) {
      return (this.firstName.substring(0,1).toLowerCase() + this.lastName.toLowerCase());
    },
    color: '{{random("auburn", "aqua", "golden", "rose", "purple", "emerald", "orange", "navy", "teal", "pink", "maroon", "forest", "salmon", "magenta", "midnight", "silver")}}',
    adj: '{{random("brainy", "bright", "careful", "brave", "delightful", "calm", "jolly", "fantastic", "good", "graceful", "kind")}}',
    animal: '{{random("deer", "eagle", "hawk", "bear", "kangaroo", "lemur", "raven", "lion", "owl", "panda", "robin")}}',
    alias: function (tags) {
      return (this.color + " " + this.adj + " " + this.animal);
    },
    email: function (tags) {
      // Email tag is deprecated, because now you can produce an email as simple as this:
      return (this.firstName.substring(0,1).toLowerCase() + this.lastName.toLowerCase() + '@gatech.edu');
    },
    phone: '{{phone()}}',
    commMethod: '{{random("phone", "email")}}',
    optIn: '{{random("0", "1")}}',
    approvedbBy: 'NULL',
    depthFocus: '{{random("neuroengineering","cardiovascular-systems", "biomechanics", "biomaterials", "medical-imaging")}}',
    postGradPlan: '{{random("Graduate School")}}',
    postGradPlanDesc: '{{random("Study study study")}}',
    expectedGraduation: '{{random("Fall 2014", "Spring 2014", "Fall 2015", "Spring 2015")}}',
    transferFromOutside: '{{random("0", "1")}}',
    institutionName: '{{random("Georgia Institute of Technology")}}',
    transferFromInside: '{{random("0", "1")}}',
    prevMajor: '{{random("Architecture", "Computer Science", "Computational Media")}}',
    intnlStudent: '{{random("0", "1")}}',
    firstGenCollegeStudent: '{{random("0", "1")}}',
    liveBeforeTech: '{{random("Marietta", "Duluth", "New York", "Boston", "Los Angeles")}}',
    liveOnCampus: '{{random("0", "1")}}',
    undergradResearch: '{{random("0", "1")}}',
    undergradResearchDesc: '{{random("Biomedical Tissue Adaptation")}}',
    homeCountry: '{{random("China", "India", "United States", "Spain")}}',
    personalHobby: '{{random("Rock Climbing")}}',
    gender: '{{random("female", "male")}}',
    otherMajor: '{{random("Architecture", "Computer Science", "Computational Media")}}'
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

//Delete mentors not in Admin and Mentee tables.. doesn't work as of right now
DELETE WHERE username not IN (SELECT username FROM Mentee)UNION(SELECT username FROM Admin);
