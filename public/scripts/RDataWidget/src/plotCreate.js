define(["dojox/charting/Chart",
        'dojo/_base/array',
        "dojox/timing",
        'dstore/charting/StoreSeries',
        "dojox/charting/themes/MiamiNice",
        "dojox/charting/axis2d/Default",
        "dojox/charting/plot2d/Columns",
        "dojox/charting/plot2d/Lines",
        'dojo/_base/declare',
        "dojox/charting/plot2d/common",
        "dojox/charting/action2d/Tooltip",
        "dojox/charting/action2d/Highlight",
        "dojox/charting/plot2d/Markers",
        "dojo/domReady!"],
    function (Chart,
              array,
              timing,
              StoreSeries,
              MiamiNice,
              Default,
              Columns,
              Lines,
              declare,
              common,
              Tooltip,
              Highlight) {
        return declare(null, {
            _chart: null,
            _data: null,
            _store: null,
            isError: false,
            wordInAxis: 3,
            constructor: function (name, store, settings) {
                try {
                    var self = this;

                    this._chart = new Chart(name, {
                        title: settings.title,
                        titlePos: "top",
                        titleGap: 25,
                        titleFont: "normal normal normal 15pt Arial",
                        titleFontColor: "orange"
                    });

                    this._store = store;

                    this._chart.addPlot("columnsPlot", {
                        type: Columns,
                        lines: true,
                        areas: true,
                        markers: true,
                        //tension: "S",
                        minBarSize: 10,
                        maxBarSize: 500,
                        gap: 0.1
                    });

                    this._chart.setTheme(MiamiNice);

                    this._chart.addAxis("x", {
                        majorTickStep: 1, minorTicks: false, title: settings.axis.xAxis, titleOrientation: "away",
                        labelFunc: function (index) {
                            var column = "NULL";
                            self._store.fetchRange({start: index - 1, end: index}).then(function (item) {
                                column = item[0].x
                            });
                            
                            return column;
                        },
                        font: "normal normal normal 10pt Arial"
                    });

                    var yAxisLabel = "";

                    var words = settings.axis.yAxis.split(" ");

                    for(var i =0; i < words.length; ++i){
                        yAxisLabel += " " + words[i] ;
                        if(i%3 == 0){
                            yAxisLabel += "\n";
                        }
                    }

                    this._chart.addAxis("y", {
                        vertical: true,
                        min: 0,
                        title: yAxisLabel,
                        titleFont: "normal normal normal 9pt Arial"
                    });

                    this._chart.addSeries("Series 1", new StoreSeries(this._store,
                        function (item) {
                            //return {x: item.x, y: item.y};
                            return item.y
                        }), {
                        plot: "columnsPlot",
                        stroke: {
                            color: "red",
                            width: 1
                        },
                        fill: [255, 128, 0, 0.7]
                    });

                    new Tooltip(this._chart, "columnsPlot", {
                        text: function (chartItem) {
                            return "id: " + chartItem.run.source.objects[chartItem.index].id + '<br>' + settings.axis.yAxis + ": " + chartItem.y + '<br>' + settings.axis.xAxis + ": " + chartItem.run.source.objects[chartItem.index].x;
                        }
                    });
                    new Highlight(this._chart, "columnsPlot");
                } catch (e) {
                    this.isError = true
                }
            },
            render: function () {
                this._chart.render();
            }
        });

    });