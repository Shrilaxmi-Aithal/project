<!DOCTYPE html>
<html>
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
	<link rel="stylesheet" href="css/welcome.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<body ng-app="myApp" ng-controller="myCtrl">
		<div class="postbtn"> 
		    <input type="text" ng-model="first">
		    <button ng-click="myFunc()" >Post</button><br>
		</div>

		<ul>
	        <li ng-repeat="name in myTxt track by $index" ng-show="name" style="list-style:none;">
	          	<div class="container">
	        		<div class="col-md-4">
			        <div class="table-responsive">
			        	<table class="table table-condensed">
			          		<tr><td class="col-md-6">  {{name}} </td>
			           		<td class="col-md-3"> <button ng-click="countName(name)" >like</button></td>
			          		<td class="col-md-3">	{{ counter[$index]}}</td>
			      		</tr></table>
			      	</div></div>
			  </div>
	        </li>
	    </ul>

		<script>
			var app = angular.module("myApp", []);
	
			app.controller("myCtrl", function($scope, $http) {
		 		var init = function () {
					$scope.myTxt = [];
					$scope.counter = [];
					$scope.countit = 0
					$http({
						url : 'http://localhost:8081/api/users',
						method : 'get',
						headers: {'Content-Type': 'application/json'}
					}).then(function mysu(response){
						if(response.status == 200) {
							console.log("success");
							$scope.returndata = (response);
							console.log("h"+$scope.returndata);
						
							angular.forEach($scope.returndata,function(item,index){
								angular.forEach(item,function(inneritem,index){
									if(item == null) {
										console.log("err");
									} else {
										console.log("item: "+inneritem);
										
										$scope.myTxt.push(item[$scope.countit]['data']); 
										$scope.counter.push(item[$scope.countit]['count']); 
										console.log("data: "+item[$scope.countit]['data']);
										console.log("counter: "+item[$scope.countit]['count']);
										$scope.countit = $scope.countit + 1;
										console.log("countit: "+$scope.countit);
										
									}
								});
							});
							
					    } else {
					    	console.log("no response");
					    }
					},function (response){
						console.log("error");
						
					});
				}
				init();
				
				$scope.myFunc = function () {
				
					$scope.myTxt.push($scope.first); 
					var data = JSON.stringify({
			          			first: $scope.first
			        });
					console.log("data:"+data);
					
					$http({
						url : 'http://localhost:8081/api/user',
						method : 'post',
						data : data,
						headers: {'Content-Type': 'application/json'}

					}).then(function mySuccess(response){
						console.log("data posted");
					}, function myError(response) {
						console.log("error");
					});
				}

				$scope.countName = function (name) {
					
					var data = JSON.stringify({
						name: name
					});
					console.log("update data:"+data);

					$http({
						url : 'http://localhost:8081/api/updateuser',
						method : 'post',
						data : data
					}).then(function mySuccess(response){
						console.log("data updated");
						init();
					}, function myError(response) {
						console.log("error");
					});

				}
			});
		</script>
	</body>
</html>



