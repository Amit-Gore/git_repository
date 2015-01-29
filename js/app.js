/**
 * AngularJS Tutorial 1
 * @author Nick Kaye <nick.c.kaye@gmail.com>
 */

/**
 * Main AngularJS Web Application
 */

var app = angular.module('tutorialWebApp', ['ngRoute','ui.bootstrap','angularUtils.directives.dirPagination','ngAnimate']);


/*********************************Services Written by Amit Gore****************************************/
app.factory('search_to_book', function() {
 var doc_data = {}
 function set(data) {
   doc_data = data;
 }
 function get() {
  return doc_data;
 }

 return {
  set: set,
  get: get
 }

});

/***********************************end of services*********************************************/

/*** Configure the Routes*/
app.config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    // Home
	.when("/", {templateUrl: "webpage/index.html", controller: "PageCtrl"})
    .when("/search/:param1-:param11", {
    	templateUrl: "webpage/search.html", 
    	controller: "doctor_search"
    		})
    .when("/book_appointment/:slot/:date/:doctor", {
    	templateUrl: "webpage/docPrimaryInfo.html", 
    	controller: "book_appointment"
    		})
	.when("/primary_info", {templateUrl: "webpage/docPrimaryInfo.html", controller: "PageCtrl"})
    // login Pages
	.when("/new_user", {templateUrl: "webpage/login/new_user.html", controller: "PageCtrl"})
	.when("/patient", {templateUrl: "webpage/login/patient.html", controller: "PageCtrl"})
	.when("/doctor", {templateUrl: "webpage/login/doctor.html", controller: "PageCtrl"})
	.when("/forgotp", {templateUrl: "webpage/login/forgotp.html", controller: "PageCtrl"})
	.when("/morelocation", {templateUrl: "webpage/more/morelocation.html", controller: "BlogCtrl"})
	.when("/morespeciality", {templateUrl: "webpage/more/morespeciality.html", controller: "BlogCtrl"})
	.when("/moreclinic", {templateUrl: "webpage/more/moreclinic.html", controller: "BlogCtrl"})
	
	
	
	//.when("/primary_info", {templateUrl: "webpage/about.html", controller: "PageCtrl"})
    .when("/about", {templateUrl: "webpage/about.html", controller: "PageCtrl"})
    .when("/faq", {templateUrl: "webpage/faq.html", controller: "PageCtrl"})
    .when("/pricing", {templateUrl: "webpage/pricing.html", controller: "PageCtrl"})
    .when("/services", {templateUrl: "webpage/services.html", controller: "PageCtrl"})
    .when("/contact", {templateUrl: "webpage/contact.html", controller: "PageCtrl"})
    // Blog
    .when("/blog", {templateUrl: "webpage/blog.html", controller: "BlogCtrl"})
    .when("/blog/post", {templateUrl: "webpage/blog_item.html", controller: "BlogCtrl"})
    // else 404
    .otherwise("/404", {templateUrl: "webpage/404.html", controller: "PageCtrl"});
}]);

/**
 * Controls the Blog
 */
app.controller('BlogCtrl', function (/* $scope, $location, $http */) {
  console.log("Blog Controller reporting for duty.");
});

/**
 * Controls all other Pages
 */
app.controller('PageCtrl', function ( $scope, $location, $http, $routeParams ) {
	console.log("Page Controller reporting for duty.");

  // Activates the Carousel
  $('.carousel').carousel({
    interval: 5000
  });

  // Activates Tooltips for Social Links
  $('.tooltip-social').tooltip({
    selector: "a[data-toggle=tooltip]"
  })
});




app.controller('PhoneListCtrl', function ($scope) {
  $scope.phones = [
    {'name': 'Nexus S',
     'snippet': 'Fast just got faster with Nexus S.'},
    {'name': 'Motorola XOOM™ with Wi-Fi',
     'snippet': 'The Next, Next Generation tablet.'},
    {'name': 'MOTOROLA XOOM™',
     'snippet': 'The Next, Next Generation tablet.'}
  ];
});


// to toggle
app.controller('mainController', function($scope) {
  
  // set the default states for lions and cranes
  $scope.lions = false;
  $scope.cranes = false;
});


/*----------------------------------------------CONTROLLERS WRITTEN BY AMIT GORE--------------------------------------------------------------------*/


