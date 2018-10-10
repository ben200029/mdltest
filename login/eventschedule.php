<?php
ob_start();

require_once('config.php');
require_once('settings.php');
require_once('header.php');

if (!login_check($db)) {
    Leave(SITE_URL);
}
if (isset($_GET['logout'])) {
    logout();
    Leave(SITE_URL);
}

require_once ('navbar.php');
require_once ('functions_event.php');
$roles = array('Admin', 'Sales Rep', 'Sales Manager');

$userID = $_SESSION['user']["id"];
$roleInfo = getRole($db, $userID);
$role = $roleInfo['role'];
if (!in_array($role, $roles)) {
    Leave(SITE_URL . "/no-permission.php");
}

$salesRepDetails = $db->row("SELECT * FROM tblsalesrep WHERE Guid_user=:userid ORDER BY first_name, last_name", array('userid' => $userID));

// Account table
$clause = " ORDER BY account";
$accountdt = $db->selectAll('tblaccount', $clause);

$thisMessage = "";
$error = array();

$roleID = $roleInfo['Guid_role'];

$default_account = "";

if (isset($_POST['search']) && (strlen($_POST['from_date']) || strlen($_POST['to_date']))) {
    verify_input($error);
}
?>
<link rel="stylesheet" href="assets/eventschedule/css/fullcalendar.css" />
<link rel="stylesheet" href="assets/eventschedule/css/bootstrap-datetimepicker.min.css">
<script src="assets/eventschedule/js/jquery.min.js"></script>
<script src="assets/eventschedule/js/jquery-ui.min.js"></script>
<script src="assets/eventschedule/js/moment.min.js"></script>
<script src="assets/eventschedule/js/fullcalendar.min.js"></script>
<script src="assets/eventschedule/js/bootstrap-datetimepicker.min.js"></script>

