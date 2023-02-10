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
var chart4_params = {
          chart: {
            type: "area",
            height: 265,
            width: '100%',
            foreColor: "#ccc",
            toolbar: {
              autoSelected: "pan",
              show: true
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
          markers: {
            size: 0,
            colors: ["#fff"],
            strokeColor: ['#5d78ff', '#0abb87'],
            strokeWidth: 3
          },
          series: [
            {
              name: 'Все пользователи',
              data: [[new Date("01/01/2014 00:00:00").getTime(), 15], [new Date("01/01/2014 01:00:00").getTime(), 10], [new Date("01/01/2014 02:00:00").getTime(), 12], [new Date("01/01/2014 03:00:00").getTime(), 42], [new Date("01/01/2014 04:00:00").getTime(), 76], [new Date("01/01/2014 05:00:00").getTime(), 51], [new Date("01/01/2014 06:00:00").getTime(), 76], [new Date("01/01/2014 07:00:00").getTime(), 121], [new Date("01/01/2014 08:00:00").getTime(), 108], [new Date("01/01/2014 09:00:00").getTime(), 96], [new Date("01/01/2014 10:00:00").getTime(), 84], [new Date("01/01/2014 11:00:00").getTime(), 115], [new Date("01/01/2014 12:00:00").getTime(), 150], [new Date("01/01/2014 13:00:00").getTime(), 140], [new Date("01/01/2014 14:00:00").getTime(), 200], [new Date("01/01/2014 15:00:00").getTime(), 230], [new Date("01/01/2014 16:00:00").getTime(), 240], [new Date("01/01/2014 17:00:00").getTime(), 210], [new Date("01/01/2014 18:00:00").getTime(), 140], [new Date("01/01/2014 19:00:00").getTime(), 90]]
            },
            {
              name: 'Уникальные пользователи',
              data: [[new Date("01/01/2014 00:00:00").getTime(), 1], [new Date("01/01/2014 01:00:00").getTime(), 3], [new Date("01/01/2014 02:00:00").getTime(), 5], [new Date("01/01/2014 03:00:00").getTime(), 18], [new Date("01/01/2014 04:00:00").getTime(), 28], [new Date("01/01/2014 05:00:00").getTime(), 7], [new Date("01/01/2014 06:00:00").getTime(), 26], [new Date("01/01/2014 07:00:00").getTime(), 51], [new Date("01/01/2014 08:00:00").getTime(), 68], [new Date("01/01/2014 09:00:00").getTime(), 48], [new Date("01/01/2014 10:00:00").getTime(), 52], [new Date("01/01/2014 11:00:00").getTime(), 90], [new Date("01/01/2014 12:00:00").getTime(), 70], [new Date("01/01/2014 13:00:00").getTime(), 96], [new Date("01/01/2014 14:00:00").getTime(), 126], [new Date("01/01/2014 15:00:00").getTime(), 194], [new Date("01/01/2014 16:00:00").getTime(), 225], [new Date("01/01/2014 17:00:00").getTime(), 160], [new Date("01/01/2014 18:00:00").getTime(), 54], [new Date("01/01/2014 19:00:00").getTime(), 25]]
            }
          ],
          tooltip: {
            theme: theme_chart
          },
          xaxis: {
            type: "datetime",
            labels: {
              format: 'HH:mm',
            }
          },
          yaxis: {
            min: 0,
            tickAmount: 4
          }
        };
var chart5_params = {
  chart: {
    height: 220,
    width: '100%',
    type: 'donut',
  sparkline: {
    enabled: true
  },
  },
  plotOptions: {
    pie: {
      expandOnClick: false,
      customScale: 0.9,
    }
  },
  series: [32, 28, 20, 12, 8],
  legend: {
    show: false,
    position: 'bottom',
    horizontalAlign: 'center',
    verticalAlign: 'middle',
    floating: false,
    fontSize: '15px',
    fontFamily: 'pfm',
    showForSingleSeries: false,
    showForNullSeries: true,
    showForZeroSeries: true,
    offsetX: -20,
    offsetY: 5,
    labels: {
      colors: 'var(--color)',
      useSeriesColors: false,

    },
  onItemClick: {
  toggleDataSeries: false
  },
  },
  labels: ["Младше 18 лет", "18-24 года", "25-34 года", "35-44 года", "45 лет и старше"],
  colors: ["#5d78ff", "#0abb87", "#00bcd4", "#ffb822", "#fd397a"],
  tooltip: {
    theme: theme_chart
  },
}
var chart6_params = {
            chart: {
                height: 220,
                width: '100%',
                type: 'donut',
                sparkline: {
                    enabled: true
                },
            },
            plotOptions: {
              pie: {
                expandOnClick: false,
                customScale: 0.9,
              }
            },
            series: [44, 56],
            legend: {
                show: false,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '15px',
                fontFamily: 'pfm',
                showForSingleSeries: false,
                showForNullSeries: true,
                showForZeroSeries: true,
                offsetX: -20,
                offsetY: 5,
                labels: {
                    colors: 'var(--color)',
                    useSeriesColors: false,

                },
                onItemClick: {
                    toggleDataSeries: false
                },
            },
            labels: ["Мужской", "Женский"],
            colors: ["#5d78ff", "#ffb822"],
            tooltip: {
              theme: theme_chart
            },
        }
var chart7_params = {
                chart: {
                    height: 220,
                    width: '100%',
                    type: 'donut',
                    sparkline: {
                        enabled: true
                    },
                },
                plotOptions: {
                  pie: {
                    expandOnClick: false,
                    customScale: 0.9,
                  }
                },
                series: [55, 45],
                legend: {
                    show: false,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    verticalAlign: 'middle',
                    floating: false,
                    fontSize: '15px',
                    fontFamily: 'pfm',
                    showForSingleSeries: false,
                    showForNullSeries: true,
                    showForZeroSeries: true,
                    offsetX: -20,
                    offsetY: 5,
                    labels: {
                        colors: 'var(--color)',
                        useSeriesColors: false,

                    },
                    onItemClick: {
                        toggleDataSeries: false
                    },
                },
                labels: ["Авторизированных", "Не авторизированных"],
                colors: ["#0abb87", "#5d78ff"],
                tooltip: {
                  theme: theme_chart
                },
            }
var chart8_params = {
        chart: {
            height: 220,
            width: '100%',
            type: 'donut',
            sparkline: {
                enabled: true
            },
        },
        plotOptions: {
          pie: {
            expandOnClick: false,
            customScale: 0.9,
          }
        },
        series: [32, 68],
        legend: {
            show: false,
            position: 'bottom',
            horizontalAlign: 'center',
            verticalAlign: 'middle',
            floating: false,
            fontSize: '15px',
            fontFamily: 'pfm',
            showForSingleSeries: false,
            showForNullSeries: true,
            showForZeroSeries: true,
            offsetX: -20,
            offsetY: 5,
            labels: {
                colors: 'var(--color)',
                useSeriesColors: false,

            },
            onItemClick: {
                toggleDataSeries: false
            },
        },
        labels: ["Вернувшихся", "Очень редкие"],
        colors: ["#6b5eae", "#00bcd4"],
        tooltip: {
          theme: theme_chart
        },
    }
var chart9_params = {
            chart: {
                type: 'line',
                height: 270,
                toolbar: {
                  autoSelected: "pan",
                  show: true
                }
            },
            colors: ["#5d78ff", "#ccc"],
            fill: {
                type: 'solid',
                opacity: 1,
            },
            stroke: {
                curve: 'smooth',
                width: [4, 2],
                dashArray: [0, 8]
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
            legend: {
                show: true,
                position: 'top',
                offsetX: -15,
                offsetY: 0,
                fontSize: '15px',
                fontFamily: 'pfl',
                floating: false,
                horizontalAlign: 'left',
                labels: {
                  colors: 'var(--color)',
                  useSeriesColors: false,
                }
            },
            series: [{
                name: 'Текущий месяц',
                data: [
                        [new Date("01/01/2014 00:00:00").getTime(), 15],
                        [new Date("01/02/2014 00:00:00").getTime(), 10],
                        [new Date("01/03/2014 00:00:00").getTime(), 12],
                        [new Date("01/04/2014 00:00:00").getTime(), 25],
                        [new Date("01/05/2014 00:00:00").getTime(), 30],
                        [new Date("01/06/2014 00:00:00").getTime(), 24],
                        [new Date("01/07/2014 00:00:00").getTime(), 33],
                        [new Date("01/08/2014 00:00:00").getTime(), 36],
                        [new Date("01/09/2014 00:00:00").getTime(), 24],
                        [new Date("01/10/2014 00:00:00").getTime(), 28],
                        [new Date("01/11/2014 00:00:00").getTime(), 32],
                      ]
            }, {
                name: 'Прошлый месяц',
                data: [
                        [new Date("01/01/2014 00:00:00").getTime(), 12],
                        [new Date("01/02/2014 00:00:00").getTime(), 10],
                        [new Date("01/03/2014 00:00:00").getTime(), 18],
                        [new Date("01/04/2014 00:00:00").getTime(), 5],
                        [new Date("01/05/2014 00:00:00").getTime(), 12],
                        [new Date("01/06/2014 00:00:00").getTime(), 13],
                        [new Date("01/07/2014 00:00:00").getTime(), 25],
                        [new Date("01/08/2014 00:00:00").getTime(), 33],
                        [new Date("01/09/2014 00:00:00").getTime(), 28],
                        [new Date("01/10/2014 00:00:00").getTime(), 19],
                        [new Date("01/11/2014 00:00:00").getTime(), 27],
                      ]
            }],
            yaxis: {
              labels: {
                style: {
                  colors: 'var(--color)',
                  fontSize: '12px'
                }
              }
            },
            xaxis: {
              type: "datetime",

              labels: {
                formatter: function(format) {
                  var data = new Date(format).getDate(),
                      month = new Date(format).getMonth() + 1,
                      fullYear = new Date(format).getFullYear();

                    if(data < 10){
                      data = '0' + data;
                    }
                    if(month < 10){
                      month = '0' + month;
                    }

                    return data + '.' + month + '.' + fullYear;
                },
                style: {
                  colors: 'var(--color)',
                  fontSize: '12px'
                }
              }
            },
            tooltip: {
                theme: theme_chart,
                fixed: {
                    enabled: false
                },
                x: {
                    show: false,
                    enabled: false,
                },
                y: {
                    title: {
                        formatter: function(seriesName) {
                            return 'Отказов'
                        }
                    }
                },
                marker: {
                    show: true
                }
            }
        }
var chart10_params = {
          series: [{
            name: 'За все время',
            data: [500, 420, 120, 203, 160, 31, 42, 56, 3, 160]
        }],
          chart: {
          height: 338,
          type: 'bar',
          events: {
            click: function(chart, w, e) {
              // console.log(chart, w, e)
            }
          }
        },
        colors: ["#6b5eae", "#ffb822",'#fd397a','#5d78ff','#00bcd4','#d32f2f','#0abb87','#ff5722','#689f38','#00acc1'],
        plotOptions: {
          bar: {
            columnWidth: '45%',
            distributed: true
          }
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
        legend: {
          show: false
        },
        yaxis: {
          labels: {
            style: {
              colors: 'var(--color)',
              fontSize: '12px'
            }
          }
        },
        xaxis: {
          categories: [
            'Google','Яндекс','Yahoo!','Поиск@Mail.ru','Bing','Nigma.ru','«Вебальта»','Live Search','MSN','Рамблер'
          ],
          labels: {
            style: {
              colors: 'var(--color)',
              fontSize: '12px'
            }
          }
        },
        tooltip: {
          theme: theme_chart
        },
        };
var chart11_params = {
                  series: [{
                    name: 'За все время',
                    data: [100, 420, 120, 203, 160, 31, 670, 500, 13, 160, 80, 57, 64, 51, 30, 20]
                }],
                  chart: {
                  height: 338,
                  type: 'bar',
                  events: {
                    click: function(chart, w, e) {
                      // console.log(chart, w, e)
                    }
                  }
                },
                colors: ["#6b5eae", "#ffb822",'#fd397a','#5d78ff','#00bcd4','#d32f2f','#0abb87','#4872a3','#689f38','#00acc1','#ff5722'],
                plotOptions: {
                  bar: {
                    columnWidth: '45%',
                    distributed: true
                  }
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
                legend: {
                  show: false
                },
                yaxis: {
                  labels: {
                    style: {
                      colors: 'var(--color)',
                      fontSize: '12px'
                    }
                  }
                },
                xaxis: {
                  categories: [
                    'Facebook',
                    'Twitter',
                    'LinkedIn',
                    'Pinterest',
                    'Google Plus+',
                    'Tumblr',
                    'Instagram',
                    'ВКонтакте',
                    'Flickr',
                    'MySpace',
                    'Meetup',
                    'Tagged',
                    'Ask.fm',
                    'MeetMe',
                    'ClassMates',
                    'Одноклассники'
                  ],
                  labels: {
                    style: {
                      colors: 'var(--color)',
                      fontSize: '12px'
                    }
                  }
                },
                tooltip: {
                  theme: theme_chart
                },
                };
var chart12_params = {
        chart: {
            height: 135,
            type: 'donut',
            sparkline: {
                enabled: true
            }
        },
        plotOptions: {
          pie: {
            expandOnClick: false,
          }
        },
        series: [44, 55, 41, 17, 15],
        legend: {
            show: false,
        },
        tooltip: {
          theme: theme_chart
        },
        labels: ["Сингапур", "Мальдивы", "Сан-Марино", "Ватикан", "Бахрейн"],
        colors: ["#5d78ff", "#0abb87", "#00bcd4", "#ffb822", "#fd397a"]
    }
var chart13_params = {
      chart: {
        type: "area",
        height: 265,
        width: '100%',
        foreColor: "#ccc",
        toolbar: {
          autoSelected: "pan",
          show: true
        }
      },
      colors: ['#5d78ff', '#0abb87'],
      stroke: {
        width: 3
      },
      grid: {
        borderColor: 'var(--border-color)',
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
          position: 'bottom',
          horizontalAlign: 'left',
          verticalAlign: 'middle',
          floating: false,
          fontSize: '15px',
          fontFamily: 'pfl',
          showForSingleSeries: false,
          showForNullSeries: true,
          showForZeroSeries: true,
          offsetX: 0,
          offsetY: 5,

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
          data: [
            [new Date("01/01/2014 00:00:00").getTime(), 420],
            [new Date("01/01/2014 01:00:00").getTime(), 350],
            [new Date("01/01/2014 02:00:00").getTime(), 340],
            [new Date("01/01/2014 03:00:00").getTime(), 297],
            [new Date("01/01/2014 04:00:00").getTime(), 249],
            [new Date("01/01/2014 05:00:00").getTime(), 235],
            [new Date("01/01/2014 06:00:00").getTime(), 220],
            [new Date("01/01/2014 07:00:00").getTime(), 194],
            [new Date("01/01/2014 08:00:00").getTime(), 162],
            [new Date("01/01/2014 09:00:00").getTime(), 152],
            [new Date("01/01/2014 10:00:00").getTime(), 132],
            [new Date("01/01/2014 11:00:00").getTime(), 124],
            [new Date("01/01/2014 12:00:00").getTime(), 103],
            [new Date("01/01/2014 13:00:00").getTime(), 67],
            [new Date("01/01/2014 14:00:00").getTime(), 54],
            [new Date("01/01/2014 15:00:00").getTime(), 40],
            [new Date("01/01/2014 16:00:00").getTime(), 36]
          ]
        },
        {
          name: 'Уникальные пользователи',
          data: [
            [new Date("01/01/2014 00:00:00").getTime(), 360],
            [new Date("01/01/2014 01:00:00").getTime(), 240],
            [new Date("01/01/2014 02:00:00").getTime(), 211],
            [new Date("01/01/2014 03:00:00").getTime(), 230],
            [new Date("01/01/2014 04:00:00").getTime(), 164],
            [new Date("01/01/2014 05:00:00").getTime(), 130],
            [new Date("01/01/2014 06:00:00").getTime(), 115],
            [new Date("01/01/2014 07:00:00").getTime(), 103],
            [new Date("01/01/2014 08:00:00").getTime(), 94],
            [new Date("01/01/2014 09:00:00").getTime(), 68],
            [new Date("01/01/2014 10:00:00").getTime(), 79],
            [new Date("01/01/2014 11:00:00").getTime(), 61],
            [new Date("01/01/2014 12:00:00").getTime(), 76],
            [new Date("01/01/2014 13:00:00").getTime(), 58],
            [new Date("01/01/2014 14:00:00").getTime(), 20],
            [new Date("01/01/2014 15:00:00").getTime(), 11],
            [new Date("01/01/2014 16:00:00").getTime(), 4]
          ]
        }
      ],
      tooltip: {
        theme: theme_chart
      },
      xaxis: {
        type: "datetime",
        labels: {
          format: 'HH:mm',
          style: {
            colors: 'var(--color)',
            fontSize: '12px'
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: 'var(--color)',
            fontSize: '12px'
          }
        },
        min: 0,
        tickAmount: 4
      }
    };
var chart14_params = {
                      series: [{
                        name: 'За все время',
                        data: [100, 420, 120, 203, 160]
                    }],
                      chart: {
                      height: 265,
                      type: 'bar',
                      events: {
                        click: function(chart, w, e) {
                          // console.log(chart, w, e)
                        }
                      }
                    },
                    colors: ["#6b5eae", "#ffb822",'#fd397a','#5d78ff','#00bcd4'],
                    plotOptions: {
                      bar: {
                        columnWidth: '45%',
                        distributed: true
                      }
                    },
                    grid: {
                      borderColor: 'var(--border-color)',
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
                    legend: {
                      show: false
                    },
                    yaxis: {
                      labels: {
                        style: {
                          colors: 'var(--color)',
                          fontSize: '12px'
                        }
                      }
                    },
                    xaxis: {
                      categories: [
                        'Младше 18 лет',
                        '18 - 24 года',
                        '25 - 34 года',
                        '35 - 44 года',
                        '45 и старше',
                      ],
                      labels: {
                        style: {
                          colors: 'var(--color)',
                          fontSize: '12px'
                        }
                      }
                    },
                    tooltip: {
                      theme: theme_chart
                    },
                  };
var chart15_params = {
    series: [{
      name: 'За все время',
      data: [100, 20]
  }],
    chart: {
    height: 265,
    type: 'bar',
    events: {
      click: function(chart, w, e) {
        // console.log(chart, w, e)
      }
    }
  },
  colors: ['#5d78ff',"#ffb822"],
  plotOptions: {
    bar: {
      columnWidth: '45%',
      distributed: true
    }
  },
  grid: {
    borderColor: 'var(--border-color)',
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
  legend: {
    show: false
  },
  yaxis: {
    labels: {
      style: {
        colors: 'var(--color)',
        fontSize: '12px'
      }
    }
  },
  xaxis: {
    categories: [
      'Мужской',
      'Женский'
    ],
    labels: {
      style: {
        colors: 'var(--color)',
        fontSize: '12px'
      }
    }
  },
  tooltip: {
    theme: theme_chart
  },
  };
var chart16_params = {
                    series: [{
                      name: 'За все время',
                      data: [100, 420, 120, 203, 160, 31, 670, 500, 13, 160, 80, 57, 64, 51, 30, 20]
                  }],
                    chart: {
                    height: 338,
                    type: 'bar',
                    events: {
                      click: function(chart, w, e) {
                        // console.log(chart, w, e)
                      }
                    }
                  },
                  colors: ["#6b5eae", "#ffb822",'#fd397a','#5d78ff','#00bcd4','#d32f2f','#0abb87','#4872a3','#689f38','#00acc1','#ff5722'],
                  plotOptions: {
                    bar: {
                      columnWidth: '45%',
                      distributed: true
                    }
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
                  legend: {
                    show: false
                  },
                  yaxis: {
                    labels: {
                      style: {
                        colors: 'var(--color)',
                        fontSize: '12px'
                      }
                    }
                  },
                  xaxis: {
                    categories: [
                      'Facebook',
                      'Twitter',
                      'LinkedIn',
                      'Pinterest',
                      'Google Plus+',
                      'Tumblr',
                      'Instagram',
                      'ВКонтакте',
                      'Flickr',
                      'MySpace',
                      'Meetup',
                      'Tagged',
                      'Ask.fm',
                      'MeetMe',
                      'ClassMates',
                      'Одноклассники'
                    ],
                    labels: {
                      style: {
                        colors: 'var(--color)',
                        fontSize: '12px'
                      }
                    }
                  },
                  tooltip: {
                    theme: theme_chart
                  },
                  };
var chart17_params = {
                        chart: {
                          type: "area",
                          height: 265,
                          width: '100%',
                          foreColor: "#ccc",
                          toolbar: {
                            autoSelected: "pan",
                            show: true
                          }
                        },
                        colors: ['#5d78ff', '#0abb87'],
                        stroke: {
                          width: 3
                        },
                        grid: {
                          borderColor: 'var(--border-color)',
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
                            position: 'bottom',
                            horizontalAlign: 'left',
                            verticalAlign: 'middle',
                            floating: false,
                            fontSize: '15px',
                            fontFamily: 'pfl',
                            showForSingleSeries: false,
                            showForNullSeries: true,
                            showForZeroSeries: true,
                            offsetX: 0,
                            offsetY: 5,

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
                            data: [
                              [new Date("01/01/2014 00:00:00").getTime(), 420],
                              [new Date("01/01/2014 01:00:00").getTime(), 350],
                              [new Date("01/01/2014 02:00:00").getTime(), 340],
                              [new Date("01/01/2014 03:00:00").getTime(), 297],
                              [new Date("01/01/2014 04:00:00").getTime(), 249],
                              [new Date("01/01/2014 05:00:00").getTime(), 235],
                              [new Date("01/01/2014 06:00:00").getTime(), 220],
                              [new Date("01/01/2014 07:00:00").getTime(), 194],
                              [new Date("01/01/2014 08:00:00").getTime(), 162],
                              [new Date("01/01/2014 09:00:00").getTime(), 152],
                              [new Date("01/01/2014 10:00:00").getTime(), 132],
                              [new Date("01/01/2014 11:00:00").getTime(), 124],
                              [new Date("01/01/2014 12:00:00").getTime(), 103],
                              [new Date("01/01/2014 13:00:00").getTime(), 67],
                              [new Date("01/01/2014 14:00:00").getTime(), 54],
                              [new Date("01/01/2014 15:00:00").getTime(), 40],
                              [new Date("01/01/2014 16:00:00").getTime(), 36]
                            ]
                          },
                          {
                            name: 'Уникальные пользователи',
                            data: [
                              [new Date("01/01/2014 00:00:00").getTime(), 360],
                              [new Date("01/01/2014 01:00:00").getTime(), 240],
                              [new Date("01/01/2014 02:00:00").getTime(), 211],
                              [new Date("01/01/2014 03:00:00").getTime(), 230],
                              [new Date("01/01/2014 04:00:00").getTime(), 164],
                              [new Date("01/01/2014 05:00:00").getTime(), 130],
                              [new Date("01/01/2014 06:00:00").getTime(), 115],
                              [new Date("01/01/2014 07:00:00").getTime(), 103],
                              [new Date("01/01/2014 08:00:00").getTime(), 94],
                              [new Date("01/01/2014 09:00:00").getTime(), 68],
                              [new Date("01/01/2014 10:00:00").getTime(), 79],
                              [new Date("01/01/2014 11:00:00").getTime(), 61],
                              [new Date("01/01/2014 12:00:00").getTime(), 76],
                              [new Date("01/01/2014 13:00:00").getTime(), 58],
                              [new Date("01/01/2014 14:00:00").getTime(), 20],
                              [new Date("01/01/2014 15:00:00").getTime(), 11],
                              [new Date("01/01/2014 16:00:00").getTime(), 4]
                            ]
                          }
                        ],
                        tooltip: {
                          theme: theme_chart
                        },
                        xaxis: {
                          type: "datetime",
                          labels: {
                            format: 'HH:mm',
                            style: {
                              colors: 'var(--color)',
                              fontSize: '12px'
                            }
                          }
                        },
                        yaxis: {
                          labels: {
                            style: {
                              colors: 'var(--color)',
                              fontSize: '12px'
                            }
                          },
                          min: 0,
                          tickAmount: 4
                        }
                      };
var chart18_params = {
                                            series: [{
                                              name: 'За все время',
                                              data: [100, 420, 120, 203, 160]
                                          }],
                                            chart: {
                                            height: 265,
                                            type: 'bar',
                                            events: {
                                              click: function(chart, w, e) {
                                                // console.log(chart, w, e)
                                              }
                                            }
                                          },
                                          colors: ["#6b5eae", "#ffb822",'#fd397a','#5d78ff','#00bcd4'],
                                          plotOptions: {
                                            bar: {
                                              columnWidth: '45%',
                                              distributed: true
                                            }
                                          },
                                          grid: {
                                            borderColor: 'var(--border-color)',
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
                                          legend: {
                                            show: false
                                          },
                                          yaxis: {
                                            labels: {
                                              style: {
                                                colors: 'var(--color)',
                                                fontSize: '12px'
                                              }
                                            }
                                          },
                                          xaxis: {
                                            categories: [
                                              'Младше 18 лет',
                                              '18 - 24 года',
                                              '25 - 34 года',
                                              '35 - 44 года',
                                              '45 и старше',
                                            ],
                                            labels: {
                                              style: {
                                                colors: 'var(--color)',
                                                fontSize: '12px'
                                              }
                                            }
                                          },
                                          tooltip: {
                                            theme: theme_chart
                                          },
                                        };
var chart19_params = {
    series: [{
      name: 'За все время',
      data: [100, 20]
  }],
    chart: {
    height: 265,
    type: 'bar',
    events: {
      click: function(chart, w, e) {
        // console.log(chart, w, e)
      }
    }
  },
  colors: ['#5d78ff',"#ffb822"],
  plotOptions: {
    bar: {
      columnWidth: '45%',
      distributed: true
    }
  },
  grid: {
    borderColor: 'var(--border-color)',
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
  legend: {
    show: false
  },
  yaxis: {
    labels: {
      style: {
        colors: 'var(--color)',
        fontSize: '12px'
      }
    }
  },
  xaxis: {
    categories: [
      'Мужской',
      'Женский'
    ],
    labels: {
      style: {
        colors: 'var(--color)',
        fontSize: '12px'
      }
    }
  },
  tooltip: {
    theme: theme_chart
  },
  };
var chart20_params = {
                    series: [{
                      name: 'За все время',
                      data: [100, 420, 120, 203, 160, 31, 670, 500, 13, 160, 80, 57, 64, 51, 30, 20]
                  }],
                    chart: {
                    height: 338,
                    type: 'bar',
                    events: {
                      click: function(chart, w, e) {
                        // console.log(chart, w, e)
                      }
                    }
                  },
                  colors: ["#6b5eae", "#ffb822",'#fd397a','#5d78ff','#00bcd4','#d32f2f','#0abb87','#4872a3','#689f38','#00acc1','#ff5722'],
                  plotOptions: {
                    bar: {
                      columnWidth: '45%',
                      distributed: true
                    }
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
                  legend: {
                    show: false
                  },
                  yaxis: {
                    labels: {
                      style: {
                        colors: 'var(--color)',
                        fontSize: '12px'
                      }
                    }
                  },
                  xaxis: {
                    categories: [
                      'Facebook',
                      'Twitter',
                      'LinkedIn',
                      'Pinterest',
                      'Google Plus+',
                      'Tumblr',
                      'Instagram',
                      'ВКонтакте',
                      'Flickr',
                      'MySpace',
                      'Meetup',
                      'Tagged',
                      'Ask.fm',
                      'MeetMe',
                      'ClassMates',
                      'Одноклассники'
                    ],
                    labels: {
                      style: {
                        colors: 'var(--color)',
                        fontSize: '12px'
                      }
                    }
                  },
                  tooltip: {
                    theme: theme_chart
                  },
                  };
var chart1;
var chart2;
var chart3;
var chart4;
var chart5;
var chart6;
var chart7;
var chart8;
var chart9;
var chart10;
var chart11;
var chart12;
var chart13;
var chart14;
var chart15;
var chart16;
var chart17;
var chart18;
var chart19;
var chart20;


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
  chart4 = new ApexCharts(document.querySelector("#chart4"), chart4_params);
  chart4.render();
  chart5 = new ApexCharts(document.querySelector("#chart5"), chart5_params);
  chart5.render();
  chart6 = new ApexCharts(document.querySelector("#chart6"), chart6_params);
  chart6.render();
  chart7 = new ApexCharts(document.querySelector("#chart7"), chart7_params);
  chart7.render();
  chart8 = new ApexCharts(document.querySelector("#chart8"), chart8_params);
  chart8.render();
  chart9 = new ApexCharts(document.querySelector("#chart9"), chart9_params);
  chart9.render();
  chart10 = new ApexCharts(document.querySelector("#chart10"), chart10_params);
  chart10.render();
  chart11 = new ApexCharts(document.querySelector("#chart11"), chart11_params);
  chart11.render();
  chart12 = new ApexCharts(document.querySelector("#chart12"), chart12_params);
  chart12.render();
  chart13 = new ApexCharts(document.querySelector("#chart13"), chart13_params);
  chart13.render();
  chart14 = new ApexCharts(document.querySelector("#chart14"), chart14_params);
  chart14.render();
  chart15 = new ApexCharts(document.querySelector("#chart15"), chart15_params);
  chart15.render();
  chart16 = new ApexCharts(document.querySelector("#chart16"), chart16_params);
  chart16.render();
  chart17 = new ApexCharts(document.querySelector("#chart17"), chart17_params);
  chart17.render();
  chart18 = new ApexCharts(document.querySelector("#chart18"), chart18_params);
  chart18.render();
  chart19 = new ApexCharts(document.querySelector("#chart19"), chart19_params);
  chart19.render();
  chart20 = new ApexCharts(document.querySelector("#chart20"), chart20_params);
  chart20.render();


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

function updateChartsNew(a) {

  animateCharts = false;

  if(a == undefined){
    a = theme_chart;
  }
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
    chart4.destroy();
    chart5.destroy();
    chart6.destroy();
    chart7.destroy();
    chart8.destroy();
    chart9.destroy();
    chart10.destroy();
    chart11.destroy();
    chart12.destroy();
    chart13.destroy();
    chart14.destroy();
    chart15.destroy();
    chart16.destroy();
    chart17.destroy();
    chart18.destroy();
    chart19.destroy();
    chart20.destroy();

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
  chart4 = new ApexCharts(document.querySelector("#chart4"), chart4_params);
  chart4.render();
  chart5 = new ApexCharts(document.querySelector("#chart5"), chart5_params);
  chart5.render();
  chart6 = new ApexCharts(document.querySelector("#chart6"), chart6_params);
  chart6.render();
  chart7 = new ApexCharts(document.querySelector("#chart7"), chart7_params);
  chart7.render();
  chart8 = new ApexCharts(document.querySelector("#chart8"), chart8_params);
  chart8.render();
  chart9 = new ApexCharts(document.querySelector("#chart9"), chart9_params);
  chart9.render();
  chart10 = new ApexCharts(document.querySelector("#chart10"), chart10_params);
  chart10.render();
  chart11 = new ApexCharts(document.querySelector("#chart11"), chart11_params);
  chart11.render();
  chart12 = new ApexCharts(document.querySelector("#chart12"), chart12_params);
  chart12.render();
  chart13 = new ApexCharts(document.querySelector("#chart13"), chart13_params);
  chart13.render();
  chart14 = new ApexCharts(document.querySelector("#chart14"), chart14_params);
  chart14.render();
  chart15 = new ApexCharts(document.querySelector("#chart15"), chart15_params);
  chart15.render();
  chart16 = new ApexCharts(document.querySelector("#chart16"), chart16_params);
  chart16.render();
  chart17 = new ApexCharts(document.querySelector("#chart17"), chart17_params);
  chart17.render();
  chart18 = new ApexCharts(document.querySelector("#chart18"), chart18_params);
  chart18.render();
  chart19 = new ApexCharts(document.querySelector("#chart19"), chart19_params);
  chart19.render();
  chart20 = new ApexCharts(document.querySelector("#chart20"), chart20_params);
  chart20.render();

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
