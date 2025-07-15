<?php

	include_once ("../includes/includes.php");
	$cnn = $db;
	
 	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);

class crm_mdl {

	private $ignore_ids = "4,5,8,52,57,88,105,108";

	public function __construct() {

	}

	public function getUserObjectDB($member_id) {

		$user_query 	= 	'SELECT 
								memberID,
								MemberName,
								memberSurname,
								memberDisplayName,
								memberInitials,
								memberEmail,
								memberSPLRetail,
								memberSPLIndustrial,
								memberSPLIT,
								memberSPLWarehouse,
								memberSPLAccounts,
								memberBackColour
							FROM members
							WHERE memberID = ' . $member_id;

		global $db;
		$user_result = $db->query($user_query);
		$user_object = $user_result->fetch();

		return $user_object;

	}

	
	public function getUserPreferencesDB($member_id) {

		$getUserPrefQuery = "SELECT * FROM crm_preferences WHERE memberID = " . $member_id;

		global $db;
		$getUserPrefResult = $db->query($getUserPrefQuery);

		return $getUserPrefResult;

	}

	public function getTeamMembersDB() {

		$team_industrial = false;
		$team_retail = false;

		$team_choice = " AND 1 = 1 ";

		if ($_SESSION['user_object']['memberSPLIndustrial'] == 1 ) { $team_industrial = true;}
		if ($_SESSION['user_object']['memberSPLRetail'] == 1 ) { $team_retail = true;}
		if ($_SESSION['user_object']['memberSPLAccounts'] == 1 ) { $team_accounts = true;}


		if ($team_industrial && $team_retail) { $team_choice = "memberSPLIndustrial = 1 OR memberSPLRetail = 1"; }
		if ($team_industrial && !$team_retail) { $team_choice = "memberSPLIndustrial = 1"; }
		if (!$team_industrial && $team_retail) { $team_choice = "memberSPLRetail = 1"; }

		$team_choice = "memberSPLIndustrial = 1 OR memberSPLRetail = 1 OR memberSPLAccounts = 1";
		
		/* get the users teams from their object */

		$userQuery 		=		"SELECT * FROM members WHERE memberName != '' AND memberArchive = 0 and (" . $team_choice . ") ORDER BY memberName ASC";
		global $db;
		$userResult		= 		$db -> query ($userQuery);

		return $userResult;

	}

	public function getAllTasksDB($data_array) {

		$assignees_query = $data_array['assignees'];
		$completed_filter = $data_array['completed'];
		$to_do_query =  $data_array['to_do'];
		$show_recur_query = $data_array['recur'];
		$week_to_show = $data_array['range'];
		$order_by = $data_array['order'];

		if (isset($_SESSION['show_self'])) {
			$creator = " AND contactPersonID = " . $_SESSION['memberSPLSWID'] ;
		} else { 
			$creator = " AND 1 = 1 ";
		}

		$allowable_trays_query = " AND 1 = 1 ";

		$allowable_trays = $this->get_viewable_trays_db();
		$allowable_trays_list = implode(",", $allowable_trays);

		if ($allowable_trays) { 

			$allowable_trays_query = " AND nextActionID IN  (" . $allowable_trays_list . ")";

		}		

		$tasksQuery	= 	"SELECT DISTINCT
					activity.*,
					date_format(activity.followupdate, '%d/%m/%Y') follow_up_date,
					date_format(activity.activityCompleted, '%d/%m/%Y') activity_completed_date,
					date_format(followUpDate, '%H:%i') time_for_col,
					date_format(activity.followupdate, '%Y-%m-%d') date_for_col,
					convert(concat(quoteID, orderID), SIGNED) all_q_o,
					jobby.memberDisplayName  jobByName,
					jobby.memberPhoto  jobByPhoto,
					contacts.contactsCompany,
					t.trayID, t.trayName, t.trayLabel, t.trayBackColour, t.system
				FROM activity, members jobby, contacts, crm_trays t
				WHERE jobby.memberID = activity.activityBy
				AND contacts.contactsID = activity.contactsID 
				AND activity.followUpAssignees IS NOT NULL
				AND activity.nextActionID = t.trayID
				" . $assignees_query . "
				" . $completed_filter . "
				" . $to_do_query . " 
				" . $show_recur_query . "
				" . $week_to_show . "
				" . $creator . "
				" . $allowable_trays_query . "				

				UNION ALL

			SELECT DISTINCT
				activity.*,
				date_format(activity.followupdate, '%d/%m/%Y') follow_up_date,
				date_format(activity.activityCompleted, '%d/%m/%Y') activity_completed_date,
				date_format(followUpDate, '%H:%i') time_for_col,
				date_format(activity.followupdate, '%Y-%m-%d') date_for_col,
				convert(concat(quoteID, orderID), SIGNED) all_q_o,
				jobby.memberDisplayName  jobByName,
				jobby.memberPhoto  jobByPhoto,
				jobSelf.memberDisplayName jobSelfName,
				t.trayID, t.trayName, t.trayLabel, t.trayBackColour, t.system
			FROM activity, members jobby, members jobSelf, crm_trays t
			WHERE jobby.memberID = activity.activityBy
			AND activity.contactsID = 0
			AND activity.contactPersonID = jobSelf.memberID
			AND activity.followUpAssignees IS NOT NULL
			AND activity.nextActionID = t.trayID
			" . $assignees_query . "
			" . $completed_filter . "
			" . $to_do_query . " 
			" . $show_recur_query . "
			" . $week_to_show . "
			" . $creator . "
			" . $allowable_trays_query . "			
			" . $order_by;

		global $db;
		$tasksResult = $db -> query($tasksQuery);

		return $tasksResult;

	}

