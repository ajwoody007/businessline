<?php

	$mymemberID		= 	$_SESSION["memberSPLSWID"];	
	$user_id 		=  	$_SESSION["memberSPLSWID"];	

    /* initial settings */

	$follow_up_date_raw = strtotime("+7 day");
	$follow_up_date = date('d/m/y', $follow_up_date_raw);

	/* set colour scheme based on user -- here temporarily, will move to settings page...eventually */

	$colour_class = "";
	if ($user_id == 104) {$colour_class = "scheme_ruby"; }

if(isLoggedInSPL()){ ?>   

<script>
$(document).ready(function() {

	console.log("ROOT " + root_path + "/remote_customers.php");

	$(".chooseCustomer").select2({
        ajax: {
           url: root_path + "/remote_customers.php",
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    type: 'customerspo'
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        },
        cache: true,
        minimumInputLength: 3
    });
	$(".chooseCustomer").trigger('change');

	var crmID		= $(this).data('crm-cid');
	$("#customer_choice").val(crmID).change();

	$(".toggletrays").click(function () {
		console.log("tray rads clicked");
		if ( $("#completed").prop("checked")) { return false; }
		$(".traydetail").toggle(),
		$(".toggletrays").toggleClass("show"),
		$(".trayrads").toggle();
	});

	$(".toggle_recur").click(function () {

		console.log("clicked");

		$(".recur_row").toggle(),
		$(".daily_block").hide(),
		$(".weekly_block").hide(),
		$(".monthly_block").hide(),
		$(".whenrads").toggleClass("hide");

		if ($(".recur_row").is(":visible") && $("#recur_day_label").hasClass("crm_recur_selected")) {$(".daily_block").show(); } else { $(".daily_block").hide();}
		if ($(".recur_row").is(":visible") && $("#recur_week_label").hasClass("crm_recur_selected")) {$(".weekly_block").show(); } else { $(".weekly_block").hide();}
		if ($(".recur_row").is(":visible") && $("#recur_month_label").hasClass("crm_recur_selected")) {$(".monthly_block").show(); } else { $(".monthly_block").hide();}

	});

	$(".crm_date").click(function () {

		if ($(".recur_row").is(":visible") && $("#recur_day_label").hasClass("crm_recur_selected")) {$(".daily_block").show(); } else { $(".daily_block").hide();}
		if ($(".recur_row").is(":visible") && $("#recur_week_label").hasClass("crm_recur_selected")) {$(".weekly_block").show(); } else { $(".weekly_block").hide();}
		if ($(".recur_row").is(":visible") && $("#recur_month_label").hasClass("crm_recur_selected")) {$(".monthly_block").show(); } else { $(".monthly_block").hide();}

	});	

	$(".toggle_assignees").click(function () {
		$("#non_assignees_list").fadeToggle(400),
		$("#non_assignees_list").toggleClass("hide");
		$("#non_assignees_list").toggleClass("show");
		$("#non_team_list").hide();
	});

	$(".toggle_non_team").click(function () {
		$("#non_team_list").fadeToggle(400),
		$("#non_team_list").toggleClass("hide");
		$("#non_team_list").toggleClass("show");
	});	

	$(".toggle_recur_info").click(function () {
		$("#recur_info").toggle(),
		$("#recur_actions").toggle(),
		$("#recur_info").toggleClass("hide");
		$("#recur_actions").toggleClass("show");
	});

	$("#edit_profile").click(function () {
		
		enable_profile('family');
		enable_profile('pets');
		enable_profile('sport');
		enable_profile('holiday');
		enable_profile('birthday');
		enable_profile('contactProfile');

		$("#edit_profile").hide();
		$("#save_profile").show();
		$(".fa-save").show();

	});

	$("#save_profile").click(function () {

		/* wrap the data in an array and package off to ajax/php */

		var profile_array = {
			'family' : $("#family").val(),
			'pets' : $("#pets").val(),
			'sport' : $("#sport").val(),
			'holiday' : $("#holiday").val(),
			'birthday' : $("#birthday").val(),
			'profile' : $("#contactProfile").val(),
			'contact_person_id' : $("#contactNames").val()
		};
		
		var action = "update_profile";

		$.ajax({ 
			type: "POST",	
			data: {"action":action, "profile_array":profile_array},
			url: ajax_path,
			success: function(resp){
				console.log(resp);
			}
		});	

		disable_profile('family');
		disable_profile('pets');
		disable_profile('sport');
		disable_profile('holiday');
		disable_profile('birthday');
		disable_profile('contactProfile');

		$("#edit_profile").show();
		$("#save_profile").hide();

	});	
	
	function enable_profile(profile_category) {

		console.log("enable " + profile_category);

		$("#" + profile_category).removeClass("locked");
		$("#" + profile_category).prop("disabled", false);
		$("#" + profile_category).css("background-color", "#fff");
		$("#" + profile_category).css("padding", "8px");
		$("#" + profile_category).css("margin", "4px 12px");
		$("#" + profile_category + "_block").show();
		$("#contactProfile").css("margin-left", "10px");
		$("#contactProfile").css("border", "1px #999 solid");
		$("#contactProfile").css("width", "80%");
		$("#contactProfile").show();
		$("#profile_block").show();

		return;

	}

	function disable_profile(profile_category) {

		console.log("disable " + profile_category);
		
		$("#" + profile_category).prop("disabled", true);
		$("#" + profile_category).css({"background-color":"#ccc","padding":"8px","margin":"4px" });
		$("#" + profile_category).addClass("locked");

		$("#contactProfile").css({"margin-left":"5px","border":"0px #eee solid","width":"80%","background-color":"#ccc"});

		if (!$("#" + profile_category).val()) { $("#" + profile_category + "_block").hide(); }
		if (!$("#contactProfile").val()) { $("#profile_block").hide(); }
		return;

	}

	$("#customer_choice").change( function() {

		var transition_speed = 700;

		if (("#customer_choice")) { 
			$("#contactNames").show();
			$(".crmpu_right").show(transition_speed); 
			$("#edit_profile").show(); 
			$("#save_profile").hide(); 
			$("#email_block").show(); 
			$("#phone_block").show(); 
		}
		
	});

	$('body').on('change', '#file-input', function (){
		$(this).parent().find('.fa-upload').css({color: "green"});
	});

	$('body').on('change', '#crmFileUpload', function (){
		$(this).parent().find('.fa-upload').css({color: "green"});
		$("#crmFileUpload").push($("#crmFileUpload").data() );
	});

	$("#show_files").click( function() { $("#view_files").toggle(500); });

	if ($("#crmBy").val() == "104") { $("#followUpAssigned").addClass("assignee_ruby");  }

	function formatState(state) {
		const option = $(state.element);
		const style = option.data("color");
		if (!style) {			return state.text;		  }
		return $('<div style="background-color: ' + $(state.element).data('color') + '"> ' + state.text + '</div>');
	  };
	
	function formatState2(state) {
	  const option = $(state.element);
	  const color = option.data("color");
	  if (!color) {		return state.text;	  }
	  return $(`<span class="option" style="background-color: ${color}">${state.text}</span>`);
	};
	
	$(".choosesplteam").select2({
			placeholder: {    id: '-1',  text: 'Please choose team member(s)'},
			templateSelection: formatState2,
			templateResult: formatState,
	});

	$("#activity_id_file").on("drop", function(event) {
		console.log("FILES = ");
		event.preventDefault();  
		event.stopPropagation();
	
	});

	$("#completed_label").hover(function() {

		if ($("#completed_label").css("background-color") != "rgb(67, 146, 77)")  {
			$("#completed_label").css({"background-color":"#43924D", "color":"#ffffff", "border":"#43924D 2px solid"});
		} else {
			$("#completed_label").css({"background-color":"#ffffff", "color":"#000000", "border":"#43924D 2px solid"});
		}

	});

	$("#editCRMActivity").hover(function() {

		if ($("#editCRMActivity").css("background-color") != "rgb(124, 68, 145)")  {
			$("#editCRMActivity").css({"background-color":"#7C4491", "color":"#ffffff", "border":"#7C4491 2px solid"});
		} else {
			$("#editCRMActivity").css({"background-color":"#ffffff", "color":"#000000", "border":"#7C4491 2px solid"});
		}		

	});	

	$("#save_profile").hover(function() {

		if ($("#save_profile").css("background-color") != "rgb(124, 68, 145)")  {
			$("#save_profile").css({"background-color":"#7C4491", "color":"#ffffff", "border":"#7C4491 2px solid"});
		} else {
			$("#save_profile").css({"background-color":"#ffffff", "color":"#00000", "border":"#7C4491 2px solid"});
		}		

	});	

	$("#recurMonthType").change(function() {

		$("#recurMonthsCount").hide();
		$("#monthlyDayBlock").hide();

		var typeSetting = $("#recurMonthType").val();

		console.log("TYPE = " + $("#recurMonthType").val());

		if (typeSetting == 'date') {
			$("#recurMonthsCount").css("display", "inline-block");
		}

		if (typeSetting == 'day') {
			$("#monthlyDayBlock").css("display", "inline-block");
		}

	});

	$("#customer_choice_label").click(function() {

		var customer_id = $("#company_id").val();
		if (!customer_id || customer_id == "0") { return false;}
		window.open("../customers/?editcontactsID=" + customer_id, '_blank');

	});

});
	
</script>

<style>

	.completed { text-align:center; width:auto;float:left; clear:both;}
	.completed label  { width:100%; margin: 20px 0 0 0 !important; float:left; cursor:pointer;  display: inline-block;   background-color: #fff;    padding: 6px 12px 8px !important;    font-size: 1em  !important;;   border: 2px solid #999 ;    border-radius: 10px;	font-weight: 700  !important;margin-right:1%;margin-bottom:1%; box-sizing: border-box; text-align: center;position: relative;}
	.completed label i {color:#43924D}
	.completed input[type="checkbox"]{  display: none}
	.completed input[type="checkbox"]:checked + label{  background-color: #43924D;   border: 2px solid #43924D; color: #ffffff;} 
	.completed input[type="checkbox"]:checked + label i { color: #ffffff; }

	#popupbg {width: 100%; min-height: 100%; position: fixed; top: 0; bottom: 0; left: 0; z-index: 999999;  background-color: rgba(64, 43, 109, 0.9); display:none;}
	#popupbg .crmpu_inner { box-sizing: border-box; position:relative;width:60%;  margin: 90px auto 0px; border-radius:15px;padding:0px; 	box-shadow: 0 1px 2px rgba(0,0,0,.2);background: linear-gradient(to right, #cccccc 28%, #ffffff 28%); overflow:hidden}
	#popupbg .crmpu_left,
	#popupbg .crmpu_right{box-sizing: border-box;  padding:30px 30px 0px 30px;  }

	#popupbg .mode_type { width:30%; }

	#popupbg .crmpu_inner button.close{ 	cursor:pointer; display:block; position:absolute; top:30px;; right:26px; color:#777; background:none; font-size:2.5em;}
	#popupbg .crmpu_inner button.minimise{ 	cursor:pointer; display:block; position:absolute; top:30px;; right:92px; color:#333; background:none; font-size:2.5em;}
	#popupbg .crmpu_left {float:left; width:28%;   }
	#popupbg .crmpu_right {float:right;;  width:72%; ;}
	#popupbg .crmpu_left select,  #popupbg .crmpu_left input, #popupbg .crmpu_left textarea { width:100%; background:transparent; border:1px solid #999; padding:12px; border-radius:10px;  box-sizing: border-box}
	#popupbg .crmpu_left .locked { border:0px !important;}
	#popupbg .crmpu_left textarea {margin-top: 12px;width: 70%; }
	#popupbg .crmpu_left .formsep {margin-bottom: 20px}

	#popupbg .crmpu_left .profile-point {float:left; width:110%; margin-bottom: 0%;}
	#popupbg .crmpu_left .profile-point i  {float:left; width:10%;  padding:0px;margin-top:9px; text-align:center;}
	#popupbg .crmpu_left .profile-point input  {float:left;width:78%;padding:6px;margin:0px;}

	#popupbg .crm_title  {margin-top:2px;font-size:1.5em; font-weight:bold}
	#popupbg .crmpu_right select, #popupbg .crmpu_right input, #popupbg .crmpu_right textarea { width:100%; background:transparent; border:2px solid #999; padding:8px; border-radius:10px;  box-sizing: border-box}
	#popupbg .crmpu_right .first{ width:30%; margin-right:5%}
	#popupbg .crmpu_right .locked { border:0px !important;}
	#popupbg .crmpu_right textarea {height:65px;margin-top: 10px}
	#popupbg .crmpu_right .formsep {margin-bottom: 12px}

	#popupbg .borderless {border:0px;margin:0px;padding:4px 0px;}

	#popupbg button  {border:0px; }
	#popupbg textarea {outline:none!important; }

	.crm_rad label, .crm_rad2 label {float:left;position:relative;display:inline-block; font-weight:700}
	/* .crm_rad input[type=radio]:checked+label:after{content:'\2713';position:absolute;top:23px;right:3px;font-size:15px;line-height:.8;color:#fff;transition:.2s} */

	/* .crm_rad input[type=radio].toggletrays:checked+label:after{content:'';position:absolute;top:23px;right:3px;font-size:15px;line-height:.8;transition:.2s} */
	/* .crm_rad2 input[type=radio]:checked+label:after{content:'\2713';position:absolute;top:23px;right:3px;font-size:15px;line-height:.8;color:#fff;transition:.2s} */
	/* .crm_rad2 input[type=radio].togglewhen:checked+label:after{content:'';position:absolute;top:23px;right:3px;font-size:15px;line-height:.8;color:#fff;transition:.2s} */

	.crm_rad .upload label{width:100%;text-align:center}
	/* .crm_rad input[type=radio]:focus+label{border:2px solid #444} */
	/* .crm_rad input[type=radio]:checked+label{background-color:#F19C38;border-color:#F19C38} */
	/* .crm_rad2 input[type=radio]:checked+label{background-color:#4DA59D;border-color:#4DA59D} */

	.crm_rad.upload.showu{width:100%}
	.crm_rad label{font-size:.8em; width:100%;}
	@media only screen and (min-width:1180px) and (max-width:1490px){.crm_rad label{font-size:.8em;padding:12px 4px}}
	@media only screen and (min-width:991px) and (max-width:1180px){.crm_rad label{font-size:.85em;padding:12px 4px}}
	@media only screen and (min-width:0px) and (max-width:991px){.crm_rad label{font-size:2.5vw}}
	@media only screen and (min-width:0px) and (max-width:690px){.crm_rad label{font-size:3.5vw;padding:12px 4px}}

	.crm_rad,
	.crm_rad2 { float:left; width:100%;    margin: 0px 0 8px 0px}

	.crm_rad input[type=radio] , 
	.crm_rad2 input[type=radio] {    opacity: 0;    position: fixed;    width: 0}

	.crm_rad label,
	.crm_rad2 label {    cursor: pointer;    padding: 10px 10px;    font-size:0.85em;   border: 2px solid #999;    border-radius: 10px;   margin-right: 14px;	margin-top: 12px;    margin-bottom: 0%;    box-sizing: border-box;width:auto;	font-weight: 700;}
	label.tt, 
	label.wh {margin-right: 0px;padding: 5px 8px; float:right;background:#999; border: 2px solid #999; color:#fff; }

	.crm_rad2 label:nth-of-type(4) {margin-left:0%}	
	.crm_rad label i,
	.crm_rad2 label i{font-size:1.8em		}

	i.recur { margin: -1px -2px -2px 8px;    font-size: 1.4em !important;    float: right;}

	 .crmpu_left .select2-selection--multiple {background-color: transparent !important; padding:0 !important; margin:0 !important;font-size:1.25em;}
	 .crmpu_left .select2-selection__choice { float:left;font-size: 1em;  padding: 0px 10px; border:0 !important }

	 .crmpu_left .select2-results__option { padding: 0 !important; }
	 .crmpu_left .select2-results__option div { padding: 6px; }

	 .crmpu_left .select2-container--default .select2-selection--multiple {border:0px #ccc solid;width:100%;background-color:#fff!important; border-radius:12px;outline:none!important;}
	 .crmpu_left .select2-container--default .select2-selection--single  {border:none;width:100%;background-color:#ccc!important;}
	.crm_light .select2-container--default .select2-selection--single  {border:none;width:100%;background-color:#fff!important;}
	 .crmpu_left .select2-selection--multiple {border:none;width:100%;background-color:#fff!important;outline:none!important;}
	 .crmpu_left .select2-search__field {border:none!important;}

	.chooseCustomer .select2-selection--single {display:none;}
	.choosesplteam .select2-selection--multiple select input textarea .select2-search__field .select2-selection__rendered {border:none!important;}
	.chooseteam .select2-selection--multiple select input textarea .select2-search__field .select2-selection__rendered {border:none!important;}

	#popupbg .crmpu_right select, #popupbg .crmpu_right input, #popupbg .crmpu_right textarea{border:none!important;}

	.trayToggleDetail .select2-selection--multiple {border:0px #ccc solid;width:100%;background-color:#ccc!important; border-radius:0px;height:36px!important;padding:4px;}
	
	.urgent { text-align:center; width:auto;float:right}
	.urgent label  { width:100%; margin: 0px!important; float:left; cursor:pointer;  display: inline-block;   background-color: #fff; background-color:#fff;color:#000;   padding: 10px 10px !important;    font-size: 1em  !important;;   border: 2px solid #999;    border-radius: 10px;	font-weight: 700  !important;margin-right:1%;margin-bottom:1%; box-sizing: border-box; text-align: center;position: relative;}
	.urgent label i {color:#F4403B}
	.urgent input[type="checkbox"]{  display: none}
	.urgent input[type="checkbox"]:checked + label{    background-color: #F4403B;   border: 2px solid #F4403B; color: #ffffff;} 
	.urgent input[type="checkbox"]:checked + label i{ color: #ffffff; }

	button.savecrm, a.savecrm {margin: 20px 0 0 0 !important;  background-color: #FFFFFF; color:#000000; border:2px solid #7C4491 ; border-radius: 10px;	font-weight: 700  !important;  padding: 10px 10px !important;  }
	/* button.savecrm, a.savecrm {margin: 20px 0 0 0 !important;  background-color: #7C4491; color:#ffffff; border-radius: 10px;	font-weight: 700  !important;  padding: 10px 10px !important;  } */

	.traydetail {margin-top: -48px; float: left; width:100%; display:none;}
	.traydetail select{width:92.4% !important; float:left; padding: 7px;}
	.traydetail textarea{border:2px solid #999; margin:0px 0 10px 0}
	.toggletrays.show {display: block}
	.trayrads.hide {display: none}

	.whendetail {display:none; margin-top: -48px;    float: left; width:100%}
	.whendetail input{width:20%; float:left; border:2px solid #999; padding: 7px;}
	.whendetail input:nth-of-type(2){width:13.5%; margin-left:15px; float:left; border:2px solid #999; padding: 7px;}
	.togglewhens.show {display: block}
	.whenrads.hide {display: none}

	/* .recurdetail img.ui-datepicker-trigger {margin-right:20px;} */
	.togglerecurs.show {display: block}
	.recurrads.hide {display: none}

	.recurItems { width:auto;clear:both;border:0px red solid; }
	.recurItems label  { cursor:pointer; display: inline-block; width:12%; color:#999; background-color: #fff; padding: 6px!important;font-size:1em!important;border: 0px solid #999!important;    border-radius: 10px;margin:1px 4px;	font-weight: 700  !important;box-sizing: border-box; text-align: center;position: relative;}
	.recurItems label i {color:#43924D}
	.recurItems input[type="checkbox"]{ display: none;}
	.recurItems input[type="checkbox"]:checked + label{ background-color: #43924D;  color: #ffffff; } 
	.recurItems input[type="checkbox"]:checked + label i{ color: #ffffff; }

	.whenrads img.ui-datepicker-trigger {margin-left:-50px!important;top:18px!important; }
	.crmpu_left img.ui-datepicker-trigger {display:none!important;}
	.contact_profile { margin-left:-10px;}

	.recur_daily_start img.ui-datepicker-trigger {right:-108px!important;top:-33px!important; }
	.recur_daily_end img.ui-datepicker-trigger {right:-108px!important;top:-33px!important; }
	.recur_weekly_start img.ui-datepicker-trigger {right:-108px!important;top:-33px!important; }
	.recur_monthly_start img.ui-datepicker-trigger {right:-108px!important;top:-33px!important; }

	::-webkit-scrollbar {
	-webkit-appearance: none;
	width: 7px;
	}

	::-webkit-scrollbar-thumb {
	border-radius: 4px;
	background-color: rgba(0, 0, 0, .5);
	box-shadow: 0 0 1px rgba(255, 255, 255, .5);
	}	

	.ordholder {float:left;width:26%; margin-right:2%; background:transparent; border:1px solid #999; padding:12px; border-radius:10px;  box-sizing: border-box; display: inline-block}
	.ordholder:last-of-type { margin-right:2%;}
	.ordholder i { color:#5E308B;font-size:1.8em;float:right; margin: -5px;}
	.ordholder:nth-of-type(2) i {color:#4B96DB;}
	.ordholder:nth-of-type(1) input {border:0px; padding:0px; width:40%;}
	.ordholder:nth-of-type(2) input {border:0px; padding:0px; width:85% }
	.ordholder:nth-of-type(3) input {border:0px; float:left; padding:0px; width:85%; }

	.historyscroll {overflow-y: auto;max-height: 160px; box-sizing: border-box; padding-right:12px; margin-bottom:10px}
	.historyblock {width: 100%;   background: transparent;    border: 1px solid #999;    padding: 12px; margin:12px 0 0px 0;   border-radius: 10px;    box-sizing: border-box;}
	.historyblock div {border:0; padding:0; margin:0; text-align: left;}
	.historyblock div:nth-of-type(2) {margin:3px 0 0 0; color:#444;font-style:italic;}

</style>

<div class="crm_mini" id="crm_mini" onclick="maximize_crm()" style="display:none;">
	<i class="fa-solid fa-arrow-up-right-from-square fa-rotate-270" aria-hidden="true" id="crm_mini_icon" style="font-size:2em;position:fixed;bottom:0;right:0; margin:20px;float: none;z-index:99999" ></i>
	<!-- <img class="crm" id="crm_mini_icon" src="images/activity-icon.png" alt="All Activity" height="40" style="position:fixed;bottom:0;right:0; margin:20px;float: none;z-index:99999"> -->
	</div>
<div id="popupbg" >

	<div class="crmpu_inner">

	<!-- hidden fields section -->

		<input type="hidden" id="followUpDateOriginal" name="followUpDateOriginal" value="" />
		<input type="hidden" id="followUpTimeOriginal" name="followUpTimeOriginal" value="" />
		<input type="hidden" id="activity_id"  name="activity_id" />
		<input type="hidden" id="crmBy" name="crmBy" value="<?php echo $_SESSION["memberSPLSWID"]; ?>" readonly>
		<input type="hidden" id="contactPersonID" name="contactPersonID" />
		<input type="hidden" id="action_type" name="action_type" />
		<input type="hidden" id="company_id" name="company_id" />

	<!-- end of hidden fields section -->

		<div id="showSchedule" class="show_schedule" style="display:none;background-color:#ccc;"></div>
		
		<button class="close" id="btnExit" onclick=close_crm_popup()>
			<i class="fa-solid fa-rectangle-xmark" aria-hidden="true"></i>
		</button>
		
		<button class="minimise" id="btnMinimize" onclick="minimize_crm()" >
			<i class="fa-solid fa-arrow-up-right-from-square fa-rotate-90" aria-hidden="true"></i>
		</button>
		
		<div class="crmpu_left">
			
			<form>

				<div class="ordholder" id="customer_block" style="width:100%;margin-bottom:12px;padding:4px;">

					<select name="customer_choice" id="customer_choice" class="chooseCustomer formsep js-example-basic-single" onchange=getContactsForCustomer() required style="display:none;width:100%;" >
						<option value="" disabled selected style="color:#f1f1f1;">Select company</option>
						<option value="0" style="">Self</option>
					</select>
					
				</div>

				<a class='self_button' id='setSelf' onclick=setSelf() style='margin-bottom:20px;'>This activity is for me</a>

				<input id="customer_choice_label" class="customer_choice_label" style="display:none;margin-bottom:16px;outline:none;"  />

				<select class="formsep" id="contactNames" name="contactNames" onchange=getCRMContact() style="display:none;"></select>

				<div class="profile-point formsep" id="phone_block" >
					<a class="crm_icon_link" id="call_contact"><i class="fa fa-phone fa-rotate-90 profile_icon" aria-hidden="true"></i></a>
					<input class="locked" type="text" id="contactPhone" name="contactPhone" value="" disabled/>
				</div>

				<div class="profile-point formsep" id="email_block">
					<a class="crm_icon_link" id="email_contact"><i class="fa fa-envelope profile_icon" aria-hidden="true"></i></a>
					<input class="locked" type="text" id="contactEmail" name="contactEmail" value="" disabled/>
				</div>
				
				<div class="profile-point formsep" id="family_block"  >
					<i class="fa fa-users profile_icon" aria-hidden="true" title="Family details"></i>
					<input class="locked profile_edit" type="text" id="family" name="family" value="" disabled/>
				</div>
				
				<div class="profile-point formsep" id="pets_block" >
					<i class="fa fa-paw profile_icon" aria-hidden="true" title="Pets - past or present"></i>
					<input class="locked profile_edit" type="text" id="pets" name="pets" value="" disabled />
				</div>

				<div class="profile-point formsep" id="sport_block"  >
					<i class="fa fa-futbol profile_icon" aria-hidden="true" title="Favourite sport"></i>
					<input class="locked profile_edit" type="text" id="sport" name="sport" value="" disabled/>
				</div>

				<div class="profile-point formsep" id="holiday_block" >
					<i class="fa-solid fa-plane-departure profile_icon" aria-hidden="true" title="Favourite holiday location"></i>
					<input class="locked profile_edit" type="text" id="holiday" name="holiday" value="" disabled/>
				</div>

				<div class="profile-point formsep" id="birthday_block"  >
					<i class="fa-solid fa-cake-candles profile_icon" aria-hidden="true" title="Birthday"></i>
					<input class="locked profile_edit" type="date" id="birthday" name="birthday" value="" disabled style="color:#000;">
				</div>	

				<div class="profile-point formsep" id="profile_block" >
					<i class="fa-solid fa-user profile_icon" aria-hidden="true" title="Any additional details"></i>
					<textarea id="contactProfile" class="locked formsep profile_edit profile-point contact_profile" disabled style="border:0px #eee solid!important;margin:0px;width:80%;padding:0px 12px; min-height:100px;"></textarea>
				</div>	

				<div class="profile-point" id="edit_profile" style="display:none;cursor:pointer;font-size:1em!important;">
					<i class="fa-solid fa-edit profile_icon" aria-hidden="true" title="Click to edit the profile."></i>
				</div>

				<a class="savecrm" id="save_profile" style="cursor:pointer;display:none;margin:12px 0px!important;float:right;">Update Profile</a>


			</form>
			
		</div>
		
		<div class="crmpu_right" >
		
			<!-- <form action="#" id="crmBlockModal" class="" method="post" enctype="multipart/form-data" > -->

			<!-- drop down for order / quote -->

				<div class="ordholder" style="width:35%;padding:4px;">
				
						<select id="qo_value" style="width:40%;padding:4px;border:none;outline:none;">

							<option selected disabled value="0">Select...</option>
							<option value="Order">Order</option>
							<option value="Quote">Quote</option>

						</select>

					<input id="crmorderID" name="crmorderID"  type="text" inputmode="numeric" step="1" class="" value="" style="border:none;outline:none!important;width:40%;" />
					<span style="padding-top:12px!important;"><a id="goToOrder" onclick=goToOrder() style="cursor:pointer;display:none;"><i class="fa-solid fa-paper-plane" aria-hidden="true" style="border:none;margin-top:6px;padding-right:12px;"></i></a></span>
				</div>	

				<form id="file_upload">

					<div class="ordholder" style="padding:0px;width:20%;">

					<!-- <span style="margin:50px 0px 0px 50px;display:flex;border:1px red solid;width:60%;z-index:9999">Click to upload files</span> -->

					<input type="text" id="activity_id_file"  name="activity_id_file" style="display:none;border:1px #ccc solid;width:100%;background-color:#fff;text-align:left!important;" />

					<input name="crmFileUpload[]" id="crmFileUpload" multiple type="file" class="custom-file-input fas fa-upload" onchange=updateList() title="Click to upload files" style="cursor:pointer;border:0px #ccc solid;width:100%;background-color:#fff;text-align:left!important;">
					
					</div>
					<div id='file_list'></div>
				</form>

				<div class="ordholder" style="width:23%;padding: 9px 6px;">	
					<input id="date_activity_added" name="date_activity_added" value="<?php echo date('d/m/Y H:i'); ?>" readonly style="border:none;outline:none!important;margin:1px;padding:2px;" />
				</div>

				<div class="clear"></div>

				<div class="ordholder" id="view_files" style="display:none;width:100%;padding:2px;margin:8px 0px;">

					<div class="crmRow" style="">
						<div class="fileTable"></div>
					</div>

				</div>
				
				<div class="crm_title">Notes</div>

				<textarea class="formsep" id="new_crm_task" name="new_crm_task" onkeyup="add_crm_task()" placeholder="Add new action..." style="outline:none!important;border:1px #999 solid!important;resize:none;"></textarea>
					
				<div class="crm_title" id="history_title" style="display:none;">History</div>

				<div class="historyscroll">	

					<div id="tasks_data"></div>
					
				</div>	

				<input type="hidden" id="task_count" />
				
				<div class="crm_title">Next Action</div>

				<div class="crm_rad">

					<div class="trayrads" >

						<!-- get all the system trays from the db -->

						<?php

							$trayQuery 		= "SELECT * FROM crm_trays WHERE system = 1 AND DELETED = 0";
							$trayResult 	= $db->query($trayQuery);

							$iRowCount = 1;
							$color_style = 'background-color:#fff;color:#000;';

							while ($tray_row = $trayResult->fetch()) {

								if ($tray_row['trayID'] == 1) { $class = "crm_cs";}
								if ($tray_row['trayID'] == 2) { $class = "crm_cc";}
								if ($tray_row['trayID'] == 3) { $class = "crm_co";}
								if ($tray_row['trayID'] == 5) { $class = "crm_td";}

								echo '<input type="radio" class="system_tray" name="followUpAction" id="tray_' . $tray_row['trayID'] . '" value=' . $tray_row['trayID'] . ' onchange=unsetActionExtra(' . $tray_row['trayID'] . ') ><label id="tray_' . $tray_row['trayID'] . '_label" for="tray_' . $tray_row['trayID'] . '" class="' . $class . '" >' . $tray_row['trayName'] . '</label>';

								$iRowCount++;
								
							}	

						?>

					</div>

					<!-- get the custom trays for this user -->

					<input type="radio" class="toggletrays" name="crm_action" id="action_10001" value="10001"><label class="tt" for="action_10001"><i class="fa-solid fa-ellipsis-h" aria-hidden="true"></i></label>

				</div>
				<input type="hidden" id="actions_count" value="<?= $iRowCount; ?>">

				<div class="traydetail" >
					<select class="formsep" id="followUpActionExtra" style="border:2px solid #999!important;" ></select>
				</div>

				<div class="crm_rad2 when_row" style="position:relative;margin:0px;">

					<div style="display:none!important;">
						<button name="crm_when" id="when_1000" value="1000" style="display:none;"></button>
						<button name="crm_when" id="when_2000" value="2000" style="display:none;"></button>
						<button name="crm_when" id="when_3000" value="3000" style="display:none;"></button>
						<button name="crm_when" id="when_4000" value="4000" style="display:none;"></button>
					</div>

					<div class="whenrads" >
						<label class='crm_date' for="when_1000" id="when_1000_label" onclick=setActionDate("today") >Today</label>	
						<label class='crm_date' for="when_2000" id="when_2000_label" onclick=setActionDate("day")>Tomorrow</label>
						<label class='crm_date' for="when_3000" id="when_3000_label" onclick=setActionDate("week")>Next Week</label>
						<label class='crm_date' for="when_4000" id="when_4000_label" onclick=snoozeActivityForm() style="display:none;">Snooze</label>
						<input id="followUpDate" class="follow_up_date datepick formsep" type="text" name="followUpDate" <?php echo $follow_up_date; ?> style="width:150px;padding:8px;outline:none!important;margin-top:12px;float:left;border:2px #999 solid!important;" />
					</div>

					<div class="recur_row" id="recur_row" style="display:none;margin: 0px 0px 8px; padding-bottom: 55px;">
						<input type="radio" name="crm_recur" id="recur_day" value="day" onchange=setRecurringDisplay('day')><label for="recur_day" id="recur_day_label" class="crm_recur">Daily</label>
						<input type="radio" name="crm_recur" id="recur_week" value="week" onchange=setRecurringDisplay('week')><label for="recur_week" id="recur_week_label" class="crm_recur">Weekly</label>
						<input type="radio" name="crm_recur" id="recur_month" value="month" onchange=setRecurringDisplay('month')><label for="recur_month" id="recur_month_label" class="crm_recur">Monthly</label>
						<input type="radio" name="" id="recur_clear" value="clear" onclick=setRecurringClear()><label for="recur_clear" id="recur_clear_label" class="crm_recur">Clear</label>
					</div>

					<div id="recur_toggle_block" style="position:absolute;display:float:right;top:0px;right:0px">
						<input type="radio" class="toggle_recur" name="toggle_recur" id="toggle_recur" value=""><label class="tt" for="toggle_recur" style="inline-block;vertical-align:top!important;float:right;background-color:#DD58B4;border:none;"><i class="fa-solid fa-rotate-reverse" aria-hidden="true"></i></label>
					</div>

				</div>	

				<div class="crm_rad2 daily_block" id="dailyBlock" style="display:none;margin:8px 0px;border:0px red solid;height:48px">

					<div class='recur_daily_start' style="display:none;width:150px;margin:0px 3px 0px 0px;">

						<input 
							id="recurringDayStartDate" 
							name="recurringDayStartDate"  
							class=" datepick " 
							placeholder="Starting" 
							type="text" 
							autocomplete="off"
							style="width:150px;padding:8px;outline:none!important;border:2px #999 solid!important;" 
						/>

					</div>

					<div class='recur_daily_end' style="display:none;width:150px;">
					
						<input 
							id="recurringDayEndDate" 
							name="recurringDayEndDate" 
							class="datepick" 
							placeholder="Ending"
							type="text" 
							value="" 
							autocomplete="off"
							style="width:150px;padding:8px;outline:none!important;margin:0px 3px 0px 0px;float:left;border:2px #999 solid!important;" 
						/> 

					</div>

				</div>

				<div class="crm_rad2 weekly_block" id="weeklyBlock" style="display:none;margin:8px 0px;height:48px">

					<div class='recur_weekly_start' style="display:inline-block;vertical-align:top;width:150px;margin:0px 3px 0px 0px;">

						<input 
							id="recurringWeekStartDate" 
							name="recurringWeekStartDate"  
							class="datepick" 
							placeholder="Starting" 
							type="text" 
							value=""
							autocomplete="off" 
							style="width:150px;padding:8px;outline:none!important;border:2px #999 solid!important;" 
						/>

					</div>				

					<div class='recur_weekly' style="display:inline-block;vertical-align:top;margin:0px 3px 0px 0px;width:150px;" >

						<input 
							type="number" 
							class="" 
							value="" 
							name="recurringWeeksDuration" 
							id="recurringWeeksDuration" 
							placeholder="No of weeks"
							autocomplete="off"
							min="1"
							max="52"
							style="outline:none!important;width:150px;border:none;"
						/>	
						
					</div>

					<div class="recur_weekly" style="display:inline-block;width:50%;height:34px!important;padding:0px;">
						
						<div class="recurItems" style="">
							<span style="float:left;padding:6px;color:#9F9F9F">Repeat on</span>
							<div style="text-align:right;">
								<input type="checkbox" name="btnMon" id="btnMon" value="mon"><label for="btnMon">Mon</label>
								<input type="checkbox" name="btnTue" id="btnTue" value="tue"><label for="btnTue">Tue</label>
								<input type="checkbox" name="btnWed" id="btnWed" value="wed"><label for="btnWed">Wed</label>
								<input type="checkbox" name="btnThu" id="btnThu" value="thu"><label for="btnThu">Thu</label>
								<input type="checkbox" name="btnFri" id="btnFri" value="fri"><label for="btnFri">Fri</label>
							</div>

						</div>

					</div>
				
					</div>

				<div class="crm_rad2 monthly_block" id="monthlyBlock" style="display:none;border:0px blue solid;margin:8px 0px 18px;">

					<div class='recur_monthly_start' style="display:inline-block;vertical-align:top;margin:0px 3px 0px 0px;width:150px;height:34px;">

						<input 
							id="recurringMonthStartDate" 
							name="recurringMonthStartDate" 
							class="datepick" 
							placeholder="Starting"
							type="text" 
							value="" 
							autocomplete="off"
							style="padding:8px;outline:none!important;border:2px #999 solid!important;" 
						/> 

					</div>

					<div class='recur_month_duration' style="display:inline-block;vertical-align:top;width:150px;margin:0px 3px 0px 0px!important;height:34px;">

						<input 
							type="number" 
							class="" 
							value="" 
							name="recurringMonthsDuration" 
							id="recurringMonthsDuration" 
							placeholder="No of months"
							autocomplete="off"
							min="1"
							max="12"
							style="outline:none!important;width:150px;border:2px #999 solid!important;border-radius:12px;margin:0px 3px 0px 0px!important;"
						/>	

					</div>	
					
					<div class='recur_month_type' style="display:inline-block;vertical-align:top;width:150px;margin:0px 3px 0px 0px">

						<select class="" id="recurMonthType" name="recurMonthType" style="outline:none!important;padding:4px;height:38px;border:2px #999 solid!important;"> 
							<option selected disabled style="color:#999!important;">Select rate</option>
							<option value="day">Month Day</option>
							<option value="date">Month Date</option>
            			</select>

					</div>

					<div class='recur_months_count' id='recurMonthsCount' style="display:none;height:34px;width:150px;border:2px #999 solid;border-radius:12px;margin:0px 3px 0px 0px;">

							<input 
								type="number" 
								class="" 
								value="" 
								name="recurringMonthDate" 
								id="recurringMonthDate" 
								placeholder="on nth date"
								style="outline:none!important;width:150px;border:none;"
								min=1
								max=31
							/>	

						</div>	

					<div class="crmRow" id="monthlyDayBlock" style="display:none;border:0px black solid;margin:8px 0px;">

						<div class='' style="display:inline-block;width:150px;margin:0px 3px 0px 0px;border-radius:12px;">

							<select class="" id="recurringMonthFrequency" name="recurringMonthFrequency" style="outline:none!important;border:2px #999 solid!important;"> 
								<option disabled selected>Frequency</option>
								<option value="1">First</option>
								<option value="2">Second</option>
								<option value="3">Third</option>
								<option value="4">Fourth</option>
							</select>

						</div>	
					
						<div class='' style="display:inline-block;width:148px;margin:0px 3px 0px 0px;">

							<select class="" id="recurringMonthDay" name="recurringMonthDay" style="outline:none!important;border-radius:12px;border:2px #999 solid!important;"> 
								<option disabled selected>Select day</option>
								<option>Monday</option>
								<option>Tuesday</option>
								<option>Wednesday</option>
								<option>Thursday</option>
								<option>Friday</option>
							</select>

						</div>	

						<div class='recur_months_count' style="display:inline-block;height:34px;width:150px;border:2px #999 solid;border-radius:12px;margin:0px 3px 0px 0px;">

							<input 
								type="number" 
								class="" 
								value="" 
								name="recurringMonthDate" 
								id="recurringMonthDate" 
								placeholder="of every month"
								style="outline:none!important;width:150px;border:none;"
								min=1
								max=31
								disabled
							/>	

						</div>	
						
					</div>

				</div>

				<?php 

					$chosenones  = array();
					$chosenones = array(1,6,7);

					$ddquery		=		"SELECT * FROM members WHERE memberDisplayName != '' AND memberArchive = 0 and (memberSPLIndustrial = 1 OR memberSPLRetail = 1) ORDER BY memberName ASC";
					$ddresult		=		$db -> query($ddquery);

				?>

				<!-- new method for assigning people to this activity -->

				<div class='ordholder' style='width:70%!important;position:relative;line-height:100%;min-height:45px;padding:4px;'>

					<div id='assignees_list' style='width:85%;'></div>

					<div id='toggle_assignees_block' class='crm_rad2' style='float:right;width:10%;position:absolute;top:0px;right:6px;'>
						<input type="radio" class="toggle_assignees" name="toggle_assignees" id="toggle_assignees"  ><label class="tt" for="toggle_assignees" id="toggle_assigness_label" style="background-color:#fff;" ><i class="fa-duotone fa-solid fa-people-group" aria-hidden="true" style="padding:2px 6px;--fa-primary-color:#008080; --fa-secondary-color:#999999;"></i></label>
					</div>
				</div>

				<div class="urgent">
					<input type="checkbox" name="btnUrgent" id="urgent" value="urgent" style="width:100%!important;">
					<label for="urgent" id="urgent_label" onclick=toggleUrgent() class='crm_urgent'>
						<i class='fa-duotone fa-solid fa-triangle-exclamation' style='--fa-primary-color: #000000; --fa-secondary-color: #F0D942; --fa-secondary-opacity: 01;font-size:1.25em;' aria-hidden="true" id="urgent_icon" ></i> Urgent
					</label>
				</div>
				
				<div class='ordholder' id='non_assignees_list' style='width:70%!important;min-height:45px;position:relative;line-height:100%;padding:4px;background-color:#f2f2f2;display:none;'>

					<div class='crm_rad2' style='float:right;width:10%;position:absolute;top:0px;right:6px;'>
						<input type="radio" class="toggle_non_team" name="toggle_non_team" id="toggle_non_team" value="" style="" ><label class="tt" for="toggle_non_team" style="background-color:#fff;"><i class="fa-duotone fa-solid fa-people-group" aria-hidden="true" style="padding:2px 6px;--fa-primary-color:#008080; --fa-secondary-color:#008080;"></i></label>
					</div>

				</div>

				<div class='ordholder' id='non_team_list' style='width:70%!important;position:relative;min-height:45px;line-height:100%;padding:4px;background-color:#f2f2f2;display:none;'></div>
					
				<div class="clear"></div>
				
				<div class="completed">
					<input type="checkbox" name="btnCompleted" id="completed" value="" style="width:100%!important;display:none;">
					<label for="completed" onclick="set_complete_toggle()" id="completed_label" name="completed_label" style="width:100%!important;padding:14px!important;border:#43924D 2px solid;">Complete</label>
				</div>
				
				<!-- hide these rows unless this activity is part of a recurring task - set in the js file -->

				<div class="crm_rad2 recur_info_row" style="width:66%!important;margin:8px 12px;line-height:140%;text-align:center;">

					<div id='recur_actions' class='recur_actions' style="margin:12px 0px;display:none;">
						
						<a class='show_series' onclick=show_schedule()><i class="fa-regular fa-calendar-lines-pen" style="font-size:1.5em;"></i> Show series</a>
						<a class='show_series' onclick=cancelFutureActivity()><i class="fa-regular fa-calendar-circle-exclamation" style="font-size:1.5em;"></i> Cancel future</a>
						<a class='show_series' onclick=cancelRecurrance()><i class="fa-regular fa-calendar-circle-minus" style="font-size:1.5em;"></i> Cancel this</a>
						<a class='show_series' onclick=cancelAllActivity()><i class="fa-regular fa-calendar-xmark" style="font-size:1.5em;"></i> Cancel all</a>

					</div>
<!-- 
						<div style="position:absolute;float:right;top:6px;right:-50px">
							<input type="radio" class="toggle_recur_info" name="toggle_recur_info" id="toggle_recur_info" value=""><label class="tt" for="toggle_recur_info" style="display:inline-block;vertical-align:top!important;float:right;"><i class="fa-solid fa-ellipsis-h" aria-hidden="true"></i></label>
						</div> -->

				</div>
				
				<button class="savecrm" id="editCRMActivity" onclick=save_crm_activity() style="float:right;width:113.72px;height:51px;border:2px solid #7C4491;">Save</button>
				<div id='recur_info' class='recur_info' style="margin:12px 0px;"></div>
			<!-- </form> -->
			
		</div>
		
	</div>
</div>

       
<?php } else { ?>
    <!--DONT  SHOW PAGE-->
 <?php } ?>

<script>

	$("#followUpAssigned").css("background-color", "yellow");

</script>
