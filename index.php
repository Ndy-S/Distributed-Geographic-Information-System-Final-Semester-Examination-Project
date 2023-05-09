<?php
    require 'function.php';

    if (isset($_POST['login'])) {
        $email = $_POST['loginemail'];
        $password = $_POST['loginpassword'];

        $checkdatabase = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$password'");
        $getuserdata = mysqli_fetch_array($checkdatabase);

        $count = mysqli_num_rows($checkdatabase);

        if ($count > 0) {
            $iduser = $getuserdata['iduser'];
            $username = $getuserdata['username'];
            $email = $getuserdata['email'];
            $password = $getuserdata['password'];
            $role = $getuserdata['role'];
            $image = $getuserdata['image'];
            $debt = $getuserdata['debt'];
    
            $_SESSION['iduser'] = $iduser;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['role'] = $role;
            $_SESSION['image'] = $image;
            $_SESSION['debt'] = $debt;

            $getdebt = mysqli_query($conn, "SELECT * FROM debt WHERE iduser = '$iduser'");
            $datadebt = mysqli_fetch_array($getdebt);
    
            if ($datadebt) {
    
            } else {
                $insertdebt = mysqli_query($conn, "INSERT INTO debt (iduser, debt, debtstatus, payqty) VALUES ('$iduser', '0', '0', '0')");
            }

            if ($_SESSION['role'] == '0') {
                $_SESSION['log'] = 'True';
                $_SESSION['role'] = $role;
                header('location: user/userhome.php');
            } else if ($_SESSION['role'] == '1') {
                $_SESSION['log'] = 'True';
                $_SESSION['role'] = $role;
                header('location: admin/adminhome.php');
            } else if ($_SESSION['role'] == '2') {
                $_SESSION['log'] = 'True';
                $_SESSION['role'] = $role;
                header('location: user/userdebthome.php');
            } else if ($_SESSION['role'] == '3') {
                $_SESSION['log'] = 'True';
                $_SESSION['role'] = $role;
                header('location: courier/courierhome.php');
            }
        } else {
            echo "<script>alert('The email or password you entered is incorrect!');</script>";
        };
    };

    if (isset($_POST['register'])) {
        $username = $_POST['registerusername'];
        $email = $_POST['registeremail'];
        $password = $_POST['registerpassword'];
        
        $allowed_extension = array('png','jpg');
        $name = $_FILES['file']['name'];
        $dot = explode('.',$name);
        $extension = strtolower(end($dot));
        $size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $image = md5(uniqid($name,true) . time()).'.'.$extension;

        $checkcurrentuser = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
        $getdatauser = mysqli_fetch_array($checkcurrentuser);

        if ($getdatauser) {
            echo "<script>alert('Email has been used, please try another email!');</script>";
        } else {
            if (in_array($extension, $allowed_extension) === true) {
                if ($size < 15000000) {
                    move_uploaded_file($file_tmp, './img/'.$image);

                    $addtodatabase = mysqli_query($conn, "INSERT INTO user (username, email, password, image) VALUES ('$username', '$email', '$password', '$image')");
                    
                    if ($addtotable) {
                        header('location: index.php');
                    } else {
                        echo 'Failed!';
                        header('location: index.php');
                    }
                } else {
                    echo "<script> alert('File size too big!');</script>";
                }
            } else {
                $image = 'user.jpg';
                $addtodatabase = mysqli_query($conn, "INSERT INTO user (username, email, password, image) VALUES ('$username', '$email', '$password', '$image')");

                if ($addtotable) {
                    header('location: index.php');
                } else {
                    echo 'Failed!';
                    header('location: index.php');
                }
            }
        }

    };

    if (!isset($_SESSION['log'])) {
        
    } else {
        if ($_SESSION['role'] == '0') {
            $_SESSION['log'] = 'True';
            header('location: user/userhome.php');
        } else if ($_SESSION['role'] == '1') {
            $_SESSION['log'] = 'True';
            header('location: admin/adminhome.php');
        } else if ($_SESSION['role'] == '2') {
            $_SESSION['log'] = 'True';
            header('location: user/userdebthome.php');
        } else if ($_SESSION['role'] == '3') {
            $_SESSION['log'] = 'True';
            header('location: courier/courierhome.php');
        }
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project UAS SIGT A</title>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=#&libraries=places"></script>
    <link href="css/styles.css" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">
    <link type="text/css" href="css/dataTables.checkboxes.css" rel="stylesheet"/>
	<link type="text/css" href="css/datatables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/custom.css" rel="stylesheet"/>
</head>

<body>
    <div class="wrapper">
        <div class="body-overlay"></div>

        <!-- Sidebar HTML Code Start -->
        <div id="sidebar">
	        <div class="sidebar-header">
		        <h3><img src="img/logo.png" class="img-fluid"/><span>Project UAS</span></h3>
		    </div>
            
            <ul class="list-unstyled component m-0">
		        <li class="active">
		            <a href="#" class="dashboard"><i class="material-icons">category</i>Dashboard</a>
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
							        <li class="dropdown nav-item">
                                        <a class="nav-link" href="#notifmodal" data-toggle="modal"><span class="material-icons">notifications</span><span class="notification">1</span></a>
							        </li>
							        <li class="dropdown nav-item">
							            <a class="nav-link" href="#" data-toggle="dropdown"><img src="img/unuser.png" style="width:40px; border-radius:50%;"/><span class="xp-user-live"></span></a>
								        <ul class="dropdown-menu small-menu">
								    	    <li><a href="#loginmodal" data-toggle="modal"><span class="material-icons">login</span>Login</a></li>
								            <li><a href="#registermodal" data-toggle="modal"><span class="material-icons">how_to_reg</span>Register</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    </div>
                    
                    <div class="xp-breadcrumbbar text-center">
				        <h4 class="page-title">Visitor Dashboard</h4>
					    <ol class="breadcrumb">
					        <li class="breadcrumb-item">You don't have access to any features, please login!</li>
					    </ol>
				    </div>
                </div>
		    </div>
		    <!-- Topbar HTML Code End -->
		  
            <!-- Main Content Start -->
            <div id="maps"></div>

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

    <!-- Notification Modal Start -->
    <div class="modal fade" tabindex="-1" id="notifmodal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notification <small class="text-muted">(1)</small></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <label><b class="text-danger">You are not logged in yet</b>, to access more features please <a href="#loginmodal" data-toggle="modal" data-dismiss="modal" class="text-primary">login</a> or <a href="#registermodal" data-toggle="modal" data-dismiss="modal" class="text-primary">register</a>!</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Notification Modal End -->

    <!-- Login Modal Start -->
    <div class="modal fade" tabindex="-1" id="loginmodal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
            	            <label>Email</label>
            	        	<input type="email" name="loginemail" class="form-control" placeholder="Type your email" required>
            	        </div>
            	        <div class="form-group">
            	            <label>Password</label>
                            <input type="password" name="loginpassword" class="form-control" placeholder="Type your password" required>
            	        </div>
                        <a href="#registermodal" data-toggle="modal" data-dismiss="modal">Don't have an account yet? <span class="text-primary">Click to Register!</span></a>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="reset-btn" hidden>Reset</button>
                        <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="login">Login</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Login Modal End -->

    <!-- Register Modal Start -->
    <div class="modal fade" tabindex="-1" id="registermodal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Register</h5>
                    <button type="reset" class="reset-btn" hidden>Reset</button>
                    <button type="button" class="close reset" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
            	            <label>Username</label>
            	        	<input type="text" name="registerusername" class="form-control" placeholder="Type your username" required>
            	        </div>
                        <div class="form-group">
            	            <label>Email</label>
            	        	<input type="email" name="registeremail" class="form-control" placeholder="Type your email" required>
            	        </div>
            	        <div class="form-group">
            	            <label>Password</label>
                            <input type="password" name="registerpassword" class="form-control" placeholder="Type your password" required>
            	        </div>
                        <div class="form-group">
                            <label>Profile <small>(Only JPG and PNG formats with Max Size of 15 MB)</small></label>
            	        	<input type="file" name="file" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="reset-btn" hidden>Reset</button>
                        <button type="button" class="btn btn-secondary reset" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="register">Register</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Register Modal End -->

    <script>
        let map = new google.maps.Map(document.getElementById("maps"), {
            mapId: '5f5954f022beaf06',
            center: { lat: 0.4764885246421527, lng: 101.38129313475055}, 
            zoom: 17,
            disableDefaultUI: true
        });

        const markers = [
            ["<b>Hendy Saputra 2003113132</b>", 0.4774336136714595, 101.38126079022958, "./img/male.svg", 38, 31, google.maps.Animation.BOUNCE], 
            ["<b>Seteven 2003114203</b>", 0.47536366628611165, 101.37988375483279, "./img/boy.svg", 26, 29, google.maps.Animation.BOUNCE], 
            ["<b>Elvina Carolina 2003111123</b>", 0.47503580166210957, 101.38224247866373, "./img/girl.svg", 24, 30, google.maps.Animation.BOUNCE],
            ["<marquee><b>Kelompok Project UAS SIGT</hb></marquee>", 0.4762860439906126, 101.38100866259013, "./img/flag.svg", 25, 31, google.maps.Animation.DROP]
        ];


        for (let i = 0; i < markers.length; i++) {
            const currMarker = markers[i];

            const marker = new google.maps.Marker({
                position: {lat: currMarker[1], lng: currMarker[2]},
                map,
                title: currMarker[0],
                icon: {
                    url: currMarker[3],
                    scaledSize: new google.maps.Size(currMarker[4], currMarker[5])
                },
                animation: currMarker[6]
            });

            const infowindow = new google.maps.InfoWindow({
                content: currMarker[0]
            });

            if (i == 3) {
                infowindow.open(map, marker);
            }

            marker.addListener("click", () => {
                infowindow.open(map, marker);
            })
        }
    </script>
    <script src="js/jquery-3.6.1.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/dataTables.checkboxes.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
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
        });
    </script>
</body>
</html>
