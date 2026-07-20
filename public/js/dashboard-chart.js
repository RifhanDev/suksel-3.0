var today = new Date();
const transactionsValueChart = echarts.init(document.getElementById('chart_transactions_value'), 'shine');
const transactionsChart = echarts.init(document.getElementById('chart_transactions'), 'shine');
const tendersChart = echarts.init(document.getElementById('chart_tenders'), 'shine');
const usersChart = echarts.init(document.getElementById('chart_users'), 'shine');
const vendorsChart = echarts.init(document.getElementById('chart_vendors'), 'shine');

/* chart setting */
const labelRotate = 60;
var show_label_top = false;

$('input[name="year_summary"], input[name="year_summary"]').datepicker({
    dateFormat: 'yyyy',
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
}).datepicker("setDate", new Date(today.getFullYear(), today.getMonth(), today.getDate()));

$('input[name="year_start"], input[name="year_quarter"]').datepicker({
    dateFormat: 'yyyy',
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
}).datepicker("setDate", new Date(today.getFullYear(), today.getMonth(), today.getDate()));

$('input[name="monthly_start"]').datepicker({
    format: 'M yyyy',
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    startView: "months",
    minViewMode: "months"
}).datepicker("setDate", new Date(today.getFullYear(), today.getMonth(), today.getDate()));

function exportPdf(chart_id, name) {
    let chart = document.getElementById(chart_id);
    html2canvas(chart).then((canvas) => {
        let imgData = canvas.toDataURL('image/png');
        const doc = new jspdf.jsPDF('p', 'mm', 'a4');
        let imgHeight = canvas.height * 208 / canvas.width;

        doc.addImage(imgData, 'PNG', 0, 0, 208, imgHeight)
        doc.save(name + '.pdf');
    });
}

