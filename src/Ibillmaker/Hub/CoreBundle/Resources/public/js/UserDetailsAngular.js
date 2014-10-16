var appformEdit = angular.module("formEdit", ["xeditable"], function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });
appformEdit.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});

    appformEdit.controller('formEditController', function($scope ,$http,IAPIUserDetailService) {
        // I contain the User (Detail) to be rendered.
        $scope.user = [];  
        $scope.error = [];
        $scope.editableForm = [];
        $scope.editableForm.$validusername = false;
    $scope.init = function(id){
      loadRemoteData(id);
    };
    
        // I apply the remote data to the local scope.
    function applyRemoteData( userDetail ) {
        $scope.user = userDetail;
    }

    function applyError(){alert('ok');
       $scope.error.value = true;
      
    }

    // I load the remote data from the server.
    function loadRemoteData(id) {
        $scope.editableForm.$validusername = true;
        // The IAPIUserDetailService returns a promise.
        IAPIUserDetailService.getUserDetails(id)
            .then(
                function( userDetail ) {
                    userDetail.id = id;
                    applyRemoteData( userDetail );
                }
            )
        ;
    }
    

  $scope.checkUserName = function(userName) {      
      IAPIUserDetailService.checkUserName(userName,$scope.user.id)
                .then(
                    function( userResponse) {
                        console.log(userResponse);
                        if (userResponse.code == 21) {
                            $scope.editableForm.$invalid = true;
                            $scope.editableForm.$validusername = false;
                            $scope.editableForm.$setError('username', 'username is not available. Please choose another one');
                        }else{
                            
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
     IAPIUserDetailService.saveUserDetails($scope.user)
            .then(
                function( userResponse ) {
                    console.log(userResponse.code);
                    if (userResponse.code == 21) {
                        $scope.editableForm.$setError('username', userResponse.message);
                    }
                });
  };
});


// create request service
appformEdit.factory('IAPIUserDetailService', function($http) {

    var InternalAPIUserDetail = {};


//    ergastAPI.getUserDetails = function(id) {
//        $http.get("http://localhost:9091/iauser/getuserdetails/5")
//            .success(function(response) {
//                $scope.user = ;
//    });
//    }
    
                // I get all of the Users Details in the remote collection.
                InternalAPIUserDetail.getUserDetails = function(id) {
                    var request =    $http.get("http://localhost:9091/iauser/getuserdetails/"+id)
                    return( request.then( handleSuccess, handleError ) );
                }
                // check userName is exist or not
                InternalAPIUserDetail.checkUserName = function(userName,id){
                    var request = $http.get("http://localhost:9091/iauser/checkusername/"+userName+"/"+id)
                    return( request.then( handleSuccess, handleError ) );
                }
                // Save UserDetails
                InternalAPIUserDetail.saveUserDetails = function(userDetails){
                    var request = $http.post("http://localhost:9091/iauser/saveuserdetails",userDetails)
                    return( request.then( handleSuccess, handleError ) );
                }
    
                // I transform the error response, unwrapping the application dta from
                // the API response payload.
                function handleError( response ) {
 
                    // The API response from the server should be returned in a
                    // nomralized format. However, if the request was not handled by the
                    // server (or what not handles properly - ex. server error), then we
                    // may have to normalize it on our end, as best we can.
                    if (
                        ! angular.isObject( response.data ) ||
                        ! response.data.message
                        ) {
                        return( $q.reject( "An unknown error occurred." ) );
                    }
 
                    // Otherwise, use expected error message.
                    return( $q.reject( response.data.message ) );
                }
 
 
                // I transform the successful response, unwrapping the application data
                // from the API response payload.
                function handleSuccess( response ) {
                    return( response.data );
                }            
 
    return InternalAPIUserDetail;
  });
  
