<?php
session_start();
include "api/ses.php";

if(isset($_SESSION['user'])){
  $name = $_SESSION['user']['lastname'] . ',' . $_SESSION['user']['firstname'];  
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Room Reservation</title>

  <style type="text/css">
      p, body, td, input, select { font-family: -apple-system,system-ui,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; font-size: 12px; }
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
  <script src="js/daypilot/daypilot-all.min.js"></script>

  <!-- additional themes -->
  <link type="text/css" rel="stylesheet" href="themes/calendar_green.css"/>
  <link type="text/css" rel="stylesheet" href="themes/calendar_traditional.css"/>
  <link type="text/css" rel="stylesheet" href="themes/calendar_transparent.css"/>
  <link type="text/css" rel="stylesheet" href="themes/calendar_white.css"/>

</head>
<body>

<div class="header" style="background: linear-gradient(to right, #23301b 0%, #009e22 44%, #011329 100%);">
  <h1>Library Room Reservation</h1>
  <div></div>
</div>

<div class="main">
  <div style="display: flex;">

    <div style="margin-right: 10px;">
      <div id="nav"></div>
    </div>

    <div style="flex-grow: 1;">
      <style>
        /* The Modal (background) */
        .modal {
          display: none; /* Hidden by default */
          position: fixed; /* Stay in place */
          z-index: 1; /* Sit on top */
          left: 0;
          top: 0;
          width: 100%; /* Full width */
          height: 100%; /* Full height */
          overflow: auto; /* Enable scroll if needed */
          background-color: rgb(0,0,0); /* Fallback color */
          background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content/Box */
        .modal-content {
          background-color: #fefefe;
          margin: 15% auto; /* 15% from the top and centered */
          padding: 20px;
          border: 1px solid #888;
          width: 80%; /* Could be more or less, depending on screen size */
        }

        /* The Close Button */
        .close {
          color: #aaa;
          float: right;
          font-size: 28px;
          font-weight: bold;
        }

        .close:hover,
        .close:focus {
          color: black;
          text-decoration: none;
          cursor: pointer;
        }
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
      </style>
      <div class="btn-group">
        <button>Date Today:</button>
        <button id="start">Apple</button>    
        <!--a id="cbtn" href="#" onclick="picker.show(); return false;">Change</a-->

        <div class="btn-groups">
          <a href="logout.php" id="cbtn2">Logout</a>
          <button><?php echo $_SESSION['user']['firstname'] ?></button>
          <button  id="myBtn">Submit Reservation</button>          
        </div>
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
      <style>
        #fname, #country, #resby, #rtype, #starts, #end {
          width: 100%;
          padding: 12px 20px;
          margin: 8px 0;
          display: block;
          border: 1px solid #ccc;
          border-radius: 4px;
          box-sizing: border-box;
        }
         #modalsub {
          width: 100%;
          background-color: #04AA6D;
          color: white;
          padding: 14px 20px;
          margin: 8px 0;
          border: none;
          border-radius: 4px;
          cursor: pointer;
         }
         #modalsub :hover {
          background-color: #45a049;
        }

        .modal-content{
          width: 25%;
                  
          font-family: Arial;
        }
                
      </style>



      <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
          <span class="close">&times;</span>
          <div class="content">
            <!-- Modal content -->
            <form action="/">
              <label for="rtype">Country</label>
              <select id="rtype" name="rtype">
                <option value="class">CLASS</option>
                <option value="mesem">MEETING/SEMINARS</option>
                <option value="others">Others</option>
              </select>

              <label for="fname">Subject/Name:</label>
              <input type="text" id="fname" name="name" >

              <label for="resby">Reserved By:</label>
              <input type="text" id="resby" name="resby" value="<?php echo $name; ?>">

              <label for="starts">Start (Time):</label>
              <input type="time" id="starts" name="start">

              <label for="end">End (Time):</label>
              <input type="time" id="end" name="end">

              <input id="modalsub" type="submit" value="Submit">
            </form>




            <!-- Modal content -->
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