function transactionValueChart(data) {
    let x_name = data['x'];
    let y_name = data['y'];
    let title = data['title'];
    delete data.x;
    delete data.y;
    delete data.title;
    let type = Object.keys(data);
    let xaxis = Object.values(data);

    var seriesData = [];
    Object.keys(data).forEach(key => {
        seriesData.push({
            name: key,
            type: 'bar',
            smooth: true,
            label: {
                normal: {
                    show: show_label_top,
                    position: 'top'
                }
            },
            itemStyle: {
                normal: {
                    areaStyle: {
                        type: 'default'
                    }
                }
            },
            data: data[key].value
        });
    });

    seriesTransactionValue = seriesData;

    var option = {
        title: {
            text: 'Carta Nilai Transaksi ' + title,
            left: 'center',
        },
        legend: {
            bottom: 0,
            data: type
        },
        tooltip: {
            trigger: 'axis'
        },
        toolbox: {
            show: true,
            feature: {
                mark: { show: true },
                dataZoom: { show: true },
                dataView: {
                    show: true,
                    title: 'Lihat Data',
                    lang: ['Lihat Data', 'Tutup', 'Refresh', 'Excel'],
                    optionToContent: function (opt) {
                        var axisData = opt.xAxis[0].data;
                        var series = opt.series;
                        var table = '<table id="excel_'+title.replace(/ /g,"_")+'" style="width:100%;text-align:center"><thead><tr>'
                            + '<th style="text-align:center">' + opt.xAxis[0].name + '</th>'
                            + '<th style="text-align:center">' + series[0].name + '</th>'
                            + '<th style="text-align:center">' + series[1].name + '</th>'
                            + '</tr></thead><tbody>';
                        for (var i = 0, l = axisData.length; i < l; i++) {
                            table += '<tr>'
                                + '<td>' + axisData[i] + '</td>'
                                + '<td>' + new Intl.NumberFormat().format(series[0].data[i]) + '</td>'
                                + '<td>' + new Intl.NumberFormat().format(series[1].data[i]) + '</td>'
                                + '</tr>';
                        }
                        table += '</tbody></table>';
                        return table;
                    }
                },
                magicType: {
                    show: true,
                    title: {
                        bar: 'Bar',
                        line: 'Line',
                        stack: 'Stack',
                        tiled: 'Tiled'
                    },
                    type: ['bar', 'line', 'stack', 'tiled']
                },
                restore: {
                    show: true,
                    title: "Restore"
                },
                saveAsImage: {
                    show: true,
                    title: "Simpan Gambar"
                },
                myTool1: {
                    show: true,
                    title: 'Simpan PDF',
                    icon: 'path://M160.381,282.225c0-14.832-10.299-23.684-28.474-23.684c-7.414,0-12.437,0.715-15.071,1.432V307.6c3.114,0.707,6.942,0.949,12.192,0.949C148.419,308.549,160.381,298.74,160.381,282.225zM272.875,259.019c-8.145,0-13.397,0.717-16.519,1.435v105.523c3.116,0.729,8.142,0.729,12.69,0.729c33.017,0.231,54.554-17.946,54.554-56.474C323.842,276.719,304.215,259.019,272.875,259.019zM488.426,197.019H475.2v-63.816c0-0.398-0.063-0.799-0.116-1.202c-0.021-2.534-0.827-5.023-2.562-6.995L366.325,3.694c-0.032-0.031-0.063-0.042-0.085-0.076c-0.633-0.707-1.371-1.295-2.151-1.804c-0.231-0.155-0.464-0.285-0.706-0.419c-0.676-0.369-1.393-0.675-2.131-0.896c-0.2-0.056-0.38-0.138-0.58-0.19C359.87,0.119,359.037,0,358.193,0H97.2c-11.918,0-21.6,9.693-21.6,21.601v175.413H62.377c-17.049,0-30.873,13.818-30.873,30.873v160.545c0,17.043,13.824,30.87,30.873,30.87h13.224V529.2c0,11.907,9.682,21.601,21.6,21.601h356.4c11.907,0,21.6-9.693,21.6-21.601V419.302h13.226c17.044,0,30.871-13.827,30.871-30.87v-160.54C519.297,210.838,505.47,197.019,488.426,197.019z M97.2,21.605h250.193v110.513c0,5.967,4.841,10.8,10.8,10.8h95.407v54.108H97.2V21.605z M362.359,309.023c0,30.876-11.243,52.165-26.82,65.333c-16.971,14.117-42.82,20.814-74.396,20.814c-18.9,0-32.297-1.197-41.401-2.389V234.365c13.399-2.149,30.878-3.346,49.304-3.346c30.612,0,50.478,5.508,66.039,17.226C351.828,260.69,362.359,280.547,362.359,309.023z M80.7,393.499V234.365c11.241-1.904,27.042-3.346,49.296-3.346c22.491,0,38.527,4.308,49.291,12.928c10.292,8.131,17.215,21.534,17.215,37.328c0,15.799-5.25,29.198-14.829,38.285c-12.442,11.728-30.865,16.996-52.407,16.996c-4.778,0-9.1-0.243-12.435-0.723v57.67H80.7V393.499z M453.601,523.353H97.2V419.302h356.4V523.353z M484.898,262.127h-61.989v36.851h57.913v29.674h-57.913v64.848h-36.593V232.216h98.582V262.127z',
                    onclick: () => this.exportPdf('chart_tenders', 'Carta Nilai Transaksi')
                },
                myTool2: {
                    show: true,
                    title: 'Papar Data',
                    icon: 'path://M15 12c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z',
                    onclick: () => {
                        show_label_top = !show_label_top;
                        seriesTransactionValue.forEach(element => {
                            element.label.normal.show = show_label_top;
                        });
                        transactionsValueChart.setOption({
                            series: seriesTransactionValue
                        });
                    }
                }
            }
        },
        calculable: true,
        xAxis: {
            type: 'category',
            name: x_name,
            data: xaxis[0].data,
            axisLabel: {
                interval: 0,
                rotate: labelRotate
            }
        },
        yAxis: {
            name: y_name,
            type: 'value'
        },
        series: seriesData
    };

    transactionsValueChart.setOption(option);
}

