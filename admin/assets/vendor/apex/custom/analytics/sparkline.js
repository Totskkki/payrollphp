// Option 1
var options1 = {
	series: [
		{
			name: "Products",
			data: [5, 10, 30, 15, 35, 25, 45],
		},
	],
	chart: {
		type: "line",
		width: 100,
		height: 60,
		sparkline: {
			enabled: true,
		},
	},
	stroke: {
		curve: "smooth",
		width: 3,
	},
	xaxis: {
		crosshairs: {
			width: 1,
		},
	},
	tooltip: {
		fixed: {
			enabled: false,
		},
		x: {
			show: false,
		},
		marker: {
			show: false,
		},
	},
	grid: {
		show: false,
	},
	colors: ["#ffffff"],
	xaxis: {
		categories: [
			"Sunday",
			"Monday",
			"Tuesday",
			"Wednesday",
			"Thursday",
			"Friday",
			"Saturday",
		],
	},
	tooltip: {
		y: {
			formatter: function (val) {
				return val + " Sales";
			},
		},
	},
};
var chart1 = new ApexCharts(document.querySelector("#spark1"), options1);
chart1.render();

// Option 2
var options2 = {
	series: [
		{
			name: "Orders",
			data: [5, 10, 30, 15, 35, 25, 45],
		},
	],
	chart: {
		type: "line",
		width: 100,
		height: 60,
		sparkline: {
			enabled: true,
		},
	},
	stroke: {
		curve: "smooth",
		width: 3,
	},
	xaxis: {
		crosshairs: {
			width: 1,
		},
	},
	tooltip: {
		fixed: {
			enabled: false,
		},
		x: {
			show: false,
		},
		marker: {
			show: false,
		},
	},
	grid: {
		show: false,
	},
	colors: ["#ffffff"],
	xaxis: {
		categories: [
			"Sunday",
			"Monday",
			"Tuesday",
			"Wednesday",
			"Thursday",
			"Friday",
			"Saturday",
		],
	},
	tooltip: {
		y: {
			formatter: function (val) {
				return val + " Sales";
			},
		},
	},
};
var chart2 = new ApexCharts(document.querySelector("#spark2"), options2);
chart2.render();

// Option 3
var options3 = {
	series: [
		{
			name: "Reviews",
			data: [5, 10, 30, 15, 35, 25, 45],
		},
	],
	chart: {
		type: "line",
		width: 100,
		height: 60,
		sparkline: {
			enabled: true,
		},
	},
	stroke: {
		curve: "smooth",
		width: 3,
	},
	xaxis: {
		crosshairs: {
			width: 1,
		},
	},
	tooltip: {
		fixed: {
			enabled: false,
		},
		x: {
			show: false,
		},
		marker: {
			show: false,
		},
	},
	grid: {
		show: false,
	},
	colors: ["#ffffff"],
	xaxis: {
		categories: [
			"Sunday",
			"Monday",
			"Tuesday",
			"Wednesday",
			"Thursday",
			"Friday",
			"Saturday",
		],
	},
	tooltip: {
		y: {
			formatter: function (val) {
				return val + " Sales";
			},
		},
	},
};
var chart3 = new ApexCharts(document.querySelector("#spark3"), options3);
chart3.render();