<script>
    function getTimeFromDate(dateString) {
      const date = new Date(dateString);
      const hour = String(date.getHours()).padStart(2, "0");
      const minute = String(date.getMinutes()).padStart(2, "0");
      const second = String(date.getSeconds()).padStart(2, "0");
      return `${hour}:${minute}:${second}`;
    }
   var picker = new DayPilot.DatePicker({
        target: 'start', 
        pattern: 'yyyy-MM-dd', 
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
    viewType: "Day",
    headerDateFormat: "dddd",
    cellHeight: 25,
    crosshairType: "Disabled",
    businessBeginsHour: 7,
    dayBeginsHour: 7,
    dayEndsHour: 18,
    timeRangeSelectedHandling: "Disabled",
    eventDeleteHandling: "Disabled",
    eventMoveHandling: "Disabled",
    eventResizeHandling: "Disabled",
    eventClickHandling: "Disabled",
    eventHoverHandling: "Disabled",
    theme: "calendar_green",
    /*
    eventDeleteHandling: "Update",
    onEventDeleted: async (args) => {
        const id = args.e.id();
        await DayPilot.Http.delete(`/api/CalendarEvents/${id}`);
        console.log("Deleted.");
    },
    */
   //++++++++++++++++++++++++++++++++++++++++++++++______________MOVE_________________++++++++++++++++++++++++++++
   /*
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
        text1: args.e.data.text1  
      };
      //console.log(args.e.data.barColor)
      await DayPilot.Http.post(`/api/event_update.php`, data);
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
        text1: args.e.data.text1    
      };
      await DayPilot.Http.post(`/api/event_update.php`, data);
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
      const form = [
        {name: "Type of Reservation", id: "rtype", type: "select", options: restypes},
        {name: "Subject/Name", id: "text"},
        {name: "Reserved by:", id: "text1"},
        {name: "Status", id: "status", type: " select", options: statuses},
        {name: "Color", id: "barColor", type: "select", options: colors},
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
        rtype: modal.result.rtype

      };
    
      const {data} = await DayPilot.Http.post(`/api/event_create.php`, event);

      var res = dp.events.add({
        start: args.start,
        end: args.end,
        id: data.id,
        text: modal.result.text,
        text1: modal.result.text1,
        barColor: modal.result.barColor,
        status: modal.result.status,
        rtype: modal.result.rtype
      });

      console.log(event)
    },
    onEventClick: async (args) => {
      app.editEvent(args.e);
    },
    */
    onBeforeEventRender: args => {
      console.log(args.data)     
      if (args.data.status == 'approved'){
        var part0 = "<b>"+ args.data.room + " ROOM </b><hr>";
        var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
        var part2 =  "<hr>By:" + args.data.text1 +"<br>FROM:"+ getTimeFromDate(args.data.start) + "<br>To:" + getTimeFromDate(args.data.end);
        var part3 ="<hr>" + args.data.status.toUpperCase()     
        args.data.html = part0+ part1 + part2 + part3

        //args.data.backColor = "green";
        //args.data.fontColor ="white"        
      }
      if (args.data.status == 'pending'){      
        var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
        var part2 =  "<hr>By:" + args.data.text1 +"<br>FROM: "+ getTimeFromDate(args.data.start) + "<br>To: " + getTimeFromDate(args.data.end);
        var part3 ="<hr>" + args.data.status.toUpperCase()     
        args.data.html = part1 + part2 + part3

        //args.data.backColor = "Yellow";
        //args.data.fontColor ="black"        
      }
      if (args.data.status == 'denied'){      
        var part1 = args.data.rtype.toUpperCase() + " : " + "[" + args.data.text.toUpperCase() + "] <br> ";
        var part2 =  "<hr>By:" + args.data.text1 +"<br>FROM: "+ getTimeFromDate(args.data.start) + "<br>To: " + getTimeFromDate(args.data.end);
        var part3 ="<hr>" + args.data.status.toUpperCase()     
        args.data.html = part1 + part2 + part3
        
        //args.data.backColor = "red";
        //args.data.fontColor ="white"        
      }
      /*
      args.data.areas = [
        {
          top: 5,
          right: 5,
          width: 16,
          height: 16,
          symbol: "icons/daypilot.svg#minichevron-down-4",
          fontColor: "#666",
          visibility: "Hover",
          action: "ContextMenu",
          style: "background-color: #f9f9f9; border: 1px solid #666; cursor:pointer; border-radius: 15px;"
        }
      ];
      */
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
      dp.events.load("/api/event_list.php");
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

      const form = [
        //{ name: "Name", id: "text" }
        {name: "Type of Reservation", id: "rtype", type: "select", options: restypes},
        {name: "Text", id: "text"},
        {name: "Start", id: "start", type: "datetime"},
        {name: "End", id: "end", type: "datetime"},
        {name: "Status", id: "status", type: " select", options: statuses},
        {name: "Color", id: "barColor", type: "select", options: colors},
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
        rtype: modal.result.rtype
        
      };
      
      await DayPilot.Http.post(`/api/event_update.php`, data);

      dp.events.update({
        ...e.data,
        text: modal.result.text,
        start: modal.result.start,
        end: modal.result.end,
        barColor: modal.result.barColor,
        status: modal.result.status,
        rtype: modal.result.rtype
        
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
      await DayPilot.Http.post(`/api/event_delete.php`, params);

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
        text1: e.data.text1

      };
     
      const { data } = await DayPilot.Http.post(`/api/event_create.php`, event);

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

<script>
  // Get the modal
  var modal = document.getElementById("myModal");

  // Get the button that opens the modal
  var btn = document.getElementById("myBtn");

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  // When the user clicks on the button, open the modal
  btn.onclick = function() {
    modal.style.display = "block";
  }

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>

</body>
</html>