<style>
    .col-md-1 { width: 10.333333% !important;}
    .container { max-width: 100% !important;}
    #datetimepicker1{ position: relative; width: 172px; }
    #datetimepicker1 input{ width: 100%; }
    #datetimepicker1 img{ position: absolute; top: 8px; right: 5px;}

    #datetimepicker2{ position: relative; /* width: 172px; */ }
    #datetimepicker2 input{ width: 100%; }
    #datetimepicker2 img{ position: absolute; top: 8px; right: 5px;}
    textarea.form-control{height: auto !important;}
    .fc-event-container {padding: 5px 0 !important;}

    .fc-event {
        box-shadow:  0 0 .25em !important;
        border-radius:  .625em !important;
        background-color: #fff !important;
        color: #000 !important;
    }
    .fc-axis{display: none !important;}

    /* The Modal (background) */
    .schedulemodal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 10; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content/Box */
    .schedulemodal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 5px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
    }

    /* The Close Button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        padding: 4px 9px;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    .evtcontent{ padding: 5px 5px; white-space: pre-wrap !important;}
    .evttitle{font-weight: bold; color: #3a87ad; white-space: nowrap !important; overflow: hidden;text-overflow: ellipsis;}

    .fc-month-view .evttitle, .fc-basicWeek-view .evttitle{width:90%;}
    .fc-basicWeek-view .fc-comments{width: 90%;}
    .rightCircleicon1{ position: absolute; width: 20px; height: 20px; right: 0px; top: -1px; background-image: url("assets/eventschedule/images/icon_brca_day.png"); background-repeat: no-repeat;background-size: 20px 20px; pointer-events: visible;}
    .rightCircleicon2{ position: absolute; width: 20px; height: 20px; right: 0px; top: -1px; background-image: url("assets/eventschedule/images/icon_health_fair.png"); background-repeat: no-repeat;background-size: 20px 20px;pointer-events: visible;}
   
    .numberCircle {
        padding: 14px;
        text-align: center;
        position: relative;
        top: 0;
        left: 0;
        font-size: 30px;
        font-family: "Open Sans";
        color: rgb(255, 255, 255);
        font-weight: bold;
        text-shadow: 0px 0px 10px #654c07;
        background: url(assets/eventschedule/icons/icon_brca_day.png);
        background-size: 100%;
        background-repeat: no-repeat;
        background-position: center;
    }
    .numberCircleContainer {
        margin: 15px 0;
        text-align: center;
    }
    #calendar .fc-body td {vertical-align: top !important;}  
    .fc-event-container > a {min-height: 65px;}
    .comments-log .commentlogss { padding: 8px 0;  border-bottom: 1px solid #00000009; }
    .comments-log .commentlogss p { font-size: 14px; }
    .comments-log { max-height: 250px; overflow-x: auto; }
    .fc-basicDay-view .day_header{
        font-size: 20px;
        font-family: "Open Sans";
        color: rgb(58, 135, 173);
        font-weight: bold;
        white-space: normal !important;
    }

    .fc-basicDay-view .fc-content {
        overflow: initial;
        width: 30%;
        padding: 1% 0 1% 1%;
    }

    .fc-basicDay-view .fc-day-grid-event {
        display: flex;
        justify-content: space-between;
    }

    .fc-basicDay-view h1{
        width:100%;
        text-align:left;
        font-size: 20px;
        font-family: "Open Sans";
        color: rgba(64, 64, 64, 0.878);
        padding-top: 10px;
    }

    .fc-basicDay-view .fc-comments{
        white-space: initial !important;
        text-overflow: ellipsis;
        text-align: center;
        padding-bottom: 1%;
        flex-grow:1;
        margin-bottom: 1%;
    }

    .fc-comments .day-comments{
        background: rgb(1,1,1,0.1);
        height: 100%;
        width: 80%;
        text-align: left;
        padding-top: 10px;
        clear: both;
        padding-left: 10px;
    }

    .fc-basicDay-view .fc-logo{
        width: 23%;
        display: flex;
        align-items: center;
    }

    .day_stats{
        margin:auto;
        font-size: 1.2em;
        padding-top: 1em;
        width:110%;
    }

    .day_stats p {
        display: inline;
        padding: 0 5% 0 0;
    }


    .fc-basicDay-view .fc-salesrep{
        line-height: 2.3em;
    }

    .fc-basicDay-view .fc-salesrep span,
    .fc-basicDay-view .fc-number span{
        font-weight:bold;
    }

    .fc-basicDay-view .fc-logo img{  width:100%; max-width: 180px;   }

    .fc-month-view .fc-scroller{ height: auto !important; }


    .fc-comments{white-space: nowrap !important; overflow: hidden;text-overflow: ellipsis;}
    tr > td > .fc-day-grid-event{ pointer-events: none; }
    .show-stats { pointer-events: visible; }
    .fc-basicDay-view .evttitle{ white-space: normal !important; }
    #home form{ padding: 20px; }
    .fc-salesrep a{ margin-bottom: 5px; }
    .show-stats, .evttitle a, .fc-salesrep a { pointer-events: visible; }
    #action_palette{ padding-top: 4.5em; }
    main{ padding-top: 6.9em; }
    .summary_brca_icon {
        height: 50px;
    }
    .fc-salesrep a{color:#000; pointer-events:visible;}

    @media only screen 
    and (min-device-width : 768px) 
    and (max-device-width : 1024px) 
    and (orientation : portrait) { 
        .fc-basicDay-view .fc-day-grid-event {
            flex-direction: column;
        }

        .fc-basicDay-view .fc-content {
            width:100%;
        }

        .day_stats {
            padding-top: 0;
            width: 50%;
            text-align: center;
        }

        .fc-basicDay-view .fc-comments {
            width:100%;
        }

        .fc-basicDay-view .fc-logo{
            width: 100%;
        }
    }
    @media only screen 
    and (min-device-width : 768px) 
    and (max-device-width : 1024px) 
    and (orientation : landscape) { 

    }
    
</style>
<script>
    
    Date.prototype.getUnixTime = function() { return this.getTime()/1000|0 };
    if(!Date.now) Date.now = function() { return new Date(); }
    Date.time = function() { return Date.now().getUnixTime(); }

    $(document).ready(function () {

        var salereps = $('#salerepid').val();
        if(salereps){
            $('#accountopt option').remove();
            $('#accountopt').html('<option value="0">Account</option>');
            $.ajax({
                type : 'POST',
                data : 'salerepId='+ salereps,
                dataType: 'json',
                url : 'accountselection.php',
                success : function(data){
                    $.each(data, function(k, v) {
                        if(v.id) $('#accountopt').append('<option value="' + v.id + '">' + v.name + '</option>');
                    });
                }
            });
        }    

        $(".f2").width('95%');
        $("input[name='eventtype']").click(function () {
            var evtType = $(this).val();
            if (evtType == 2) {
                $(".f2").width('100%');
                $("div.accounttype").hide();
                $("div.healthcare").show();
            } else {
                $(".f2").width('95%');
                $("div.accounttype").show();
                $("div.healthcare").hide();
            }
        });

        $("input[name='modaleventtype']").click(function () {
            var modalevtType = $(this).val();
            if (modalevtType == 2) {
                $("div.modalaccounttype").addClass('hide').removeClass('show');//  hide();
                $("div.modalhealthcare").addClass('show').removeClass('hide'); //show();
            } else {
                $("div.modalaccounttype").addClass('show').removeClass('hide'); //show();
                $("div.modalhealthcare").addClass('hide').removeClass('show'); //hide();
            }
        });
        
        if (localStorage.salesrepValue) {
            $('#salesrepfilter').val(localStorage.salesrepValue);
        }
        if (localStorage.accountValue) {
            $('#accountfilter').val(localStorage.accountValue);
        }
        if (localStorage.salesrepValue != 0 || localStorage.accountValue != 0) {
            var cursource = 'eventload.php?salerepId=' + localStorage.salesrepValue + '&accountId=' + localStorage.accountValue;
        }
        else{
            var cursource = 'eventload.php';
        }

        $('#salesrepfilter,#accountfilter').change(function () {
            var salesrep = 0;
            var account = 0;
            salesrep = $('#salesrepfilter option:selected').val();
            account = $('#accountfilter option:selected').val();
            localStorage.setItem('salesrepValue', salesrep );
            localStorage.setItem('accountValue', account );
            var allcursource = 'eventload.php';
            if (salesrep != 0 || account != 0) {
                cursource = 'eventload.php?salerepId=' + salesrep + '&accountId=' + account;
            }

            $('#calendar').fullCalendar('removeEventSources');
            $('#calendar').fullCalendar('refetchEvents');
            if (salesrep == 0 && account == 0) {
                $('#calendar').fullCalendar('addEventSource', allcursource);
            } else {
                $('#calendar').fullCalendar('addEventSource', cursource);
            }
            $('#calendar').fullCalendar('refetchEvents');

        });

        // when summary button is clicked
        $('#summary').on('click touchstart', function () {
            var summarycursource = 'summaryeventload.php';

            $('#calendar').fullCalendar('removeEventSources');
            $('#calendar').fullCalendar('refetchEvents');
            $('#calendar').fullCalendar('addEventSource', summarycursource);
            $('#calendar').fullCalendar('refetchEvents');
            $("#detail").css("background", "#90bcf7");
            $("#summary").css("background", "linear-gradient(to bottom, rgba(255,255,255,1) 46%,rgba(224,224,224,1) 64%,rgba(243,243,243,1) 100%)");
        });

        // when detail button is clicked
        $('#detail').on('click touchstart', function () {
            var detailcursource = 'eventload.php';

            $('#calendar').fullCalendar('removeEventSources');
            $('#calendar').fullCalendar('refetchEvents');
            $('#calendar').fullCalendar('addEventSource', detailcursource);
            $('#calendar').fullCalendar('refetchEvents');
            $("#summary").css("background", "#90bcf7");
            $("#detail").css("background", "linear-gradient(to bottom, rgba(255,255,255,1) 46%,rgba(224,224,224,1) 64%,rgba(243,243,243,1) 100%)");
        });

        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            views: {
                week: {
                    titleFormat: '[Week of ] MMMM D, YYYY',
                    titleRangeSeparator: ' to ',
                }
            },
            defaultView: 'basicWeek',
            eventSources: cursource,
            selectable: true,
            selectHelper: true,
            editable: false,
            eventOverlap: false,
            dayRender: function (date, cell) {
                var today = new Date();
                if (date._d.getDate() === today.getDate()) {
                    // cell.css("background-color", "red");
                }
            },
            eventClick: function (event, jsEvent, view)
            {
                var moment = $.datepicker.formatDate('yy-mm-dd', new Date());
                // Get the modal
                var modal = document.getElementById('myModal');
                //var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                var thisdate = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                if (moment <= thisdate) {
                    $('#updateEvent').find('input, button, select').prop("disabled", false);
                    $('#eventupdate').prop("disabled", true);
                } else {
                    $('#updateEvent').find('input, button, select').prop("disabled", true);
                }
                var frmstart = $.fullCalendar.formatDate(event.start, "MM/DD/Y");
                $('#myModal').find('#modaleventstart').val(frmstart);
                $("#modalsalesrepopt").val(event.salesrepid);
                $('#modalaccountopt option').remove();
                $('#modalaccountopt').html('<option value="0">Account</option>');
                $.ajax({
                    type : 'POST',
                    data : 'salerepId='+ event.salesrepid,
                    dataType: 'json',
                    url : 'accountselection.php',
                    success : function(data){
                        $.each(data, function(k, v) {
                            if(v.id) $('#modalaccountopt').append('<option value="' + v.id + '">' + v.name + '</option>');
                        });
                        $("#modalaccountopt").val(event.accountid);
                    }
                });
                
                popup_comment(event.id);
                
                $("#modalfull_name_id").val(event.hltname);
                $("#modalstreet1_id").val(event.street1);
                $("#modalstreet2_id").val(event.street2);
                $("#modalcity_id").val(event.city);
                $("#modalstate_id").val(event.state);
                $("#modalzip_id").val(event.zip);
                $("#modalid").val(event.id);
                $("#modalsalerepid").val(event.salesrepid);
                $("#modalhealthcareid").val(event.healthcareid);

                if (event.title == 'BRCA Day') {
                    $('#brcaradio').prop("checked", true);
                    var modalevtType = $(this).val();
                    $("div.modalaccounttype").show();
                    $("div.modalhealthcare").hide();
                } else {
                    $('#healthradio').prop("checked", true);
                    $("div.modalaccounttype").hide();
                    $("div.modalhealthcare").show();
                }
                var today = new Date();
                var currentDate = today.getDate();
                var eventDate = $.fullCalendar.formatDate(event.start, "DD");
                var parsedNow =  new Date(today).getUnixTime();
                var parsedEventTime = new Date(event.start).getUnixTime();
                //if (parsedEventTime >= parsedNow) {
                    //modal.style.display = "block";
                    $("#myModal").delay( 100 ).fadeIn( 400 )
                //}

            },
            eventMouseover: function (calEvent, jsEvent) {
                if (!calEvent.evtCnt) {
                    var message = '';
                    if (calEvent.salesrep == null)
                        message += 'SalesRep is not assigned';
                    if (calEvent.salesrep == null && calEvent.account == null)
                        message += ' and ';
                    if (calEvent.account == null && calEvent.title == 'BRCA Day')
                        message += 'Account Number is missing';
                    var mouseOver = "Registerd: " + calEvent.registeredCnt + "<br />";
                    mouseOver += "Completed: " + calEvent.completedCnt + "<br />";
                    mouseOver += "Qualified: " + calEvent.qualifiedCnt;
                    if (mouseOver != '') {
                        bgclr = '#FF4500';
                        if(message == ""){
                            message = mouseOver;
                            bgclr = '#FFF';
                        }
                        var tooltip = '<div class="tooltipevent" style="padding:20px 20px;min-width:100px;min-height:100px;background:'+ bgclr + ';color:#000;position:absolute;z-index:10001;">' + message + '</div>';
                        $("body").append(tooltip);
                        //$(this).mouseover(function (e) {
                        $(".show-stats").mouseover(function (e) {
                            $(this).css('z-index', 10000);
                            $('.tooltipevent').fadeIn('500');
                            $('.tooltipevent').fadeTo('10', 1.9);

                        }).mousemove(function (e) {
                            $('.tooltipevent').css('top', e.pageY + 10);
                            $('.tooltipevent').css('left', e.pageX + 20);
                        });
                    }
                }
                
            },

            eventMouseout: function (calEvent, jsEvent) {
                $(this).css('z-index', 8);
                $('.tooltipevent').remove();
            },
            eventRender: function (event, element, view) {
                //element.attr('href', 'javascript:void(0);');
                $('.show-stats').off('click');
                var today = new Date();
                var currentDate = today.getDate();
                                
                var eventDate = $.fullCalendar.formatDate(event.start, "DD");
                var parsedNow =  new Date(today).getUnixTime();
                var parsedEventTime = new Date(event.start).getUnixTime();
                
                var time = $.fullCalendar.formatDate(event.start, "hh:mm a");
                var logo = "";
                var account = "";
                var name = "";
                var salesrep = "";
                if (event.logo)
                    logo = '<div class="fc-logo">' + event.logo + '</div>';
                if (event.account)
                    account = event.account + ' - ';

                if (event.salesrep)
                    salesrep = '<div class="fc-salesrep" ><a href="salesreps.php?action=edit&id='+ event.salesrepid +'">' + event.salesrep + '</a></div>';
                var cmts = '';

                var view = $('#calendar').fullCalendar('getView');
                if(view.name == 'basicDay'){
                    var html = "";
                    cmts = '<div class="fc-number"><span>Account Number: </span>' + event.account + '</div>';
                    
                    cmts += '<div class = "day_stats">';
                    var eventData = { action: 'getStates', account: event.account, regitered:28, qualified: 29, completed: 36,  submitted: 1};
                    $.ajax({
                        url: "ajaxHandlerEvents.php",
                        type: "POST",
                        data: eventData,
                        success: function (res)
                        {
                            var result = JSON.parse(res);
                            cmts += '<p class="show-stats"><span class="silhouette">' + result["reg"] + ' <img src="assets/eventschedule/icons/silhouette_icon.png"></span> | <span class="checkmark"> '+ result["qua"] + ' <img src="assets/eventschedule/icons/checkmark_icon.png"></span> | <span class="dna">'+ result["com"] + ' <img src="assets/eventschedule/icons/dna_icon.png"></span> | <span class="flask">'+result["sub"]+'<img src="assets/eventschedule/icons/flask_icon.png"></span></p>' +
                                '';
                    
                        }
                    });
                    
                    cmts += '</div></div>'
                    cmts += '<div class="fc-comments"><h1>Comments</h1><div class = "day-comments">';
                    if(event.comments != null) 
                          cmts += event.comments;
                    cmts += '</div></div>'
                    if(event.logo != null)
                        cmts += '<div class="fc-logo"><img src = "../login/images/practice/' + event.logo + '" onError="imgError(this);"/></div>';
                }

                var icon = '';
                if (event.title == 'BRCA Day') {
                    icon = 'rightCircleicon1';
                    if (event.name)
                        name = event.name;
                } else {
                    icon = 'rightCircleicon2';
                    if (event.hltname)
                        name = event.hltname;
                }

                var borderColor = 'border: 2px solid #30a844 !important'; // default color
                if (event.color) {
                    borderColor = "border: 2px solid " + event.color + " !important";
                }
                
                var modifiedName = sentenceCase(name);
                
                var content = '<div class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable" style="' + borderColor + '">' +
                        '<div class="fc-content evtcontent">' +
                        '<div class="fc-title evttitle"><a href=accounts.php?account_id="' + event.accountid + '">' + modifiedName + '</a></div>' +
                        salesrep + cmts +
                        '<div class="' + icon + '"></div>' +
                        '</div>' +
                        '</div>';

                if (event.evtCnt) {
                    $("#summary").css("background","linear-gradient(to bottom, rgba(255,255,255,1) 46%,rgba(224,224,224,1) 64%,rgba(243,243,243,1) 100%)");
                    $("#detail").css("background","#90bcf7");
                    var content = '<div class="fc-content evtcontent days-' + eventDate + '" style="padding: 0 20px;">';
                    content += '<div class="numberCircleContainer"><span class="numberCircle">' + event.evtCnt + '</span></></div>';
                    content += '<div><img src="assets/eventschedule/icons/silhouette_icon.png" width="13" style="margin-right:5px;">Registered <span style="float:right">' + event.registeredCnt + '</span></div>';
                    content += '<div><img src="assets/eventschedule/icons/checkmark_icon.png" style="margin-right:5px;">Completed <span style="float:right">' + event.completedCnt + '</span></div>';
                    content += '<div><img src="assets/eventschedule/icons/dna_icon.png" style="margin-right:5px;">Qualified <span style="float:right">' + event.qualifiedCnt + '</span></div>';
                    content += '<div><img src="assets/eventschedule/icons/flask_icon.png" style="margin-right:5px;">Submitted <span style="float:right">0</span></div>';
                    content += '</div>';
                    return $(content);
                } else {
                    $("#summary").css("background","#90bcf7");
                    $("#detail").css("background","linear-gradient(to bottom, rgba(255,255,255,1) 46%,rgba(224,224,224,1) 64%,rgba(243,243,243,1) 100%)");
                    if (parsedEventTime < parsedNow) {
                        var content = '<div class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable days-' + eventDate + '"  style="' + borderColor + '">' +
                                '<div class="fc-content evtcontent">'+                                
                                '<div class="fc-title evttitle"><a href="accounts.php?account_id=' + event.accountid + '">' + modifiedName + '</a></div>' + salesrep + cmts;
                                if(view.name != 'basicDay'){
                                    
                                    var eventData = { action: 'getStates', account: event.account, regitered:28, qualified: 29, completed: 36,  submitted: 1};
                                    $.ajax({
                                        url: "ajaxHandlerEvents.php",
                                        type: "POST",
                                        data: eventData,
                                        success: function (res)
                                        {
                                            var result = JSON.parse(res);
                                            cmts += '<div class="show-stats"><span class="silhouette">' + result["reg"] + ' <img src="assets/eventschedule/icons/silhouette_icon.png"></span> | <span class="checkmark"> '+ result["qua"] + ' <img src="assets/eventschedule/icons/checkmark_icon.png"></span> | <span class="dna">'+ result["com"] + ' <img src="assets/eventschedule/icons/dna_icon.png"></span> | <span class="flask">'+result["sub"]+' <img src="assets/eventschedule/icons/flask_icon.png"></span></div>';
                                    
                                        }
                                    });
                                    /*content += '<div class="show-stats"><span class="silhouette">' + event.registeredCnt + ' <img src="assets/eventschedule/icons/silhouette_icon.png"></span> | <span class="checkmark"> '+ event.qualifiedCnt + ' <img src="assets/eventschedule/icons/checkmark_icon.png"></span> | <span class="dna">'+ event.completedCnt + ' <img src="assets/eventschedule/icons/dna_icon.png"></span> | <span class="flask">0 <img src="assets/eventschedule/icons/flask_icon.png"></span></div>';*/
                                    
                                }
                            content += '<div class="' + icon + '"></div>';   
                            content += '</div> </div>';
                        return $(content);
                    } else {
                        return $(content);
                    }
                }


            },
            eventAfterRender: function (event, element, view) {
                if (!event.evtCnt) {
                    if ((event.salesrep == null || event.account == null) && event.title == 'BRCA Day') {
                        //element.css('background-color', '#FF6347');
                        element.css('background-color', '#fff');
                        element.css('color', '#000');
                        element.css('border-color', '#FF6347');
                    } else if (event.salesrep == null && event.title != 'BRCA Day') {
                        //element.css('background-color', '#FF6347');
                        element.css('background-color', '#fff');
                        element.css('color', '#000');
                        element.css('border-color', '#FF6347');
                    } else {
                        element.css('background-color', '#fff');
                        element.css('color', '#000');
                    }
                }
                
            },
            eventAfterAllRender: function (event, element, view) {
                //$(".days-06:first").css("display", "block");
                $("td.fc-event-container").each(function() {
                    //$(this).removeAttr('rowspan');
                });
            },
        });

        // Whenever the user clicks on the "save" button
        var clickEventType=((document.ontouchstart!==null)?'click':'touchstart');
        //alert(clickEventType);
        $('#eventsave').bind(clickEventType, function () {
            var errorMsg = "";
            if ($("#salesrepopt").val() == "") {
                errorMsg = "Please select Genetic Consultant"
            }
            if ($("input[name='eventtype']:checked").val() == 1 && $('#accountopt').val() == 0) {
                if (errorMsg)
                    errorMsg += "\n";
                errorMsg += "Please select Account";
            }
            if (errorMsg) {
                alert(errorMsg);
                return false;
            }

            var title = $("input[name='eventtype']:checked").parent('label').text();
            if ($('#eventstart').val() && ($('#salerepid').val() || $('#accountopt').val() != 0)) {
                var start = dateFormat($('#eventstart').val(), "yyyy-mm-dd");
                var end = dateFormat($('#eventstart').val(), "yyyy-mm-dd");
                var accountId = $('#accountopt').val();
                var salesrepId = $('#salerepid').val() ? $('#salerepid').val() : 0;
                var comments = $('#comment').val();
                var full_name = $('#full_name_id').val() ? $('#full_name_id').val() : '';
                var street1 = $('#street1_id').val() ? $('#street1_id').val() : '';
                var street2 = $('#street2_id').val() ? $('#street2_id').val() : '';
                var city = $('#city_id').val() ? $('#city_id').val() : '';
                var state = $('#state_id').val() ? $('#state_id').val() : '';
                var zip = $('#zip_id').val() ? $('#zip_id').val() : '';
                var userid = $('#commenterid').val() ? $('#commenterid').val() : '';

                var eventData = {
                    title: title,
                    start: start,
                    end: end,
                    salesrepId: salesrepId,
                    accountId: accountId,
                    comments: comments,
                    full_name: full_name,
                    street1: street1,
                    street2: street2,
                    city: city,
                    state: state,
                    zip: zip,
                    userid: userid,
                };
                $.ajax({
                    url: "eventinsert.php",
                    type: "POST",
                    data: eventData,
                    success: function ()
                    {
                        //$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                        $('#calendar').fullCalendar('refetchEvents');
                        //alert("Added Successfully");
                    }
                })

            } else {
                return false;
            }
        });

        $("#modalcomment, #modalfull_name_id, #modalstreet1_id, #modalstreet2_id, #modalcity_id, #modalstate_id, #modalzip_id").bind("keyup change", function(e) {
            $(this).addClass('updated');
            if($(this).val() != '')
                $('button#eventupdate').prop('disabled', false);
            else
                $('button#eventupdate').prop('disabled', true);
        })
        /*$("#modalcomment").change(function(){
            $(this).addClass('updated');
            $('button#eventupdate').prop('disabled', false);
        })*/
        // Whenever the user clicks on the "update" button
        $('#eventupdate').bind(clickEventType, function () {
            var commentid = "";
            if($(this).hasClass("edited")){
                $(this).removeClass("edited")
                commentid = $('#eventupdate').attr('data-commentid');
            }
            var errorMsg = "";
            if ($("#modalsalesrepopt").val() == "0") {
                errorMsg = "Please select Genetic Consultant"
            }
            if ($("input[name='modaleventtype']:checked").val() == 1 && ($('#modalaccountopt').val() == 0 || $('#modalaccountopt').val() == null )) {
                if (errorMsg)
                    errorMsg += "\n";
                errorMsg += "Please select Account";
            }
            if (errorMsg) {
                alert(errorMsg);
                return false;
            }
            var title = $("input[name='modaleventtype']:checked").parent('label').text();
            if ($('#modaleventstart').val() && ($('#modalsalerepid').val() || $('#modalaccountopt').val() != 0)) {
                var start = moment($('#modaleventstart').val()).format("YYYY-MM-DD");
                var end = moment($('#modaleventstart').val()).format("YYYY-MM-DD");   
                var accountId = $('#modalaccountopt').val();
                var salesrepId = $('#modalsalerepid').val() ? $('#modalsalerepid').val() : 0;
                var comments = $('#modalcomment').val();
                var full_name = $('#modalfull_name_id').val() ? $('#modalfull_name_id').val() : '';
                var street1 = $('#modalstreet1_id').val() ? $('#modalstreet1_id').val() : '';
                var street2 = $('#modalstreet2_id').val() ? $('#modalstreet2_id').val() : '';
                var city = $('#modalcity_id').val() ? $('#modalcity_id').val() : '';
                var state = $('#modalstate_id').val() ? $('#modalstate_id').val() : '';
                var zip = $('#modalzip_id').val() ? $('#modalzip_id').val() : '';
                var modalhealthcareid = $('#modalhealthcareid').val() ? $('#modalhealthcareid').val() : '';
                var modalid = $('#modalid').val();
                var userid = $('#update_commenterid').val();
                var eventData = {
                    modaltitle: title,
                    modalstart: start,
                    modalend: end,
                    modalsalesrepId: salesrepId,
                    modalaccountId: accountId,
                    modalcomments: comments,
                    full_name: full_name,
                    street1: street1,
                    street2: street2,
                    city: city,
                    state: state,
                    zip: zip,
                    modalid: modalid,
                    userid: userid,
                    modalhealthcareid: modalhealthcareid,
                    commentid: commentid,
                    action: 'eventupdate'
                };

                $.ajax({
                    url: "ajaxHandlerEvents.php",
                    type: "POST",
                    data: eventData,
                    success: function (res)
                    {
                        //$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                        $('#calendar').fullCalendar('refetchEvents');
                        popup_comment(modalid);
                        //modal.style.display = "none";
                    }
                })

            } else {
                return false;
            }
        });

        // cancel update
        $('#eventcancel').bind(clickEventType, function () {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        });

        // Whenever the user clicks on the "delete" button
        $('#eventdelete').bind(clickEventType, function () {
            var modalid = $('#modalid').val();
            if (confirm("Are you sure you want to remove it?"))
            {
                var id = modalid;
                $.ajax({
                    url: "eventdelete.php",
                    type: "POST",
                    data: {id: id},
                    success: function ()
                    {
                        $('#calendar').fullCalendar('refetchEvents');
                        var modal = document.getElementById('myModal');
                        modal.style.display = "none";
                        //alert("Event Removed");
                    }
                })
            }

        });

        $('#salesrepopt').on('change', function () {

                var selec = $('#accountopt option:selected').val();
                $('#accountopt option').remove();
                $('#accountopt').html('<option value="0">Account</option>');
                $.ajax({
                    type : 'POST',
                    data : 'salerepId='+ this.value,
                    dataType: 'json',
                    url : 'accountselection.php',
                    success : function(data){
                        console.log(data);
                        $.each(data, function(k, v) {
                            if(selec == v.id) var selected = 'selected';
                            if(v.id) $('#accountopt').append('<option value="' + v.id + '" '+ selected + '>' + v.name + '</option>');
                        });


                    }
                });
            $('#salerepid').val(this.value);
        });
        $('#modalsalesrepopt').on('change', function () {
            var selec = $('#modalaccountopt option:selected').val();
            $('#modalaccountopt option').remove();
            $('#modalaccountopt').html('<option value="0">Account</option>');
            $.ajax({
                type : 'POST',
                data : 'salerepId='+ this.value,
                dataType: 'json',
                url : 'accountselection.php',
                success : function(data){
                    $.each(data, function(k, v) {
                        if(selec == v.id) var selected = 'selected';
                        if(v.id) $('#modalaccountopt').append('<option value="' + v.id + '" '+ selected + '>' + v.name + '</option>');
                    });


                }
            });
            $('#modalsalerepid').val(this.value);
        });
        
        $('#accountopt').on('change', function () {
                var selec = $('#salesrepopt option:selected').val();
                $('#salesrepopt option').remove();
                $('#salesrepopt').html('<option value="">Genetic Consultant</option>');
                $.ajax({
                    type : 'POST',
                    data : 'accountId='+ this.value,
                    dataType: 'json',
                    url : 'salesrepselection.php',
                    success : function(data){
                        $.each(data, function(k, v) {
                            if(selec == v.id) var selected = 'selected';
                            if(v.id) $('#salesrepopt').append('<option value="' + v.id + '" '+ selected + '>' + v.name + '</option>');
                        });


                    }
                });
                
                var accountName =  $('#accountopt option:selected').text();
                var accountIdArr = accountName.split("-");
                var accountId = accountIdArr[0];
                if(accountId != 'Account'){
                    var ajaxUrl = baseUrl+'/ajaxHandler.php';
                    $.ajax( ajaxUrl , {
                        type: 'POST',
                        data: {
                           get_account_info: '1',
                           account_id: accountId
                        },
                        success: function(response) {
                            var result = JSON.parse(response);
                            var accountData = result['accountInfo'];
                            var providers = result['providers']
                            if(providers.length == 0){
                                if(!confirm("No Provider in this Account. Do you want to continue?")){
                                    $("#accountopt").val('0');
                                }    
                            }    
                        },
                        error: function() {
                            alert('0');
                        }
                    });
                }    
        });
        
        $('#modalaccountopt').on('change', function () {
                var selec = $('#modalsalesrepopt option:selected').val();
                $('#modalsalesrepopt option').remove();
                $('#modalsalesrepopt').html('<option value="">Genetic Consultant</option>');
                $.ajax({
                    type : 'POST',
                    data : 'accountId='+ this.value,
                    dataType: 'json',
                    url : 'salesrepselection.php',
                    success : function(data){
                        $.each(data, function(k, v) {
                            if(selec == v.id) var selected = 'selected';
                            if(v.id) $('#modalsalesrepopt').append('<option value="' + v.id + '" '+ selected + '>' + v.name + '</option>');
                        });


                    }
                });
                
                var accountName =  $('#modalaccountopt option:selected').text();
                var accountIdArr = accountName.split("-");
                var accountId = accountIdArr[0];
                if(accountId != 'Account'){
                    var ajaxUrl = baseUrl+'/ajaxHandler.php';
                    $.ajax( ajaxUrl , {
                        type: 'POST',
                        data: {
                           get_account_info: '1',
                           account_id: accountId
                        },
                        success: function(response) {
                            var result = JSON.parse(response);
                            var accountData = result['accountInfo'];
                            var providers = result['providers']
                            if(providers.length == 0){
                                if(!confirm("No Provider in this Account. Do you want to continue?")){
                                    $("#modalaccountopt").val('0');
                                }    
                            }    
                        },
                        error: function() {
                            alert('0');
                        }
                    });
                } 
        });

        
        var dateFormat = function () {
            var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
                    timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
                    timezoneClip = /[^-+\dA-Z]/g,
                    pad = function (val, len) {
                        val = String(val);
                        len = len || 2;
                        while (val.length < len)
                            val = "0" + val;
                        return val;
                    };

            // Regexes and supporting functions are cached through closure
            return function (date, mask, utc) {
                var dF = dateFormat;

                // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
                if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
                    mask = date;
                    date = undefined;
                }

                // Passing date through Date applies Date.parse, if necessary
                date = date ? new Date(date) : new Date;
                if (isNaN(date))
                    throw SyntaxError("invalid date");

                mask = String(dF.masks[mask] || mask || dF.masks["default"]);

                // Allow setting the utc argument via the mask
                if (mask.slice(0, 4) == "UTC:") {
                    mask = mask.slice(4);
                    utc = true;
                }

                var _ = utc ? "getUTC" : "get",
                        d = date[_ + "Date"](),
                        D = date[_ + "Day"](),
                        m = date[_ + "Month"](),
                        y = date[_ + "FullYear"](),
                        H = date[_ + "Hours"](),
                        M = date[_ + "Minutes"](),
                        s = date[_ + "Seconds"](),
                        L = date[_ + "Milliseconds"](),
                        o = utc ? 0 : date.getTimezoneOffset(),
                        flags = {
                            d: d,
                            dd: pad(d),
                            ddd: dF.i18n.dayNames[D],
                            dddd: dF.i18n.dayNames[D + 7],
                            m: m + 1,
                            mm: pad(m + 1),
                            mmm: dF.i18n.monthNames[m],
                            mmmm: dF.i18n.monthNames[m + 12],
                            yy: String(y).slice(2),
                            yyyy: y,
                            h: H % 12 || 12,
                            hh: pad(H % 12 || 12),
                            H: H,
                            HH: pad(H),
                            M: M,
                            MM: pad(M),
                            s: s,
                            ss: pad(s),
                            l: pad(L, 3),
                            L: pad(L > 99 ? Math.round(L / 10) : L),
                            t: H < 12 ? "a" : "p",
                            tt: H < 12 ? "am" : "pm",
                            T: H < 12 ? "A" : "P",
                            TT: H < 12 ? "AM" : "PM",
                            Z: utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
                            o: (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
                            S: ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
                        };

                return mask.replace(token, function ($0) {
                    return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
                });
            };
        }();

