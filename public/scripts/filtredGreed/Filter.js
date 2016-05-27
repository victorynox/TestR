/**
 * Created by root on 22.04.16.
 */
define(
    [
        'dojo/_base/declare',
        'dojo/on',
        'dojo/dom',
        'dojo/json',
        'dstore/Memory',
        'dstore/Trackable',
        "dojo/store/Observable",
        "dijit/tree/ObjectStoreModel",
        'dstore/Filter',
        "script/filtredGreed/FilterTree",
        "dijit/form/Form",
        "dijit/form/DateTextBox",
        "dijit/form/Button",
        "dijit/form/Select",
        "dojox/layout/TableContainer",
        "dijit/ConfirmDialog",
        "dijit/form/TextBox",
        "dojo/query",
        'dojo/_base/array',
        "dijit/Tree",
        "dijit/Menu",
        "dijit/MenuItem",
        "dijit/CheckedMenuItem",
        "dijit/MenuSeparator",
        "dijit/PopupMenuItem",

    ],
    function (declare,
              on,
              dom,
              json,
              Memory,
              Trackable,
              Observable,
              ObjectStoreModel,
              Filter,
              FilterTree,
              Form,
              DateTextBox,
              Button,
              Select,
              TableContainer,
              ConfirmDialog,
              TextBox,
              query,
              array,
              Tree,
              Menu,
              MenuItem,
              CheckedMenuItem,
              MenuSeparator,
              PopupMenuItem) {
        return declare(null, {
            __reload: null,
            __filter: {},
            __store: null,
            __columnOption: [],
            __filterTree: null,
            __filterTreeModel: null,
            __storeTree: null,
            __reloadTimeout: 25,

            constructor: function (reload, store, options) {
                var self = this;

                self.__reload = reload;
                self.__store = store;
                self.__columnOption = options;
                self.__filter = new Filter();

                self.__createFilterTree();

                on(document.getElementById("newFilter"), "click", function () {
                    self.__createFilterForm(function (filter) {

                        if (!self.__filter.type) {
                            self.__filter = filter;
                        } else {
                            self.__filter = new Filter().and(self.__filter, filter);
                        }
                        self.__reload(self.__store.filter(self.__filter));
                        self.__reloadTree();
                    });
                });

                on(document.getElementById("cleanFilter"), "click", function () {
                    self.__filter = new Filter();
                    self.__reload(self.__store);
                    self.__reloadTree();
                });
            },
            __addFilter: function () {

            },
            __createFilterForm: function (addFilter) {

                var self = this;

                self.__addFilter = addFilter;

                if (!self.__filterDialog) {
                    var form = new Form({
                        id: "filterCreateDialogForm",
                        doLayout: true
                    });


                    var selectOptions = [];

                    array.forEach(self.__columnOption, function (item, i) {
                        selectOptions.push({id: i, label: item.label, value: item.value.name});
                    }, self);

                    var formContainer = new TableContainer(
                        {
                            cols: 1,
                            customClass: "labelsAndFields",
                            "labelWidth": "200"
                        }
                    );

                    var selectParams = new Select({
                        label: "Поле",
                        name: "field",
                        options: selectOptions
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

                    var value = new Select({
                        label: "Значение",
                        name: "value",
                        options: [
                            {id: 0, label: "ItemListed", value: "ItemListed"},
                            {id: 1, label: "ERROR", value: "ERROR"}
                        ]
                    });

                    formContainer.addChild(selectParams);
                    formContainer.addChild(selectFilter);
                    formContainer.addChild(value);
                    formContainer.placeAt(form);

                    selectParams.on('change', function () {
                        var name = this.get("value");
                        var param = null;
                        array.forEach(self.__columnOption, function (item, i) {
                            if (item.value.name == name) {
                                param = item;
                                return 0;
                            }
                        }, self);

                        selectFilter.set("options", param.filter);
                        //var index = formContainer.getIndexOfChild(value);
                        formContainer.removeChild(value);
                        value = self.__getValue(param);
                        formContainer.addChild(value);
                    });

                    self.__filterDialog = new ConfirmDialog({
                        id: 'filterCreateDialog',
                        title: "Фильтр",
                        style: 'width:600px;',
                        content: form,
                        execute: function () {


                            var f = query("#filterCreateDialogForm")[0];

                            var filter = f["filter"];
                            var field = f["field"];
                            var value = f["value"];

                            var param = null;
                            array.forEach(self.__columnOption, function (item) {
                                if (item.value.name == field.value) {
                                    param = item;
                                    return 0;
                                }
                            }, self);
                            var currentfilter = new Filter();
                            switch (filter.value) {
                                case "eq":
                                {
                                    currentfilter = currentfilter.eq(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                                case "gt":
                                {
                                    currentfilter = currentfilter.gt(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                                case "lt":
                                {
                                    currentfilter = currentfilter.lt(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                                case "gte":
                                {
                                    currentfilter = currentfilter.gte(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                                case "lte":
                                {
                                    currentfilter = currentfilter.lte(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                                case "ne":
                                {
                                    currentfilter = currentfilter.ne(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                                case "in":
                                {
                                    currentfilter = currentfilter.in(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                                case "match":
                                {
                                    currentfilter = currentfilter.match(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                                case "contains":
                                {
                                    currentfilter = currentfilter.contains(field.value, self.__parser(value.value, param.value.type));
                                    break;
                                }
                            }
                            self.__currentfilter = currentfilter;
                            self.__addFilter(currentfilter);

                        }
                    });
                    form.startup();
                    //self.__filterDialog.show();
                }
                self.__filterDialog.show();
            },


            __getValue: function (param) {
                var self = this;

                switch (param.value.field.type) {
                    case 'Select':
                    {
                        return new Select({
                            label: "Значение",
                            name: "value",
                            options: param.value.field.option
                        });
                    }
                    case 'TextBox':
                    {
                        return new TextBox({
                            label: "Значение",
                            name: "value"
                        });
                    }
                }

            },

            __parser: function (value, type) {
                switch (type) {
                    case 'string':
                    {
                        return value + '';
                    }
                    case 'int':
                    {
                        return parseInt(value);
                    }
                    case 'float':
                    {
                        return parseFloat(value);
                    }
                }
            },

            getFilter: function () {
                return json.stringify(this.__filter);
            },

            __createFilterTree: function () {
                var self = this;

                var parser = new FilterTree();
                self.__filterTreeModel = parser.parse(self.__filter, 0, null);

                self.__storeTree = new (declare([Memory, Trackable]))({
                    data: self.__filterTreeModel,
                    idProperty: "id",
                    getChildren: function (object, onComplete) {
                        this.filter({"parentID": object.id}).fetch().then(function (objects) {
                            onComplete(objects);
                        });
                    },
                    mayHaveChildren: function (item) {
                        return item.mayHawChild;
                    },
                    getRoot: function (onItem, onError) {
                        // there should be only a single object in (the root of) this collection,
                        // so we just return that
                        this.get(0).then(onItem);
                    },
                    getLabel: function (object) {
                        if (!object.name) {
                            return object.type ? object.type : "non filter";
                        } else {
                            switch (object.type) {
                                case "eq":
                                {
                                    return object.name + " = " + object.value;
                                }
                                case "ne":
                                {
                                    return object.name + " != " + object.value;
                                }
                                case "lt":
                                {
                                    return object.name + " < " + object.value;
                                }
                                case "lte":
                                {
                                    return object.name + " <= " + object.value;
                                }
                                case "gt":
                                {
                                    return object.name + " > " + object.value;
                                }
                                case "gte":
                                {
                                    return object.name + " >= " + object.value;
                                }
                                case "in":
                                {
                                    return object.name + " in " + object.value;
                                }
                                case "match":
                                {
                                    return object.name + " /" + object.value + "/";
                                }
                                case "contains":
                                {
                                    return object.name + " contains " + object.value;
                                }
                            }
                        }
                    },

                });
                self.__storeTree = self.__storeTree.track();

                self.__reloadTree();

                /*self.__storeTree.on('reload', function (event) {
                    self.__storeTree.get(0).then(function (data) {
                        if (!data) {
                            self.__filter = new Filter();
                        } else {
                            var node = parser.getAllChild(self.__storeTree, data);
                            if (node) {
                                self.__filter = parser.filterParse(node);
                            } else {
                                self.__filter = new Filter();
                            }
                        }
                        self.__reload(self.__store.filter(self.__filter));
                        self.__reloadTree();
                    });
                });*/

                self.__storeTree.on('add', function (event) {
                    self.__filterTree.emit("add", event);
                });
                self.__storeTree.on('put', function (event) {
                    self.__filterTree.emit("put", event);
                });
                self.__storeTree.on('delete', function (event) {
                    self.__filterTree.emit("delete", event);
                });

            },

            __reloadTree: function () {
                var self = this;
                var filterTreeDIV = dom.byId('filters');
                filterTreeDIV.innerHTML = "";
                self.__filterTree = new Tree({
                    notificationStack: [],
                    model: self.__storeTree,
                    onOpenClick: true,
                    onClick: function (item, node, evt) {
                        self.__createMenu(item, node, evt);
                    }
                });
                self.__filterTree.placeAt(filterTreeDIV).startup();
                self.__filterTree.on('add, put, delete', function (event) {
                    self.__filterTree.notificationStack.push(self.__filterTree.notificationStack.length+1);

                    var reloadCheck = function () {
                        var lenght =  self.__filterTree.notificationStack[self.__filterTree.notificationStack.length-1];
                        if( self.__filterTree.notificationStack.length === lenght){
                            self.__filterTree.emit('reload', event);
                        }else{
                            setTimeout(reloadCheck, self.__reloadTimeout);
                        }
                    };
                    setTimeout(reloadCheck, self.__reloadTimeout);
                });
                
                self.__filterTree.on('reload', function (event) {
                    var parser = new FilterTree();
                    self.__storeTree.get(0).then(function (data) {
                        if (!data) {
                            self.__filter = new Filter();
                        } else {
                            var node = parser.getAllChild(self.__storeTree, data);
                            if (node) {
                                self.__filter = parser.filterParse(node);
                            } else {
                                self.__filter = new Filter();
                            }
                        }
                        self.__reload(self.__store.filter(self.__filter));
                        self.__reloadTree();
                    });
                });
            },

            __createMenu: function (item, node, evt) {
                var self = this;

                var pMenu = new Menu({
                    targetNodeIds: [node.focusNode.id]
                });

                pMenu.addChild(new MenuItem({
                    label: "Добавить Фильтр",
                    onClick: function () {

                        if (self.__storeTree.mayHaveChildren(item)) {
                            self.__createFilterForm(function (filter) {
                                if (!self.__filter.type) {
                                    self.__filter = filter;
                                    self.__reload(self.__store.filter(self.__filter));
                                    self.__createFilterTree();
                                } else {

                                    var parser = new FilterTree();
                                    var data = self.__storeTree.data;
                                    var index = [];
                                    data.forEach(function (element) {
                                        index.push(element.id);
                                    });
                                    index.sort();
                                    var node = parser.parse(filter, index[index.length], item.id);
                                    node = node[0];

                                    self.__storeTree.add(node);
                                    //self.__storeTree.emit('reload', {target: node});
                                }
                            });
                        } else {
                            alert("This element can't haw child");
                        }
                    }
                }));
                pMenu.addChild(new MenuItem({
                    label: "Добавить Обьеденение 'ИЛИ' ",
                    onClick: function () {

                        self.__createFilterForm(function (filter) {
                            if (!self.__filter.type) {
                                self.__filter = filter;
                                self.__reload(self.__store.filter(self.__filter));
                                self.__createFilterTree();
                            } else {

                                var parser = new FilterTree();
                                var or = new Filter();
                                or = or.or(filter, parser.filterParse(item));

                                var nodeArr;
                                self.__storeTree.remove(item.id);
                                if (item.parentID !== null) {
                                    var data = self.__storeTree.data;
                                    var index = [];
                                    data.forEach(function (element) {
                                        index.push(element.id);
                                    });
                                    index.sort();
                                    nodeArr = parser.parse(or, index[index.length - 1] + 1, item.parentID);
                                    array.forEach(nodeArr, function (node) {
                                        self.__storeTree.add(node);
                                    });

                                } else {
                                    nodeArr = parser.parse(or, 0, null);

                                    array.forEach(nodeArr, function (node) {
                                        self.__storeTree.add(node);
                                    });
                                }
                               // self.__storeTree.emit('reload', {target: nodeArr});
                            }

                        });
                    }
                }));
                pMenu.addChild(new MenuItem({
                    label: "Добавить Обьеденение 'И' ",
                    onClick: function () {

                        self.__createFilterForm(function (filter) {
                            if (!self.__filter.type) {
                                self.__filter = filter;
                                self.__reload(self.__store.filter(self.__filter));
                                self.__createFilterTree();
                            } else {
                                var parser = new FilterTree();
                                var and = new Filter();
                                and = and.and(filter, parser.filterParse(item));

                                var nodeArr;
                                self.__storeTree.remove(item.id);
                                if (item.parentID !== null) {
                                    var data = self.__storeTree.data;
                                    var index = [];
                                    data.forEach(function (element) {
                                        index.push(element.id);
                                    });
                                    index.sort();
                                    nodeArr = parser.parse(and, index[index.length - 1] + 1, item.parentID);
                                    array.forEach(nodeArr, function (node) {
                                        self.__storeTree.add(node);
                                    });
                                } else {
                                    nodeArr = parser.parse(and, 0, null);
                                    array.forEach(nodeArr, function (node) {
                                        self.__storeTree.add(node);
                                    });
                                }
                                //self.__storeTree.emit('reload', {target: nodeArr});
                            }
                        });
                    }
                }));
                pMenu.addChild(new MenuItem({
                    label: "удалить Фильтр",
                    onClick: function () {
                        self.__storeTree.remove(item.id);
                        //self.__storeTree.emit('reload', {target: item});

                    }
                }));
                pMenu.startup();
            }
        })
    });