	public function private_tray_toggle_db($tray_id) {

		/* is this tray set private or public? */

		$checkPrivateQuery = "SELECT private from crm_trays where trayID = " . $tray_id;
		global $db;
		$checkPrivateResult = $db->query($checkPrivateQuery);
		$isPrivateArray = $checkPrivateResult->fetch();
		$isPrivate = $isPrivateArray['private'];

		if ($isPrivate == 1) { 
			$updateQuery = "UPDATE crm_trays set private = 0 where trayID = " . $tray_id;
		} else {
			$updateQuery = "UPDATE crm_trays set private = 1 where trayID = " . $tray_id;
		}

		echo $updateQuery;

		global $db;
		$updateResult = $db->query($updateQuery);

		return;
		
	}

	public function set_archive_db($activityID) {
		
		$query				=		"UPDATE activity SET activityArchive = NOW() WHERE activityID = '$activityID'";

		global $db;
		$result				=		$db -> query($query);
		return;

	}

	public function get_crm_task_db($task_id) {

		$taskQuery	= 	"SELECT
					activity.*,
					date_format(activity.followupdate, '%d/%m/%Y') follow_up_date,
					date_format(activity.activityAdded, '%d/%m/%Y') activity_added_date,
					date_format(activity.activityAdded, '%h:%i%p') activity_added_time,
					date_format(activity.followupdate, '%l:%i') follow_up_time,
					date_format(activity.activityCompleted, '%d/%m/%Y') activity_completed_date,
					jobby.memberDisplayName  jobByName,
					jobby.memberPhoto  jobByPhoto,
					jobfor.memberDisplayName  jobForName,
					jobfor.memberSurName  jobForSurName,
					jobfor.memberPhoto  jobForPhoto,
					contacts.contactsCompany,
					contacts.contactsTelephone,
					t.trayName, t.traySlug, t.trayID, `t`.`system` is_system
					-- p.contactsName, p.contactPersonID, p.contactsTelephone, p.contactsEmail, p.contactsProfile, p.family, p.pets, concat(p.sport,' - ',p.team) sport, p.holiday
				FROM activity
				LEFT JOIN members jobby ON (activity.activityBy = jobby.memberID)
				LEFT JOIN members jobfor ON (activity.activityFor = jobfor.memberID) 
				LEFT JOIN contacts ON (activity.contactsID = contacts.contactsID)
				-- LEFT JOIN contactPerson p ON (p.contactPersonID = activity.contactPersonID)
				LEFT JOIN crm_trays t ON t.trayID = activity.nextActionID
				WHERE activityID = '$task_id'";

		global $db;		
		$taskResult	=	$db -> query($taskQuery);
		$taskArray	=	$taskResult->fetch();

		return $taskArray;

	}