// Some common format strings
        dateFormat.masks = {
            "default": "ddd mmm dd yyyy HH:MM:ss",
            shortDate: "m/d/yy",
            mediumDate: "mmm d, yyyy",
            longDate: "mmmm d, yyyy",
            fullDate: "dddd, mmmm d, yyyy",
            shortTime: "h:MM TT",
            mediumTime: "h:MM:ss TT",
            longTime: "h:MM:ss TT Z",
            isoDate: "yyyy-mm-dd",
            isoTime: "HH:MM:ss",
            isoDateTime: "yyyy-mm-dd'T'HH:MM:ss",
            isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
        };

// Internationalization strings
        dateFormat.i18n = {
            dayNames: [
                "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
                "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
            ],
            monthNames: [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
                "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
            ]
        };

// For convenience...
        Date.prototype.format = function (mask, utc) {
            return dateFormat(this, mask, utc);
        };

        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });

    function sentenceCase(str) {
        if ((str === null) || (str === ''))
            return false;
        else
            str = str.toString();

        return str.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

    /* New Code */
    function imgError(image) {
        image.onerror = "";
        image.src = "../login/images/logo-placeholder.png";
        return true;
    }

    function popup_comment(eventID){
        $.ajax({
            type : 'POST',
            data : { action: 'getComment', eventid: eventID },
            url : 'ajaxHandlerEvents.php',
            success : function(res){
                //console.log(res);
                var result = JSON.parse(res);
                $(".comments-log").html('');
                $("#modalcomment").val('');
                var count = 0;
                var commentstext = "";
                if(result.length != 0){
                    for( count = 0; count < result.length; count++){
                        var comment_date = moment(new Date(result[count]['created_date'])).format('DD MMM YYYY');
                        commentstext += "<div class='commentlogss' id='"+result[count]['id']+"'><p>";
                        if(result[count]['repfname'] != null){
                            commentstext += "<strong>"+ result[count]['repfname'] + " " + result[count]['replname'] + " (" + comment_date + ") </strong>";
                        }else{
                            commentstext += "<strong>"+ result[count]['adminfname'] + " " + result[count]['adminlname'] + " (" + comment_date + ") </strong>";
                        }

                        var current_user = $("#current_user").val();
                        if(current_user == result[count]['user_id'])
                            commentstext += "<span style='float:right; margin-right:5px;'><a class='fas fa-pencil-alt edit' href='#'></a> <a href='#' class='fa fa-times del'></a></span></p>";
                        else
                            commentstext += "</p>";

                        commentstext += "<p class='comments'>"+result[count]['comments']+"</p></div>";
                    }

                    $(".comments-log").html(commentstext);
                }
            }
        });
    }

    $(document).delegate('.del','click',function(){
        var parent = $(this).parent().parent().parent();
        var id = parent.attr("id");
        $.ajax({
            url: 'ajaxHandlerEvents.php',
            type: 'POST',
            data: {action:"commentDelete", commentid:id},
            success: function(res){
                var result = JSON.parse(res);
                if(result == true){ 
                    parent.html("Deleted..");
                    $(parent).fadeOut(2000);
                }
            }
        })
    });
    $(document).delegate('.edit','click',function(){
        var parent = $(this).parent().parent().parent();
        var id = parent.attr("id");
        var text = parent.find('.comments').text();
        $("#modalcomment").val(text);
        $("#eventupdate").addClass('edited').attr("data-commentid",id);
    });

</script>
<aside id="action_palette" class="action_palette_width">		
    <div class="box full">
        <h4 class="box_top">Add Event</h4>
        <?php //if ($dataViewAccess) { ?>
            <div class="boxtent scroller ">
                <form id="filter_form" action="" method="post">	
                    <?php
                    $date_error = "";

                    if (isset($_POST['search'])) {
                        if (isset($error['from_date'])) {
                            $date_error = " error";
                        } elseif (strlen($_POST['from_date'])) {
                            $date_error = " valid";
                        }
                    }
                    ?>
                    <div class="f2<?php echo ((!isset($_POST['clear'])) && (isset($_POST['from_date'])) && (strlen($_POST['from_date']))) ? " show-label" : ""; ?><?php echo $date_error; ?>">
                        <label class="dynamic" for="event_date"><span>Event Date</span></label>

                        <div class="group">                       
                            <input readonly class="datepicker" type="text" id="eventstart" name="eventstart" value="<?php echo ((!isset($_POST['clear'])) && isset($_POST['from_date']) && strlen($_POST['from_date'])) ? $_POST['from_date'] : ""; ?>" placeholder="Event Date">

                            <p class="f_status">
                                <span class=""><strong></strong></span>
                            </p>
                        </div>
                    </div>
                    <div class="f2<?php echo ((!isset($_POST['clear'])) && (isset($_POST['salesrepopt'])) && (strlen($_POST['salesrepopt']))) ? " show-label valid" : ""; ?>">
                        <label class="dynamic" for="salesrepopt"><span>Genetic Consultant</span></label>

                        <div class="group">
                            <?php if ($role == 'Admin' || $role == 'Sales Manager') { ?>
                                <select id="salesrepopt" name="salesrepopt" class="<?php echo ((!isset($_POST['clear'])) && (isset($_POST['salesrepopt'])) && (strlen($_POST['salesrepopt']))) ? "" : "no-selection"; ?>">
                                    <option value="">Genetic Consultant</option>							
                                    <?php
                                    $salesreps = $db->query("SELECT * FROM tblsalesrep GROUP BY first_name  ORDER BY first_name, last_name");

                                    foreach ($salesreps as $salesrep) {
                                        if ($salesrep['first_name'] != "" || $salesrep['last_name'] != "") {
                                            ?>
                                            <option value="<?php echo $salesrep['Guid_salesrep']; ?>"<?php echo ((!isset($_POST['clear'])) && (isset($_POST['salesrepopt']) && ($_POST['salesrepopt'] == $salesrep['Guid_user'])) ? " selected" : ""); ?>><?php echo $salesrep['first_name'] . " " . $salesrep['last_name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <p class="f_status">
                                    <span class=""><strong></strong></span>
                                </p>

                            <?php } ?>
                            <?php if ($role == 'Sales Rep') { ?>
                                <span><?php
                                    echo $salesRepDetails['first_name'] . " " . $salesRepDetails['last_name'];
                                    ?>
                                </span>    
                            <?php } ?>
                            <input type="hidden" id="salerepid" value="<?php echo $salesRepDetails['Guid_salesrep']; ?>">

                        </div>
                    </div>
                    <div class="<?php echo ((!isset($_POST['clear'])) && (isset($_POST['eventtype'])) && (strlen($_POST['eventtype']))) ? "valid" : ""; ?>" style="margin-top: 15px;">
                        <div class="group">
                            <div class="eventtype">
                                <label style="padding-right: 11px;"><input type="radio" name="eventtype" value="1" checked>BRCA Day</label>
                                <label><input type="radio" name="eventtype" value="2">Health Care Fair</label>
                            </div><p class="f_status">
                                <span class=""><strong></strong></span>
                            </p>
                        </div>
                    </div>
                    <div class='f2 accounttype'>
                        <div class="group">
                            <select class="form-control" id="accountopt">
                                <option value="0">Account</option>
                                <?php
                                foreach ($accountdt as $acct) {
                                    ?>
                                    <option value='<?php echo $acct['Guid_account']; ?>'><?php echo $acct['account'] . ' - ' . ucwords(strtolower($acct['name'])); ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <p class="f_status">
                                <span class=""><strong></strong></span>
                            </p>    
                        </div>
                    </div>
                    <div class='f2 accounttype'> 
                        <div class="group">
                            <textarea class="form-control" rows="10" id="comment" placeholder="Comments" required="required"></textarea>
                        </div> 
                    </div> 
                    <div class="healthcare" style="display: none;">
                        <div class="f2">
                            <div class="group">
                                <!-- Full Name -->
                                <input type="text" class="form-control" id="full_name_id" name="full_name" placeholder="Full Name">
                                <p class="f_status">
                                    <span class=""><strong></strong></span>
                                </p>
                            </div> 
                        </div>
                        <div class="f2">
                            <div class="group">
                                <!-- Street 1 -->
                                <input type="text" class="form-control" id="street1_id" name="street1" placeholder="Street address, P.O. box, company name, c/o">
                                <p class="f_status">
                                    <span class=""><strong></strong></span>
                                </p>
                            </div>  
                        </div>
                        <div class="f2">    
                            <div class="group">    
                                <!-- Street 2 -->
                                <input type="text" class="form-control" id="street2_id" name="street2" placeholder="Apartment, suite, unit, building, floor, etc.">
                                <p class="f_status">
                                    <span class=""><strong></strong></span>
                                </p>
                            </div>
                        </div>
                        <div class="f2">    
                            <div class="group">
                                <!-- City-->
                                <input type="text" class="form-control" id="city_id" name="city" placeholder="City">
                                <p class="f_status">
                                    <span class=""><strong></strong></span>
                                </p>    
                            </div>
                        </div>
                        <div class="f2">    
                            <div class="group">
                                <!-- State Button -->
                                <select class="form-control" id="state_id" name="state">
                                    <option value="">State</option>
                                    <option value="AL">Alabama</option>
                                    <option value="AK">Alaska</option>
                                    <option value="AZ">Arizona</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="CA">California</option>
                                    <option value="CO">Colorado</option>
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="DC">District Of Columbia</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="HI">Hawaii</option>
                                    <option value="ID">Idaho</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IN">Indiana</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">Massachusetts</option>
                                    <option value="MI">Michigan</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">Mississippi</option>
                                    <option value="MO">Missouri</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NV">Nevada</option>
                                    <option value="NH">New Hampshire</option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="ND">North Dakota</option>
                                    <option value="OH">Ohio</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="OR">Oregon</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                    <option value="SD">South Dakota</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="TX">Texas</option>
                                    <option value="UT">Utah</option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WA">Washington</option>
                                    <option value="WV">West Virginia</option>
                                    <option value="WI">Wisconsin</option>
                                    <option value="WY">Wyoming</option>
                                </select>
                                <p class="f_status">
                                    <span class=""><strong></strong></span>
                                </p>
                            </div>
                        </div>
                        <div class="f2">    
                            <div class="group">
                                <!-- Zip Code-->
                                <input type="text" class="form-control" id="zip_id" name="zip" placeholder="zip code">
                                <p class="f_status">
                                    <span class=""><strong></strong></span>
                                </p>    
                            </div>  
                        </div>
                    </div>
                    
                    <input type="hidden" id="current_user" name="userid" value="<?php echo $userID; ?>">
                    <input type="hidden" id="date_updated" name="update_date" value="<?php echo date("Y-m-d H:i:s"); ?>">
                    <button type="submit" id="eventsave" class="button filter half" style="cursor: pointer;">Save</button> 
                    <button type="submit" name="clear" class="button cancel half" style="cursor: pointer;"><strong>Clear</strong></button>
                </form>
                <!--********************   SEARCH BY PALETTE END    ******************** -->

            </div>
        <?php //} ?>
    </div>    
</aside>
<?php
// Salesrep table
$clause = "  ORDER BY first_name, last_name";
$salesrep = $db->selectAll('tblsalesrep', $clause);
?>
<main class="wider-main">
    <?php if ($thisMessage != "") { ?>
        <section id="msg_display" class="show success">
            <h4><?php echo $thisMessage; ?></h4>
        </section>
    <?php } ?>    
    <div class="box full visible ">  
        <section id="palette_top">
            <h4>             
                <ol class="breadcrumb">
                    <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                    <li class="active">Event Schedule</li>  
                </ol>      
            </h4>
            <a href="<?php echo SITE_URL; ?>/dashboard.php?logout=1" name="log_out" class="button red back logout"></a>
            <a href="<?php echo SITE_URL; ?>/dashboard2.php" class="button homeIcon"></a>
            <a href="https://www.mdlab.com/questionnaire" target="_blank" class="button submit"><strong>View Questionnaire</strong></a>
        </section>
        <div class="scroller event-schedule">
            <div class="container">  
                <div class="filterby" style="padding: 0 0 10px 0;">
                    <div>                
                        <div>
                            <div>
                                <div class="row">
                                    <div id="stats-summary">

                                    </div>

                                    <div class="col-md-1"><label>Filter by:</label></div>
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <select class="form-control" id="salesrepfilter">
                                                <option value="0">Genetic Consultant</option>
                                                <?php
                                                foreach ($salesrep as $srole) {
                                                    if ($srole['first_name']) {
                                                        ?>
                                                        <option value='<?php echo $srole['Guid_salesrep']; ?>'><?php echo $srole['first_name'] . " " . $srole['last_name']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <select class="form-control" id="accountfilter">
                                                <option value="0">Account</option>
                                                <?php
                                                foreach ($accountdt as $acct) {
                                                    ?>
                                                    <option value='<?php echo $acct['Guid_account']; ?>'><?php echo $acct['account'] . ' - ' . ucwords(strtolower($acct['name'])); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" name="Detail" id="detail" class="info-button" style="float:left; margin-right: 20px;">Detail</button>
                                        <button type="button" name="Summary" id="summary" class="info-button" style="background: #90bcf7;">Summary</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="calendar"></div>

            </div>
            <!-- The Modal -->
            <div id="myModal" class="schedulemodal">

                <!-- Modal content -->
                <div class="schedulemodal-content">
                    <span class="close">&times;</span>
                    <form id='updateEvent'>
                        <input type="hidden" name="modalid" id="modalid" value="">
                        <input type="hidden" name="modalhealthcareid" id="modalhealthcareid" value="">
                        <div class="panel-primary">
                            <div class="panel-body">
                                <div class="row">
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <div class='input-group modaldate' id='datetimepicker2'>
                                                <input type='text' id="modaleventstart" class="form-control" placeholder="Event Date" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php //if ($role == 'Admin' || $role == 'Sales Manager') { ?>
                                        <div class='col-md-2'>
                                            <div class="form-group">
                                                <select class="form-control" id="modalsalesrepopt">
                                                    <option value="0">Genetic Consultant</option>
                                                    <?php
                                                    foreach ($salesrep as $srole) {
                                                        if ($srole['first_name']) {
                                                            ?>
                                                            <option value='<?php echo $srole['Guid_salesrep']; ?>'><?php echo $srole['first_name'] . " " . $srole['last_name']; ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php // } ?>
                                    <?php if ($role == 'Sales Rep') { ?>
                                        <!--<div class='col-md-2'>
                                            <div class="form-group">
                                                <span><?php
                                                    echo $salesRepDetails['first_name'] . " " . $salesRepDetails['last_name'];
                                                    ?>
                                                </span>    
                                            </div>
                                        </div>-->
                                    <?php } ?>
                                    <input type="hidden" id="modalsalerepid" value="<?php echo $salesRepDetails['Guid_salesrep']; ?>">
                                    <div class='col-md-2'>
                                        <div class="form-group">
                                            <div class="modaleventtype">
                                                <label><input type="radio"  id="brcaradio" name="modaleventtype" value="1" checked>BRCA Day</label>
                                            </div>
                                            <div class="modaleventtype">
                                                <label><input type="radio"  id="healthradio" name="modaleventtype" value="2">Health Care Fair</label>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class='col-md-5 modalaccounttype'>
                                        <div class="form-group">
                                            <select class="form-control" id="modalaccountopt">
                                                <option value="0">Account</option>
                                                <?php
                                                foreach ($accountdt as $acct) {
                                                    ?>
                                                    <option value='<?php echo $acct['Guid_account']; ?>'><?php echo $acct['account'] . ' - ' . ucwords(strtolower($acct['name'])); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div></div>
                                <div class="row">
                                    <div class='col-md-7 col-sm-6 modalaccounttype'> 
                                        <div class="form-group">
                                            <label for="modalcomment">Add Comments: </label>
                                            <textarea class="form-control" name="modalcomment" rows="10" id="modalcomment" placeholder="Comments" required></textarea>
                                        </div> 
                                    </div>  
                                    <div class="col-md-5 col-sm-6">
                                        <label for="modalcomment">Comments History: </label>
                                        <div class="comments-log">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row modalhealthcare" style="display: none;">
                                    <div class='col-md-4'>
                                        <div class="form-group"> <!-- Full Name -->
                                            <input type="text" class="form-control" id="modalfull_name_id" name="modalfull_name" placeholder="Full Name">
                                        </div>	
                                    </div>    
                                    <div class='col-md-4'>
                                        <div class="form-group"> <!-- Street 1 -->
                                            <input type="text" class="form-control" id="modalstreet1_id" name="modalstreet1" placeholder="Street address, P.O. box, company name, c/o">
                                        </div>					
                                    </div>    
                                    <div class='col-md-4'>    
                                        <div class="form-group"> <!-- Street 2 -->
                                            <input type="text" class="form-control" id="modalstreet2_id" name="modalstreet2" placeholder="Apartment, suite, unit, building, floor, etc.">
                                        </div>	
                                    </div>    
                                    <div class='col-md-4'>
                                        <div class="form-group"> <!-- City-->
                                            <input type="text" class="form-control" id="modalcity_id" name="modalcity" placeholder="City">
                                        </div>									
                                    </div>    
                                    <div class='col-md-4'>
                                        <div class="form-group"> <!-- State Button -->
                                            <select class="form-control" id="modalstate_id" name="modalstate">
                                                <option value="">State</option>
                                                <option value="AL">Alabama</option>
                                                <option value="AK">Alaska</option>
                                                <option value="AZ">Arizona</option>
                                                <option value="AR">Arkansas</option>
                                                <option value="CA">California</option>
                                                <option value="CO">Colorado</option>
                                                <option value="CT">Connecticut</option>
                                                <option value="DE">Delaware</option>
                                                <option value="DC">District Of Columbia</option>
                                                <option value="FL">Florida</option>
                                                <option value="GA">Georgia</option>
                                                <option value="HI">Hawaii</option>
                                                <option value="ID">Idaho</option>
                                                <option value="IL">Illinois</option>
                                                <option value="IN">Indiana</option>
                                                <option value="IA">Iowa</option>
                                                <option value="KS">Kansas</option>
                                                <option value="KY">Kentucky</option>
                                                <option value="LA">Louisiana</option>
                                                <option value="ME">Maine</option>
                                                <option value="MD">Maryland</option>
                                                <option value="MA">Massachusetts</option>
                                                <option value="MI">Michigan</option>
                                                <option value="MN">Minnesota</option>
                                                <option value="MS">Mississippi</option>
                                                <option value="MO">Missouri</option>
                                                <option value="MT">Montana</option>
                                                <option value="NE">Nebraska</option>
                                                <option value="NV">Nevada</option>
                                                <option value="NH">New Hampshire</option>
                                                <option value="NJ">New Jersey</option>
                                                <option value="NM">New Mexico</option>
                                                <option value="NY">New York</option>
                                                <option value="NC">North Carolina</option>
                                                <option value="ND">North Dakota</option>
                                                <option value="OH">Ohio</option>
                                                <option value="OK">Oklahoma</option>
                                                <option value="OR">Oregon</option>
                                                <option value="PA">Pennsylvania</option>
                                                <option value="RI">Rhode Island</option>
                                                <option value="SC">South Carolina</option>
                                                <option value="SD">South Dakota</option>
                                                <option value="TN">Tennessee</option>
                                                <option value="TX">Texas</option>
                                                <option value="UT">Utah</option>
                                                <option value="VT">Vermont</option>
                                                <option value="VA">Virginia</option>
                                                <option value="WA">Washington</option>
                                                <option value="WV">West Virginia</option>
                                                <option value="WI">Wisconsin</option>
                                                <option value="WY">Wyoming</option>
                                            </select>					
                                        </div>
                                    </div>    
                                    <div class='col-md-4'>
                                        <div class="form-group"> <!-- Zip Code-->
                                            <input type="text" class="form-control" id="modalzip_id" name="modalzip" placeholder="zip code">
                                        </div>	
                                    </div>    
                                    <input type="hidden" id="update_commenterid" name="userid" value="<?php echo $userID; ?>">
                                    <input type="hidden" id="update_date_updated" name="update_date" value="<?php echo date("Y-m-d H:i:s"); ?>">  
                                </div>  
                                <div class="row">
                                    <div class='col-md-10'>
                                        <button type="button" id="eventupdate" class="btn btn-primary" style="cursor: pointer;" disabled="disabled">Update</button>
                                        <button type="button" id="eventcancel" class="btn btn-danger" style="cursor: pointer;">Cancel</button>
                                        <button type="button" class="btn btn-danger" id="eventdelete" style="border-radius: 2em !important; margin: 7px 0;" style="cursor: pointer;">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form> 
                </div>

            </div>
        </div>
    </div>
</main>
<button id="action_palette_toggle" class="toggle_move"><i class="fa fa-2x fa-angle-right"></i></button>
<script>
    $(function () {
        $('#modaleventstart').datepicker({
            dateFormat: "mm/dd/yy",
            showOn: 'both',
            buttonImageOnly: true,
            buttonImage: 'assets/eventschedule/images/calendar.gif'
        });
    });
</script>
<?php require_once 'scripts.php'; ?>
<?php require_once 'footer.php'; ?>