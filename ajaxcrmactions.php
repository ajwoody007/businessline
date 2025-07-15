<?php

include ("../../includes/includes.php");
    include ("../mdl/crm_mdl.php");

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	function connect_mdl() {
        
        $objMdl = new crm_mdl;
        return $objMdl;

    }

	$string						=		new safeEscapeString($_POST["action"]);
	$action						=		$string -> returnString();

	switch ($action) {	

		case "archive":

			$activityID			=		$_POST["id"];

			$mdl 				= 		connect_mdl();
			$setArchive 		= 		$mdl->set_archive_db($activity_id);

			return $setArchive;

			break;

		case "snooze":

			$activityID			=		$_POST["id"];
			//$query			=		"UPDATE activity SET x = '$action' WHERE activityID = '$activityID'";
			$result				=		$db -> query($query);
			break;

		case "reassign":

			$activityID			=		$_POST["id"];
			//$query			=		"UPDATE activity SET x = '$action' WHERE activityID = '$activityID'";
			$result				=		$db -> query($query);
			break;

		case "markascomplete":

			$activityID			=		$_POST["id"];
			$query				=		"UPDATE activity SET activityCompleted = NOW() WHERE activityID = '$activityID'";
			$result				=		$db -> query($query);
			break;

		case "mark_activity_complete":

			$activity_id		=		$_POST["activity_id"];
			$query				=		"UPDATE activity SET activityCompleted = NOW() WHERE activityID = '$activity_id'";
			$result				=		$db -> query($query);
			break;

		case "change_follow_up_by":

			$_SESSION['team_member'] = $_POST["member_id"];

			break;

		case "set_crm_show":

			$show_or_hide = $_POST["flag"];

			if ($show_or_hide == "1") {
				$_SESSION["show_completed"] = true;
			} else {
				$_SESSION["show_completed"] = false;
			}

			break;

		case "show_to_do":

			if (!isset($_SESSION["show_to_do"])) { $_SESSION["show_to_do"] = true; } else { unset($_SESSION["show_to_do"]);}

		break;

		case "set_crm_range":

			$_SESSION["show_range"] = $_POST["range"];
			echo $_SESSION["show_range"];
			break;

		case "count_series":

			$activity_id		= 		$_POST['activity_id'];
			$getSeriesQuery		=		"SELECT activitySeries from activity WHERE activityID = '$activity_id' ORDER BY followUpDate";
			$getSeriesResult	=		$db -> query($getSeriesQuery);
			$getSeriesArray		=		$getSeriesResult->fetch();
			$getSeriesID		=		$getSeriesArray['activitySeries'];

			$countQuery			= 		"SELECT count(activityID) total_in_series FROM activity WHERE activitySeries = '$getSeriesID' AND activityArchive is null";
			$getCountResult		=		$db -> query($countQuery);
			$getCountArray		=		$getCountResult->fetch();
			$getSeriesCount		=		$getCountArray['total_in_series'];

			$countPosQuery		= 		"SELECT count(activityID) pos_in_series FROM activity WHERE activitySeries = '$getSeriesID' AND activityID > '$activity_id' AND activityArchive is null";
			$getCountPosResult	=		$db -> query($countPosQuery);
			$getCountPosArray	=		$getCountPosResult->fetch();
			$getSeriesCountPos	=		$getCountPosArray['pos_in_series'];



			$actualPos = $getSeriesCount - $getSeriesCountPos;

			echo $getSeriesCount . "," . $actualPos;

			break;
			
		case "get_recurring_end_date":

			$activity_id = $_POST['activity_id'];

			$mdl 				= 		connect_mdl();
			$end_Date 			= 		$mdl->get_recurring_end_date_db($activity_id);

			echo $end_Date;

			return;

		case "get_crm_task":

			unset($_SESSION['activity_id']);
			$task_id 					= 		$_POST['activity_id'];
			$_SESSION['activity_id'] 	= 		$_POST['activity_id'];
			$_SESSION['task_action'] 	= 		"edit";

			$mdl 				= 		connect_mdl();
			$taskArray 			= 		$mdl->get_crm_task_db($task_id);

			$myArrayParsed = json_encode($taskArray);
			print_r($myArrayParsed); 

			break;

		case "change_crm_display":

			unset($_SESSION['crm_display_type']);
			$_SESSION['crm_display_type'] = $_POST["new_value"];

			echo $_SESSION['crm_display_type'];	

			break;	

		case "cal_back":

			 $new_date = $_SESSION['cal_start_date']->modify('-7 days');
			 unset($_SESSION['cal_start_date']);
			 $_SESSION['cal_start_date'] = $new_date;

			break;

		case "cal_back_month":

			$new_date = $_SESSION['cal_start_date']->modify('-1 month');
			$referenceDate = $new_date->modify(-($new_date->format('N')-1) . 'days');
			unset($_SESSION['cal_start_date']);
			$_SESSION['cal_start_date'] = $referenceDate;

			break;			

		case "cal_forward":

			$new_date = $_SESSION['cal_start_date']->modify('+7 days');
			unset($_SESSION['cal_start_date']);
			$_SESSION['cal_start_date'] = $new_date;

			break;	

		case "cal_forward_month":

			$new_date = $_SESSION['cal_start_date']->modify('+1 month');
			$referenceDate = $new_date->modify(-($new_date->format('N')-1) . 'days');
			unset($_SESSION['cal_start_date']);
			$_SESSION['cal_start_date'] = $referenceDate;

			echo $_SESSION['cal_start_date']->format("d/m/Y");

			break;				
			
		case "cal_today":

			unset($_SESSION['cal_start_date']);
			unset($_SESSION['defineWeek']);

			break;

		case "change_task_follow_up_date":

			$activity_id			=		$_POST["activity_id"];
			$new_date 				=		$_POST["new_date"];
			$original_time			=		$_POST["activity_time"];
			$new_date_full			= 		$new_date . " " . $original_time;

			/* get the base activityId for this task series */
			
			$getTaskDetails 		=		"SELECT activitySeries, followUpDate, followUpAssignees FROM activity where activityID = '$activity_id'";
			$getTaskResult			=		$db -> query($getTaskDetails);
			$getTaskArray			=		$getTaskResult->fetch();
			$getTaskSeries			=		$getTaskArray['activitySeries'];
			$getTaskBaseID			=		$getTaskArray['activitySeries'];
			$getTaskBaseDate		=		$getTaskArray['followUpDate'];
			$getTaskForArray		=		json_decode($getTaskArray['followUpAssignees']);

			$array_pos = array_search($current_member_id, $getTaskForArray);
			array_splice($getTaskForArray, $array_pos, 1);
			array_push($getTaskForArray, $new_member_id);

			$newMemberValue = json_encode($getTaskForArray);

			$query				=		"UPDATE activity SET followUpDate = '$new_date_full' WHERE activityID = '$activity_id'";
			$result				=		$db -> query($query);

			// if ($getTaskSeries) {

			// 	$query				=		"UPDATE activity SET activityFor = '$new_member_id' WHERE activitySeries = '$getTaskBaseID' AND followUpDate >= '$new_date'";

			// }
			$result					=		$db -> query($query);
			break;

		case "cancel_activity":

			$activity_id		=		$_POST["activity_id"];
			$query				=		"UPDATE activity SET activityArchive = NOW() WHERE activityID = '$activity_id'";
			$result				=		$db -> query($query);
			break;

		case "cancel_all_activity":

			$activity_id		=		$_POST["activity_id"];

			/* first, get the series id for which this activity is a part */

			$getSeriesQuery		=		"SELECT activitySeries from activity WHERE activityID = '$activity_id'";
			$getSeriesResult	=		$db -> query($getSeriesQuery);
			$getSeriesArray		=		$getSeriesResult->fetch();
			$getSeriesID		=		$getSeriesArray['activitySeries'];

			$query				=		"UPDATE activity SET activitySeries = null, seriesType = null, activityArchive = NOW() WHERE activitySeries = '$getSeriesID'";
			echo $query;
			$result				=		$db -> query($query);
			break;

		case "cancel_future_activity":

			$activity_id		=		$_POST["activity_id"];

			/* first, get the series id for which this activity is a part */

			$getSeriesQuery		=		"SELECT activitySeries, followUpDate from activity WHERE activityID = '$activity_id'";
			$getSeriesResult	=		$db -> query($getSeriesQuery);
			$getSeriesArray		=		$getSeriesResult->fetch();
			$getSeriesID		=		$getSeriesArray['activitySeries'];
			$getActivityDate	=		$getSeriesArray['followUpDate'];

			$query				=		"UPDATE activity SET activitySeries = null, seriesType = null, activityArchive = NOW() WHERE activitySeries = '$getSeriesID' and followUpDate > '$getActivityDate'";
			$result				=		$db -> query($query);
			break;		
			
		case "cancel_recurrance":

			$activity_id		=		$_POST["activity_id"];

			/* first, archive all the activities in series except the first */

			$query				=		"UPDATE activity SET activitySeries = null, seriesType = null, activityArchive = NOW() WHERE activitySeries = '$activity_id' AND activityID != '$activity_id'";
			$result				=		$db -> query($query);

			/* next, remove first from series */

			$query				=		"UPDATE activity SET activitySeries = null, seriesType = null WHERE activityID = '$activity_id'" ;
			$result				=		$db -> query($query);
			break;				

		case "get_series":

			$activityID 				=	$_POST['activity_id'];

			/* get the series data */

			$mdl 				= 		connect_mdl();
			$getSeriesResult	= 		$mdl->get_series_data_db($activityID);
			
			$getSeriesSize			= 	$getSeriesResult->size();
			$getSeriesArray			= 	[];
			$task_count				=	1;
			$today					=	Date('Y-m-d 23:59');
			$place_line_count		= 	1;
			$full_date				= 	"";

			// if ( $getSeriesSize > 0 ) {
			
			// 	$full_date = 
			// 		"<div style='background-color:#005C37;color:#fff;padding:8px 0px;'>
			// 			<div style='display:inline-block;width:75px!important;padding-left:8px;'>Task No.</div>
			// 			<div style='display:inline-block;width:175px!important;'>Date.</div>
			// 			<div style='display:inline-block;width:300px!important;'>Next Action.</div>
			// 			<div style='display:inline-block;width:175px!important;'>Assignees</div>
			// 			<div style='display:inline-block;width:175px!important;'>Completed</div>
			// 		</div>";

			// }

			// if ( $getSeriesSize > 0 ) {

			// 	$full_date = "<table class='series_data_table'><th style='width:20%;'>Task No.</th><th>Date</th><th>Next Action</th><th>Assignees</th><th>Completed?</th>";

			// }

			array_push($getSeriesArray, $full_date);

			while($getSeriesRow = $getSeriesResult->fetch()) {

				$full_date = "";

				/* for each activity, get the initials for each of the assignees */

				$assigneeArray = json_decode($getSeriesRow['followUpAssignees']);

				$assigneeArrayResult = $mdl->get_activity_members_db($assigneeArray);
				$inits = "";

				while ($assigneeRow = $assigneeArrayResult->fetch()) {

					$name_inits = $assigneeRow["member_inits"] ;
					$name_back = $assigneeRow['memberBackColour'];
					$inits .= '<span style="background-color:' . $name_back . ';font-size:0.9em;border-radius:50%;width:32px;height:32px;line-height:32px;text-align:center;color:#fff;display:inline-block;margin:0px 2px;font-weight:bold;">' . $name_inits . '</span>';

				}

				/* for each activity get the latest task */

				$task_query = "SELECT max(taskID), task FROM activity_tasks WHERE activityID = " . $getSeriesRow['activityID'] . " GROUP by activityID";
				$task_result = $db->query($task_query);
				$task_array = $task_result->fetch();
				$task = $task_array['task'];

				if ($getSeriesRow['activityCompleted']) { 
					$display_flag_start = "<tr>"; 
					$display_flag_end = "</tr>"; 
					$completed_flag = $getSeriesRow['completed_date'];
					$member_inits  = "<span style='padding:8px 6px;color:#fff;height:50px;width:50px;line-height:50px;font-size:0.75em;font-weight:700;background-color:" . $getSeriesRow['memberBackColour'] . ";border-radius:50%;'>" . $getSeriesRow['completed_by_inits'] . "</span>";
					$completed_message = "This task was completed by " . $member_inits . " on " . $completed_flag;
				} else { 
					$display_flag_start = "<tr >"; 
					$display_flag_end = "</tr>"; 
					$completed_flag = "";
					$completed_message = "";
				} 

				$dateObj = \DateTime::createFromFormat("Y-m-d", $getSeriesRow['followUpDate']);
				$today_date = \DateTime::createFromFormat("Y-m-d", $today);

				$top_border = "none";
				if ($dateObj > $today_date) {
					$top_border = "2px #000 solid";
				}

				$border_style ="border-left:10px solid " . $getSeriesRow["trayBackColour"] . ";";
				$tray_block = "<span style='margin-left:12px;border-radius:12px;padding:4px 8px;color:#fff;font-size:0.8em;background-color:" . $getSeriesRow["trayBackColour"] . "';>" . $getSeriesRow["trayName"] . "</span>";

				// $full_date 		.= 		"
				// 						<div style='margin:8px;background-color:#fff;padding:12px 0px;border-left:5px solid " . $border_colour . ";border-top:" . $top_border . ";'>
				// 						<div style='display:inline-block;width:61px!important;padding-left:8px;border:0px blue solid;'>" . $task_count . "</div>
				// 						<div style='display:inline-block;width:178px!important;'>" . $getSeriesRow['follow_up_date'] . "</div>
				// 						<div style='display:inline-block;width:294px!important;'>" . $task . "</div>
				// 						<div style='display:inline-block;width:180px!important;'>" . $inits . "</div>
				// 						<div style='display:inline-block;width:175px!important;'>" . $completed_flag . "</div>
				// 						</div>
				// 						";

										
				$full_date = "<div style='display:inline-block;padding:8px;background-color:#fff;margin:12px 8px;width:96%;" . $border_style . "'>";
				$full_date .= "<div style='display:inline-block;text-align:left;width:60%;font-weight:700'>" . $task . "</div>";
				$full_date .= "<div style='margin:12px 0px;display:inline-block;float:right;text-align:right;width:30%;'>" .  $inits . $tray_block . "</div>";
				$full_date .= "<div style='display:inline-block;width:100%;'>";
				$full_date .= "<div style='display:inline-block;text-align:right;float:right;width:35%;'>" . $getSeriesRow['follow_up_date'] . "</div>";
				$full_date .= "<div style='display:inline-block;text-align:left;float:left;width:60%;'>" . $completed_message . "</div>";
				$full_date .= "</div>";
				$full_date .= "</div>";
				
				array_push($getSeriesArray, $full_date);
				$task_count++;

			}

			$full_date = "</table>";
			array_push($getSeriesArray, $full_date);

			$myArrayParsed = json_encode($getSeriesArray);
			print_r($myArrayParsed);

		break;

		case "set_cal_view_type":

			$cal_view = $_POST['cal_view'];

			unset($_SESSION['cal_view']);
			$_SESSION['cal_view'] = $cal_view;

		break;

		case "show_activities":

			$type = $_POST['activity_type'];

			switch ($type) {
				case "lates":
					if (!isset($_SESSION['lates'])) { $_SESSION['lates'] = true; } else { unset($_SESSION['lates']);}
				break;
				case "recurring":
					if (!isset($_SESSION['recurring'])) { $_SESSION['recurring'] = true; } else { unset($_SESSION['recurring']);}
				break;
				case "types":
					if (!isset($_SESSION['types'])) { $_SESSION['types'] = true; } else { unset($_SESSION['types']);}
				break;
				case "stages":
					if (!isset($_SESSION['stages'])) { $_SESSION['stages'] = true; } else { unset($_SESSION['stages']);}
				break;
				case "all":
					$_SESSION['lates'] = true;
					$_SESSION['recurring'] = true;
					$_SESSION['types'] = true;
					$_SESSION['stages'] = true; 
					unset($_SESSION["show_to_do"]);
				break;

			}

		break;

		case "show_types":

			$type = $_POST['activity_type'];
			// echo $_SESSION['calls'] ; exit;
			switch ($type) {
				case "show_all_types":
					$_SESSION['type_to_show'] = "all";
					break;
				case "calls":
					if (!isset($_SESSION['calls'])) { $_SESSION['calls'] = true; } else { unset($_SESSION['calls']);}
					break;
				case "emails":
					if (!isset($_SESSION['emails'])) { $_SESSION['emails'] = true; } else { unset($_SESSION['emails']);}
					break;
				case "notes":
					if (!isset($_SESSION['notes'])) { $_SESSION['notes'] = true; } else { unset($_SESSION['notes']);}
					break;
				case "reminders":
					if (!isset($_SESSION['reminders'])) { $_SESSION['reminders'] = true; } else { unset($_SESSION['reminders']);}
					break;
				case "visits":
					if (!isset($_SESSION['visits'])) { $_SESSION['visits'] = true; } else { unset($_SESSION['visits']);}
					break;
			}

		break;

		case "update_crm_defaults":

			$defaults_string = $_POST['values'];
			$memberID = $_POST["member_id"];

			$defaults_array = explode (",", $defaults_string);
			$JSON_array = json_encode($defaults_array);

			$updateQuery 		=		"UPDATE members set memberCRMDefaults = '" . $JSON_array . "' WHERE memberID = " . $memberID;
			$updateResult		=		$db -> query($updateQuery);

			break;

		case "minimize_crm":

			$_SESSION['crm_data'] = $_POST['data_array'];
			$_SESSION['minimized'] = true;

			print_r($_SESSION['crm_data']);

			break;

		case "maximize_crm":

			$myArrayParsed = json_encode($_SESSION['crm_data']);
			print_r($myArrayParsed);
			
			unset($_SESSION['minimized']);

			break;			
			
			
		case "snooze_activity";

				/* 	this is to snooze the activity from today, so get todays date and add 3 days - if the new date falls on a saturday, push forward two further days */

				$activity_id = $_POST['activity_id'];
				$today = date('Y-m-d');
				$getCurrentDate = date('Y-m-d');

				$follow_up_date = new DateTime($today);

					for ($iCnt = 1; $iCnt <= 3; $iCnt++) {

						$follow_up_date->modify('+1 days');

						if($follow_up_date->format('N') == 6) {$follow_up_date 		= 	$follow_up_date->modify('+2 day'); } 
						if($follow_up_date->format('N') == 7) {$follow_up_date 		= 	$follow_up_date->modify('+1 day'); } 
					}

				$updateQuery 	= "UPDATE activity SET followUpDate = '" . $follow_up_date->format('Y-m-d H:i:s') . "' WHERE activityID = " . $activity_id;
				$result			= $db->query($updateQuery);

				echo $follow_up_date->format('d/m/Y');

			break;

			case "save_preferences":

				$arr_pref = $_POST['pref_data'];

				/* does this user have any settings stored already? if so, update, if not, insert */

				$getUserPrefQuery = "SELECT memberID FROM crm_preferences WHERE memberID = " . $_SESSION["memberSPLSWID"];
				$getUserPrefResult = $db->query($getUserPrefQuery);

				$def_view = "1";

				if ($arr_pref[15] == "true") { $def_view = "1" ;} 
				if ($arr_pref[16] == "true" ) { $def_view = "2" ;} 
				if ($arr_pref[17] == "true") { $def_view = "3" ;} 

				if ($getUserPrefResult->fetch()) { // update

					$saveUserPrefQuery = "UPDATE crm_preferences SET 
											lates 			= " . $arr_pref[0] . ",
											recur 			= " . $arr_pref[1] . ",
											stages 			= " . $arr_pref[2] . ",
											types 			= " . $arr_pref[3] . ",
											calls 			= " . $arr_pref[4] . ",
											emails 			= " . $arr_pref[5] . ",
											notes 			= " . $arr_pref[6] . ",
											reminders 		= " . $arr_pref[7] . ",
											visits 			= " . $arr_pref[8] . ",
											to_do 			= " . $arr_pref[9] . ",
											completed 		= " . $arr_pref[10] . ",
											today 			= " . $arr_pref[11] . ",
											to_date 		= " . $arr_pref[12] . ",
											future 			= " . $arr_pref[13] . ",
											show_all 		= " . $arr_pref[14] . ",
											recur_col 		= " . $arr_pref[18] . ",
											default_view 	= " . $def_view . "
											WHERE memberID 	= " . $_SESSION["memberSPLSWID"];
				} else { // insert
					$saveUserPrefQuery = "INSERT INTO crm_preferences 
					(memberID, lates, recur, stages, types, calls, emails, notes, reminders, visits, to_do, completed, today, to_date, future, show_all, recur_col, default_view)
					VALUES (
						" . $_SESSION["memberSPLSWID"] . ",
						" . $arr_pref[0] . ",	
						" . $arr_pref[1] . ",
						" . $arr_pref[2] . ",
						" . $arr_pref[3] . ",
						" . $arr_pref[4] . ",
						" . $arr_pref[5] . ",
						" . $arr_pref[6] . ",
						" . $arr_pref[7] . ",
						" . $arr_pref[8] . ",
						" . $arr_pref[9] . ",
						" . $arr_pref[10] . ",
						" . $arr_pref[11] . ",
						" . $arr_pref[12] . ",
						" . $arr_pref[13] . ",
						" . $arr_pref[14] . ",
						" . $arr_pref[18] . ",
						" . $def_view . "
					);";
				}

				$saveUserPrefResult = $db->query($saveUserPrefQuery);

			break;

		case "sort_crm_table":

			$new_col_to_sort = $_POST['col_to_sort'];

			if (isset($_SESSION['crm_col_to_sort'])) { $prev_col_to_sort = $_SESSION['crm_col_to_sort']; } else { $prev_col_to_sort = ""; } 
			if (isset($_SESSION['crm_sort_order'])) { $sort_order = $_SESSION['crm_sort_order']; } else { $sort_order = "ASC";}

			/* reverse sort order if same column clicked */

			if ($new_col_to_sort == $prev_col_to_sort) { 
				if ($sort_order == "ASC") { $sort_order = "DESC"; } else { $sort_order = "ASC";} 
			} else {
				$sort_order = "ASC";
			}

			$order_by = "ORDER BY " . $new_col_to_sort . " " . $sort_order;

			if ($new_col_to_sort == "followUpNotes") { $order_by = "ORDER BY all_notes" . " " . $sort_order; }
			if ($new_col_to_sort == "quotes_orders") { $order_by = "ORDER BY all_q_o" . " " . $sort_order; }

			$_SESSION['crm_sort_order'] = $sort_order;
			$_SESSION['crm_col_to_sort'] = $new_col_to_sort;
			$_SESSION['crm_full_order_by'] = $order_by;


			echo $_SESSION['crm_sort_order'];
			break;

		case "undo_complete":

			$activity_id = $_POST['activity_id'];

			$updateQuery 	= "UPDATE activity SET activityCompleted = null WHERE activityID = " . $activity_id;
			$result			= $db->query($updateQuery);

			break;

		case "change_kan_col":

			$activity_id = $_POST['activity_id'];
			$new_col = $_POST['new_col'];

			$updateQuery = "UPDATE activity SET followUpStage = " . $new_col . " WHERE activityID = " . $activity_id;
			$result			= $db->query($updateQuery);

			break;

		case "change_type_stage":

			$activity_id 	= 	$_POST['activity_id'];
			$new_col_val 	= 	$_POST['new_col_val'];
			$new_category	= 	$_POST['new_col_category'];

			/* get the current values first */

			$currentQuery 	= "SELECT followUpType, followUpStage FROM activity WHERE activityID = " . $activity_id;
			$result			= $db->query($currentQuery);
			$currentArray	= $result->fetch();

			if ($new_category == "stages") { $new_stage = $new_col_val; $new_type = $currentArray['followUpType']; }
			if ($new_category == "types") { $new_stage = $currentArray['followUpStage']; $new_type = $new_col_val; }

			if ($new_col_val == "Visit") {$new_type = 'Site Visit' ;}

			/* update to the new values */

			if ($new_category != "trays") { 

				$updateQuery 	= "UPDATE activity set followUpType = '" .  $new_type . "', followUpStage = '" . $new_stage . "' WHERE activityID = " . $activity_id;
				$result			= $db->query($updateQuery);

			}

			if ($new_category == "trays") { 

				$updateQuery 	= "UPDATE activity set nextActionID = '" .  $new_col_val . "'  WHERE activityID = " . $activity_id;
				$result			= $db->query($updateQuery);

			}

			break;

		case "set_activity_date" :

			$activity_date = date("Y-m-d");
			$duration = $_POST['duration'];

			$dateObj = \DateTime::createFromFormat("Y-m-d", $activity_date);
			$follow_up_date = $dateObj;

			$new_date = "";

			if ($duration == "today") { $new_date = $follow_up_date;}
			if ($duration == "day") { $new_date = $follow_up_date->modify('+1 day');}
			if ($duration == "week") { $new_date = $follow_up_date->modify('+1 week');}

			if($new_date->format('N') == 6) {
				$new_date 		= 	$new_date->modify('+2 day');
			}

			if($new_date->format('N') == 7) {
				$new_date 		= 	$new_date->modify('+1 day');
			}

			echo $new_date->format("d/m/Y");

			break;

		case "add_crm_task":

			$activty_id = $_POST['activity_id'];
			$task = $_POST['task'];

			$insertQuery = 'INSERT INTO activity_tasks (activityID, task, allocatedTo, createdDate, createdBy)
							VALUES (
								' . $activty_id . ',
								"' . $task . '",
								' . $_SESSION['memberSPLSWID'] . ',
								NOW(),
								' . $_SESSION['memberSPLSWID'] . '
							)';

			$insertResult = $db->query($insertQuery);

			break;

		case "change_crm_task":

			$task_id = $_POST['task_id'];
			$task = $_POST['task'];

			$updateQuery 	= 	'UPDATE activity_tasks SET task = "' . $task . '" WHERE taskID = ' . $task_id;

			$updateResult 	= 	$db->query($updateQuery);

			break;

		case "get_activity_tasks":

			$activty_id = $_POST["activity_id"];

			$taskQuery = "SELECT a.taskID, a.task, a.allocatedTo, a.completedDate, date_format(a.createdDate, '%d/%m/%Y %l:%i%p') date_created, 
							concat(left(m.memberDisplayname,1),left(m.memberSurname,1)) created_by
							FROM activity_tasks a 
							LEFT JOIN members m ON m.memberID = a.createdBy
							WHERE activityID = " . $activty_id . "
							ORDER BY createdDate DESC";

			$taskResult = $db->query($taskQuery);
			$taskCount = $taskResult->size();
			$taskArray = [];
			$iRowCount = 0;

			$_SESSION['task_count'] = $taskCount;

			while ($taskRow = $taskResult->fetch()) {

				$row_id = "edit_crm_task" . $iRowCount;
				$span_id = "edit_crm_timestamp" . $iRowCount;
				$task_id = $taskRow['taskID'];
				$task_data = '<textarea class="borderless" style="outline:none;border:none;margin:0px;padding:0px;height:auto;resize:none;" id="' . $row_id . '" onkeyup=change_crm_task(' . $task_id . ',' . $iRowCount . ') >' . $taskRow["task"] . '</textarea><br>
				<span id="' . $span_id . '" style="font-style:italic;color:#999;contenteditable:false;">' . strtolower($taskRow["date_created"]) . ' by ' . $taskRow["created_by"] . "</span>";
				$task_line = '<div class="historyblock" style="display:block;">';
				$task_line .= '<div >' . $task_data . '</div>';
				$task_line .= '</div>';

				array_push($taskArray,$task_line) ;
				$iRowCount++;
				
			}
			
			// print_r($taskArray);
			$myArrayParsed = json_encode($taskArray);
			print_r($myArrayParsed);

			break;

		case "delete_crm_task":

			$task_id = $_POST['task_id'];

			$taskQuery = "DELETE FROM activity_tasks WHERE taskID = " . $task_id; 
			$taskResult = $db->query($taskQuery);

			break;

		case "allocate_crm_task":

			$task_id = $_POST['task_id'];
			$member_id = $_POST['member_id'];

			$updateQuery = 'UPDATE activity_tasks set allocatedTo = ' . $member_id . ' WHERE taskID = ' . $task_id;
			echo $updateQuery;
			$updateResult = $db->query($updateQuery);

			break;

		case "complete_toggle":

			$task_id = $_POST['task_id'];
			$is_checked = $_POST['is_checked'];

			echo($is_checked);

			if ($is_checked == "true") {
				$updateQuery = 'UPDATE activity_tasks set completedDate = NOW(), updatedDate = NOW(), updatedBy = ' . $_SESSION['memberSPLSWID'] . ' WHERE taskID = ' . $task_id;
			} else {
				$updateQuery = 'UPDATE activity_tasks set completedDate = null, updatedDate = null, updatedBy = null WHERE taskID = ' . $task_id;
			}
			echo $updateQuery;
			$updateResult = $db->query($updateQuery);

			break;

			/* start of crm customer */

		case "change_customer":

			$customer 			= 	$_POST["customerID"] ; 
			$profileQuery 		=	"SELECT contactsProfile FROM contacts where contactsID = " .  $customer;

			$profileResult		=	$db->query($profileQuery);
			$noteList = '';
	
			while ($noteRow = $profileResult->fetch()) {
				$noteList .= $noteRow['contactsProfile'] . "\n";
			}

			echo $noteList;
	
		break;

		case "get_primary":
			
			$customer 			= 	$_POST["customerID"] ; 
			$primaryQuery 		=	"SELECT contactsName, contactsTelephone, contactsEmail FROM contactperson where contactsID = " .  $customer . " AND `primary` = '1'";
			$primaryResult		=	$db->query($primaryQuery);
			$primaryList = '';
	
			while ($primaryRow = $primaryResult->fetch()) {
				$name = $primaryRow['contactsName'];
				$phone =  $primaryRow['contactsTelephone'];  
				$email =  $primaryRow['contactsEmail'];  
			}

			$crmData = [];
			array_push($crmData, $name, $phone, $email);
			$JSON_array = json_encode($crmData);

			print_r($JSON_array);

		break;

		case "all_contacts":

			$customer 			= 	$_POST["customerID"] ; 
			
			$primaryQuery 		=	"SELECT contactsName, contactPersonID, `primary`, contactsTelephone, contactsEmail, contactsProfile,
									family, pets, sport, holiday, date_format(birthday, '%d/%m/%Y') birthday
									FROM contactperson 
									WHERE contactsID = " .  $customer . "
									AND deleted = 0";

			$primaryResult		=	$db->query($primaryQuery);
			$primaryList = '';

			$crmData = [];
	
			while ($primaryRow = $primaryResult->fetch()) {
				$name 	= 	$primaryRow['contactsName'] . ";";
				$name 	.= 	$primaryRow['contactPersonID'] . ";";
				$name 	.= 	$primaryRow['primary'] . ";";
				$name 	.= 	$primaryRow['contactsTelephone'] . ";";
				$name 	.= 	$primaryRow['contactsEmail'];
				$name 	.= 	$primaryRow['contactsProfile'];
				array_push($crmData, $name);

			}
			
			$JSON_array = json_encode($crmData);
			print_r($JSON_array);

		break;

		case "get_self_details":

			$member_id 			= 	$_POST["member_id"] ; 
			$memberQuery 		=	"SELECT concat(memberDisplayName, ' ', memberSurname) member_name, memberID, memberMobile, memberEmail FROM members where memberID = " . $member_id;
			$memberResult		=	$db->query($memberQuery);
			$memberList = '';

			$crmData = [];
	
			while ($memberRow = $memberResult->fetch()) {
				$name 	= 	$memberRow['member_name'] . ";";
				$name 	.= 	$memberRow['memberID'] . ";";
				$name 	.= 	$memberRow['memberMobile'] . ";";
				$name 	.= 	$memberRow['memberEmail'];
				array_push($crmData, $name);

			}
			
			$JSON_array = json_encode($crmData);
			print_r($JSON_array);

		break;		

		case "get_crm_contact":

			$contact_id			= 	$_POST["contact_id"] ; 

			$contactQuery 		=	"SELECT contactperson.contactsTelephone, contactperson.contactsMobile, contactperson.contactsEmail, contactperson.contactsProfile, 
									family, pets, sport, team, holiday, 
									date_format(birthday, '%d/%m/%Y') birthday, 
									contactperson.contactsName, 
									date_format(birthday, '%Y-%m-%d') birthday_date,
									contacts.contactsTelephone company_phone
									FROM contactperson
									LEFT JOIN contacts ON contacts.contactsID = contactperson.contactsID
									WHERE contactperson.contactPersonID = " .  $contact_id;

			$contactResult		=	$db->query($contactQuery);
			$contactList = '';
			$crmData = [];
	
			while ($contactRow = $contactResult->fetch()) {
				
				$details 	= 	$contactRow['contactsTelephone'] . ";";
				$details 	.= 	$contactRow['contactsEmail'] . ";";
				$details 	.= 	$contactRow['contactsProfile'] . ";";
				$details 	.= 	$contact_id . ";";
				$details 	.= 	$contactRow['contactsName'] . ";";
				$details 	.= 	$contactRow['family'] . ";";
				$details 	.= 	$contactRow['pets'] . ";";
				$details 	.= 	$contactRow['sport'] . ";";
				$details 	.= 	$contactRow['team'] . ";";
				$details 	.= 	$contactRow['holiday'] . ";";
				$details 	.= 	$contactRow['birthday'] . ";";
				$details 	.= 	$contactRow['contactsMobile'] . ";";
				$details 	.= 	$contactRow['birthday_date'] . ";";
				$details 	.= 	$contactRow['company_phone'] . ";";
				array_push($crmData, $details);

			}
			
			$JSON_array =  json_encode($crmData);
			print_r($JSON_array);

		break;

		/* end of crmcustomer */	

		case "create_crm_activity":

			echo $_POST;

			$crmID				=		$_POST["customer_choice"];
			$crmBy				=		$_POST["crmBy"];
			$crmType			=		$_POST["crmType"];
			$string				=		new safeEscapeString($_POST["crmNotes"]);
			$crmNotes			=		$string -> returnString();
			$orderID			=		$_POST["crmorderID"];
			$quoteID			=		$_POST["crmquoteID"];
			$string				=		new safeEscapeString($_POST["new_crm_task"]);
			$task				= 		$string -> returnString();
			$selectedName		=		$_POST["contactNames"];	
	
			$followUpDate		=		flipdate($_POST["followUpDate"],'toDB');   ;
			$followUpTime		=		$_POST["followUpTime"];
			$followUpAction		=		$_POST["followUpAction"];
			$followUpAssigned	=		json_encode($_POST["followUpAssigned"]);
			$followUpStage		=		$_POST["followUpStage"];
			
			if ($crmType == "Note") { $followUpAssigned = $_POST["followUpAssignedNote"]; }
			if (!$crmType && $followUpAction) { $crmType = $followUpAction;}
			if (!$followUpAction && $crmType) { $followUpAction = $crmType;}
	
			if (!$crmType && !$followUpAction) {
				$crmType = 'Call';
				$followUpAction = 'Call';
			}
	
			$followUpPriority	=		$_POST["followUpPriority"];
			$string				=		new safeEscapeString($_POST["followUpNotes"]);
			$followUpNotes		=		$string -> returnString();
	
			if ($crmType == "Note") { $followUpDate = date('Y-m-d'); $followUpTime = date('H:i');}
	
			$query			=		"INSERT INTO activity (contactsID,contactPersonID,activityAdded,activityBy, activityType,activityNotes, followUpDate, followUpType, followUpAssignees,followUpNotes,followUpPriority,orderID,quoteID,followUpStage) 
			VALUES ('$crmID','$selectedName',NOW(), '$crmBy','$crmType','$crmNotes','$followUpDate $followUpTime','$followUpAction','$followUpAssigned','$followUpNotes','$followUpPriority','$orderID','$quoteID', '$followUpStage')";
	
			$result			=		$db -> query($query);
			$activityID		=		$result -> insertID();
	
			$files		=		array();
			$fdata		=		$_FILES['crmFileUpload'];
			if(is_array($fdata['name'])){
				for($i=0; $i < count($fdata['name']); ++$i){
						$files[]=array(	'name'  	=>	$fdata['name'][$i],	'type' 	=> 	$fdata['type'][$i],	'tmp_name'	=>	$fdata['tmp_name'][$i],	'error' 	=> 	$fdata['error'][$i], 'size' 	=> 	$fdata['size'][$i]);
				}
			} else {
				$files[]=$fdata;
			}
			$filenames = "";
			foreach ($files as $file) { 
	
				$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 4);
				$customerFile= str_replace(" ", "-", $file['name']);
	
				$getridof = array(" ", "'", '"', "!", "[", "]", "(", ")", "@", "/", "\\", "*", "%", "£", "$");
				$customerFile	= str_replace($getridof, "",$customerFile );
				$filename	= $activityID.'_'.$randomString.'_'.$customerFile; 
		
				$filepath = "../crmfiles";
				
				$File = new FileUpload($file['tmp_name'], $filename, $filepath );
				if ($File -> uploadFile()) { 
					$filenames .= $filename.'|';
				} 
			}
			if($filenames != ""){
				$query		=		"UPDATE activity  SET activityFile = '$filenames' WHERE activityID 	= 	'$activityID'";
				if($result		=		$db -> query($query)){
				
				}
			}
	
			/* now save the initial task */
	
			$insertQuery = "INSERT INTO activity_tasks (activityID, task, allocatedTo, createdDate, createdBy)
							VALUES (
								" . $activityID . ",
								'" . $task . "',
								" . $_SESSION['memberSPLSWID'] . ",
								NOW(),
								" . $_SESSION['memberSPLSWID'] . "
							)";
	
			$insertResult = $db->query($insertQuery);

			break;

		case "update_crm_activity_old":

			$activity_id 			= 		$_POST['activity_id'];
			$data_array 			= 		$_POST['data_array'];

			echo $activity_id;

			print_r("ARRAY = " + $data_array);

			$contactsID				=		$data_array["contact_id"];

			$tempSave = "UPDATE activity SET contactPersonID = " . $contactsID . " WHERE activityID = " . $activity_id;
			$result				=		$db -> query($tempSave);

			echo $tempSave;

			exit;
			
			$activityID				=		$_POST["activity_id"];	
			$crmID					=		$_POST["customer_choice"];
			$contactsID				=		$_POST["contact_id"];
			$crmBy					=		$_POST["crmBy"];
			$crmType				=		$_POST["crmType"];
			$orderID				=		$_POST["crmorderID"];
			$quoteID				=		$_POST["crmquoteID"];	
			$contactPersonID		=		$_POST["contactNames"];	
			$string					=		new safeEscapeString($_POST["crmNotes"]);	
			$crmNotes				=		$string -> returnString();
			$string					=		new safeEscapeString($_POST["new_crm_task"]);
			$newTask				=		$string -> returnString();
			$followUpAssignedRaw	= 		$_POST["followUpAssigned"];
			$task_count				= 		$_POST["task_count"];

			if (!$followUpAssignedRaw || $followUpAssignedRaw == "null") { $followUpAssignedVal = $_SESSION["memberSPLSWID"]; } else { $followUpAssignedVal = $followUpAssignedRaw; }

			$followUpDate			=		flipdate($_POST["followUpDate"],'toDB');   
			$followUpDateOriginal 	= 		flipdate($_POST['followUpDateOriginal'],'toDB');  
			$followUpTime			=		$_POST["followUpTime"];
			$followUpTimeOriginal	=		$_POST["followUpTimeOriginal"];	
			$followUpAction			=		$_POST["followUpAction"];
			$followUpAssigned		=		json_encode($followUpAssignedVal);
			$followUpStage			=		$_POST["followUpStage"];
			$stageOriginal			=		$_POST["stageOriginal"];	
			$contactProfile			= 		$_POST["contactProfile"];

			if ($crmType == "Note") { $followUpAssigned = $_POST["followUpAssignedNote"]; }

			if (!$crmType && $followUpAction) { $crmType = $followUpAction;}
			if (!$followUpAction && $crmType) { $followUpAction = $crmType;}

			if (!$crmType && !$followUpAction) {
				$crmType = 'Call';
				$followUpAction = 'Call';
			}

			$followUpPriority		=		$_POST["followUpPriority"];
			$priorityOriginal		=		$_POST["priorityOriginal"];

			$string					=		new safeEscapeString($_POST["followUpNotes"]);
			$followUpNotes			=		$string -> returnString();

			$query					= 		"UPDATE activity SET 
												activityType = '$crmType',
												followUpDate = '$followUpDate $followUpTime',
												followUpType = '$followUpAction',
												followUpAssignees = '$followUpAssigned',
												followUpNotes = '$followUpNotes',
												followUpPriority = '$followUpPriority',
												followUpStage = '$followUpStage',
												orderID = '$orderID',
												quoteID = '$quoteID',
												contactPersonID = '$contactPersonID'
											WHERE
												activityID = '$activityID'
			";

			$result				=		$db -> query($query);

			$files					=		array();
			$fdata					=		$_FILES['crmFileUpload'];
			if(is_array($fdata['name'])){
				for($i=0; $i < count($fdata['name']); ++$i){
						$files[]=array(	'name'  	=>	$fdata['name'][$i],	'type' 	=> 	$fdata['type'][$i],	'tmp_name'	=>	$fdata['tmp_name'][$i],	'error' 	=> 	$fdata['error'][$i], 'size' 	=> 	$fdata['size'][$i]);
				}
			} else {
				$files[]=$fdata;
			}
			$filenames = "";
			foreach ($files as $file) { 

				$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 4);
				$customerFile= str_replace(" ", "-", $file['name']);

				$getridof = array(" ", "'", '"', "!", "[", "]", "(", ")", "@", "/", "\\", "*", "%", "£", "$");
				$customerFile	= str_replace($getridof, "",$customerFile );
				$filename	= $activityID.'_'.$randomString.'_'.$customerFile; 

				echo $filename;
				$filepath = "../crmfiles";
				
				$File = new FileUpload($file['tmp_name'], $filename, $filepath );
				if ($File -> uploadFile()) { 
					$filenames .= $filename.'|';
				} 

				/* append to any existing files in the table */

				$query 				= 		"SELECT activityFile FROM activity where activityID = '$activityID'";
				$result 			=		$db->query($query);
				$filesArray			= 		$result->fetch();
				$existingFiles 		=		$filesArray['activityFile'];

				$filenames .= $existingFiles;

			}
			if($filenames != ""){
				$query				=		"UPDATE activity SET activityFile = '$filenames' WHERE activityID 	= 	'$activityID'";
				if($result			=		$db -> query($query)){
				
				}
			}

			/* then add any new tasks */

			if ($newTask) {
				$insertQuery = 'INSERT INTO activity_tasks (activityID, task, allocatedTo, createdDate, createdBy)
				VALUES (
					' . $activityID . ',
					"' . $newTask . '",
					' . $_SESSION['memberSPLSWID'] . ',
					NOW(),
					' . $_SESSION['memberSPLSWID'] . '
				)';

				$insertResult = $db->query($insertQuery);

			}

			/* next, update the contact profile */

			$profileQuery = 'UPDATE contactperson SET contactsProfile =  "' . $contactProfile . '" WHERE contactPersonID = ' . $contactPersonID;
			$profileResult = $db->query($profileQuery);

			/* finally, add to the audit trail if an auditable value has been changed */

			if ($followUpDateOriginal != $followUpDate || $followUpTimeOriginal != $followUpTime) { 
				
				$oldValue = $followUpDateOriginal . " " . $followUpTimeOriginal;
				$newValue = $followUpDate . " " . $followUpTime;
				$auditType = '1';
				
				$result = addToAuditTrail($activityID, $auditType, $oldValue, $newValue, $db);
			
			}

			if ($priorityOriginal != $followUpPriority) { 
				
				$oldValue = $priorityOriginal;
				$newValue = $followUpPriority;
				$auditType = '5';
				
				$result = addToAuditTrail($activityID, $auditType, $oldValue, $newValue, $db);
			
			}

			if ($stageOriginal != $followUpStage) { 
				
				$oldValue = $stageOriginal;
				$newValue = $followUpStage;
				$auditType = '6';
				
				$result = addToAuditTrail($activityID, $auditType, $oldValue, $newValue, $db);
			
			}	

			/* finally send notification emails */

			$objMail = new crm_mail_ctl();
			// $success = $objMail->sendMailOnAllocateActivity($activityID, $db);

			return;

		case "save_crm_activity":

			/* get the data from the array */

			$form_data = $_POST['form_data'];
			$activity_id = $form_data['activity_id'];
			$contact_id = $form_data['contact_id'];
			$contact_person_id = $form_data['contact_person_id'];
			$qo_num = $form_data['qo_num'];
			$qo_type = $form_data['qo_type'];
			$next_action = $form_data['next_action'];
			$follow_up_date = $form_data['follow_up_date'];
			$crm_assignees = $form_data['crm_assignees'];
			$priority = $form_data['priority'];
			$completed = $form_data['completed'];
			$activity_completed = "null";
			$string	= new safeEscapeString($form_data["new_action_text"]);
			$newTask = $string -> returnString();

			if ($completed == 1) {$activity_completed = "NOW()"; } else {$activity_completed = "null";}

			$user_id = $_SESSION['memberSPLSWID'];
			
			if ($qo_type == "Order") { $qo = "orderID = '$qo_num'";}
			if ($qo_type == "Quote") { $qo = "quoteID = '$qo_num'";}
			if (!$qo_type) {$qo = "orderID = '0', quoteID = '0'"; }

			/* add or edit? */

			if ($activity_id) {

				$saveQuery = "UPDATE activity SET 
								" . $qo . ",
								contactPersonID = '$contact_person_id',
								nextActionID = '$next_action',
								followUpDate = '$follow_up_date',
								followUpAssignees = '$crm_assignees',
								followUpPriority = '$priority',
								activityCompleted = $activity_completed,
								updateUser = '$user_id',
								updateDate = NOW()
								WHERE activityID = '$activity_id'
				";
				$saveResult = $db->query($saveQuery);

			} else {

				$quote_num = 0;
				$order_num = 0;

				if ($qo_type == "Order") { $order_num = $qo_num; }
				if ($qo_type == "Quote") { $quote_num = $qo_num;}

				/* stand alone or recurring task? */

				$stand_alone = true;

				if (isset($form_data['recur_daily_array'])) { $stand_alone = false;}
				if (isset($form_data['recur_weekly_array'])) { $stand_alone = false;}
				if (isset($form_data['recur_monthly_array'])) { $stand_alone = false;}

				if ($stand_alone) { 

					$saveQuery = "INSERT INTO activity (
									contactsID, contactPersonID, activityAdded, activityBy, followUpDate, followUpPriority, nextActionID, followUpAssignees, orderID, quoteID
									)
									VALUES (
										'$contact_id',
										'$contact_person_id',
										NOW(),
										'$user_id',
										'$follow_up_date',
										'$priority',
										'$next_action',
										'$crm_assignees',
										'$order_num',
										'$quote_num'
									)";

					$saveResult = $db->query($saveQuery);
					if (!$activity_id) { $activity_id = $saveResult -> insertID();}

				}

			}

			/* now add any new tasks */

			if ($activity_id) { 

				if ($newTask) {

					$insertQuery = 'INSERT INTO activity_tasks (activityID, task, allocatedTo, createdDate, createdBy)
					VALUES (
						' . $activity_id . ',
						"' . $newTask . '",
						' . $_SESSION['memberSPLSWID'] . ',
						NOW(),
						' . $_SESSION['memberSPLSWID'] . '
					)';

					$insertResult = $db->query($insertQuery);	

				}

			}

			/* recurring */

			$additional_data = [
				'db' => $db,
				'activity_id' => $activity_id,
				'new_task' => $newTask
			];

			if (isset($form_data['recur_daily_array'])) { create_recurring_tasks($form_data['recur_activity_data'], $form_data['recur_daily_array'], 'day', $additional_data); }
			if (isset($form_data['recur_weekly_array'])) { create_recurring_tasks($form_data['recur_activity_data'], $form_data['recur_weekly_array'], 'week', $additional_data); }
			if (isset($form_data['recur_monthly_array'])) { create_recurring_tasks($form_data['recur_activity_data'], $form_data['recur_monthly_array'], 'month', $additional_data); }

			return;

		case "update_crm_activity":

			$updateQuery = "UPDATE activity SET 
							" . $qo . ",
							contactPersonID = '$contact_person_id',
							nextActionID = '$next_action',
							followUpDate = '$follow_up_date',
							followUpAssignees = '$crm_assignees',
							followUpPriority = '$priority',
							activityCompleted = $activity_completed
							WHERE activityID = '$activity_id'
			";

			$updateResult = $db->query($updateQuery);

			// print_r("ARRAY = " + $data_array);

			// $contactsID				=		$data_array["contact_id"];

			// $tempSave = "UPDATE activity SET contactPersonID = " . $contactsID . " WHERE activityID = " . $activity_id;
			// $result				=		$db -> query($tempSave);

			// echo $tempSave;

			// exit;
			
			// $activityID				=		$_POST["activity_id"];	
			// $crmID					=		$_POST["customer_choice"];
			// $contactsID				=		$_POST["contact_id"];
			// $crmBy					=		$_POST["crmBy"];
			// $crmType				=		$_POST["crmType"];
			// $orderID				=		$_POST["crmorderID"];
			// $quoteID				=		$_POST["crmquoteID"];	
			// $contactPersonID		=		$_POST["contactNames"];	
			// $string					=		new safeEscapeString($_POST["crmNotes"]);	
			// $crmNotes				=		$string -> returnString();
			// $string					=		new safeEscapeString($_POST["new_crm_task"]);
			// $newTask				=		$string -> returnString();
			// $followUpAssignedRaw	= 		$_POST["followUpAssigned"];
			// $task_count				= 		$_POST["task_count"];

			// if (!$followUpAssignedRaw || $followUpAssignedRaw == "null") { $followUpAssignedVal = $_SESSION["memberSPLSWID"]; } else { $followUpAssignedVal = $followUpAssignedRaw; }

			// $followUpDate			=		flipdate($_POST["followUpDate"],'toDB');   
			// $followUpDateOriginal 	= 		flipdate($_POST['followUpDateOriginal'],'toDB');  
			// $followUpTime			=		$_POST["followUpTime"];
			// $followUpTimeOriginal	=		$_POST["followUpTimeOriginal"];	
			// $followUpAction			=		$_POST["followUpAction"];
			// $followUpAssigned		=		json_encode($followUpAssignedVal);
			// $followUpStage			=		$_POST["followUpStage"];
			// $stageOriginal			=		$_POST["stageOriginal"];	
			// $contactProfile			= 		$_POST["contactProfile"];

			// if ($crmType == "Note") { $followUpAssigned = $_POST["followUpAssignedNote"]; }

			// if (!$crmType && $followUpAction) { $crmType = $followUpAction;}
			// if (!$followUpAction && $crmType) { $followUpAction = $crmType;}

			// if (!$crmType && !$followUpAction) {
			// 	$crmType = 'Call';
			// 	$followUpAction = 'Call';
			// }

			// $followUpPriority		=		$_POST["followUpPriority"];
			// $priorityOriginal		=		$_POST["priorityOriginal"];

			// $string					=		new safeEscapeString($_POST["followUpNotes"]);
			// $followUpNotes			=		$string -> returnString();

			// $query					= 		"UPDATE activity SET 
			// 									activityType = '$crmType',
			// 									followUpDate = '$followUpDate $followUpTime',
			// 									followUpType = '$followUpAction',
			// 									followUpAssignees = '$followUpAssigned',
			// 									followUpNotes = '$followUpNotes',
			// 									followUpPriority = '$followUpPriority',
			// 									followUpStage = '$followUpStage',
			// 									orderID = '$orderID',
			// 									quoteID = '$quoteID',
			// 									contactPersonID = '$contactPersonID'
			// 								WHERE
			// 									activityID = '$activityID'
			// ";

			// $result				=		$db -> query($query);





			// /* then add any new tasks */

			// if ($newTask) {
			// 	$insertQuery = 'INSERT INTO activity_tasks (activityID, task, allocatedTo, createdDate, createdBy)
			// 	VALUES (
			// 		' . $activityID . ',
			// 		"' . $newTask . '",
			// 		' . $_SESSION['memberSPLSWID'] . ',
			// 		NOW(),
			// 		' . $_SESSION['memberSPLSWID'] . '
			// 	)';

			// 	$insertResult = $db->query($insertQuery);

			// }

			// /* next, update the contact profile */

			// $profileQuery = 'UPDATE contactperson SET contactsProfile =  "' . $contactProfile . '" WHERE contactPersonID = ' . $contactPersonID;
			// $profileResult = $db->query($profileQuery);

			// /* finally, add to the audit trail if an auditable value has been changed */

			// if ($followUpDateOriginal != $followUpDate || $followUpTimeOriginal != $followUpTime) { 
				
			// 	$oldValue = $followUpDateOriginal . " " . $followUpTimeOriginal;
			// 	$newValue = $followUpDate . " " . $followUpTime;
			// 	$auditType = '1';
				
			// 	$result = addToAuditTrail($activityID, $auditType, $oldValue, $newValue, $db);
			
			// }

			// if ($priorityOriginal != $followUpPriority) { 
				
			// 	$oldValue = $priorityOriginal;
			// 	$newValue = $followUpPriority;
			// 	$auditType = '5';
				
			// 	$result = addToAuditTrail($activityID, $auditType, $oldValue, $newValue, $db);
			
			// }

			// if ($stageOriginal != $followUpStage) { 
				
			// 	$oldValue = $stageOriginal;
			// 	$newValue = $followUpStage;
			// 	$auditType = '6';
				
			// 	$result = addToAuditTrail($activityID, $auditType, $oldValue, $newValue, $db);
			
			// }	

			// /* finally send notification emails */

			// $objMail = new crm_mail_ctl();
			// $success = $objMail->sendMailOnAllocateActivity($activityID, $db);

			return;

		case "get_custom_trays":

			$user_id = $_SESSION['memberSPLSWID'];
			$like = '"%' . $user_id  . '%"';
			$members_query = "(owner = " . $user_id . " OR members LIKE " . $like . ") AND DELETED = 0";	

			$trayQuery 	= "SELECT trayID, trayName, `system` is_system FROM crm_trays WHERE " . $members_query;
			$trayResult = $db->query($trayQuery);
			$trayArray = [];

			while ($trayRow = $trayResult->fetch()) {

				$tray_data = $trayRow['trayID'] . ";" . $trayRow['trayName'] . ";" . $trayRow['is_system'] ;
				array_push($trayArray,$tray_data);
			}

			$trayJSON = json_encode($trayArray);
			print_r($trayJSON);
			break;

		case "update_profile":

			$profile_data = $_POST['profile_array'];

			$contact_person_id = $profile_data['contact_person_id'];
			$family = $profile_data['family'];
			$pets= $profile_data['pets'];
			$sport = $profile_data['sport'];
			$holiday = $profile_data['holiday'];
			$birthdayRaw = $profile_data['birthday'];
			$profile = $profile_data['profile'];

			$updateQuery = 'UPDATE contactperson SET 
							family = "' . $family . '",
							pets = "' . $pets . '",
							sport = "' . $sport . '",
							holiday = "' . $holiday . '",
							birthday = "' . $birthdayRaw . '",
							contactsProfile = "' . $profile . '"
							WHERE contactPersonID = ' . $contact_person_id
			;
			$updateResult = $db->query($updateQuery);

			return;

		case "add_new_tray":

			$new_tray_name = $_POST['tray_name'];
			$new_tray_back = $_POST['tray_back_colour'];
			$tray_members = $_POST['tray_members'];
			$user_id = $_SESSION['memberSPLSWID'];

			if (!$new_tray_name) { return false; }

			$tray_slug = strtolower(str_replace(" ", "_", $new_tray_name));

			$newTrayQuery = 'INSERT INTO crm_trays (traySlug, trayName, `system`, owner, trayBackColour, trayForeColour, members) VALUES (
							"' . $tray_slug . '",
							"' . $new_tray_name . '",
							"0",
							"' . $user_id . '",
							"' . $new_tray_back . '",
							"#FFFFFF",
							"' . $tray_members . '"

			)';
			$newTrayResult = $db->query($newTrayQuery);
			return;

		case "delete_crm_tray":

			/* first check that the tray is truly empty */

			$tray_id = $_POST['tray_id'];

			$trayQuery = "SELECT count(activityID) no_of_trays FROM activity WHERE nextActionID = " . $tray_id;
			$trayResult = $db->query($trayQuery);
			$trayArray = $trayResult->fetch();
			$trayCount = $trayArray['no_of_trays'];

			if ($trayCount == 0 ) {

				/* delete the tray */
				$trayQuery = "UPDATE crm_trays SET deleted = 1 WHERE trayID = " . $tray_id;
				$trayResult = $db->query($trayQuery);
				$result = "success";
			} else {
				$result = "fail";
			}
			echo $result;
			return;

			case "update_trays":

				$tray_data = $_POST['tray_data'];

				foreach ($tray_data as $tray) {

					$trayQuery = 'UPDATE crm_trays SET 
						trayName = "' . $tray["tray_name"] . '", 
						members = "' . $tray["tray_members"] . '" ,
						trayBackColour = "' . $tray["tray_back_colour"] . '"
						WHERE trayID = ' . $tray["tray_id"];

						$trayResult = $db->query($trayQuery);
				}
				echo $trayQuery; 
				return;

		case "set_cust_comp":

			/* get customer from url into a session to spot when it changes */

			$customer_id = $_POST['customer_id'];

			if (!isset($_SESSION['customer_id']) || (isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != $customer_id ) ) { 
				$_SESSION['customer_id'] = $customer_id; 
				$_SESSION['show_completed_for_customer'] = "true";
			} else {
				if(isset($_SESSION['show_completed_for_customer'])) { 
					unset($_SESSION['show_completed_for_customer']); 
				} else {
					$_SESSION['show_completed_for_customer'] = "true";
				}
			}

			return;

		case "get_customer_slow":

			$customer_phrase = $_POST['customer_phrase'];

			$customerQuery		=		"SELECT contacts.contactsID, contacts.contactsRef,contacts.contactsCompany, address.addressTown, address.addressAddress1 
										FROM contacts 
										INNER JOIN address ON contacts.contactsID = address.contactsID 
										WHERE contacts.customerType = 'customer' 
										AND (address.addressType LIKE '%Delivery%' OR address.addressTown IS NULL) 
										AND contacts.contactsRef != ''
										AND contacts.contactsCompany like '%" . $customer_phrase . "%';
										GROUP BY contacts.contactsID 
										ORDER BY contacts.contactsCompany ASC, addressTown ASC";

			$customerQuery = "";
			$customerResult		=		$db -> query($customerQuery);
			$data = array();
			while($addressrow 	=		$customerResult -> fetch()){
				$data[] = array(
					'key' => $addressrow['contactsID'], 
					'value' =>  $addressrow['contactsRef'].' - '.$addressrow['contactsCompany'].', '.$addressrow["addressTown"]
				);
			
			}

			echo $data;
			
			return;

		case "get_customer_from_id":

			$customer_id 		= 	$_POST['customer_id'];
			$customer_query 	= 	"SELECT contactsCompany company FROM contacts WHERE contactsID = " . $customer_id;
			$customer_result 	= 	$db->query($customer_query);
			$customer_array		=	$customer_result->fetch();
			$customer_name		= 	$customer_array['company'];

			echo $customer_name;

			return;

		case "get_customer_from_qo":

			$qo_flag 			= 	$_POST['qo_flag'];
			$qo_id 				= 	$_POST['qo_id'];
			$customer_query		= 	"";

			if (!$qo_id) { return false;}

			if ($qo_flag == "quote") { $customer_query = "SELECT contacts.contactsCompany company, contacts.contactsID FROM contacts LEFT JOIN quotes ON quotes.contactsID = contacts.contactsID WHERE quotes.quoteID = " . $qo_id;}
			if ($qo_flag == "order") { $customer_query = "SELECT contacts.contactsCompany company, contacts.contactsID FROM contacts LEFT JOIN orders ON orders.contactsID = contacts.contactsID WHERE orders.orderID = " . $qo_id;}

			if (!$customer_query) { return false;}

			$customer_result 	= 	$db->query($customer_query);
			$customer_array		=	$customer_result->fetch();

			$customer_JSON		= 	json_encode($customer_array);

			print_r($customer_JSON);

			return;

		case "choose_week":
			
			unset($_SESSION['cal_start_date']);
			unset($_SESSION['defineWeek']);
			$week_chosen = $_POST['week_chosen'];

			if (strlen($week_chosen) == 8) { $dateObj = \DateTime::createFromFormat("d/m/y", $week_chosen); }
			if (strlen($week_chosen) == 10) { $dateObj = \DateTime::createFromFormat("d/m/Y", $week_chosen); }
			$chosen_start = $dateObj;
			$_SESSION['defineWeek'] = $chosen_start;

			echo $_SESSION['defineWeek'] ->format("d/m/Y");

			return;

		case "set_activity_complete":

			$activity_id = $_POST['activity_id'];
			$is_complete = $_POST['is_complete'];
			$new_task = $_POST['new_task'];

			$mdl = connect_mdl();
			$complete = $mdl->set_activity_complete_db($activity_id, $is_complete, $new_task);

			return;
	

		case "private_tray_toggle":

			$tray_id = $_POST['tray_id'];

			$mdl = connect_mdl();
			$togglePrivate = $mdl->private_tray_toggle_db($tray_id);

			echo $togglePrivate;

			return;

		break;

		case "change_display_range":

			$_SESSION['display_range'] = $_POST['display_range'];

			echo $_SESSION['display_range'];

			return;

		case "toggle_completed":

			if ($_SESSION['toggle_completed'] == "1") { $_SESSION['toggle_completed'] = "0" ; } else {$_SESSION['toggle_completed'] = "1"; }

			return;

			case "get_full_assignee_inits":
				
				$mdl = connect_mdl();
				$assigneeInits = $mdl->get_full_assignee_inits_db();

				$parsed_inits = json_encode($assigneeInits);
				print_r( $parsed_inits );

			return;

			case "get_member_inits":

				$activity_id = $_POST['activity_id'];
				$assignees_list = $_POST['assignees'];

				$mdl = connect_mdl();
				$team_data = $mdl->get_member_inits_db($activity_id, $assignees_list);

				$parsed_inits = json_encode($team_data);
				print_r( $parsed_inits );

			return;

			case "get_non_team_members":

				$mdl = connect_mdl();
				$get_non_team_members_db = $mdl->get_non_team_members_db();

				$parsed_non_team = json_encode($get_non_team_members_db);
				print_r( $parsed_non_team );

			return ;

			case "get_self_activity":

				if (isset($_SESSION['show_lates'])) { unset($_SESSION['show_lates']) ; } 
				if (isset($_SESSION['show_history'])) { unset($_SESSION['show_history']) ; } 
				if (isset($_SESSION['show_self_tasks'])) { unset($_SESSION['show_self_tasks']) ; } else { $_SESSION['show_self_tasks'] = true; }

				$mdl = connect_mdl();
				$get_activity_history_db = $mdl->get_activity_history_db();

			return;				
			
			case "get_self_inits":

				$mdl = connect_mdl();
				$get_self_inits = $mdl->get_self_inits_db();

				$parsed_self_inits = json_encode($get_self_inits);
				print_r( $parsed_self_inits );

			return;		

			case "set_auto_view":

				if (isset($_SESSION['auto_view_task'])) { unset($_SESSION['auto_view_task']); }
				$activity_id = $_POST['activity_id'];
				$_SESSION['auto_view_task'] = $activity_id;

			return;				


			case "check_permissions":

				$activity_id = $_POST['activity_id'];

				$mdl = connect_mdl();
				$has_permissions = $mdl->check_permissions_db($activity_id);

				print_r( $has_permissions );

				return;		

			case "show_lates":

				if (isset($_SESSION['show_self_tasks'])) { unset($_SESSION['show_self_tasks']) ; }
				if (isset($_SESSION['show_history'])) { unset($_SESSION['show_history']) ; }
				if (isset($_SESSION['show_lates'])) { unset($_SESSION['show_lates']) ; } else { $_SESSION['show_lates'] = true; }

			return;

			case "get_activity_history":

				if (isset($_SESSION['show_self_tasks'])) { unset($_SESSION['show_self_tasks']) ; }
				if (isset($_SESSION['show_lates'])) { unset($_SESSION['show_lates']) ; } 
				if (isset($_SESSION['show_history'])) { unset($_SESSION['show_history']) ; } else { $_SESSION['show_history'] = true; }

				$mdl = connect_mdl();
				$get_activity_history_db = $mdl->get_activity_history_db();

			return;
				
			case "get_self_activity":

				if (isset($_SESSION['show_lates'])) { unset($_SESSION['show_lates']) ; } 
				if (isset($_SESSION['show_history'])) { unset($_SESSION['show_history']) ; } 
				if (isset($_SESSION['show_self_tasks'])) { unset($_SESSION['show_self_tasks']) ; } else { $_SESSION['show_self_tasks'] = true; }

				$mdl = connect_mdl();
				$get_activity_history_db = $mdl->get_activity_history_db();

			return;				

		}

	function addToAuditTrail($activityID, $auditType, $oldValue, $newValue, $db) {

		$auditQuery 	= 	"INSERT INTO crm_audit_trail (activityID, auditTypeID, oldValue, NewValue, dateCreated, userCreating) VALUES ( 
							" . $activityID . ",
							'" . $auditType . "',
							'" . $oldValue . "',
							'" . $newValue . "',
							NOW(),
							" . $_SESSION['memberSPLSWID'] . "
							)";

		$auditResult 	= 	$db->query($auditQuery);

		return;

	}

	function create_recurring_tasks($activity_data_array, $recur_data_array, $recur_type, $additional_data ) {

			/* get the activity data for all recurring types */
			/* get the activity data for the dates */
			/* work out the difference between the two - total number of days marking the size of the loop */
			/* create the loop and create an activity for each iteration of the loop, skip over weekends */

			$activity_by = $_SESSION['memberSPLSWID'];

			$db = $additional_data['db'];
			$activity_id = $additional_data['activity_id'];
			$new_task = $additional_data['new_task'];

			echo $new_task;

			$order_id = 0;
			$quote_id = 0;

			$contacts_id = $activity_data_array[0]['contacts_id'];
			$contact_person_id = $activity_data_array[0]['contact_person_id'];
			$qo_value = $activity_data_array[0]['qo_value'];
			if ($qo_value == 'Order') {
				$order_id = $activity_data_array[0]['qo_id'];
			}
			if ($qo_value == 'Quote') {
				$quote_id = $activity_data_array[0]['qo_id'];
			}
			$follow_up_assignees = $activity_data_array[0]['follow_up_assignees'];
			$follow_up_priority = $activity_data_array[0]['follow_up_priority'];
			$file_list = $activity_data_array[0]['file_list'];
			$next_action_id = $activity_data_array[0]['next_action_id'];

			if ($recur_type == "day") { 

				$start_date = ($recur_data_array[0]['start_date']);
				$end_date = ($recur_data_array[0]['end_date']);

				if (strlen($start_date) == 8) { $dateObj = \DateTime::createFromFormat("d/m/y", $start_date); }
				if (strlen($start_date) == 10) { $dateObj = \DateTime::createFromFormat("d/m/Y", $start_date); }
				$follow_up_date = $dateObj;

				if (strlen($end_date) == 8) { $end_dateObj = \DateTime::createFromFormat("d/m/y", $end_date); }
				if (strlen($end_date) == 10) { $end_dateObj = \DateTime::createFromFormat("d/m/Y", $end_date); }
				$end_date_obj = $end_dateObj;

				$day_count = $end_date_obj->diff($follow_up_date)->format("%a");

				for ($i = 0; $i <= $day_count; $i++) {

					if ($dateObj > $end_date_obj) { continue; } else { echo $follow_up_date->format('Y-m-d') ; }

					if($follow_up_date->format('N') == 6) {
						$follow_up_date 		= 	$dateObj->modify('+2 day');
					}

					if($follow_up_date->format('N') == 7) {
						$follow_up_date 		= 	$dateObj->modify('+1 day');
					}

					$follow_up_date_full 	= 	$follow_up_date->format('Y-m-d 00:00:00');

					/* is the first recurring task also the first task - created at the same time as the initial task? */
					
					if (!$activity_id) {

						$createRecurringQuery	=	"INSERT INTO activity 
													(contactsID, contactPersonID, orderID, quoteID, activityAdded, activityBy, followUpAssignees, followUpDate, followUpPriority,
													activityFile, seriesType, nextActionID)
													VALUES
													('$contacts_id', '$contact_person_id', '$order_id', '$quote_id', NOW(), '$activity_by', '$follow_up_assignees', '$follow_up_date_full', 
													'$follow_up_priority', '$file_list', '$recur_type', '$next_action_id'
													)";

						$createRecurringResult	=	$db -> query($createRecurringQuery);
						$newActivityID			=	mysql_insert_id();
						$activity_id = $newActivityID;

						$recurringTaskQuery		=	"INSERT INTO activity_tasks
													(activityID, task, allocatedTo, createdDate, createdBy)
													VALUES
													('" . $activity_id . "',
													'" . $new_task . "',
													'" . $activity_by . "',
													NOW(),
													'" . $activity_by . "'
													)";
						$recurringtaskResult	=	$db->query($recurringTaskQuery);												

					} else { 

						$createRecurringQuery	=	"INSERT INTO activity 
												(contactsID, contactPersonID, orderID, quoteID, activityAdded, activityBy, followUpAssignees, followUpDate, followUpPriority,
												activitySeries, activityFile, seriesType, nextActionID)
												VALUES
												('$contacts_id', '$contact_person_id', '$order_id', '$quote_id', NOW(), '$activity_by', '$follow_up_assignees', '$follow_up_date_full', 
												'$follow_up_priority', '$activity_id', '$file_list', '$recur_type', '$next_action_id'
												)";
												$createRecurringResult	=	$db -> query($createRecurringQuery);
												$newActivityID			=	mysql_insert_id();
						
						$taskQuery				=	"SELECT task, allocatedTo, createdBy FROM activity_tasks WHERE activityID = " . $activity_id;
						$taskResult				=	$db->query($taskQuery);

						while ($taskRow = $taskResult->fetch()) {

							$recurringTaskQuery		=	"INSERT INTO activity_tasks
														(activityID, task, allocatedTo, createdDate, createdBy)
														VALUES
														('" . $newActivityID . "',
														'" . $taskRow['task'] . "',
														'" . $taskRow['allocatedTo'] . "',
														NOW(),
														'" . $taskRow['createdBy'] . "'
														)";
							$recurringtaskResult	=	$db->query($recurringTaskQuery);

						}
					}

					$follow_up_date 		= 	$dateObj->modify('+1 day');
					
				}

			} 

			if ($recur_type == "week") { 

				$start_date = $recur_data_array[0]['start'];
				$iterations  = $recur_data_array[0]['duration'];
				$days_list = [];

				if (strlen($start_date) == 8) { $dateObj = \DateTime::createFromFormat("d/m/y", $start_date); }
				if (strlen($start_date) == 10) { $dateObj = \DateTime::createFromFormat("d/m/Y", $start_date); }
				$follow_up_date = $dateObj;

				if ($recur_data_array[0]['mon'] == "true") { array_push($days_list, 1); }
				if ($recur_data_array[0]['tue'] == "true") { array_push($days_list, 2); }
				if ($recur_data_array[0]['wed'] == "true") { array_push($days_list, 3); }
				if ($recur_data_array[0]['thu'] == "true") { array_push($days_list, 4); }
				if ($recur_data_array[0]['fri'] == "true") { array_push($days_list, 5); }

				$totalIterations = $iterations * 7;
				
				for ($i = 0; $i < $totalIterations; $i++) {

					/* is the current day in the array of days */

					$today_day = $follow_up_date->format('N');

					if (in_array($today_day, $days_list)) {
						
						$follow_up_date_full 	= 	$follow_up_date->format('Y-m-d 00:00:00');

						/* is the first recurring task also the first task - created at the same time as the initial task? */
						
						if (!$activity_id) {

							$createRecurringQuery	=	"INSERT INTO activity 
														(contactsID, contactPersonID, orderID, quoteID, activityAdded, activityBy, followUpAssignees, followUpDate, followUpPriority,
														activityFile, seriesType, nextActionID)
														VALUES
														('$contacts_id', '$contact_person_id', '$order_id', '$quote_id', NOW(), '$activity_by', '$follow_up_assignees', '$follow_up_date_full', 
														'$follow_up_priority', '$file_list', '$recur_type', '$next_action_id'
														)";

							$createRecurringResult	=	$db -> query($createRecurringQuery);
							$newActivityID			=	mysql_insert_id();
							$activity_id = $newActivityID;

							$recurringTaskQuery		=	"INSERT INTO activity_tasks
														(activityID, task, allocatedTo, createdDate, createdBy)
														VALUES
														('" . $activity_id . "',
														'" . $new_task . "',
														'" . $activity_by . "',
														NOW(),
														'" . $activity_by . "'
														)";
							$recurringtaskResult	=	$db->query($recurringTaskQuery);												

						} else { 

							$createRecurringQuery	=	"INSERT INTO activity 
													(contactsID, contactPersonID, orderID, quoteID, activityAdded, activityBy, followUpAssignees, followUpDate, followUpPriority,
													activitySeries, activityFile, seriesType, nextActionID)
													VALUES
													('$contacts_id', '$contact_person_id', '$order_id', '$quote_id', NOW(), '$activity_by', '$follow_up_assignees', '$follow_up_date_full', 
													'$follow_up_priority', '$activity_id', '$file_list', '$recur_type', '$next_action_id'
													)";
													$createRecurringResult	=	$db -> query($createRecurringQuery);
													$newActivityID			=	mysql_insert_id();

							$taskQuery				=	"SELECT task, allocatedTo, createdBy FROM activity_tasks WHERE activityID = " . $activity_id;
							$taskResult				=	$db->query($taskQuery);

							while ($taskRow = $taskResult->fetch()) {

								$recurringTaskQuery		=	"INSERT INTO activity_tasks
															(activityID, task, allocatedTo, createdDate, createdBy)
															VALUES
															('" . $newActivityID . "',
															'" . $taskRow['task'] . "',
															'" . $taskRow['allocatedTo'] . "',
															NOW(),
															'" . $taskRow['createdBy'] . "'
															)";
								$recurringtaskResult	=	$db->query($recurringTaskQuery);

							}
						}

					}

					$follow_up_date 			= 	$dateObj->modify('+1 day');

				}

			}

			if ($recur_type == "month") { 

				$start_date = $recur_data_array[0]['month_start_date']; 
				$iterations =  $recur_data_array[0]['months_duration']; 
				$recur_month_type =  $recur_data_array[0]['recur_month_type']; 
				$month_frequency =  $recur_data_array[0]['month_frequency']; 
				$recurring_month_day =  $recur_data_array[0]['recurring_month_day']; 
				$recurring_month_date =  $recur_data_array[0]['recurring_month_date']; 

				if (strlen($start_date) == 8) { $dateObj = \DateTime::createFromFormat("d/m/y", $start_date); }
				if (strlen($start_date) == 10) { $dateObj = \DateTime::createFromFormat("d/m/Y", $start_date); }
				$follow_up_date = $dateObj;
				$today = date('Y-m-d');

				if ($recur_month_type == "date") {

					$starting_day = $recurring_month_date;
					$first_date = date_create($follow_up_date->format('Y-m-' . $starting_day));

					if (strlen($today) == 8) { $dateObj = \DateTime::createFromFormat("d/m/y", $today); }
					if (strlen($today) == 10) { $dateObj = \DateTime::createFromFormat("d/m/Y", $today); }
					$today_compare = $dateObj;

					if (($first_date) < ($today_compare)) {
						$follow_up_date = $first_date->modify('+1 month');
					} else {
						$follow_up_date = $first_date;
					}

					for ($i = 0; $i < $iterations; $i++) {

						if($follow_up_date->format('N') == 6) {
							$follow_up_date 		= 	$follow_up_date->modify('+2 day');
						}

						if($follow_up_date->format('N') == 7) {
							$follow_up_date 		= 	$follow_up_date->modify('+1 day');
						}

						$follow_up_date_full 	= 	$follow_up_date->format('Y-m-d 00:00:00');

						/* is the first recurring task also the first task - created at the same time as the initial task? */

						if (!$activity_id) {

							$createRecurringQuery	=	"INSERT INTO activity 
														(contactsID, contactPersonID, orderID, quoteID, activityAdded, activityBy, followUpAssignees, followUpDate, followUpPriority,
														activityFile, seriesType, nextActionID)
														VALUES
														('$contacts_id', '$contact_person_id', '$order_id', '$quote_id', NOW(), '$activity_by', '$follow_up_assignees', '$follow_up_date_full', 
														'$follow_up_priority', '$file_list', '$recur_type', '$next_action_id'
														)";

							$createRecurringResult	=	$db -> query($createRecurringQuery);
							$newActivityID			=	mysql_insert_id();
							$activity_id = $newActivityID;

							$recurringTaskQuery		=	"INSERT INTO activity_tasks
														(activityID, task, allocatedTo, createdDate, createdBy)
														VALUES
														('" . $activity_id . "',
														'" . $new_task . "',
														'" . $activity_by . "',
														NOW(),
														'" . $activity_by . "'
														)";
							$recurringtaskResult	=	$db->query($recurringTaskQuery);	
							
							echo $recurringTaskQuery;

						} else { 

							$createRecurringQuery	=	"INSERT INTO activity 
													(contactsID, contactPersonID, orderID, quoteID, activityAdded, activityBy, followUpAssignees, followUpDate, followUpPriority,
													activitySeries, activityFile, seriesType, nextActionID)
													VALUES
													('$contacts_id', '$contact_person_id', '$order_id', '$quote_id', NOW(), '$activity_by', '$follow_up_assignees', '$follow_up_date_full', 
													'$follow_up_priority', '$activity_id', '$file_list', '$recur_type', '$next_action_id'
													)";
													$createRecurringResult	=	$db -> query($createRecurringQuery);
													$newActivityID			=	mysql_insert_id();

							$taskQuery				=	"SELECT task, allocatedTo, createdBy FROM activity_tasks WHERE activityID = " . $activity_id;
							$taskResult				=	$db->query($taskQuery);

							while ($taskRow = $taskResult->fetch()) {

								$recurringTaskQuery		=	"INSERT INTO activity_tasks
															(activityID, task, allocatedTo, createdDate, createdBy)
															VALUES
															('" . $newActivityID . "',
															'" . $taskRow['task'] . "',
															'" . $taskRow['allocatedTo'] . "',
															NOW(),
															'" . $taskRow['createdBy'] . "'
															)";
								$recurringtaskResult	=	$db->query($recurringTaskQuery);

							}
						}

						$follow_up_date			= 	date_create($follow_up_date->format('Y-m-' . $starting_day));
						$follow_up_date 		= 	$follow_up_date->modify('+1 month');
					}

				}

				if ($recur_month_type == "day") {

					/* set defaults and starting point */

					$day_num = 0;

					switch ($recurring_month_day) { 
						case "Monday":
							$day_num = 1;
							break;
						case "Tuesday":
							$day_num = 2;
							break;
						case "Wednesday":
							$day_num = 3;
							break;
						case "Thursday":
							$day_num = 4;
							break;
						case "Friday":
							$day_num = 5;
							break;
					}

					$ordinal_count	=	$month_frequency;
					$current_count	= 	1;
					$first_date = date_create($follow_up_date->format('Y-m-01'));
					$process_date = clone $first_date;
					if (strlen($today) == 8) { $dateObj = \DateTime::createFromFormat("d/m/y", $today); }
					if (strlen($today) == 10) { $dateObj = \DateTime::createFromFormat("d/m/Y", $today); }
					$today_compare = $dateObj;

					for ($i = 0; $i < $iterations; $i++) {

						for ($dayCount = 0; $dayCount < 7; $dayCount++) {

							if ($process_date->format('N') != $day_num)  {
								$process_date 			= 	$process_date->modify('+1 day');
							}

						}
						// if ($process_date->format('N') == $day_num)  {

						for ($ordinalCount = 1; $ordinalCount < $ordinal_count; $ordinalCount++) {

							if ($current_count != $ordinal_count) {
								$process_date 			= 	$process_date->modify('+7 day');
								$current_count++;
							}

						}

						$compare_date = date_create($process_date->format('Y-m-d'));

						if ($process_date > $today_compare) { 

							$follow_up_date_full 	= 	$process_date->format('Y-m-d 00:00:00');

							/* is the first recurring task also the first task - created at the same time as the initial task? */

							if (!$activity_id) {

								$createRecurringQuery	=	"INSERT INTO activity 
															(contactsID, contactPersonID, orderID, quoteID, activityAdded, activityBy, followUpAssignees, followUpDate, followUpPriority,
															activityFile, seriesType, nextActionID)
															VALUES
															('$contacts_id', '$contact_person_id', '$order_id', '$quote_id', NOW(), '$activity_by', '$follow_up_assignees', '$follow_up_date_full', 
															'$follow_up_priority', '$file_list', '$recur_type', '$next_action_id'
															)";

														$createRecurringResult	=	$db -> query($createRecurringQuery);
														$newActivityID			=	mysql_insert_id();
														$activity_id = $newActivityID;

														$recurringTaskQuery		=	"INSERT INTO activity_tasks
																					(activityID, task, allocatedTo, createdDate, createdBy)
																					VALUES
																					('" . $activity_id . "',
																					'" . $taskRow['task'] . "',
																					'" . $taskRow['allocatedTo'] . "',
																					NOW(),
																					'" . $taskRow['createdBy'] . "'
																					)";
														$recurringtaskResult	=	$db->query($recurringTaskQuery);												

							} else { 

								$createRecurringQuery	=	"INSERT INTO activity 
														(contactsID, contactPersonID, orderID, quoteID, activityAdded, activityBy, followUpAssignees, followUpDate, followUpPriority,
														activitySeries, activityFile, seriesType, nextActionID)
														VALUES
														('$contacts_id', '$contact_person_id', '$order_id', '$quote_id', NOW(), '$activity_by', '$follow_up_assignees', '$follow_up_date_full', 
														'$follow_up_priority', '$activity_id', '$file_list', '$recur_type', '$next_action_id'
														)";
														$createRecurringResult	=	$db -> query($createRecurringQuery);
														$newActivityID			=	mysql_insert_id();

								$taskQuery				=	"SELECT task, allocatedTo, createdBy FROM activity_tasks WHERE activityID = " . $activity_id;
								$taskResult				=	$db->query($taskQuery);

								while ($taskRow = $taskResult->fetch()) {

									$recurringTaskQuery		=	"INSERT INTO activity_tasks
																(activityID, task, allocatedTo, createdDate, createdBy)
																VALUES
																('" . $newActivityID . "',
																'" . $taskRow['task'] . "',
																'" . $taskRow['allocatedTo'] . "',
																NOW(),
																'" . $taskRow['createdBy'] . "'
																)";
									$recurringtaskResult	=	$db->query($recurringTaskQuery);

								}
							}

							$process_date 			= 	$process_date->modify('+1 month');
							$process_date 			= 	date_create($process_date->format('Y-m-01'));
							$current_count = 1;
						} else {
							$process_date 			= 	$process_date->modify('+1 month');
							$process_date 			= 	date_create($process_date->format('Y-m-01'));
							$i--;
						}

					}

				}

			}

			/* make the current task part of the series */

			$linkRecurringActivity	=	"UPDATE activity SET activitySeries = '$activity_id', seriesType = '$recur_type' WHERE activityID = '$activity_id'";
			$linkRecurringResult	=	$db -> query($linkRecurringActivity);

			return;


	}





?>