	public function get_full_assignee_inits_db() {

		/* first get my team */

		$inits_result = $this->get_my_team_list_db();

		$full_assignee_array = [];

		while ($inits_row = $inits_result->fetch()) {

			array_push($full_assignee_array, $inits_row);

		}

		return $full_assignee_array;

	}

	public function get_recurring_end_date_db($activity_id) {

		global $db;	
		$endDateQuery		= 		"SELECT max(followUpDate) follow_up_date, seriesType FROM activity 
										WHERE activityArchive is null 
										AND activitySeries = (SELECT activitySeries FROM activity where activityID = " . $activity_id . ")";
		$endDateResult		=		$db -> query($endDateQuery);
		$endDateArray		=		$endDateResult->fetch();
		$end_date			=		$endDateArray['follow_up_date'];
		$series_type 		= 		$endDateArray['seriesType'];

		$recur_array = $end_date . "," . $series_type;

		return $recur_array;

	}

	public function get_member_inits_db($activity_id, $assignees_list) {

		$my_team = '';
		$my_team = '';
		$not_my_team = '';
		$assignees_array = [];
		$my_team_array = [];
		$not_my_team_array = [];

		/* extract follow_up_assignees into searchable array */

		$assignee_list  = implode(",", $assignees_list);

		/* get the team that the current user is in */

		$memberObject = $this->getMemberObject();

		if ($memberObject['member_retail'] || $memberObject['member_ind']) { $my_team = " AND (memberSPLRetail = 1 OR memberSPLIndustrial = 1)" ; }

		if ($memberObject['member_retail']) { $not_my_team = " AND (memberSPLRetail = 0 ) "; }
		if ($memberObject['member_ind']) { $not_my_team .= " AND (memberSPLIndustrial = 0 ) "; }
		if ($memberObject['member_accounts']) { $not_my_team .= " AND (memberSPLAccounts = 0 ) "; }
		if ($memberObject['member_it']) { $not_my_team .= " AND (memberSPLIT = 0 ) "; }
		if ($memberObject['member_warehouse']) { $not_my_team .= " AND (memberSPLWarehouse = 0 ) "; }

		/* get inits for the assignees */

		$assignees_query = '

			SELECT a.activityID, ta.memberID, ta.memberInitials member_inits, ta.memberBackColour, ta.memberPhoto, ta.memberName
			FROM activity a, members ta
			WHERE activityID = ' . $activity_id . '
			AND ta.memberID in (' . $assignee_list . ')
			AND memberID NOT IN (' . $this->ignore_ids . ')
			AND ta.membersurname != ""
			AND ta.memberInitials != ""
			AND ta.memberSPLWarehouse = 0
			ORDER BY member_inits

		';
		

		$my_team_result = $this->get_my_team_list_db($assignee_list);

		$not_my_team_query = '

			SELECT a.activityID, ntm.memberID, ntm.memberInitials member_inits, ntm.memberBackColour, ntm.memberPhoto, ntm.memberName
			FROM activity a, members ntm
			WHERE activityID = ' . $activity_id . '
			AND ntm.memberID NOT IN (' . $assignee_list . ')
			AND memberID NOT IN (' . $this->ignore_ids . ')
			AND ntm.memberArchive = 0
			' . $not_my_team . '
			AND ntm.membersurname != ""
			AND ntm.memberInitials != ""
			AND ntm.memberSPLWarehouse = 0
			ORDER BY member_inits

		';

		global $db;	

		$assignees_result = $db->query($assignees_query);
		// $my_team_result = $db->query($my_team_query);
		$not_my_team_result = $db->query($not_my_team_query);

		$assignee_inits = "";

		while ($assignee_row = $assignees_result->fetch()) {

			$assignee_inits = "";

			$full_name = explode(" ", $assignee_row['member_inits']);
			foreach ($full_name as $inits) {
				$assignee_inits .= $inits;
			}

			array_push($assignees_array, [
				'assignee_id' => $assignee_row['memberID'], 
				'member_inits'  => $assignee_inits, 
				'memberBackColour' => $assignee_row['memberBackColour'],
				'member_photo' => $assignee_row['memberPhoto'],
				'member_name' => $assignee_row['memberName']
			]);
		}

		while ($my_team_row = $my_team_result->fetch()) {

			$assignee_inits = "";

			$full_name = explode(" ", $my_team_row['memberInitials']);
			foreach ($full_name as $inits) {
				$assignee_inits .= $inits;
			}

			array_push($my_team_array, [
				'assignee_id' => $my_team_row['memberID'], 
				'memberInitials'  =>$assignee_inits, 
				'memberBackColour' => $my_team_row['memberBackColour'],
				'member_photo' => $my_team_row['memberPhoto'],
				'member_name' => $my_team_row['memberName']
			]);
		}

		while ($not_my_team_row = $not_my_team_result->fetch()) {

			$assignee_inits = "";

			$full_name = explode(" ", $not_my_team_row['member_inits']);
			foreach ($full_name as $inits) {
				$assignee_inits .= $inits;
			}

			array_push($not_my_team_array, [
				'assignee_id' => $not_my_team_row['memberID'], 
				'member_inits'  => $assignee_inits, 
				'memberBackColour' => $not_my_team_row['memberBackColour'],
				'member_photo' => $not_my_team_row['memberPhoto'],
				'member_name' => $not_my_team_row['memberName']
			]);
		}

		$team_data = [
			['assignees_array' => $assignees_array],
			['my_team_array' => $my_team_array],
			['not_my_team_array' => $not_my_team_array],
		];

		return $team_data;

	}

