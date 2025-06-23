var options = {
	chart: {
		height: 380,
		type: "area",
		toolbar: {
			show: false,
		},
	},
	dataLabels: {
		enabled: false,
	},
	stroke: {
		curve: "smooth",
		width: 3,
	},
	series: [
		{
			name: "Sales",
			data: [10, 40, 15, 30, 20, 35, 20, 10, 30, 40, 55, 30],
		},
		{
			name: "Clicks",
			data: [5, 10, 25, 5, 10, 25, 50, 35, 40, 20, 35, 75],
		},
	],
	grid: {
		borderColor: "#dfd6ff",
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
	},
	xaxis: {
		categories: [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"May",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Oct",
			"Nov",
			"Dec",
		],
	},
	yaxis: {
		labels: {
			show: false,
		},
	},
	tooltip: {
		y: {
			formatter: function (val) {
				return val + "k";
			},
		},
	},
	colors: ["#097ce0", "#8c44aa"],
};

var chart = new ApexCharts(document.querySelector("#yearlySales"), options);

chart.render();
