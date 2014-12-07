var appformEdit = angular.module("formEdit", ["xeditable"], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});
appformEdit.run(function(editableOptions) {
    editableOptions.theme = 'bs3';
});

appformEdit.controller('formEditController', function($scope, $filter, $http, IAPIUserDetailService) {
    // I contain the User (Detail) to be rendered.
    $scope.user = [];
    $scope.error = [];
    $scope.hostUrl = "http://localhost:9091/";
   
    $scope.editableForm = [];
    $scope.editableForm.$validusername = false;
    $scope.init = function(id) {
        loadRemoteData(id);
        $scope.loadCountryList();
    };

    // I apply the remote data to the local scope.
    function applyRemoteData(userDetail) {
        $scope.user = userDetail;
        $scope.user.status = $scope.user.p_country;
        
    }

    function applyError() {
        $scope.error.value = true;

    }

    // I load the remote data from the server.
    function loadRemoteData(id) {
        $scope.editableForm.$validusername = true;
        // The IAPIUserDetailService returns a promise.
        IAPIUserDetailService.getUserDetails(id, $scope.hostUrl)
                .then(
                        function(userDetail) {
                            userDetail.id = id;

                            applyRemoteData(userDetail);
                        }
                )
                ;
    }


    $scope.checkUserName = function(userName) {
        IAPIUserDetailService.checkUserName(userName, $scope.user.id, $scope.hostUrl)
                .then(
                        function(userResponse) {
                            console.log(userResponse);
                            if (userResponse.code == 21) {
                                $scope.editableForm.$invalid = true;
                                $scope.editableForm.$validusername = false;
                                $scope.editableForm.$setError('username', 'username is not available. Please choose another one');
                            } else {
                                $scope.editableForm.$invalid = false;
                                $scope.editableForm.$validusername = true;
                                $scope.editableForm.$setError('username', '');
                            }
                        });

    };



    $scope.saveUser = function() {
        // $scope.user already updated!
        console.log('------save user');
        console.log($scope.user);
        IAPIUserDetailService.saveUserDetails($scope.user, $scope.hostUrl)
                .then(
                        function(userResponse) {
                            console.log(userResponse.code);
                            if (userResponse.code == 21) {
                                $scope.editableForm.$setError('username', userResponse.message);
                            }
                        });
    };
    $scope.countryList = [];
    $scope.loadCountryList = function() {
        return $scope.countryList.length ? $scope.user.p_country : $http.get($scope.hostUrl + "iauser/address/getcountrylist").success(function(data) {
            $scope.countryList = data;
            
        });
    };
    
    
    $scope.showCountry = function(type) {
    var id = type;    
    if($scope.countryList.length) {
      var selected = $filter('filter')($scope.countryList, {id: id});
      return selected.length ? selected[0].name : 'Not set';
    } else {
      return $scope.user.p_country_name;
    }
  };

//    $scope.$watch('user.p_country', function(newVal, oldVal) {
//        if($scope.countryList.length >0){
//            console.log('hello');
//        }else{
//            $scope.countryList = $scope.loadCountryList();
//            console.log($scope.countryList);
//        }
//        if (newVal !== oldVal) {
//            var selected = $filter('filter')($scope.countryList, {id: $scope.user.p_country});
//    
//            $scope.user.p_country_name = selected.length ? selected[0].name : $scope.user.p_country_name;
//        }
//    });

});


// create request service
appformEdit.factory('IAPIUserDetailService', function($http) {

    var InternalAPIUserDetail = {};

    // I get all of the Users Details in the remote collection.
    InternalAPIUserDetail.getUserDetails = function(id, hostUrl) {
        var request = $http.get(hostUrl + "iauser/getuserdetails/" + id)
        return(request.then(handleSuccess, handleError));
    }
    // check userName is exist or not
    InternalAPIUserDetail.checkUserName = function(userName, id, hostUrl) {
        var request = $http.get(hostUrl + "iauser/checkusername/" + userName + "/" + id)
        return(request.then(handleSuccess, handleError));
    }
    // Save UserDetails
    InternalAPIUserDetail.saveUserDetails = function(userDetails, hostUrl) {
        console.log(userDetails);
        var request = $http.post(hostUrl + "iauser/saveuserdetails", userDetails)
        return(request.then(handleSuccess, handleError));
    }

    // I transform the error response, unwrapping the application dta from
    // the API response payload.
    function handleError(response) {

        // The API response from the server should be returned in a
        // nomralized format. However, if the request was not handled by the
        // server (or what not handles properly - ex. server error), then we
        // may have to normalize it on our end, as best we can.
        if (
                !angular.isObject(response.data) ||
                !response.data.message
                ) {
            return($q.reject("An unknown error occurred."));
        }

        // Otherwise, use expected error message.
        return($q.reject(response.data.message));
    }


    // I transform the successful response, unwrapping the application data
    // from the API response payload.
    function handleSuccess(response) {
        return(response.data);
    }

    return InternalAPIUserDetail;
});