	public function get_series_data_db($activity_id) {

		$getSeriesQuery			=	"SELECT
									activityID, 
									date_format(followUpDate, '%D %M %Y') follow_up_date, 
									date_format(followUpDate, '%H:%i') follow_up_time, 
									followUpDate,
									activityCompleted,
									activityCompletedBy,
									date_format(activityCompleted,  '%D %M %Y') completed_date, 
									followUpAssignees,
									t.trayBackColour,
									t.trayName,
									m.memberInitials completed_by_inits,
									m.memberBackColour
									FROM activity
									LEFT JOIN crm_trays t ON t.trayID = activity.nextActionID
									LEFT JOIN members m ON m.memberID = activity.activityCompletedBy
									WHERE activitySeries = 
										(SELECT activitySeries FROM activity WHERE activityID = " . $activity_id . ") 
									ORDER BY followUpDate";

		global $db;

		$getSeriesResult		=	$db -> query($getSeriesQuery);

		return $getSeriesResult;

	}

	public function get_activity_members_db($assignees_list) {

		/* extract follow_up_assignees into searchable array */

		$assignee_list  = implode(",", $assignees_list);

		/* get inits for the assignees */

		$assignees_query = '

			SELECT memberID, memberInitials member_inits, memberBackColour FROM members WHERE memberID in (' . $assignee_list . ')
			ORDER BY member_inits';

		global $db;
		$assignees_result		=	$db -> query($assignees_query);

		return $assignees_result;

	}

	public function getSeriesLastDateDB($activity_id) {

		$last_date_query 		= "SELECT max(followUpDate) last_date FROM activity WHERE activitySeries = " . $activity_id ;
		$second_last_date_query = "SELECT max(followUpDate) last_date FROM activity WHERE activitySeries = " . $activity_id . " AND followUpDate < (SELECT MAX(followUpDate) FROM activity WHERE activitySeries = " . $activity_id . " ORDER BY followUpDate)";
		global $db;
		$last_date_result		=	$db -> query($last_date_query);
		$last_date_array 		= 	$last_date_result->fetch();
		$last_date				=   $last_date_array['last_date'];

		$second_last_date_result		=	$db -> query($second_last_date_query);
		$second_last_date_array 		= 	$second_last_date_result->fetch();
		$second_last_date				=   $second_last_date_array['last_date'];

		$dates_array = [
			'last_date' => $last_date,
			'second_last_date' => $second_last_date
		];

		return $dates_array;

	}

	public function getOrderQuoteTasksDB($type, $id) {

		$type_query = "";

		if ($type == "order") { $type_query = "WHERE orderID = '" . $id . "'"; }
		if ($type == "quote") { $type_query = "WHERE quoteID = '" . $id . "'"; }
		if (!$type_query) { return false; }

		$order_query = "SELECT 
							activityID, followUpDate, followUpAssignees,
							m.memberInitials completed_by_inits,
							m.memberBackColour,
							t.trayBackColour,
							t.trayName
							FROM activity 
							LEFT JOIN members m ON m.memberID = activity.activityCompletedBy
							LEFT JOIN crm_trays t ON t.trayID = activity.nextActionID
							" . $type_query . "
							AND activityArchive is null
							AND activityCompleted is null";
		global $db;
		$order_result		=	$db -> query($order_query);

		return $order_result;

	}	

