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
    
    $scope.init = function(id){
      loadRemoteData(id);
    };
    
        // I apply the remote data to the local scope.
    function applyRemoteData( userDetail ) {
        console.log('RemoteData');
        console.log(userDetail);
        $scope.user = userDetail;
    }


    // I load the remote data from the server.
    function loadRemoteData(id) {
        // The IAPIUserDetailService returns a promise.
        IAPIUserDetailService.getUserDetails(id)
            .then(
                function( userDetail ) {
                    applyRemoteData( userDetail );

                }
            )
        ;

    }
    

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


// create request service
appformEdit.factory('IAPIUserDetailService', function($http) {

    var InternalAPIUserDetail = {};


//    ergastAPI.getUserDetails = function(id) {
//        $http.get("http://localhost:9091/iauser/getuserdetails/5")
//            .success(function(response) {
//                $scope.user = ;
//    });
//    }
    
     // I get all of the friends in the remote collection.
                 InternalAPIUserDetail.getUserDetails = function(id) {
 
                var request =    $http.get("http://localhost:9091/iauser/getuserdetails/"+id)
                  
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
  
