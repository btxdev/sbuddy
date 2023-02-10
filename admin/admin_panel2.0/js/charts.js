/*
 *  Swiftly Admin Panel v1.12 alpha
 *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
 *  All rights reserved.
 */

var chart1_params = {
  chart: {
    type: "area",
    height: 265,
    width: '100%',
    foreColor: "#ccc",
    toolbar: {
      autoSelected: "pan",
      show: true
    },
    animations: {
        enabled: animateCharts,
        easing: 'easeinout',
        speed: 800,
        animateGradually: {
            enabled: true,
            delay: 150
        },
        dynamicAnimation: {
            enabled: true,
            speed: 350
        }
    }
  },
  colors: ['#5d78ff', '#0abb87'],
  stroke: {
    width: 3
  },
  grid: {
    borderColor: 'var(--main-bg-search)',
    strokeDashArray: 7,
    clipMarkers: false,
    yaxis: {
      lines: {
        show: true
      }
    }
  },
  dataLabels: {
    enabled: false
  },
  fill: {
    gradient: {
      enabled: true,
      opacityFrom: 0.75,
      opacityTo: 0
    }
  },
  legend: {
      show: true,
      position: 'top',
      horizontalAlign: 'left',
      verticalAlign: 'middle',
      floating: false,
      fontSize: '15px',
      fontFamily: 'pfl',
      showForSingleSeries: false,
      showForNullSeries: true,
      showForZeroSeries: true,
      offsetX: -16,
      offsetY: 0,

  },
  markers: {
    size: 0,
    colors: ["#fff"],
    strokeColor: ['#5d78ff', '#0abb87'],
    strokeWidth: 3
  },
  series: [
    {
      name: 'Все пользователи',
      data: [[new Date("01/01/2014 00:00:00").getTime(), 15], [new Date("01/01/2014 01:00:00").getTime(), 10], [new Date("01/01/2014 02:00:00").getTime(), 12], [new Date("01/01/2014 03:00:00").getTime(), 42], [new Date("01/01/2014 04:00:00").getTime(), 76], [new Date("01/01/2014 05:00:00").getTime(), 51], [new Date("01/01/2014 06:00:00").getTime(), 76], [new Date("01/01/2014 07:00:00").getTime(), 121], [new Date("01/01/2014 08:00:00").getTime(), 108], [new Date("01/01/2014 09:00:00").getTime(), 96], [new Date("01/01/2014 10:00:00").getTime(), 84], [new Date("01/01/2014 11:00:00").getTime(), 115], [new Date("01/01/2014 12:00:00").getTime(), 150], [new Date("01/01/2014 13:00:00").getTime(), 140], [new Date("01/01/2014 14:00:00").getTime(), 200], [new Date("01/01/2014 15:00:00").getTime(), 230], [new Date("01/01/2014 16:00:00").getTime(), 240]]
    },
    {
      name: 'Уникальные пользователи',
      data: [[new Date("01/01/2014 00:00:00").getTime(), 1], [new Date("01/01/2014 01:00:00").getTime(), 3], [new Date("01/01/2014 02:00:00").getTime(), 5], [new Date("01/01/2014 03:00:00").getTime(), 18], [new Date("01/01/2014 04:00:00").getTime(), 28], [new Date("01/01/2014 05:00:00").getTime(), 7], [new Date("01/01/2014 06:00:00").getTime(), 26], [new Date("01/01/2014 07:00:00").getTime(), 51], [new Date("01/01/2014 08:00:00").getTime(), 68], [new Date("01/01/2014 09:00:00").getTime(), 48], [new Date("01/01/2014 10:00:00").getTime(), 52], [new Date("01/01/2014 11:00:00").getTime(), 90], [new Date("01/01/2014 12:00:00").getTime(), 70], [new Date("01/01/2014 13:00:00").getTime(), 96], [new Date("01/01/2014 14:00:00").getTime(), 126], [new Date("01/01/2014 15:00:00").getTime(), 194], [new Date("01/01/2014 16:00:00").getTime(), 225]]
    }
  ],
  tooltip: {
    theme: theme_chart,
    x: {
      format: 'dd MMM в HH:mm',
    },
    y:{
      format: 'dd MMM в HH:mm',
    }
  },

  xaxis: {
    type: "datetime",
    labels: {
      format: 'HH:mm',
      datetimeUTC: false,
    }
  },
  yaxis: {
    min: 0,
    tickAmount: 4,
  }
};
var chart2_params = {
  chart: {
    height: "100%",
    type: "area",
    toolbar: {
      show: false
    },
    zoom: {
      enabled: false,
    },
    animations: {
        enabled: animateCharts,
    }
  },
  // stroke: {
  //   curve: 'stepline',
  // },
  colors: ['#fff'],
  dataLabels: {
    enabled: false
  },
  crosshairs: {
    show: false
  },
  tooltip: {
    theme: theme_chart,
  },
  series: [
    {
      name: "Новых",
      //data: [[new Date("01/01/2014 00:00:00").getTime(), 15], [new Date("01/01/2014 01:00:00").getTime(), 10], [new Date("01/01/2014 02:00:00").getTime(), 12], [new Date("01/01/2014 03:00:00").getTime(), 42], [new Date("01/01/2014 04:00:00").getTime(), 76], [new Date("01/01/2014 05:00:00").getTime(), 51], [new Date("01/01/2014 06:00:00").getTime(), 76], [new Date("01/01/2014 07:00:00").getTime(), 121], [new Date("01/01/2014 08:00:00").getTime(), 108], [new Date("01/01/2014 09:00:00").getTime(), 96], [new Date("01/01/2014 10:00:00").getTime(), 84], [new Date("01/01/2014 11:00:00").getTime(), 115], [new Date("01/01/2014 12:00:00").getTime(), 150], [new Date("01/01/2014 13:00:00").getTime(), 140], [new Date("01/01/2014 14:00:00").getTime(), 200], [new Date("01/01/2014 15:00:00").getTime(), 230], [new Date("01/01/2014 16:00:00").getTime(), 240]]
      data: registerStatisticsData
    }
  ],
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 0,
      opacityFrom: 0,
      opacityTo: 0,
      stops: [0, 90, 100]
    }
  },
  grid: {
    borderColor: 'transparent',
  },
  xaxis: {
    type: "datetime",
    labels: {
       show: false,
    },
    axisBorder: {
        show: false,
    }
  },
  yaxis: {
    show: false
  }
};
var chart3_params = {
        chart: {
            type: 'bar',
            height: 107,
            sparkline: {
                enabled: true
            },
            animations: {
                enabled: animateCharts,
            }
        },
        colors: ["#5d78ff"],
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: false
                },
                columnWidth: '75%',
                endingShape: 'rounded'
            }
        },
        series: [{
            data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54, 25, 66, 41, 89, 63, 54],
            name: ["1 января",'2 января','3 января','4 января','5 января','6 января','7 января','8 января','9 января','10 января','11 января','12 января','13 января','14 января','15 января','16 января','17 января'],
        }],
        xaxis: {
            crosshairs: {
                width: 1
            },
        },
        tooltip: {
            theme: theme_chart,
            fixed: {
                enabled: false
            },
            x: {
                show: false
            },
            y: {
                title: {
                  formatter: function(value, { seriesIndex, dataPointIndex, w }) {
                    return w.config.series[seriesIndex].name[dataPointIndex]
                  }
                }
            },
            marker: {
                show: false
            }
        }
    }