	public function get_last_task_db($activity_id) {

		$task_query = "SELECT taskID, task FROM activity_tasks WHERE taskID = (select max(taskID) from activity_tasks WHERE activityID = " . $activity_id . " )";
		global $db;
		$task_result = $db->query($task_query);
		$task_array = $task_result->fetch();
		$task = $task_array['task'];

		return $task;

	}

	public function get_customer_tasks_db($editcontactsID) {

		if (isset( $_SESSION['crm_full_order_by'] )) {
			$order_by = $_SESSION['crm_full_order_by'];
		} else {
			$order_by = 'ORDER BY followUpDate ASC';
		}
	
		$activity_completed = " AND activityCompleted is null";
	
		if (!isset($_SESSION['customer_id']) || (isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != $_GET["editcontactsID"] ) ) { 
			$_SESSION['customer_id'] = $_GET["editcontactsID"]; 
		} else {
			if(!isset($_SESSION['show_completed_for_customer'])) { 
				$activity_completed = " AND activityCompleted is null";
			} else {
				$activity_completed = " AND 1 = 1";
			}
		}

		$allowable_trays_query = " AND 1 = 1 ";

		$allowable_trays = $this->get_viewable_trays_db();
		$allowable_trays_list = implode(",", $allowable_trays);

		if ($allowable_trays) { 

			$allowable_trays_query = " AND nextActionID IN  (" . $allowable_trays_list . ")";

		}
	
		$tasksQuery	= 	"SELECT DISTINCT
						activity.*,
						date_format(activity.followupdate, '%d/%m/%Y %H:%i') follow_up_date,
						date_format(activity.activityCompleted, '%d/%m/%Y') activity_completed_date,
						date_format(followUpDate, '%H:%i') time_for_col,
						concat (followupnotes, activitynotes) all_notes,
						convert(concat(quoteID, orderID), SIGNED) all_q_o,
						jobby.memberDisplayName  jobByName,
						jobby.memberPhoto  jobByPhoto,
						jobfor.memberDisplayName  jobForName,	
						jobfor.memberSurName  jobForSurName,
						jobfor.memberPhoto  jobForPhoto,
						contacts.contactsCompany,
						t.trayName, t.trayBackColour
					FROM activity, members jobfor, members jobby, contacts, crm_trays t
					WHERE activity.contactsID = '$editcontactsID'
					AND jobby.memberID = activity.activityBy
					AND contacts.contactsID = activity.contactsID
					AND jobfor.memberID in (REGEXP_REPLACE(REPLACE(activity.followUpAssignees, '', ''), '[^0-9,]', ''))
					AND activity.activityArchive is null
					AND activity.nextActionID = t.trayID
					" . $activity_completed . "
					" . $allowable_trays_query . "
					" . $order_by;

		global $db;
		$tasksResult = $db -> query($tasksQuery);

		return $tasksResult;
	
	}

	public function get_self_tasks_db($user_id) {

		$activity_completed = " AND 1 = 1";
	
		if (!isset($_SESSION['toggle_completed']) || $_SESSION['toggle_completed'] == "0") { 
			$activity_completed = " AND activityCompleted is null";
		}

		$order_by = 'ORDER BY followUpDate ASC';

		$tasksQuery	= 	"SELECT DISTINCT
							a.*,
							date_format(a.followupdate, '%d/%m/%Y %H:%i') follow_up_date,
							date_format(a.activityCompleted, '%d/%m/%Y') activity_completed_date,
							date_format(a.followUpDate, '%H:%i') time_for_col,
							t.trayID, t.trayName, t.trayLabel, t.trayBackColour
						FROM
							activity a
						LEFT JOIN 
							crm_trays t ON t.trayID = a.nextActionID
						WHERE 
							contactPersonID = " . $user_id . "
						AND
							activityArchive is null
						" . $activity_completed . "
						" . $order_by;

		global $db;
		$tasksResult = $db -> query($tasksQuery);

		return $tasksResult;		

	}	

