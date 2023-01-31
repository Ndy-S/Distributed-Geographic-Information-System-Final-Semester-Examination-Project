<?php
    require '../function.php';

    $iduser = $_SESSION['iduser'];
    $getalluser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
    $datauser = mysqli_fetch_array($getalluser);
    $role = $datauser['role'];
    $email = $datauser['email'];
    $password = $datauser['password'];
    
    $_SESSION['role'] = $role;
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    
    if (isset($_SESSION['log']) && $_SESSION['role'] == '0' || $_SESSION['role'] == '2') {

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
		            <a href="userhome.php" class="dashboard"><i class="material-icons">category</i>Items Data</a>
		        </li>
                <li class="active">
		            <a href="#homeSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="material-icons">receipt_long</i>Transaction</a>
                    <ul class="show list-unstyled menu" id="homeSubmenu1">
			            <li class="active"><a href="userhistory.php">Status</a></li>
		            </ul>
		        </li>
                <li>
                    <?php
                        $getalldebt = mysqli_query($conn, "SELECT * FROM debt WHERE iduser = '$iduser'");
                        $getdatadebt = mysqli_fetch_array($getalldebt);
                        $currentuserdebt = $getdatadebt['debt'];
                        
                        if ($currentuserdebt > 0) {
		                    echo '<a href="#paydebt" data-toggle="modal"><i class="material-icons">payments</i>Pay Debt</a>';
                        } else {
                            echo '<a href="#paydebtnone" data-toggle="modal"><i class="material-icons">payments</i>Pay Debt</a>';
                        }
                    ?>
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
                                            $getdebtuser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser' AND role = 2");
                                            $rowCount = mysqli_num_rows($getdebtuser);
                                        ?>
							            <a class="nav-link" href="#" data-toggle="dropdown"><span class="material-icons">notifications</span><span class="notification"><?=$rowCount;?></span></a>
								            <ul class="dropdown-menu">
                                                <?php 
                                                    if ($rowCount == 0) {
                                                ?>
                                                    <li><a href="#">No notifications yet</a></li>
                                                <?php                                                     
                                                    }; 
                                                    while ($fetch= mysqli_fetch_array($getdebtuser)) {
                                                        $role = $fetch['role'];

                                                        if ($role == 2) {
                                                            echo '<li><a href="#"><strong class="text-danger">ALERT!</strong> You are on debt.</a></li>';
                                                        }
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
				        <h4 class="page-title">User Dashboard</h4>
					    <ol class="breadcrumb">
					        <li class="breadcrumb-item"><a href="#">User access granted!</a></li>
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
						                <h2 class="ml-lg-2">Transaction Status</h2>
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
                            <?php
                                $getalldebt = mysqli_query($conn, "SELECT * FROM debt WHERE iduser = '$iduser'");
                                $datadebt = mysqli_fetch_array($getalldebt);
                                $showdebt = $datadebt['debt'];                        
                            ?>
                            <input type="text" class="form-control" value="<?php echo "Rp ".number_format($showdebt,2,',','.');?>" readonly>
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

    <!-- Track Courier Modal -->
        <div class="modal fade" id="trackcouriermodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Courier Location</h5>
                </div>

                <!-- Modal body -->
                <form id="trackcourierform">
                    <div class="modal-body mapsave">
                        <div class="map"></div>
                        <div class="distance"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">OK</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Cancel Transaction Modal -->
    <div class="modal fade" id="canceltrmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cancel Transaction</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form id="submitcancelidtr">
                    <div class="modal-body">
                    <p>Are you sure you want to cancel "<b id="itemnametr"></b>" borrowing transaction?</p>
                    <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="cancelidtr">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">OK</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Delete History Modal -->
        <div class="modal fade" id="deletehistorymodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete History</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form id="deletehistoryform">
                    <div class="modal-body">
                    <p>Are you sure you want to delete "<b id="deletehitemname"></b>" transaction history?</p>
                    <p>
                        <small><b>Amount: <span class="text-primary" id="deletehamount"></span></b></small><br>
                        <small><b>Returned: <span class="text-primary" id="deletehreturned"></span></b></small><br>
                        <small><b>Status: <span class="text-primary" id="deletehstatus"></span></b></small><br>
                        <small><b>Borrow Date: <span class="text-primary" id="deletehborrowdate"></span></b></small>
                    </p>
    		        <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="deletehidtr">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Delete</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Report Modal -->
        <div class="modal fade" id="reportmissmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Report Missing Item</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="reportmissform">
                    <div class="modal-body">
                    <p>Are you sure you want to report missing "<b id="reportmissname"></b>" ?</p>
                    <p>
                        <small><b>Amount: <span class="text-primary" id="reportmissamt"></span></b></small><br>
                        <small><b>Borrow Date: <span class="text-primary" id="reportmissbdate"></span></b></small>
                    </p>
    		        <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddenreportidtr">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Report</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Return Modal -->
    <div class="modal fade" id="returnreqmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Return Item</h4>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal">&times;</button>
                </div>
                <form id="returnreqform">
                    <div class="modal-body">
                    <p>Are you sure you want to return "<b id="returnreqitemname"></b>"?</p>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" id="returnreqamount" class="form-control" min="1" required>        	            
                    </div>
                    <p>
                        <small><b>Borrow Date: <span class="text-primary" id="returnreqbdate"></span></b></small><br>
                        <small><b>Borrow Deadline: <span class="text-primary" id="returnreqdeadline"></span></b></small>
                    </p>
    		        <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddenreturnidtr">
                    <input type="hidden" id="hiddenretborrowqty">
                    <input type="hidden" id="hiddenretreturned">
                    </div>
                    <div class="modal-footer">
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">OK</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Return Request Modal -->
    <div class="modal fade" id="cancelreturnmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cancel Return Request</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="cancelreturnform">
                    <div class="modal-body">
                    <p>Are you sure you want to cancel "<b id="cancelreturnitemname"></b>" return request?</p>
                    <p>
                        <small><b>Return Request Amount: <span class="text-primary" id="cancelreturnreqam"></span></b></small><br>
                    </p>
    		        <p class="text-warning"><small>this action Cannot be Undone</small></p>
                    <input type="hidden" id="hiddencancelreturnidtr">
                    <input type="hidden" id="hiddencancelreturnreq">
                    <input type="hidden" id="hiddencancelreturnstatus">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">OK</button>
                </div>
                </form>
            </div>
        </div>
    </div>
                        
    <!-- Pay Debt Modal -->
    <div class="modal fade" id="paydebt">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Pay Debt</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                    <!-- Modal body -->
                    <form id="paydebtform">
                        <div class="modal-body">
        		            <div class="form-group">
                            <p>Your debt <b><?php echo "Rp ".number_format($showdebt,2,',','.');?></b></p>
                            </div>
        		            <div class="form-group">
        		                <label>Amount <small>(Rupiah Currency)</small></label>
                                <input type="number" id="paydebtpayqty" class="form-control" min="1" max="<?=$showdebt;?>" required>
        		            </div>
                            <div class="form-group">
                                <?php 
                                    date_default_timezone_set('Asia/Jakarta');
                                    $currdate = date('d-m-Y H:i:s');
                                ?>
        		                <label>Current Date <small>(Asia/Jakarta)</small></label>
                                <input type="text" id="paydebtcurrdate" value="<?=$currdate;?>" class="form-control" readonly>
        		            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="reset-btn" hidden>Reset</button>
                            <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>

    <!-- Pay Debt None Modal -->
    <div class="modal fade" id="paydebtnone">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Pay Debt</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                    <!-- Modal body -->
                    <form method="post">
                        <div class="modal-body">
        		            <div class="form-group">
                            <p>Your debt <b><?php echo "Rp ".number_format($showdebt,2,',','.');?></b></p>
                            </div>
        		            <div class="form-group">
        		                <label>Amount <small>(Rupiah Currency)</small></label>
                                <input type="number" name="payqty" class="form-control" min="1" max="<?=$showdebt;?>" readonly>
        		            </div>
                            <div class="form-group">
                                <?php 
                                    date_default_timezone_set('Asia/Jakarta');
                                    $currdate = date('d-m-Y H:i:s');
                                ?>
        		                <label>Current Date <small>(Asia/Jakarta)</small></label>
                                <input type="text" name="currdate" value="<?=$currdate;?>" class="form-control" readonly>
        		            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="reset-btn" hidden>Reset</button>
                            <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancel</button>
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
                    displayUserHistorySend: displayData
                },
                success: function(data, status) {
                    $('#example').html(data);
                }
            });
        }

        // Track Courier Function
        function trackcourier(trackcourieridtr) {
            $.post("../function.php", {trackcourieridtrSend: trackcourieridtr},
            function(data, status) {
                var courierReport = JSON.parse(data);
                const iconCourier = {
                    url: '../img/courier.svg',
                    scaledSize: new google.maps.Size(30, 26)
                };

                let courierlat = courierReport.courierlat;
                let courierlng = courierReport.courierlon;

                if (courierlat.length < 1) {
                    directionsRenderer.setDirections({routes: []});
                    $('.distance').html("<div class='alert-warning' style='padding: 10px;'>Courier location not found, please wait until courier logged in!</div>");
                } else {
                    const infowindowCourier = new google.maps.InfoWindow({
                        content: '<b>Courier</b>'
                    });

                    let markerCourierOptions = {
                        icon: iconCourier,
                        position: new google.maps.LatLng(courierlat, courierlng),
                        map: map,
                        animation: google.maps.Animation.BOUNCE
                    };
                    let markerCourier = new google.maps.Marker(markerCourierOptions);
                    markerCourier.addListener('click', () => {
                        infowindowCourier.open({
                            anchor: markerCourier
                        })
                    })

                    $('#receiveritemname').html(courierReport.itemname);
                    $('#receivername').val(courierReport.username);

                    var request = {
                        origin: {lat: parseFloat(courierlat), lng: parseFloat(courierlng)},
                        destination: courierReport.address,
                        travelMode: 'DRIVING'
                    };

                    function showRoute() {
                        directionsService.route(request, function(result, status) {
                            if (status == google.maps.DirectionsStatus.OK) {
                                let distance = result.routes[0].legs[0].distance.value;
                                let distanceKilo = distance / 1000;
                    
                                $('.distance').html("<div class='alert-info' style='padding: 10px;'><table><tr><td>Driving distance</td> <td>:</td> <td>" + distanceKilo + " KM.</td></tr></td><td>Estimated Duration</td> <td>:</td> <td>" + result.routes[0].legs[0].duration.text + ".</td></table></div>");

                                directionsRenderer.setDirections(result);
                            }
                        }); 
                    }

                    setTimeout(showRoute, 500);

                    $('#trackcourierform').submit(function (e) {
                        e.preventDefault();
                        markerCourier.setMap(null);
                        $('#trackcouriermodal').modal('hide');
                    });
                }
            });

            $('#trackcouriermodal').modal('show');
        }


        // Cancel Transaction Function
        function canceltr(cancelidtr) {
            $('#cancelidtr').val(cancelidtr);
            
            $.post("../function.php", {
                cancelidtrSend: cancelidtr
            }, function(data, status) {
                var canceltr = JSON.parse(data);
                $('#itemnametr').html(canceltr.itemname);
            });

            $('#canceltrmodal').modal('show');
        }

        $('#submitcancelidtr').submit(function (e) {
            e.preventDefault();
            var hiddenidtr = $('#cancelidtr').val();

            $.post("../function.php", {
                hiddenidtrSend: hiddenidtr
            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#canceltrmodal').modal('hide');
            });
        });

        // Delete History Transaction Function
        function deletehistorytr(deletehtr) {
            $('#deletehidtr').val(deletehtr);

            $.post("../function.php", {
                deletehidtrSend: deletehtr
            }, function(data, status) {
                var deletehtr = JSON.parse(data);
                $('#deletehitemname').html(deletehtr.itemname);
                $('#deletehamount').html(deletehtr.borrowqty);
                $('#deletehreturned').html(deletehtr.returned);
                $('#deletehborrowdate').html(deletehtr.borrowdate);
                $('#deletehstatus').html(deletehtr[0]);
            });

            $('#deletehistorymodal').modal('show');
        }

        $('#deletehistoryform').submit(function (e) {
            e.preventDefault();
            var hiddendelhidtr = $('#deletehidtr').val();

            $.post("../function.php", {
                hiddendelhidtrSend: hiddendelhidtr
            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#deletehistorymodal').modal('hide');
            });
        });

        // Report Missing Item Function
        function reportmiss(reportidtr) {
            $('#hiddenreportidtr').val(reportidtr);

            $.post("../function.php", {
                reportidtrSend: reportidtr
            }, function(data, status) {
                var reportmiss = JSON.parse(data);
                $('#reportmissname').html(reportmiss.itemname);
                $('#reportmissamt').html(reportmiss[0]);
                $('#reportmissbdate').html(reportmiss.borrowdate);
            });

            $('#reportmissmodal').modal('show');
        }

        $('#reportmissform').submit(function (e) {
            e.preventDefault();
            var hiddenreportidtr = $('#hiddenreportidtr').val();

            $.post("../function.php", {
                hiddenreportidtrSend: hiddenreportidtr
            }, function(data, status) {
                $('#reportmissmodal').modal('hide');

                location.reload();
            });
        });

        // Return Item Function
        function returnreq(returnidtr) {
            $('#hiddenreturnidtr').val(returnidtr);
            
            $.post("../function.php", {
                returnidtrSend: returnidtr
            }, function(data, status) {
                var returnreq = JSON.parse(data);
                $('#returnreqitemname').html(returnreq.itemname);
                $('#returnreqamount').val(returnreq[0]);
                $('#returnreqamount').attr({'max': returnreq[0]});
                $('#returnreqbdate').html(returnreq.borrowdate);
                $('#returnreqdeadline').html(returnreq.borrowdeadline);

                $('#hiddenretborrowqty').val(returnreq.borrowqty);
                $('#hiddenretreturned').val(returnreq.returned);
            });

            $('#returnreqmodal').modal('show');
        }

        $('#returnreqform').submit(function (e) {
            e.preventDefault();
            var hiddenreturnidtr = $('#hiddenreturnidtr').val();
            var hiddenretborrowqty = $('#hiddenretborrowqty').val();
            var hiddenretreturned = $('#hiddenretreturned').val();
            var returnamount = $('#returnreqamount').val();

            $.post("../function.php", {
                hiddenreturnidtrSend: hiddenreturnidtr,
                hiddenretborrowqtySend: hiddenretborrowqty,
                hiddenretreturnedSend: hiddenretreturned,
                returnamountSend: returnamount

            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#returnreqmodal').modal('hide');
            });
        });

        // Cancel Return Function
        function cancelreturn(cancelreturnidtr) {
            $('#hiddencancelreturnidtr').val(cancelreturnidtr);

            $.post("../function.php", {
                cancelreturnidtrSend: cancelreturnidtr
            }, function(data, status) {
                var cancelreturn = JSON.parse(data);
                $('#cancelreturnitemname').html(cancelreturn.itemname);
                $('#cancelreturnreqam').html(cancelreturn.req);

                $('#hiddencancelreturnreq').val(cancelreturn.req);
                $('#hiddencancelreturnstatus').val(cancelreturn.status);
            });

            $('#cancelreturnmodal').modal('show');
        }

        $('#cancelreturnform').submit(function (e) {
            e.preventDefault();
            var hiddencancelreturnidtr = $('#hiddencancelreturnidtr').val();
            var hiddencancelreturnreq = $('#hiddencancelreturnreq').val();
            var hiddencancelreturnstatus = $('#hiddencancelreturnstatus').val();

            $.post("../function.php", {
                hiddencancelreturnidtrSend: hiddencancelreturnidtr,
                hiddencancelreturnreqSend: hiddencancelreturnreq,
                hiddencancelreturnstatusSend: hiddencancelreturnstatus

            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $('#cancelreturnmodal').modal('hide');
            });
        });

        // Pay Debt Function
        $('#paydebtform').submit(function (e) {
            e.preventDefault();
            var paydebtpayqty = $('#paydebtpayqty').val();
            var paydebtcurrdate = $('#paydebtcurrdate').val();
            $.ajax({
                url: '../function.php',
                type: 'post',
                data: {
                    paydebtpayqtySend: paydebtpayqty,
                    paydebtcurrdateSend: paydebtcurrdate
                },
                success: function(data, status) {
                    $(".reset-btn").click();
                    $("#paydebt").modal('hide');
                }
            });
        });

    </script>
</body>
</html>