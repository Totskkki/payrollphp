var options = {
	series: [
		{
			name: "Visitors",
			data: [10, 20, 30, 40],
		},
		{
			name: "Orders",
			data: [15, 25, 35, 45],
		},
		{
			name: "Income",
			data: [20, 30, 40, 50],
		},
	],
	chart: {
		type: "bar",
		height: 300,
		stacked: true,
		toolbar: {
			show: false,
		},
	},
	plotOptions: {
		bar: {
			horizontal: false,
			columnWidth: "40%",
		},
	},
	legend: {
		show: false,
	},
	stroke: {
		width: 0,
	},
	xaxis: {
		categories: ["Q1", "Q2", "Q3", "Q4"],
		labels: {
			show: false,
		},
	},
	yaxis: {
		labels: {
			show: false,
		},
	},
	grid: {
		borderColor: "#b7c6d8",
		strokeDashArray: 5,
		xaxis: {
			lines: {
				show: true,
			},
		},
		yaxis: {
			lines: {
				show: false,
			},
		},
		padding: {
			top: 0,
			right: 10,
			left: 20,
			bottom: -20,
		},
	},
	tooltip: {
		y: {
			formatter: function (val) {
				return val;
			},
		},
	},
	colors: ["#2599ff", "#3ba3ff", "#51adff", "#66b8ff", "#92ccff"],
	fill: {
		opacity: 1,
	},
};

var chart = new ApexCharts(document.querySelector("#statistics"), options);
chart.render();
