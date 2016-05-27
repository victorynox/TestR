/**
 * Created by root on 29.04.16.
 */

define([
    'dojo/_base/declare',
    'dojo/_base/lang',
    'dojo/Deferred',
    'dojo/aspect',
    'dojo/_base/array',
    'dojo/dom',
    "dijit/registry",
    "dojo/dom-construct",
    'dojo/has',
    'dojo/on',
    "script/SmartTree/SmartTree",
    "dijit/Menu",
    "dijit/MenuItem",
    "dijit/CheckedMenuItem",
    "dijit/MenuSeparator",
    "dijit/PopupMenuItem",
    
    "./SmartFilterNode",
    "dijit/form/Form",
    "dijit/form/DateTextBox",
    "dijit/form/Button",
    "dijit/form/Select",
    "dojox/layout/TableContainer",
    "dijit/ConfirmDialog",
    "dijit/form/TextBox",
    "dojo/dnd/Source",
    "dojo/query",
], function (declare,
             lang,
             Deferred,
             aspect,
             array,
             dom,
             registry,
             domConstruct,
             has,
             on,
             SmartTree,
             Menu,
             MenuItem,
             CheckedMenuItem,
             MenuSeparator,
             PopupMenuItem,
             SmartFilterNode,
             Form,
             DateTextBox,
             Button,
             Select,
             TableContainer,
             ConfirmDialog,
             TextBox,
             Source,
             query) {
    return declare(null, {

        domNodeID: null,

        storeTree: null,

        filteredStoreDataOption: null,

        smartTree: null,

        menu: null,

        filterEditDialog: null,

        __addFilter: null,
        __onCancel: null,

        __targetNode: null,

        constructor: function (domNodeID, storeTree, filteredStoreDataOption) {
            var self = this;

            if (domNodeID) {
                self.domNodeID = domNodeID;
            }

            if (storeTree) {
                self.storeTree = storeTree;
            }

            if (filteredStoreDataOption) {
                self.filteredStoreDataOption = filteredStoreDataOption;
            }

            if (self.storeTree && self.domNodeID) {
                self.smartTree = new SmartTree(self.storeTree, self.domNodeID);
                self.__menuInit();
                self.__filterEditDialogInit();
                self.__filterTreeControllerItemInit();
            }
        },

        showEditForm: function () {
            var self = this;
            //if (!self.filterEditDialog) {
            self.__filterEditDialogInit();
            //}
            self.filterEditDialog.show();

        },

        _setFormFilterHandler: function (onOk, onCancel) {
            var self = this;
            self.__addFilter = onOk;
            if (onCancel !== undefined && onCancel !== null) {
                self.__onCancel = onCancel;
            }
        },

        __menuInit: function () {
            var self = this;
            if (!self.menu) {

                self.menu = new Menu({
                    targetNodeIds: ["filters"],
                    onFocus: function () {
                        var selected = self.smartTree.tree.selectedItem;
                        // do stuff here like:
                        array.forEach(this.getChildren(), function (child) {
                            this.removeChild(child);
                            child.targetNode = selected;
                            this.addChild(child);
                        }, this);
                    }
                });

                self.menu.addChild(new MenuItem({
                    label: "Добавить Условие",
                    targetNode: null,
                    onClick: function (event) {
                        if (this.targetNode) {
                            if (this.targetNode.mayHawChild) {
                                var targetNode = this.targetNode;
                                self._setFormFilterHandler(function (filter) {
                                    filter.mayHawChild = false;
                                    self.storeTree.add(filter, {parent: targetNode});
                                });

                                self.showEditForm();
                            } else {
                                alert('Условние не может иметь детей');
                            }
                        } else {
                            alert('You may select filter item');
                        }

                    }
                }));

                self.menu.addChild(new MenuItem({
                    label: "Изменить Условие",
                    targetNode: null,
                    onClick: function (event) {
                        if (this.targetNode) {
                            if (!this.targetNode.mayHawChild) {
                                var targetNode = this.targetNode;
                                self._setTargetNode(targetNode);
                                self._setFormFilterHandler(function (filter) {
                                    targetNode.name = filter.name;
                                    targetNode.value = filter.value;
                                    targetNode.type = filter.type;
                                    self.storeTree.put(targetNode, {parent: targetNode});
                                });

                                self.showEditForm();
                            } else {
                                alert('You may select condition item');
                            }
                        } else {
                            alert('You may select filter item');
                        }

                    }
                }));

                self.menu.addChild(new MenuItem({
                    label: "Удалить Условие",
                    targetNode: null,
                    onClick: function (event) {

                        if (this.targetNode) {
                            if (this.targetNode.parentID !== null) {
                                self.storeTree.remove(this.targetNode.id);
                            } else {
                                alert("You can't remove root node");
                            }
                        } else {
                            alert('You may select filter item');
                        }
                    }
                }));
                
                self.menu.addChild(new MenuSeparator());

                var filterEditLogicSubMenu = new Menu({
                    onFocus: function () {
                        var selected = self.smartTree.tree.selectedItem;
                        // do stuff here like:
                        array.forEach(this.getChildren(), function (child) {
                            this.removeChild(child);
                            child.targetNode = selected;
                            this.addChild(child);
                        }, this);
                    }
                });

                filterEditLogicSubMenu.addChild(new MenuItem({
                    label: "'AND' / 'И' ",
                    targetNode: null,
                    onClick: function (event) {

                        if (this.targetNode) {

                            var filter = new SmartFilterNode('and');
                            self.storeTree.add(filter, {parent: this.targetNode});

                        } else {
                            alert('You may select filter item');
                        }
                    }
                }));

                filterEditLogicSubMenu.addChild(new MenuItem({
                    label: "'OR' / 'ИЛИ' ",
                    targetNode: null,
                    onClick: function (event) {

                        if (this.targetNode) {

                            var filter = new SmartFilterNode('or');
                            self.storeTree.add(filter, {parent: this.targetNode});

                        } else {
                            alert('You may select filter item');
                        }
                    }
                }));

                self.menu.addChild(new PopupMenuItem({
                    label: "Добавиь обьеденение ",
                    popup: filterEditLogicSubMenu,
                }));

                self.menu.startup();
            }
        },

        _setTargetNode: function (targetNode) {
            var self = this;
            self.__targetNode = targetNode;
        },

        __filterEditDialogInit: function () {
            var self = this;

            if (!self.filterEditDialog) {
                //domConstruct.destroy('filterCreateDialogForm');

                var getValue = function (param) {
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

                };

                var parser = function (value, type) {
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
                };

                var form = new Form({
                    id: "filterCreateDialogForm",
                    doLayout: true
                });

                var selectOptions = [];

                array.forEach(self.filteredStoreDataOption, function (item, i) {
                    selectOptions.push({id: i, label: item.label, value: item.value.name});
                }, self);

                var formContainer = new TableContainer(
                    {
                        cols: 1,
                        customClass: "labelsAndFields",
                        "labelWidth": "200"
                    }
                );

                var selectParams;

                if (self.filteredStoreDataOption) {
                    selectParams = new Select({
                        label: "Поле",
                        name: "field",
                        options: selectOptions
                    });

                    selectParams.on('change', function () {
                        var name = this.get("value");
                        var param = null;
                        array.forEach(self.filteredStoreDataOption, function (item, i) {
                            if (item.value.name == name) {
                                param = item;
                                return 0;
                            }
                        }, self);

                        selectFilter.set("options", param.filter);
                        //var index = formContainer.getIndexOfChild(value);
                        formContainer.removeChild(value);
                        value = getValue(param);
                        formContainer.addChild(value);
                    });
                } else {

                    selectParams = new TextBox({
                        label: "Поле",
                        name: "field",
                    });

                }
                var selectFilter = new Select({
                    label: "Фильтр",
                    name: "filter",
                    options: [
                        {id: 0, label: "=", value: "eq"},
                        {id: 0, label: ">", value: "gt"},
                        {id: 0, label: "<", value: "lt"},
                        {id: 0, label: ">=", value: "gte"},
                        {id: 0, label: "<=", value: "lte"},
                        {id: 0, label: "!=", value: "ne"},
                        {id: 0, label: "in", value: "in"},
                        {id: 0, label: "match", value: "match"},
                        {id: 0, label: "contains", value: "contains"}
                    ],
                    required: false
                });

                var value = new TextBox({
                    label: "Значение",
                    name: "value",
                });


                formContainer.addChild(selectParams);
                formContainer.addChild(selectFilter);
                formContainer.addChild(value);
                formContainer.placeAt(form);


                self.filterEditDialog = new ConfirmDialog({
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
                        array.forEach(self.filteredStoreDataOption, function (item) {
                            if (item.value.name == field.value) {
                                param = item;
                                return 0;
                            }
                        }, self);
                        var currentFilter = new SmartFilterNode(filter.value);
                        currentFilter.mayHawChild = false;
                        currentFilter.name = field.value;
                        currentFilter.value = parser(value.value, param.value.type);
                        if (self.__addFilter !== null) {
                            self.__addFilter(currentFilter);
                        }
                    },
                    onCancel: function () {
                        if (self.__onCancel !== null) {
                            self.__onCancel();
                        }
                    }
                });
                form.startup();
            }
        },

        __filterTreeControllerItemInit: function () {
            var self = this;
            var filter;
            self.storeTree.itemCreator = function (nodes, target, source) {
                var sourceItem = source.getItem(nodes[0].id);

                if (sourceItem) {
                    if (Array.isArray(sourceItem.type)) {
                        switch (sourceItem.type[0]) {
                            case "and":
                            {
                                filter = new SmartFilterNode('and');
                                return [filter];
                                //self.storeTree.add(filter, {parent: parent});

                            }
                            case "or":
                            {
                                filter = new SmartFilterNode('or');
                                return [filter];
                                //self.storeTree.add(filter, {parent: parent});

                            }
                            case "filter":
                            {

                                self._setFormFilterHandler(function (filter) {
                                    self.storeTree.filter({"type": "proxy"}).forEach(function (item) {
                                        filter.id = item.id;
                                        filter.parentID = item.parentID;
                                        self.storeTree.put(filter, {parent: parent, overwrite: true});
                                    });
                                }, function () {
                                    self.storeTree.filter({"type": "proxy"}).forEach(function (item) {
                                        self.storeTree.remove(item.id);
                                    });
                                });

                                self.showEditForm();

                                filter = new SmartFilterNode('proxy');
                                filter.name = "new Condition";
                                return [filter];
                            }
                        }
                    }
                }
            }

        },

        destroy: function () {
            var filterCreatedDialogNode = registry.byId('filterCreateDialog');
            var filterCreateDialogFormNode = registry.byId('filterCreateDialogForm');
            var tree = registry.byId(self.domNodeID);


            if (filterCreateDialogFormNode) {
                filterCreateDialogFormNode.destroyRecursive();
            }

            if (filterCreatedDialogNode) {
                filterCreatedDialogNode.destroyRecursive();
            }

            if (tree) {
                tree.destroyRecursive();
            }

        },

        setData: function (data) {
            var self = this;
            self.storeTree.setData(data);
            self.smartTree.setStoreTree(self.storeTree);
        }

    })
});
