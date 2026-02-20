
export const initChartThree = () => {
    const chartElement = document.querySelector('#chartThree');

    if (chartElement) {
        const labels = chartElement.dataset.chartLabels ? JSON.parse(chartElement.dataset.chartLabels) : [];
        const attendanceSeries = chartElement.dataset.chartAttendance ? JSON.parse(chartElement.dataset.chartAttendance) : [];
        const loanSeries = chartElement.dataset.chartLoans ? JSON.parse(chartElement.dataset.chartLoans) : [];
        const healthSeries = chartElement.dataset.chartHealth ? JSON.parse(chartElement.dataset.chartHealth) : [];

        const seriesMap = {
            attendance: {
                name: "Absensi",
                data: attendanceSeries,
                color: "#465FFF",
            },
            loans: {
                name: "Peminjaman",
                data: loanSeries,
                color: "#F59E0B",
            },
            health: {
                name: "Tim Kesehatan",
                data: healthSeries,
                color: "#8B5CF6",
            },
        };

        const defaultKey = "attendance";
        const chartThreeOptions = {
            series: [{
                name: seriesMap[defaultKey].name,
                data: seriesMap[defaultKey].data,
            }],
            legend: {
                show: false,
                position: "top",
                horizontalAlign: "left",
            },
            colors: [seriesMap[defaultKey].color],
            chart: {
                fontFamily: "Outfit, sans-serif",
                height: 310,
                type: "area",
                toolbar: {
                    show: false,
                },
            },
            fill: {
                gradient: {
                    enabled: true,
                    opacityFrom: 0.55,
                    opacityTo: 0,
                },
            },
            stroke: {
                curve: "straight",
                width: ["2"],
            },
            markers: {
                size: 0,
            },
            labels: {
                show: false,
                position: "top",
            },
            grid: {
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
            },
            dataLabels: {
                enabled: false,
            },
            tooltip: {
                x: {
                    format: "dd MMM yyyy",
                },
            },
            xaxis: {
                type: "category",
                categories: labels,
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                tooltip: false,
            },
            yaxis: {
                title: {
                    style: {
                        fontSize: "0px",
                    },
                },
            },
        };

        const chart = new ApexCharts(chartElement, chartThreeOptions);
        chart.render();

        const tabButtons = document.querySelectorAll('.chart-tab-btn');
        tabButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const key = button.dataset.chartTab;
                if (!seriesMap[key]) {
                    return;
                }

                tabButtons.forEach((btn) => {
                    btn.classList.remove('bg-white', 'shadow-theme-xs', 'text-gray-700', 'dark:bg-gray-800', 'dark:text-white');
                    btn.classList.add('text-gray-500', 'dark:text-gray-400');
                });
                button.classList.add('bg-white', 'shadow-theme-xs', 'text-gray-700', 'dark:bg-gray-800', 'dark:text-white');
                button.classList.remove('text-gray-500', 'dark:text-gray-400');

                chart.updateOptions({
                    colors: [seriesMap[key].color],
                });
                chart.updateSeries([{
                    name: seriesMap[key].name,
                    data: seriesMap[key].data,
                }]);
            });
        });
        return chart;
    }
}

export default initChartThree;
