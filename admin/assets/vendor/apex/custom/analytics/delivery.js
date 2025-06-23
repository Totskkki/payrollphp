var options = {
	chart: {
		height: 245,
		type: "bar",
		toolbar: {
			show: false,
		},
	},
	plotOptions: {
		bar: {
			columnWidth: "60%",
			borderRadius: 5,
			// distributed: true,
			dataLabels: {
				position: "top",
			},
		},
	},
	series: [
		{
			name: "Delivered",
			data: [30, 40, 50],
		},
	],
	legend: {
		show: false,
	},
	xaxis: {
		categories: ["Pizza", "Burger", "Donut"],
		axisBorder: {
			show: false,
		},
		yaxis: {
			show: false,
		},
		tooltip: {
			enabled: true,
		},
		labels: {
			show: true,
		},
	},
	yaxis: {
		show: false,
	},
	grid: {
		show: false,
		padding: {
			top: 0,
			right: 0,
			bottom: -10,
			left: 10,
		},
	},
	tooltip: {
		y: {
			formatter: function (val) {
				return val;
			},
		},
	},
	colors: ["#8c44aa", "#2599ff", "#f07f1c"],
};
var chart = new ApexCharts(document.querySelector("#deliveryGraph"), options);
chart.render();
