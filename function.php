<?php
    session_start();
    $conn = mysqli_connect("localhost","root","","project_sigt");
    extract($_POST);
    
    // Profile Update
    if (isset($_POST['profileupdate'])) {
        $iduser = $_SESSION['iduser'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $allowed_extension = array('png','jpg');
        $name = $_FILES['file']['name'];
        $dot = explode('.',$name);
        $extension = strtolower(end($dot));
        $size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $image = md5(uniqid($name,true) . time()).'.'.$extension;

        if (in_array($extension, $allowed_extension) === true) {
            if ($size < 15000000) {
                move_uploaded_file($file_tmp, '../img/'.$image);

                $addtodatabase = mysqli_query($conn, "UPDATE user SET username = '$username', email = '$email', password = '$password', image = '$image' WHERE iduser = '$iduser'"); 
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                $_SESSION['image'] = $image;

                if ($addtotable) {
                    header('location: ../index.php');
                } else {
                    echo 'Failed!';
                    header('location: ../index.php');
                }
            } else {
                echo "<script> alert('File size too big!');</script>";
            }
        } else {
            $addtodatabase = mysqli_query($conn, "UPDATE user SET username = '$username', email = '$email', password = '$password' WHERE iduser = '$iduser'"); 
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            if ($addtodatabase) {
                header('location: ../index.php');
            } else {
                echo 'Failed!';
                header('location: ../index.php');
            }
        }
    }

    // ADMIN - Display Item
    if (isset($_POST['displaySend'])) {
        $table = '
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Item Price</th>
                    <th>Available Item</th>
                    <th>Item Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';

        $result = mysqli_query($conn, "SELECT * FROM item");
        $i = 1;
        while ($data = mysqli_fetch_array($result)) {
            $itemid = $data['itemid'];
            $itemname = $data['itemname'];
            $itemdesc = $data['itemdesc'];
            $itemprice = $data['itemprice'];
            $itemava = $data['itemava'];
            $itemtotal = $data['itemtotal'];
            $table.='
                <tr id="'.$itemid.'">
                    <td>'.$i++.'</td>
                    <td>'.$itemname.'</td>
                    <td>'.$itemdesc.'</td>
                    <td>Rp '.number_format($itemprice,2,',','.').'</td>
                    <td>'.$itemava.'</td>
                    <td>'.$itemtotal.'</td>
                    <td>
                        <a class="edit" onclick="getItem('.$itemid.')">
                            <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                        </a>
                        <a class="delete" onclick="deleteItem('.$itemid.')">
                            <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                        </a>
                    </td>
                </tr>';
        }
        $table.='</tbody>';

        $datatable = "<script>   
            var table = $('#example').DataTable({
                'columnDefs': [{
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                }],
                'select': {
                    'style': 'multi'
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.DT_RowId);
                    $(nRow).attr('id', 'id_' + aData.DT_RowId);
                },
                'order': [
                    [0, 'asc']
                ]
            });
            </script>"; 
        echo $table;
        echo $datatable;
    }

    // ADMIN - Insert Item
    if (isset($_POST['itemNameSend']) && isset($_POST['itemDescSend']) && isset($_POST['itemPriceSend']) && isset($_POST['itemAvaSend']) && isset($_POST['itemTotalSend'])) {
        $checkcurrentitem = mysqli_query($conn, "SELECT * FROM item WHERE itemname = '$itemNameSend'");
        $getdataitem = mysqli_fetch_array($checkcurrentitem);

        if ($getdataitem) {
            echo "<script>alert('This item already exist in Database!');</script>";
        } else {
            $addtotable = mysqli_query($conn, "INSERT INTO item (itemname, itemava, itemdesc, itemprice, itemtotal) VALUES ('$itemNameSend', '$itemAvaSend', '$itemDescSend', '$itemPriceSend', '$itemTotalSend')");
        }
    }

    // ADMIN - Edit Item
    if (isset($_POST['updateid'])) {
        $getitemid = $_POST['updateid'];

        $result = mysqli_query($conn , "SELECT * FROM item WHERE itemid = $getitemid");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['hiddendata'])) {
        $uniqueid = $_POST['hiddendata'];
        $itemName = $_POST['editItemName'];
        $itemDesc = $_POST['editItemDesc'];
        $itemPrice = $_POST['editItemPrice'];
        $itemAva = $_POST['editItemAva'];
        $itemTotal = $_POST['editItemTotal'];

        $result = mysqli_query($conn, "UPDATE item SET itemname='$itemName', itemdesc='$itemDesc', itemprice='$itemPrice', itemava='$itemAva', itemtotal='$itemTotal' WHERE itemid = '$uniqueid'");
    
    }

    // ADMIN - Delete Item
    if (isset($_POST['updateiddel'])) {
        $getitemiddel = $_POST['updateiddel'];

        $result = mysqli_query($conn , "SELECT * FROM item WHERE itemid = $getitemiddel");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }
    
    if (isset($_POST['hiddendatadel'])) {
        $uniqueid = $_POST['hiddendatadel'];

        $result = mysqli_query($conn, "DELETE FROM item WHERE itemid = '$uniqueid'");
    }


    // ADMIN - Delete Multiple Item
    if (isset($_POST['selectedArray'])) {
        $arrayList = $_POST['selectedArray'];

        $i = 0;
        foreach ($arrayList as $each) {
            $array[$i] = $each;
            $i += 1;
        }
        foreach ($array as $itemid) {
            $delete = mysqli_query($conn, "DELETE FROM item WHERE itemid = '$itemid'");
        };
    }


    // ADMIN - Display Incoming Item
    if (isset($_POST['displayIncomingSend'])) {
        $table = '
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Item Name</th>
                    <th>Date</th>
                    <th>Receiver Name</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
        
        $result = mysqli_query($conn, "SELECT * FROM itemin n, item i WHERE i.itemid = n.itemid");
        $i = 1;
        while ($data = mysqli_fetch_array($result)) {
            $itemid = $data['itemid'];
            $inid = $data['inid'];
            $itemname = $data['itemname'];
            $date = $data['date'];
            $receivername = $data['receivername'];
            $qty = $data['qty'];
            $table.='
                <tr id="'.$inid.'">
                    <td>'.$i++.'</td>
                    <td>'.$itemname.'</td>
                    <td>'.$date.'</td>
                    <td>'.$receivername.'</td>
                    <td>'.$qty.'</td>
                    <td>
                        <a class="edit" onclick="getItem('.$itemid.','.$inid.')">
                            <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                        </a>
                        <a class="delete" onclick="deleteItem('.$itemid.','.$inid.','.$qty.')">
                            <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                        </a>
                    </td>
                </tr>';
        }
        $table.='</tbody>';

        $datatable = "<script>   
            var table = $('#example').DataTable({
                'columnDefs': [{
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                }],
                'select': {
                    'style': 'multi'
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.DT_RowId);
                    $(nRow).attr('id', 'id_' + aData.DT_RowId);
                },
                'order': [
                    [0, 'asc']
                ]
            });
            </script>";
        echo $table;
        echo $datatable;
    }

    // ADMIN - Add Incoming Item
    if (isset($_POST['itemIdSend']) && isset($_POST['receiverNameSend']) && isset($_POST['qtySend'])) { 
        $checkcurrent = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemIdSend'");
        $getdata = mysqli_fetch_array($checkcurrent);

        $currentqty = $getdata['itemtotal'];
        $totalcurrentqty = $currentqty + $qtySend;

        $currentava = $getdata['itemava'];
        $totalcurrentava = $currentava + $qtySend;

        $addtoin = mysqli_query($conn, "INSERT INTO itemin (itemid, receivername, qty) VALUES ('$itemIdSend', '$receiverNameSend', '$qtySend')");
        $updateqtyin = mysqli_query($conn, "UPDATE item SET itemtotal = '$totalcurrentqty', itemava = '$totalcurrentava' WHERE itemid = '$itemIdSend'");
    }

    // ADMIN - Edit Incoming Item
    if (isset($_POST['itemidSend']) && isset($_POST['inidSend'])) {
        $getitemid = $_POST['itemidSend'];
        $getinid = $_POST['inidSend'];
    
        $result = mysqli_query($conn , "SELECT * FROM itemin n, item i WHERE i.itemid = n.itemid && inid = '$getinid'");
    
        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }
    
        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['itemidUpdateSend']) && isset($_POST['inidUpdateSend'])) {
        $itemid = $_POST['itemidUpdateSend'];
        $inid = $_POST['inidUpdateSend'];
        $receivername = $_POST['receiverNameEditSend'];
        $qty = $_POST['qtyEditSend'];
        
        $checkitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
        $theitems = mysqli_fetch_array($checkitem);
        $currentitemtotal = $theitems['itemtotal'];
        $currentava = $theitems['itemava'];
        
        $checkqty = mysqli_query($conn, "SELECT * FROM itemin WHERE inid = '$inid'");
        $theqty = mysqli_fetch_array($checkqty);
        $currentqty = $theqty['qty'];

        if ($qty > $currentqty) {
            $diff = $qty - $currentqty;
            $reducetotal = $currentitemtotal + $diff;
            $reduceava = $currentava + $diff;
            $reduceitem = mysqli_query($conn, "UPDATE item SET itemtotal = '$reducetotal', itemava = '$reduceava' WHERE itemid = '$itemid'");
            $update = mysqli_query($conn, "UPDATE itemin SET qty = '$qty', receivername = '$receivername' WHERE inid = '$inid'");
        } else {
            $diff = $currentqty - $qty;
            $reducetotal = $currentitemtotal - $diff;
            $reduceava = $currentava - $diff;
            $reduceitem = mysqli_query($conn, "UPDATE item SET itemtotal = '$reducetotal', itemava = '$reduceava' WHERE itemid = '$itemid'");
            $update = mysqli_query($conn, "UPDATE itemin SET qty = '$qty', receivername = '$receivername' WHERE inid = '$inid'");
        }
    }

    // ADMIN - Delete Incoming Item
    if (isset($_POST['itemidDelSend']) && isset($_POST['inidDelSend']) && isset($_POST['qtyDelSend'])) {
        $inid = $_POST['inidDelSend'];

        $result = mysqli_query($conn , "SELECT * FROM itemin n, item i WHERE i.itemid = n.itemid && inid = '$inid'");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['hiddenitemidDelSend']) && isset($_POST['hiddeninidDelSend']) && isset($_POST['hiddenqtyDelSend'])) {
        $itemid = $_POST['hiddenitemidDelSend'];
        $inid = $_POST['hiddeninidDelSend'];
        $qty = $_POST['hiddenqtyDelSend'];
    
        $getdataitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
        $data = mysqli_fetch_array($getdataitem);
        $itemtotal = $data['itemtotal'];
        $itemava = $data['itemava'];

        $reducetotal = $itemtotal - $qty;
        $reduceava = $itemava - $qty;
        
        $update = mysqli_query($conn, "UPDATE item SET itemtotal = '$reducetotal', itemava = '$reduceava' WHERE itemid = '$itemid'");
        $deletedata = mysqli_query($conn, "DELETE FROM itemin WHERE inid = '$inid'");
    }

    // ADMIN - Delete Multiple Incoming Item
    if (isset($_POST['selectedIncomingArray'])) {
        $arrayList = $_POST['selectedIncomingArray'];

        $i = 0;
        foreach ($arrayList as $each) {
            $array[$i] = $each;
            $i++;
        }
        foreach ($array as $inid) {
            $getdataitemin = mysqli_query($conn, "SELECT * FROM itemin WHERE inid = '$inid'");
            $data = mysqli_fetch_array($getdataitemin);
            $qty = $data['qty'];
            $itemid = $data['itemid'];

            $getdataitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
            $data = mysqli_fetch_array($getdataitem);
            $itemtotal = $data['itemtotal'];
            $itemava = $data['itemava'];

            $reducetotal = $itemtotal - $qty;
            $reduceava = $itemava - $qty;

            $update = mysqli_query($conn, "UPDATE item SET itemtotal = '$reducetotal', itemava = '$reduceava' WHERE itemid = '$itemid'");
            $delete = mysqli_query($conn, "DELETE FROM itemin WHERE inid = '$inid'");
        };
    }

    // ADMIN - Display Exit Item
    if (isset($_POST['displayExitSend'])) {
        $table = '
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Item Name</th>
                    <th>Date</th>
                    <th>Receiver Name</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
        
        $result = mysqli_query($conn, "SELECT * FROM itemout t, item i WHERE i.itemid = t.itemid");
        $i = 1;
        while ($data = mysqli_fetch_array($result)) {
            $itemid = $data['itemid'];
            $outid = $data['outid'];
            $itemname = $data['itemname'];
            $date = $data['date'];
            $receivername = $data['receivername'];
            $qty = $data['qty'];
            $table.='
                <tr id="'.$outid.'">
                    <td>'.$i++.'</td>
                    <td>'.$itemname.'</td>
                    <td>'.$date.'</td>
                    <td>'.$receivername.'</td>
                    <td>'.$qty.'</td>
                    <td>
                        <a class="edit" onclick="getItem('.$itemid.','.$outid.')">
                            <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                        </a>
                        <a class="delete" onclick="deleteItem('.$itemid.','.$outid.','.$qty.')">
                            <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                        </a>
                    </td>
                </tr>';
        }
        $table.='</tbody>';

        $datatable = "<script>   
            var table = $('#example').DataTable({
                'columnDefs': [{
                    'targets': 0,
                    'checkboxes': {
                        'selectRow': true
                    }
                }],
                'select': {
                    'style': 'multi'
                },
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.DT_RowId);
                    $(nRow).attr('id', 'id_' + aData.DT_RowId);
                },
                'order': [
                    [0, 'asc']
                ]
            });
            </script>";
        echo $table;
        echo $datatable;
    }

    // ADMIN - Add Exit Item
    if (isset($_POST['itemExitIdSend']) && isset($_POST['receiverExitNameSend']) && isset($_POST['qtyExitSend'])) { 
        $checkcurrent = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemExitIdSend'");
        $getdata = mysqli_fetch_array($checkcurrent);

        $currentqty = $getdata['itemtotal'];
        $currentava = $getdata['itemava'];

        if ($currentava >= $qtyExitSend) {
            $totalcurrentqty = $currentqty - $qtyExitSend;
            $totalcurrentava = $currentava - $qtyExitSend;
            
            $addtoout = mysqli_query($conn, "INSERT INTO itemout (itemid, receivername, qty) VALUES ('$itemExitIdSend', '$receiverExitNameSend', '$qtyExitSend')");
            $updateqtyout = mysqli_query($conn, "UPDATE item SET itemtotal = '$totalcurrentqty', itemava = '$totalcurrentava' WHERE itemid = '$itemExitIdSend'");
        } else {
            echo "<script> alert('Not enough items available, please check the number of items available!');</script> ";
        }
    }

    // ADMIN - Edit Exit Item
    if (isset($_POST['itemidExitSend']) && isset($_POST['outidExitSend'])) {
        $getitemid = $_POST['itemidExitSend'];
        $getoutid = $_POST['outidExitSend'];
    
        $result = mysqli_query($conn, "SELECT * FROM itemout t, item i WHERE i.itemid = t.itemid && outid = '$getoutid'");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }
    
        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['itemidExitUpdateSend']) && isset($_POST['outidExitUpdateSend'])) {
        $itemid = $_POST['itemidExitUpdateSend'];
        $outid = $_POST['outidExitUpdateSend'];
        $receivername = $_POST['receiverExitNameEditSend'];
        $qty = $_POST['qtyExitEditSend'];
        
        $checkitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
        $theitems = mysqli_fetch_array($checkitem);
        $currentitemtotal = $theitems['itemtotal'];
        $currentava = $theitems['itemava'];
        
        $checkqty = mysqli_query($conn, "SELECT * FROM itemout WHERE outid = '$outid'");
        $theqty = mysqli_fetch_array($checkqty);
        $currentqty = $theqty['qty'];

        $totalava = $currentava + $currentqty;

        if ($totalava >= $qty) {
            if ($qty > $currentqty) {
                $diff = $qty - $currentqty;
                $reducetotal = $currentitemtotal - $diff;
                $reduceava = $currentava - $diff;
                $reduceitem = mysqli_query($conn, "UPDATE item SET itemtotal = '$reducetotal', itemava = '$reduceava' WHERE itemid = '$itemid'");
                $update = mysqli_query($conn, "UPDATE itemout SET qty = '$qty', receivername = '$receivername' WHERE outid = '$outid'");
            } else {
                $diff = $currentqty - $qty;
                $reducetotal = $currentitemtotal + $diff;
                $reduceava = $currentava + $diff;
                $reduceitem = mysqli_query($conn, "UPDATE item SET itemtotal = '$reducetotal', itemava = '$reduceava' WHERE itemid = '$itemid'");
                $update = mysqli_query($conn, "UPDATE itemout SET qty = '$qty', receivername = '$receivername' WHERE outid = '$outid'");
            }
        } else {
            echo "<script> alert('Not enough items available, please check the number of items available!');</script> ";
        }
    }

    // ADMIN - Delete Exit Item
    if (isset($_POST['itemidDelSend']) && isset($_POST['outidDelSend']) && isset($_POST['qtyDelSend'])) {
        $outid = $_POST['outidDelSend'];

        $result = mysqli_query($conn, "SELECT * FROM itemout t, item i WHERE i.itemid = t.itemid && outid = '$outid'");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }
    
    if (isset($_POST['hiddenitemidDelSend']) && isset($_POST['hiddenoutidDelSend']) && isset($_POST['hiddenqtyDelSend'])) {
        $itemid = $_POST['hiddenitemidDelSend'];
        $outid = $_POST['hiddenoutidDelSend'];
        $qty = $_POST['hiddenqtyDelSend'];
    
        $getdataitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
        $data = mysqli_fetch_array($getdataitem);
        $itemtotal = $data['itemtotal'];
        $itemava = $data['itemava'];

        $reducetotal = $itemtotal + $qty;
        $reduceava = $itemava + $qty;
        
        $update = mysqli_query($conn, "UPDATE item SET itemtotal = '$reducetotal', itemava = '$reduceava' WHERE itemid = '$itemid'");
        $deletedata = mysqli_query($conn, "DELETE FROM itemout WHERE outid = '$outid'");
    }

    // ADMIN - Delete Multiple Exit Item
    if (isset($_POST['selectedExitArray'])) {
        $arrayList = $_POST['selectedExitArray'];

        $i = 0;
        foreach ($arrayList as $each) {
            $array[$i] = $each;
            $i++;
        }
        foreach ($array as $outid) {
            $getdataitemout = mysqli_query($conn, "SELECT * FROM itemout WHERE outid = '$outid'");
            $data = mysqli_fetch_array($getdataitemout);
            $qty = $data['qty'];
            $itemid = $data['itemid'];

            $getdataitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
            $data = mysqli_fetch_array($getdataitem);
            $itemtotal = $data['itemtotal'];
            $itemava = $data['itemava'];

            $reducetotal = $itemtotal + $qty;
            $reduceava = $itemava + $qty;

            $update = mysqli_query($conn, "UPDATE item SET itemtotal = '$reducetotal', itemava = '$reduceava' WHERE itemid = '$itemid'");
            $deletedata = mysqli_query($conn, "DELETE FROM itemout WHERE outid = '$outid'");
        };
    }

    // ADMIN - Display User Request Data
    if (isset($_POST['admindisplayuserreqSend'])) {
        $table = '                    
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';

        $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser");
        $i = 1;
        while ($data = mysqli_fetch_array($result)) {
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
            
            $systemdate = date('Y-m-d', strtotime($borrowdate. ' + 1 days'));
            date_default_timezone_set('Asia/Jakarta');
            $currentsystemdate = date('Y-m-d H:i:s');

            if (empty($address)) {
                $address = "None";
            }

            if ($currentsystemdate < $systemdate && $status == 1) {
                $table.='
                    <tr id="'.$idtr.'">
                        <td>'.$i++.'</td>
                        <td>'.$username.'</td>
                        <td>'.$itemname.'</td>
                        <td>'.$borrowqty.'</td>
                        <td>'.$req.'</td>
                        <td>'.$returned.'</td>
                        <td>'.$address.'</td>
                        <td>'.$borrowdate.'</td>
                        <td>'.$systemdate.'</td>
                        <td>Waiting for Confirmation</td>
                        <td>
                            <a class="text-success" onclick="confirmreq('.$idtr.')">
                                <i class="material-icons" data-toggle="tooltip" title="Confirm">check_circle</i>
                            </a>
                            <a class="text-danger" onclick="rejectreq('.$idtr.')">
                                <i class="material-icons" data-toggle="tooltip" title="Reject">cancel</i>
                            </a>
                        </td>
                    </tr>'; 
            } else if ($currentsystemdate > $systemdate && $status == 1) { 
                $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '8' WHERE idtr = '$idtr'");
                $updateitem = mysqli_query($conn, "UPDATE usertransaction SET status = '8' WHERE idtr = '$idtr'");
            } else if ($status == 4) {
                $table.='
                    <tr id="'.$idtr.'">
                        <td>'.$i++.'</td>
                        <td>'.$username.'</td>
                        <td>'.$itemname.'</td>
                        <td>'.$borrowqty.'</td>
                        <td>'.$req.'</td>
                        <td>'.$returned.'</td>
                        <td>'.$address.'</td>
                        <td>'.$borrowdate.'</td>
                        <td>'.$borrowdeadline.'</td>
                        <td>Return Partially Request</td>
                        <td>
                            <a class="text-success" onclick="confirmreturnp('.$idtr.')">
                                <i class="material-icons" data-toggle="tooltip" title="Confirm">check_circle</i>
                            </a>
                            <a class="text-danger" onclick="rejectreturn('.$idtr.')">
                                <i class="material-icons" data-toggle="tooltip" title="Reject">cancel</i>
                            </a>
                        </td>
                    </tr>';
            } else if ($status == 6) {
                $table.='
                    <tr id="'.$idtr.'">
                        <td>'.$i++.'</td>
                        <td>'.$username.'</td>
                        <td>'.$itemname.'</td>
                        <td>'.$borrowqty.'</td>
                        <td>'.$req.'</td>
                        <td>'.$returned.'</td>
                        <td>'.$address.'</td>
                        <td>'.$borrowdate.'</td>
                        <td>'.$borrowdeadline.'</td>
                        <td>Return All Request</td>
                        <td>
                            <a class="text-success" onclick="confirmreturnall('.$idtr.')">
                                <i class="material-icons" data-toggle="tooltip" title="Confirm">check_circle</i>
                            </a>
                            <a class="text-danger" onclick="rejectreturn('.$idtr.')">
                                <i class="material-icons" data-toggle="tooltip" title="Reject">cancel</i>
                            </a>
                        </td>
                    </tr>';
            } else if ($status == 12) {
                $table.='
                    <tr id="'.$idtr.'">
                        <td>'.$i++.'</td>
                        <td>'.$username.'</td>
                        <td>'.$itemname.'</td>
                        <td>'.$borrowqty.'</td>
                        <td>'.$req.'</td>
                        <td>'.$returned.'</td>
                        <td>'.$address.'</td>
                        <td>'.$borrowdate.'</td>
                        <td>'.$systemdate.'</td>
                        <td>Waiting for Delivery</td>
                        <td>
                            <a class="text-success" onclick="delivery('.$idtr.')">
                                <i class="material-icons" data-toggle="tooltip" title="Confirm">check_circle</i>
                            </a>
                            <a class="text-danger" onclick="rejectreq('.$idtr.')">
                                <i class="material-icons" data-toggle="tooltip" title="Reject">cancel</i>
                            </a>
                        </td>
                    </tr>';
            }
        }

        $table.='</tbody>';
        $datatable = "<script>            
                var table = $('#example').DataTable({
                    'columnDefs': [{
                        'targets': 0
                    }],
                    'fnCreatedRow': function(nRow, aData, iDataIndex) {
                        $(nRow).attr('data-id', aData.DT_RowId);
                        $(nRow).attr('id', 'id_' + aData.DT_RowId);
                    },
                    'order': [
                        [0, 'asc']
                    ]
                });
            </script>";
        echo $table;
        echo $datatable;
    }

    // ADMIN - Confirm User Request
    if (isset($_POST['confirmreqidtrSend'])) {
        $idtr = $_POST['confirmreqidtrSend'];

        $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser && idtr = $idtr");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    }

    if (isset($_POST['hiddenconfirmreqidtrSend']) && isset($_POST['hiddenconfirmreqitemidSend'])) {
        $idtr = $_POST['hiddenconfirmreqidtrSend'];
        $itemid = $_POST['hiddenconfirmreqitemidSend'];

        $getalltr = mysqli_query($conn, "SELECT * FROM transaction WHERE idtr = '$idtr'");
        $datatr = mysqli_fetch_array($getalltr);
        $borrowqty = $datatr['borrowqty'];
        
        $getallitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
        $dataitem = mysqli_fetch_array($getallitem);
        $itemava = $dataitem['itemava'];

        $currentitemava = $itemava - $borrowqty;

        $updateitem = mysqli_query($conn, "UPDATE item SET itemava = '$currentitemava' WHERE itemid = '$itemid'");
        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '2' WHERE idtr = '$idtr'");
        $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '2' WHERE idtr = '$idtr'");
    }

    // ADMIN - Delivery Request
        if (isset($_POST['deliveryidtrSend'])) {
            $idtr = $_POST['deliveryidtrSend'];
    
            $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser && idtr = $idtr");
            $courier = mysqli_query($conn, "SELECT * FROM user WHERE role = '3'");

            $response = array();
            $courierList = array();

            while ($row = mysqli_fetch_assoc($result)) {
                $response = $row;
            }
            while ($crow = mysqli_fetch_assoc($courier)) {
                $courierList = $crow;
                array_push($response, $courierList);
            }
    
            echo json_encode($response);
        }

        if (isset($_POST['hiddendeliveryidtrSend']) && isset($_POST['hiddendeliveryitemidSend']) && isset($_POST['selectedcourierSend'])) {
            $idtr = $_POST['hiddendeliveryidtrSend'];
            $itemid = $_POST['hiddendeliveryitemidSend'];
            $courier = $_POST['selectedcourierSend'];
    
            $getalltr = mysqli_query($conn, "SELECT * FROM transaction WHERE idtr = '$idtr'");
            $datatr = mysqli_fetch_array($getalltr);
            $borrowqty = $datatr['borrowqty'];
            
            $getallitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
            $dataitem = mysqli_fetch_array($getallitem);
            $itemava = $dataitem['itemava'];
    
            $currentitemava = $itemava - $borrowqty;
    
            $updateitem = mysqli_query($conn, "UPDATE item SET itemava = '$currentitemava' WHERE itemid = '$itemid'");
            $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '13', courierid = $courier WHERE idtr = '$idtr'");
            $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '13', courierid = $courier WHERE idtr = '$idtr'");
        }

    // ADMIN - Reject User Request
    if (isset($_POST['rejectreqidtrSend'])) {
        $idtr = $_POST['rejectreqidtrSend'];

        $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser && idtr = $idtr");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    }

    if (isset($_POST['hiddenrejectreqidtrSend'])) {
        $idtr = $_POST['hiddenrejectreqidtrSend'];

        $getalltr = mysqli_query($conn, "SELECT * FROM transaction WHERE idtr = '$idtr'");
        $datatr = mysqli_fetch_array($getalltr);
        $iduser = $datatr['iduser'];
        $borrowqty = $datatr['borrowqty'];

        $getuserdata = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
        $datauser = mysqli_fetch_array($getuserdata);
        $loan = $datauser['loan'];

        $currloan = $loan - $borrowqty;
        $updateloan = mysqli_query($conn, "UPDATE user SET loan = '$currloan' WHERE iduser = '$iduser'");
        
        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '3' WHERE idtr = '$idtr'");
        $updateitem = mysqli_query($conn, "UPDATE usertransaction SET status = '3' WHERE idtr = '$idtr'");
    }

    // ADMIN - Confirm Return Partially Request
    if (isset($_POST['confirmreturnpidtrSend'])) {
        $idtr = $_POST['confirmreturnpidtrSend'];

        $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser && idtr = $idtr");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    }

    if (isset($_POST['hiddenconfirmreturnpidtrSend']) && isset($_POST['hiddenconfirmreturnpitemidSend'])) {
        $idtr = $_POST['hiddenconfirmreturnpidtrSend'];
        $itemid = $_POST['hiddenconfirmreturnpitemidSend'];

        $getalltr = mysqli_query($conn, "SELECT * FROM transaction WHERE idtr = '$idtr'");
        $datatr = mysqli_fetch_array($getalltr);
        $req = $datatr['req'];
        $returned = $datatr['returned'];
        $iduser = $datatr['iduser'];
        
        $getallitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
        $dataitem = mysqli_fetch_array($getallitem);
        $itemava = $dataitem['itemava'];

        $getuserdata = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
        $datauser = mysqli_fetch_array($getuserdata);
        $loan = $datauser['loan'];

        $currentreturned = $req + $returned;
        $currentitemava = $itemava + $req;

        $updateitem = mysqli_query($conn, "UPDATE item SET itemava = '$currentitemava' WHERE itemid = '$itemid'");
        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '5', req = '0', returned = '$currentreturned' WHERE idtr = '$idtr'");
        $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '5', req = '0', returned = '$currentreturned' WHERE idtr = '$idtr'");
        
        $currloan = $loan - $req;
        $updateloan = mysqli_query($conn, "UPDATE user SET loan = '$currloan' WHERE iduser = '$iduser'");
    }

    // ADMIN - Confirm Return All Request
    if (isset($_POST['confirmreturnallidtrSend'])) {
        $idtr = $_POST['confirmreturnallidtrSend'];

        $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser && idtr = $idtr");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    }

    if (isset($_POST['hiddenreturnallidtrSend']) && isset($_POST['hiddenreturnallitemidSend'])) {
        $idtr = $_POST['hiddenreturnallidtrSend'];
        $itemid = $_POST['hiddenreturnallitemidSend'];

        $getalltr = mysqli_query($conn, "SELECT * FROM transaction WHERE idtr = '$idtr'");
        $datatr = mysqli_fetch_array($getalltr);
        $req = $datatr['req'];
        $returned = $datatr['returned'];
        $iduser = $datatr['iduser'];
        
        
        $getallitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
        $dataitem = mysqli_fetch_array($getallitem);
        $itemava = $dataitem['itemava'];

        $getuserdata = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
        $datauser = mysqli_fetch_array($getuserdata);
        $loan = $datauser['loan'];

        $currentreturned = $req + $returned;
        $currentitemava = $itemava + $req;

        $updateitem = mysqli_query($conn, "UPDATE item SET itemava = '$currentitemava' WHERE itemid = '$itemid'");
        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '7', req = '0', returned = '$currentreturned' WHERE idtr = '$idtr'");
        $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '7', req = '0', returned = '$currentreturned' WHERE idtr = '$idtr'");

        $currloan = $loan - $req;
        $updateloan = mysqli_query($conn, "UPDATE user SET loan = '$currloan' WHERE iduser = '$iduser'");
    }

    // ADMIN - Reject Return Request
    if (isset($_POST['rejectreturnidtrSend'])) {
        $idtr = $_POST['rejectreturnidtrSend'];

        $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser && idtr = $idtr");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    }

    if (isset($_POST['hiddenrejectreturnidtrSend'])) {
        $idtr = $_POST['hiddenrejectreturnidtrSend'];

        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '10', req = '0' WHERE idtr = '$idtr'");
        $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '10', req = '0' WHERE idtr = '$idtr'");
    }

    // ADMIN - Display Pay Debt Request Data
    if (isset($_POST['admindebtreqdisplaydataSend'])) {
        $table = '
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Username</th>
                    <th>Debt</th>
                    <th>Payment Quantity</th>
                    <th>Payment Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';

        $result = mysqli_query($conn, "SELECT * FROM debt WHERE debtstatus = '2'");
        $i = 1;
        while ($data = mysqli_fetch_array($result)) {
            $iduser = $data['iduser'];
            $debt = $data['debt'];
            $payqty = $data['payqty'];
            $paydate = $data['paydate'];

            $getalluser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
            $datauser = mysqli_fetch_array($getalluser);
            $username = $datauser['username'];   

            $table.='
                <tr>
                    <td>'.$i++.'</td>
                    <td>'.$username.'</td>
                    <td>Rp '.number_format($debt,2,',','.').'</td>
                    <td>Rp '.number_format($payqty,2,',','.').'</td>
                    <td>'.$paydate.'</td>
                    <td>
                        <a class="text-success" onclick="confirmdebtreq('.$iduser.')">
                            <i class="material-icons" data-toggle="tooltip" title="Confirm">check_circle</i>
                        </a>
                    </td>
                </tr>';
        }

        $table.='</tbody>';
        $datatable = "<script>
                var table = $('#example').DataTable({
                    'columnDefs': [{
                        'targets': 0
                    }],
                    'fnCreatedRow': function(nRow, aData, iDataIndex) {
                        $(nRow).attr('data-id', aData.DT_RowId);
                        $(nRow).attr('id', 'id_' + aData.DT_RowId);
                    },
                    'order': [
                        [0, 'asc']
                    ]
                });
            </script>";
        echo $table;
        echo $datatable; 
    }
    
    // ADMIN - Confirm Pay Debt Request
    if (isset($_POST['confirmdebtreqiduserSend'])) {
        $iduser = $_POST['confirmdebtreqiduserSend'];

        $result = mysqli_query($conn, "SELECT * FROM debt WHERE debtstatus = '2' && iduser = $iduser");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
            
            $getalluser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
            $datauser = mysqli_fetch_array($getalluser);
            $username = $datauser['username'];   
        }

        array_push($response, $username);
        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['hiddendebtreqiduserSend']) && isset($_POST['hiddendebtreqdebtSend']) && isset($_POST['hiddendebtreqpayqtySend'])) {
        $iduser = $_POST['hiddendebtreqiduserSend'];
        $debt = $_POST['hiddendebtreqdebtSend'];
        $payqty = $_POST['hiddendebtreqpayqtySend'];

        $count = $debt - $payqty;

        if ($count > 0) {
            $updatepay = mysqli_query($conn, "UPDATE debt SET debtstatus = '1', payqty = '0', debt = '$count' WHERE iduser = '$iduser'");
        } else if ($count == 0) {
            $updatepayall = mysqli_query($conn, "UPDATE debt SET debtstatus = '1', payqty = '0', debt = '$count' WHERE iduser = '$iduser'");
            $updatepayall = mysqli_query($conn, "UPDATE user SET role = '0' WHERE iduser = '$iduser'");
        }
    }

    // ADMIN - Edit User Data
    if (isset($_POST['userupdate'])) {
        $iduser = $_POST['iduser'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $debt = $_POST['debt'];
        $debtorigin = $_POST['debtorigin'];

        if ($debt == null) {
            $debt = $debtorigin;
        } else {
            $debt = $debt;
        }

        $getdebt = mysqli_query($conn, "SELECT * FROM debt WHERE iduser = '$iduser'");
        $datadebt = mysqli_fetch_array($getdebt);
        
        $allowed_extension = array('png','jpg');
        $name = $_FILES['file']['name'];
        $dot = explode('.',$name);
        $extension = strtolower(end($dot));
        $size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $image = md5(uniqid($name,true) . time()).'.'.$extension;

        if (in_array($extension, $allowed_extension) === true) {
            if ($size < 15000000) {
                move_uploaded_file($file_tmp, '../img/'.$image);
                        
                $addtodatabase = mysqli_query($conn, "UPDATE user SET username = '$username', email = '$email', password = '$password', image = '$image', role = '$role' WHERE iduser = '$iduser'"); 
                
                if ($datadebt) {
                    $addtodatadebt = mysqli_query($conn, "UPDATE debt SET debt = '$debt' WHERE iduser = '$iduser'");
                } else {
                    $addtodatadebt = mysqli_query($conn, "INSERT INTO debt (iduser, debt) VALUES ('$iduser', '$debt')");
                }

                if ($addtotable && $addtodatadebt) {
                    header('location: usercontrol.php');
                } else {
                    echo 'Failed!';
                    header('location: usercontrol.php');
                }
            } else {
                echo "<script> alert('File size too big!');</script>";
            }
        } else {
            $addtodatabase = mysqli_query($conn, "UPDATE user SET username = '$username', email = '$email', password = '$password', role = '$role' WHERE iduser = '$iduser'"); 

            if ($datadebt) {
                $addtodatadebt = mysqli_query($conn, "UPDATE debt SET debt = '$debt' WHERE iduser = '$iduser'");
            } else {
                $addtodatadebt = mysqli_query($conn, "INSERT INTO debt (iduser, debt) VALUES ('$iduser', '$debt')");
            }
            
            if ($addtodatabase && $addtodatadebt) {
                header('location: usercontrol.php');
            } else {
                echo 'Failed!';
                header('location: usercontrol.php');
            }
        }
    }

    if (isset($_POST['deleteuser'])) {
        $iduser = $_POST['iduser'];
        $delete = mysqli_query($conn, "DELETE FROM user WHERE iduser = '$iduser'");
        $deletedebt = mysqli_query($conn, "DELETE FROM debt WHERE iduser = '$iduser'");

        if ($delete && $deletedebt) {
            header('location: usercontrol.php');
        } else {
            echo 'Failed!';
            header('location: usercontrol.php');
        }
    }

    // USER - Display Item
    if (isset($_POST['displayUserSend'])) {
        $table = '
        <thead>
            <tr>
                <th>No.</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Available Item</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $result = mysqli_query($conn, "SELECT * FROM item");
        $i = 1;
        while ($data = mysqli_fetch_array($result)) {
            $itemid = $data['itemid'];
            $itemname = $data['itemname'];
            $itemdesc = $data['itemdesc'];
            $itemava = $data['itemava'];
            $itemtotal = $data['itemtotal'];

            $iduser = $_SESSION['iduser'];
            $getuser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
            $datauser = mysqli_fetch_array($getuser);
            $loan = $datauser['loan'];

            $borrowbtn = '
                <a class="text-success" onclick="borrowItem('.$itemid.','.$itemava.','.$iduser.')">
                    <i class="material-icons" data-toggle="tooltip" title="Borrow">shopping_basket</i>
                </a>';
            $borrowlimitbtn = '
                <a class="text-success" onclick="borrowItemLimit('.$itemid.','.$itemava.','.$iduser.')">
                    <i class="material-icons" data-toggle="tooltip" title="Borrow">shopping_basket</i>
                </a>';

            $table.='
                <tr id="'.$itemid.'">
                    <td>'.$i++.'</td>
                    <td>'.$itemname.'</td>
                    <td>'.$itemdesc.'</td>
                    <td>'.$itemava.'</td>
                    <td>'.($loan < 5 ? $borrowbtn : $borrowlimitbtn).'</td>
                </tr>';
        
        }
        $table.='</tbody>';
        $datatable = "<script>   
            var table = $('#example').DataTable({
                'columnDefs': [{
                    'targets': 0
                }],
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.DT_RowId);
                    $(nRow).attr('id', 'id_' + aData.DT_RowId);
                },
                'order': [
                    [0, 'asc']
                ]
            });
            </script>"; 
        echo $table;
        echo $datatable;
    }

    // USER - Borrow Item
    if (isset($_POST['borrowitemidsend'])) {
        $borrowitemid = $_POST['borrowitemidsend'];
        $iduser = $_POST['borrowidusersend'];
        $itemava = $_POST['borrowitemavasend'];

        $result = mysqli_query($conn , "SELECT * FROM item WHERE itemid = $borrowitemid");

        $getuser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
        $datauser = mysqli_fetch_array($getuser);
        $loan = $datauser['loan'];

        if ($itemava == 0) {
            $bqty = $itemava;
        } else if ($itemava <= 5) {
            if ($loan >= $itemava && $loan <= 2) {
                $bqty = $itemava;
            } else if ($loan >= $itemava) {
                $bqty = 5 - $loan;
            } else if ($itemava > $loan && $itemava <= 3) {
                $bqty = $itemava;
            } else if ($itemava > $loan && $loan == 0) {
                $bqty = $itemava;
            } else if ($itemava > $loan ) {
                $bqty = 5 - $loan;
            }
        } else if ($itemava > 5) {
            $bqty = 5 - $loan;
        }


        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }
        array_push($response, $loan);
        array_push($response, $bqty);

        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['borrowqtySend']) && isset($_POST['addressSend']) && isset($_POST['borrowdateSend']) && isset($_POST['borrowdeadlineSend']) && isset($_POST['hiddenitemidSend']) && isset($_POST['hiddeniduserSend'])) {
        $itemid = $_POST['hiddenitemidSend'];
        $borrowqty = $_POST['borrowqtySend'];
        $address = $_POST['addressSend'];
        $borrowdate = date('Y-m-d H:i:s', strtotime($_POST['borrowdateSend']));
        $borrowdeadline = date('Y-m-d H:i:s', strtotime($_POST['borrowdeadlineSend']));
        $iduser = $_POST['hiddeniduserSend'];
        $warehouselat = $_POST['warehouselatSend'];
        $warehouselon = $_POST['warehouselonSend'];
        
        if (empty($address)) {
            $inserttodb = mysqli_query($conn, "INSERT INTO transaction (iduser, itemid, borrowqty, borrowdate, borrowdeadline, status, debtdate) VALUES ('$iduser', '$itemid', '$borrowqty', '$borrowdate', '$borrowdeadline', '1', '$borrowdeadline')");

            $getalltr = mysqli_query($conn, "SELECT * FROM transaction WHERE iduser = '$iduser' AND itemid = '$itemid' AND borrowdate = '$borrowdate'");
            $datatr = mysqli_fetch_array($getalltr);
            $idtr = $datatr['idtr'];
    
            $getuserdata = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
            $datauser = mysqli_fetch_array($getuserdata);
            $loan = $datauser['loan'];
            
            $inserttoudb = mysqli_query($conn, "INSERT INTO usertransaction (idtr, iduser, itemid, borrowqty, borrowdate, borrowdeadline, status, debtdate) VALUES ('$idtr', '$iduser', '$itemid', '$borrowqty', '$borrowdate', '$borrowdeadline', '1', '$borrowdeadline')");
            
            $currloan = $loan + $borrowqty;
            $updateloan = mysqli_query($conn, "UPDATE user SET loan = '$currloan' WHERE iduser = '$iduser'");
        } else {
            $inserttodb = mysqli_query($conn, "INSERT INTO transaction (iduser, itemid, borrowqty, borrowdate, borrowdeadline, status, debtdate, warehouselat, warehouselon, address) VALUES ('$iduser', '$itemid', '$borrowqty', '$borrowdate', '$borrowdeadline', '12', '$borrowdeadline', '$warehouselat', '$warehouselon', '$address')");

            $getalltr = mysqli_query($conn, "SELECT * FROM transaction WHERE iduser = '$iduser' AND itemid = '$itemid' AND borrowdate = '$borrowdate'");
            $datatr = mysqli_fetch_array($getalltr);
            $idtr = $datatr['idtr'];
    
            $getuserdata = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
            $datauser = mysqli_fetch_array($getuserdata);
            $loan = $datauser['loan'];
            
            $inserttoudb = mysqli_query($conn, "INSERT INTO usertransaction (idtr, iduser, itemid, borrowqty, borrowdate, borrowdeadline, status, debtdate, warehouselat, warehouselon, address) VALUES ('$idtr', '$iduser', '$itemid', '$borrowqty', '$borrowdate', '$borrowdeadline', '12', '$borrowdeadline', '$warehouselat', '$warehouselon', '$address')");
            
            $currloan = $loan + $borrowqty;
            $updateloan = mysqli_query($conn, "UPDATE user SET loan = '$currloan' WHERE iduser = '$iduser'");
        }
    }

    // USER - Borrow Item Limit
    if (isset($_POST['borrowitemidLimitsend'])) {
        $borrowitemid = $_POST['borrowitemidLimitsend'];
        $iduser = $_POST['borrowiduserLimitsend'];
        $itemava = $_POST['borrowitemavaLimitsend'];

        $result = mysqli_query($conn , "SELECT * FROM item WHERE itemid = $borrowitemid");

        $getuser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
        $datauser = mysqli_fetch_array($getuser);
        $loan = $datauser['loan'];

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }
        array_push($response, $loan);
        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    // USER = Display History Transaction
    if (isset($_POST['displayUserHistorySend'])) {
        $table = '                    
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Item Name</th>
                    <th>Amount</th>
                    <th>Returned</th>
                    <th>Borrow Date</th>
                    <th>Return Deadline</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
            
        $iduser = $_SESSION['iduser'];
        $result = mysqli_query($conn, "SELECT * FROM usertransaction u, item i WHERE i.itemid = u.itemid AND u.iduser = '$iduser';");
        $i = 1;
        while ($data = mysqli_fetch_array($result)) {
            $idtr = $data['idtr'];
            $itemid = $data['itemid'];
            $itemname = $data['itemname'];
            $borrowqty = $data['borrowqty'];
            $req = $data['req'];
            $returned = $data['returned'];
            $borrowdate = $data['borrowdate'];
            $borrowdeadline = $data['borrowdeadline'];
            $status = $data['status'];
            $itemprice = $data['itemprice'];
            $debtdate = date('Y-m-d H:i:s', strtotime($data['debtdate']));
            
            $systemdate = date('Y-m-d H:i:s', strtotime($borrowdate. ' + 1 days'));
            date_default_timezone_set('Asia/Jakarta');
            $currentsystemdate = date('Y-m-d H:i:s');
            // $currentsystemdate = date('Y-m-d H:i:s', strtotime($borrowdeadline. ' + 4 days'));

            if (($debtdate < $currentsystemdate) && ($status == 2 || $status == 4 || $status == 5 || $status == 6 || $status == 9 || $status == 10)) {
                $currentsystemdateformat = date_create_from_format('Y-m-d G:i:s', $currentsystemdate);
                $currentsystemdateformat->getTimestamp();

                $debtdateformat = date_create_from_format('Y-m-d G:i:s', $debtdate);
                $debtdateformat->getTimestamp();
                
                $interval = date_diff($currentsystemdateformat, $debtdateformat);
                $debt = ($interval->days) * ((5/100 * $itemprice)*($borrowqty-$returned)) + $_SESSION['debt'];

                $_SESSION['debt'] = $debt;
                $_SESSION['role'] = 2;
                $debtdate = date('Y-m-d H:i:s', strtotime($debtdate. ' + 1 days'));

                $updatetr = mysqli_query($conn, "UPDATE transaction SET debtdate = '$debtdate' WHERE idtr = '$idtr'");
                $updateutr = mysqli_query($conn, "UPDATE usertransaction SET debtdate = '$debtdate' WHERE idtr = '$idtr'");
                $updateuser = mysqli_query($conn, "UPDATE user SET role = '2' WHERE iduser = '$iduser'");

                if (mysqli_query($conn, "SELECT * FROM debt WHERE iduser = '$iduser'")) {
                    $updatedebt = mysqli_query($conn, "UPDATE debt SET debt = '$debt' WHERE iduser = '$iduser'");

                } else {
                    $insertdebt = mysqli_query($conn, "INSERT INTO debt (iduser, debt) VALUES ('$iduser', '$debt')");
                }
            }

            $statusShow = [
                'Canceled', 
                ['Waiting for Confirmation', 'Rejected by System'], 
                'Borrow Confirmed',
                'Rejected by Admin',
                'Return Partially Request',
                'Return Partially Confirmed',
                'Return All Request',
                'Returned',
                'Rejected by System',
                'Return Canceled',
                'Return Rejected by Admin',
                'Item Missing',
                'Waiting for Delivery',
                'Courier On The Way to Location',
                'System Eror'
            ];

            $statusBtn = [
                ["<a class='text-danger' onclick='canceltr(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Cancel Transaction'>cancel</i>
                </a>",
                "<a class='text-danger' onclick='deletehistorytr(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Delete History'>delete</i>
                </a>
                <a class='text-danger' onclick='reportmiss(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Report Missing Item'>report</i>
                </a>"],

                "<a class='text-primary' onclick='returnreq(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Return Item'>send</i>
                </a>
                <a class='text-danger' onclick='reportmiss(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Report Missing Item'>report</i>
                </a>",

                "<a class='text-danger' onclick='deletehistorytr(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Delete History'>delete</i>
                </a>",

                ["<a class='text-danger' onclick='cancelreturn(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Cancel Return Request'>cancel</i>
                </a>",
                "<a class='text-primary' onclick='returnreq(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Return Item'>send</i>
                </a>
                <a class='text-danger' onclick='reportmiss(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Report Missing Item'>report</i>
                </a>"],

                "<a class='text-danger' onclick='deletehistorytr(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Delete History'>delete</i>
                </a>",

                "<a class='text-danger' onclick='trackcourier(".$idtr.")'>
                    <i class='material-icons' data-toggle='tooltip' title='Track Courier'>map</i>
                </a>"
            ];

            if (($currentsystemdate > $systemdate) && $status == 1){ 
                $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '8' WHERE idtr = '$idtr'");
                $updateitem = mysqli_query($conn, "UPDATE usertransaction SET status = '8' WHERE idtr = '$idtr'");
            }

            $table.='
                <tr>
                    <td>'.$i++.'</td>
                    <td>'.$itemname.'</td>
                    <td>'.$borrowqty.'</td>
                    <td>'.$returned.'</td>
                    <td>'.$borrowdate.'</td>
                    <td>'.$borrowdeadline.'</td>
                    <td>'.
                        ($status == 0 
                            ? $statusShow[0] 
                            : ($status == 1 && ($currentsystemdate < $systemdate)
                                ? $statusShow[1][0]
                                : ($status == 1 && ($currentsystemdate > $systemdate)
                                    ? $statusShow[1][1]
                                    : ($status == 2
                                        ? $statusShow[2]
                                        : ($status == 3
                                            ? $statusShow[3]
                                            : ($status == 4
                                                ? $statusShow[4]
                                                : ($status == 5
                                                    ? $statusShow[5]
                                                    : ($status == 6
                                                        ? $statusShow[6]
                                                        : ($status == 7
                                                            ? $statusShow[7]
                                                            : ($status == 8
                                                                ? $statusShow[8]
                                                                : ($status == 9
                                                                    ? $statusShow[9]
                                                                    : ($status == 10
                                                                        ? $statusShow[10]
                                                                        : ($status == 11
                                                                            ? $statusShow[11]
                                                                            : ($status == 12
                                                                                ? $statusShow[12]
                                                                                : ($status == 13
                                                                                    ? $statusShow[13]
                                                                                    : $statusShow[14]
                                                                                )
                                                                            )
                                                                        )
                                                                    )        
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    .'
                    </td>
                    <td>'.
                        (($status == 1 && ($currentsystemdate < $systemdate)) || $status == 12
                            ? $statusBtn[0][0] 
                            : ($status == 1 && ($currentsystemdate > $systemdate)
                                ? $statusBtn[0][1]
                                : ($status == 2 || $status == 5 || $status == 9 || $status == 10
                                    ? $statusBtn[1]
                                    : ($status == 3 || $status == 0 || $status == 7 || $status == 8 || $status == 11
                                        ? $statusBtn[2]
                                        : (($status == 4 || $status == 6) && $currentsystemdate < $systemdate
                                            ? $statusBtn[3][0]
                                            : (($status == 4 || $status == 6) && $currentsystemdate > $systemdate
                                                ? $statusBtn[3][1]
                                                : ($status == 13 
                                                    ? $statusBtn[5]
                                                    : $statusBtn[4]
                                                )
                                            )
                                        )
                                    )
                                ) 
                            )
                        )
                    .'
                    </td>
                </tr>';
        }
        
        $table.='</tbody>';
        $datatable = "<script>
                var table = $('#example').DataTable({
                    'columnDefs': [{
                        'targets': 0
                    }],
                    'fnCreatedRow': function(nRow, aData, iDataIndex) {
                        $(nRow).attr('data-id', aData.DT_RowId);
                        $(nRow).attr('id', 'id_' + aData.DT_RowId);
                    },
                    'order': [
                        [0, 'asc']
                    ]
                });
            </script>";
        echo $table;
        echo $datatable; 
    }

    // USER - Cancel Transaction
    if (isset($_POST['cancelidtrSend'])) {
        $idtr = $_POST['cancelidtrSend'];

        $iduser = $_SESSION['iduser'];
        $result = mysqli_query($conn, "SELECT * FROM usertransaction u, item i WHERE i.itemid = u.itemid AND u.iduser = '$iduser' && idtr = $idtr");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }
        
    if (isset($_POST['hiddenidtrSend'])) {
        $idtr = $_POST['hiddenidtrSend'];

        $getalltr = mysqli_query($conn, "SELECT * FROM transaction WHERE idtr = '$idtr'");
        $datatr = mysqli_fetch_array($getalltr);
        $iduser = $datatr['iduser'];
        $borrowqty = $datatr['borrowqty'];

        $getuserdata = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
        $datauser = mysqli_fetch_array($getuserdata);
        $loan = $datauser['loan'];

        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '0' WHERE idtr = '$idtr'");
        $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '0' WHERE idtr = '$idtr'");

        $currloan = $loan - $borrowqty;
        $updateloan = mysqli_query($conn, "UPDATE user SET loan = '$currloan' WHERE iduser = '$iduser'");
    }

    // USER - Delete History Transaction
    if (isset($_POST['deletehidtrSend'])) {
        $idtr = $_POST['deletehidtrSend'];

        $iduser = $_SESSION['iduser'];
        $result = mysqli_query($conn, "SELECT * FROM usertransaction u, item i WHERE i.itemid = u.itemid AND u.iduser = '$iduser' && idtr = $idtr;");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;

            $status = $row['status'];
            if ($status == 0) {
                $stat = "Canceled";
            } else if ($status == 1) {
                $stat = "Waiting for Confirmation";
            } else if ($status == 2) {
                $stat = "Borrow Confirmed";
            } else if ($status == 3) {
                $stat = "Rejected by Admin";
            } else if ($status == 4) {
                $stat = "Return Partially Request";
            } else if ($status == 5) {
                $stat = "Return Partially Confirmed";
            } else if ($status == 6) {
                $stat = "Return All Request";
            } else if ($status == 7) {
                $stat = "Returned";
            } else if ($status == 8) {
                $stat = "Rejected by System";
            } else if ($status == 9) {
                $stat = "Return Canceled";
            } else if ($status == 10) {
                $stat = "Return Rejected by Admin";
            } else if ($status == 11) {
                $stat = "Item Missing";
            } else if ($status == 12) {
                $stat = "Waiting for Delivery";
            } else {
                $stat = "System Error";
            }
        }

        array_push($response, $stat);
        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['hiddendelhidtrSend'])) {
        $idtr = $_POST['hiddendelhidtrSend'];

        $deleteh = mysqli_query($conn, "DELETE FROM  usertransaction WHERE idtr = '$idtr'");
    }

    // USER - Report Missing Item
    if (isset($_POST['reportidtrSend'])) {
        $idtr = $_POST['reportidtrSend'];

        $iduser = $_SESSION['iduser'];
        $result = mysqli_query($conn, "SELECT * FROM usertransaction u, item i WHERE i.itemid = u.itemid AND u.iduser = '$iduser' && idtr = $idtr;");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;

            $borrowqty = $row['borrowqty'];
            $returned = $row['returned'];
            $amount = $borrowqty-$returned;
        }

        array_push($response, $amount);
        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['hiddenreportidtrSend'])) {
        $idtr = $_POST['hiddenreportidtrSend'];

        $gettr = mysqli_query($conn, "SELECT * FROM transaction WHERE idtr = '$idtr'");
        $datatr = mysqli_fetch_array($gettr);
        $iduser = $datatr['iduser'];
        $itemid = $datatr['itemid'];
        $borrowqty = $datatr['borrowqty'];
        $returned = $datatr['returned'];

        $getitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = '$itemid'");
        $dataitem = mysqli_fetch_array($getitem);
        $itemprice = $dataitem['itemprice'];

        $getdebt = mysqli_query($conn, "SELECT * FROM debt WHERE iduser = '$iduser'");
        $datadebt = mysqli_fetch_array($getdebt);
        $debt = $datadebt['debt'];

        $getuserdata = mysqli_query($conn, "SELECT * FROM user WHERE iduser = '$iduser'");
        $datauser = mysqli_fetch_array($getuserdata);
        $loan = $datauser['loan'];

        $miss = $borrowqty - $returned;
        $totaldebt = ($itemprice * ($borrowqty - $returned)) + $debt;

        $updateitem = mysqli_query($conn, "UPDATE item SET itemtotal = itemtotal - $miss WHERE itemid = '$itemid'");

        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '11' WHERE idtr = '$idtr'");
        $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '11' WHERE idtr = '$idtr'");

        $currloan = $loan - ($borrowqty - $returned);
        $updateloan = mysqli_query($conn, "UPDATE user SET role = '2', loan = '$currloan' WHERE iduser = '$iduser'");
    
        if ($datadebt) {
            $updatedebt = mysqli_query($conn, "UPDATE debt SET debt = '$totaldebt' WHERE iduser = '$iduser'");
        } else {
            $insertdebt = mysqli_query($conn, "INSERT INTO debt (iduser, debt) VALUES ('$iduser', '$totaldebt')");
        }
    }

    // USER - Return Item Request
    if (isset($_POST['returnidtrSend'])) {
        $idtr = $_POST['returnidtrSend'];

        $iduser = $_SESSION['iduser'];
        $result = mysqli_query($conn, "SELECT * FROM usertransaction u, item i WHERE i.itemid = u.itemid AND u.iduser = '$iduser' && idtr = $idtr;");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;

            $borrowqty = $row['borrowqty'];
            $returned = $row['returned'];
            if ($returned == 0) {
                $retamount = $borrowqty;
            } else if ($returned > 0) {
                $retamount = $borrowqty - $returned;
            }
        }

        array_push($response, $retamount);
        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['hiddenreturnidtrSend']) && isset($_POST['hiddenretborrowqtySend']) && isset($_POST['hiddenretreturnedSend']) && isset($_POST['returnamountSend'])) {
        $idtr = $_POST['hiddenreturnidtrSend'];
        $borrowqty = $_POST['hiddenretborrowqtySend'];
        $returned = $_POST['hiddenretreturnedSend'];
        $amount = $_POST['returnamountSend'];

        $ret = $returned + $amount;
        
        if ($ret < $borrowqty) {
            $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '4', req = '$amount' WHERE idtr = '$idtr'");
            $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '4', req = '$amount' WHERE idtr = '$idtr'");
        } else if ($ret == $borrowqty) {
            $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '6', req = '$amount' WHERE idtr = '$idtr'");
            $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '6', req = '$amount' WHERE idtr = '$idtr'");
        }
    }

    // USER - Cancel Return Request
    if (isset($_POST['cancelreturnidtrSend'])) {
        $idtr = $_POST['cancelreturnidtrSend'];

        $iduser = $_SESSION['iduser'];
        $result = mysqli_query($conn, "SELECT * FROM usertransaction u, item i WHERE i.itemid = u.itemid AND u.iduser = '$iduser' && idtr = $idtr;");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    } else {
        $response['status'] = 200;
        $response['message'] = "Invalid or data not found";
    }

    if (isset($_POST['hiddencancelreturnidtrSend']) && isset($_POST['hiddencancelreturnreqSend']) && isset($_POST['hiddencancelreturnstatusSend'])) {
        $idtr = $_POST['hiddencancelreturnidtrSend'];
        $req = $_POST['hiddencancelreturnreqSend'];
        $status = $_POST['hiddencancelreturnstatusSend'];

        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '9', req = '0' WHERE idtr = '$idtr'");
        $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '9', req = '0' WHERE idtr = '$idtr'");
    }

    // USER - Pay Debt
    if (isset($_POST['paydebtpayqtySend']) && isset($_POST['paydebtcurrdateSend'])) {
        $payqty = $_POST['paydebtpayqtySend'];
        $iduser = $_SESSION['iduser'];
        $currdate = date('Y-m-d H:i:s', strtotime($_POST['paydebtcurrdateSend']));

        $updatetr = mysqli_query($conn, "UPDATE debt SET debtstatus = '2', payqty = '$payqty', paydate = '$currdate' WHERE iduser = '$iduser'");
    }

    // COURIER - Display Data
    if (isset($_POST['courierdisplaySend'])) {
        $table = '
        <thead>
            <tr>
                <th>No.</th>
                <th>Username</th>
                <th>Item Name</th>
                <th>Amount</th>
                <th>Address</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $courierid = $_SESSION['iduser'];
        $result = mysqli_query($conn, "SELECT * FROM transaction WHERE status = '13' && courierid = $courierid");
        $i = 1;
        while ($data = mysqli_fetch_array($result)) {
            $idtr = $data['idtr'];
            $iduser = $data['iduser'];
            $itemid = $data['itemid'];
            $borrowqty = $data['borrowqty'];
            $address = $data['address'];
            $status = $data['status'];

            $resultuser = mysqli_query($conn, "SELECT * FROM user WHERE iduser = $iduser");
            $datauser = mysqli_fetch_array($resultuser);
            $username = $datauser['username'];

            $resultitem = mysqli_query($conn, "SELECT * FROM item WHERE itemid = $itemid");
            $dataitem = mysqli_fetch_array($resultitem);
            $itemname = $dataitem['itemname'];


            $courierbtn = '
                <a class="text-success" onclick="courierReport('.$idtr.')">
                    <i class="material-icons" data-toggle="tooltip" title="Courier Report">local_shipping</i>
                </a>';

            $table.='
                <tr id="'.$itemid.'">
                    <td>'.$i++.'</td>
                    <td>'.$username.'</td>
                    <td>'.$itemname.'</td>
                    <td>'.$borrowqty.'</td>
                    <td>'.$address.'</td>
                    <td>Courier On The Way to Location</td>
                    <td>'.$courierbtn.'</td>
                </tr>';
        
        }
        $table.='</tbody>';
        $datatable = "<script>   
            var table = $('#example').DataTable({
                'columnDefs': [{
                    'targets': 0
                }],
                'fnCreatedRow': function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data-id', aData.DT_RowId);
                    $(nRow).attr('id', 'id_' + aData.DT_RowId);
                },
                'order': [
                    [0, 'asc']
                ]
            });
            </script>"; 
        echo $table;
        echo $datatable;
    }

    // COURIER - Courier Location
    if (isset($_POST['courierlatSend']) && isset($_POST['courierlngSend'])) {
        $courierlat = $_POST['courierlatSend'];
        $courierlon = $_POST['courierlngSend'];

        $courierid = $_SESSION['iduser'];
        $resulttr = mysqli_query($conn, "UPDATE transaction SET courierlat = '$courierlat', courierlon = '$courierlon' WHERE courierid = '$courierid'");
        $resultutr = mysqli_query($conn, "UPDATE usertransaction SET courierlat = '$courierlat', courierlon = '$courierlon' WHERE courierid = '$courierid'");
    }

    // COURIER - Courier Report
    if (isset($_POST['courieridtrSend'])) {
        $idtr = $_POST['courieridtrSend'];

        $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser && idtr = $idtr");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    }

    if (isset($_POST['hiddencourieridtrSend']) && isset($_POST['receivernameSend'])) {
        $idtr = $_POST['hiddencourieridtrSend'];
        $receivername = $_POST['receivernameSend'];

        $updatetr = mysqli_query($conn, "UPDATE transaction SET status = '2', receivername = '$receivername' WHERE idtr = '$idtr'");
        $updateutr = mysqli_query($conn, "UPDATE usertransaction SET status = '2', receivername = '$receivername' WHERE idtr = '$idtr'");
    }

    // USER - Track Courier Location
    if (isset($_POST['trackcourieridtrSend'])) {
        $idtr = $_POST['trackcourieridtrSend'];

        $result = mysqli_query($conn, "SELECT * FROM transaction t, item i, user u WHERE i.itemid = t.itemid && u.iduser = t.iduser && idtr = $idtr");

        $response = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        echo json_encode($response);
    }
?>