	public function get_non_team_members_db() {

		$my_team_result = $this->get_my_team_list_db();
		$non_team_ids = '';
		$user_id = $_SESSION['memberSPLSWID'];
		$team_array = [];

		while ($my_team = $my_team_result->fetch() ) {

			$non_team_ids .= $my_team['memberID'] . ",";

		}

		$non_team_ids = rtrim($non_team_ids, ',');

		$non_team_query = "SELECT memberID, memberName, memberInitials, memberBackColour, memberPhoto
							FROM members 
							WHERE memberArchive = 0
							AND memberInitials != ''
							AND memberID NOT IN (" . $non_team_ids . ")
							AND memberID NOT IN (" . $this->ignore_ids . ")
							AND memberID != " . $user_id . "
							AND memberSPLWarehouse = 0
							ORDER BY memberName
						";

		global $db;
		$non_team_result = $db->query($non_team_query);

		while ($non_team = $non_team_result->fetch()) {

			array_push($team_array, [
				'member_id' => $non_team['memberID'],
				'member_name' => $non_team['memberName'],
				'member_initials' => $non_team['memberInitials'],
				'member_back_colour' => $non_team['memberBackColour'],
				'member_photo' => $non_team['memberPhoto']
			]);

		}

		return $team_array;

	}

	public function get_self_inits_db() {

		$user_id 	= 	$_SESSION['memberSPLSWID'];
		$my_array 	= 	[];

		$self_query = "SELECT memberID, memberName, memberInitials, memberBackColour, memberPhoto, memberSPLAccounts, memberSPLRetail, memberSPLIndustrial, memberSPLIT 
							FROM members 
							WHERE memberID = " . $user_id
						;

		global $db;
		$self_result = $db->query($self_query);

		while ($self_row = $self_result->fetch()) {

			array_push($my_array, [
				'member_id' => $self_row['memberID'],
				'member_name' => $self_row['memberName'],
				'member_initials' => $self_row['memberInitials'],
				'member_back_colour' => $self_row['memberBackColour'],
				'member_photo' => $self_row['memberPhoto'],
				'member_accounts' => $self_row['memberSPLAccounts'],
				'member_retail' => $self_row['memberSPLRetail'],
				'member_industrial' => $self_row['memberSPLIndustrial'],
				'member_split' => $self_row['memberSPLIT']
			]);

		}

		return $my_array;

	}

	private function get_my_team_list_db($assignee_list = null) {

		$user_id = $_SESSION['memberSPLSWID'];

		if (!$assignee_list) { $assignee_list = $user_id;}

		$my_team_query = 'SELECT memberSPLAccounts, memberSPLRetail, memberSPLIndustrial, memberSPLIT FROM members where memberid = ' . $user_id;

		global $db;
		$my_team_result = $db->query($my_team_query);

		$industrial_query 	= "";
		$retail_query 		= "";
		$accounts_query 	= "";
		$it_query 			= "";
		$warehouse_query	= " AND memberSPLWarehouse = 0";
		$team_array			= [];

		while ($my_team = $my_team_result->fetch() ) {

			if ($my_team['memberSPLIndustrial']) { $industrial_query = ' memberSPLIndustrial = 1 OR ' ;} 
			if ($my_team['memberSPLRetail']) { $retail_query = ' memberSPLRetail = 1 OR ' ;}
			if ($my_team['memberSPLAccounts']) { $accounts_query = ' memberSPLAccounts = 1 OR ' ;}
			if ($my_team['memberSPLIT']) { $it_query = ' memberSPLIT = 1 OR ' ;}
			
		}

		$teams_query = " AND (" . $industrial_query .  $retail_query . $accounts_query . $it_query;

		$query = rtrim($teams_query, "RO ") . ")";

		$team_query = "SELECT memberID, memberName, memberInitials, memberBackColour, memberPhoto, memberSPLAccounts, memberSPLRetail, memberSPLIndustrial, memberSPLIT
							FROM members 
							WHERE memberArchive = 0
							AND memberInitials != ''
							" . $query . "
							". $warehouse_query . "
							AND memberID NOT IN (" . $assignee_list . ")
							AND memberID NOT IN (" . $this->ignore_ids . ")							
							ORDER BY memberName
						";

		global $db;
		$team_result = $db->query($team_query);

		return $team_result;

	}

