define(['dojo/dom',
        "dojo/on",
        "dojo",
        "dojo/request",
        "dojo/query",
        "dojo/_base/array",
        'dojo/_base/declare',
        "dijit/form/Form",
        "dijit/form/DateTextBox",
        "dijit/form/NumberTextBox",
        "dijit/form/Button",
        "dijit/form/Select",
        "dojox/layout/TableContainer",
        "dojo/Deferred",
        "dojo/dom-construct",
        "dojo/domReady!"],
    function (dom,
              on,
              dojo,
              request,
              query,
              array,
              declare,
              Form,
              DateTextBox,
              NumberTextBox,
              Button,
              Select,
              TableContainer,
              Deferred,
              domConstruct) {
        return declare(null, {
            _myStore: null,
            constructor: function (myStore) {
                var self = this;
                self._myStore = myStore;
            },
            getForm: function (name) {
                domConstruct.destroy("scriptConfigDialogForm");
                switch (name) {
                    case "plotPublishPrice":
                    case "plotSoldPrice":
                    case "plotProbPrice":
                    case "plotProfPrice":
                    case "plotPublishDay":
                    case "plotSoldDay":
                    case "plotProbDay":
                    case "plotCreatedDay":
                    case "plotPublishTime":
                    case "plotSoldTime":
                    case "plotProbTime":
                    case "plotCreatedTime":
                    case "plotCreatedTimeWithTZ":
                    case "tableCategoryPrice":
                    case "tableCategoryID":
                    case "tableProduct":
                    {
                        return this.__plotPublishPrice();
                    }
                    default:
                    {
                        return this.__notForm();
                    }
                }
            },

            __notForm: function () {
                var self = this;
                var asyForm = function () {
                    var deferred = new Deferred();



                    var form = new Form({
                        id: "scriptConfigDialogForm",
                        doLayout: true
                    });

                    setTimeout(function () {
                        deferred.resolve(form)
                    }, 2);

                    return deferred.promise;
                };
                return asyForm();
            },
            __plotPublishPrice: function () {
                var self = this;

                var asyForm = function () {
                    var deferred = new Deferred();
                    var list = [];


                    var form = new Form({
                        id: "scriptConfigDialogForm",
                        doLayout: true
                    });

                    var formContainer = new TableContainer(
                        {
                            cols: 1,
                            customClass: "labelsAndFields",
                            "labelWidth": "200"
                        }
                    );

                    var ebayCategoryId = new NumberTextBox({
                        label: "Номер категории",
                        name: "ebaycategory_id",
                        required: false
                    });

                    var beginAddDate = new DateTextBox({
                        label: "Дата начала выборки",
                        name: "begadd_date",
                        required: false
                    });

                    var endAddDate = new DateTextBox({
                        label: "Дата начала выборки",
                        name: "endadd_date",
                        required: false
                    });

                    var brand = new Select();

                    self._myStore.query([{name: "scriptName", value: "getBrand"}]).then(function (items) {

                        list.push({id: 0, label: "По всем брендам", value: ""});

                        array.forEach(items, function (item) {
                            list.push({id: item.id, label: item.label, value: item.value});
                        });

                        var brand = new Select({
                            label: "Бренд",
                            name: "brand",
                            options: list,
                            required: false
                        });

                        array.forEach([ebayCategoryId, beginAddDate, endAddDate, brand], function (child) {
                            formContainer.addChild(child);
                        });
                        formContainer.placeAt(form);

                        deferred.resolve(form);
                    });

                    return deferred.promise;
                };

                return asyForm();
            }
        });

    });