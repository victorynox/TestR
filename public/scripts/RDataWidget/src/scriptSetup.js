define([
    'dojo/_base/declare',
    'dojo/_base/array',
    'dojo/_base/lang',
    "dijit/ConfirmDialog",
    "dijit/form/TextBox",
    "dijit/form/NumberTextBox",
    "dojo/dom-construct",
    "dojo/dom",
    "dojo/on",
    "dojo/_base/event",
    "dojo/query",
    'dstore/Memory',
    'dstore/Trackable',
    "dijit/form/Form",
    "dijit/form/DateTextBox",
    "dijit/form/Button",
    "dijit/form/Select",
    "./plotCreate",
    "./formCreate",
    "./dgredCreate",
    'dstore/Filter',
    'dstore/Store',
    'dstore/Rest',
    "dojox/layout/TableContainer",
    "dojo/Deferred",
    "dojo/dom-style",
    "dojo/dom-class",
    "Rscript/Smart/TableControlPanel/TableControlPanelFactory",

    "Rscript/Smart/Composite/widget/Composite",
    'Rscript/Smart/FilterControlPanel/widget/FilterControlPanel',
    'Rscript/Smart/FilteredGrid/widget/FilteredGrid',
    'Rscript/Smart/TableWithConfiguration/widget/TableWithConfiguration',
    'Rscript/Smart/DataPreviewControlPanel/widget/DataPreviewControlPanel',
    'Rscript/Smart/DataPreviewControlPanel/util/GridWithChartFactory',
    'Rscript/Smart/Chart/widget/Chart',
    "dojox/charting/plot2d/Columns",


    "Rscript/Smart/extensions/Store/StoreRqlFilter",
    "dojox/widget/TitleGroup",
    "dijit/TitlePane",


], function (declare,
             array,
             lang,
             confirmDialog,
             TextBox,
             NumberTextBox,
             domConstruct,
             dom,
             on,
             event,
             query,
             Memory,
             Trackable,
             Form,
             DateTextBox,
             Button,
             Select,
             plotCreate,
             formCreate,
             dgredCreate,
             Filter,
             Store,
             Rest,
             TableContainer,
             Deferred,
             domStyle,
             domClass,
             TableControlPanelFactory,
             Composite,
             FilterControlPanel,
             FilteredGrid,
             TableWithConfiguration,
             DataPreviewControlPanel,
             GridWithChartFactory,
             Chart,
             Columns,
             StoreRqlFilter,
             TitleGroup,
             TitlePane) {
    return declare(null, {

        __scriptsList: {},
        __scriptSelectionForm: null,
        __scriptConfigDialog: null,
        __scriptConfigDialogForm: null,
        __store: null,
        __cashStore: null,
        __plot: null,
        __dgrid: null,
        __name: null,
        __filter: {},
        __filterDialog: null,
        __storeConfig: {
            "target": "/rest/",
            "useRangeHeaders": true,
            'headers': {
                'Accept': 'application/json',
            }
        },
        __chart: {
            width: "100%",
            height: "500px",
            margin: "5px auto 0px auto"
        },
        firstTable: null,
        factoryTable: null,
        tablePublishTimeWithFactory: null,
        composite: null,

        constructor: function (scriptsList, store, chart) {
            if (scriptsList != null && store instanceof Store) {

                domStyle.set("alertBlock", {
                    display: "none"
                });

                this.__scriptsList = scriptsList;

                this.__store = store;

                this.__chart.margin = chart.margin != null ? chart.margin : this.__chart.margin;
                this.__chart.width = chart.width != null ? chart.width : this.__chart.width;
                this.__chart.height = chart.height != null ? chart.height : this.__chart.height;


                var self = this;
                var listForSelect = [];
                array.forEach(this.__scriptsList.names, function (name) {
                    listForSelect.push({label: self.__scriptsList['scripts'][name].reportName, value: name})
                });

                this.__createSelectionForm(listForSelect);

                on(document.getElementById("closeAlert"), "click", function () {
                    domStyle.set("alertBlock", {
                        display: "none"
                    });
                });

            }
        },

        __createSelectionForm: function (listForSelect) {
            this.__scriptSelectionForm = new Form({
                id: 'scriptSelectionForm',
                doLayout: true
            });

            new Select({
                label: "select Script",
                name: "select",
                options: listForSelect

            }).placeAt(this.__scriptSelectionForm.containerNode);

            var button = new Button({
                label: "Yes",
                name: "submit",
                id: 'ButtonSelectSubmit'
            }).placeAt(this.__scriptSelectionForm.containerNode);

            var self = this;

            on(button, "click", function () {
                var f = query("#scriptSelectionForm")[0];
                self.__name = f['select'].value;
                dom.byId("reportName").innerHTML = self.__scriptsList['scripts'][self.__name].reportName;
                self.__createConfigDialog();
            });

            var divForm = dom.byId("selectScript");

            this.__scriptSelectionForm.placeAt(divForm);
        },

        __render: function (store) {

            var self = this;
            if (!(store instanceof Memory)) {
                store = self.__cashStore;
            }

            var compositeDiv = dom.byId("grid");
            //var gridDiv = dom.byId("grid");

            compositeDiv.innerHTML = "";
            //gridDiv.innerHTML = "";

            if (self.composite) {
                self.composite.destroyRecursive();
                if(dom.byId(self.composite.domNode)){
                    domConstruct.destroy(dom.byId(self.composite.domNode));
                }
                self.composite = null;
            }

            if (self.__scriptsList["scripts"][self.__name]["return"]['type'] === "plot") {
                self.composite = GridWithChartFactory(self.__name, store, true);
            } else {
                self.composite = GridWithChartFactory(self.__name, store, false);
            }
            self.composite.placeAt(dom.byId(compositeDiv));
            self.composite.startup();
            return self.composite !== null;
        },

        __renderPlotWithGrid: function (filter, store) {
            var self = this;

            /*if (!(store instanceof Memory)) {
             store = self.__cashStore;
             }

             var chartDiv = dom.byId("simplechart");
             //var gridDiv = dom.byId("grid");

             chartDiv.innerHTML = "";
             //gridDiv.innerHTML = "";

             var group = new TitleGroup();
             dom.byId("grid").appendChild(group.domNode);

             var setting = {
             title: self.__scriptsList.scripts[self.__name]['reportName'],
             return: {
             fieldNames: self.__scriptsList["scripts"][self.__name]["return"]['fieldNames'],
             fieldLabel: self.__scriptsList["scripts"][self.__name]["return"]['fieldLabel']
             }
             };

             if (self.__scriptsList["scripts"][self.__name]["return"]['type'] === "plot") {
             domStyle.set(chartDiv, {

             width: self.__chart.width,
             height: self.__chart.height,
             margin: self.__chart.margin
             });
             setting.axis = self.__scriptsList.scripts[self.__name]['axis'];
             self.plot = new plotCreate('simplechart', store.filter(filter), setting);
             if (self.plot.isError) {
             return false;
             }
             self.plot.render();
             } else {
             domStyle.set(chartDiv, {

             width: 0,
             height: 0,
             margin: 0
             });
             }

             if (self.__dgrid) {
             self.__dgrid.destroy();

             if (dom.byId(self.__dgrid.domNode)) {
             domConstruct.destroy(self.__dgrid.domNode);
             }
             }

             if (self.__name === "tablePublishTime") {
             var button = new Button({
             "label": "Установить количество товаров",
             });

             dom.byId("menuContainer").appendChild(button.domNode);

             on(button, "click", function (event) {
             var countLotNumberTextBox = new NumberTextBox({
             name: "countLot"
             });
             var dialog = new confirmDialog({
             title: "Количество товаров которые будут выставлены",
             style: "width: 500px;",
             content: countLotNumberTextBox.domNode,
             execute: function () {
             if (self.tablePublishTimeWithFactory !== null) {
             domConstruct.destroy(dom.byId(self.tablePublishTimeWithFactory.domNode));
             self.tablePublishTimeWithFactory.destroyRecursive();
             }
             var factor = countLotNumberTextBox.get('value');
             var asyncLoader = function () {
             var deferred = new Deferred();

             store.fetch().then(function (items) {
             var data = [];

             items.forEach(function (item) {
             for (var key in item) {
             if (item.hasOwnProperty(key) && key !== 'id') {
             item[key] *= factor;
             item[key] = item[key].toFixed(3);
             }
             }
             data.push(item);
             });

             var analogStore = new Memory({
             data: data
             });

             deferred.resolve(analogStore)
             });

             return deferred.promise;
             };
             asyncLoader().then(function (storeWithFactory) {
             self.tablePublishTimeWithFactory = TableControlPanelFactory("tablePublishTimeWithFactory", storeWithFactory);
             self.tablePublishTimeWithFactory.startup();

             if (self.factoryTable === null || self.factoryTable === undefined) {
             self.factoryTable = new TitlePane({open: true, title: self.__name});
             self.firstTable.set('open', false);
             group.addChild(self.factoryTable);
             }

             self.factoryTable.set("content", self.tablePublishTimeWithFactory.domNode);
             self.factoryTable.startup();

             //dom.byId("grid").appendChild(self.tablePublishTimeWithFactory.domNode);

             });
             }
             });
             dialog.show();
             });
             } else {
             dom.byId("menuContainer").innerHTML = "";
             }

             self.__dgrid = new TableControlPanelFactory(self.__name, store);

             if (self.__dgrid !== null) {
             self.__dgrid.startup();

             if (self.firstTable === null || self.firstTable === undefined) {
             self.firstTable = new TitlePane({open: true, title: self.__name});
             group.addChild(self.firstTable);
             }

             self.firstTable.set("content", self.__dgrid.domNode);
             self.firstTable.startup();
             //dom.byId("grid").appendChild(self.__dgrid.domNode);
             }

             return self.__dgrid !== null;*/

            return self.__render(store);
        },

        __createConfigDialog: function () {
            var self = this;
            var data = [];
            var store = this.__store;

            if (self.__scriptConfigDialogForm instanceof Form) {
                self.__scriptConfigDialogForm.destroyRecursive(true);
            }
            if (self.__scriptConfigDialog instanceof confirmDialog) {
                self.__scriptConfigDialog.destroyRecursive(true);
            }

            var formCreator = new formCreate();
            var asyFCreate = formCreator.getForm(self.__name);


            asyFCreate.then(function (form) {
                self.__scriptConfigDialogForm = form;
                self.__scriptConfigDialog = new confirmDialog({
                    id: 'scriptConfigDialog',
                    title: self.__scriptsList['scripts'][self.__name].reportName,
                    style: 'width:600px;',
                    content: self.__scriptConfigDialogForm,
                    execute: function () {
                        var asyncProcess = function () {

                            var deferred = new Deferred();

                            setTimeout(function () {
                                deferred.progress("in progress...");
                            }, 2);

                            try {
                                data = {
                                    params: [],
                                    scriptName: null
                                };

                                var f = query("#scriptConfigDialogForm")[0];
                                data.scriptName = self.__name;

                                array.forEach(self.__scriptsList["scripts"][self.__name]["paramsName"], function (name) {
                                    var item = f[name];

                                    if (item !== null && item !== undefined && item.value !== "") {
                                        if (!(name === 'brand' && item.value === '0')) {
                                            data.params.push({name: name, value: item.value});
                                        }
                                    }

                                });
                                var a = 1;
                                self.__store.query(data).then(function (respData) {
                                        if (respData.cdsId === null || respData.cdsId === undefined) {
                                            throw "Not return cdsID";
                                        }

                                        var setting = lang.clone(self.__storeConfig);
                                        setting.target = self.__storeConfig.target + respData.cdsId;


                                        self.__cashStore = new (declare([StoreRqlFilter, Trackable]))(setting);
                                        //self.__cashStore = new (declare([Rest, Trackable]))(setting);

                                        if (!self.__renderPlotWithGrid(self.__filter)) {
                                            setTimeout(function () {
                                                deferred.reject("error by rendering report");
                                            }, 2);
                                        } else {
                                            deferred.resolve('finish');
                                        }

                                    }, function (error) {
                                        setTimeout(function () {
                                            deferred.reject("Script timeout end");
                                        }, 1);
                                    }
                                );
                            } catch (err) {
                                setTimeout(function () {
                                    deferred.reject(err.message);
                                }, 2);
                            }
                            return deferred.promise;
                        };

                        var exec = asyncProcess();

                        exec.then(
                            function (result) {

                                domStyle.set("alertBlock", {display: "block"});
                                domClass.remove("alertBlock", "alert-danger");
                                domClass.remove("alertBlock", "alert-info");
                                domClass.add("alertBlock", "alert-success");

                                dom.byId("output2").innerHTML = "Report create: " + result;
                            }, function (error) {

                                domStyle.set("alertBlock", {display: "block"});
                                domClass.remove("alertBlock", "alert-success");
                                domClass.remove("alertBlock", "alert-info");
                                domClass.add("alertBlock", "alert-danger");

                                dom.byId("output2").innerHTML = "Report create stop! Errored out with: " + error;
                            }, function (progress) {
                                domStyle.set("alertBlock", {display: "block"});

                                domClass.remove("alertBlock", "alert-success");
                                domClass.remove("alertBlock", "alert-danger");
                                domClass.add("alertBlock", "alert-info");

                                dom.byId("output2").innerHTML = "Report create: " + progress;
                            });
                    }
                });

                self.__scriptConfigDialogForm.startup();
                self.__scriptConfigDialog.show();
            });
        }
    });
});