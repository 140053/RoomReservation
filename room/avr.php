<?php
session_start();
include "../api/_room.php";
$path = pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME); 
$home = 'Library';

switch ($path) {
  case 'home':
      $home = 'Library';
    break;
  case 'avr':
      $home = 'Audio Visual';
    break;
  case 'lecture':
      $home = 'Lecture';
    break;
  case 'discussion':
      $home = 'Discussion';
    break;
  case 'gs':
      $home = 'GS Students';
    break;       
  default:
      $home = 'Library';
    break;
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?php echo $home ?> Room Reservation</title>

  <style type="text/css">
      p, body, td, input, select { font-family: -apple-system,system-ui,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; font-size: 14px; }
      body { padding: 0px; margin: 0px; background-color: #ffffff; }
      a { color: #1155a3; }
      .space { margin: 10px 0px 10px 0px; }
      .header { background: #003267; background: linear-gradient(to right, #011329 0%, #00639e 44%, #011329 100%); padding: 20px 10px; color: white; box-shadow: 0px 0px 10px 5px rgba(0, 0, 0, 0.75); }
      .header a { color: white; }
      .header h1 a { text-decoration: none; }
      .header h1 { padding: 0px; margin: 0px; }
      .main { padding: 10px; margin-top: 10px; }
  </style>

  <style>

  </style>

  <!-- DayPilot library -->
  <script src="../js/daypilot/daypilot-all.min.js"></script>

  <!-- additional themes -->
  <link type="text/css" rel="stylesheet" href="../themes/calendar_green.css"/>
  <link type="text/css" rel="stylesheet" href="../themes/calendar_traditional.css"/>
  <link type="text/css" rel="stylesheet" href="../themes/calendar_transparent.css"/>
  <link type="text/css" rel="stylesheet" href="../themes/calendar_white.css"/>

</head>
<body>

<div class="header" style="background: linear-gradient(to right, #23301b 0%, #009e22 44%, #011329 100%);">
<h1 onclick="toRoom('home')"><?php echo $home ?> Room Reservation</h1>
  <div></div>
</div>

<div class="main">
  <div style="display: flex;">

    <div style="margin-right: 10px;">
      <div id="nav"></div>
    </div>

    <div style="flex-grow: 1;">
    <style>
      .btn-group button {
        background-color: #04AA6D; /* Green background */
        border: 1px solid green; /* Green border */
        color: white; /* White text */
        padding: 10px 24px; /* Some padding */
        cursor: pointer; /* Pointer/hand icon */
        float: left; /* Float the buttons side by side */
        font-size: larger;
      }
      .btn-groups button {
        background-color: #04AA6D; /* Green background */
        border: 1px solid green; /* Green border */
        color: white; /* White text */
        padding: 10px 24px; /* Some padding */
        cursor: pointer; /* Pointer/hand icon */
        float: right; /* Float the buttons side by side */
        font-size: larger;
      }

      #cbtn{
        background-color: #cfb816; 
        border: 1px solid green; /* Green border */
        color: white; /* White text */
        padding: 10px 24px; /* Some padding */
        cursor: pointer; /* Pointer/hand icon */
        float: left; /* Float the buttons side by side */
      }
      #cbtn2{
        background-color: #eb0534; /* Green background */
        border: 1px solid green; /* Green border */
        color: white; /* White text */
        padding: 10px 24px; /* Some padding */
        cursor: pointer; /* Pointer/hand icon */
        float: right; /* Float the buttons side by side */
      }

      /* Clear floats (clearfix hack) */
      .btn-group:after{
        content: "";
        clear: both;
        display: table;
      }
      .btn-groups:after{
        content: "";
        clear: both;
        display: table;
      }

      .btn-group button:not(:last-child) {
        border-right: none; /* Prevent double borders */
      }
      .btn-groups button:not(:last-child) {
        border-right: none; /* Prevent double borders */
      }

      /* Add a background color on hover */
      .btn-group button:hover {
        background-color: #3e8e41;
      }
      .btn-groups button:hover {
        background-color: #3e8e41;
      }
      <?php if(isset($_SESSION['user']) and $_SESSION['auth'] == 0){  ?>
      .toroom{
        width:25%
      }
      <?php }else{   ?>
        .toroom{
          width:33.3%
        }
      <?php }   ?>
    </style>
    <div class="btn-group">
      <button>Date Today:</button>
      <button id="start"></button>    
      <!--a id="cbtn" href="#" onclick="picker.show(); return false;">Change</a-->
      <div class="btn-groups">
        <?php if(isset($_SESSION['user'])){  ?>
          <a href="/logout.php" id="cbtn2">Logout</a>
          <button><?php echo $_SESSION['user']['firstname'] ?></button>
        <?php }else{ ?>
          <a href="/login.php" id="cbtn2">Login</a>
        <?php } ?>
    </div>

   
      <div class="space">
        Theme: <select id="theme">
        <option value="calendar_default">Default</option>
        <option value="calendar_white">White</option>
        <option value="calendar_green" selected>Green</option>
        <option value="calendar_traditional">Traditional</option>
        <option value="calendar_transparent">Transparent</option>
      </select>
      </div>
     
      <div id="dp"></div>
      <br>
      <hr>
      <p>Room Schedule</p>
      <div class="btn-group" style="width:100%">
        <button  class="toroom" onclick="toRoom('avr')" >Audio Visual Room</button>
        <button  class="toroom" onclick="toRoom('lecture')" >Lecture Room</button>
        <button  class="toroom" onclick="toRoom('discussion')" >Discussion Room </button>
        <?php if(isset($_SESSION['user']) and $_SESSION['auth'] == 0 ){  ?>
        <button  class="toroom" onclick="toRoom('gs')" >GS Student Room </button>
        <?php } ?>
      </div>
    </div>

  </div>
</div>

<script>
    function toRoom(room){
      switch (room) {
        case 'avr':
          window.location.href = "/room/avr.php";
          break;
        case 'lecture':
          window.location.href = "/room/lecture.php";
          break;      
        case 'discussion':
          window.location.href = "/room/discussion.php";
          break; 
        case 'gs':
          window.location.href = "/room/gs.php";
          break;                
        default:
          window.location.href = "/";
          break;
      }
    }

    function getTimeFromDate(dateString) {
      const date = new Date(dateString);
      const hour = date.getHours();
      const minute = String(date.getMinutes()).padStart(2, "0");
      const period = hour < 12 ? "AM" : "PM";
      const hour12 = hour % 12 || 12;
      const hourStr = String(hour12).padStart(2, "0");
      return `${hourStr}:${minute} ${period}`;
    }
   var picker = new DayPilot.DatePicker({
        target: 'start', 
        pattern: 'MMMM dd yyyy hh:mm tt', 
        onTimeRangeSelected: function(args) { 
            dp.startDate = args.date;
            dp.update();
        }
    });

  const nav = new DayPilot.Navigator("nav", {
    showMonths: 3,
    skipMonths: 3,
    selectMode: "Week",
    onTimeRangeSelected: args => {
      dp.update({
        startDate: args.day
      });
      app.loadEvents();
    }
  });
  //nav.init();

  const dp = new DayPilot.Calendar("dp", {
    viewType: "Week",
    headerDateFormat: "dddd (MMM d)",
    cellHeight: 25,
    crosshairType: "Disabled",
    businessBeginsHour: 7,
    dayBeginsHour: 7,
    dayEndsHour: 18,
    <?php if(isset($_SESSION['user'])){ ?>
    timeRangeSelectedHandling: "Enabled",
    <?php }else{ ?>
    timeRangeSelectedHandling: "Disabled",
    eventDeleteHandling: "Disabled",
    eventMoveHandling: "Disabled",
    eventResizeHandling: "Disabled",
    eventClickHandling: "Disabled",
    eventHoverHandling: "Disabled",
    <?php } ?>
    //theme: "calendar_green",
    /*
    eventDeleteHandling: "Update",
    onEventDeleted: async (args) => {
        const id = args.e.id();
        await DayPilot.Http.delete(`/api/CalendarEvents/${id}`);
        console.log("Deleted.");
    },
    */
   //++++++++++++++++++++++++++++++++++++++++++++++______________MOVE_________________++++++++++++++++++++++++++++ 
    onEventMoved: async (args) => {
      const id = args.e.id();
      const data = {
        id: args.e.id(),
        start: args.newStart,
        end: args.newEnd,
        text: args.e.text(),
        color: args.e.data.barColor,
        status: args.e.data.status,
        rtype: args.e.data.rtype,
        text1: args.e.data.text1,
        room: args.e.data.room     
      };
      //console.log(args.e.data.barColor)
      await DayPilot.Http.post(`../api/event_update.php`, data);
      console.log("Moved.");
    },
    //++++++++++++++++++++++++++++++++++++++++++++++______________RESIZED_________________++++++++++++++++++++++++++++
    onEventResized: async (args) => {
      const id = args.e.id();
      const data = {
        id: args.e.id(),
        start: args.newStart,
        end: args.newEnd,
        text: args.e.text(),
        color: args.e.data.barColor,
        status: args.e.data.status,
        rtype: args.e.data.rtype,
        text1: args.e.data.text1,
        room: args.e.data.room    
      };
      await DayPilot.Http.post(`../api/event_update.php`, data);
      console.log("Resized.");
    },
    //++++++++++++++++++++++++++++++++++++++++++++++______________CREATE_________________++++++++++++++++++++++++++++
    onTimeRangeSelected: async (args) => {
      const colors = [
                    {name: "Blue", id: "#3c78d8"},
                    {name: "Green", id: "#6aa84f"},
                    {name: "Yellow", id: "#f1c232"},
                    {name: "Red", id: "#cc0000"},
                ];

      const statuses = [
          {name: "Approved", id: 'approved'},
          {name: "Pending", id: 'pending'},
          {name: "Denied", id: 'denied'},

      ];
      const restypes = [
          {name: "CLASS", id: 'class'},
          {name: "Meeting/Seminar", id: 'mesem'},
          {name: "Others", id: 'others'},

      ];
      const rooms = [
        {name: "AVR ROOM", id: 'AVR'},
        {name: "Lecture ROOOM", id: 'LECTURE'},
        {name: "Discussion Room", id: 'DISCUSION'},
        {name: "Proposal/Final Defense Romm", id: "GS STUDENT"}
      ]
      const form = [
        {name: "Type of Reservation", id: "rtype", type: "select", options: restypes},
        {name: "Subject/Name", id: "text"},
        {name: "Reserved by:", id: "text1"},
        {name: "Status", id: "status", type: " select", options: statuses},
        {name: "Color", id: "barColor", type: "select", options: colors},
        {name: "Room", id: "room", type:"select", options: rooms}
      ];

      const modal = await DayPilot.Modal.form(form, {});
      dp.clearSelection();

      if (modal.canceled) {
        return;
      }

      const event = {
        start: args.start,
        end: args.end,
        text: modal.result.text,
        text1: modal.result.text1,
        barColor: modal.result.barColor,
        status: modal.result.status,
        rtype: modal.result.rtype,
        room: modal.result.room

      };
    
      const {data} = await DayPilot.Http.post(`../api/event_create.php`, event);

      var res = dp.events.add({
        start: args.start,
        end: args.end,
        id: data.id,
        text: modal.result.text,
        text1: modal.result.text1,
        barColor: modal.result.barColor,
        status: modal.result.status,
        rtype: modal.result.rtype,
        room: modal.result.room
      });

      console.log(event)
    },
    onEventClick: async (args) => {
      //app.editEvent(args.e);
      app.viewRes(args.e);
    },
    onBeforeEventRender: args => {
      //console.log(args.data)     
     
    <?php if(isset($_SESSION['user']) and $_SESSION['auth'] == 0){ ?>
        if (args.data.status == 'approved'){
          var part0 = "<b>"+ args.data.room + " ROOM </b><hr>";
          var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
          var part2 =  "<hr>By:" + args.data.text1 +"<br>FROM:"+ getTimeFromDate(args.data.start) + "<br>To:" + getTimeFromDate(args.data.end);
          var part3 ="<hr>" + args.data.status.toUpperCase()     
          args.data.html = part0+ part1 + part2 + part3

          args.data.backColor = "green";
          args.data.fontColor ="white"        
        }
        if (args.data.status == 'pending'){
          var part0 = "<b>"+ args.data.room + " ROOM </b><hr>";     
          var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
          var part2 =  "<hr>By:" + args.data.text1 +"<br>FROM:"+ getTimeFromDate(args.data.start) + "<br>To:" + getTimeFromDate(args.data.end);
          var part3 ="<hr>" + args.data.status.toUpperCase()     
          args.data.html = part0+ part1 + part2 + part3

          args.data.backColor = "Yellow";
          args.data.fontColor ="black"        
        }
        if (args.data.status == 'denied'){  
          var part0 = "<b>"+ args.data.room + " ROOM </b><hr>";
          var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
          var part2 =  "<hr>By:" + args.data.text1 +"<br>FROM:"+ getTimeFromDate(args.data.start) + "<br>To:" + getTimeFromDate(args.data.end);
          var part3 ="<hr>" + args.data.status.toUpperCase()     
          args.data.html = part0+ part1 + part2 + part3
          
          args.data.backColor = "red";
          args.data.fontColor ="white"        
        }
        args.data.areas = [
          {
            top: 5,
            right: 5,
            width: 16,
            height: 16,
            symbol: "../icons/daypilot.svg#minichevron-down-4",
            fontColor: "#666",
            visibility: "Hover",
            action: "ContextMenu",
            style: "background-color: #f9f9f9; border: 1px solid #666; cursor:pointer; border-radius: 15px;"
          }
        ];
      
    <?php }else{ ?>
        if (args.data.status == 'approved'){
          var part0 = "<b>"+ args.data.room + " ROOM </b><hr>";
          var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
          var part2 =  "<hr>By: NULL" + "<br>FROM:"+ getTimeFromDate(args.data.start) + "<br>To:" + getTimeFromDate(args.data.end);
          var part3 ="<hr>" + args.data.status.toUpperCase()     
          args.data.html = part0+ part1 + part2 + part3

          //args.data.backColor = "green";
          //args.data.fontColor ="white"        
        }
        if (args.data.status == 'pending'){
          var part0 = "<b>"+ args.data.room + " ROOM </b><hr>";     
          var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
          var part2 =  "<hr>By: NULL" +"<br>FROM:"+ getTimeFromDate(args.data.start) + "<br>To:" + getTimeFromDate(args.data.end);
          var part3 ="<hr>" + args.data.status.toUpperCase()     
          args.data.html = part0+ part1 + part2 + part3

          //args.data.backColor = "Yellow";
          //args.data.fontColor ="black"        
        }
        if (args.data.status == 'denied'){  
          var part0 = "<b>"+ args.data.room + " ROOM </b><hr>";
          var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
          var part2 =  "<hr>By: NULL"  +"<br>FROM:"+ getTimeFromDate(args.data.start) + "<br>To:" + getTimeFromDate(args.data.end);
          var part3 ="<hr>" + args.data.status.toUpperCase()     
          args.data.html = part0+ part1 + part2 + part3
          
          //args.data.backColor = "red";
          //args.data.fontColor ="white"        
        }
        if (args.data.status == 'cancelled'){  
          var part0 = "<b>"+ args.data.room + " ROOM </b><hr>";
          var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
          var part2 =  "<hr>By:NULL"  +"<br>FROM:"+ getTimeFromDate(args.data.start) + "<br>To:" + getTimeFromDate(args.data.end);
          var part3 ="<hr>" + args.data.status.toUpperCase()     
          args.data.html = part0+ part1 + part2 + part3
          
          args.data.backColor = "black";
          args.data.fontColor ="white"        
        }

    <?php } ?>
     
    },
    contextMenu: new DayPilot.Menu({
      items: [
        {
          text: "Edit...",
          onClick: args => {
            app.editEvent(args.source);
          }
        },
        {
          text: "Delete",
          onClick: args => {
            app.deleteEvent(args.source);
          }
        },
        {
          text: "-"
        },
        {
          text: "Duplicate",
          onClick: args => {
            app.duplicateEvent(args.source);
          }
        },
      ]
    })
  });
  dp.init();


  const app = {
    elements: {
      theme: document.querySelector("#theme")
    },
    //++++++++++++++++++++++++++++++++++++++++++++++______________ONLOAD_________________++++++++++++++++++++++++++++
    loadEvents() {
      dp.events.load("../api/avr_event_list.php");
    },
    async viewRes(e){
      //console.log(e)
      <?php if(isset($_SESSION['user']) and $_SESSION['auth'] == 0){ ?>
      var part0 = "Room: <b>"+ e.data.room + " ROOM </b><hr>";
      var part1 = "Type: "+e.data.rtype.toUpperCase() + " : " + "[" + e.data.text.toUpperCase() + "] <br> ";
      var part2 =  "<hr>By: " + e.data.text1 +"<br>FROM: "+ getTimeFromDate(e.data.start) + "<br>To: " + getTimeFromDate(e.data.end);
      var part3a ="<hr>STATUS [" + e.data.status.toUpperCase() + "]"
      var part3 = "<div class='btn-groups'><button>" + e.data.status.toUpperCase() + "</button></div>"    
     <?php }else{ ?>
      var part0 = "Room: <b>"+ e.data.room + " ROOM </b><hr>";
      var part1 = "Type: "+e.data.rtype.toUpperCase() + " : " + "[" + e.data.text.toUpperCase() + "] <br> ";
      var part2 =  "<hr>By: NULL " +"<br>FROM: "+ getTimeFromDate(e.data.start) + "<br>To: " + getTimeFromDate(e.data.end);
      var part3a ="<hr>STATUS [" + e.data.status.toUpperCase() + "]"
      var part3 = "<div class='btn-groups'><button>" + e.data.status.toUpperCase() + "</button></div>"    
      <?php } ?>
      const form = [
        {
          type: 'title',
          name: 'Information',
        },
        {
          type: 'html',
          html: part0+ part1 + part2 + part3,
        },
      ];
      const data = {};

      const modal = await DayPilot.Modal.form(form, data);
      if (modal.canceled) {
        return;
      }
      if (modal.canceled == false){
        return;
      }
     

    },
    //++++++++++++++++++++++++++++++++++++++++++++++______________EDIT_________________++++++++++++++++++++++++++++
    async editEvent(e) {
      const colors = [
                    {name: "Blue", id: "#3c78d8"},
                    {name: "Green", id: "#6aa84f"},
                    {name: "Yellow", id: "#f1c232"},
                    {name: "Red", id: "#cc0000"},
                ];
      const statuses = [
          {name: "Approved", id: 'approved'},
          {name: "Pending", id: 'pending'},
          {name: "Denied", id: 'denied'},

      ];
      const restypes = [
          {name: "CLASS", id: 'class'},
          {name: "Meeting/Seminar", id: 'mesem'},
          {name: "Others", id: 'others'},

      ];
      const rooms = [
        {name: "AVR ROOM", id: 'AVR'},
        {name: "Lecture ROOOM", id: 'LECTURE'},
        {name: "Discussion Room", id: 'DISCUSION'},
        {name: "Proposal/Final Defense Romm", id: "GS STUDENT"}
      ]

      const form = [
        //{ name: "Name", id: "text" }
        {name: "Type of Reservation", id: "rtype", type: "select", options: restypes},
        {name: "Text", id: "text"},
        //{name: "Start", id: "start", type: "datetime"},
        //{name: "End", id: "end", type: "datetime"},
        {name: "Status", id: "status", type: " select", options: statuses},
        {name: "Color", id: "barColor", type: "select", options: colors},
        {name: "Room", id: "room", type:"select", options: rooms}
      ];

      const modal = await DayPilot.Modal.form(form, e.data);
      if (modal.canceled) {
        return;
      }

      const id = e.id();
      const data = {
        id: e.id(),
        start: e.start(),
        end: e.end(),
        text: modal.result.text,
        color: modal.result.barColor,
        status: modal.result.status,
        rtype: modal.result.rtype,
        room: modal.result.room
        
      };
      
      await DayPilot.Http.post(`../api/event_update.php`, data);

      dp.events.update({
        ...e.data,
        text: modal.result.text,
        start: modal.result.start,
        end: modal.result.end,
        barColor: modal.result.barColor,
        status: modal.result.status,
        rtype: modal.result.rtype,
        room: modal.result.room
        
      });
      console.log("Updated.");
    },
    //++++++++++++++++++++++++++++++++++++++++++++++______________DELETE_________________++++++++++++++++++++++++++++
    async deleteEvent(e) {
      const modal = await DayPilot.Modal.confirm("Do you really want to delete this event?");
      if (modal.canceled) {
        return;
      }
      const id = e.id();
      const params = {
        id
      };
      await DayPilot.Http.post(`../api/event_delete.php`, params);

      dp.events.remove(id);

      console.log("Deleted.");
    },
    //++++++++++++++++++++++++++++++++++++++++++++++______________DUPLICATE_________________++++++++++++++++++++++++++++
    async duplicateEvent(e) {
      
      const event = {
        start: e.start(),
        end: e.end(),
        text: e.text() + " (copy)",
        barColor: e.data.barColor,
        status: e.data.status,
        rtype: e.data.rtype,
        text1: e.data.text1,
        room: e.data.room

      };
     
      const { data } = await DayPilot.Http.post(`../api/event_create.php`, event);

      dp.events.add({
        ...event,
        id: data.id,
      });
     
      console.log("Duplicated.");
    },
    init() {
      app.elements.theme.addEventListener("change", () => {
        dp.update({
          theme: app.elements.theme.value
        });
      });

      app.loadEvents();
    }
  };
  app.init();


</script>

</body>
</html>