function transactionChart(data) {

    let x_name = data['x'];
    let y_name = data['y'];
    let title = data['title'];
    delete data.x;
    delete data.y;
    delete data.title;
    let type = Object.keys(data);
    let xaxis = Object.values(data);

    var seriesData = [];
    Object.keys(data).forEach(key => {
        seriesData.push({
            name: key,
            type: 'bar',
            smooth: true,
            label: {
                normal: {
                    show: show_label_top,
                    position: 'top'
                }
            },
            itemStyle: {
                normal: {
                    areaStyle: {
                        type: 'default'
                    }
                }
            },
            data: data[key].value
        });
    });

    seriesTransaction = seriesData;

    var option = {
        title: {
            text: 'Carta Jenis Transaksi ' + title,
            left: 'center',
        },
        legend: {
            bottom: 0,
            data: type
        },
        tooltip: {
            trigger: 'axis'
        },
        toolbox: {
            show: true,
            feature: {
                mark: { show: true },
                dataZoom: { show: true },
                dataView: {
                    show: true,
                    title: 'Lihat Data',
                    lang: ['Lihat Data', 'Tutup', 'Refresh', 'Excel'],
                    optionToContent: function (opt) {
                        var axisData = opt.xAxis[0].data;
                        var series = opt.series;
                        var table = '<table id="excel_'+title.replace(/ /g,"_")+'" style="width:100%;text-align:center"><thead><tr>'
                            + '<th style="text-align:center">' + opt.xAxis[0].name + '</th>'
                            + '<th style="text-align:center">' + series[0].name + '</th>'
                            + '<th style="text-align:center">' + series[1].name + '</th>'
                            + '</tr></thead><tbody>';
                        for (var i = 0, l = axisData.length; i < l; i++) {
                            table += '<tr>'
                                + '<td>' + axisData[i] + '</td>'
                                + '<td>' + new Intl.NumberFormat().format(series[0].data[i]) + '</td>'
                                + '<td>' + new Intl.NumberFormat().format(series[1].data[i]) + '</td>'
                                + '</tr>';
                        }
                        table += '</tbody></table>';
                        return table;
                    }
                },
                magicType: {
                    show: true,
                    title: {
                        bar: 'Bar',
                        line: 'Line',
                        stack: 'Stack',
                        tiled: 'Tiled'
                    },
                    type: ['bar', 'line', 'stack', 'tiled']
                },
                restore: {
                    show: true,
                    title: "Restore"
                },
                saveAsImage: {
                    show: true,
                    title: "Simpan Gambar"
                },
                myTool1: {
                    show: true,
                    title: 'Simpan PDF',
                    icon: 'path://M160.381,282.225c0-14.832-10.299-23.684-28.474-23.684c-7.414,0-12.437,0.715-15.071,1.432V307.6c3.114,0.707,6.942,0.949,12.192,0.949C148.419,308.549,160.381,298.74,160.381,282.225zM272.875,259.019c-8.145,0-13.397,0.717-16.519,1.435v105.523c3.116,0.729,8.142,0.729,12.69,0.729c33.017,0.231,54.554-17.946,54.554-56.474C323.842,276.719,304.215,259.019,272.875,259.019zM488.426,197.019H475.2v-63.816c0-0.398-0.063-0.799-0.116-1.202c-0.021-2.534-0.827-5.023-2.562-6.995L366.325,3.694c-0.032-0.031-0.063-0.042-0.085-0.076c-0.633-0.707-1.371-1.295-2.151-1.804c-0.231-0.155-0.464-0.285-0.706-0.419c-0.676-0.369-1.393-0.675-2.131-0.896c-0.2-0.056-0.38-0.138-0.58-0.19C359.87,0.119,359.037,0,358.193,0H97.2c-11.918,0-21.6,9.693-21.6,21.601v175.413H62.377c-17.049,0-30.873,13.818-30.873,30.873v160.545c0,17.043,13.824,30.87,30.873,30.87h13.224V529.2c0,11.907,9.682,21.601,21.6,21.601h356.4c11.907,0,21.6-9.693,21.6-21.601V419.302h13.226c17.044,0,30.871-13.827,30.871-30.87v-160.54C519.297,210.838,505.47,197.019,488.426,197.019z M97.2,21.605h250.193v110.513c0,5.967,4.841,10.8,10.8,10.8h95.407v54.108H97.2V21.605z M362.359,309.023c0,30.876-11.243,52.165-26.82,65.333c-16.971,14.117-42.82,20.814-74.396,20.814c-18.9,0-32.297-1.197-41.401-2.389V234.365c13.399-2.149,30.878-3.346,49.304-3.346c30.612,0,50.478,5.508,66.039,17.226C351.828,260.69,362.359,280.547,362.359,309.023z M80.7,393.499V234.365c11.241-1.904,27.042-3.346,49.296-3.346c22.491,0,38.527,4.308,49.291,12.928c10.292,8.131,17.215,21.534,17.215,37.328c0,15.799-5.25,29.198-14.829,38.285c-12.442,11.728-30.865,16.996-52.407,16.996c-4.778,0-9.1-0.243-12.435-0.723v57.67H80.7V393.499z M453.601,523.353H97.2V419.302h356.4V523.353z M484.898,262.127h-61.989v36.851h57.913v29.674h-57.913v64.848h-36.593V232.216h98.582V262.127z',
                    onclick: () => this.exportPdf('chart_tenders', 'Carta Jenis Transaksi')
                },
                myTool2: {
                    show: true,
                    title: 'Papar Data',
                    icon: 'path://M15 12c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z',
                    onclick: () => {
                        show_label_top = !show_label_top;
                        seriesTransaction.forEach(element => {
                            element.label.normal.show = show_label_top;
                        });
                        transactionsChart.setOption({
                            series: seriesTransaction
                        });
                    }
                }
            }
        },
        calculable: true,
        xAxis: {
            type: 'category',
            name: x_name,
            data: xaxis[0].data,
            axisLabel: {
                interval: 0,
                rotate: labelRotate
            }
        },
        yAxis: {
            name: y_name,
            type: 'value'
        },
        series: seriesData
    };

    transactionsChart.setOption(option);
}

