/* declare global variables availble to every js function here */

	/* if logging set to 1 will output values to console */	
	var logging_value = 1;

	/* set env and then let then determine file paths */
	var app_path = 'crm_test';
	var env = 'live';
	// var env = 'test';

	if (!env) { var env = 'test';}
	if (env == "test") { app_path = "crm_test" ;}
	if (env == "live") { app_path = "crm" ;}

	var url = window.location.href;
	var ajax_path = "http://localhost/raptor/crm/ctl/ajaxcrmactions.php";
	var crm_path = "http://localhost/raptor/crm";
	var root_path = "http://localhost/raptor";
	var ajax_file_path = "http://localhost/raptor/crm/ajaxcrmfileupload.php"; 

	if (url.includes("www.splraptor.co.uk") ) { 
		ajax_path = "https://www.splraptor.co.uk/" + app_path + "/ctl/ajaxcrmactions.php"; 
		crm_path = "https://www.splraptor.co.uk/"  + app_path ;
		root_path = "https://www.splraptor.co.uk";
		ajax_file_path = "https://www.splraptor.co.uk/" + app_path + "/ajaxcrmfileupload.php"; 
	}

	var files_to_upload = [];
	// const dt = new DataTransfer();

	var months = [
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December",
	];

class crm {

	reload_crm_popup(activity_id) {

		editCrmTask(activity_id);
		return false;

	}

	save_crm_errors(activity_id) {

			var txtMessage = "Please ensure you enter at least one task.<br><br>";   
			var buttons = "<button class=btnCrm onclick=hide_dialog()> OK </button>";
			var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
			var message = txtMessage + btnRow;

			setDisplay();

			document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

			return false;

	}

