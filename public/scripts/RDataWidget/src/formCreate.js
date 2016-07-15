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
        "dijit/form/TextBox",
        "dijit/form/Button",
        "dijit/form/Select",
        "dojox/layout/TableContainer",
        "dojo/Deferred",
        "dojo/dom-construct",
        'dstore/Trackable',
        'dstore/Rest',
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
              TableContainer,
              Deferred,
              domConstruct,
              Trackable,
              Rest,
              DstoreAdapter,
              StoreRqlFilter) {
        return declare(null, {
            getCategoryStore: null,
            getBrandStore: null,
            constructor: function () {
                var self = this;

                self.getCategoryStore = new DstoreAdapter( new (declare([Trackable, Rest]))({
                    'target': '/rest/getCategory',
                    'headers': {
                        'Accept': 'application/json',
                    }
                }));

                self.getBrandStore = new DstoreAdapter(new (declare([Trackable, Rest]))({
                    'target': '/rest/getBrand',
                    'idProperty': 'value',
                    'headers': {
                        'Accept': 'application/json',
                    }
                }));

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
                    case "tableCategory":
                    case "tableProduct":
                    {
                        return this.__plotPublishPrice();
                    }
                    case "NNSold":
                    case "NN":
                    {
                        return this.__NNForm();
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

                    var brandArr = [

                    ];

                    self.getBrandStore.query().forEach(function (item) {
                        if((item.selected !== undefined || item.selected !== null ) && item.selected === true){
                            brandArr.push({label: item.name, value: item.value, selected: true});
                        }else{
                            brandArr.push({label: item.name, value: item.value});
                        }
                    });

                    var brand = new Select({
                        label: "Бренд",
                        name: 'brand',
                        options: brandArr,
                        style: 'width: 200px;',
                        maxHeight: -1,
                    });

                    array.forEach([title, beginAddDate, endAddDate, brand], function (child) {
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

                    self.getCategoryStore.query().forEach(function (item) {
                        if((item.selected !== undefined || item.selected !== null ) && item.selected === true){
                            categoryArr.push({label: item.name, value: item.id, selected: true});
                        }else{
                            categoryArr.push({label: item.name, value: item.id});
                        }
                    });

                    self.getBrandStore.query().forEach(function (item) {
                        if((item.selected !== undefined || item.selected !== null ) && item.selected === true){
                            brandArr.push({label: item.name, value: item.value, selected: true});
                        }else{
                            brandArr.push({label: item.name, value: item.value});
                        }
                    });



                    var ebayCategory = new Select({
                        label: "Категория",
                        name: 'likeebaycategory_id',
                        options: categoryArr,
                        style: 'width: 200px;',

                        maxHeight: -1,
                    });

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

                    array.forEach([ebayCategory, beginAddDate, endAddDate, brand], function (child) {
                        formContainer.addChild(child);
                    });

                    formContainer.placeAt(form);

                    deferred.resolve(form);

                    return deferred.promise;
                };

                return asyForm();
            }
        });

    });