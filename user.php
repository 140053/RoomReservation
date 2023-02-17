<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Room Reservation</title>

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
  <div>Schedule</div>
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
    #cbtn{
      background-color: #cfb816; /* Green background */
      border: 1px solid green; /* Green border */
      color: white; /* White text */
      padding: 10px 24px; /* Some padding */
      cursor: pointer; /* Pointer/hand icon */
      float: left; /* Float the buttons side by side */
    }

    /* Clear floats (clearfix hack) */
    .btn-group:after{
      content: "";
      clear: both;
      display: table;
    }

    .btn-group button:not(:last-child) {
      border-right: none; /* Prevent double borders */
    }

    /* Add a background color on hover */
    .btn-group button:hover {
      background-color: #3e8e41;
    }
    </style>
    <div class="btn-group">
      <button>Date:</button>
      <button id="start">Apple</button>    
      <a id="cbtn" href="#" onclick="picker.show(); return false;">Change</a>
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
    </div>

  </div>
</div>

<script>
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
    cellHeight: 40,
    crosshairType: "Disabled",
    businessBeginsHour: 7,
    dayBeginsHour: 7,
    dayEndsHour: 18,
    timeRangeSelectedHandling: "Enabled",
    theme: "calendar_green",
    /*
    eventDeleteHandling: "Update",
    onEventDeleted: async (args) => {
        const id = args.e.id();
        await DayPilot.Http.delete(`/api/CalendarEvents/${id}`);
        console.log("Deleted.");
    },
    */
    onEventMoved: async (args) => {
      const id = args.e.id();
      const data = {
        id: args.e.id(),
        start: args.newStart,
        end: args.newEnd,
        text: args.e.text(),
        color: args.e.data.barColor
      };
      //console.log(args.e.data.barColor)
      await DayPilot.Http.post(`/api/event_update.php`, data);
      console.log("Moved.");
    },
    onEventResized: async (args) => {
      const id = args.e.id();
      const data = {
        id: args.e.id(),
        start: args.newStart,
        end: args.newEnd,
        text: args.e.text(),
        color: args.e.data.barColor
        
      };
      await DayPilot.Http.post(`/api/event_update.php`, data);
      console.log("Resized.");
    },
    onTimeRangeSelected: async (args) => {
      const colors = [
                    {name: "Blue", id: "#3c78d8"},
                    {name: "Green", id: "#6aa84f"},
                    {name: "Yellow", id: "#f1c232"},
                    {name: "Red", id: "#cc0000"},
                ];
      const form = [
        {name: "Name", id: "text"},
        {name: "Reserved by:", id: "text1"},
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

      };
    
      
      const {data} = await DayPilot.Http.post(`/api/event_create.php`, event);

      var res = dp.events.add({
        start: args.start,
        end: args.end,
        id: data.id,
        text: modal.result.text,
        text1: modal.result.text1,
        barColor: modal.result.barColor
      });

      console.log(event)
    },
    onEventClick: async (args) => {
      app.editEvent(args.e);
    },
    onBeforeEventRender: args => {
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
    loadEvents() {
      dp.events.load("/api/event_list.php");
    },
    async editEvent(e) {
      const colors = [
                    {name: "Blue", id: "#3c78d8"},
                    {name: "Green", id: "#6aa84f"},
                    {name: "Yellow", id: "#f1c232"},
                    {name: "Red", id: "#cc0000"},
                ];

      const form = [
        //{ name: "Name", id: "text" }
        {name: "Text", id: "text"},
        {name: "Start", id: "start", type: "datetime"},
        {name: "End", id: "end", type: "datetime"},
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
        color: modal.result.barColor
        
      };
      
      await DayPilot.Http.post(`/api/event_update.php`, data);

      dp.events.update({
        ...e.data,
        text: modal.result.text,
        start: modal.result.start,
        end: modal.result.end,
        barColor: modal.result.barColor
        
      });
      console.log("Updated.");
    },
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
    async duplicateEvent(e) {
      
      const event = {
        start: e.start(),
        end: e.end(),
        text: e.text() + " (copy)",
        barColor: e.data.barColor

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

</body>
</html>