function tenderChart(data) {
    let x_name = data['x'];
    let y_name = data['y'];
    let title = data['title'];
    delete data.x;
    delete data.y;
    delete data.title;
    let type = Object.keys(data);
    let xaxis = Object.values(data);

    var seriesData = [];
    Object.keys(data).forEach(key => {
        seriesData.push({
            name: key,
            type: 'bar',
            smooth: true,
            label: {
                normal: {
                    show: show_label_top,
                    position: 'top'
                }
            },
            itemStyle: {
                normal: {
                    areaStyle: {
                        type: 'default'
                    }
                }
            },
            data: data[key].value
        });
    });

    seriesTender = seriesData;

    var option = {
        title: {
            text: 'Carta Tender & Sebutharga ' + title,
            left: 'center',
        },
        legend: {
            bottom: 0,
            data: type
        },
        tooltip: {
            trigger: 'axis'
        },
        toolbox: {
            show: true,
            feature: {
                mark: { show: true },
                dataZoom: { show: true },
                dataView: {
                    show: true,
                    title: 'Lihat Data',
                    lang: ['Lihat Data', 'Tutup', 'Refresh', 'Excel'],
                    optionToContent: function (opt) {
                        var axisData = opt.xAxis[0].data;
                        var series = opt.series;
                        var table = '<table id="excel_'+title.replace(/ /g,"_")+'" style="width:100%;text-align:center"><thead><tr>'
                            + '<th style="text-align:center">' + opt.xAxis[0].name + '</th>'
                            + '<th style="text-align:center">' + series[0].name + '</th>'
                            + '<th style="text-align:center">' + series[1].name + '</th>'
                            + '</tr></thead><tbody>';
                        for (var i = 0, l = axisData.length; i < l; i++) {
                            table += '<tr>'
                                + '<td>' + axisData[i] + '</td>'
                                + '<td>' + new Intl.NumberFormat().format(series[0].data[i]) + '</td>'
                                + '<td>' + new Intl.NumberFormat().format(series[1].data[i]) + '</td>'
                                + '</tr>';
                        }
                        table += '</tbody></table>';
                        return table;
                    }
                },
                magicType: {
                    show: true,
                    title: {
                        bar: 'Bar',
                        line: 'Line',
                        stack: 'Stack',
                        tiled: 'Tiled'
                    },
                    type: ['bar', 'line', 'stack', 'tiled']
                },
                restore: {
                    show: true,
                    title: "Restore"
                },
                saveAsImage: {
                    show: true,
                    title: "Simpan Gambar"
                },
                myTool1: {
                    show: true,
                    title: 'Simpan PDF',
                    icon: 'path://M160.381,282.225c0-14.832-10.299-23.684-28.474-23.684c-7.414,0-12.437,0.715-15.071,1.432V307.6c3.114,0.707,6.942,0.949,12.192,0.949C148.419,308.549,160.381,298.74,160.381,282.225zM272.875,259.019c-8.145,0-13.397,0.717-16.519,1.435v105.523c3.116,0.729,8.142,0.729,12.69,0.729c33.017,0.231,54.554-17.946,54.554-56.474C323.842,276.719,304.215,259.019,272.875,259.019zM488.426,197.019H475.2v-63.816c0-0.398-0.063-0.799-0.116-1.202c-0.021-2.534-0.827-5.023-2.562-6.995L366.325,3.694c-0.032-0.031-0.063-0.042-0.085-0.076c-0.633-0.707-1.371-1.295-2.151-1.804c-0.231-0.155-0.464-0.285-0.706-0.419c-0.676-0.369-1.393-0.675-2.131-0.896c-0.2-0.056-0.38-0.138-0.58-0.19C359.87,0.119,359.037,0,358.193,0H97.2c-11.918,0-21.6,9.693-21.6,21.601v175.413H62.377c-17.049,0-30.873,13.818-30.873,30.873v160.545c0,17.043,13.824,30.87,30.873,30.87h13.224V529.2c0,11.907,9.682,21.601,21.6,21.601h356.4c11.907,0,21.6-9.693,21.6-21.601V419.302h13.226c17.044,0,30.871-13.827,30.871-30.87v-160.54C519.297,210.838,505.47,197.019,488.426,197.019z M97.2,21.605h250.193v110.513c0,5.967,4.841,10.8,10.8,10.8h95.407v54.108H97.2V21.605z M362.359,309.023c0,30.876-11.243,52.165-26.82,65.333c-16.971,14.117-42.82,20.814-74.396,20.814c-18.9,0-32.297-1.197-41.401-2.389V234.365c13.399-2.149,30.878-3.346,49.304-3.346c30.612,0,50.478,5.508,66.039,17.226C351.828,260.69,362.359,280.547,362.359,309.023z M80.7,393.499V234.365c11.241-1.904,27.042-3.346,49.296-3.346c22.491,0,38.527,4.308,49.291,12.928c10.292,8.131,17.215,21.534,17.215,37.328c0,15.799-5.25,29.198-14.829,38.285c-12.442,11.728-30.865,16.996-52.407,16.996c-4.778,0-9.1-0.243-12.435-0.723v57.67H80.7V393.499z M453.601,523.353H97.2V419.302h356.4V523.353z M484.898,262.127h-61.989v36.851h57.913v29.674h-57.913v64.848h-36.593V232.216h98.582V262.127z',
                    onclick: () => this.exportPdf('chart_tenders', 'Carta Tender & Sebutharga')
                },
                myTool2: {
                    show: true,
                    title: 'Papar Data',
                    icon: 'path://M15 12c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z',
                    onclick: () => {
                        show_label_top = !show_label_top;
                        seriesTender.forEach(element => {
                            element.label.normal.show = show_label_top;
                        });
                        tendersChart.setOption({
                            series: seriesTender
                        });
                    }
                }
            }
        },
        calculable: true,
        xAxis: {
            type: 'category',
            name: x_name,
            data: xaxis[0].data,
            axisLabel: {
                interval: 0,
                rotate: labelRotate
            }
        },
        yAxis: {
            name: y_name,
            type: 'value'
        },
        series: seriesData
    };

    tendersChart.setOption(option);
}

