<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_group(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `group_list` set {$data} ";
		}else{
			$sql = "UPDATE `group_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `group_list` where `name` = '{$name}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}'" : ""));
		if($check->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Account's Group Name already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$gid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Account's Group has successfully added.";
				else
					$resp['msg'] = " Account's Group details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_group(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `group_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Account's Group has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function save_prodtype(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$id = $_POST['id'];
		}else{
			$id = '';
		}
		
		if(isset($_POST['prod_type_name']) && !empty($_POST['prod_type_name'])){
			$prod_type_name = $_POST['prod_type_name'];
		}else{
			$prod_type_name = '';
		}
		
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('prod_type_ID'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `tbl_prod_type` set {$data} ";
		}else{
			$sql = "UPDATE `tbl_prod_type` set {$data} where prod_type_ID = '{$id}' ";
		}
		if(!empty($id)){
			$check = $this->conn->query("SELECT * FROM `tbl_prod_type` where `prod_type_name` = '{$prod_type_name}' and prod_type_ID != '{$id}'");
		}else{
			$check = $this->conn->query("SELECT * FROM `tbl_prod_type` where `prod_type_name` = '{$prod_type_name}'");
		}
		if($check->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Product Type's Name already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$gid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Product Type has been successfully added.";
				else
					$resp['msg'] = " Product Type details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occurred: " . $this->conn->error . " [{$sql}]";
				error_log("Error saving product type: " . $this->conn->error . " [{$sql}]", 0);
			}
		}
		return json_encode($resp);
	}
	function delete_prodtype(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `tbl_prod_type` where prod_type_ID = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Product Type has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function save_prodcat(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$id = $_POST['id'];
		}else{
			$id = '';
		}
		
		if(isset($_POST['prod_cat_name']) && !empty($_POST['prod_cat_name'])){
			$prod_cat_name = $_POST['prod_cat_name'];
		}else{
			$prod_cat_name = '';
		}
		
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('prod_cat_ID'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `tbl_prod_cat` set {$data} ";
		}else{
			$sql = "UPDATE `tbl_prod_cat` set {$data} where prod_cat_ID = '{$id}' ";
		}
		if(!empty($id)){
			$check = $this->conn->query("SELECT * FROM `tbl_prod_cat` where `prod_cat_name` = '{$prod_cat_name}' and prod_cat_ID != '{$id}'");
		}else{
			$check = $this->conn->query("SELECT * FROM `tbl_prod_cat` where `prod_cat_name` = '{$prod_cat_name}'");
		}
		if($check->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Product Category's Name already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$gid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Product Category successfully added.";
				else
					$resp['msg'] = " Product Category details updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occurred: " . $this->conn->error . " [{$sql}]";
				error_log("Error saving product category: " . $this->conn->error . " [{$sql}]", 0);
			}
		}
		return json_encode($resp);
	}
	function delete_prodcat(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `tbl_prod_cat` where prod_cat_ID = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Product Category has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function save_employee() {
		// Log the incoming POST data
		error_log("POST Data: " . print_r($_POST, true));
	
		$id = isset($_POST['id']) ? trim($_POST['id']) : '';
		$emp_name = isset($_POST['emp_name']) ? trim($_POST['emp_name']) : '';
	
		// Prepare data for the SQL query
		$data = [];
		foreach ($_POST as $k => $v) {
			if ($k != 'id') { // Ensure we're not including the ID in the data
				$v = $this->conn->real_escape_string($v);
				$data[] = "`{$k}`='{$v}'";
			}
		}
		$dataString = implode(", ", $data);
	
		// Debugging output
		error_log("ID being used for update: '{$id}'");
		error_log("Data for SQL: {$dataString}");
	
		// Check for duplicates based on emp_name
		$duplicateCheck = $this->conn->query("SELECT * FROM `tbl_employee` WHERE `emp_name` = '{$emp_name}'");
	
		// Log the duplicate check SQL
		error_log("Duplicate check SQL: SELECT * FROM `tbl_employee` WHERE `emp_name` = '{$emp_name}'");
	
		if ($duplicateCheck && $duplicateCheck->num_rows > 0) {
			$existingEmployee = $duplicateCheck->fetch_assoc();
	
			// If the duplicate exists and the ID matches, update it
			if (!empty($id) && $id != '0' && $existingEmployee['emp_ID'] == $id) {
				// Update operation
				$sql = "UPDATE `tbl_employee` SET {$dataString} WHERE emp_ID = '{$id}'";
				error_log("Executing Update SQL: {$sql}");
			} else {
				// If the duplicate exists and the ID does not match, update the existing employee instead of inserting
				$sql = "UPDATE `tbl_employee` SET {$dataString} WHERE emp_ID = '{$existingEmployee['emp_ID']}'";
				error_log("Duplicate found. Updating existing employee with ID: {$existingEmployee['emp_ID']}. Executing Update SQL: {$sql}");
			}
		} else {
			// If no duplicates, proceed with insert or update based on ID validity
			if (!empty($id) && $id != '0') {
				// Update operation if ID is valid
				$sql = "UPDATE `tbl_employee` SET {$dataString} WHERE emp_ID = '{$id}'";
				error_log("No duplicates found. Executing Update SQL: {$sql}");
			} else {
				// Insert operation if ID is empty or zero
				$sql = "INSERT INTO `tbl_employee` SET {$dataString}";
				error_log("ID is empty or zero. Executing Insert SQL: {$sql}");
			}
		}
	
		// Execute the query
		$save = $this->conn->query($sql);
		if ($save) {
			$resp['status'] = 'success';
			$resp['msg'] = empty($id) || $id == '0' ? "Employee successfully added." : "Employee details updated successfully.";
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred: " . $this->conn->error . " [{$sql}]";
			error_log("Error saving employee: " . $this->conn->error . " [{$sql}]", 0);
		}
		return json_encode($resp);
	}
	function delete_employee(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `tbl_employee` where emp_ID = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Employee has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function save_product() {
		// Log the incoming POST data
		error_log("POST Data: " . print_r($_POST, true));
	
		// Retrieve ID and product name from POST data
		$id = isset($_POST['id']) ? trim($_POST['id']) : '';
		$product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
	
		// Prepare data for the SQL query
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('suplier_id'))) {
				if (!is_numeric($v)) {
					$v = $this->conn->real_escape_string($v);
				}
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
	
		// Check for duplicates based on product_name
		if (!empty($id)) {
			$check = $this->conn->query("SELECT * FROM `products` WHERE `product_name` = '{$product_name}' AND product_id != '{$id}'");
		} else {
			$check = $this->conn->query("SELECT * FROM `products` WHERE `product_name` = '{$product_name}'");
		}
	
		// Log the duplicate check SQL
		error_log("Duplicate check SQL: " . (isset($check) ? $check->num_rows : 'No check performed'));
	
		if ($check && $check->num_rows > 0) {
			// Duplicate found, update the existing product instead of inserting
			$existingProduct = $check->fetch_assoc();
			$sql = "UPDATE `products` SET {$data} WHERE product_id = '{$existingProduct['product_id']}'";
			error_log("Duplicate found. Updating existing product with ID: {$existingProduct['product_id']}. Executing Update SQL: {$sql}");
		} else {
			// No duplicates found, insert or update based on ID validity
			if (empty($id)) {
				// Insert operation if ID is empty
				$sql = "INSERT INTO `products` SET {$data}";
				error_log("ID is empty. Executing Insert SQL: {$sql}");
			} else {
				// Update operation if ID is valid
				$sql = "UPDATE `products` SET {$data} WHERE product_id = '{$id}'";
				error_log("No duplicates found. Executing Update SQL: {$sql}");
			}
		}
	
		// Execute the query
		$save = $this->conn->query($sql);
		if ($save) {
			$gid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['msg'] = empty($id) ? "Product successfully added." : "Product details updated successfully.";
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred: " . $this->conn->error . " [{$sql}]";
			error_log("Error saving product: " . $this->conn->error . " [{$sql}]", 0);
		}
	
		return json_encode($resp);
	}
	function delete_product(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `products` where product_id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Product has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function save_supplier(){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$id = $_POST['id'];
		}else{
			$id = '';
		}
		
		if(isset($_POST['suplier_name']) && !empty($_POST['suplier_name'])){
			$suplier_name = $_POST['suplier_name'];
		}else{
			$suplier_name = '';
		}
		
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('suplier_id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `supliers` set {$data} ";
		}else{
			$sql = "UPDATE `supliers` set {$data} where suplier_id = '{$id}' ";
		}
		if(!empty($id)){
			$check = $this->conn->query("SELECT * FROM `supliers` where `suplier_name` = '{$suplier_name}' and suplier_id != '{$id}'");
		}else{
			$check = $this->conn->query("SELECT * FROM `supliers` where `suplier_name` = '{$suplier_name}'");
		}
		if($check->num_rows > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Supplier's Name already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$gid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Supplier successfully added.";
				else
					$resp['msg'] = " Supplier details updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occurred: " . $this->conn->error . " [{$sql}]";
				error_log("Error saving supplier: " . $this->conn->error . " [{$sql}]", 0);
			}
		}
		return json_encode($resp);
	}
	function delete_supplier(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `supliers` where suplier_id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Supplier has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	/*function delete_prodtype(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `tbl_prod_type` where prod_type_ID = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Product Type has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}*/

	function save_account(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `account_list` set {$data} ";
		}else{
			$sql = "UPDATE `account_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `account_list` where `name` ='{$name}' and delete_flag = 0 ".($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = " Account's Name already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Account has successfully added.";
				else
					$resp['msg'] = " Account has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
	}
	function delete_account(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `account_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Account has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_journal(){
		if(empty($_POST['id'])){
			$prefix = date("Ym-");
			$code = sprintf("%'.05d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `journal_entries` where `code` = '{$prefix}{$code}' ")->num_rows;
				if($check > 0){
					$code = sprintf("%'.05d",ceil($code) + 1);
				}else{
					break;
				}
			}
			$_POST['code'] = $prefix.$code;
			$_POST['user_id'] = $this->settings->userdata('id');
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))  && !is_array($_POST[$k])){
				if(!is_numeric($v) && !is_null($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				if(!is_null($v))
				$data .= " `{$k}`='{$v}' ";
				else
				$data .= " `{$k}`= NULL ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `journal_entries` set {$data} ";
		}else{
			$sql = "UPDATE `journal_entries` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$jid = !empty($id) ? $id : $this->conn->insert_id;
			$data = "";
			$this->conn->query("DELETE FROM `journal_items` where journal_id = '{$jid}'");
			foreach($account_id as $k=>$v){
				if(!empty($data)) $data .=", ";
				$data .= "('{$jid}','{$v}','{$group_id[$k]}','{$amount[$k]}')";
			}
			if(!empty($data)){
				$sql = "INSERT INTO `journal_items` (`journal_id`,`account_id`,`group_id`,`amount`) VALUES {$data}";
				$save2 = $this->conn->query($sql);
				if($save2){
					$resp['status'] = 'success';
					if(empty($id)){
						$resp['msg'] = " Journal Entry has successfully added.";
					}else
						$resp['msg'] = " Journal Entry has been updated successfully.";
				}else{
					$resp['status'] = 'failed';
					if(empty($id)){
						$resp['msg'] = " Journal Entry has failed to save.";
						$this->conn->query("DELETE FROM `journal_entries` where id = '{$jid}'");
					}else
						$resp['msg'] = " Journal Entry has failed to update.";
					$resp['error'] = $this->conn->error;
				}
			}else{
				$resp['status'] = 'failed';
				if(empty($id)){
					$resp['msg'] = " Journal Entry has failed to save.";
					$this->conn->query("DELETE FROM `journal_entries` where id = '{$jid}'");
				}else
					$resp['msg'] = " Journal Entry has failed to update.";
				$resp['error'] = "Journal Items is empty";
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_journal(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `journal_entries` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Journal Entry has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function cancel_journal(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `journal_entries` set `status` = '3' where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," journaling has successfully cancelled.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_reservation(){
		$_POST['journal'] = $_POST['date'] ." ".$_POST['time'];
		extract($_POST);
		$capacity = $this->conn->query("SELECT `".($seat_type == 1 ? "first_class_capacity" : "economy_capacity")."` FROM group_list where id in (SELECT group_id FROM `journal_entries` where id ='{$journal_id}') ")->fetch_array()[0];
		$reserve = $this->conn->query("SELECT * FROM `reservation_list` where journal_id = '{$journal_id}' and journal='{$journal}' and seat_type='$seat_type'")->num_rows;
		$slot = $capacity - $reserve;
		if(count($firstname) > $slot){
			$resp['status'] = "failed";
			$resp['msg'] = "This journal has only [{$slot}] left for the selected seat type/group";
			return json_encode($resp);
		}
		$data = "";
		$sn = [];
		$prefix = $seat_type == 1 ? "FC-" : "E-";
		$seat = sprintf("%'.03d",1);
		foreach($firstname as $k=>$v){
			while(true){
				$check = $this->conn->query("SELECT * FROM `reservation_list` where journal_id = '{$journal_id}' and journal='{$journal}' and seat_num = '{$prefix}{$seat}' and seat_type='$seat_type'")->num_rows;
				if($check > 0){
					$seat = sprintf("%'.03d",ceil($seat) + 1);
				}else{
					break;
				}
			}
			$seat_num = $prefix.$seat;
			$seat = sprintf("%'.03d",ceil($seat) + 1);
			$sn[] = $seat_num;
			if(!empty($data)) $data .= ", ";
			$data .= "('{$seat_num}','{$journal_id}','{$journal}','{$v}','{$middlename[$k]}','{$lastname[$k]}','{$seat_type}','{$fare_amount}')";
		}
		if(!empty($data)){
			$sql = "INSERT INTO `reservation_list` (`seat_num`,`journal_id`,`journal`,`firstname`,`middlename`,`lastname`,`seat_type`,`fare_amount`) VALUES {$data}";
			$save_all = $this->conn->query($sql);
			if($save_all){
				$resp['status'] = 'success';
				$resp['msg'] = "Reservation successfully submitted.";
				$get_ids = $this->conn->query("SELECT id from `reservation_list` where `journal_id` = '{$journal_id}' and `journal` = '{$journal}' and seat_type='{$seat_type}' and seat_num in ('".(implode("','",$sn))."') ");
				$res = $get_ids->fetch_all(MYSQLI_ASSOC);
				$ids = array_column($res,'id');
				$ids = implode(",",$ids);
				$resp['ids'] = $ids;
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured while saving the data. Error: ".$this->conn->error;
				$resp['sql'] = $sql;
			}
		}else{
			$resp['status'] = "failed";
			$resp['msg'] = "No Data to save.";
		}
		

		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_reservation(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `reservation_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Reservation Details has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_reservation_status(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `reservation_list` set `status` = '{$status}' where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"reservation Request status has successfully updated.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_reservation':
		echo $Master->save_reservation();
	break;
	case 'delete_reservation':
		echo $Master->delete_reservation();
	break;
	case 'update_reservation_status':
		echo $Master->update_reservation_status();
	break;
	case 'save_message':
		echo $Master->save_message();
	break;
	case 'delete_message':
		echo $Master->delete_message();
	break;
	case 'save_group':
		echo $Master->save_group();
	break;
	case 'delete_group':
		echo $Master->delete_group();
	break;
	case 'delete_prodcat':
		echo $Master->delete_prodcat();
	break;
	case 'save_employee':
		echo $Master->save_employee();
	break;
	case 'delete_employee':
		echo $Master->delete_employee();
	break;
	case 'save_product':
		echo $Master->save_product();
	break;
	case 'delete_product':
		echo $Master->delete_product();
	break;
	case 'save_supplier':
		echo $Master->save_supplier();
	break;
	case 'delete_supplier':
		echo $Master->delete_supplier();
	break;
	case 'delete_prodtype':
		echo $Master->delete_prodtype();
	break;
	case 'save_prodtype':
		echo $Master->save_prodtype();
	break;
	case 'save_prodcat':
		echo $Master->save_prodcat();
	break;
	case 'save_account':
		echo $Master->save_account();
	break;
	case 'delete_account':
		echo $Master->delete_account();
	break;
	case 'save_journal':
		echo $Master->save_journal();
	break;
	case 'delete_journal':
		echo $Master->delete_journal();
	break;
	case 'cancel_journal':
		echo $Master->cancel_journal();
	break;
	default:
		// echo $sysset->index();
		break;
}