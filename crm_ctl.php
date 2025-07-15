<?php
    
    include ("mdl/crm_mdl.php");

 	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
	 
class crm_ctl {

    private function connect_mdl() {
        
        $objMdl = new crm_mdl;
        return $objMdl;

    }

    public function getUserObject($member_id) {

        $mdl = $this->connect_mdl();
        $user_object = $mdl->getUserObjectDB($member_id);
        return $user_object;
        
    }    

    public function getUserPreferences($member_id) {

        $mdl = $this->connect_mdl();
        $get_user_prefs = $mdl->getUserPreferencesDB($member_id);
        return $get_user_prefs;

    }

    public function getTeamMembers() {

        $mdl = $this->connect_mdl();
        $get_team_members = $mdl->getTeamMembersDB();
        return $get_team_members;

    }

    public function getAllTasks($data_array) {

        $mdl = $this->connect_mdl();
        $get_all_tasks = $mdl->getAllTasksDB($data_array);
        return $get_all_tasks;

    }

    public function getSeriesLastDate($activity_id) {

        $mdl = $this->connect_mdl();
        $get_series_last_date = $mdl->getSeriesLastDateDB($activity_id);
        return $get_series_last_date;

    }    

    public function getOrderQuoteTasks($type, $id) { 

        $mdl = $this->connect_mdl();
        $get_order_tasks = $mdl->getOrderQuoteTasksDB($type, $id);
        return $get_order_tasks;

    }

    public function get_member_inits($activity_id, $assignees_list) { 

        $mdl = $this->connect_mdl();
        $get_members = $mdl->get_member_inits_db($activity_id, $assignees_list);
        return $get_members;

    }

    public function get_last_task($activity_id) {

        $mdl = $this->connect_mdl();
        $get_last_task = $mdl->get_last_task_db($activity_id);
        return $get_last_task;

    }    

    public function get_customer_tasks($editcontactsID) {
        
        $mdl = $this->connect_mdl();
        $get_customer_tasks = $mdl->get_customer_tasks_db($editcontactsID);
        return $get_customer_tasks;

    }

    public function get_self_tasks($user_id) {

        $mdl = $this->connect_mdl();
        $get_self_tasks = $mdl->get_self_tasks_db($user_id);
        return $get_self_tasks;
        
    }

    public function check_lates() {

        $mdl = $this->connect_mdl();
        $get_lates = $mdl->check_lates_db();
        return $get_lates;

    }    

    public function get_activity_history($user_id, $date_filter) {

        $mdl = $this->connect_mdl();
        $get_history= $mdl->get_activity_history_db($user_id, $date_filter);
        return $get_history;        

    }    

    public function get_company_town($customer_id) {

        $mdl = $this->connect_mdl();
        $town = $mdl->get_company_town_db($customer_id);
        return $town;    

    }    


}