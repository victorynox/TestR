define([
    'dojo/_base/declare',
    'dojo/_base/array',
    "dijit/ConfirmDialog",
    "dijit/form/TextBox",
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
    "dojox/layout/TableContainer",
    "dojo/Deferred",
    "dojo/dom-style"


], function (declare,
             array,
             confirmDialog,
             TextBox,
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
             TableContainer,
             Deferred,
             domStyle
) {
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
        __chart: {
            width: "100%",
            height: "500px",
            margin: "5px auto 0px auto"
        },
        constructor: function (scriptsList, store, chart) {
            if(scriptsList != null && store instanceof Store){

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
                    listForSelect.push({label: name, value: name})
                });
                this.__createSelectionForm(listForSelect);

                var temp;
                on(document.getElementById("newFilter"), "click", function () {
                    temp = null;
                    self.__createFilterForm();
                });


                on(document.getElementById("addFilter"), "click", function () {
                    if(!(temp instanceof Memory)){
                        temp = self.__cashStore.filter(self.__filter);
                    }else{
                        temp = temp.filter(self.__filter);
                    }

                    self.__createFilterForm(temp);
                });

                on(document.getElementById("cleanFilter"), "click", function () {
                    self.__filter = {};
                    self.__renderPlotWithGrid(self.__filter);
                });

                on(document.getElementById("closeAlert"), "click", function () {
                    domStyle.set("alertBlock", {
                        display: "none"
                    });
                });
            }
        },

        __createFilterForm: function (store) {
            var self = this;


            if (!self.__filterDialog) {
                var form = new Form({
                    id: "filterCreateDialogForm",
                    doLayout: true
                });

                var formContainer = new TableContainer(
                    {
                        cols: 1,
                        customClass: "labelsAndFields",
                        "labelWidth": "200"
                    }
                );

                var selectParamsList = [];
                array.forEach(self.__scriptsList["scripts"][self.__name]["return"]['fieldNames'], function (names, i) {
                    selectParamsList.push({id: i, label:names, value:names });
                });

                var selectParams = new Select({
                    label: "Поле",
                    name: "field",
                    options: selectParamsList,
                    required: false
                });

                var selectFilter = new Select({
                    label: "Фильтр",
                    name: "filter",
                    options: [
                        {id: 0, label: "=", value: "eq"},
                        {id: 0, label: ">", value: "gt"},
                        {id: 0, label: "<", value: "lt"},
                        {id: 0, label: ">=", value: "gte"},
                        {id: 0, label: "<=", value: "lte"},
                        {id: 0, label: "!=", value: "ne"}
                    ],
                    required: false
                });
                var value = new TextBox({
                    label: "Значение",
                    name: "value",
                });

                formContainer.addChild(selectFilter);
                formContainer.addChild(selectParams);
                formContainer.addChild(value);
                formContainer.placeAt(form);

                self.__filterDialog = new confirmDialog({
                    id: 'filterCreateDialog',
                    title: 'test dialog',
                    style: 'width:600px;',
                    content: form,
                    execute: function () {
                        var f = query("#filterCreateDialogForm")[0];

                        var filter = f["filter"];
                        var field = f["field"];
                        var value = f["value"];
                        self.__filter = new Filter();
                        switch (filter.value) {
                            case "eq":
                            {
                                self.__filter = self.__filter.eq(field.value, parseFloat(value.value));
                                break;
                            }
                            case "gt":
                            {
                                self.__filter = self.__filter.gt(field.value, parseFloat(value.value));
                                break;
                            }
                            case "lt":
                            {
                                self.__filter = self.__filter.lt(field.value, parseFloat(value.value));
                                break;
                            }
                            case "gte":
                            {
                                self.__filter = self.__filter.gte(field.value, parseFloat(value.value));
                                break;
                            }
                            case "lte":
                            {
                                self.__filter = self.__filter.lte(field.value, parseFloat(value.value));
                                break;
                            }
                            case "ne":
                            {
                                self.__filter = self.__filter.ne(field.value, parseFloat(value.value));
                                break;
                            }
                        }
                        self.__renderPlotWithGrid(self.__filter, store);
                    }
                });


                form.startup();
                //self.__filterDialog.show();
            }
            self.__filterDialog.show();

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
                self.__createConfigDialog();
            });

            var divForm = dom.byId("selectScript");

            this.__scriptSelectionForm.placeAt(divForm);
        },

        __renderPlotWithGrid: function (filter, store) {
            var self = this;
            if(!(store instanceof  Memory)){
                store = self.__cashStore;
            }

            var chartDiv = dom.byId("simplechart");
            var gridDiv = dom.byId("grid");

            chartDiv.innerHTML = "";
            gridDiv.innerHTML = "";

            var setting = {
                title: self.__scriptsList.scripts[self.__name]['reportName'],
                return: {
                    fieldNames: self.__scriptsList["scripts"][self.__name]["return"]['fieldNames'],
                    fieldLabel: self.__scriptsList["scripts"][self.__name]["return"]['fieldLabel']
                }
            };

            if(self.__scriptsList["scripts"][self.__name]["return"]['type'] === "plot"){
                domStyle.set(chartDiv, {
                    /*width: "1200px",
                    height: "500px",
                    margin: "5px auto 0px auto"*/
                    width: self.__chart.width,
                    height: self.__chart.height,
                    margin: self.__chart.margin
                });
                setting.axis = self.__scriptsList.scripts[self.__name]['axis'];
                self.plot = new plotCreate('simplechart', store.filter(filter), setting);
                if(self.plot.isError){
                    return false;
                }
                self.plot.render();
            }else{
                domStyle.set(chartDiv, {
                    /*width: "1200px",
                     height: "500px",
                     margin: "5px auto 0px auto"*/
                    width: 0,
                    height: 0,
                    margin: 0
                });
            }

            self.__dgrid = new dgredCreate(store.filter(filter), setting);
            return !self.__dgrid.isError;

        },

        __createConfigDialog: function () {
            var self = this;
            var data = [];
            var store = this.__store;

            if(self.__scriptConfigDialogForm instanceof Form){
                self.__scriptConfigDialogForm.destroyRecursive(true);
            }
            if(self.__scriptConfigDialog instanceof confirmDialog){
                self.__scriptConfigDialog.destroyRecursive(true);
            }

            var formCreator = new formCreate(store);
            var asyFCreate = formCreator.getForm(self.__name);


            asyFCreate.then(function (form) {
                self.__scriptConfigDialogForm = form;
                self.__scriptConfigDialog = new confirmDialog({
                    id: 'scriptConfigDialog',
                    title: 'test dialog',
                    style: 'width:600px;',
                    content: self.__scriptConfigDialogForm,
                    execute: function () {
                        var asyncProcess = function () {

                            var deferred = new Deferred();

                            setTimeout(function () {
                                deferred.progress("in progress...");
                            }, 2);

                            try {
                                data = [];
                                var f = query("#scriptConfigDialogForm")[0];
                                data.push({name: "scriptName", value: self.__name});

                                array.forEach(self.__scriptsList["scripts"][self.__name]["paramsName"], function (name) {
                                    var item = f[name];
                                    if (item.value != "") {
                                        data.push({name: name, value: item.value});
                                    }

                                });

                                self.__store.query(data).then(function (items) {
                                    if(!items || items.length < 1){
                                        setTimeout(function () {
                                            deferred.reject("Data were not returned");
                                        }, 1);
                                    }else if(items[0] === "ERROR"){
                                        setTimeout(function () {
                                            deferred.reject("Sent invalid data or data is not enough for the report");
                                        }, 1);
                                    }else{
                                        self.__cashStore = new (declare([Memory, Trackable]))({data: items});

                                        if(!self.__renderPlotWithGrid(self.__filter)){
                                            setTimeout(function () {
                                                deferred.reject("error by rendering report");
                                            }, 2);
                                        }else{
                                            deferred.resolve('finish');
                                        }

                                    }
                                });
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
                                domStyle.set("alertBlock", {
                                    display: "block"
                                });
                                dom.byId("output2").innerHTML = "Report create: " + result;
                            }, function (error) {
                                domStyle.set("alertBlock", {
                                    display: "block"
                                });
                                dom.byId("output2").innerHTML = "Report create stop! Errored out with: " + error;
                            }, function (progress) {
                                domStyle.set("alertBlock", {
                                    display: "block"
                                });
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