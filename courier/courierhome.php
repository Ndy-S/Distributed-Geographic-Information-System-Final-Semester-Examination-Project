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
    
    if (isset($_SESSION['log']) && $_SESSION['role'] == '3') {

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
		        <li class="active">
		            <a href="#" class="dashboard"><i class="material-icons">category</i>Courier Data </a>
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
				        <h4 class="page-title">Courier Dashboard</h4>
					    <ol class="breadcrumb">
					        <li class="breadcrumb-item"><a href="#">Courier access granted!</a></li>
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
						                <h2 class="ml-lg-2">Courier Data</h2>
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

    <!-- Courier Modal -->
        <div class="modal fade" id="couriermodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delivery Arrived at the Destination</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!-- Modal body -->
                <form id="courierform">
                    <div class="modal-body">
                    <div class="form-group">
                        <p>Are you sure you want to confirm "<b id="receiveritemname"></b>" delivery?</p>
                    </div>
                    <div class="form-group map"></div>
                    <div class="form-group">
            	        <label>Receiver Name</label>
            	        <input type="text" id="receivername" class="form-control" required>
            	    </div>
                        <p class="text-warning"><small>this action Cannot be Undone</small></p>
                        <input type="hidden" id="hiddencourieridtr">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
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
            courierLoc();
        });

        $(".xp-menubar").on('click',function(){
            $("#sidebar").toggleClass('active');
            $("#content").toggleClass('active');
        });

        $('.xp-menubar,.body-overlay').on('click',function(){
            $("#sidebar,.body-overlay").toggleClass('show-nav');
        });

        // Display Function
        function displayData() {
            var displayData = "true";
            
            $.ajax({
                url: '../function.php',
                type: 'post',
                data: {
                    courierdisplaySend: displayData
                },
                success: function(data, status) {
                    $('#example').html(data);
                }
            });
        }

        setInterval(courierLoc, 100000);

        
        // Courier Location Function
        function courierLoc() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition (
                    function(position) {
                        let courierlat = position.coords.latitude;
                        let courierlng = position.coords.longitude;
                        $.post("../function.php", {
                            courierlatSend: courierlat,
                            courierlngSend: courierlng
                        }, function(data, status) {

                            const iconCourier = {
                                url: '../img/courier.svg',
                                scaledSize: new google.maps.Size(30, 26)
                            };


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
                            
                            setInterval(remMarker, 95000);

                            function remMarker() {
                                markerCourier.setMap(null);
                                markerCourier = [];
                            }
                        });

                    }
                );
            }
        }

        // Courier Report Function
        function courierReport(courieridtr) {
            $('#hiddencourieridtr').val(courieridtr);

            $.post("../function.php", {courieridtrSend: courieridtr},
            function(data, status) {
                var courierReport = JSON.parse(data);
                $('#receiveritemname').html(courierReport.itemname);
                $('#receivername').val(courierReport.username);

                var request = {
                    origin: {lat: parseFloat(courierReport.warehouselat), lng: parseFloat(courierReport.warehouselon)},
                    destination: courierReport.address,
                    travelMode: 'DRIVING'
                };

                function showRoute() {
                    directionsService.route(request, function(result, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            directionsRenderer.setDirections(result);
                        }
                    }); 
                }

                setTimeout(showRoute, 500);
            });

            $('#couriermodal').modal('show');
        }

        $('#courierform').submit(function (e) {
            e.preventDefault();
            var hiddencourieridtr = $('#hiddencourieridtr').val();
            var receivername = $('#receivername').val();

            $.post("../function.php", {
                hiddencourieridtrSend: hiddencourieridtr,
                receivernameSend: receivername
            }, function(data, status) {
                $('#couriermodal').modal('hide');

                $('#example').DataTable().destroy();
                displayData();
            });
        });
        
    </script>
</body>
</html>