var app = angular.module("formEdit", ["xeditable", "ngMockE2E"]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});

app.controller('Ctrl', function($scope, $filter, $http) {
 $scope.user = {
    id: 1,
    name: 'awesome user',
    status: 2,
    group: 4,
    groupName: 'admin'
  }; 

  $scope.statuses = [
    {value: 1, text: 'status1'},
    {value: 2, text: 'status2'},
    {value: 3, text: 'status3'},
    {value: 4, text: 'status4'}
  ]; 

  $scope.groups = [];
  $scope.loadGroups = function() {
    return $scope.groups.length ? null : $http.get('/groups').success(function(data) {
      $scope.groups = data;
    });
  };

  $scope.showGroup = function() {
    if($scope.groups.length) {
      var selected = $filter('filter')($scope.groups, {id: $scope.user.group});
      return selected.length ? selected[0].text : 'Not set';
    } else {
      return $scope.user.groupName;
    }
  };

  $scope.checkName = function(data) {
    if (data !== 'awesome' && data !== 'error') {
      return "Username should be `awesome` or `error`";
    }
  };

  $scope.saveUser = function() {
    // $scope.user already updated!
    return $http.post('/saveUser', $scope.user).error(function(err) {
      if(err.field && err.msg) {
        // err like {field: "name", msg: "Server-side error for this username!"} 
        $scope.editableForm.$setError(err.field, err.msg);
      } else { 
        // unknown error
        $scope.editableForm.$setError('name', 'Unknown error!');
      }
    });
  };
});

// ---------------- mock $http requests --------------------
app.run(function($httpBackend) {
  $httpBackend.whenGET('/groups').respond([
    {id: 1, text: 'user'},
    {id: 2, text: 'customer'},
    {id: 3, text: 'vip'},
    {id: 4, text: 'admin'}
  ]);
    
  $httpBackend.whenPOST(/\/saveUser/).respond(function(method, url, data) {
    data = angular.fromJson(data);
    if(data.name === 'error') {
      return [500, {field: 'name', msg: 'Server-side error for this username!'}]; 
    } else {
      return [200, {status: 'ok'}]; 
    }
  });
});