var chart3_1_params = {
        chart: {
            type: 'bar',
            height: 107,
            sparkline: {
                enabled: true
            },
            animations: {
                enabled: animateCharts,
            }
        },
        colors: ["#5d78ff"],
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: false
                },
                columnWidth: '75%',
                endingShape: 'rounded'
            }
        },
        series: [{
            data: [37, 65, 32, 51, 34, 70, 28, 45, 50, 24, 45, 42],
            name: ["Январь",'Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь']
        }],
        xaxis: {
            crosshairs: {
                width: 1
            },
        },
        tooltip: {
            theme: theme_chart,
            fixed: {
                enabled: false
            },
            x: {
                show: false,
                formatter: undefined,
            },
            y: {
                title: {
                  formatter: function(value, { seriesIndex, dataPointIndex, w }) {
                    return w.config.series[seriesIndex].name[dataPointIndex]
                  }
                }
            },
            marker: {
                show: false
            }
        }
    }
var chart1;
var chart2;
var chart3;



function statMap(a){
  $(a).vectorMap({
    map: 'world_mill_en',
    normalizeFunction: 'polynomial',
    hoverOpacity: 0.6,
    hoverColor: false,
    regionStyle: {
        initial: {
            fill: 'var(--border-color)'
        }
    },
    onRegionClick: function(event, code){
      console.log(code)
      // if(code == 'RU'){
      //   console.log('Вы нажали на Россию');
      // }
  	},
    markerStyle: {
        initial: {
            r: 5,
            'fill': '#5d78ff90',
            'fill-opacity': 0.9,
            'stroke': '#5d78ff2e',
            'stroke-width': 7,
            'stroke-opacity': 0.4
        },

        hover: {
            r: 7,
            'stroke': '#1fca943b',
            'fill': '#0abb87',
            'fill-opacity': 1,
            'stroke-width': 1.5
        }
    },
    backgroundColor: 'transparent',
    markers: [{
        latLng: [41.9, 12.45],
        name: "Ватикан: 5"
    }, {
        latLng: [58.0, 56.15],
        name: "Пермь: 545"
    }, {
        latLng: [55.45, 37.36],
        name: "Москва: 345"
    }, {
        latLng: [56.51, 60.36],
        name: "Екатеринбург: 137"
    }, {
        latLng: [43.73, 7.41],
        name: "Монако: 5"
    }, {
        latLng: [-.52, 166.93],
        name: "Науру: 5"
    }, {
        latLng: [-8.51, 179.21],
        name: "Тувалу: 5"
    }, {
        latLng: [43.93, 12.46],
        name: "Сан-Марино: 5"
    }, {
        latLng: [47.14, 9.52],
        name: "Лихтенштейн: 5"
    }, {
        latLng: [7.11, 171.06],
        name: "Маршалловы острова: 5"
    }, {
        latLng: [17.3, -62.73],
        name: "Сент-Китс и Невис: 5"
    }, {
        latLng: [3.2, 73.22],
        name: "Мальдивы: 5"
    }],
    zoomOnScroll: true
});
}