	saveSuccess() {

		var txtMessage = "Saved successfully.<br><br>";   
		var buttons = "<button class=btnCrm onclick=hide_dialog()> OK </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		var message = txtMessage + btnRow;

		setDisplay();

		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;
		
	}

}

	var crmClass = new crm;

	function reset_crm_form() {

		$("#followUpDateOriginal").val("");
		$("#followUpTimeOriginal").val("");
		$("#contactPersonId").val("");
		$("#action_type").val("");
		$("#activity_id").val("");
		// $("#customer_choice").val("");
		$("#contactNames").empty();
		// $("#contactPhone").val("");
		$("#contactEmail").val("");
		$("#family").val("");
		$("#pets").val("");
		$("#sport").val("");
		$("#holiday").val("");
		$("#birthday").val("");
		$("#profile").val("");
		$("#contactProfile").val("");
		$("#activity_id_file").val("");
		// $("#date_activity_added").val("");
		$("#new_crm_task").val("");
		$("#tasks_data").val("");
		// $("#actions_count").val("");
		$("#followUpActionExtra").empty("");
		$("#recurringDayStartDate").val("");
		$("#recurringDayEndDate").val("");
		$("#recurringWeekStartDate").val("");
		$("#recurringWeeksDuration").val("");
		$("#btnMon").val("");
		$("#btnTue").val("");
		$("#btnWed").val("");
		$("#btnThu").val("");
		$("#btnFri").val("");
		$("#recurringMonthStartDate").val("");
		$("#recurringMonthsDuration").val("");
		$("#recurringMonthDate").val("");
		$("#followUpAssigned").val("");
		$("#urgent").prop('checked', false);
		$("#completed").prop('checked', false);

		$("#followUpDateOriginal").hide();
		$("#followUpTimeOriginal").hide();
		$("#contactPersonId").hide();
		$("#action_type").hide();
		$("#activity_id").show();
		$("#crmBy").show();
		$("#btnExit").show();
		$("#btnMinimize").hide();
		$("#customer_choice").hide();
		$("#contactNames").hide();
		$("#phone_block").hide();
		$("#email_block").hide();
		$("#family_block").hide();
		$("#pets_block").hide();
		$("#sport_block").hide();
		$("#holiday_block").hide();
		$("#birthday_block").hide();
		$("#profile_block").hide();
		$("#contactProfile").hide();
		$("#editProfile").show();
		$("#saveProfile").hide();
		$("#qo_value").show();
		$("#crmOrderID").show();
		$("#file_upload").show();
		$("#activity_id_file").hide();
		$("#show_files").hide();
		$("#crmFileUpload").show();
		$("#date_activity_added").show();
		$("#view_files").hide();
		$("#new_crm_task").show();
		$("#history_title").hide();
		// $("#tasks_data").hide();
		// $("#tray_??? (system trays)").show();
		// $("#actions_count").hide();
		$("#action_10001").hide();
		$(".traydetail").hide();
		$("#when_1000").hide();
		$("#when_2000").hide();
		$("#when_3000").hide();
		$("#recur_repeat").hide();
		$("#recur_day").hide();
		$("#recur_week").hide();
		$("#recur_month").hide();
		$("#toggle_recur").show();
		$("#dailyBlock").hide();
		$(".recur_daily_start").hide();
		$(".recur_daily_end").hide();
		// $("#recurringDayStartDate").hide();
		// $("#recurringDayEndDate").hide();
		$("#weeklyblock").hide();
		// $("#recurringWeekStartDate").hide();
		// $("#recurringWeeksDuration").hide();
		$("#btnMon").hide();
		$("#btnTue").hide();
		$("#btnWed").hide();
		$("#btnThu").hide();
		$("#btnFri").hide();
		$("#monthlyBlock").hide();
		// $("#recurringMonthStartDate").hide();
		// $("#recurringMonthsDuration").hide();
		// $("#recurringMonthType").hide();
		// $("#recurringMonthDate").hide();
		// $("#recurringMonthFrequency").hide();
		// $("#recurringMonthDay").hide();
		$("#followUpAssigned").hide();
		$("#urgent").hide();
		$("#completed").hide();
		$("#editCRMActivity").show();
		$("#setSelf").hide();
		$("#customer_choice_label").hide();

		// $("#btnMinimize").hide();
		
		$("#popupbg").fadeOut(100);

		return;

	}

	function hide_dialog() {
		
		document.getElementById("message").style.display="none" ;  
		document.getElementById("crm_wrapper").style.display="none";

	}

	function refresh_page() {

		location.reload();

	}

	function closeSchedule() {

		document.getElementById("showSchedule").style.display="none";
	}

	function setDisplay() {

		document.getElementById("message").style.display="block";
		document.getElementById("message").style.position="fixed"; 
		document.getElementById("message").style.backgroundColor = "none";
		document.getElementById("message").style.border = "2px #ccc solid;";
		document.getElementById("message").style.margin="0px"; 
		document.getElementById("message").style.width=visualViewport.width/2 + "px";
		document.getElementById("message").style.left=visualViewport.width/4 + "px"; 
		document.getElementById("message").style.zIndex=99999991;
		setCRMWrapper();
		return;
	}

	function setPrefContainer() {

		document.getElementById("pref_container").style.position="fixed"; 
		document.getElementById("pref_container").style.backgroundColor = "none";
		document.getElementById("pref_container").style.border = "none";
		document.getElementById("pref_container").style.margin="0px"; 
		document.getElementById("pref_container").style.top=visualViewport.height/4.5 + "px"; 
		document.getElementById("pref_container").style.left=visualViewport.width/4 + "px"; 
		document.getElementById("pref_container").style.width=visualViewport.width/2 + "px";
		$("#pref_container").fadeIn(750);

		setCRMWrapper();
		return;

	}

	function setTrayContainer() {

		document.getElementById("tray_container").style.position="fixed"; 
		document.getElementById("tray_container").style.backgroundColor = "none";
		document.getElementById("tray_container").style.border = "none";
		document.getElementById("tray_container").style.margin="0px"; 
		document.getElementById("tray_container").style.top=visualViewport.height/6 + "px"; 
		document.getElementById("tray_container").style.left=visualViewport.width/4 + "px"; 
		document.getElementById("tray_container").style.width=visualViewport.width/2 + "px";
		document.getElementById("message").style.zIndex=99999991;
		$("#tray_container").fadeIn(750);

		setCRMWrapper();
		return;

	}	

	function setCRMWrapper() {

		document.getElementById("crm_wrapper").style.display="inline-block";
		document.getElementById("crm_wrapper").style.position="fixed"; 
		document.getElementById("crm_wrapper").style.width="150%";
		document.getElementById("crm_wrapper").style.height="150%";
		document.getElementById("crm_wrapper").style.top="0px";
		document.getElementById("crm_wrapper").style.left="0px";
		document.getElementById("crm_wrapper").style.border="0px";
		document.getElementById("crm_wrapper").style.borderRadius="0px";
		document.getElementById("crm_wrapper").style.background="rgba(81,45,109,0.85)";
		document.getElementById("crm_wrapper").style.zIndex=99999992;

		return;

	}

	function display_crm_form() {

	}

	function crm_open_add_mode(memberID) {

		reset_crm_form();
	
        $('body').css("overflow", "hidden");

        $('#crmNotes').val('');

        var d = new Date();

        var curr_day = d.getDate();
        var curr_month = d.getMonth();
        var curr_year = d.getFullYear();

        var curr_hour = d.getHours();
        var curr_min = d.getMinutes();
        var curr_sec = d.getSeconds();
        if (curr_min < 10) { curr_min = "0" + curr_min;}
        if (curr_hour < 10) { curr_hour = "0" + curr_hour;}
        
        var ampm = curr_hour >= 12 ? 'pm' : 'am';

        MyDateString = ('0' + d.getDate()).slice(-2) + '/' + ('0' + (d.getMonth()+1)).slice(-2) + '/' + d.getFullYear();

        curr_month++ ; // In js, first month is 0, not 1
        year_2d = curr_year.toString().substring(2, 4);

		/* get customer from url if one exists */
		custID = "";
		crmID = "";
		var queryString = window.location.search;
		var urlParams = new URLSearchParams(queryString);
		var custID = urlParams.get('editcontactsID');

		/* get order from url if one exists */
		qo_id = "";
		var queryString = window.location.search;
		var urlParams = new URLSearchParams(queryString);
		var qo_id = urlParams.get('id');
		qo_flag = "";

		if (url.includes("quotes")) { var qo_flag = "quote" ;}
		if (url.includes("orders")) { var qo_flag = "order" ;}

		if (custID) {

			$("#customer_block").hide();
			$("#customer_choice_label").show();
			$("#company_id").val(custID);

			var action = "get_customer_from_id";

			$.ajax({
				type: "POST",
				data: {"action":action,"customer_id":custID},
				url: ajax_path,
				success: function(data_resp){

					$("#customer_choice_label").val(data_resp);
					$("#contactNames").show();
					getContactsForCustomer();
					$(".crmpu_right").show();

				}

			});
			
		}

		if (qo_flag) {

			$("#customer_block").hide();
			$("#customer_choice_label").show();
			$("#company_id").val(custID);

			var action = "get_customer_from_qo";

			$.ajax({
				type: "POST",
				data: {"action":action,"qo_flag":qo_flag,"qo_id":qo_id},
				url: ajax_path,
				success: function(data_resp){

					var actionData = JSON.parse(data_resp);
					$("#customer_choice_label").val(actionData['company']);
					$("#company_id").val(actionData['contactsID']);
					$("#contactNames").show();
					getContactsForCustomer();
					$(".crmpu_right").show();

				}

			});
			
		}

        $('#activity_id').val("");
        $("#crmDateDisplay").val(MyDateString + " " + curr_hour + ":" + curr_min + ampm)
        $("#customer_choice").attr("disabled", false);
        $("#crmNotes").attr("disabled", false);

        $(".follow_up_title").html("<h2>Action <small> ** will create reminder and notify **</small></h2>");

        $('#followUpDate').val(MyDateString);
        $('#followUpDateOriginal').val(MyDateString);
        $('#followUpTime').val("09:30");
        $('#followUpTimeOriginal').val(curr_hour + ":" + curr_min);
        $('#followUpNotes').val('');
        $('#followUpPriority').val('Medium').change();
        $('#priorityOriginal').val('Medium');
        $("#followUpStage").val('1').change();
        $("#stageOriginal").val('1').change();

        var $el 		= $(".crmcustselect");
        var crmType		=	$(this).data('crm-type');
        var crmTypeIs3	=	$(this).data('crm-type2');
        var crmTypeIs	=	$(this).data('crm-type2');

        $('#followUpAction').val(crmTypeIs3).change();
        $("#actType").text($(this).data('crm-type2'));

        $("#addCRMActivity").text('ADD');
        $("#addCRMActivity").prop('disabled', false);
        $("#addCRMActivity").show();
        $("#editCRMActivity").show();
        $("#editCRMActivityClose").hide();

		if (!custID) {

			$("#customer_choice").val(crmID).change();
		}
        
        $("#crmTypeLabel").html(crmType);
        $("#crmType").val(crmTypeIs3);
        $("#crmTypeIs").val(crmTypeIs);
        $("#extra_info").hide();
        $("#new_crm_task").val("");
        $("#new_crm_task").attr("placeholder", "Add contact reason");
        
        if (typeof quoteref != "undefined") { 
            $("#crmorderID").val($("#quoteref").val());
			$("#customer_choice").val(contactsID.value).change();
			$("#qo_value").val("Quote").change();
            quote_link = "<a href='../quotes/?add=&id=" + quoteref.value + "&stage=2' target='_blank'>Quote No:</a>";
        } else {
            quote_link = "Quote No:";
        }
        $("#quote_label").html(quote_link);

        if (typeof orderref != "undefined") {
            $("#crmorderID").val($("#orderref").val());
			$("#customer_choice").val(contactsID.value).change();
			$("#qo_value").val("Order").change();
            order_link = "<a href='../orders/?add&id=" + orderref.value + "&stage=2' target='_blank'>Order No:</a>";
        } else {
            order_link = "Order No:";
        }
        $("#order_label").html(order_link);	

		$("#followUpAssigned").val($("#member_id").val()).change();
       
        $("#contactNames").empty();
        $("#contactName").val("");
        $("#contactPhone").val("");
        $("#contactEmail").val("");
        $("#contactProfile").val("");
       
        $('#followUpDate').attr("disabled", false);
        $('#followUpTime').attr("disabled", false);
        $("#followUpNotes").attr("disabled", false);
        $("#followUpAction").attr("disabled", false);
        $("#followUpAssigned").attr("disabled", false);
        $("#followUpPriority").attr("disabled", false);
        $("#followUpStage").attr("disabled", false);
        $('#crmorderID').attr("disabled", false);
        $('#crmquoteID').attr("disabled", false);
        $("#customerProfile").attr("disabled", false);

		action = "get_custom_trays";
		tray_id = 0;
		var tray_to_show = "tray_" + tray_id;
		$("#" + tray_to_show).prop( "checked", true) ;
		$("#followUpActionExtra").empty();

		var selField = document.getElementById("followUpActionExtra");
		var opt = document.createElement('option');
		opt.disabled = true;	

		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(data_resp){
				var actionData = JSON.parse(data_resp);
				var iLimit = actionData.length;

				/* add to the followUpActionExtra select options */
				var is_system = 1;
				var selField = document.getElementById("followUpActionExtra");

				var opt = document.createElement('option');
				opt.value = 0;
				opt.innerHTML = "Select a custom tray...";
				opt.selected = true;
				opt.disabled = true;
				selField.appendChild(opt);

				for (iCnt = 0; iCnt < iLimit; iCnt++ ) {
					var str_full_item = actionData[iCnt].split(";");

						var opt = document.createElement('option');
						opt.value = str_full_item[0];

						/* if the selected one matches a value, get its is_system setting */
						if (tray_id === str_full_item[0]) { is_system = str_full_item[2]; }

						opt.innerHTML = str_full_item[1];

						selField.appendChild(opt);
						
				}

				if (is_system == 0) { $(".toggletrays").click(); } 

				$("#followUpActionExtra").val(tray_id).change();
			}

		});

		/* get the initials of people that can be allocated to this activity */

		getSelfInitials();
		getFullTeamInitials();
		getNonTeamInitials();

		$('div[class="profile-point"]').each(function(index,item){ $(".profile-point").hide(); });

		$("#contactNames").hide(); 
		$(".crmpu_right").hide(); 
		$("#setSelf").show();

		$("#edit_profile").hide(); 
		$("#save_profile").hide(); 

		if ($("#customer_choice").val() != null) { 
			$("#contactNames").show(); 
			$(".crmpu_right").show(); 
			$("#edit_profile").show(); 
		}

		$(".completed").hide();
		show_crm_popup();		

    }

	function markActivityComplete(activityID) {

		$(".calendarTab").off("click");

		var txtMessage = "Are you sure you want to mark this activity as complete?<br><br>";    
		var buttons = "<button class=btnCrm onclick=mark_activity_complete(" + activityID + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=refresh_page()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();

		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function mark_activity_complete(activityID) {

		var action = "mark_activity_complete";

		$.ajax({
			type: "POST",
			data: {"activity_id":activityID,"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	
	}

	function cancelActivity(activityID) {

		var txtMessage = "Are you sure you want to cancel this activity?<br><br>";    
		var buttons = "<button class=btnCrm onclick=cancelActivityConfirm(" + activityID + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=refresh_page()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();
		
		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function cancelActivityConfirm(activityID) {

		var action = "cancel_activity";

		$.ajax({
			type: "POST",
			data: {"activity_id":activityID,"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	
	}	

	function cancelAllActivity() {

		var activity_id = $("#activity_id").val();

		var txtMessage = "Are you sure you want to cancel every activity in this series (including this one)?<br><br>";    
		var buttons = "<button class=btnCrm onclick=cancelAllActivityConfirm(" + activity_id + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=hide_dialog()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();
		
		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function cancelAllActivityConfirm(activityID) {

		var action = "cancel_all_activity";

		$.ajax({
			type: "POST",
			data: {"activity_id":activityID,"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	
	}	

	function cancelFutureActivity() {

		var activity_id = $("#activity_id").val();

		var txtMessage = "Are you sure you want to cancel every future activity in this series?<br><br>";    
		var buttons = "<button class=btnCrm onclick=cancelFutureActivityConfirm(" + activity_id + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=hide_dialog()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();
		
		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function cancelFutureActivityConfirm(activityID) {

		var action = "cancel_future_activity";

		$.ajax({
			type: "POST",
			data: {"activity_id":activityID,"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	
	}

	function cancelRecurrance() {

		var activity_id = $("#activity_id").val();

		var txtMessage = "Are you sure you want to cancel the recurrance for this activity?<br><br>";    
		var buttons = "<button class=btnCrm onclick=cancelRecurranceConfirm(" + activity_id + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=hide_dialog()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();
		
		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function cancelRecurranceConfirm(activityID) {

		var action = "cancel_recurrance";

		$.ajax({
			type: "POST",
			data: {"activity_id":activityID,"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	
	}


	function changeFollowUpBy() {

		var memberID = $("#follow_up_by_id").val();

		var action = "change_follow_up_by";

		$.ajax({
			type: "POST",
			data: {"member_id":memberID,"action":action},
			url: ajax_path,
			success: function(resp){
				location.reload();
			}

		});	

	}

	function changeToMember(memberID) {

		var action = "change_follow_up_by";

		$.ajax({
			type: "POST",
			data: {"member_id":memberID,"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	

	}

	function show_hide_completed(flag) {

		var action = "set_crm_show";

		$.ajax({
			type: "POST",
			data: {"flag":flag,"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	

	}

	function show_to_do() {

		var action = "show_to_do";

		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	

	}

	function show_range(range) {

		var action = "set_crm_range";

		$.ajax({
			type: "POST",
			data: {"range":range,"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});	

	}

	function edit_crm_activity(activityID, speed) {

		reset_crm_form();
		var recurring_end_date = "";

		$("#activity_id").val('0');

		/* use ajax to get all the data into an array and return it here */

		if ($("#message").css("display") != "none" && $("#message").css("display") != undefined) {
			$("#crm_popup_block").hide();
			$("#crmBlockModal").addClass("hidden");
			return false;
		} 

		if (!activityID) { var activityID = $('#activity_id').val(); }
		action = "count_series";

		$("#btnMakeRecurring").hide();
		$("#recurring_icon").hide();
		$.ajax({
			type: "POST",
			data: {"activity_id":activityID,"action":action},
			url: ajax_path,
			success: function(resp){
				var seriesTotalArray = resp.split(',');
				var seriesTotal = seriesTotalArray[0];
				var end_date = seriesTotalArray[2];
				if (seriesTotal !=0) {
					$("#btnMakeRecurring").show();
					$("#recurring_icon").show();
					$("#schedule").show();
				} else {
					$("#btnMakeRecurring").show();
					$("#schedule").hide();
					$("#recurring_icon").show();
				}
				
			}

		});

		/* elements when a recurring task */
		
		action = "get_recurring_end_date";

		$.ajax({
			type: "POST",
			data: { "activity_id": activityID, "action": action },
			url: ajax_path,
			success: function (resp) {
				var recur_data = resp.split(',');
				end_date = recur_data[0];
				series_type = recur_data[1];
				var ed = new Date(end_date);
				recurring_end_date = ed;
				var monthName = "";
				var monthName = months[ed.getMonth()];
				var fullYear = ed.getFullYear();
				var nth = "";
				switch (ed.getDate()) {
					case 1:
					case 21:
					case 31:
						nth = "st";
						break;
					case 2:
					case 22:
						nth = "nd";
						break;
					case 3:
					case 23:
						nth = "rd";
						break;

					default:
						break;
				}
				recurring_message = "";
				if (series_type == "day") {
					recurring_message = "This activity repeats every day until " + ed.getDate() + nth + " " + monthName + " " + fullYear;
				}
				if (series_type == "week") {
					recurring_message = "This activity repeats every week until " + ed.getDate() + nth + " " + monthName + " " + fullYear;
				}
				if (series_type == "month") {
					recurring_message = "This activity repeats every month until " + ed.getDate() + nth + " " + monthName + " " + fullYear;
				}

				$("#recur_info").html(recurring_message);

			}

		});

		action = "get_crm_task";
		storedContact = "";
		var recurring_message = "";

		$.ajax({
			type: "POST",
			data: {"activity_id":activityID,"action":action},
			url: ajax_path,
			success: function(resp){

				var newData = JSON.parse(resp);

				var activity_type = newData.activityType;
				var activity_type_label = "Activity Notes";

				if (newData.followUpType != "") { follow_up_type = newData.followUpType; } else {follow_up_type = newData.activityType; }

				if (activity_type === "Note") { activity_type_label = "Notes";}

				files_list = "";
				file_link= "";
				files_list_array = "";
				$(".fileTable").html("");

				if (newData.activityFile) {

					files_list = newData.activityFile.replace(/\|/g, ",");
					files_list_array = files_list.split(',');

					for (iFile = 0; iFile < files_list_array.length; iFile++) {

						file_icon = getIcon(files_list_array[iFile]);

						if (files_list_array[iFile]) { 
							file_link = file_link + "<a href='../crmfiles/" + files_list_array[iFile] + "' target='_blank'>" + file_icon + "</a> ";
						}

					}

					
					$(".fileTable").html(file_link);
					$("#view_files").show();
				}
				
				$('#action_type').val("edit");
				
				$('#activity_id').val(newData.activityID);
				$('#activity_id_file').val(newData.activityID);
				var activity_added = newData.activity_added_date + " " + newData.activity_added_time.replace(/^0+/, '');

				$('#date_activity_added').val(activity_added.toLowerCase());
				$('#followUpDate').val(newData.follow_up_date);
				$('#followUpTime').val(newData.follow_up_time);
				$('#followUpDateOriginal').val(newData.follow_up_date);
				$('#followUpTimeOriginal').val(newData.follow_up_time);

				if (newData.contactsCompany) { 
					$("#customer_choice").hide();
					$("#customer_choice_label").show();
					$("#customer_choice_label").val(newData.contactsCompany);
					$("#contactNames").show();
					$("#contactNames").val(newData.contactPersonID).change();
					$("#company_id").val(newData.contactsID);
					$("#customer_block").hide();
					getContactsForCustomer();
				} else {
					$("#customer_choice").show();
					$("#customer_choice_label").val('');
					$("#customer_choice_label").hide();
					$("#contactNames").hide();
					$("#customer_block").show();
				}
				
				$("#crmTypeIs").val(newData.activityType);
				$("#crmDateDisplay").val(newData.activity_added_date);
				var assignees = JSON.parse(newData.followUpAssignees);
				$("#followUpAssigned").val(assignees).change();
				$("#followUpStage").val(newData.followUpStage).change();
				$("#stageOriginal").val(newData.followUpStage);
				$("#contactPersonID").val(newData.contactPersonID);
				$("#when_4000").hide();
				$("#when_4000_label").show();

				$("#contactPhone").val(newData.contactsTelephone);

				getMemberInits(newData.activityID, assignees);

				if (newData.contactsID == '0') { 
					$("#company_id").val(newData.contactsID); 
					$("#customer_choice_label").show();
					$("#customer_choice_label").val('Self');
					$("#customer_choice").hide();
					$("#contactNames").show();
					$("#customer_block").hide();
				}
				
				getCRMContact();

				$("#crmquoteID").val('');
				$("#crmorderID").val('');
				
				if (newData.quoteID && newData.quoteID != 0) { 
					$("#crmorderID").val(newData.quoteID); 
					$("#crmorderID").show(); 
					$("#qo_value").val("Quote").change();
				}

				if (newData.orderID && newData.orderID != 0) { 
					$("#crmorderID").val(newData.orderID); 
					$("#crmorderID").show();
					$("#qo_value").val("Order").change();
				}

				$("#qo_value").attr("disabled", false); 
				$("#crmorderID").attr("readonly", false);
				if ($("#crmorderID").val()) { 
					$("#qo_value").attr("disabled", true); 
					$("#crmorderID").attr("readonly", true); 
					$("#goToOrder").show();
				}

				$("#customer_choice").attr("disabled", false);

				$("#addCRMActivity").hide();
				$("#editCRMActivity").show();
				$("#cancelCRMActivity").show();
				
				$("#recurring_block").hide();
				$("#recurring_iterations_block").hide();
				$("#recurringFrequencyBlock").hide();
				$("#recurringInformationLabel").hide();

				$("#recurringIterationsStart").hide();
				$("#recurringTimeBlock").hide();
				$("#recurringIterationsBlock").hide();
				$("#recurringMonthChoiceBlock").hide();
				$("#recurringMonthChoiceDayBlock").hide();
				$("#recurringMonthChoiceOrdinalLabelBlock").hide();
				$("#recurringMonthChoiceOrdinalBlock").hide();
				$("#recurringMonthChoiceOrdinalDayBlock").hide();
				$("#recurringDaysBlock").hide();
				$("#recurringAction").hide();
				$("#btnMakeRecurring").show();
				$("#btnCancelRecurring").hide();	
				
				var seriesTypeLabel = "";

				switch (newData.seriesType) {
					case "day":
						seriesTypeLabel = "Daily";
						break;
					case "week":
						seriesTypeLabel = "Weekly";
						break;
					case "month":
						seriesTypeLabel = "Monthly";
						break;
				}

				$(".follow_up_title").html("<h2 style='margin:0px!important;padding:0px!important;'>Action</h2>");

				/* block off certain actions if the activity is completed */

				if (newData.activity_completed_date) {

					$("#btn_completed").addClass("crm_toggle_button_pressed");
					$("#btn_completed").removeClass("crm_toggle_button");
					$("#completed_check").show();

					$('#followUpDate').attr("disabled", true);
					$('#followUpTime').attr("disabled", true);
					$("#followUpNotes").attr("disabled", true);
					$("#followUpAction").attr("disabled", true);
					$("#followUpAssigned").attr("disabled", true);
					$("#followUpPriority").attr("disabled", true);
					$("#followUpStage").attr("disabled", true);
					$('#crmorderID').attr("disabled", true);
					$('#crmquoteID').attr("disabled", true);
					$("#customerProfile").attr("disabled", true);
					$("#quick_date_buttons").hide();
					$("#editCRMActivity").hide();
					$("#editCRMActivityClose").hide();
					$("#crmFileUpload").attr("disabled", true);
					$("#btnCancelMessage").hide();
					$("#btnCancelAllMessage").hide();
					$("#btnCancelFutureMessage").hide();
					$("#btnCancelRecurrance").hide();
					$("#showSchedule").hide();
					$("#make_recurring_button").hide();
					$("#attach_files").hide();
					$("#btnMakeRecurring").hide();
					$("#btnCancelRecurring").hide();
					$("#recurring_icon").hide();

					$("#toggle_assignees_block").hide();

					$("#qo_value").attr("disabled", true);
					$(".ui-datepicker-trigger").hide();

					$("#btnMinimize").hide();

				} else {

					$("#btn_completed").removeClass("crm_toggle_button_pressed");
					$("#btn_completed").addClass("crm_toggle_button");
					$("#completed_check").hide();

					$('#followUpDate').attr("disabled", false);
					$('#followUpTime').attr("disabled", false);
					$("#followUpNotes").attr("disabled", false);
					$("#followUpAction").attr("disabled", false);
					$("#followUpAssigned").attr("disabled", false);
					$("#followUpPriority").attr("disabled", false);
					$("#followUpStage").attr("disabled", false);
					$('#crmorderID').attr("disabled", false);
					$('#crmquoteID').attr("disabled", false);
					$("#customerProfile").attr("disabled", false);
					$("#crmFileUpload").show();	
					$("#editCRMActivity").show();
					$("#showSchedule").hide();
					$("#recurring_icon").show();

					if (newData.activitySeries == newData.activityID) {
						var title="Cancel every activity in this recurring series and make this activity non-recurring."
						$("#btnCancelRecurrance").html("<a type='button' class='button' onclick=cancelRecurrance(" + newData.activityID + ") title='" + title + "'><i class='fa-regular fa-calendar-xmark' style='font-size:2em;'></i></a>");
						$("#btnCancelRecurrance").show();
						$(".recur_info_row").show();
						$("#recur_actions").show();
						
					} else {
						$("#btnCancelRecurrance").hide();
						$("#recur_actions").hide();
					}

				}	
				
				// $(".recur_info_row").hide();
				// $("#recur_toggle_block").show();

				if (newData.activitySeries) {

					$("#btnCancelAllMessage").html("<button type='button' class='button' onclick=cancelAllActivity(" + newData.activityID + ") ><img class='button_icon' src='../images/cancel_all.png' title='Cancel All Activity'></button>");
					$("#btnCancelAllMessage").show();
					$("#btnCancelFutureMessage").html("<button type='button' class='button' onclick=cancelFutureActivity(" + newData.activityID + ") ><img class='button_icon' src='../images/cancel_future.png' title='Cancel Future Activity'></button>");
					$("#btnCancelFutureMessage").show();
					$("#make_recurring_button").hide();
					$("#btnMakeRecurring").hide();
					$("#recurring_icon").hide();
					$("#btnCancelRecurring").hide();
					$("#extra_info").show();
					$(".recur_info_row").show();
					$("#recur_toggle_block").hide();
					$("#recur_actions").show();

					action = "get_series";

					$.ajax({
						type: "POST",
						data: {"activity_id":newData.activityID,"action":action},
						url: ajax_path,success: function(data_resp){
							var scheduleData = "";

							scheduleData 						= "<div style='display:inline-block;width:100%;background-color:#DD58B4;color:#fff;position:relative;'>";
							scheduleData = scheduleData					+ 	"<div style='float:left;text-align:left;padding:12px;font-size:1.25em;position:absolute;top:0;'>";
							scheduleData = scheduleData 				+  "Here is the full list of activities in this " + seriesTypeLabel + " series.";
							scheduleData = scheduleData 			+ "</div>";
							scheduleData = scheduleData 			+ "<div style='float:right;text-align:right;cursor:pointer;padding:12px;font-size:1.25em;'><a onclick=closeSchedule()><i class='fa-solid fa-rectangle-xmark' aria-hidden='true'></i> </a></div>";
							scheduleData = scheduleData 			+ "<br>";
							scheduleData = scheduleData 		+ "</div><br>";

							var scheduleArray = JSON.parse(data_resp);

							for (i = 0; i < scheduleArray.length; i++) {
								scheduleData = scheduleData + scheduleArray[i];
							}

							$("#showSchedule").html(scheduleData);

						}
					
					});						

				} else {

					$("#btnCancelAllMessage").hide();
					$("#btnCancelFutureMessage").hide();
					$("#make_recurring_button").show();

				}

				/* get the tasks for this activity */

				action = "get_activity_tasks";
				$("#tasks_data").empty();
				$("#new_crm_task").val("");

				$.ajax({
					type: "POST",
					data: {"activity_id":newData.activityID,"action":action},
					url: ajax_path,
					success: function(data_resp, count){
						var formData = JSON.parse(data_resp);
						var iLimit = formData.length;
						var div = document.getElementById('tasks_data');
						var countTasks = 0;

						for (iCnt = 0; iCnt < iLimit; iCnt++) {
							div.innerHTML += formData[iCnt];
							countTasks++;
						}

						$("#new_crm_task").attr("placeholder", "Add task...");
						$("#task_count").val(countTasks);
						$("#history_title").show();
						$(".historyscroll").show();
						$("#tasks_data").show();
					}
				
				});			

				/* set the next action, first with system trays */

				var action_code = "tray_" + newData.trayID;
				$("#" + action_code).prop("checked", true);

				var action_code_label = "tray_" + newData.trayID + "_label";

				if (newData.trayID == 1) { $("#tray_1_label").addClass('crm_cs_selected'); }
				if (newData.trayID == 2) { $("#tray_2_label").addClass('crm_cc_selected'); }
				if (newData.trayID == 3) { $("#tray_3_label").addClass('crm_co_selected'); }
				if (newData.trayID == 5) { $("#tray_5_label").addClass('crm_td_selected'); }

				if (newData.trayID > 5) {
					$(".trayrads").hide();
				} else {
					$(".trayrads").show();
				}

				/* now get the list of custom trays and select if set  */

				action = "get_custom_trays";
				$("#followUpActionExtra").empty();
				$('#followUpActionExtra').append($('<option>', { value: 0, text: 'Select a custom tray...' }));
				$('#followUpActionExtra option[value=0]').prop("disabled", true);
				$('#followUpActionExtra option[value=0]').prop("selected", true).change();

				$.ajax({
					type: "POST",
					data: {"action":action},
					url: ajax_path,
					success: function(data_resp){
						var actionData = JSON.parse(data_resp);
						var iLimit = actionData.length;

						/* add to the followUpActionExtra select options */
						
						var selField = document.getElementById("followUpActionExtra");

						for (iCnt = 0; iCnt < iLimit; iCnt++ ) {
							var str_full_item = actionData[iCnt].split(";");
							var opt = document.createElement('option');
							opt.value = str_full_item[0];
							opt.innerHTML = str_full_item[1];
							selField.appendChild(opt);
						}

						if (newData.is_system == 0) { 
							$(".toggletrays").click();
							$(".traydetail").show();
							$(".trayrads").hide();
							$("#followUpActionExtra").val(newData.trayID).change();
						} else {
							$(".trayrads").show();
							$(".traydetail").hide();
						}

					}

				});

				$("#followUpDate").val(newData.follow_up_date).change();
				$("#followUpTime").val(newData.follow_up_time);

				if (newData.followUpPriority == 1) { $("#urgent").prop("checked", true);$("#urgent").html("Marked as URGENT");} 
				if (newData.activityCompleted) { $("#completed").prop("checked", true);}

				if (newData.contactsID == "0") {

					var action 	= "get_self_details";

					var selField = document.getElementById("contactNames");
					var opt = document.createElement('option');
					opt.disabled = true;
					
					selField.appendChild(opt);			
		
					$.ajax({
						type: "POST",
						data: {"member_id":newData.contactPersonID,"action":action},
						url: ajax_path,
						success: function(resp){
							$("#contactNames").empty();
		
							var crmData = JSON.parse(resp);
							var selField = document.getElementById("contactNames");
							var opt = document.createElement('option');
							var str_full_item = crmData[0].split(";");
		
							opt.innerHTML = str_full_item[0];
							opt.value = str_full_item[1];
		
							selField.appendChild(opt);
							var contact = str_full_item[1];
							var phone = str_full_item[2];
							var email = str_full_item[3];

							$("#contactNames").val(newData.contactPersonID).change();
							$("#contactPhone").val(phone);
							$("#contactEmail").val(email);

							$("#phone_block").show();
							$("#email_block").show();

						}
		
					});	
		
				}

				$("#show_attached_files").hide();
				if (newData.activityFile) {$("#show_attached_files").show();}

				/* make the label with the customer name clickable, if not "self" or empty */

				if (!$("#company_id").val() || $("#company_id").val() == "0") { 
					$("#customer_choice_label").removeClass("customer_choice_label") ;
				} else {
					$("#customer_choice_label").addClass("customer_choice_label") ;
				}

				/* is this activity last or second last? */

				var recdate = new Date(recurring_end_date);
				var month = recdate.getMonth() + 1;
				var date = recdate.getDate();
				if (month.toString().length == 1) { month = "0" + month;}
				if (date.toString().length == 1) { date = "0" + date;}

				var last_date_raw = recdate.getFullYear() + "-" + month + "-" + date;
				var fud_string = $("#followUpDate").val();

				var last_date = last_date_raw.toLocaleString("en-GB", { 
					year: "numeric",
					month: "2-digit",
					day: "2-digit",
				  })

				var fu_date_array = fud_string.split("/");
				var fu_date_db = fu_date_array[2] + "-" + fu_date_array[1] + "-" + fu_date_array[0];

				var recurring_message = $("#recur_info").html();

				console.log("LAST IN SERIES " + last_date);
				console.log("THIS FU DATE " + fu_date_db);
				console.log(month.toString().length);

				if (last_date == fu_date_db ) { recurring_message = recurring_message + " - this is the last activity!"; } 

				$("#recur_info").html(recurring_message);

				/* check permissions
				
				  if not the creator or an asignee, block:

				  	- files from being uploaded
					- new notes from being added
					- existing notes from being editied
					- assigness list changed
					- activity being completed or saved
				
				*/				  

				action = "check_permissions";

				$.ajax({
				  type: "POST",
				  data: {"action":action, "activity_id":newData.activityID},
				  url: ajax_path,
				  success: function(resp){

					  console.log(resp);

					  if (!resp) {
						  $("#editCRMActivity").hide();
						  $("#completed_label").hide();
						  $("#crmFileUpload").prop("disabled", true);
						  $("#toggle_assigness_label").hide();
						  
					  } else {
						  $("#editCRMActivity").show();
						  $("#completed_label").show();
						  $("#toggle_assigness_label").show();
						  $("#crmFileUpload").prop("disabled", false);
					  }

				  }

			  });

				show_crm_popup(activityID,0,0,speed);
				return false;

			}

		});	

		

	}
	function addCrmTaskFromCal(member_id, activity_date) {

		if ($("#activity_id").val()=='0') { return false; }

		if ($("#message").css("display") != "none") { return; }

		// var activityDate = $('#crmDateDisplay').val();

		var date_full = activity_date.split("-");
		var date_year = date_full[0];
		var date_month = date_full[1];
		var date_day = date_full[2];

		var d = new Date();
		var curr_hour_full = d.getHours();
		var curr_min = d.getMinutes();
		if (curr_min < 10) { curr_min = "0" + curr_min;}
		if (curr_hour_full < 10) { curr_hour_full = "0" + curr_hour_full;}

		var ampm = curr_hour_full >= 12 ? 'pm' : 'am';

		if (curr_hour_full > 12) { curr_hour = (curr_hour_full - 12) ;} else { curr_hour = curr_hour_full; }

		var d = new Date();
		var activity_time = (curr_hour + ':' + curr_min + ampm) ;
		var follow_up_time = curr_hour_full + ':' + curr_min;

		var new_activity_date = date_day + "/" + date_month + "/" + date_year + " " + activity_time.toLowerCase() ;
		var new_follow_up_date = date_day + "/" + date_month + "/" + date_year;

		$('#activity_id').val("");
		$('#crmDateDisplay').val(new_activity_date);
		$('#followUpDate').val(new_follow_up_date);
		$('#followUpTime').val(follow_up_time);
		$("#followUpAssigned").val(member_id).change();
		$("#followUpPriority").val('Medium').change();
		$("#followUpStage").val('1').change();
		$('#crmorderID').attr("disabled", false);
		$('#crmquoteID').attr("disabled", false);
		$("#customerProfile").attr("disabled", false);
		$("#quick_date_buttons").show();		
		$("#customer_choice").val("").change();
		$("#crmNotes").val("");
		$("#followUpNotes").val("");
		$("#followUpAction").val("Call").change();
		$("#crmTypeIs").val("");
		$('#crmNotesLabel').html("Activity Notes:");
		$(".follow_up_title").html("<h2>Action <small> ** will create reminder and notify **</small></h2>");

		$("#contactNames").empty();
		$("#contactPhone").val("");
		$("#contactEmail").val("");
		$("#contactProfile").val("");
		$("#contactName").val("");

		$("#page_title").html("<h1>Add Activity From Calendar</h1>");
		$("#customer_choice").attr("disabled", false);
		$("#crmNotes").attr("disabled", false);

		$("#crmquoteID").val('');
		$("#crmorderID").val('');
		$("#quote_label").html('Quote No:');
		$("#order_label").html('Order No:');

		$("#addCRMActivity").show();
		$("#editCRMActivity").show();
		$("#editCRMActivityClose").hide();
		$("#cancelCRMActivity").hide();
		$("#btnCompletedMessage").html("");
		$("#btnCompletedMessage").hide();
		$("#lblCompletedMessage").hide();
		$("#btnMakeRecurring").hide();
		$("#btnCancelRecurring").hide();
		$("#recurring_block").hide();
		$("#recurring_iterations_block").hide();
		$("#btnCancelMessage").hide();
		$("#btnCancelAllMessage").hide();
		$("#btnCancelFutureMessage").hide();
		$(".fileTable").html("");
		$("#tasks_data").empty();
		$("#new_crm_task").val("");
		$("#new_crm_task").attr("placeholder", "Add contact reason");

		action = "get_custom_trays";
		tray_id = 0;
		var tray_to_show = "tray_" + tray_id;
		$("#" + tray_to_show).prop( "checked", true) ;
		$("#followUpActionExtra").empty();
		var selField = document.getElementById("followUpActionExtra");
		var opt = document.createElement('option');
		opt.disabled = true;
		selField.appendChild(opt);			
		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(data_resp){
				var actionData = JSON.parse(data_resp);
				var iLimit = actionData.length;

				/* add to the followUpActionExtra select options */
				var is_system = 1;
				var selField = document.getElementById("followUpActionExtra");

				for (iCnt = 0; iCnt < iLimit; iCnt++ ) {
					var str_full_item = actionData[iCnt].split(";");
					var opt = document.createElement('option');
					opt.value = str_full_item[0];

					/* if the selected one matches a value, get its is_system setting */
					if (tray_id === str_full_item[0]) { is_system = str_full_item[2]; }

					opt.innerHTML = str_full_item[1];
					selField.appendChild(opt);
				}

				if (is_system == 0) { $(".toggletrays").click(); } 

				$("#followUpActionExtra").val(tray_id).change();
			}

		});

		/* get the initials of people that can be allocated to this activity */

		getSelfInitials();
		getFullTeamInitials();
		getNonTeamInitials();

		$('div[class="profile-point"]').each(function(index,item){ $(".profile-point").hide(); });
		$("#contactNames").hide();
		$(".crmpu_right").hide(); 
		$("#save_profile").hide(); 
		$('#completed').prop('checked', false); 			
		$(".completed").hide();
		$("#btnMinimize").hide();

		show_crm_popup();

	}

	function addCrmTaskFromKan(member_id, tray_id) {

		if ($("#message").css("display") != "none") { return; }

		// if (type === "Visit" ) { type = "Site Visit";}

		$stage = "1";

		// if (type === "to_do") { type = "Call"; $stage = "1";}
		// if (type === "customer_pending") { type = "Call"; $stage = "2";}
		// if (type === "supplier_pending") { type = "Call"; $stage = "3";}
		// if (type === "spl_pending") { type = "Call"; $stage = "4";}

		var d = new Date();

		var date_year = d.getFullYear();
		var date_month = d.getMonth() + 1;
		var date_day = d.getDate();

		if (date_month < 10) { date_month = "0" + date_month;}
		if (date_day < 10) { date_day = "0" + date_day;}

		var curr_hour_full = d.getHours();
		var curr_min = d.getMinutes();
		if (curr_min < 10) { curr_min = "0" + curr_min;}
		if (curr_hour_full < 10) { curr_hour_full = "0" + curr_hour_full;}

		var ampm = curr_hour_full >= 12 ? 'pm' : 'am';

		if (curr_hour_full > 12) { curr_hour = (curr_hour_full - 12) ;} else { curr_hour = curr_hour_full; }

		var d = new Date();
		var activity_time = (curr_hour + ':' + curr_min + ampm) ;
		var follow_up_time = curr_hour_full + ':' + curr_min;

		var new_activity_date = date_day + "/" + date_month + "/" + date_year + " " + activity_time ;
		var new_follow_up_date = date_day + "/" + date_month + "/" + date_year;

		$('#activity_id').val("");
		$('#crmDateDisplay').val(new_activity_date);
		$('#followUpDate').val(new_follow_up_date);
		$('#followUpTime').val(follow_up_time);
		$("#followUpAssigned").val(member_id).change();
		$("#followUpPriority").val('Medium').change();
		// $("#followUpStage").val($stage).change();
		$("#customer_choice").val("").change();
		$('#crmorderID').attr("disabled", false);
		$('#crmquoteID').attr("disabled", false);
		$("#customerProfile").attr("disabled", false);
		$("#quick_date_buttons").show();
		$("#crmFileUpload").show();
		$("#crmNotes").val("");
		$("#followUpNotes").val("");
		// $("#followUpAction").val(type).change();
		$("#crmTypeIs").val("");
		$('#crmNotesLabel').html("Activity Notes:");
		$(".follow_up_title").html("<h2>Action <small> ** will create reminder and notify **</small></h2>");

		$("#contactNames").empty();
		$("#contactPhone").val("");
		$("#contactEmail").val("");
		$("#contactProfile").val("");
		$("#contactName").val("");

		$("#page_title").html("<h1>Add Activity From Tray</h1>");
		$("#customer_choice").attr("disabled", false);
		$("#crmNotes").attr("disabled", false);

		$("#crmquoteID").val('');
		$("#crmorderID").val('');
		$("#quote_label").html('Quote No:');
		$("#order_label").html('Order No:');

		$("#addCRMActivity").show();
		$("#editCRMActivity").show();
		$("#editCRMActivityClose").hide();
		$("#cancelCRMActivity").hide();
		$("#btnCompletedMessage").html("");
		$("#btnCompletedMessage").hide();
		$("#lblCompletedMessage").hide();
		$("#btnMakeRecurring").hide();
		$("#btnCancelRecurring").hide();
		$("#recurring_block").hide();
		$("#recurring_iterations_block").hide();
		$("#btnCancelMessage").hide();
		$("#btnCancelAllMessage").hide();
		$("#btnCancelFutureMessage").hide();
		$(".fileTable").html("");
		$("#tasks_data").empty();
		$("#new_crm_task").val("");
		$("#new_crm_task").attr("placeholder", "Add contact reason");

		action = "get_custom_trays";
		var tray_to_show = "tray_" + tray_id;
		$("#" + tray_to_show).prop( "checked", true) ;

		var action_code_label = "tray_" + tray_id+ "_label";

		if (tray_id == 1) { $("#tray_1_label").addClass('crm_cs_selected'); }
		if (tray_id == 2) { $("#tray_2_label").addClass('crm_cc_selected'); }
		if (tray_id == 3) { $("#tray_3_label").addClass('crm_co_selected'); }
		if (tray_id == 5) { $("#tray_5_label").addClass('crm_td_selected'); }

		$("#followUpActionExtra").empty();
		var selField = document.getElementById("followUpActionExtra");
		var opt = document.createElement('option');
		opt.disabled = true;
		selField.appendChild(opt);			
		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(data_resp){
				var actionData = JSON.parse(data_resp);
				var iLimit = actionData.length;

				/* add to the followUpActionExtra select options */
				var is_system = 1;
				var selField = document.getElementById("followUpActionExtra");

				for (iCnt = 0; iCnt < iLimit; iCnt++ ) {
					var str_full_item = actionData[iCnt].split(";");
					var opt = document.createElement('option');
					opt.value = str_full_item[0];

					/* if the selected one matches a value, get its is_system setting */
					if (tray_id === str_full_item[0]) { is_system = str_full_item[2]; }

					opt.innerHTML = str_full_item[1];
					selField.appendChild(opt);
				}

				if (is_system == 0) { $(".toggletrays").click(); } 

				$("#followUpActionExtra").val(tray_id).change();
			}

		});

		/* get the initials of people that can be allocated to this activity */

		getSelfInitials();
		getFullTeamInitials();
		getNonTeamInitials();

		$('div[class="profile-point"]').each(function(index,item){ $(".profile-point").hide(); });
		$("#contactNames").hide();
		$(".crmpu_right").hide(); 
		$("#save_profile").hide(); 	
		$('#completed').prop('checked', false); 	
		$(".completed").hide();
		$("#btnMinimize").hide();

		show_crm_popup();

	}

	function cal_back() {
	
		var action = "cal_back";

		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});			

	}

	function cal_back_month() {
	
		var action = "cal_back_month";

		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});			

	}
	
	function cal_forward() {
	
		var action = "cal_forward";

		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});			

	}
	
	function cal_forward_month() {
	
		var action = "cal_forward_month";

		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});			

	}

	function cal_today() {
	
		var action = "cal_today";

		$.ajax({
			type: "POST",
			data: {"action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}

		});			

	}

	function changeDisplay(type) {

		var action = "change_crm_display";

		$.ajax({
				type: "POST",
				data: "new_value="+type+"&action="+action,
				url: ajax_path,
				success: function(resp){
					location.reload();
				}
		});
	}

	function allowDrop(ev) {
        
		ev.preventDefault();
		
	}

	function changeFollowUpDate(newDate) {

		var action = "change_task_follow_up_date";
		var activityID = document.getElementById("activity_id_drag").value;
		var activityTime = document.getElementById("activity_time").value;

		$.ajax({
			type: "POST",
			data: "activity_id="+activityID+"&activity_time="+activityTime+"&new_date="+newDate+"&action="+action,
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}
		});
	}

	function crmPipe(activity_id, activity_time) {

		document.getElementById("activity_id_drag").value = activity_id;
		document.getElementById("activity_time").value = activity_time;
		document.getElementById("member_id_drag").value = member_id;

	}

	function crmDragKan(activity_id, start_col) {

		document.getElementById("activity_id_drag").value = activity_id;

	}

	function changeKanCol(new_col_val, new_col_category) {

		activity_id = document.getElementById("activity_id_drag").value;
		action = "change_type_stage";	

		$.ajax({
			type: "POST",
			data: "activity_id="+activity_id+"&new_col_val="+new_col_val+"&new_col_category="+new_col_category+"&action="+action,
			url: ajax_path,
			success: function(resp){
				// alert (resp);
				location.reload();
			}
		});

	}

	function makeRecurring() {

		// document.getElementById("recurring_block").style.display="block";
		// document.getElementById("recurring_block").style.position="fixed"; 
		// document.getElementById("recurring_block").style.backgroundColor = "#D3D3D3";
		// document.getElementById("recurring_block").style.padding = "0" + "px";
		// document.getElementById("recurring_block").style.border = "0" + "px red solid";
		// document.getElementById("recurring_block").style.top=screen.height/4 + "px"; 

		var follow_up_date = document.getElementById("followUpDate").value;
		var follow_up_time = document.getElementById("followUpTime").value;

		var fu_date_array = follow_up_date.split("/");

		var fu_date = new Date(fu_date_array[2], fu_date_array[1]-1, fu_date_array[0]);
		var fu_day = fu_date.getDay();
		fu_date.setMonth(fu_date.getMonth()+1);
		fu_date.setDate(fu_date.getDate() +1);

		/* if daily, add 1, if weekly add 7 */

		fu_full_date = ('0' + fu_date.getDate()).slice(-2) + "/" + ('0' + fu_date.getMonth()).slice(-2) + "/" + fu_date.getFullYear();
		
		switch (fu_day) {
			case "1":
				$("#recurMon").prop( "checked", true) ;
				break;
			case "2":
				$("#recurTue").prop( "checked", true) ;
				break;
			case "3":
				$("#recurWed").prop( "checked", true) ;
				break;
			case 4:
				$("#recurThu").prop( "checked", true) ;
				break;
			case "5":
				$("#recurFri").prop( "checked", true) ;
				break;
			default:
				break;	
		}

		// $("#recurring_block").width(screen.width/2 + "px");
		// $("#recurring_block").css('left', screen.width/4 + "px");
		$('#recurringDate').val(fu_full_date);
		$('#recurringTime').val(follow_up_time);
		// $("#recurring_block").show();
		// $("#recurring_iterations_block").show();
		
		$("#recurringFrequencyBlock").show();
		$("#recurringInformationLabel").show();
		// $("#make_recurring").html('<a class="crm_link" href="#" onclick="cancel_recurring_tasks()" >Cancel recurring</a></div>');
		$("#btnMakeRecurring").hide();
		$("#btnCancelRecurring").show();
		$("#recurringFrequency").val('');
		$("#recurringFrequency").show();
		$("#recurring_buttons").show();
		$("#recurringCancelButton").show();
		$("#recurring_section").show();
		$("#recurringMonthsSelection").show();

	}

	function cancel_recurring_tasks() {
		
		$('#recurringFrequency').val('');
		$('#recurringTimeBlock').hide();
		$('#recurringIterationsBlock').hide();
		$('#recurringAction').hide();
		$('#recurringIterationsStart').hide();
		$('#recurring_buttons').hide();
		$("#recurringFrequency").hide();
		$("#recurringDaysBlock").hide();
		$('#recurringIterations').val('');

		$("#recurringMonthChoiceBlock").hide();
		$("#recurringMonthChoiceDayBlock").hide();
		$("#recurringMonthChoiceOrdinalLabelBlock").hide();
		$("#recurringMonthChoiceOrdinalBlock").hide();
		$("#recurringMonthChoiceOrdinalDayBlock").hide();
		$("#recurringFrequencyBlock").hide();
		$("#recurringInformationLabel").hide();
		$("#recurringMonthsSelection").hide();

		// $("#make_recurring").html('<a class="crm_link" href="#" onclick="makeRecurring()" >Make recurring</a></div>');

		$("#btnMakeRecurring").show();
		$("#btnCancelRecurring").hide();
		$("#recurring_section").hide();
		

		document.getElementById("recurring_block").style.display="none";

	}

	function create_recurring_tasks() {

		var has_error = false;
		
		var frequency = document.getElementById("recurringFrequency").value;
		var iterations_val = document.getElementById("recurringIterations").value;
		var start_date = document.getElementById("recurringDate").value;
		var start_time = document.getElementById("recurringTime").value;
		var recur_mon = document.getElementById("recurMon").checked;
		var recur_tue = document.getElementById("recurTue").checked;
		var recur_wed = document.getElementById("recurWed").checked;
		var recur_thu = document.getElementById("recurThu").checked;
		var recur_fri = document.getElementById("recurFri").checked;
		var month_day_label = document.getElementById("recurringMonthDayLabel").checked;
		var month_day_val = document.getElementById("recurringMonthChoiceDay").value;
		var iterations = parseInt(iterations_val);
		var month_day = parseInt(month_day_val);

		var pattern_label = document.getElementById("recurringMonthPatternLabel").checked;
		var pattern_months_val = document.getElementById("recurringMonthChoicePattern").value;
		var pattern_months = parseInt(pattern_months_val);

		if (frequency === "Day") { var iterations_label = "days"; }
		if (frequency === "Week") { var iterations_label = "weeks"; }
		if (frequency === "Month") { var iterations_label = "months"; }

		/* validation */
		
		if (!iterations || !start_date || !start_time) {
			var txtMessage = "Please ensure all fields are completed.";  
			has_error = true; 
		}

		if (iterations < 1) {
			var txtMessage = "Please enter a valid number of " + iterations_label + ".";  
			has_error = true; 
		}

		if (frequency === "Week" && (!recur_mon && !recur_tue && !recur_wed && !recur_thu && !recur_fri )) {
			var txtMessage = "Please select at least one day.";  
			has_error = true; 
		}

		if (frequency === "Month" && (month_day_label && !month_day) || (month_day_label && month_day < "1") || (month_day_label && month_day > "31")) {
			var txtMessage = "Please enter a valid day of the month.";  
			has_error = true; 
		}

		if (frequency === "Month" && (pattern_label && !pattern_months) || (pattern_label && pattern_months < "1") || (pattern_label && pattern_months > "12")) {
			var txtMessage = "Please enter a valid number of months.";  
			has_error = true; 
		}

		if (!has_error) {

			var txtMessage = "This will create " + iterations + " tasks, are you sure you wish to continue?<br><br>";   

			if (frequency === "Day") { txtMessage = "This will create tasks at the specified time for  " + iterations + " more days, are you sure you wish to continue?<br><br>";   }
			if (frequency === "Week") { txtMessage = "This will create tasks on the specified days for  " + iterations + " more weeks, are you sure you wish to continue?<br><br>";   }
			if (frequency === "Month") { txtMessage = "This will create tasks on the specified days for  " + iterations + " more months, are you sure you wish to continue?<br><br>";   }
			 
			var buttons = "<button class=btnCrm onclick=createRecurringTasksConfirm()> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=hide_dialog()> No </button>";
			var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
			message = txtMessage + btnRow;

		} else {

			var buttons = "<button class=btnCrm onclick=hide_dialog()> OK </button>";
			var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
			message = txtMessage + btnRow;

		}

		setDisplay();
		
		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function getRecurringData(type) {

		var assignees_array = [];

		$(".crm_assignee").each(function () {

			var assignee_raw = $(this).attr('data-id');

			if (assignee_raw != undefined) {
				assignee = '"' + $(this).attr('data-id') + '"';
				assignees_array.push(assignee);

			}
		});

		var activity_id = $("#activity_id").val();

		if (!activity_id) {
			assignees_array.push('"' + $("#crmBy").val() + '"');
		}

		if (assignees_array) { assignees_list = '[' + assignees_array + ']'; }

		/* loop to get the system trays */

		iActions = 5;
		var systemTray = "";
		var nextActionID = "";
		var followUpActionExtra = "";

		for (iCnt = 1; iCnt <= iActions; iCnt++) {

			var tray_row = "#tray_" + iCnt;

			if ($(tray_row).prop("checked")) {
				systemTray = iCnt;
			}

		}

		if (systemTray) { nextActionID = systemTray ;}
		followUpActionExtra = $("#followUpActionExtra").val();
		if (followUpActionExtra) { nextActionID = followUpActionExtra; }

		activity_recur_data = [{
			'frequency'				: 			type,
			'activity_id'			: 			$("#activity_id").val(),
			'contacts_id'			: 			$("#company_id").val(),
			'contact_person_id'		: 			$("#contactNames").val(),
			'qo_value'				: 			$("#qo_value").val(),
			'qo_id'					: 			$("#crmorderID").val(),
			'follow_up_priority'	: 			$("#urgent").val(),
			'next_action_id'		: 			nextActionID,
			'file_list'				: 			$("#crmFileUpload").val(), 
			'follow_up_assignees'	: 			assignees_list
		}];

		return activity_recur_data;

	}

	function createRecurringTasksConfirm() {

		var frequency = document.getElementById("recurringFrequency").value;
		var date = document.getElementById("recurringDate").value;
		var time = document.getElementById("recurringTime").value;
		var iterations = document.getElementById("recurringIterations").value;
		var activity_id = document.getElementById("activity_id").value;
		var contactsID = document.getElementById("customer_choice").value;
		var contactPersonID = document.getElementById("contactNames").value;
		var orderID = document.getElementById("crmorderID").value;
		var quoteID = document.getElementById("crmquoteID").value;
		var activityType = document.getElementById("crmType").value;
		var activityFor = document.getElementById("followUpAssigned").value;
		var followUpPriority = document.getElementById("followUpPriority").value;
		var followUpType = document.getElementById("followUpAction").value;
		var followUpNotes = document.getElementById("followUpNotes").value;
		var activityNotes = "";

		/* get the days selected into an array and include that in the push */

		var dayList = '';

		if (document.getElementById("recurMon").checked) { dayList = dayList + '1' ; } else { dayList = dayList + '0' ; }
		if (document.getElementById("recurTue").checked) { dayList = dayList + ';2' ; } else { dayList = dayList + ';0' ; }
		if (document.getElementById("recurWed").checked) { dayList = dayList + ';3' ; } else { dayList = dayList + ';0' ; }
		if (document.getElementById("recurThu").checked) { dayList = dayList + ';4' ; } else { dayList = dayList + ';0' ;}
		if (document.getElementById("recurFri").checked) { dayList = dayList + ';5' ; } else { dayList = dayList + ';0' ; }

		var dataArray = [];
		dataArray.push(frequency);
		dataArray.push(date);
		dataArray.push(time);
		dataArray.push(iterations);
		dataArray.push(activity_id);
		dataArray.push(contactsID);
		dataArray.push(orderID);
		dataArray.push(quoteID);
		dataArray.push(activityType);
		dataArray.push(activityFor);
		dataArray.push(followUpPriority);
		dataArray.push(followUpType);
		dataArray.push(dayList);
		dataArray.push(contactPersonID);

		/* if monthly, which type was selected, day or ordinal? */

		var monthFrequency = "day";

		if (document.getElementById("recurringMonthDayLabel").checked) {  monthFrequency = "day"; }
		if (document.getElementById("recurringMonthDay").checked) {  monthFrequency = "ordinal"; }

		var dayOfMonth = document.getElementById("recurringMonthChoiceDay").value;
		var ordinal = document.getElementById("recurringMonthChoiceOrdinal").value;
		var ordinalDay = document.getElementById("recurringMonthChoiceOrdinalDay").value;

		var action = "create_recurring_tasks";

		// var action = "";	

		$.ajax({
			type: "POST",
			data: "data_array="+dataArray+"&activity_notes="+activityNotes+"&follow_up_notes="+followUpNotes+"&month_frequency="+monthFrequency+"&day_of_month="+dayOfMonth+"&ordinal="+ordinal+"&ordinal_day="+ordinalDay+"&action="+action,
			// data: {"data_array":dataArray,"activity_notes":activityNotes,"follow_up_notes":followUpNotes,"month_frequency":monthFrequency,"day_of_month":dayOfMonth,"action":action},
			url: ajax_path,
			success: function(resp){
				// prompt(resp, resp);
				location.reload();
			}
		});

		location.reload();

	}

	function show_cal_type(type) {

		action = "set_cal_view_type";

		$.ajax({
			type: "POST",
			data: "cal_view="+type+"&action="+action,
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}
		});

	}

	function showActivities(type) {

		action = "show_activities";

		$.ajax({
			type: "POST",
			data: "activity_type="+type+"&action="+action,
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}
		});

	}

	function showTypes(type) {

		action = "show_types";

		$.ajax({
			type: "POST",
			data: "activity_type="+type+"&action="+action,
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}
		});	

	}

	function snoozeActivityForm() {

		if ($("input[name='btnCompleted']").is(":checked")) { return; }

		var activity_id = $("#activity_id").val();

		if (!activity_id) { return false;}

		action = "snooze_activity";

		$.ajax({
			type: "POST",
			data: "activity_id="+activity_id+"&action="+action,
			url: ajax_path,
			success: function(resp){
				$("#followUpDate").val(resp)
				setWhenColours("snooze");
				// alert(resp);
				// location.reload();
			}
		});	

	}

	function snoozeActivity(activityID) {

		$(".calendarTab").off("click");

		var txtMessage = "Are you sure you want to snooze this activity?<br><br>";    
		var buttons = "<button class=btnCrm onclick=snooze_activity(" + activityID + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=refresh_page()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();

		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function snooze_activity(activityID) {

		$(".calendarTab").off("click");
		$("#message").css("border","none");
		$("#message").css("display","block");

		action = "snooze_activity";

		$.ajax({
			type: "POST",
			data: "activity_id="+activityID+"&action="+action,
			url: ajax_path,
			success: function(resp){
				// location.reload();
			}
		});	

	}

	function minimize_crm() {

		action = "minimize_crm";

		iActions = 5;
		selectedAction = 0;
		systemTray = 0;
		customTray = $("#actions_count").val(); 

		/* loop to get the system trays */

		for (iCnt = 1; iCnt <= iActions; iCnt++) {

			var tray_row = "#tray_" + iCnt;
			var tray_id = $(tray_row).prop("checked");

			if ($(tray_row).prop("checked")) {
				systemTray = iCnt;
			}

		}

		/* loop to get the custom trays - a list of them and also the one selected*/

		var customTrayValue = $("#followUpActionExtra").val(); 
		var customTraysArray = []; 
		var customOptions = $('#followUpActionExtra option');
		$.map(customOptions ,function(customOptions) {
			customTraysArray.push( {
				"text" : customOptions.text,
				"value" : customOptions.value,
			})
		});

		/* loop to get the historic actions values */

		iActionLimit = $("#task_count").val();
		var actions_array = [];

		for (iCnt = 0; iCnt < iActionLimit; iCnt++) {

			row_id = "edit_crm_task" + iCnt;
			edit_crm_timestamp = "edit_crm_timestamp" + iCnt;

			actions_array.push({
				action_val : $("#" + row_id).val(),
				action_timestamp : $("#" + edit_crm_timestamp).html(),
			});

		}

		/* capture new fields directly into the array */

		var field_array = {
			"follow_up_date_original" : $("#followUpDateOriginal").val(),
			"follow_up_time_original" : $("#followUpTimeOriginal").val(),
			"activity_id" : $("#activity_id").val(),
			"logged_by" : $("#crmBy").val(),
			"contact_person" : $("#contactPersonID").val(),
			"action_type" : $("#action_type").val(),
			"company_id" : $("#company_id").val(),
			"customer_choice" : $("#customer_choice").val(),
			"customer_choice_label" : $("#customer_choice_label").val(),
			"set_self" : $("#setSelf").val(),  // always set this to false on both mini and maximize
			"contact_names" : $("#contactNames").val(),
			"qo_value" : $("#qo_value").val(),
			"crm_order_id" : $("#crmorderID").val(),
			"crm_file_upload" : $("#crmFileUpload").val(),
			"date_activity_added" : $("#date_activity_added").val(),
			"new_crm_task" : $("#new_crm_task").val(), 
			// files added already need to be loaded via a loop or lookup
			// all previous tasks need to be loaded via a loop
			/* work out which action button is selected and store that */
			/* system trays and custom trays */
			"system_tray" : systemTray,
			"custom_tray_selected" : customTrayValue,
			"custom_trays" : customTraysArray,
			"follow_up_date" : $("#followUpDate").val(),
			/* all the recurring data */
			"follow_up_assigned" : $("#followUpAssigned").val(),
			"urgent" : $("#urgent").prop("checked"),
			"completed" : $("#completed").prop("checked"),
			"actions_array" : actions_array
			};

		$.ajax({ 
			type: "POST",	
			data: {"data_array":field_array,"action":action},
			url: ajax_path,
			success: function(resp){
				$("#popupbg").hide();
				$("#crm_mini").show();
				
			}
		});	

	}

	function maximize_crm() {
	
		/* get the values from the session variables */

		action = "maximize_crm";

		$.ajax({ 
			type: "POST",	
			data: {"action":action},
			url: ajax_path,
			success: function(resp){
				var formData = JSON.parse(resp);

				$("#activity_id").val(formData['activity_id']);
				$("#followUpDateOriginal").val(formData['follow_up_date_original']);
				$("#followUpTimeOriginal").val(formData['follow_up_time_original']);
				$("#crmBy").val(formData['logged_by']);
				$("#contactPersonID").val(formData['contact_person']);
				$("#action_type").val(formData['action_type']);
				$("#company_id").val(formData['company_id']);
				$("#customer_choice").val(formData['customer_choice']).change();

				if (formData['company_id'] == 0) { 
					customer_label = "Self"; 
					$("#edit_profile").hide();
					$("#save_profile").hide();
					$(".fa-save").hide();
					$("#profile_block").hide();
					profile_block
				} else {
					customer_label = formData['customer_choice_label'];
					$("#edit_profile").show();
					$("#save_profile").hide();
					$(".fa-save").hide();
				}

				$("#phone_block").show();
				$("#email_block").show();
				$("#customer_choice_label").val(customer_label);
				$("#setSelf").val(formData['set_self']),  // always set this to false on both mini and maximize
				$("#setSelf").hide();
				$("#contactNames").val(formData['contact_names']).change();
				$("#qo_value").val(formData['qo_value']).change();
				$("#crmorderID").val(formData['crm_order_id']);
				$("#crmFileUpload").val(formData['crm_file_upload']);
				$("#date_activity_added").val(formData['date_activity_added']);
				$("#new_crm_task").val(formData['new_crm_task']);
				 // files added already need to be loaded via a loop or lookup
				$("#followUpDate").val(formData['follow_up_date']);
				$("#followUpAssigned").val(formData['follow_up_assigned']);

				if (formData['urgent'] == "true") { $("#urgent").prop("checked",true) ;} else { $("#urgent").prop("checked", false)}
				if (formData['completed'] == "true") { $("#completed").prop("checked", true) ;}  else { $("#completed").prop("checked", false)}

				$("#customer_choice_label").show();
				$("#customer_choice").hide();
				$("#customer_block").hide();
			
				if (!$("#qo_value").val()) { 
					$("#qo_value").val("0").change();
				}

				$('#followUpActionExtra').append($('<option>', { value: 0,text :'Select a custom tray...' }));
				$('#followUpActionExtra option[value=0]').prop("disabled", true);

				/* action history */

				actions_array = formData['actions_array'];

				iLimit = actions_array.length;

				for (iCnt = 0; iCnt < iLimit; iCnt++) {

					row_id = "edit_crm_task" + iCnt;
					row_id_time = row_id + "_time";

					$('<textarea />',{
						val: actions_array[iCnt]['action_val'],
						class: "borderless",
						style: "outline:none;border:none;margin:0px;padding:0px;height:auto;",
						id: row_id,
						onkeyup: change_crm_task(' . $task_id . ',' . $iRowCount . ')
					}).appendTo('.historyscroll');

					$('<span />',{
						id: row_id_time,
						html: actions_array[iCnt]['action_timestamp'],
						style: "font-style:italic;color:#999;",
					}).appendTo('.historyscroll');

					$("#" + row_id).next().andSelf().wrapAll("<div class='ordholder' style='width:100%;margin:8px 0px;'></div>");

				}

				// system tray, if selected

				var selected_tray = formData['system_tray'];
				var action_code_label = "#tray_" + selected_tray + "_label";

				$(action_code_label).prop("checked", true);

				switch (selected_tray) { 
					case "1":
						$(action_code_label).css("background-color","#416AC3")
						break;
					case "2":
						$(action_code_label).css("background-color","#DD58B4")
						break;
					case "3":
						$(action_code_label).css("background-color","#5BB554")
						break;
					case "5":
						$(action_code_label).css("background-color","#7042BC")
						break;
				}

				// custom tray, if selected

				/* add the custom trays back to the form */

				custom_items = formData['custom_trays'];
				custom_selected = formData['custom_tray_selected'];
				$.each(custom_items, function (i, item) {
					$('#followUpActionExtra').append($('<option>', { 
						value: item.value,
						text : item.text 
					}));
				});

				$('#followUpActionExtra option[value=' + custom_selected + ']').prop("selected", true);

				var system_tray = formData['system_tray'];

				if (system_tray != 0) {
					$('.trayrads').show();
					$('.traydetail').hide();
				} else {
					$('.trayrads').hide();
					$('.traydetail').show();	
				}

			}
		});	

		$("#crm_mini").hide();
		$("#popupbg").show();

	}

	function save_preferences() {
		
		/* wrap into an array direct from the preferences popup to pass to php */
		/* new items to be added to the end !!! */

		var arrPref = [];
		arrPref.push($("#show_lates").is(":checked"));
		arrPref.push($("#show_recur").is(":checked"));
		arrPref.push($("#show_stages").is(":checked"));
		arrPref.push($("#show_types").is(":checked"));
		arrPref.push($("#show_calls").is(":checked"));
		arrPref.push($("#show_emails").is(":checked"));
		arrPref.push($("#show_notes").is(":checked"));
		arrPref.push($("#show_reminders").is(":checked"));
		arrPref.push($("#show_visits").is(":checked"));
		arrPref.push($("#todo").is(":checked"));	
		arrPref.push($("#crm_completed").is(":checked"));
		arrPref.push($("#show_today").is(":checked"));
		arrPref.push($("#show_to_date").is(":checked"));
		arrPref.push($("#show_future").is(":checked"));
		arrPref.push($("#show_all").is(":checked"));
		arrPref.push($("#radCal").is(":checked"));
		arrPref.push($("#radKan").is(":checked"));
		arrPref.push($("#radTab").is(":checked"));
		arrPref.push($("#show_recur_col").is(":checked"));
		
		action = "save_preferences";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "pref_data":arrPref},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}
		});

	}

	function cancel_save_preferences() {

		$("#pref_container").hide(500);
		$("#crm_wrapper").fadeOut(500);
	}

	function sortCRMCol(col_to_sort) {

		action = "sort_crm_table";

		$.ajax({ 
			type: "POST",	
			data: {"col_to_sort":col_to_sort, "action":action},
			url: ajax_path,
			success: function(resp){
				// location.reload();
			}
		});

	}

	function undoComplete(activity_id) {

		var txtMessage = "Are you sure you want to re-open this activity?<br><br>";  
		var buttons = "<button class=btnCrm onclick=undo_complete(" + activity_id + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=hide_dialog()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();

		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function undo_complete(activity_id) {

		action = "undo_complete";

		$.ajax({ 
			type: "POST",	
			data: {"activity_id":activity_id, "action":action},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}
		});

	}

	function showAll() {
		
		/* wrap into an array direct from the preferences popup to pass to php */

		var arrPref = [];
		arrPref.push(true);
		arrPref.push(true);
		arrPref.push(true);
		arrPref.push(true);

		arrPref.push(true);
		arrPref.push(true);
		arrPref.push(true);
		arrPref.push(true);
		arrPref.push(true);

		arrPref.push(false);	
		arrPref.push(true);

		arrPref.push(false);
		arrPref.push(false);
		arrPref.push(false);
		arrPref.push(true);

		arrPref.push(true);
		arrPref.push(false);
		arrPref.push(false);
		
		action = "save_preferences";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "pref_data":arrPref},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}
		});

	}

	function resetCRMSettings() {

		/* wrap into an array direct from the preferences popup to pass to php */

		var arrPref = [];
		arrPref.push(false);
		arrPref.push(false);
		arrPref.push(false);
		arrPref.push(true);

		arrPref.push(true);
		arrPref.push(true);
		arrPref.push(true);
		arrPref.push(true);
		arrPref.push(true);

		arrPref.push(true);	
		arrPref.push(false);

		arrPref.push(true);
		arrPref.push(false);
		arrPref.push(false);
		arrPref.push(false);

		arrPref.push(true);
		arrPref.push(false);
		arrPref.push(false);
		
		action = "save_preferences";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "pref_data":arrPref},
			url: ajax_path,
			success: function(resp){
				// alert(resp);
				location.reload();
			}
		});		

	}

	function setActionDate(duration) {

		if ($("input[name='btnCompleted']").is(":checked")) { return; }

		current_setting = document.getElementById("followUpDate").value;
		action = "set_activity_date";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "current_setting":current_setting, "duration":duration },
			url: ajax_path,
			success: function(resp){

				$("#followUpDate").val(resp)
				setWhenColours(duration)

			}
		});	

	}

	function setWhenColours(duration) {

		switch (duration) {
			case "today":
				$("#when_1000_label").addClass('crm_date_selected');
				$("#when_2000_label").removeClass('crm_date_selected');
				$("#when_3000_label").removeClass('crm_date_selected');
				$("#when_4000_label").removeClass('crm_date_selected');
			break;
			case "day":
				$("#when_1000_label").removeClass('crm_date_selected');
				$("#when_2000_label").addClass('crm_date_selected');
				$("#when_3000_label").removeClass('crm_date_selected');
				$("#when_4000_label").removeClass('crm_date_selected');				
			break;
			case "week":
				$("#when_1000_label").removeClass('crm_date_selected');
				$("#when_2000_label").removeClass('crm_date_selected');
				$("#when_3000_label").addClass('crm_date_selected');
				$("#when_4000_label").removeClass('crm_date_selected');	
			break;			
			case "snooze":
				$("#when_1000_label").removeClass('crm_date_selected');
				$("#when_2000_label").removeClass('crm_date_selected');
				$("#when_3000_label").removeClass('crm_date_selected');
				$("#when_4000_label").addClass('crm_date_selected'); 
			break;
			}

	}

	function getIcon(filename) {

		var fileExt = filename.split('.').pop();
					
		switch(fileExt) {
			case "docx":
			case "doc":
				img_icon = "icon_doc.png";
				break;
			case "xlsx":
			case "xls":
				img_icon = "icon_xls.png";
				break;
			case "csv":
				img_icon = "icon_csv.png";
				break;
			case "pdf":
				img_icon = "icon_pdf.png";
				break;
			case "png":
				img_icon = "icon_png.png";
				break;
			case "jpg":
				img_icon = "icon_jpg.png";
				break;
			default:
				img_icon = "icon_unk.png";
				break;
		}

		file_icon = "<img src='../images/" + img_icon + "' style='width:40px;'/>";
		return (file_icon);
	}

	function getCRMContact() {

		var action = "get_crm_contact";
		var customer_id_label = $("#company_id").val();
		if (customer_id_label) {
			customer_id = customer_id_label;
		} else {
			customer_id = $("#customer_choice").val();
		}
		var contact_id = $("#contactNames").val();

		if (!contact_id) {contact_id = document.getElementById("contactPersonID").value;}
		if (!contact_id) { return false;}

		 /* only load the contact details if the customer is not self */

		if (customer_id != "0") {

			$.ajax({ 
				type: "POST",	
				data: {"action":action, "contact_id":contact_id },
				url: ajax_path,
				success: function(resp){
					console.log(resp);
					var crmData = JSON.parse(resp);
					iLimit = crmData.length;
					var phone = "";
					var email = "";
					var profile = "";
					var contact = "";
					var name = "";
					var team = "";
					var family = "";
					var pets = "";
					var sport = "";
					var holiday = "";
					var birthday_uk = "";
					var birthday = "";
					var mobile = "";
	
					for (i = 0; i < iLimit; i++) {
	
						str_full_item = crmData[i].split(";");
						phone = str_full_item[0];
						email = str_full_item[1];
						profile = str_full_item[2];
						contact = str_full_item[3];
						name = str_full_item[4];
						family = str_full_item[5];
						pets = str_full_item[6];
						sport = str_full_item[7];
						team = str_full_item[8];
						holiday = str_full_item[9];
						birthday_uk = str_full_item[10];
						mobile = str_full_item[11];
						birthday = str_full_item[12];
						company_phone = str_full_item[13];

						if (birthday = "0000-00-00") { birthday = ""; }

					}

					if (team) {
						var full_sport = sport + " - " + team;
					} else {
						var full_sport = sport;
					}

					if (!phone) {
						$("#contactPhone").val(mobile);
						var phone_to_call = mobile;
					} else {
						$("#contactPhone").val(phone);
						var phone_to_call = phone;
					}

					if (!$("#contactPhone").val()) { 
						$("#contactPhone").val(company_phone);
					}

					phone_to_call = phone_to_call.replace(/\s/g, '');
					phone_to_call = phone_to_call.replace('\(','');
					phone_to_call = phone_to_call.replace('\)','');
					
					$("#contactEmail").val(email);

					if (family) {$("#family_block").show(); $("#family").val(family); } else { $("#family_block").hide(); }
					if (pets) { 
						$("#pets_block").show(); 
						$("#pets").val(pets); 
					} else { 
						$("#pets_block").hide(); 
					}

					$("#email_block").show(); 
					$("#phone_block").show(); 

					if (sport) {$("#sport_block").show(); $("#sport").val(sport); } else { $("#sport_block").hide(); }
					if (team) {$("#team_block").show(); $("#team").val(team); } else { $("#team_block").hide(); }
					if (holiday) {$("#holiday_block").show(); $("#holiday").val(holiday); } else { $("#holiday_block").hide(); }
					if (birthday) {$("#birthday_block").show(); $("#birthday").val(birthday); } else { $("#birthday_block").hide(); }
					if (profile) {$("#profile_block").show(); $("#profile_block").val(profile); } else { $("#profile_block").hide(); }

					$("#contactNames").val(contact_id);
					$("#contactPersonID").val(contact_id);
					$("#call_contact").attr('href', 'tel:' + phone_to_call);
					$("#email_contact").attr('href', 'mailto:' + email);
					$("#edit_profile").show("");

					$("#setSelf").hide();

				}
			
			});	
			
		}

		return;

	}

	function add_crm_task() {

		if ($("#completed").attr("checked", true)) { return ;}

		var activity_id = document.getElementById("activity_id").value;

		if (!activity_id) { return; }

		let key = event.key;

		if (key === "Enter") {

			var task = document.getElementById("new_crm_task").value;

			var action = "add_crm_task";
			var task = document.getElementById("new_crm_task").value;
			var activity_id = document.getElementById("activity_id").value;

			$.ajax({ 
				type: "POST",	
				data: {"action":action, "task":task, "activity_id":activity_id },
				url: ajax_path,
				success: function(resp){
					edit_crm_activity(activity_id, "fast");
				}
			});

		}
	}
	
	function change_crm_task(task_id, row_num) {

		if ($("#completed").attr("checked", true)) { return; }

		let key = event.key;

		if (key === "Enter") {

			var action = "change_crm_task";
			var element = "edit_crm_task" + row_num;

			var task = document.getElementById(element).value;
			var activity_id = document.getElementById("activity_id").value;

			$.ajax({ 
				type: "POST",	
				data: {"action":action, "task":task, "task_id":task_id },
				url: ajax_path,
				success: function(resp){
					edit_crm_activity(activity_id, "fast");
				}

			});

		}

	}

	function deleteCRMTask(task_id, activity_id) {

		var txtMessage = "Are you sure you want to delete this task?<br><br>";    
		var buttons = "<button class=btnCrm onclick=delete_crm_task(" + task_id + "," + activity_id + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=hide_dialog()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();

		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;
		
	}

	function delete_crm_task(task_id, activity_id) {

		action = "delete_crm_task";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "task_id":task_id },
			url: ajax_path,
			success: function(resp){
				// prompt(resp, resp);
				hide_dialog();
				editCrmTask(activity_id);

			}

		});

	}

	function allocateCRMTask(task_id, member_id, activity_id) {

		action = "allocate_crm_task";

		$.ajax({ 
			type: "POST",	
			data: { "action":action, "task_id":task_id, "member_id":member_id },
			url: ajax_path,
			success: function(resp){
				// prompt(resp, resp);
				editCrmTask(activity_id);

			}

		});

	}

	function complete_toggle(task_id, row_id) {

		action = "complete_toggle";
		var activity_id = document.getElementById("activity_id").value;
		var id_to_check = "chkComplete" + row_id;
		var is_checked = document.getElementById(id_to_check).checked;

		$.ajax({ 
			type: "POST",	
			data: { "action":action, "task_id":task_id, "is_checked":is_checked },
			url: ajax_path,
			success: function(resp){
				// prompt(resp, resp);
				editCrmTask(activity_id);

			}

		});

	}

	function alertMe() {
		alert("move item");
	}

	function changeAllocatee(rowCount, task_id) {

		var allocate_row = 'allocatee' + rowCount;
		var allocatee = document.getElementById(allocate_row).value;

		var activity_id = document.getElementById('activity_id').value;
		var action = "allocate_crm_task";

		$.ajax({ 
			type: "POST",	
			data: { "action":action, "task_id":task_id, "member_id":allocatee },
			url: ajax_path,
			success: function(resp){
				// prompt(resp, resp);
				editCrmTask(activity_id);

			}

		});
		
	}

	function show_crm_popup(activity_id, member_id, activity_date, speed) {

		if (activity_date) { 
			
			var fu_date_array = activity_date.split("-");
			var fu_date_year = fu_date_array[0];
			var fu_date_month = fu_date_array[1];
			var fu_date_day = fu_date_array[2];
			var fu_date_display = fu_date_day + "/" + fu_date_month + "/" + fu_date_year;
			$("#followUpDate").val(fu_date_display);
		
		}
		var fade_in = 500;
		if ( speed == "fast" ) { fade_in = 0;}
		$("#popupbg").fadeIn(fade_in);
		return;

	}

	function edit_crm_activity_from_cal(activity_id, speed) {

		show_crm_popup(activity_id, 0, 0, speed);
		

	}

	function add_crm_activity_from_cal(member_id, activity_date) {

		show_crm_popup(0,member_id, activity_date);

	}

	function add_crm_activity_from_menu(member_id) {

		show_crm_popup();

	}

	function close_crm_popup() {

		// reset_crm_form();
		$("#crm_mini").hide();
		refresh_page();

	}
 
	function hideActions() {

		$("#btnCloseActions").hide();
		$("#btnOpenActions").show();
		$("#additional_actions").hide();

	}

	function showActions() {

		$("#btnCloseActions").show();
		$("#btnOpenActions").hide();
		$("#additional_actions").show();

	}

	function hideDates() {

		$("#btnCloseDates").hide();
		$("#btnOpenDates").show();
		$("#show_followup_date").hide();

	}

	function showDates() {

		$("#btnCloseDates").show();
		$("#btnOpenDates").hide();
		$("#show_followup_date").show();

	}	

	function reset_call_type() {

		$("#type_phone").css('color', '#ccc');
		$("#type_email").css('color', '#ccc');
		$("#type_note").css('color', '#ccc');
		$("#type_bell").css('color', '#ccc');
		$("#type_visit").css('color', '#ccc');

		return;

	}

	function getContactsForCustomer() {

		var customer_id_label = $("#company_id").val();

		if (customer_id_label) {
			crmID = customer_id_label;
		} else {
			crmID = $("#customer_choice").val();
		}

        var member_id = $("#crmBy").val();

        if (!crmID) { return ; }

		/* clear previous profile values */

        $("#contactNames").empty();
        $("#contactPhone").val("");
        $("#contactEmail").val("");
        $("#family").val("");		
        $("#pets").val("");		
        $("#sport").val("");		
        $("#holiday").val("");		
        $("#birthday").val("");		
        $("#contactProfile").val("");	

		$("#phone_block").hide();
		$("#email_block").hide();
		$("#family_block").hide();
		$("#pets_block").hide();
		$("#sport_block").hide();
		$("#holiday_block").hide();
		$("#birthday_block").hide();
		$("#contactProfile").hide("");	
		$("#edit_profile").hide("");

        /* get list of all contacts for the selected company if not self (crmID 0) */

        if (crmID == "0" ) {
            
            var action 	= "get_self_details";
            $("#contactNames").empty();
			var member_id = $("#crmBy").val();

            var selField = document.getElementById("contactNames");
            var opt = document.createElement('option');
            opt.disabled = true;
            selField.appendChild(opt);			

            $.ajax({
                type: "POST",
                data: {"member_id":member_id,"action":action},
                url: ajax_path,
                success: function(resp){

                    var crmData = JSON.parse(resp);
                    var selField = document.getElementById("contactNames");
                    var opt = document.createElement('option');
                    var str_full_item = crmData[0].split(";");

                    opt.innerHTML = str_full_item[0];
                    opt.value = str_full_item[1];

                    selField.appendChild(opt);
                    var contact = str_full_item[1];
                    var phone = str_full_item[2];
                    var email = str_full_item[3];

                    $("#contactPhone").val(phone);
                    $("#contactEmail").val(email);

					$("#phone_block").show();
					$("#email_block").show();

                }

            });	

        } else {

            var action 	= "all_contacts";

			var original_contact = $("#contactPersonID").val();

            var selField = document.getElementById("contactNames");
            var opt = document.createElement('option');
            opt.innerHTML = "Select Contact";
            opt.disabled = true;
            opt.selected = true;
            selField.appendChild(opt);

            $.ajax({
                type: "POST",
                data: {"customerID":crmID,"action":action},
                url: ajax_path,
                success: function(resp){
                    var crmData = JSON.parse(resp);
                    iLimit = crmData.length;
                    var selField = document.getElementById("contactNames");
                    var primary_contact = 0;
                    var primary_phone = "";
                    var primary_email = "";
                    var primary_profile = "";

                    for (i = 0; i < iLimit; i++) {

                        var opt = document.createElement('option');
                        var str_full_item = crmData[i].split(";");
                        
                        opt.innerHTML = str_full_item[0];
                        opt.value = str_full_item[1];
                        primary_contact_flag = str_full_item[2];

						var editMode = $("#action_type").val();

                        if ((primary_contact_flag == 1 || iLimit == 1) && !editMode) { 
                            var primary_contact = str_full_item[1];
                            var primary_phone = str_full_item[3];
                            var primary_email = str_full_item[4];
                            var primary_profile = str_full_item[5];

                            var primary_family = str_full_item[6];
                            var primary_pets= str_full_item[7];
                            var primary_sport = str_full_item[8];
                            var primary_holiday = str_full_item[9];
                            var primary_birthday = str_full_item[10];

							$("#contactPersonID").val(primary_contact);
							var contact = $("#contactPersonID").val();
                        	$("#contactNames").val(contact).change();
							$("#edit_profile").show("");
                        } 

                        if (str_full_item[0]) { selField.appendChild(opt); }
                    }

					/* adding or editing? */
					
					var activity_id = $("#activity_id").val();

                    if (!activity_id && primary_contact) {
                        $("#contactNames").val(primary_contact).change();
                        $("#contactPhone").val(primary_phone);
                        $("#contactEmail").val(primary_email);
                        $("#contactProfile").val(primary_profile);

                        $("#family").val(primary_family);
                        $("#pets").val(primary_pets);
                        $("#sport").val(primary_sport);
                        $("#holiday").val(primary_holiday);
                        $("#birthday").val(primary_birthday);

                    }

                    if (activity_id) {
                        var contact = $("#contactPersonID").val();
                        $("#contactNames").val(contact).change();
                    }

                }
            });	

        }

		$("#company_id").val(crmID);
		if ($("#customer_choice").val()) { $("#customer_choice_label").val($("#customer_choice option:selected").text());}

	}

	function create_crm_activity() {

        if ($("#new_crm_task").val() == "") {
            var activity_id = $("#activity_id").val(); 
            crmClass.save_crm_errors(activity_id);
            return false;
        }

        $("form#crmBlockModal").submit(function(e){

        var crmID 		= 	$('#customer_choice').val();	
        // var orderID 	= 	$('#crmorderID').val();
        var quoteID 	= 	$('#crmquoteID').val();
        var crmType		= 	$('#crmType').val();
        //var crmDate		= 	$('#crmDate').val();
        var crmBy		= 	$('#crmBy').val();
        var crmNotes 	= 	encodeURIComponent($('#crmNotes').val());

        if(crmNotes || (crmType == "Reminder")){
        
        	//	var followUpDate		= 	$('#followUpDate').val();
            //var followUpTime		= 	$('#followUpTime').val();
            //var followUpAction		= 	$('#followUpAction').val();
            // var followUpAssigned	= 	$('#followUpAssignedNote').val();
            //var followUpPriority	= 	$('#followUpPriority').val();
            //var followUpNotes 		= 	encodeURIComponent($('#followUpNotes').val());

            //var data = "crmID=" +crmID+"&orderID=" +orderID+"&quoteID=" +quoteID+"&crmType=" +crmType+"&crmDate="+crmDate+"&crmBy="+crmBy+"&crmNotes="+crmNotes+"&followUpDate="+followUpDate+"&followUpTime="+followUpTime+"&followUpPriority="+followUpPriority+"&followUpAction="+followUpAction+"&followUpAssigned="+followUpAssigned+"&followUpNotes=" +followUpNotes;

            var formData = new FormData(this); // NEW

            $.each($("input[type='file']")[0].files, function(i, file) {
                formData.append('file', file);
            });

				$.ajax({ 
					type: "POST",
					action: "create_crm_activity",
					//data: data,
					data: formData, // NEW
					processData: false,// NEW
					cache: false,
					contentType: false, // NEW
					url: ajax_path,
					success: function(resp){
						prompt(resp, resp);
						$(this).prop('disabled', false);
						$("#addCRMActivity").text('ADDING...');
						setTimeout(function() {		$("#crmForm").hide('blind', {}, 400)		}, 1000);
						$('body').css("overflow", "auto");
						// location.reload();
						// editCrmTask(activity_id);
						
					}
				});

            } else {

                alert ('No Notes added')

            } /* end of notes or reminder if */
 
		});

	}

	function update_crm_activity() {


        if ($("#task_count").html() == "0") {
            var activity_id = $("#activity_id").val(); 
            crmClass.save_crm_errors(activity_id);
            return false;
        }
        $("form#crmBlockModal").submit(function(){

            var crmID	= $('#customer_choice').val();	
            var formData = new FormData(this); // NEW

            formData.append("contact_id", crmID);
            
			// $.each($("input[type='file']")[0].files, function(i, file) {
            //     formData.append('file', file);
            // });
			
            // var activity_id = document.getElementById("activity_id").value;
			// var action = "update_crm_activity";
            $.ajax({ 
                type: "POST",	
				data: formData,
                processData: false,// NEW
                cache: false,
                contentType: false, // NEW
                url: "ajaxeditcrmactivity.php",
                success: function(resp){
					prompt(resp,resp);
                }
            });	
        }); 
        location.reload();

	}

	function show_schedule() {

		document.getElementById("showSchedule").style.width=visualViewport.width/2 + "px";
		document.getElementById("showSchedule").style.left=screen.width/18 + "px"; 
		document.getElementById("showSchedule").style.top=screen.width/30 + "px"; 
        $("#showSchedule").show();

	}

	function get_recurring_settings() {

		var frequency = $('#recurringFrequency').val();
        var frequency_label = frequency.toLowerCase() + "s";
        var follow_up_date = document.getElementById("followUpDate").value;
        var follow_up_time = document.getElementById("followUpTime").value;
        var fu_date_array = follow_up_date.split("/");
        var fu_date = new Date(fu_date_array[2], fu_date_array[1]-1, fu_date_array[0]);
        // fu_date.setMonth(fu_date.getMonth()+1);
        var fu_day = fu_date.getDay();
        var fu_today = fu_date.getDate();

        $('#recurringDuration').text(frequency_label);
        
        switch (frequency) {
            case "Day":
                fu_date.setMonth(fu_date.getMonth()+1);
                fu_date.setDate(fu_date.getDate() +1);
                $('#recurringTimeBlock').show();
                $('#recurringIterationsBlock').show();
                $('#recurringAction').show();
                $('#recurringIterationsStart').show();
                $('#recurringDaysBlock').hide();
                $('#recurringIterations').val('30');
                $('#recurringMonthChoiceBlock').hide();
                $('#recurringMonthChoiceDayBlock').hide();
                $('#recurringMonthChoiceOrdinalBlock').hide();
                $('#recurringMonthChoiceOrdinalDayBlock').hide();
                $('#recurringMonthChoiceOrdinalLabelBlock').hide();
                $('#recurringMonthChoicePatternBlock').hide();
                $("#recurringMonthChoicePatternBlockMonths").hide();
                break;
            case "Week":
                fu_date.setDate(fu_date.getDate() +7);
                $('#recurringTimeBlock').show();
                $('#recurringIterationsBlock').show();
                $('#recurringAction').show();
                $('#recurringIterationsStart').show();
                $('#recurringDaysBlock').show();
                $('#recurringIterations').val('12');
                $('#recurringMonthChoiceBlock').hide();
                $('#recurringMonthChoiceDayBlock').hide();
                $('#recurringMonthChoiceOrdinalBlock').hide();
                $('#recurringMonthChoiceOrdinalDayBlock').hide();
                $('#recurringMonthChoiceOrdinalLabelBlock').hide();
                $('#recurringMonthChoicePatternBlock').hide();
                $("#recurringMonthChoicePatternBlockMonths").hide();

                fu_date.setMonth(fu_date.getMonth());
                fu_day = fu_date.getDay();
                switch (fu_day) {
                    case 1:
                        $("#recurMon").prop( "checked", true) ;
                        break;
                    case 2:
                        $("#recurTue").prop( "checked", true) ;
                        break;
                    case 3:
                        $("#recurWed").prop( "checked", true) ;
                        break;
                    case 4:
                        $("#recurThu").prop( "checked", true) ;
                        break;
                    case 5:
                        $("#recurFri").prop( "checked", true) ;
                        break;
                    default:
                        break;	
                    }	
                    fu_date.setMonth(fu_date.getMonth()+1);
                break;
            case "Month":
                fu_date.setMonth(fu_date.getMonth()+2);
                $('#recurringTimeBlock').show();
                $('#recurringIterationsBlock').show();
                $('#recurringAction').show();
                $('#recurringIterationsStart').show();
                $('#recurringDaysBlock').hide();
                $('#recurringIterations').val('6');
                $('#recurringMonthChoiceBlock').show();
                $('#recurringMonthChoiceDayBlock').show();
                $('#recurringMonthChoiceOrdinalBlock').show();
                $('#recurringMonthChoiceOrdinalDayBlock').show();
                $('#recurringMonthChoiceOrdinalLabelBlock').show();
                $('#recurringMonthChoiceDay').val(fu_today);
                $('#recurringMonthChoicePatternBlock').show();
                $("#recurringMonthChoicePatternBlockMonths").show();
                break;
            default:
                $('#recurringTimeBlock').hide();
                $('#recurringIterationsBlock').hide();
                $('#recurringIterationsStart').hide();
                $('#recurringDaysBlock').show();
                $('#recurringAction').show();
                $('#recurringMonthChoiceBlock').hide();
                $('#recurringMonthChoiceDayBlock').hide();
                $('#recurringMonthChoiceOrdinalBlock').hide();
                $('#recurringMonthChoiceOrdinalDayBlock').hide();
                $('#recurringMonthChoiceOrdinalLabelBlock').hide();
                $('#recurringMonthChoicePatternBlock').hide();
                $("#recurringMonthChoicePatternBlockMonths").hide();
                break;
        }

        fu_full_date = ('0' + fu_date.getDate()).slice(-2) + "/" + ('0' + fu_date.getMonth()).slice(-2) + "/" + fu_date.getFullYear();
        $('#recurringDate').val(fu_full_date);

	}

	function show_month_day() {

		$('#recurringMonthChoiceDay').attr( 'disabled', false);
		$('#recurringMonthChoiceDay').css( 'background-color', '#fff');

        $('#recurringMonthChoiceOrdinal').attr( 'disabled', true);
		$('#recurringMonthChoiceOrdinal').css( 'background-color', '#f1f1f1');

        $('#recurringMonthChoiceOrdinalDay').attr( 'disabled', true);
		$('#recurringMonthChoiceOrdinalDay').css( 'background-color', '#f1f1f1');

        $('#recurringMonthChoicePattern').attr( 'disabled', true);
		$('#recurringMonthChoicePattern').css( 'background-color', '#f1f1f1');		

	}

	function show_month_the() {

		$('#recurringMonthChoiceDay').attr( 'disabled', true);
		$('#recurringMonthChoiceDay').css( 'background-color', '#f1f1f1');

        $('#recurringMonthChoiceOrdinal').attr( 'disabled', false);
		$('#recurringMonthChoiceOrdinal').css( 'background-color', '#fff');

        $('#recurringMonthChoiceOrdinalDay').attr( 'disabled', false);
		$('#recurringMonthChoiceOrdinalDay').css( 'background-color', '#fff');

        $('#recurringMonthChoicePattern').attr( 'disabled', true);
		$('#recurringMonthChoicePattern').css( 'background-color', '#f1f1f1');

	}

	function show_month_every() {

		$('#recurringMonthChoiceDay').attr( 'disabled', true);
		$('#recurringMonthChoiceDay').css( 'background-color', '#f1f1f1');

        $('#recurringMonthChoiceOrdinal').attr( 'disabled', true);
		$('#recurringMonthChoiceOrdinal').css( 'background-color', '#f1f1f1');

        $('#recurringMonthChoiceOrdinalDay').attr( 'disabled', true);
		$('#recurringMonthChoiceOrdinalDay').css( 'background-color', '#f1f1f1');

        $('#recurringMonthChoicePattern').attr( 'disabled', false);
		$('#recurringMonthChoicePattern').css( 'background-color', '#fff');	

	}

	function save_crm_activity() {

		/* 
			order no / quote no
			next action and next action extra
			when, including date and time
			assignees
			urgent
			completed
			files
			recurring - added 31/10/2024
		*/
		
		var activity_id = $("#activity_id").val();
		var contact_person_id = $("#contactNames").val();
		var contact_id_label = $("#company_id").val();

		if (contact_id_label) { 
			var contact_id = contact_id_label;
		} else {
			var contact_id = $("#customer_choice").val();
		}

		var qo_num = $("#crmorderID").val();
		var qo_label = $("#qo_value").val();
		var date_added = $("#date_activity_added").val();
		var next_action_extra = $("#followUpActionExtra").val(); 
		var new_action =  $("#new_crm_task").val(); 
		var actions_count = $("#actions_count").val();
		var next_action = 0;
		var next_action_label = "";
		var crm_action_text = $("#crm_action_text").val();
		var follow_up_date = $("#followUpDate").val();

		if (!$("#followUpAssigned").val()) { $("#followUpAssigned").val($("#crmBy").val()); }
		
		var assignees_array = [];

		$(".crm_assignee").each(function() {

			var assignee_raw = $(this).attr('data-id') ;

			if (assignee_raw != undefined) {
				assignee = '"' + $(this).attr('data-id') + '"';
				assignees_array.push(assignee);

			}
		});

		if (!activity_id) {
			assignees_array.push('"' + $("#crmBy").val() + '"');
		}

		if (assignees_array) { new_assignees_list = '[' + assignees_array + ']'; }

		if (next_action_extra) { next_action = next_action_extra;}
		if (next_action == 0) {
			for (iCnt = 1; iCnt <= actions_count; iCnt++) {
				var action_row = "tray_" + iCnt;
				if ($("#" + action_row).prop("checked") === true) { 
					next_action = $("#" + action_row).val(); 
					next_action_label = " - " + $('label[for="'+ $("#" + action_row).attr('id') +'"]').text();
				}
			}

		}

		// if (next_action === 0) { next_action = 5;next_action_label = " - to do";}

		if ($("#urgent").prop("checked") == true) { var priority = 1;} else { priority = 0; }
		if ($("#completed").prop("checked") == true) { var completed = 1;} else { completed = 0; }

		/* validation */

		var check_new_action = 0

		if (!activity_id) { check_new_action = 1 } 

		/* auto populate first action if blank at this point */

		if (check_new_action == 1 & !new_action) { 
			new_action = "Action" + next_action_label;
		}

		has_contact = true;
		has_company = true;
		has_assignees = true;

		var txtMessage = '';

		if (!contact_id ) { has_contact = false;}
		if (!activity_id || activity_id == 0) {has_company = false;}
		if (!assignees_array || assignees_array == "") { has_assignees = false; }

		has_error = false;
		if ((!has_contact && !has_company) || !follow_up_date || !has_assignees || next_action == 0) {
			txtMessage = "<h2>Please ensure you:</h2><ul>";
			txtMessage = txtMessage + "<li>- select a company,</li>";
			txtMessage = txtMessage + "<li>- enter a follow up date,</li>";
			txtMessage = txtMessage + "<li>- specificy a next action and</li>";
			txtMessage = txtMessage + "<li>- assign someone to this activity.</li></ul>";
			has_error = true; 
		}

		if (qo_num && !qo_label) {
			var txtMessage = "Please specify if '" + qo_num + "' is a Quote or an Order.";  
			has_error = true; 
		}

		if (has_error) {
			var buttons = "<button class=btnCrm onclick=hide_dialog()> OK </button>";
			var btnRow = "<div style='text-align:right;margin-right:5px;'>" + buttons + "</div>";
			message = txtMessage + btnRow;
			setDisplay();
			document.getElementById("message").innerHTML="<div class='message_content' >" + message + "</div>" ;
			return false;
		}

		var fu_date_array = follow_up_date.split("/");
		var fu_date_db = fu_date_array[2] + "-" + fu_date_array[1] + "-" + fu_date_array[0];

		var files = $("#multiplefilelist").prop('files');

		var qo_type = "";
		if (qo_label) {qo_type = qo_label.trim(); }

		/* establish what recurring data needs to be sent to ajax, if any */

		/* DAILY */

		var recur_error = false;
		var recur_daily_array = [];
		var recur_weekly_array = [];
		var recur_monthly_array = [];
		var recur_activity_data = "";

		var recur_type = '';
		if ($("#recur_day").prop("checked")) { recur_type = 'day'; }
		if ($("#recur_week").prop("checked")) { recur_type = 'week'; }
		if ($("#recur_month").prop("checked")) { recur_type = 'month'; }

		if (recur_type == 'day' ) {

			if (!$("#recurringDayStartDate").val() || !$("#recurringDayEndDate").val()) { 
				recur_error = true;
				txtMessage = "<br><br>Please ensure you provide a start and an end date when creating daily activities.<br><br>";
			}

			if ($("#recurringDayStartDate").val() > $("#recurringDayEndDate").val()) { 
				recur_error = true;
				txtMessage = "<br><br>Please ensure you provide an end date later than the start date when creating daily activities.<br><br>";
			}			

			if (!recur_error) {

				/* get all the data needed to create a new task */

				recur_activity_data = getRecurringData(recur_type);

				recur_daily_array = [{
					'start_date': $("#recurringDayStartDate").val(),
					'end_date': $("#recurringDayEndDate").val()
				}];

			}

		}

		/* WEEKLY */

		if (recur_type == 'week') {

			var week_start_date = $("#recurringWeekStartDate").val();
			var weeks_duration = $("#recurringWeeksDuration").val();

			var mon = false, tue = false, wed = false, thu = false, fri = false;
			if ($("#btnMon").prop("checked")) { mon = true; }
			if ($("#btnTue").prop("checked")) { tue = true; }
			if ($("#btnWed").prop("checked")) { wed = true; }
			if ($("#btnThu").prop("checked")) { thu = true; }
			if ($("#btnFri").prop("checked")) { fri = true; }

			if (!week_start_date || !weeks_duration) {
				var txtMessage = "<br><br>Please ensure you provide a start date and a duration when creating weekly activities.<br><br>";
				recur_error = true;
			}

			if (!mon && !tue && !wed && !thu && !fri) {
				var txtMessage = "<br><br>Please ensure you select at least one weekday when creating weekly activities.<br><br>";
				recur_error = true;
			}

			if (!recur_error) {

				/* get all the data needed to create a new task */

				recur_activity_data = getRecurringData(recur_type);
				
				recur_weekly_array = [{
					'start' : week_start_date,
					'duration': weeks_duration,
					'mon': mon,
					'tue': tue,
					'wed': wed,
					'thu': thu,
					'fri': fri
				}];

			}

		}

		/* MONTHLY */

		if (recur_type == 'month') {

			var month_start_date = $("#recurringMonthStartDate").val();
			var months_duration = $("#recurringMonthsDuration").val();
			var recur_month_type = $("#recurMonthType").val();
			var month_frequency = $("#recurringMonthFrequency").val();
			var recurring_month_day = $("#recurringMonthDay").val(); 
			var recurring_month_date = $("#recurringMonthDate").val(); 

			if (recur_month_type == "date" && !recurring_month_date) {
				var txtMessage = "<br><br>Please ensure you select a day in the month when a rate of 'Date' is selected.<br><br>";
				recur_error = true;
			}
			if (recur_month_type == "day" && !recurring_month_day) {
				var txtMessage = "<br><br>Please ensure you select a weekday when a rate of 'Day' is selected.<br><br>";
				recur_error = true;
			}
			if (recur_month_type == "day" && !month_frequency) {
				var txtMessage = "<br><br>Please ensure you select a frequency when a rate of 'Day' is selected.<br><br>";
				recur_error = true;
			}
			if (!recur_month_type) {
				var txtMessage = "<br><br>Please ensure you select a rate of either 'Day' or 'Date' when creating monthly activities.<br><br>";
				recur_error = true;
			}
			if (!month_start_date || !months_duration) {
				var txtMessage = "<br><br>Please ensure you provide a start date and a duration when creating monthly activities.<br><br>";
				recur_error = true;
			}
			
			if (!recur_error) {

				/* get all the data needed to create a new task */

				recur_activity_data = getRecurringData(recur_type);

				recur_monthly_array = [{
					'month_start_date': month_start_date,
					'months_duration': months_duration,
					'recur_month_type': recur_month_type,
					'month_frequency': month_frequency,
					'recurring_month_day': recurring_month_day,
					'recurring_month_date': recurring_month_date
				}];

			}

		}
		if (recur_error) { 
			var buttons = "<button class=btnCrm onclick=hide_dialog()> OK </button>";
			var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
			message = txtMessage + btnRow;
			setDisplay();
			document.getElementById("message").innerHTML = "<div class='message_content'>" + message + "</div>";
			return false;
		}

		var form_data = {
			"activity_id":activity_id,
			"contact_id":contact_id,
			"contact_person_id":contact_person_id,
			"qo_num":qo_num, 
			"qo_type":qo_label,
			"date_added":date_added, 
			"next_action":next_action, 
			"crm_action_text":crm_action_text,
			"new_action_text":new_action,
			"follow_up_date":fu_date_db,
			"crm_assignees": new_assignees_list,
			"priority":priority,
			"completed":completed,
			"recur_activity_data": recur_activity_data,
			"recur_daily_array": recur_daily_array,
			"recur_weekly_array": recur_weekly_array,	
			'recur_monthly_array': recur_monthly_array
		};

		var action = "save_crm_activity";
		
		$.ajax({ 
			type: "POST",	
			data: {"action":action, "form_data":form_data},
			url: ajax_path,
			success: function(resp){
				console.log(resp);
				// location.reload();
			}
		});	

		/* now process the files */

		// $("#crmFileUpload").data(files_to_upload);

		var formData = new FormData($("#file_upload")[0]);

		$.ajax({
			url: ajax_file_path,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			async: false,
			success:function(response){
				if (files_to_upload.length > 0) { var saveMessage = "Uploading..." } else { var saveMessage = "Saving..." }
				$("#editCRMActivity").html(saveMessage);
				setTimeout(function () {
					location.reload();
				}, 500);
				
			}
		}); 

	}

	function unsetActionExtra(tray_id) {

		/* don't allow this action if the activity is complete */
		if ($("#completed").prop("checked")) { return false ; }

		selected_action = tray_id;

		$("#tray_1_label").removeClass('crm_cs_selected');
		$("#tray_2_label").removeClass('crm_cc_selected');
		$("#tray_3_label").removeClass('crm_co_selected');
		$("#tray_5_label").removeClass('crm_td_selected');

		if (tray_id == 1) { $("#tray_1_label").addClass('crm_cs_selected'); }
		if (tray_id == 2) { $("#tray_2_label").addClass('crm_cc_selected'); }
		if (tray_id == 3) { $("#tray_3_label").addClass('crm_co_selected'); }
		if (tray_id == 5) { $("#tray_5_label").addClass('crm_td_selected'); }

		$("#followUpActionExtra").val('').change();

	}

	function setRecurringDisplay(period) {

		switch (period) {
			case "day":
				$("#dailyBlock").show();
				$("#weeklyBlock").hide();
				$("#monthlyBlock").hide();
				$("#recur_day_label").addClass('crm_recur_selected');
				$("#recur_week_label").removeClass('crm_recur_selected');
				$("#recur_month_label").removeClass('crm_recur_selected');
				$(".recur_daily_start").css("display", "inline-block");
				$(".recur_daily_end").css("display", "inline-block");
				break;
			case "week":
				$("#dailyBlock").hide();
				$("#weeklyBlock").show();
				$("#monthlyBlock").hide();
				$("#recur_day_label").removeClass('crm_recur_selected');
				$("#recur_week_label").addClass('crm_recur_selected');
				$("#recur_month_label").removeClass('crm_recur_selected');
				break;
			case "month":
				$("#dailyBlock").hide();
				$("#weeklyBlock").hide();
				$("#monthlyBlock").show();
				$("#recur_day_label").removeClass('crm_recur_selected');
				$("#recur_week_label").removeClass('crm_recur_selected');
				$("#recur_month_label").addClass('crm_recur_selected');
				break;
		}

	}

	function showHideRecurring() {
		$("#when_row").hide();
		$("#recur_row").show();
	}

	function goToOrder() {

		var order_id = $("#crmorderID").val();
		var qo_type = $("#qo_value").val();

		if (qo_type == "Order") { window.open("../orders/?add&id=" + order_id + "&stage=2", "_blank"); }
		if (qo_type == "Quote") { window.open("../quotes/?add&id=" + order_id + "&stage=2", "_blank"); }

	}

	function addTray() {

		var tray_name = $("#new_tray").val();
		var tray_back_colour = $("#new_tray_back").val();
		var tray_members = $("#new_tray_members").val();

		strMembers = "";

		if (tray_members) { strMembers = tray_members.toString(); }

		// if (!tray_name) {

		// var txtMessage = "Please provide a name for the new tray?<br><br>";    
		// var buttons = "<button class=btnCrm onclick=hide_dialog()> OK </button>";
		// var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		// message = txtMessage + btnRow;

		// setDisplay();

		// document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;
		
		if (!tray_name) {

			return false;

		} else {

			var action = "add_new_tray";

			$.ajax({ 
				type: "POST",	
				data: {"action":action, "tray_name":tray_name, "tray_back_colour":tray_back_colour, "tray_members":strMembers},
				url: ajax_path,
				success: function(resp){
					location.reload();
					// setTrayContainer();
				}
			});	

		}

		return;

	}

	function deleteCRMTrayCheck(tray_id) {

		var txtMessage = "Are you sure you want to delete this tray?<br><br>";    
		var buttons = "<button class=btnCrm onclick=deleteCRMTray(" + tray_id + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=hide_dialog()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();

		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function deleteCRMTray(tray_id) {

		action = "delete_crm_tray";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "tray_id":tray_id},
			url: ajax_path,
			success: function(resp){
				if (resp == "fail") {

					var txtMessage = "This tray cannot be deleted as it contains activities.<br><br>";    
					var buttons = "<button class=btnCrm onclick=hide_dialog()> OK </button>";
					var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
					message = txtMessage + btnRow;

					setDisplay();

					document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

				} else {

					if ($("#new_tray").val()) { 
		
						addTray();
			
					}
					
					var url = crm_path + "?show_tray_admin";
					window.location = url;
			
					return;
				}
			}
		});	

		return;

	}

	function removeMemberFromTrayCheck(tray_id) {

		var txtMessage = "Are you sure you want to remove yourself as a member of this tray?<br><br>";    
		var buttons = "<button class=btnCrm onclick=removeMemberFromTray(" + tray_id + ")> Yes </button>&nbsp;&nbsp;&nbsp;<button class=btnCrm onclick=hide_dialog()> No </button>";
		var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
		message = txtMessage + btnRow;

		setDisplay();

		document.getElementById("message").innerHTML="<div class='message_content' style='padding-top:50px;'>" + message + "</div>" ;

	}

	function removeMemberFromTrayCheck(tray_id) {

		action = "delete_member_from_tray";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "tray_id":tray_id},
			url: ajax_path,
			success: function(resp){
				location.reload();
			}
		});	

		return;

	}

	function openTrayAdmin() {

		$("#tray_container").show();
		$("#crm_wrapper").show();

	}

	function cancel_save_trays() {

		var url = decodeURI(window.location.href);
		var urlArray = url.split("?");
		location.href = urlArray[0];

	}

	function save_trays() {

		action = "update_trays";

		var tray_count = $("#int_tray_count").val();
		var tray_data = [];

		for (var icnt = 1; icnt < tray_count; icnt++) {

			var trayID = $("#tray_id_" + icnt).val();
			var trayName = $("#tray_name_" + icnt).val();
			var trayMembers = $("#tray_members_" + icnt).val();
			var trayBackColour = $("#tray_back_" + icnt).val();

			if (!trayName) { 

				var txtMessage = "Please ensure all trays have a valid name.<br><br>";    
				var buttons = "<button class=btnCrm onclick=hide_dialog()> OK </button>";
				var btnRow = "<div style='text-align:right;'>" + buttons + "</div>";
				message = txtMessage + btnRow;
				setDisplay();
				document.getElementById("message").innerHTML="<div class='message_content'>" + message + "</div>" ;
				return false;

			}

			strMembers = "";

			if (trayMembers) { strMembers = trayMembers.toString(); }
			
			tray_data.push({
				"tray_id" : trayID,
				"tray_name" : trayName,
				"tray_members" : strMembers,
				"tray_back_colour" : trayBackColour,
			});

		}

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "tray_data":tray_data},
			url: ajax_path,
			success: function(resp){
				location.reload();
			}
		});	

		if ($("#new_tray").val()) { 
		
			addTray();

		}
		
		var url = crm_path + "?show_tray_admin";
		window.location = url;

		return;

	}

	function save_trays_close() {

		save_trays();
		cancel_save_trays();

	}

	function show_hide_completed_activity() {

		var action = "set_cust_comp";

		var url = new URLSearchParams(window.location.search);
		var custID = url.get("editcontactsID");

		$.ajax({ 
			type: "POST",	
			data: { "action": action, "customer_id": custID },
			url: ajax_path,
			success: function(resp){
				location.reload();
			}
		});	

	}

	function chooseWeek() {

		var week_chosen = $("#choose_week").val();
		var action = "choose_week";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "week_chosen":week_chosen},
			url: ajax_path,
			success: function(resp){
				location.reload();
			}
		});	



	}

	function set_complete_toggle() {

		var activity_id = $("#activity_id").val();
		var is_complete = "0";
		var saveMessage = "";
		var newTask = $("#new_crm_task").val();

		if ( $("#completed").prop("checked")) {
			is_complete = "1";
			saveMessage = "Re-opening...";
		} else {
			is_complete = "0";
			saveMessage = "Completing...";
			$(".recur_info_row").css("width", "60%");
		}

		$("#completed_label").html(saveMessage);

		if (!activity_id) { return false; }

		var action = "set_activity_complete";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "activity_id":activity_id,"is_complete":is_complete, "new_task":newTask},
			url: ajax_path,
			success: function(resp){
				console.log(resp);
				setTimeout(function () {
					location.reload();
				}, 500);
				
			}
		});	

	}

	function setSelf() {

		$("#customer_choice").val(0).change();
		$("#setSelf").hide();

	}

	function updateList(e) {

		$('.fa-upload').css({ color: "green" });
		
		const file_to_upload = document.getElementById("crmFileUpload");
		dt.items.add(new File(
			[file_to_upload.value],
			file_to_upload.files
		));
		file_to_upload.files = dt.files;
		for (i = 0; i < dt.items.length; i++) {
		}
		
	}

	function toggleUrgent() {

		if ($("#urgent_label").hasClass('crm_urgent_selected')) {
			$("#urgent_label").removeClass('crm_urgent_selected') ;
		} else {
			$("#urgent_label").addClass('crm_urgent_selected') ;
		}

	}

	function lockCRMTray(tray_id) {

		var action = "private_tray_toggle";

		$.ajax({
			type: "POST",
			data: { "action": action, "tray_id": tray_id },
			url: ajax_path,
			success: function (resp) {
				save_trays();
			}
		});	
		
	}

	function changeDisplayRange(display_range) { 

		action = 'change_display_range';

		$.ajax({
			type: "POST",
			data: { "action": action, "display_range": display_range },
			url: ajax_path,
			success: function (resp) {
				location.reload();
			}
		});	
		
	}

	function toggleCompleted() {

		action = 'toggle_completed';

		$.ajax({
			type: "POST",
			data: { "action": action },
			url: ajax_path,
			success: function (resp) {
				location.reload();
			}
		});	

	}

	function getSelfInitials() {

		action = "get_self_inits";

		$.ajax({
			type: "POST",
			data: { "action": action},
			url: ajax_path,
			success: function (resp) {
				var inits = JSON.parse(resp);

				assignee_id = inits[0]['member_id'];
				assignee_inits = inits[0]['member_initials'];
				back_color = inits[0]['member_back_colour'];
				inits_block = "";
				style = "'display:inline-block;color:#fff;background-color:" + back_color + ";margin:3px;border-radius:6px;padding:6px;cursor:pointer;font-size:0.8em;'";
				on_click = "onclick=crm_remove_assignee(" + assignee_id + ",'" + back_color + "','" + assignee_inits + "')";
				assignee_id = "id=assignee_id_" + assignee_id;
				inits_block = "<span ><a " + assignee_id + " " + on_click + " style=" + style + ">x " + assignee_inits + "</a></span>";
				$("#assignees_list").append(inits_block);
				
			}

		});

	}

	function setRecurringClear() {

		$("#recurringDayStartDate").val("");
		$("#recurringDayEndDate").val("");
		$("#recurringWeekStartDate").val("");
		$("#recurringWeeksDuration").val("");
		$("#btnMon").val([]);
		$("#btnTue").val([]);
		$("#btnWed").val([]);
		$("#btnThu").val([]);
		$("#btnFri").val([]);
		$("#recurringMonthStartDate").val("");
		$("#recurringMonthsDuration").val("");
		$("#recurringMonthFrequency").val("");
		$("#recurringMonthDay").val("");
		$("#recurringMonthDate").val("");
		$("#recurMonthType").val("");

		$("#recur_day_label").removeClass("crm_recur_selected");
		$("#recur_week_label").removeClass("crm_recur_selected");
		$("#recur_month_label").removeClass("crm_recur_selected");

		return;

	}

	function showHistory() {

		action = "get_activity_history";
		$.ajax({
			type: "POST",
			data: { "action": action},
			url: ajax_path,
			success: function (resp) {
				console.log(resp);

				if ($("#lates_list_block").css('display') != "none") { $("#lates_list_block").hide("slide", { direction: "right" }, 250); $("#showLate_label").removeClass("crm_icon_active"); }
				if ($(".crm_self_container").css('display') != "none") { $(".crm_self_container").hide("slide", { direction: "right" }, 250);$("#showSelf_label").removeClass("crm_icon_active");  }

				if ($(".crm_history_container").css('display') == "none") {
					$(".crm_history_container").show("slide", { direction: "right" }, 1000);
					$("#showHistory_label").addClass("crm_icon_active"); 
				} else {
					$(".crm_history_container").hide("slide", { direction: "right" }, 250);
					$("#showHistory_label").removeClass("crm_icon_active"); 
				}
				
				return resp;

			}

		});

	}

	function showSelf() {

		action = "get_self_activity";
		$.ajax({
			type: "POST",
			data: { "action": action},
			url: ajax_path,
			success: function (resp) {

				if ($("#lates_list_block").css('display') != "none") { $("#lates_list_block").hide("slide", { direction: "right" }, 250);  $("#showLate_label").removeClass("crm_icon_active"); }
				if ($(".crm_history_container").css('display') != "none") { $(".crm_history_container").hide("slide", { direction: "right" }, 250);$("#showHistory_label").removeClass("crm_icon_active");  }

				if ($(".crm_self_container").css('display') == "none") {
					$(".crm_self_container").show("slide", { direction: "right" }, 1000);
					$("#showSelf_label").addClass("crm_icon_active"); 
				} else {
					$(".crm_self_container").hide("slide", { direction: "right" }, 250);
					$("#showSelf_label").removeClass("crm_icon_active"); 
				}

				
				return resp;

			}

		});

	}
	function showLates() {

		action = "show_lates";
		$.ajax({
			type: "POST",
			data: { "action": action},
			url: ajax_path,
			success: function (resp) {

				if ($(".crm_history_container").css('display') != "none") {$(".crm_history_container").hide("slide", { direction: "right" }, 250); $("#showHistory_label").removeClass("crm_icon_active"); }
				if ($(".crm_self_container").css('display') != "none") { $(".crm_self_container").hide("slide", { direction: "right" }, 250); $("#showSelf_label").removeClass("crm_icon_active"); }

				if ($("#lates_list_block").css('display') == "none") {
					$("#lates_list_block").show("slide", { direction: "right" }, 1000);
					$("#showLate_label").addClass("crm_icon_active"); 
				} else {
					$("#lates_list_block").hide("slide", { direction: "right" }, 250);
					$("#showLate_label").removeClass("crm_icon_active"); 
				}

			}

		});

		return;

	}

	function openCRMTaskFromEmail(activityID) {

		console.log(activityID);

		action = "set_auto_view";

		$.ajax({
			type: "POST",
			data: { "action": action, "activity_id": activityID},
			url: ajax_path,
			success: function (resp) {
				console.log(resp);
				window.open(crm_path + "/index.php");
			}

		});

	}


	/* start of all the teams data when adding an activity */

	function getSelfInitials() {

		action = "get_self_inits";

		$.ajax({
			type: "POST",
			data: { "action": action},
			url: ajax_path,
			success: function (resp) {
				var inits = JSON.parse(resp);

				assignee_id = inits[0]['member_id'];
				assignee_inits = inits[0]['member_initials'];
				border_color = inits[0]['member_back_colour'];
				image_path = '../images/'+ inits[0]['member_photo'];
				
				photo = "<img class='assignee_photo' src='" + image_path + "' title=" + inits[0]['member_name'] + " />";
				inits_block = "";
				style = "'border:" + border_color + " 2px solid;'";
				on_click = "onclick=crm_remove_assignee(" + assignee_id + ",'" + border_color + "','" + assignee_inits + "','" + image_path + "')";
				assignee_id = "id=assignee_id_" + assignee_id;
				inits_block = "<span ><a class='crm_assignee crm_assignee_inits' " + assignee_id + " " + on_click + " style=" + style + ">x " + photo + "</a></span>";
				$("#assignees_list").append(inits_block);
				
			}

		});

	}

	function getFullTeamInitials() {

		action = "get_full_assignee_inits";

		$.ajax({
			type: "POST",
			data: { "action": action},
			url: ajax_path,
			success: function (resp) {

				var inits = JSON.parse(resp);

				var iLength = inits.length;
				var inits_block = "";

				for (iCnt = 0; iCnt < iLength; iCnt++) {

					assignee_id = inits[iCnt]['memberID'];
					assignee_inits = inits[iCnt]['memberInitials'];
					border_color = inits[iCnt]['memberBackColour'];
					image_path = '../images/'+ inits[iCnt]['memberPhoto'];
					photo = "<img class='assignee_photo' src='" + image_path + "' title=" + inits[iCnt]['memberName'] + " />";

					inits_block = "";
					style = "'border:" + border_color + " 2px solid;'";
					on_click = "onclick=crm_add_assignee(" + assignee_id + ",'" + border_color + "','" + assignee_inits + "','team','" + image_path + "')";
					assignee_id = "id=assignee_id_" + assignee_id;
					inits_block ="<span ><a class='crm_assignee crm_assignee_inits' " + assignee_id + " " + on_click + " style=" + style + ">+ " + photo + "</a></span>";
					$("#non_assignees_list").append(inits_block);

				}

			}
		});

		return;		

	}

	function getNonTeamInitials() {

		action = "get_non_team_members";
		$.ajax({
			type: "POST",
			data: { "action": action},
			url: ajax_path,
			success: function (resp) {

				

				var inits = JSON.parse(resp);

				var iLength = inits.length;
				var inits_block = "";

				for (iCnt = 0; iCnt < iLength; iCnt++) {

					assignee_id = inits[iCnt]['member_id'];
					assignee_inits = inits[iCnt]['member_initials'];
					border_color = inits[iCnt]['member_back_colour'];
					image_path = '../images/'+ inits[iCnt]['member_photo'];
					photo = "<img class='assignee_photo' src='" + image_path + "' title=" + inits[iCnt]['member_name'] + " />";

					style = "'border:" + border_color + " 2px solid;'";
					on_click = "onclick=crm_add_assignee(" + assignee_id + ",'" + border_color + "','" + assignee_inits + "','team','" + image_path + "')";
					assignee_id = "id=assignee_id_" + assignee_id;
					inits_block = inits_block + "<span ><a class='crm_assignee crm_assignee_inits' " + assignee_id + " " + on_click + " style=" + style + ">+ " + photo + "</a></span>";
				}

				$("#non_team_list").append(inits_block);	
				
			}

		});

	}

	/* end of all the teams data when adding an activity */

	/* start of all the teams data when editing an activity */

	function getMemberInits(activityID, assignees) {

		action = "get_member_inits";

		$.ajax({
			type: "POST",
			data: { "activity_id": activityID, "action": action, "assignees": assignees },
			url: ajax_path,
			success: function (resp) {

				var inits_block = '';
				var data_array = JSON.parse(resp);

				assignees = data_array[0]['assignees_array'];
				assignee_length = assignees.length;

				for (assignee_count = 0; assignee_count < assignee_length; assignee_count++) {

					assignee_id = assignees[assignee_count]['assignee_id'];
					assignee_inits = assignees[assignee_count]['member_inits'];
					
					image_path = '../images/'+ assignees[assignee_count]['member_photo'];
					photo = photo = "<img class='assignee_photo' src='" + image_path + "' title=" + assignees[assignee_count]['member_name'] + " />";

					border_color = assignees[assignee_count]['memberBackColour'];
					style = "'border:" + border_color + " 2px solid;'";
					on_click = "onclick=crm_remove_assignee(" + assignee_id + ",'" + border_color + "','" + assignee_inits + "','" + image_path + "')";
					assignee_id_val = "id=assignee_id_" + assignee_id;
					inits_block = inits_block + "<span class='crm_assignee' data-id= " + assignee_id + " " + assignee_id_val + " data-assignee-list='assignee'><a class='crm_assignee crm_assignee_inits' " + on_click + " style=" + style + ">x " + photo + "</a></span>";
				}

				$("#assignees_list").html(inits_block); 

				my_team = data_array[1]['my_team_array'];
				my_team_length = my_team.length;
				inits_block = '';

				inits_block = "<span style='display:inline-block;width:90%;'>";

				for (my_team_count = 0; my_team_count < my_team_length; my_team_count++) {

					assignee_id = my_team[my_team_count]['assignee_id'];
					assignee_inits = my_team[my_team_count]['memberInitials'];
					image_path = '../images/' + my_team[my_team_count]['member_photo'];
					photo_raw = my_team[my_team_count]['member_photo'];
					photo = "<img class='assignee_photo' src='" + image_path + "' title=" + my_team[my_team_count]['member_name'] + " />";

					border_color = my_team[my_team_count]['memberBackColour'];
					style = "'border:" + border_color + " 2px solid;'";
					on_click = "onclick=crm_add_assignee(" + assignee_id + ",'" + border_color + "','" + assignee_inits + "','team','" + photo_raw + "')";
					assignee_id_val = "id=assignee_id_" + assignee_id;
					inits_block = inits_block + "<span data-id= " + assignee_id + " " + assignee_id_val + " data-assignee-list='team'><a class='crm_non_assignee crm_assignee_inits' " + on_click + " style=" + style + ">+ " + photo + "</a></span>";
				
				}

				inits_block = inits_block + "</span>";

				$("#non_assignees_list").append(inits_block); 

				not_my_team = data_array[2]['not_my_team_array'];
				not_my_team_length = not_my_team.length;
				inits_block = '';

				for (not_my_team_count = 0; not_my_team_count < not_my_team_length; not_my_team_count++) {

					assignee_id = not_my_team[not_my_team_count]['assignee_id'];
					assignee_inits = not_my_team[not_my_team_count]['member_inits'];
					image_path = '../images/'+ not_my_team[not_my_team_count]['member_photo'];
					photo = "<img class='assignee_photo' src='" + image_path + "' title=" + not_my_team[not_my_team_count]['member_name'] + " />";

					border_color = not_my_team[not_my_team_count]['memberBackColour'];
					style = "'border:" + border_color + " 2px solid;'";
					on_click = "onclick=crm_add_assignee(" + assignee_id + ",'" + border_color + "','" + assignee_inits + "','non_team','" + image_path + "')";
					assignee_id_val = "id=assignee_id_" + assignee_id;
					inits_block = inits_block + "<span data-id= " + assignee_id + " " + assignee_id_val + " data-assignee-list='non-team'><a class='crm_non_team crm_assignee_inits' " + on_click + " style=" + style + ">+ " + photo + "</a></span>";
				}

				$("#non_team_list").append(inits_block); 

			}

		});
		
	}

	/* end of all the teams data when editing an activity */

	/* start of code to move assignees */

	function crm_remove_assignee(member_id, border_color, assignee_inits, image_path) {

		assignee_to_remove = '#assignee_id_' + member_id;
		$(assignee_to_remove).remove(assignee_to_remove);
		photo = "<img class='assignee_photo' src='" + image_path + "' />";

		style = "'border:" + border_color + " 2px solid;'";
		on_click = "onclick=crm_add_assignee(" + member_id + ",'" + border_color + "','" + assignee_inits + "','team','" + image_path + "')";

		assignee_id = "id=assignee_id_" + member_id;
		inits_block = "<span class='crm_assignee' ><a class='crm_non_assignee crm_assignee_inits' " + assignee_id + " " + on_click + " style=" + style + ">+ " + photo + "</a></span>";
		$('#non_assignees_list').append(inits_block);
		
	}

	function crm_add_assignee(member_id, border_color, assignee_inits, team, photo_raw) {

		assignee_to_remove = '#assignee_id_' + member_id;
		$(assignee_to_remove).remove(assignee_to_remove);
		image_path = '../images/' + photo_raw;
		photo = "<img class='assignee_photo' src='" + image_path + "' />";
		style = "'border:" + border_color + " 2px solid;'";
		on_click = "onclick=crm_remove_assignee(" + member_id + ",'" + border_color + "','" + assignee_inits + "','" + image_path + "')";
		assignee_id_val = "id=assignee_id_" + member_id;
		inits_block = "<span class='crm_assignee' data-id= " + member_id + " " + assignee_id_val + " data-assignee-list=" + team + "><a class='crm_assignee crm_assignee_inits'  " + on_click + " style=" + style + ">x " + photo + "</a></span>";

		$('#assignees_list').append(inits_block);

	}