app.controller('patient_registration',function($scope,$rootScope,$http,$location){
	//alert($location.path());
	$scope.url= 'serverside/registration/patient_registration.php';
	$scope.p_registrationform = {};
	
	//defining submit form function
	$scope.p_registrationform.submitForm=function(){
		console.log("Submitting the form");
		var dataObject = {
				fname : $scope.p_registrationform.fname
				,lname : $scope.p_registrationform.lname
				,mobile : $scope.p_registrationform.mobile
				,password : $scope.p_registrationform.password
		};
		console.log(dataObject);
		var responsePromise = $http.post($scope.url,dataObject,{});
		responsePromise.success(function(dataFromServer, status, headers, config) {
			  alert("success");
	          console.log(dataFromServer);
	       });
	        responsePromise.error(function(data, status, headers, config) {
	          alert("Submitting form failed!");
	       });
		
		
	     }//submitForm function ends
	
	
});




/**************************************************CONTROLLERS RELATED TO SEARCH RESULT PAGE*********************************************************/
app.controller('doctor_search',function($scope,$routeParams,$rootScope,$http){
	
	var doc_name = $routeParams.param11;
	
	$scope.url= 'serverside/search/search_result.php'; // The url of php file which is intended to populate $scope with the help of AJAX call
	
	 var search_by_url=function(doc_name){
		    //Creating the http post request here
			$http.post($scope.url,{"data" : doc_name}).
			success(function(data,status){
				//alert('success');
				$scope.status=status;
				$rootScope.cards = data;
				$scope.result= data;
			}).
			error(function(data,status){
				alert('Failure');
				$scope.data = data||"Request failed";
				$scope.status = status;
			});
		 }
	if(doc_name) //if there is a parameter in the url,which would be doctor name
		{
		$scope.keywords=doc_name;
		search_by_url(doc_name);   //call the function
			
		}
	
	$scope.search = function(){
		//Creating the http post request here
		$http.post($scope.url,{"data" : $scope.keywords}).
		success(function(data,status){
			//alert('success');
			$scope.status=status;
			$rootScope.cards = data;
			$scope.result= data;
			//console.log($scope.data);
			window.location.replace('#/search/docname-'+$scope.keywords);
			//window.location.replace('#/search');
			//$location.path('#/search');
			
			
		}).
		error(function(data,status){
			alert('Failure');
			$scope.data = data||"Request failed";
			$scope.status = status;
		});
	}
	
	
/*	$scope.show_calandar = function(doc_id){
		$scope.url='serverside/search/search_result_calandar.php';
		//alert(doc_id);
		
		//Creating the http post request here
		$http.post($scope.url,{"data" :doc_id}).
		success(function(data,status){
			//alert('success');
			var chutiya = doc_id;
			$scope.status=status;
			$scope.slots=data;///check this and if not done upload files and send the link to abhishek
			//$scope.slots.splice(1,0,doc_id);
			$scope.result= data;
		}).
		error(function(data,status){
			alert('Failure');
			$scope.data = data||"Request failed";
			$scope.status = status;
		});
	}*/
	
});
/**************************************************end of controllers related to SEARCH RESULT page*********************************************************/

/*
 * Book Appointment Related controller
 * Author:Amit Gore
 * */
app.controller('book_appointment',function($scope,$routeParams,$rootScope,$http){
	
	
	$scope.slot = $routeParams.slot;
	$scope.date=$routeParams.date;
	$scope.doc_id=$routeParams.doctor;
	$scope.url= 'serverside/bookappointment/docdata_bookappointment_page.php';
	
	$http.post($scope.url,{"data" : $scope.doc_id}).
	success(function(data,status){
		//alert('success');
		$scope.status=status;
		$scope.doc_data = data;
		
	}).
	error(function(data,status){
		alert('Failure');
		$scope.data = data||"Request failed";
		$scope.status = status;
	});
	
	





});


/*------------------------------------------------end of controllers WRITTEN BY AMIT GORE----------------------------------------------------------------*/




//Angular App Module and Controller

app.controller('MapCtrl', function ($scope) {

    var mapOptions = {
        zoom: 7,
        center: new google.maps.LatLng(18.5203,73.8567),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false,
				 mapTypeControlOptions: {
				 style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
				 },
				 navigationControl: true,
				 navigationControlOptions: {
				 style: google.maps.NavigationControlStyle.SMALL
				 }
    }
	
	var bounds = new google.maps.LatLngBounds();
	
    $scope.map = new google.maps.Map(document.getElementById('map'), mapOptions);

    $scope.markers = [];
    
    var infoWindow = new google.maps.InfoWindow();
    
    var createMarker = function (info){
        
        var marker = new google.maps.Marker({
            map: $scope.map,
            position: new google.maps.LatLng(info.lat, info.long),
			
            title: info.city
        });
        marker.content = '<div class="infoWindowContent">' + info.desc + '</div>';
        bounds.extend( marker.position);
		//map.fitBounds(bounds);
		//console.log(bounds);	
        google.maps.event.addListener(marker, 'click', function(){
            infoWindow.setContent('<h2>' + marker.title + '</h2>' + marker.content);
            infoWindow.open($scope.map, marker);
        });
        
        $scope.markers.push(marker);
        
    }  
    
    for (i = 0; i < cities.length; i++)
	{
        createMarker(cities[i]);
    }

    // $scope.openInfoWindow = function(e, selectedMarker){
        // e.preventDefault();
        // google.maps.event.trigger(selectedMarker, 'click');
    // }

});