$(document).ready(function(){

  chart1 = new ApexCharts(document.querySelector("#chart1"), chart1_params);
  chart1.render();
  chart2 = new ApexCharts(document.querySelector("#chart2"), chart2_params);
  chart2.render();
  chart3 = new ApexCharts(document.querySelector("#chart3"), chart3_params);
  chart3.render();

  function generateDayWiseTimeSeries(baseval, count, yrange) {
    var i = 0;
    var series = [];
    while (i < count) {
      var x = baseval;
      var y =
        Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

      series.push([x, y]);
      baseval += 86400000;
      i++;
    }
    return series;
  }


});

function updateChartsNew(a, b) {

  if(a == undefined){
    a = theme_chart;
  }
  if(b == undefined){
    b = false;
  }

  animateCharts = b;

  for(let i = 1; i <= count_chart; i++){
    if(i == 3){
      chart3_1_params.tooltip.theme = a;
      chart3_1_params.chart.animations.enabled = animateCharts;
    }
    window['chart' + i + '_params'].tooltip.theme = a;
    window['chart' + i + '_params'].chart.animations.enabled = animateCharts;
  }


  if(typeof(chart1) != 'undefined') {
    chart1.destroy();
    chart2.destroy();
    chart3.destroy();

  }
  chart1 = new ApexCharts(document.querySelector("#chart1"), chart1_params);
  chart1.render();
  chart2 = new ApexCharts(document.querySelector("#chart2"), chart2_params);
  chart2.render();
  if(count_type_chart3 == 0){
    chart3 = new ApexCharts(document.querySelector("#chart3"), chart3_params);
  } else if(count_type_chart3 == 1){
    chart3 = new ApexCharts(document.querySelector("#chart3"), chart3_1_params);
  }
  chart3.render();

  setTimeout(function(){
    animateCharts = true;

    for(let i = 1; i <= count_chart; i++){
      if(i == 3){
        chart3_1_params.chart.animations.enabled = animateCharts;
      }
      window['chart' + i + '_params'].chart.animations.enabled = animateCharts;
    }
  }, 1)
}

function updateCharts() {
  ApexCharts.exec('chart1', 'updateOptions', {
    theme: {
        mode: theme_chart
    },
    chart: {
      foreColor: theme_chart === 'dark' ? '#f6f7f8' : '#373d3f'
    },
    tooltip: {
        theme: theme_chart
    }
  });
}
