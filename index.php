<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>HTML5/JavaScript Event Calendar (Open-Source)</title>

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

    <span id="start"></span> <a href="#" onclick="picker.show(); return false;">Change</a>
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
  nav.init();

  const dp = new DayPilot.Calendar("dp", {
    viewType: "Week",
    headerDateFormat: "dddd",
    cellHeight: 30,
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
   //++++++++++++++++++++++++++++++++++++++++++++++______________MOVE_________________++++++++++++++++++++++++++++
    onEventMoved: async (args) => {
      const id = args.e.id();
      const data = {
        id: args.e.id(),
        start: args.newStart,
        end: args.newEnd,
        text: args.e.text(),
        color: args.e.data.barColor,
        status: args.e.data.status
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
        status: args.e.data.status
        
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

      ]
      const form = [
        {name: "Name", id: "text"},
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
        status: modal.result.status

      };
    
      const {data} = await DayPilot.Http.post(`/api/event_create.php`, event);

      var res = dp.events.add({
        start: args.start,
        end: args.end,
        id: data.id,
        text: modal.result.text,
        text1: modal.result.text1,
        barColor: modal.result.barColor,
        status: modal.result.status
      });

      console.log(event)
    },
    onEventClick: async (args) => {
      app.editEvent(args.e);
    },
    onBeforeEventRender: args => {
      if (args.data.status == 'approved'){
      
        args.data.html = args.data.text + ":" + "[" + args.data.status + "]";
      }
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

      ]

      const form = [
        //{ name: "Name", id: "text" }
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
        status: modal.result.status
        
      };
      
      await DayPilot.Http.post(`/api/event_update.php`, data);

      dp.events.update({
        ...e.data,
        text: modal.result.text,
        start: modal.result.start,
        end: modal.result.end,
        barColor: modal.result.barColor,
        status: modal.result.status
        
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
        status: e.data.status

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

