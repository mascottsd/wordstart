<html>
<head>
	<title>Word Search</title>
	<!-- include Bootstrap & Font Awesome -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
	<link href="style.css" rel="stylesheet">
</head>

<body ng-app="jsApp" ng-controller="jsCtrl">

	<div class="container">
		<h1>Word Searcher &amp; Dictionary</i></h1>
		<input type="search" placeholder="Enter text" autofocus ng-model="txt" ng-change="GetWords()" />
		<i ng-show="isSearching" class="fontMed fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<span class="cloak" ng-show="!isSearching && txt">{{cnt ? cnt : "No"}} {{cnt > 1 ? 'words begin' : 'word begins'}} with "{{txt}}"</span>

		<table ng-show="txt" class="table table-hover cloak">
				<tr ng-repeat="m in matches" ng-show="$index < showLen" ng-click="DefineWord(m)">
				<td class="colIdx">{{$index + 1}}</td>

				<td>{{ m }}</td>

				<td class="more maxWidth">
					<div class="colDef">Definition <i id="icon-{{m}}" class="fontMed fa fa-caret-down"></i></div>
					<span ng-show="isWorking[m]" class="fontMed">
						<i class="fontMed fa fa-refresh fa-spin fa-3x fa-fw"></i>
						<i class='working'>Retrieving...</i>
					</span>
					<div class="hideMe" id="dd-{{m}}"></div>
				</td>
			</tr>
			<tr ng-show="matches.length > showLen" >
				<td colspan="3" ng-click="showLen = showLen + addLen" class="more">
					Show {{ min(addLen, matches.length - showLen) }} more...
				</td>
			</tr>
		</table>
	</div>

<!-- include AngularJS & jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
var app = angular.module('jsApp', []);

app.controller('jsCtrl', function ($scope, $http, $timeout) {
	ctrlScope = $scope;
	$scope.min = Math.min;
	$scope.addLen = 10;
	$scope.isWorking = {};
	$scope.cnt = '';
	//---- GetWords
	$scope.GetWords = function() {
		$scope.showLen = 10;
		$scope.isSearching = true;
		var url = "words.php?w=" + $scope.txt;
		$http.get(url).success(function(data) {
			$scope.isSearching = false;
			$scope.cnt = data.cnt;
			$scope.matches = data.matches;
		});
	}
	//---- DefineWord
	$scope.DefineWord = function(word) {
		$('#icon-'+ word).removeClass();
		var elem = $('#dd-'+ word);
		//console.log('w: '+ word);
		if (elem.is(':visible')) {
			elem.slideUp();
			$('#icon-'+ word).addClass("fontMed fa fa-caret-down");
		} else {
			elem.slideDown();
			$('#icon-'+ word).addClass("fontMed fa fa-caret-right");
		}

		if ( elem.text() ) {
			return;
		}
		$scope.isWorking[word] = true;

		//var url = "https://glosbe.com/gapi/translate?from=eng&dest=fr&format=json&phrase="+ $scope.txt;
		var url = "https://glosbe.com/gapi/translate";
		//var url = "http://www.dictionaryapi.com/api/v1/references/collegiate/json/"+ $scope.txt +"?key=a3d8f3cb-7e8e-4194-8c34-0649de4d087b";
		// $http.get(url).success(function(data) {
		// 	console.log(data);
		// });
		$.ajax({
			url: url,
		    jsonp: "cbFunc", // The name of the callback function
		    dataType: "jsonp", // Tell jQuery we're expecting JSONP
		    // Tell what we want and that we want JSON
		    data: {
		    	from: "eng",
		    	dest: "es",
		    	format: "json",
		    	callback: "cbFunc",
		    	phrase: word,
		    }
		  });
	}

	$(".cloak").removeClass('cloak');
});

function cbFunc(data)
{
	var elem = $('#dd-'+ data.phrase);
	if (!elem)
		return;
	//console.log(data);

	var mlist = [];
	$.each(data.tuc, function(i, t) {
		console.log(t);
		if (t.meanings) {
			console.log(t.meanings[0]);
			mlist.push(t.meanings[0].text);
		}
	});

	if (mlist.length)
		elem.html('<ul><li>'+ mlist.join('<li>') +'<ul>');
	else
		elem.html("<i class='working'>-- No definitions found --</i>");
	ctrlScope.isWorking[data.phrase] = false;
	ctrlScope.$apply();
}
</script>

</body>
</html>