define(["dojo/dom",
        "dojo/on",
        "dojo",
        "dojo/request",
        "dojo/query",
        "dojo/_base/array",
        'dojo/_base/declare',
        "dijit/form/Form",
        "dijit/form/DateTextBox",
        "dijit/form/NumberTextBox",
        "dijit/form/TextBox",
        "dijit/form/Button",
        "dijit/form/Select",
        "dijit/Tree",
        "dojox/layout/TableContainer",
        "dojo/Deferred",
        "dojo/DeferredList",
        "dojo/dom-construct",
        'dstore/Trackable',
        'dstore/Rest',
        'dstore/Memory',
        'dstore/legacy/DstoreAdapter',
        "Rscript/Smart/extensions/Store/StoreRqlFilter",
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
              TextBox,
              Button,
              Select,
              Tree,
              TableContainer,
              Deferred,
              DeferredList,
              domConstruct,
              Trackable,
              Rest,
              Memory,
              DstoreAdapter,
              StoreRqlFilter) {
        return declare(null, {
            getCategoryStore: null,
            getBrandStore: null,


             loadArrayByStore: function (args) {
                var deferred = new Deferred();

                if ((args.store !== null && args.store !== undefined) &&
                    (args.callback !== null && args.callback !== undefined ) &&
                    (args.array !== null && args.array !== undefined)) {

                    args.store.fetch().then(function(objects){
                        args.callback(objects);
                        deferred.resolve(args.array);
                    })

                } else {
                    throw "not set params";
                }

                return deferred.promise;
            },

            constructor: function () {
                var self = this;

                self.getCategoryStore =new (declare([Trackable, Rest]))({
                    'target': '/rest/getCategory',
                    'headers': {
                        'Accept': 'application/json',
                    }
                });

                self.getBrandStore = new (declare([Trackable, Rest]))({
                    'target': '/rest/getBrand',
                    'idProperty': 'value',
                    'headers': {
                        'Accept': 'application/json',
                    }
                });

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
                    case "soldProducts":
                    case "tableProduct": {
                        return this.__plotPublishPrice();
                    }
                    case "NNSold":
                    case "NN": {
                        return this.__NNForm();
                    }
                    case "soldView": {
                        return this.__soldViewForm();
                    }
                    default: {
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

            __soldViewForm: function () {
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

                    var beginAddDate = new DateTextBox({
                        label: "Дата начала выборки",
                        name: "begadd_date",
                        required: false
                    });

                    var endAddDate = new DateTextBox({
                        label: "Дата конца выборки",
                        name: "endadd_date",
                        required: false
                    });

                    array.forEach([beginAddDate, endAddDate], function (child) {
                        formContainer.addChild(child);
                    });

                    formContainer.placeAt(form);

                    deferred.resolve(form);

                    return deferred.promise;
                };

                return asyForm();
            },

            __NNForm: function () {
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

                    var title = new TextBox({
                        label: "Титл Товара",
                        name: "liketitle",
                        required: false
                    });

                    var beginAddDate = new DateTextBox({
                        label: "Дата начала выборки",
                        name: "begadd_date",
                        required: false
                    });

                    var endAddDate = new DateTextBox({
                        label: "Дата конца выборки",
                        name: "endadd_date",
                        required: false
                    });

                    array.forEach([title, beginAddDate, endAddDate], function (child) {
                        formContainer.addChild(child);
                    });

                    formContainer.placeAt(form);

                    deferred.resolve(form);

                    return deferred.promise;
                };

                return asyForm();
            },

            __plotPublishPrice: function () {
                var self = this;

                var asyForm = function () {
                    var deferred = new Deferred();
                    var list = [];
                    var list2 = [];

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

                    var categoryArr = [];
                    var brandArr = [];

                    var categoryProm = self.loadArrayByStore({
                        store: self.getCategoryStore,
                        array: categoryArr,
                        callback: function (objects) {
                            objects.forEach(function (item) {
                                categoryArr.push({label: item.name, id: item.id, parentID: item.parentID});
                            });
                        }
                    });

                    var brendProm = self.loadArrayByStore({
                        store: self.getBrandStore,
                        array: brandArr,
                        callback: function (objects) {
                            objects.forEach(function (item) {
                                if ((item.selected !== undefined || item.selected !== null ) && item.selected === true) {
                                    brandArr.push({label: item.name, value: item.value, selected: true});
                                } else {
                                    brandArr.push({label: item.name, value: item.value});
                                }
                            });
                        }
                    });

                    var defList = new DeferredList([categoryProm, brendProm]);

                    defList.then(function(results){
                        var brand = new Select({
                            label: "Бренд",
                            name: 'brand',
                            options: brandArr,
                            style: 'width: 200px;',

                            maxHeight: -1,
                        });

                        var beginAddDate = new DateTextBox({
                            label: "Дата начала выборки",
                            name: "begadd_date",
                            required: false
                        });

                        var endAddDate = new DateTextBox({
                            label: "Дата конца выборки",
                            name: "endadd_date",
                            required: false
                        });

                        var categoryTree = new Memory({
                            data: categoryArr,
                            mayHaveChildren: function (item) {
                                return true;
                            },
                            getChildren: function (object, onComplete) {

                                this.filter({"parentID": object.id}).fetch().then(function (objects) {
                                    onComplete(objects);
                                });
                            },
                            getLabel: function (object) {
                                return object.label;
                            },
                            getRoot: function (onItem, onError) {
                                this.filter({'parentID': '-1'}).fetch().then(function (objects) {
                                    if (objects[0] !== null && objects[0] !== undefined) {
                                        onItem(objects[0]);
                                    } else {
                                        onItem(objects);
                                    }
                                });
                            }
                        });

                        var ebayCategoryTree = new Tree({
                            label: "Категория",
                            name: 'likeebaycategory_id',
                            model: categoryTree,
                            openOnDblClick: true,
                            onClick: function(item){
                                var f = query("#scriptConfigDialogForm")[0];
                                f['likeebaycategory_id'] = {
                                    name: 'ebaycategory_id',
                                    value: item.id
                                }
                            }
                        });

                        array.forEach([ebayCategoryTree, beginAddDate, endAddDate, brand], function (child) {
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