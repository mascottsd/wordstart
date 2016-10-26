<html>
<head>
<title>Word Search</title>
<!-- include AngularJS -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
<!-- include Bootstrap -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="style.css" rel="stylesheet">
</head>

<body ng-app="jsApp" ng-controller="jsCtrl">

<div class="container">
	<h1>Input some text here:</h1>
	<input placeholder="Please enter text..." autofocus ng-model="txt" ng-change="GetWords()" />
	<span ng-show="matches.length">{{cnt}} words match</span>

	<table class="table table-hover">
		<tr ng-repeat="m in matches" ng-show="$index < showLen">
			<td style="width: 1px">{{$index + 1}}</td>
			<td>{{ m }}</td>
			<td ng-click="DefineWord(m)">Definition</td>
			<td>Spanish</td>
			<td>French</td>
		</tr>
		<tr ng-show="matches.length > showLen" >
			<td colspan="2" ng-click="showLen = showLen + addLen" class="more">
			Show {{ min(addLen, matches.length - showLen) }} more...
			</td>
		</tr>
	</table>
</div>

<script>
var app = angular.module('jsApp', []);

app.controller('jsCtrl', function ($scope, $http, $timeout) {
	$scope.min = Math.min;
	$scope.addLen = 10;
	//---- GetWords
	$scope.GetWords = function() {
		$scope.showLen = 10;
		var url = "words.php?q=" + $scope.txt;
		$http.get(url).success(function(data) {
			$scope.cnt = data.cnt;
			$scope.matches = data.matches;
		});
	}
	//---- DefineWord
	$scope.DefineWord = function() {
		var url = "https://glosbe.com/gapi/translate?from=eng&dest=fr&format=json&phrase="+ $scope.txt +"&pretty=true";
		$http.get(url).success(function(data) {
			console.log(data);
		});
	}
});
</script>

</body>
</html>