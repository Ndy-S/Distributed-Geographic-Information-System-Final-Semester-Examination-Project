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
    
    if (isset($_SESSION['log']) && $_SESSION['role'] == '0') {

    } else if (isset($_SESSION['log']) && $_SESSION['role'] == '2') {
        header('location: userdebthome.php');
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
		            <a href="#" class="dashboard"><i class="material-icons">category</i>Items Data </a>
		        </li>
                <li class="dropdown">
		            <a href="#homeSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="material-icons">receipt_long</i>Transaction</a>
                    <ul class="show list-unstyled menu" id="homeSubmenu1">
			            <li><a href="userhistory.php">Status</a></li>
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
						                <h2 class="ml-lg-2">Items Data</h2>
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
                            <?php
                                $iduser = $_SESSION['iduser'];
                                $getalluser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
                                $datauser = mysqli_fetch_array($getalluser);
                                $role = $datauser['role'];

                                $_SESSION['role'] = $role;
                            ?>
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

    <!-- Borrow Modal -->
    <div class="modal fade" id="borrowitemmodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Borrow Item</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!-- Modal body -->
                <form id="borrowitemform">
                    <div class="modal-body">
                        <div class="form-group">
                            <p>Are you sure want to borrow this item "<b id="borrowitemname"></b>" ?</p>
                            <p><small class="text-danger">Your total loan is "<b id="borrowloan"></b>", Each user only can borrow 5 items at once!</small></p>
                        </div>
                        <div class="form-group">
                            <label>Amount <small>(Item Available: <span id="borrowitemava"></span>)</small></label>
                            <input type="number" id="borrowqty" class="form-control" min="1" required>
                        </div>
                            
                        <div class="form-group">                    
                            <input type="checkbox" class="courier" id="courier">
                            <label for="courier">Use Courier</label>
                            <div class="useCourier">
                                <label>Address</label>
		                        <input type="text" id="address" class="form-control alamat">
                                <div class="map"></div>
                                <div class="distance"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php 
                                date_default_timezone_set('Asia/Jakarta');
                                $borrowdate = date('d-m-Y H:i:s');
                                $borrowdeadline = date("d-m-Y H:i:s", mktime(date("H"),date("i"),date("s"),date("m"),date("d") +30, date("Y")));
                            ?>
                            <label>Current Date <small>(Asia/Jakarta)</small></label>
                            <input type="text" id="borrowdate" value="<?=$borrowdate;?>" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Return Deadline <small>(30 Days)</small></label>
                            <input type="text" id="borrowdeadline" value="<?=$borrowdeadline;?>" class="form-control" readonly>
                        </div>
                        <input type="hidden" id="hiddenitemid">
                        <input type="hidden" id="hiddeniduser">
                        <input type="hidden" id="hiddenlat">
                        <input type="hidden" id="hiddenlon">
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="reset-btn" hidden>Reset</button>
                        <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="borrowitem">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Borrow Limit Modal -->
    <div class="modal fade" id="borrowitemlimitmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Borrow Item</h5>
                <button type="reset" class="reset-btn" hidden>Reset</button>
                <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <!-- Modal body -->
            <form>
                <div class="modal-body">
                    <div class="form-group">
                    <p>You already reached your <b class="text-danger">loan limit</b>! You can't borrow this item "<b id="borrowitemnameLimit"></b>"</p>
                    <p><small class="text-danger">Your total loan is "<b id="borrowloanlimit"></b>", Each user only can borrow 5 items at once!</small></p>
                    </div>
                    <div class="form-group">
                        <label>Amount <small>(Item Available: <span id="borrowitemavaLimit"></span>)</small></label>
                        <input type="number" class="form-control" min="1" readonly>
                    </div>
                    <div class="form-group">
                        <?php 
                            date_default_timezone_set('Asia/Jakarta');
                            $borrowdate = date('d-m-Y H:i:s');
                            $borrowdeadline = date("d-m-Y H:i:s", mktime(date("H"),date("i"),date("s"),date("m"),date("d") +30, date("Y")));
                        ?>
                        <label>Current Date <small>(Asia/Jakarta)</small></label>
                        <input type="text" name="borrowdate" value="<?=$borrowdate;?>" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Return Deadline <small>(30 Days)</small></label>
                        <input type="text" name="borrowdeadline" value="<?=$borrowdeadline;?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"  data-dismiss="modal">OK</button>
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
        let getMap = document.querySelectorAll('.map');
        getMap.forEach((mapGet) => {
            let mapOptions = {
                center: currLoc(),
                zoom: 15,
                disableDefaultUI: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            let directionsService = new google.maps.DirectionsService();
            let directionsRenderer = new google.maps.DirectionsRenderer();

            let map = new google.maps.Map(mapGet, mapOptions);
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

            let searchField = document.querySelectorAll('.alamat');

            searchField.forEach((alamat) => {

                let inital = '0.530412265187149, 101.41140601246421';
                let autocompleteSearch = new google.maps.places.Autocomplete(alamat);
                google.maps.event.addListener(autocompleteSearch, 'place_changed', function() {  
                    let distance = [];
                    let short;
                    let distanceSave;

                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        "address": alamat.value
                    }, 
                    function(results) {
                        for (let i = 0; i < locations.length; i++) {
                            const dakota = {lat: locations[i][0], lng: locations[i][1]};
                            var mk1 = new google.maps.LatLng(dakota);
                            let mk2 = results[0].geometry.location;
                            function haversine_distance(mk1, mk2) {
                                var R = 6371.0710; // Radius of the Earth in miles
                                var rlat1 = mk1.lat() * (Math.PI/180); // Convert degrees to radians
                                var rlat2 = mk2.lat() * (Math.PI/180); // Convert degrees to radians
                                var difflat = rlat2-rlat1; // Radian difference (latitudes)
                                var difflon = (mk2.lng()-mk1.lng()) * (Math.PI/180); // Radian difference (longitudes)
                                var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat/2)*Math.sin(difflat/2)+Math.cos(rlat1)*Math.cos(rlat2)*Math.sin(difflon/2)*Math.sin(difflon/2)));
                                return d;
                            }
                            distance[i] = haversine_distance(mk1, mk2);
                            if (short == null) {
                                distanceSave = dakota;
                                short = distance[i];

                                document.getElementById("hiddenlat").value = locations[i][0];
                                document.getElementById("hiddenlon").value = locations[i][1];
                            } else if (short > distance[i]) {
                                distanceSave = dakota;
                                short = distance[i];

                                document.getElementById("hiddenlat").value = locations[i][0];
                                document.getElementById("hiddenlon").value = locations[i][1];
                            }
                        }

                        let place = autocompleteSearch.getPlace();
                  	    let request = {
                  	    	origin: distanceSave,
                  	    	destination: alamat.value,
                  	    	travelMode: 'DRIVING',
                  	    	unitSystem: google.maps.UnitSystem.METRIC
                  	    };
                        directionsService.route(request, function(result, status) {
                        	if (status == google.maps.DirectionsStatus.OK) {
                                let output = document.querySelectorAll('.distance');
                                let distance = result.routes[0].legs[0].distance.value;
                                let distanceKilo = distance / 1000;
                                output.forEach((out) => {
                                    out.innerHTML = "<div class='alert-info' style='padding: 10px;'><table><tr><td>Driving distance</td> <td>:</td> <td>" + distanceKilo + " KM.</td></tr></td><td>Estimated Duration</td> <td>:</td> <td>" + result.routes[0].legs[0].duration.text + ".</td></table></div>";
                                })
                                let autocompleteSearch = new google.maps.places.Autocomplete(alamat);
                        		directionsRenderer.setDirections(result);
                        	}
                        });
                    });

                });

                let checkbox = document.querySelectorAll('.courier');
                let useCourier = document.querySelectorAll('.useCourier');
            
                checkbox.forEach((check) => { 
                    check.addEventListener('change', (event) => {
                      if (event.currentTarget.checked) {            
                        useCourier.forEach((useCour) => {
                            useCour.style.display = 'block';
                        });
                        alamat.required = true;
                        directionsRenderer.setDirections({routes: []});
                      } else {
                        useCourier.forEach((useCour) => {
                            useCour.style.display = 'none';
                        });
                        alamat.value = '';
                        alamat.required = false;
                        directionsRenderer.setDirections({routes: []});
                        let output = document.querySelectorAll('.distance');
                        output.forEach((out) => {
                            out.innerHTML = "";
                        })
                      }
                    });
                });
            });
        })
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
            let useCourier = document.querySelectorAll('.useCourier');
            let alamat = document.querySelectorAll('.alamat');
            let output = document.querySelectorAll('.distance');
            let courier = document.querySelectorAll('.courier');
            useCourier.forEach((useCour) => {
                useCour.style.display = 'none';
            });
            alamat.forEach((alm) => {
                alm.value = '';
            });
            output.forEach((out) => {
                out.innerHTML = "";
            })
            courier.forEach((cour) => {
                cour.checked = false;
            })
            $(".reset-btn").click();
        });
        
        $(document).keypress(
          function(event){
            if (event.which == '13') {
              event.preventDefault();
            }
        });

        // Display Function
        function displayData() {
            var displayData = "true";
            $.ajax({
                url: '../function.php',
                type: 'post',
                data: {
                    displayUserSend: displayData
                },
                success: function(data, status) {
                    $('#example').html(data);
                }
            });
        }

        // Borrow Item Function
        function borrowItem(borrowitemid, borrowitemava, borrowiduser) {
            $('#hiddenitemid').val(borrowitemid);
            $('#hiddeniduser').val(borrowiduser);

            $.post("../function.php", {
                borrowitemidsend: borrowitemid,
                borrowitemavasend: borrowitemava,
                borrowidusersend: borrowiduser
            }, function(data, status) {
                var borrowitemid = JSON.parse(data);

                $('#borrowitemname').html(borrowitemid.itemname);
                $('#borrowitemava').html(borrowitemid.itemava);
                $('#borrowloan').html(borrowitemid[0]);
                $('#borrowqty').attr({'max': borrowitemid[1]});
            });

            $('#borrowitemmodal').modal('show');
        }

        $('#borrowitemform').submit(function (e) {
            e.preventDefault();
            var borrowqty = $('#borrowqty').val();
            var address = $('#address').val();
            var borrowdate = $('#borrowdate').val();
            var borrowdeadline = $('#borrowdeadline').val();
            
            var warehouselat = $('#hiddenlat').val();
            var warehouselon = $('#hiddenlon').val();
            var hiddenitemid = $('#hiddenitemid').val();
            var hiddeniduser = $('#hiddeniduser').val();

            $.post("../function.php", {
                borrowqtySend: borrowqty,
                addressSend: address,
                borrowdateSend: borrowdate,
                borrowdeadlineSend: borrowdeadline,
                hiddenitemidSend: hiddenitemid,
                hiddeniduserSend: hiddeniduser,
                warehouselatSend: warehouselat,
                warehouselonSend: warehouselon
            }, function(data, status) {
                $('#example').DataTable().destroy();
                displayData();

                $(".reset-btn").click();
                $('#borrowitemmodal').modal('hide');
            });
        });

        // Borrow Item Limit Function
        function borrowItemLimit(borrowitemid, borrowitemava, borrowiduser) {
            $.post("../function.php", {
                borrowitemidLimitsend: borrowitemid,
                borrowitemavaLimitsend: borrowitemava,
                borrowiduserLimitsend: borrowiduser
            }, function(data, status) {
                var borrowitemid = JSON.parse(data);

                $('#borrowitemnameLimit').html(borrowitemid.itemname);
                $('#borrowitemavaLimit').html(borrowitemid.itemava);
                $('#borrowloanlimit').html(borrowitemid[0]);
            });

            $('#borrowitemlimitmodal').modal('show');
        }

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