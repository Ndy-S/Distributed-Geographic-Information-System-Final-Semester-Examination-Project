<?php
    require '../function.php';

    $iduser = $_SESSION['iduser'];
    $getalluser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
    $datauser = mysqli_fetch_array($getalluser);
    $email = $datauser['email'];
    $password = $datauser['password'];
    
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    
    if (isset($_SESSION['log']) && $_SESSION['role'] == '1') {

    } else {
        header('location: ../index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project UAS SIGT A</title>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2JBgkhUXxHgcXrL6MhEdR9X6am2hR--E&libraries=places"></script>
    <link href="../css/styles.css" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link type="text/css" href="../css/dataTables.checkboxes.css" rel="stylesheet"/>
	<link type="text/css" href="../css/datatables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../css/custom.css" rel="stylesheet"/>
</head>

<body>
    <div class="wrapper">
        <div class="body-overlay"></div>

        <!-- Sidebar HTML Code Start -->
        <div id="sidebar">
	        <div class="sidebar-header">
		        <h3><img src="../img/logo.png" class="img-fluid"/><span>Project UAS</span></h3>
		    </div>
            
            <ul class="list-unstyled component m-0">
		        <li>
		            <a href="adminhome.php" class="dashboard"><i class="material-icons">category</i>Items Data </a>
		        </li>		       
                <li>
		            <a href="incomingitem.php"><i class="material-icons">inventory_2</i>Incoming Items</a>
		        </li>                
                <li>
		            <a href="exititem.php"><i class="material-icons">local_shipping</i>Exit Items</a>
		        </li>
                <li class="dropdown active">
		            <a href="#homeSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="material-icons">receipt_long</i>Transaction</a>
                    <ul class="show list-unstyled menu" id="homeSubmenu1">
		                <li class="active"><a href="#">User Request</a></li>
                        <li><a href="debtreq.php">Debt Payment Request</a></li>
			            <li><a href="trdatabase.php">Transaction Database</a></li>
		            </ul>
		        </li>
                <li>
		            <a href="usercontrol.php"><i class="material-icons">manage_accounts</i>User Control</a>
		        </li>
            </ul>
        </div>
        <!-- Sidebar HTML Code End -->
	 
        <!-- Page Content Start -->
        <div id="content"> 
		    <!-- Topbar HTML Code Start -->
		    <div class="top-navbar">
		        <div class="xd-topbar">
			        <div class="row">
				        <div class="col-2 col-md-1 col-lg-1 order-2 order-md-1 align-self-center">
					        <div class="xp-menubar"><span class="material-icons text-white">signal_cellular_alt</span></div>
					    </div>
					 
					<div class="col-md-5 col-lg-3 order-3 order-md-2">
					</div>
					 
					<div class="col-10 col-md-6 col-lg-8 order-1 order-md-3">
					    <div class="xp-profilebar text-right">
						    <nav class="navbar p-0">
                                <ul class="nav navbar-nav flex-row ml-auto">
                                    <li class="d-flex align-content-center flex-wrap"><small>Hello, <b><?=$_SESSION['username'];?></b>!</small></li>
							        <li class="dropdown nav-item">
                                        <?php 
                                            $getemptyitem = mysqli_query($conn, "SELECT * FROM item WHERE itemtotal < 1");
                                            $rowCount = mysqli_num_rows($getemptyitem);
                                            $gettr = mysqli_query($conn, "SELECT * FROM transaction WHERE status = 1 OR status = 4 OR status = 6 OR status = 12");
                                            $tr = mysqli_num_rows($gettr);
                                            $getdb = mysqli_query($conn, "SELECT * FROM debt WHERE debtstatus = 2");
                                            $db = mysqli_num_rows($getdb);
                                        ?>
							            <a class="nav-link" href="#" data-toggle="dropdown"><span class="material-icons">notifications</span><span class="notification"><?=$rowCount+$tr+$db;?></span></a>
								            <ul class="dropdown-menu">
                                                <?php 
                                                    if ($rowCount + $tr + $db == 0) {
                                                ?>
                                                    <li><a href="#">No notifications yet</a></li>
                                                <?php                                                     
                                                    }; 
                                                    while ($fetch= mysqli_fetch_array($getemptyitem)) {
                                                        $item = $fetch['itemname'];
								                        echo '<li><a href="#"><strong class="text-danger">ALERT!</strong> <b>"'.$item.'"</b> is empty.</a></li>';
                                                    };

                                                    while ($fetchtr= mysqli_fetch_array($gettr)) {
                                                        $iduser = $fetchtr['iduser'];
                                                        $status = $fetchtr['status'];
                                                        $getuserinfo = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
                                                        $getinfouser = mysqli_fetch_array($getuserinfo);
                                                        $username = $getinfouser['username'];

                                                        if ($status == 1 || $status == 12) {
                                                            echo '<li><a href="#"><b>'.$username.'</b> requests to borrow items.</a></li>';
                                                        } else if ($status == 4 || $status == 6) {
                                                            echo '<li><a href="#"><b>'.$username.'</b> requests to return an item.</a></li>';
                                                        }
                                                    };

                                                    while ($fetchtr= mysqli_fetch_array($getdb)) {
                                                        $iduser = $fetchtr['iduser'];
                                                        $getuserinfo = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
                                                        $getinfouser = mysqli_fetch_array($getuserinfo);
                                                        $username = $getinfouser['username'];

                                                        echo '<li><a href="#"><b>'.$username.'</b> request to pay debt.</a></li>';
                                                    };
                                                ?>
								            </ul>
							        </li>
							        <li class="dropdown nav-item">
							            <a class="nav-link" href="#" data-toggle="dropdown"><img src="../img/<?=$_SESSION['image']?>" style="width:40px; border-radius:50%;"/><span class="xp-user-live"></span></a>
								        <ul class="dropdown-menu small-menu">
								            <li><a href="#profilemodal" data-toggle="modal"><span class="material-icons">person_outline</span>Profile</a></li>
								    	    <li><a href="../logout.php"><span class="material-icons">logout</span>Logout</a></li>
                                        </ul>
                                    </li>

                                </ul>
                            </nav>
                        </div>
                    </div>

                    </div>
                    
                    <div class="xp-breadcrumbbar text-center">
				        <h4 class="page-title">Admin Dashboard</h4>
					    <ol class="breadcrumb">
					        <li class="breadcrumb-item"><a href="#">Admin access granted!</a></li>
					    </ol>
				    </div>
                </div>
		    </div>
		    <!-- Topbar HTML Code End -->
		  
            <!-- Main Content Start -->
		    <div class="main-content">
			    <div class="row">
			        <div class="col-md-12">
				        <div class="table-wrapper">
			
				            <div class="table-title card mb-4">
				                <div class="row">
					                <div class="col-sm-6 p-0 flex justify-content-lg-start justify-content-center">
						                <h2 class="ml-lg-2">User Request</h2>
						            </div>
				                </div>
				            </div>
                        </div>
                    </div>
                </div>
                
                <table id="example" class="table table-striped table-hover"></table>
            </div>

            <footer class="py-4 mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div>Copyright &copy; Kelompok SIGT 2022</div>
                        <div>Project UAS &middot; Sistem Informasi &amp; SIGT A</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
        
    <!-- Profile Modal Start -->
    <div class="modal fade" tabindex="-1" id="profilemodal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="text-center">
                                <img src="../img/<?=$_SESSION['image']?>" style="width:200px; height:200px; border-radius:50%;" class="img-thumbnail">

                            </div>
            	        </div>
                        <div class="form-group">
                            <label>Profile <small>(Only JPG and PNG formats with Max Size of 15 MB)</small></label>
            	        	<input type="file" name="file" class="form-control">
                        </div>
            	        <div class="form-group">
                            <label>Username</label>
            	        	<input type="text" name="username" class="form-control" value="<?=$_SESSION['username'];?>" required>
                        </div>
            	        <div class="form-group">
            	            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?=$_SESSION['email'];?>" required>        	            
                        </div>
            	        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?=$_SESSION['password'];?>" required>
            	        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" class="form-control" 
                            value="<?php 
                                if ($_SESSION['role'] == 0) { 
                                    echo "User";
                                } else if ($_SESSION['role'] == 1) {
                                    echo "Admin";
                                } else if ($_SESSION['role'] == 2) {
                                    echo "On Debt";
                                } else if ($_SESSION['role'] == 3) {
                                    echo "Courier";
                                } else {
                                    echo "Unknown";
                                }
                            ?>" readonly>
            	        </div>
                        <div class="form-group">
                            <label>Total Debt</label>
                            <input type="text" class="form-control" value="<?php echo "Rp ".number_format($_SESSION['debt'],2,',','.');?>" readonly>
            	        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="reset-btn" hidden>Reset</button>
                        <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="profileupdate">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Profile Modal End -->

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmreqmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Request</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!-- Modal body -->
                <form id="confirmreqform">
                    <div class="modal-body">
                    <p>Are you sure you want to confirm "<b id="confirmreqitemname"></b>" borrow request?</p>
                    <p><small><b>User: <span class="text-primary" id="confirmrequsername"></span></b></small></p>
                    <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddenconfirmreqidtr">
                    <input type="hidden" id="hiddenconfirmreqitemid">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Delivery Modal -->
        <div class="modal fade" id="deliverymodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delivery Request</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!-- Modal body -->
                <form id="deliveryform">
                    <div class="modal-body">
                    <p>Are you sure you want to confirm "<b id="deliveryitemname"></b>" borrow request?</p>
                    <p><small><b>User: <span class="text-primary" id="deliveryusername"></span></b></small></p>
                    <div class="form-group map"></div>
                    <div class="form-group">
        		        <label>Choose courier <small></small></label>
                        <select class="form-control" id="deliveryselect" required>
                            <option value="">No Courier Available</option>
                        </select>
        		    </div>
                    <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddendeliveryidtr">
                    <input type="hidden" id="hiddendeliveryitemid">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectreqmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Reject Request</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form id="rejectreqform">
                    <div class="modal-body">
                    <p>Are you sure you want to reject "<b id="rejectreqitemname"></b>" borrow request?</p>
                    <p><small><b>User: <span class="text-primary" id="rejectrequsername"></span></b></small></p>
                    <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddenrejectreqidtr">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Reject</button>
                </div>
                </form>

            </div>
        </div>
    </div> 

    <!-- Confirm Return Partially Modal -->
    <div class="modal fade" id="confirmreturnpmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Return Partially Request</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!-- Modal body -->
                <form id="confirmreturnpform">
                    <div class="modal-body">
                    <p>Are you sure you want to confirm return "<b id="returnpitemname"></b>" partially request?</p>
                    <p>
                        <small><b>User: <span class="text-primary" id="returnpusername"></span></b></small><br>
                        <small><b>Return Amount: <span class="text-primary" id="returnpreq"></span></b></small><br>
                        <small><b>Borrowed: <span class="text-primary" id="returnpborrowqty"></span></b></small><br>
                        <small><b>Returned: <span class="text-primary" id="returnpreturned"></span></b></small>
                    </p>
                    <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddenconfirmreturnpidtr">
                    <input type="hidden" id="hiddenconfirmreturnpitemid">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Confirm Return All Modal -->
    <div class="modal fade" id="confirmreturnallmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Return All Request</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!-- Modal body -->
                <form id="confirmreturnallform">
                    <div class="modal-body">
                    <p>Are you sure you want to confirm return "<b id="returnallitemname"></b>" request?</p>
                    <p>
                        <small><b>User: <span class="text-primary" id="returnallusername"></span></b></small><br>
                        <small><b>Return Amount: <span class="text-primary" id="returnallreq"></span></b></small><br>
                        <small><b>Borrowed: <span class="text-primary" id="returnallborrowqty"></span></b></small><br>
                        <small><b>Returned: <span class="text-primary" id="returnallreturned"></span></b></small>
                    </p>
                    <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddenreturnallidtr">
                    <input type="hidden" id="hiddenreturnallitemid">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Reject Return Request Modal -->
    <div class="modal fade" id="rejectreturnmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Reject Return Request</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form id="rejectreturnform">
                    <div class="modal-body">
                    <p>Are you sure you want to reject "<b id="rejectreturnitemname"></b>" return request?</p>
                    <p>
                        <small><b>User: <span class="text-primary" id="rejectreturnusername"></span></b></small><br>
                        <small><b>Return Amount: <span class="text-primary" id="rejectreturnreq"></span></b></small><br>
                        <small><b>Borrowed: <span class="text-primary" id="rejectreturnborrowqty"></span></b></small><br>
                        <small><b>Returned: <span class="text-primary" id="rejectreturnreturned"></span></b></small>
                    </p>
                    <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddenrejectreturnidtr">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Reject</button>
                </div>
                </form>

            </div>
        </div>
    </div> 

    <script>
        function currLoc() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition (
                    function(position) {
                        let lat = position.coords.latitude;
                        let lng = position.coords.longitude;
                        let devCenter = new google.maps.LatLng(lat, lng);
                        map.setCenter(devCenter);
                    }
                );
            }
        }

        var mapOptions = {
            center: currLoc(),
            zoom: 15,
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer();

        var map = new google.maps.Map(document.querySelector('.map'), mapOptions);
        directionsRenderer.setMap(map);

        const icon = {
            url: '../img/warehouse.svg',
            scaledSize: new google.maps.Size(39, 30)
        };
        
        let locations = [
            [0.530412265187149, 101.41140601246421], 
            [0.4696614166528457, 101.4523262495508], 
            [0.525268163083154, 101.44802027724216], 
            [0.4821062661271904, 101.39487485875118], 
            [0.6086015379210076, 101.43086660278999]
        ];

        const infowindow = new google.maps.InfoWindow({
            content: 'Gudang Pengiriman'
        });

        for (let i=0; i<locations.length; i++) {
            let markerOptions = {
                icon: icon,
                position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                map: map,
            };
            let marker = new google.maps.Marker(markerOptions);
            marker.addListener('click', () => {
                infowindow.open({
                    anchor: marker
                })
            }) 
        }
    </script>
    <script src="../js/jquery-3.6.1.min.js"></script>
    <script src="../js/datatables.min.js"></script>
    <script src="../js/dataTables.checkboxes.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            displayData();
        });

        $(".xp-menubar").on('click',function(){
            $("#sidebar").toggleClass('active');
        	$("#content").toggleClass('active');
        });

        $('.xp-menubar,.body-overlay').on('click',function(){
            $("#sidebar,.body-overlay").toggleClass('show-nav');
        });

        $('.reset').click(function(){
            $(".reset-btn").click();
        });

        // Display Function
        function displayData() {
            var displayData = "true";
            $.ajax({
                url: '../function.php',
                type: 'post',
                data: {
                    admindisplayuserreqSend: displayData
                },
                success: function(data, status) {
                    $('#example').html(data);
                }
            });
        }

        // Confirm User Request Function
        function confirmreq(confirmreqidtr) {
            $('#hiddenconfirmreqidtr').val(confirmreqidtr);

            $.post("../function.php", {
                confirmreqidtrSend: confirmreqidtr
            }, function(data, status) {
                var confirmreq = JSON.parse(data);
                $('#confirmreqitemname').html(confirmreq.itemname);
                $('#confirmrequsername').html(confirmreq.username);

                $('#hiddenconfirmreqitemid').val(confirmreq.itemid);
            });

            $('#confirmreqmodal').modal('show');
        }

        $('#confirmreqform').submit(function (e) {
            e.preventDefault();
            var hiddenconfirmreqidtr = $('#hiddenconfirmreqidtr').val();
            var hiddenconfirmreqitemid = $('#hiddenconfirmreqitemid').val();

            $.post("../function.php", {
                hiddenconfirmreqidtrSend: hiddenconfirmreqidtr,
                hiddenconfirmreqitemidSend: hiddenconfirmreqitemid
            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#confirmreqmodal').modal('hide');
            });
        });

        // Delivery Function
        function delivery(deliveryidtr) {
            $('#hiddendeliveryidtr').val(deliveryidtr);

            $.post("../function.php", {
                deliveryidtrSend: deliveryidtr
            }, function(data, status) {
                var deliver = JSON.parse(data);
                var i = 0;
                var items = "";
                $('#deliveryitemname').html(deliver.itemname);
                $('#deliveryusername').html(deliver.username);

                $('#hiddendeliveryitemid').val(deliver.itemid);
                        
                var request = {
                    origin: {lat: parseFloat(deliver.warehouselat), lng: parseFloat(deliver.warehouselon)},
                    destination: deliver.address,
                    travelMode: 'DRIVING'
                };

                function showRoute() {
                    directionsService.route(request, function(result, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            directionsRenderer.setDirections(result);
                        }
                    }); 
                }

                while (deliver[i] != undefined) {
                    items+="<option value='"+deliver[i].iduser+"'>"+deliver[i].username+"</option>";
                    i++;
                }

                setTimeout(showRoute, 500);
                $("#deliveryselect").html(items); 

            });

            $('#deliverymodal').modal('show');
        }

        $('#deliveryform').submit(function (e) {
            e.preventDefault();
            var hiddendeliveryidtr = $('#hiddendeliveryidtr').val();
            var hiddendeliveryitemid = $('#hiddendeliveryitemid').val();
            var selectedcourier = $('#deliveryselect').find(":selected").val();

             $.post("../function.php", {
                 hiddendeliveryidtrSend: hiddendeliveryidtr,
                 hiddendeliveryitemidSend: hiddendeliveryitemid,
                 selectedcourierSend: selectedcourier
             }, function(data, status) {
                 $('#example').DataTable().destroy();
                 displayData();

                 $('#deliverymodal').modal('hide');
             });
        });

        // Reject User Request Function
        function rejectreq(rejectreqidtr) {
            $('#hiddenrejectreqidtr').val(rejectreqidtr);

            $.post("../function.php", {
                rejectreqidtrSend: rejectreqidtr
            }, function(data, status) {
                var rejectreq = JSON.parse(data);
                $('#rejectreqitemname').html(rejectreq.itemname);
                $('#rejectrequsername').html(rejectreq.username);

            });

            $('#rejectreqmodal').modal('show');
        }

        $('#rejectreqform').submit(function (e) {
            e.preventDefault();
            var hiddenrejectreqidtr = $('#hiddenrejectreqidtr').val();

            $.post("../function.php", {
                hiddenrejectreqidtrSend: hiddenrejectreqidtr
            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#rejectreqmodal').modal('hide');
            });
        });

        // Confirm Return Partially Function
        function confirmreturnp(confirmreturnpidtr) {
            $('#hiddenconfirmreturnpidtr').val(confirmreturnpidtr);

            $.post("../function.php", {
                confirmreturnpidtrSend: confirmreturnpidtr
            }, function(data, status) {
                var returnp = JSON.parse(data);
                $('#returnpitemname').html(returnp.itemname);
                $('#returnpusername').html(returnp.username);
                $('#returnpreq').html(returnp.req);
                $('#returnpborrowqty').html(returnp.borrowqty);
                $('#returnpreturned').html(returnp.returned);

                $('#hiddenconfirmreturnpitemid').val(returnp.itemid);
            });

            $('#confirmreturnpmodal').modal('show');
        }

        $('#confirmreturnpform').submit(function (e) {
            e.preventDefault();
            var hiddenconfirmreturnpidtr = $('#hiddenconfirmreturnpidtr').val();
            var hiddenconfirmreturnpitemid = $('#hiddenconfirmreturnpitemid').val();

            $.post("../function.php", {
                hiddenconfirmreturnpidtrSend: hiddenconfirmreturnpidtr,
                hiddenconfirmreturnpitemidSend: hiddenconfirmreturnpitemid
            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#confirmreturnpmodal').modal('hide');
            });
        });

        // Confirm Return All Function
        function confirmreturnall(confirmreturnallidtr) {
            $('#hiddenreturnallidtr').val(confirmreturnallidtr);

            $.post("../function.php", {
                confirmreturnallidtrSend: confirmreturnallidtr
            }, function(data, status) {
                var returnall = JSON.parse(data);
                $('#returnallitemname').html(returnall.itemname);
                $('#returnallusername').html(returnall.username);
                $('#returnallreq').html(returnall.req);
                $('#returnallborrowqty').html(returnall.borrowqty);
                $('#returnallreturned').html(returnall.returned);

                $('#hiddenreturnallitemid').val(returnall.itemid);
            });

            $('#confirmreturnallmodal').modal('show');
        }

        $('#confirmreturnallform').submit(function (e) {
            e.preventDefault();
            var hiddenreturnallidtr = $('#hiddenreturnallidtr').val();
            var hiddenreturnallitemid = $('#hiddenreturnallitemid').val();

            $.post("../function.php", {
                hiddenreturnallidtrSend: hiddenreturnallidtr,
                hiddenreturnallitemidSend: hiddenreturnallitemid
            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#confirmreturnallmodal').modal('hide');
            });
        });

        // Reject Return Function
        function rejectreturn(rejectreturnidtr) {
            $('#hiddenrejectreturnidtr').val(rejectreturnidtr);

            $.post("../function.php", {
                rejectreturnidtrSend: rejectreturnidtr
            }, function(data, status) {
                var rejectreturn = JSON.parse(data);
                $('#rejectreturnitemname').html(rejectreturn.itemname);
                $('#rejectreturnusername').html(rejectreturn.username);
                $('#rejectreturnreq').html(rejectreturn.req);
                $('#rejectreturnborrowqty').html(rejectreturn.borrowqty);
                $('#rejectreturnreturned').html(rejectreturn.returned);
            });

            $('#rejectreturnmodal').modal('show');
        }

        $('#rejectreturnform').submit(function (e) {
            e.preventDefault();
            var hiddenrejectreturnidtr = $('#hiddenrejectreturnidtr').val();

            $.post("../function.php", {
                hiddenrejectreturnidtrSend: hiddenrejectreturnidtr
            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#rejectreturnmodal').modal('hide');
            });
        });
    </script>
</body>
</html>