function userChart(active, unactive) {

    // Specify the configuration items and data for the chart
    var option = {
        title: {
            text: 'Carta Pai Pengguna',
            left: 'center',
        },
        legend: {
            orient: 'vertical',
            right: 0,
            bottom: 0,
            data: ['Aktif', 'Tidak Aktif'],
            formatter: (name) => {
                var series = usersChart.getOption().series[0];
                var value = new Intl.NumberFormat().format(series.data.filter(row => row.name === name)[0].value);
                return name + ' (' + value + ')';
            },
        },
        tooltip: {
            trigger: 'item',
            formatter: function (params) {
                var number = new Intl.NumberFormat().format(params.value);
                return `
                 <b>${params.seriesName}</b></br>
                 ${params.data.name} : ${number} (${params.percent}%) <br />`
            }
        },
        toolbox: {
            show: true,
            feature: {
                magicType: {
                    show: true,
                    type: ['pie']
                },
                restore: {
                    show: true,
                    title: "Restore"
                },
                saveAsImage: {
                    show: true,
                    title: "Save Image"
                }
            }
        },
        series: [
            {
                name: 'Status',
                type: 'pie',
                itemStyle: {
                    normal: {
                        label: {
                            show: true, position: 'inner',
                            formatter: function (params) {
                                return params.percent + '%\n'
                            },
                        },
                        labelLine: {
                            show: true
                        }
                    }
                },
                data: [
                    {
                        value: active,
                        name: 'Aktif'
                    },
                    {
                        value: unactive,
                        name: 'Tidak Aktif'
                    }
                ],
            }
        ]
    };

    usersChart.setOption(option);
}

