<?php
    require '../function.php';

    if (isset($_SESSION['log']) && $_SESSION['role'] == '1') {

    } else {
        header('location: ../index.php');
    }
?>
<html>
<head>
  <title>Transaction History</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
<div class="container">
	<h2>Transaction History</h2>
	<h4>(Database)</h4>
		<div class="data-tables datatable-dark">

            <table id="mauexport" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Item Name</th>
                        <th>Amount</th>
                        <th>Return Request</th>
                        <th>Returned</th>
                        <th>Address</th>
                        <th>Borrow Date</th>
                        <th>Return Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                    $getallitem = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser");
                    $i = 1;
                    while ($data = mysqli_fetch_array($getallitem)) {
                        $idtr = $data['idtr'];
                        $itemid = $data['itemid'];
                        $username = $data['username'];
                        $itemname = $data['itemname'];
                        $borrowqty = $data['borrowqty'];
                        $req = $data['req'];
                        $returned = $data['returned'];
                        $address = $data['address'];
                        $borrowdate = $data['borrowdate'];
                        $borrowdeadline = $data['borrowdeadline'];
                        $status = $data['status'];
                ?>
                        <tr id="<?php echo $idtr; ?>">
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $username; ?></td>
                        <td><?php echo $itemname; ?></td>
                        <td><?php echo $borrowqty; ?></td>
                        <td><?php echo $req; ?></td>
                        <td><?php echo $returned; ?></td>
                        <td>
                            <?php 
                                if (empty($address)) {
                                    echo 'None';
                                } else {
                                    echo $address;
                                }
                             ?>
                        </td>
                        <td><?php echo $borrowdate; ?></td>
                        <td><?php echo $borrowdeadline; ?></td>
                        <td>
                            <?php
                                if ($status == 0) {
                                    echo "Canceled";
                                } else if ($status == 1) {
                                    echo "Waiting for Confirmation";
                                } else if ($status == 2) {
                                    echo "Borrow Confirmed";
                                } else if ($status == 3) {
                                    echo "Rejected by Admin";
                                } else if ($status == 4) {
                                    echo "Return Partially Request";
                                } else if ($status == 5) {
                                    echo "Return Partially Confirmed";
                                } else if ($status == 6) {
                                    echo "Return All Request";
                                } else if ($status == 7) {
                                    echo "Returned";
                                } else if ($status == 8) {
                                    echo "Rejected by System";
                                } else if ($status == 9) {
                                    echo "Return Canceled";
                                } else if ($status == 10) {
                                    echo "Return Rejected by Admin";
                                } else if ($status == 11) {
                                    echo "Item Missing";
                                } else {
                                    echo "System Error";
                                }
                            ?>
                        </td>
                    </tr>
                <?php 
                    }; 
                ?>           
                </tbody>
            </table>		
		</div>
    </div>
	
    <script>
        $(document).ready(function() {
            $('#mauexport').DataTable( {
                dom: 'Bfrtip',
                buttons: [
                    'copy','csv','excel', 'pdf', 'print'
                ]
            } );
        } );
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
</body>
</html>