var cities = [
    {
        city : 'Pune',
        desc : 'This is the best city in the world!',
        lat : 18.5203,
        long : 73.8567
    },
    {
        city : 'Ahmadnagar',
        desc : 'This city is aiiiiite!',
        lat : 19.0800,
        long : 74.7300
    },
	{
        city : 'Nashik',
        desc : 'This city is aiiiiite!',
        lat : 20.0000,
        long : 73.7800
    },
	{
        city : 'Delhi',
        desc : 'This city is aiiiiite!',
        lat : 28.6139,
        long : 77.2089
    }
];




// form validations

app.controller('formValidation', function($scope) {

	// function to submit the form after all validation has occurred			
	$scope.submitForm = function(isValid) {

		// check to make sure the form is completely valid
		if (isValid) { 
			alert('our form is amazing');
		}

	};

});

// logform

app.controller('FormCtrl', ['$scope', function($scope) {
  // hide error messages until 'submit' event
  $scope.submitted = false;
  // hide success message
  $scope.showMessage = false;
  // method called from shakeThat directive
  $scope.submit = function() {
    // show success message
    $scope.showMessage = true;
  };
}])

app.directive('shakeThat', ['$animate', function($animate) {

  return {
    require: '^form',
    scope: {
      submit: '&',
      submitted: '='
    },
    link: function(scope, element, attrs, form) {
      // listen on submit event
      element.on('submit', function() {
        // tell angular to update scope
        scope.$apply(function() {
          // everything ok -> call submit fn from controller
          if (form.$valid) return scope.submit();
          // show error messages on submit
          scope.submitted = true;
          // shake that form
          $animate.addClass(element, 'shake', function() {
            $animate.removeClass(element, 'shake');
          });
        });
      });
    }
  };

}]);






app.controller('staticpages',function($scope){
	
	$scope.static_page = [
	{
	'images' : 	'images/marker-48.png',
	'titles' : 	' Location',
	'detail' :  ['Aundh','Baner','Shivaji nagar','Pashan','Sinhgad Road','Narhe','Kothrud','Alandi','Pimple Saudaghar'],
	'more':'morelocation'
	},
	{
	'images' : 	'images/tag-48.png',
	'titles' : 	' Speciality',
	'detail' :  ['Endocrinology','Diebetology','Nephrology','Urology','Joint replacement','Spine Surgery','Rheumatology','Neurology','Paed. Neurology'],
	
	'more':'morespeciality'
	},
	{
	'images' : 	'images/hospital-48.png',
	'titles' : 	' Clinic',
	'detail' :  ['HealthServe Community Clinic','Aditya Birla Clinic','Noble Hospital','Ruby Hall Clinic','Jehangir Hospital','Apollo Hospital','Deenanath Mangeshkar Hospital','Sancheti Hospital','Karve Childrens Hospital'],
	'more':'moreclinic'
	}];
	

});

// for static pages
app.controller('speciality',function($scope,$filter){

	$scope.specialitys = ['Endocrinology','Diebetology','Nephrology','Urology','Joint replacement','Spine Surgery','Rheumatology','Neurology','Paed. Neurology','Neuro Surgery','Cardiology','Cardiac Surgery','Oncology','Onco Surgery','Dermatology','Maxillo Facial Surgeon','Retina Surgeon','Gastroenterology','Gastro. Surgeon','Heamatology','Hapatologist','Barriatric Surgery','Orthodontist','Vascular Surgery','Endoscopic Surgery']
	;

});

app.controller('location',function($scope,$filter){

	$scope.locations = 
		  ['Aundh','Alandi','Akhurdi','Ambegoan','Baner','Balewadi','Bavdhan','Chandani Chawk','Kothrud','Karve Road','Nal Stop','Ganesh Nager','Sinhgad Road','Vadgoan','Anand Nagar','Swargate','Camp','Koregoan Park','Viman Nagar','Shaniwar Wada','Deccan']
	;

});

app.controller('clinic',function($scope,$filter){

	$scope.clinic =  ['HealthServe Community Clinic','Aditya Birla Clinic','Noble Hospital','Ruby Hall Clinic','Jehangir Hospital','Apollo Hospital','Deenanath Mangeshkar Hospital','Sancheti Hospital','Karve Childrens Hospital']	;

});

app.filter('mySort', function() {
    return function(input) {
      return input.sort();
    }
  });
