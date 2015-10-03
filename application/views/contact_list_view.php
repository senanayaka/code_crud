<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Contacts</title>
        
        <!-- create global base url for js -->
        <script type="text/javascript">

            var CI = {
                'base_url': '<?php echo base_url(); ?>'
            };

        </script>
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>assets/js/angular.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/ui-bootstrap-tpls-0.12.0.js"></script>
        
        <script>
            angular.module('app', ['ui.bootstrap']);
            angular.module('app').controller('PaginatioCtrl', function ($scope, $http) {


                $scope.currentPage = 1;
                $scope.tracks = [];
                function getData() {

                    $http.get(CI.base_url + "contacts/getlist/" + $scope.currentPage)
                            .then(function (response) {
                             $scope.totalItems = response.data.counts_all;
                             angular.copy(response.data.records, $scope.tracks)

                            });
                  }

                getData();

                //get another portions of data on page changed
                $scope.pageChanged = function () {
                    getData();
                };
                $scope.editUser = function (id) {
                    $scope.toggle = true;
                    if (id == 'new') {
                        $scope.edit = true;
                        $scope.incomplete = true;
                        $scope.contact_id = '';
                        $scope.contact_name = '';
                        $scope.contact_no = '';
                        $scope.email = '';

                    } else {

                        $scope.edit = true;
                        $scope.contact_id = $scope.tracks[id].contact_id;
                        $scope.contact_name = $scope.tracks[id].contact_name;
                        $scope.contact_no = $scope.tracks[id].contact_no;
                        $scope.email = $scope.tracks[id].email;
                    }
                };
                //  handling the submit button for the form
                $scope.addNew = function () {
                    contactData = {
                        "contact_id": $scope.contact_id,
                        "contact_name": $scope.contact_name,
                        "contact_no": $scope.contact_no,
                        "email": $scope.email
                    };
                    $scope.options = contactData;
                    $scope.addOptions = function (op) {
                        op.newlyAdded = true;
                        $scope.options.push(angular.copy(op));
                    };

                    return $http({
                        url: CI.base_url + 'contacts/save_data/',
                        method: "POST",
                        data: contactData,
                    })
                            .success(function (addData) {
                                 $http.get(CI.base_url + "contacts/getlist/" + $scope.currentPage)
                            .then(function (response) {
                             $scope.totalItems = response.data.counts_all;
                             angular.copy(response.data.records, $scope.tracks)

                            });   
                                $scope.toggle = false;
                                $scope.success = true;

                            });
                }

            });

        </script>

    </head>

    <body ng-app="app">
        <div ng-controller="PaginatioCtrl">
            <div class="container bs-docs-container">
                <div class="row">
                    <h3>Contacts Book</h3>
                    <div class="col-md-9" role="main">
                        <div class="bs-docs-section">

                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Contact No</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="track in tracks">

                                        <td>{{track.contact_id}}</td>
                                        <td>{{track.contact_name}}</td>
                                        <td>{{track.contact_no}}</td>
                                        <td>{{track.email}}</td>
                                        <td>
                                            <button class="btn" ng-click="editUser($index)">
                                                <span class="glyphicon glyphicon-pencil"></span>  Edit
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                
                            </table>
                            <tfoot><pagination total-items="totalItems" ng-model="currentPage" ng-change="pageChanged()"  ></pagination></tfoot>
                        </div>  
                    </div>
                </div>

                <hr>
                <button class="btn btn-success" ng-click="editUser('new')">
                    <span class="glyphicon glyphicon-user"></span> Add New
                </button>
                <hr>
                <div class="row" ng-show="toggle" class="ng-hide" >


                    <h3 ng-show="edit">Add New Contact:</h3>
                    <h3 ng-hide="edit">Edit Contacts:</h3>

                    <form class="form-horizontal" name="contact_form" >

                        <input type="hidden" ng-model="contact_id"   >
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Contact Name:</label>
                            <div class="col-sm-10">
                                <input type="text" ng-model="contact_name"   placeholder="Contact Name" required >
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email:</label>
                            <div class="col-sm-10">
                                <input type="email" ng-model="email"   placeholder="Email" required  >
                             
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Contact No:</label>
                            <div class="col-sm-10">
                                <input type="text" ng-model="contact_no"   placeholder="Contact No" required  ng-minlength=10 ng-maxlength=10 >
                                 <span ng-show="contact_form.contact_no.$error.integer">This is not valid integer!</span>
                                    <span ng-show="contact_form.contact_no.$error.min || contact_form.contact_no.$error.max">
                                      The value must be in range 0 to 10!</span>
                            </div>
                        </div>

                    </form>

                    <hr>
                    <button class="btn btn-success"   ng-click="addNew()" ng-disabled="!contact_form.$valid" >
                        <span class="glyphicon glyphicon-save"></span> Save Changes
                    </button>
                </div>
                <div class="row" ng-show="success" class="ng-hide" >
                    Success 
                </div>
            </div>

        </div>

    </div>

</body>

</html>