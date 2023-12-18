$(document).ready(function() {
    $(".delete-pillow").click(function(event) {
        event.preventDefault();
        let confirmed = confirm("Уверены ли вы?");
        if (confirmed) {
            window.location.href = $(this).attr("href");
        }
    });
    selectedSum();

    renderChartJS();
});

function selectedSum() {
    if ($('select[name="currency"]').length > 0) {
        let selectedCurrency = $('select[name="currency"]').val();

        $.ajax({
            url: "/backend/script/pillow.php",
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'selectSum',
                selectedCurrency: selectedCurrency
            },
            success: function (data) {
                if(data !== "false"){
                    $(".span-sum-currency").text(data);
                }
            }
        });
    }
}

function typeChartJS(){
    $(".h6-analytics").removeClass("text-danger");
    $(this).addClass("text-danger");

    renderChartJS();
}

function renderChartJS(){
    let element = $(".h6-analytics.text-danger");

    let key = element.attr("id");

    $(document).ready(function() {
        $.ajax({
            url: "/backend/script/analytics.php",
            method: 'POST',
            dataType: 'json',
            data:{
                key: key
            },
            success: function (data) {
                console.log(data);

                let canvas = document.getElementById('analytics');
                let existingChart = Chart.getChart(canvas);
                if (existingChart) {
                    // Уничтожаем предыдущий график
                    existingChart.destroy();
                }

                if(key === "day"){
                    renderChartJSDay(data);
                }
                if(key === "days"){
                    renderChartJSDays(data);
                }
                if(key === "months"){
                    renderChartJSMonths(data);
                }
            }
        });
    });
}

