
app
.filter("nl2br", function($filter) {
    return function(data) {
        if (!data) return data;
            return data.replace(/\n\r?/g, '<br />');
    };
})
.controller('timelineController', function($scope, $http){
    $('#remark-text').removeAttr('disabled');
    $('#remark-text').removeAttr('readonly');
    $scope.vendorId = vendorId;
    $scope.userId = userId;
    $scope.isShow = false;
    var remarksBaseUrl = '/vendor/' + $scope.vendorId + '/remarks';
    $scope.remarks = [];
    $scope.newRemark = {
        thumbsup: 0,
        remarks: ''
    };
    $http.get(remarksBaseUrl)
        .success(function(data){
            $scope.remarks = data;
        });

    $scope.createRemarks = function() {
        if($scope.newRemark.remarks) {
            $http.post(remarksBaseUrl, $scope.newRemark)
                .success(function(data){
                    $scope.remarks.unshift(data);
                    $scope.newRemark.remarks = '';
                    $scope.newRemark.thumbsup = 0;
                });
        }
    }

    $scope.saveEdittedRemark = function(index) {
        var remark = $scope.remarks[index];
        $http.put(remarksBaseUrl + '/' + remark.id, remark)
            .success(function(data){
                $scope.remarks[index] = data;
            });
    }

    $scope.removeRemark = function(index) {
        var remark = $scope.remarks[index];
        bootbox.confirm('Are your sure you want to delete this?', function(res){
            if(res) {
                $http.delete(remarksBaseUrl + '/' + remark.id, remark)
                    .success(function(){
                        $scope.remarks.splice(index, 1);
                    });
            }
        });
    }
});