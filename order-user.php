<?php
require_once "./protected.php";

$data = json_decode(file_get_contents("php://input"));

$today = date('Y-m-d 23:59:59', strtotime('- 1 day'));
    // print_r($decoded);
    $user_id = $decoded->data->user_id; //! Get user detail using Token

	// get array from reward 
    $query = "SELECT id, reward, sub_title, tnc, point, photo, start_date, end_date, discount FROM reward WHERE end_date > '$today' && active_status = 0 ORDER BY start_date DESC ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();

    if($stmt->execute()){
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $id = $row['id'];
        $reward = $row['reward'];
        $sub_title = $row['sub_title'];
        $tnc = $row['tnc'];
        $point = $row['point'];
        $photo = $row['photo'];
        $start_date = date('Y-m-d', strtotime($row['start_date']));
        $end_date = date('Y-m-d', strtotime($row['end_date']));
		
		//cari reward yang pernah digunakan oleh user
		$query2 = "SELECT user_id, reward_id, redeem_date, redeem_location_id, status FROM reward_history WHERE reward_id = '$id' && user_id = '$user_id' ";
		$stmt2 = $conn->prepare($query2);
    	$stmt2->bindParam(':user_id', $user_id);
		$stmt2->execute();
		$num2 = $stmt2->rowCount();

    	if ($num2 > 0) { // select jika user pernah redeem reward
			
			$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
				$status = "USED";
		} else {
			//dapatkan point terkini user
			$query3 = "SELECT user_id, user_point FROM user WHERE user_id = '$user_id' ";
			$stmt3 = $conn->prepare($query3);
			$stmt3->bindParam(':user_id', $user_id);
			$stmt3->execute();
			$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
			
			if($row3['user_point'] >= $point ) { // select jika point terkini user lebih atas sama dengan point reward
				$status = "USED AT COUNTER";
			} else {
				$status = "LOCK";
			}	
				
				
		}
		
		if($status != 'USED') { // jika user tidak pernah redeem reward, baru keluarkan data
			
		http_response_code(200);
		echo json_encode(
			array(
				"user_id" => $user_id,
				"id" => $id,
				"reward" => $reward,
				"sub_title" => $sub_title,
				"tnc" => $tnc,
				"point" => $point,
				"photo" => $photo,
				"start_date" => $start_date,
				"end_date" => $end_date,
				"status" => $status,
			)
		);
			
		}
		
	}
		   
	}
	//}