function renderChartJSMonths(mass){
    const multipleLineChart = document.getElementById('analytics').getContext('2d');

    const data = mass;

    const datasets = {};
    let color = rand_color();

    data.forEach(item => {
        const currency = item.currency_name;
        if (!datasets[currency]) {
            datasets[currency] = {
                label: currency,
                borderColor: color,
                pointBorderColor: color,
                pointHoverBorderColor: "#ffffff",
                pointBackgroundColor: color,
                pointHoverBackgroundColor: "#ffffff",
                pointBorderWidth: 0,
                pointHoverRadius: 3,
                pointHoverBorderWidth: 3,
                pointRadius: 1,
                backgroundColor: 'transparent',
                fill: true,
                borderWidth: 2,
                data: [],
                lineTension: 0.4
            };
        }
        datasets[currency].data.push(item.total_value);
    });

    const allMonths = [...new Set(data.map(item => item.month))];
    const chartData = {
        labels: allMonths,
        datasets: Object.values(datasets)
    };

    const chartOptions = {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                beginAtZero: false,
                ticks: {
                    autoSkip: true,
                    maxTicksLimit: 5,
                    callback: function(value, index, values) {
                        if (value >= 1000) {
                            return (value / 1000).toFixed(0) + 'k';
                        }
                        return value;
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        },
        elements: {
            point: {
                hitRadius: 8,
                hoverRadius: 5,
                radius: 1,
                borderWidth: 5,
                borderColor: 'blue',
                backgroundColor: 'transparent',
            }
        }
    };

    const myMultipleLineChart = new Chart(multipleLineChart, {
        type: 'line',
        data: chartData,
        options: chartOptions
    });
}

function renderChartJSDays(mass) {
    const multipleLineChart = document.getElementById('analytics').getContext('2d');

    const data = mass;

    const datasets = data.map(item => {
        const currency = item.currency_name;
        const allDates = [...new Set(data.map(innerItem => innerItem.day))];
        const values = allDates.map(date => {
            const item = data.find(item => item.day === date && item.currency_name === currency);
            return item ? item.total_value : null;
        });

        const nonNullValues = [];
        let prevValue = null;
        for (let i = 0; i < values.length; i++) {
            if (values[i] !== null) {
                nonNullValues[i] = values[i];
                prevValue = values[i];
            } else {
                nonNullValues[i] = prevValue;
            }
        }

        let color = rand_color();

        return {
            label: currency,
            borderColor: color,
            pointBorderColor: color,
            pointHoverBorderColor: "#ffffff",
            pointBackgroundColor: color,
            pointHoverBackgroundColor: "#ffffff",
            pointBorderWidth: 0,
            pointHoverRadius: 3,
            pointHoverBorderWidth: 3,
            pointRadius: 1,
            backgroundColor: 'transparent',
            fill: true,
            borderWidth: 2,
            data: nonNullValues,
            lineTension: 0.4
        };
    });

    const allDates = [...new Set(data.map(item => item.day))];
    const chartData = {
        labels: allDates,
        datasets: datasets
    };

    const chartOptions = {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                beginAtZero: false,
                ticks: {
                    autoSkip: true,
                    maxTicksLimit: 5,
                    callback: function(value, index, values) {
                        if (value >= 1000) {
                            return (value / 1000).toFixed(0) + 'k';
                        }
                        return value;
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        },
        elements: {
            point: {
                hitRadius: 8,
                hoverRadius: 5,
                radius: 1,
                borderWidth: 5,
                borderColor: 'blue',
                backgroundColor: 'transparent',
            }
        }
    };

    const myMultipleLineChart = new Chart(multipleLineChart, {
        type: 'line',
        data: chartData,
        options: chartOptions
    });
}

function renderChartJSDay(mass){

    const multipleLineChart = document.getElementById('analytics').getContext('2d');

    const data = mass;

    const datasets = Object.keys(data).map(currency => {
        const allDates = [...new Set(Object.values(data).flatMap(item => item.map(innerItem => innerItem.date)))];
        const values = allDates.map(date => {
            const item = data[currency].find(item => item.date === date);
            return item ? item.value : null;
        });


        const nonNullValues = [];
        let prevValue = null;
        for (let i = 0; i < values.length; i++) {
            if (values[i] !== null) {
                nonNullValues[i] = values[i];
                prevValue = values[i];
            } else {
                nonNullValues[i] = prevValue;
            }
        }
        let color = rand_color();

        return {
            label: currency,
            borderColor: color,
            pointBorderColor: color,
            pointHoverBorderColor: "#ffffff",
            pointBackgroundColor: color,
            pointHoverBackgroundColor: "#ffffff",
            pointBorderWidth: 0,
            pointHoverRadius: 3,
            pointHoverBorderWidth: 3,
            pointRadius: 1,
            backgroundColor: 'transparent',
            fill: true,
            borderWidth: 2,
            data: nonNullValues,
            lineTension: 0.4
        };
    });

    const allDates = [...new Set(Object.values(data).flatMap(item => item.map(innerItem => innerItem.date)))];
    const chartData = {
        labels: allDates,
        datasets: datasets
    };

    const chartOptions = {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            x: {
                display: false, // Скрыть метки по оси X
            },
            y: {
                beginAtZero: false,
                ticks: {
                    autoSkip: true,
                    maxTicksLimit: 5,
                    callback: function(value, index, values) {
                        if (value >= 1000) {
                            return (value / 1000).toFixed(0) + 'k';
                        }
                        return value;
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: true
            }
        },
        elements: {
            point: {
                hitRadius: 8,
                hoverRadius: 5,
                radius: 1,
                borderWidth: 5,
                borderColor: 'blue',
                backgroundColor: 'transparent',
            }
        }
    };

    const myMultipleLineChart = new Chart(multipleLineChart, {
        type: 'line',
        data: chartData,
        options: chartOptions
    });
}


//Генерация цветов
function rand_color() {
    const array = [
        '#52ff00', '#b400b4',
        '#ff0000', '#f9ff00', '#0020a2', '#00932a'
    ];
    const random = Math.floor(Math.random() * array.length);
    return array[random];
}