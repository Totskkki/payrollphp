document.addEventListener("DOMContentLoaded", function () {
	var calendarEl = document.getElementById("dayGrid");
	var calendar = new FullCalendar.Calendar(calendarEl, {
		headerToolbar: {
			left: "prevYear,prev,next,nextYear today",
			center: "title",
			right: "dayGridMonth,dayGridWeek,dayGridDay",
		},
		initialDate: "2022-10-12",
		navLinks: true, // can click day/week names to navigate views
		editable: true,
		dayMaxEvents: true, // allow "more" link when too many events
		events: [
			{
				title: "All Day Event",
				start: "2022-10-01",
				color: "#6f7385",
			},
			{
				title: "Long Event",
				start: "2022-10-07",
				end: "2022-10-10",
				color: "#6f7385",
			},
			{
				groupId: 999,
				title: "Birthday",
				start: "2022-10-09T16:00:00",
				color: "#6f7385",
			},

			{
				title: "Conference",
				start: "2022-10-11",
				end: "2022-10-13",
				color: "#6f7385",
			},
			{
				title: "Meeting",
				start: "2022-10-14T10:30:00",
				end: "2022-10-14T12:30:00",
				color: "#6f7385",
			},
			{
				title: "Lunch",
				start: "2022-10-16T12:00:00",
				color: "#6f7385",
			},
			{
				title: "Click for Google",
				url: "https://www.bootstrap.gallery/",
				start: "2022-10-28",
				color: "#6f7385",
			},
			{
				title: "Interview",
				start: "2022-10-20",
				color: "#6f7385",
			},
			{
				title: "Product Launch",
				start: "2022-10-29",
				color: "#6f7385",
			},
			{
				title: "Leave",
				start: "2022-10-25",
				color: "#6f7385",
			},
		],
	});

	calendar.render();
});