function vendorChart(active, unactive, unregistered) {

    // Specify the configuration items and data for the chart
    var option = {
        title: {
            text: 'Carta Pai Syarikat',
            left: 'center',
        },
        legend: {
            orient: 'vertical',
            right: 0,
            bottom: 0,
            data: ['Aktif', 'Tidak Aktif', 'Belum Daftar'],
            formatter: (name) => {
                var series = vendorsChart.getOption().series[0];
                var value = new Intl.NumberFormat().format(series.data.filter(row => row.name === name)[0].value);
                return name + ' (' + value + ')';
            },
        },
        tooltip: {
            trigger: 'item',
            formatter: function (params) {
                var number = new Intl.NumberFormat().format(params.value);
                return `
                 <b>${params.seriesName}</b></br>
                 ${params.data.name} : ${number} (${params.percent}%) <br />`
            }
        },
        toolbox: {
            show: true,
            feature: {
                magicType: {
                    show: true,
                    type: ['pie']
                },
                restore: {
                    show: true,
                    title: "Restore"
                },
                saveAsImage: {
                    show: true,
                    title: "Save Image"
                }
            }
        },
        series: [
            {
                name: 'Status',
                type: 'pie',
                label: {
                    show: true,
                    position: 'inner',
                    formatter: function (params) {
                        return params.percent + '%\n'
                    },
                },
                labelLine: {
                    show: false
                },
                data: [
                    {
                        value: active,
                        name: 'Aktif'
                    },
                    {
                        value: unactive,
                        name: 'Tidak Aktif'
                    },
                    {
                        value: unregistered,
                        name: 'Belum Daftar'
                    }
                ],
            }
        ]
    };

    vendorsChart.setOption(option);
}