	private function getMemberObject() {

		$member_id = $_SESSION['memberSPLSWID'];

		$memberQuery = "SELECT * from members WHERE memberID = " . $member_id;
		global $db;	
		$memberResult = $db->query($memberQuery);
		$memberArray = $memberResult->fetch();

		$memberObject = [];
		$retail = false;
		$instrustrial = false;
		$accounts = false;
		$it = false;
		$warehouse = false;

		if ($memberArray['memberSPLRetail']) { $retail = true ;}
		if ($memberArray['memberSPLIndustrial']) { $instrustrial = true ;}
		if ($memberArray['memberSPLAccounts']) { $accounts = true ;}
		if ($memberArray['memberSPLIT']) { $it = true ;}

		$memberObject = [
			'member_id' => $member_id,
			'member_retail' => $retail,
			'member_ind' => $instrustrial,
			'member_accounts' => $accounts,
			'member_it' => $it,
			'member_warehouse' => $warehouse,
		];

		return $memberObject;

	}

	public function check_permissions_db($activty_id) {

		$user_id = $_SESSION['memberSPLSWID'];

		$like = "'" . "%" . '"' . $user_id . '"' . "%" . "'";
		$assignees_query = " AND (activityBy = " . $user_id . " OR followUpAssignees LIKE " . $like . ")";

		$permissions_query = "SELECT 
								activityBy, followupassignees 
							FROM 
								activity 
							WHERE 
								activityID = " . $activty_id . " 
							" . $assignees_query;

		global $db;
		$permissions_result = $db -> query($permissions_query);
		$permissions_array = $permissions_result->fetch();

		print_r($permissions_array);

		return $permissions_array;

	}

	public function check_lates_db() {

		$checkalerts = $_SESSION["memberSPLSWID"];
		// $checkalerts = 400;

		$like = "'" . "%" . '"' . $checkalerts . '"' . "%" . "'";
		$assignees_query = " AND followUpAssignees LIKE " . $like ;

		$lates_query		=		"SELECT	
										a.activityID,
										a.followUpDate,
										a.activitySeries,
										a.followUpPriority,
										date_format(a.followUpDate, '%d/%m/%Y') activity_completed_date,
										a.contactsID,
										t.trayID, t.trayName, t.trayLabel, t.trayBackColour,
										c.contactsCompany, m.memberName
									FROM
										activity a
									LEFT JOIN 
										crm_trays t ON t.trayID = a.nextActionID
									LEFT JOIN
										contacts c ON c.contactsID = a.contactsID
									LEFT JOIN
										members m ON m.memberID = a.contactPersonID	
									WHERE  followUpDate != '0000-00-00 00:00:00' 
									AND activityCompleted IS NULL 
									AND `activityArchive` IS NULL 
									AND( DATE(followUpDate) < DATE(NOW()))	
									" . $assignees_query . "
									ORDER BY followUpDate ASC";

		global $db;
		$lates_result		=		$db -> query($lates_query);

		return $lates_result;

	}


	public function get_activity_history_db() {

		$user_id = $_SESSION['memberSPLSWID'];
		$date_filter = date("Y-m-d");

		$start_date = $date_filter . " 00:00:00";
		$end_date = $date_filter . " 23:59:00";

		$historyQuery = "
			SELECT 
				a.activityID, a.contactsID, a.followUpDate, h.userCreating, c.contactsCompany, 
				date_format(h.dateCreated, '%d/%m/%Y') created_date,
				date_format(h.dateCreated, '%H:%i') created_time,
				t.auditTypeSlug,
				m.memberName, m.memberBackColour,
				k.trayBackColour
			FROM
				activity a
			LEFT JOIN 
				contacts c ON c.contactsID = a.contactsID
			LEFT JOIN 
				crm_audit_trail h ON h.activityID = a.activityID
			LEFT JOIN
				crm_audit_types t on t.auditTypeID = h.auditTypeID
			LEFT JOIN
				members m on m.memberID = h.userCreating
			LEFT JOIN
				crm_trays k ON k.trayID = a.nextActionID
			WHERE 
				h.userCreating = " . $user_id . "
			AND 
				h.dateCreated BETWEEN '" . $start_date . "' AND '" . $end_date . "'
			ORDER BY h.dateCreated DESC
		";

		global $db;
		$historyResult = $db->query($historyQuery);

		return $historyResult;

	}

	public function get_company_town_db($customer_id) {

		$townQuery		=		"SELECT address.addressTown town
								FROM contacts 
								INNER JOIN address ON contacts.contactsID = address.contactsID 
								WHERE contacts.customerType = 'customer' 
								AND (address.addressType LIKE '%Delivery%' OR address.addressTown IS NULL) 
								AND contacts.contactsRef != '' 
								AND contacts.contactsID = " . $customer_id. "
								GROUP BY contacts.contactsID 
								ORDER BY contacts.contactsCompany ASC, addressTown ASC";

		global $db;
		$townResult = $db->query($townQuery);
		$townArray = $townResult->fetch();
		$town = $townArray['town'];

		return $town;

	}

	public function add_new_tasks_db($activity_id, $newTask) {

		$user_id = $_SESSION['memberSPLSWID'];

		$insertQuery = 'INSERT INTO activity_tasks (activityID, task, allocatedTo, createdDate, createdBy)
		VALUES (
			' . $activity_id . ',
			"' . $newTask . '",
			' . $user_id . ',
			NOW(),
			' . $user_id . '
		)';

		global $db;
		$insertResult = $db->query($insertQuery);	

		return $insertResult;

	}

	public function set_activity_complete_db($activity_id, $is_complete, $new_task) {

		$user_id = $_SESSION['memberSPLSWID'];

		/* first add any new tasks */

		if ($activity_id && $new_task) { $this->add_new_tasks_db($activity_id, $newTask); } 

		/* now update the activity */

		if ($is_complete == "0") { 
			$activity_completed = " activityCompleted = NOW()";
			$activity_completed_by = " activityCompletedBy = " . $user_id;
			$audit_type = 'complete';
		} else {
			$activity_completed = " activityCompleted = null";
			$activity_completed_by = " activityCompletedBy = null";
			$audit_type = 're-open';
		}

		$completedQuery = "UPDATE activity SET 
								" . $activity_completed . ",
								" . $activity_completed_by . "
							WHERE 
								activityID = " . $activity_id ;

		global $db;
		$completedResult = $db->query($completedQuery);

		/* now add to the history */

		$history = $this->add_to_audit($audit_type, $activity_id);

		return;

	}

	private function add_to_audit($action_type, $activity_id) {

		$user_id = $_SESSION['memberSPLSWID'];

		/* get the type id for the type */

		$slug = "";

		switch ($action_type) {
			case "save":
				$slug = 'saved_activity';
				break;
			case "add":
				$slug = 'created_activity';
				break;
			case "complete":
				$slug = 'completed_activity';
				break;				
			case "re-open":
				$slug = 'reopened_activity';
				break;								
		 }

		$audit_type_id_query 	= 	"SELECT auditTypeID FROM crm_audit_types WHERE auditTypeSlug = '" . $slug . "'";

		global $db;		
		$audit_type_id_result	= 	$db->query($audit_type_id_query)	;
		$audit_type_id_array 	=	$audit_type_id_result->fetch();
		$audit_type_id			= 	$audit_type_id_array['auditTypeID'];

		if (!$audit_type_id) { $audit_type_id = 0;}

		$insert_query = 'INSERT INTO crm_audit_trail 
							(activityID, auditTypeID, dateCreated, userCreating) 
						VALUES (
		 				' . $activity_id . ',
						' . $audit_type_id . ',
						NOW(),
						' . $user_id . '
						)';

		$insert_result = $db->query($insert_query);

		return;

	}

	private function get_viewable_trays_db() {

		$user_id = $_SESSION['memberSPLSWID'];

		$tray_query = "SELECT trayID FROM crm_trays 
						WHERE system = 1  
						OR ((owner = " . $user_id . " OR members in (" . $user_id . ")) 
						OR (owner != " . $user_id . " AND members NOT in (" . $user_id . ") AND private !=1)) 
						AND deleted != 1";
						
		global $db;
		$tray_result = $db -> query($tray_query);
		$allowable_tray_array = [];

		while ($allowable_tray = $tray_result->fetch()) {
			array_push($allowable_tray_array, $allowable_tray['trayID']);
		}

		return $allowable_tray_array;		

	}

}

