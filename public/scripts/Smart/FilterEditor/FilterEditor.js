/**
 * Created by root on 20.05.16.
 */
define([
        'dojo/_base/declare',
        'dojo/_base/lang',
        'dojo/_base/array',
        'dojo/aspect',
        "dojo/dom",
        "dojo/dom-construct",
        'dojo/on',
        "dojo/query",
        "dijit/Menu",
        "dijit/MenuItem",
        "dijit/CheckedMenuItem",
        "dijit/MenuSeparator",
        "dijit/PopupMenuItem",
        "dijit/form/Form",
        "dijit/form/DateTextBox",
        "dijit/form/Button",
        "dijit/form/Select",
        "dojox/layout/TableContainer",
        "dijit/ConfirmDialog",
        "dijit/form/TextBox",
        "./util/FilterParser",
        "./Entity/SmartFilterNode",
        "./widget/FilterEditor"
    ],
    function (declare,
              lang,
              array,
              aspect,
              dom,
              domConstruct,
              on,
              query,
              Menu,
              MenuItem,
              CheckedMenuItem,
              MenuSeparator,
              PopupMenuItem,
              Form,
              DateTextBox,
              Button,
              Select,
              TableContainer,
              ConfirmDialog,
              TextBox,
              FilterParser,
              SmartFilterNode,
              FilterEditor) {
        return declare([], {

            conditionEditorDialog: null,
            menu: null,
            filterEditor: null,
            __addFilter: null,
            __onCancel: null,
            filteredStoreDataOption: null,
            name: null,

            constructor: function (conf, domNode, filteredStoreDataOption) {
                this.filter = new FilterEditor(conf);
                this.filter.startup();
                dom.byId(domNode).appendChild(this.filter.domNode);

                if (filteredStoreDataOption !== undefined) {
                    this.filteredStoreDataOption = filteredStoreDataOption;
                }
                this._conditionEditDialogInit();
                this._filterMenuInit();

                //this.filter._setConditionEditorHandler = this._setConditionEditorHandler;
                //this.filter.showConditionEditForm = this.showConditionEditForm;
                var self = this;
                on(this.filter, "show-condition-edit-form", function (e) {
                    self.showConditionEditForm();
                });

                on(this.filter, "set-condition-editor-handler", function (e) {
                    var onOk = e.onOk !== null && e.onOk !== undefined ? e.onOk : null;
                    var onCancel = e.onCancel !== null && e.onCancel !== undefined ? e.onCancel : null;
                    self._setConditionEditorHandler(onOk, onCancel);
                });
                //aspect.around(this.filter, "showConditionEditForm", lang.hitch(this, "showConditionEditForm"), true);
                //aspect.around(this.filter, "_setConditionEditorHandler", lang.hitch(this, "_setConditionEditorHandler"), true);
            },
            _filterMenuInit: function () {
                var self = this;
                if (this.menu === null) {

                    this.menu = new Menu({
                        targetNodeIds: [self.filter.filterTreeNode.id],
                        onFocus: function () {
                            var selected = self.filter.getSelected();
                            // do stuff here like:
                            array.forEach(this.getChildren(), function (child) {
                                this.removeChild(child);
                                child.targetNode = selected;
                                this.addChild(child);
                            }, this);
                        }
                    });

                    this.menu.startup();
                    this.menu.addChild(new MenuItem({
                        label: "Добавить Условие",
                        targetNode: null,
                        onClick: function (event) {
                            if (this.targetNode) {
                                if (this.targetNode.mayHawChild) {
                                    var targetNode = this.targetNode;
                                    self._setConditionEditorHandler(function (filter) {
                                        filter.mayHawChild = false;
                                        self.filter._store.add(filter, {parent: targetNode});
                                    });

                                    self.showConditionEditForm();
                                } else {
                                    alert('Условние не может иметь детей');
                                }
                            } else {
                                alert('You may select filter item');
                            }

                        }
                    }));

                    this.menu.addChild(new MenuItem({
                        label: "Изменить Условие",
                        targetNode: null,
                        onClick: function (event) {
                            if (this.targetNode) {
                                if (!this.targetNode.mayHawChild) {
                                    var targetNode = this.targetNode;
                                    self._setConditionEditorHandler(function (filter) {
                                        targetNode.name = filter.name;
                                        targetNode.value = filter.value;
                                        targetNode.type = filter.type;
                                        self.filter._store.put(targetNode, {parent: targetNode});
                                    });

                                    self.showConditionEditForm();
                                } else {
                                    alert('You may select condition item');
                                }
                            } else {
                                alert('You may select filter item');
                            }

                        }
                    }));

                    this.menu.addChild(new MenuItem({
                        label: "Удалить Условие",
                        targetNode: null,
                        onClick: function (event) {

                            if (this.targetNode) {
                                if (this.targetNode.parentID !== null) {
                                    self.filter._store.remove(this.targetNode.id);
                                } else {
                                    alert("You can't remove root node");
                                }
                            } else {
                                alert('You may select filter item');
                            }
                        }
                    }));

                    this.menu.addChild(new MenuSeparator());

                    var filterEditLogicSubMenu = new Menu({
                        onFocus: function () {
                            var selected = self.filter.getSelected();
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
                                self.filter._store.add(filter, {parent: this.targetNode});

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
                                self.filter._store.add(filter, {parent: this.targetNode});

                            } else {
                                alert('You may select filter item');
                            }
                        }
                    }));

                    this.menu.addChild(new PopupMenuItem({
                        label: "Добавиь обьеденение ",
                        popup: filterEditLogicSubMenu,
                    }));
                }
                this.menu.startup()
            },

            _setConditionEditorHandler: function (onOk, onCancel) {
                var self = this;
                self.__addFilter = onOk;
                if (onCancel !== undefined && onCancel !== null) {
                    self.__onCancel = onCancel;
                }
            },

            showConditionEditForm: function () {
                var self = this;
                if (self.conditionEditorDialog) {
                    self.conditionEditorDialog.show();
                }
            },

            _conditionEditDialogInit: function () {
                var self = this;

                if (!self.conditionEditorDialog) {
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

                    self.conditionEditorDialogForm = new Form({
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
                        var selectType = new TextBox({
                            label: "Типо значения",
                            name: "type",
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
                            {id: 0, label: "match", value: "match"}
                        ],
                        required: false
                    });

                    var value = new TextBox({
                        label: "Значение",
                        name: "value",
                    });


                    formContainer.addChild(selectParams);
                    if (selectType !== undefined && selectType !== null) {
                        formContainer.addChild(selectType);
                    }
                    formContainer.addChild(selectFilter);
                    formContainer.addChild(value);
                    formContainer.placeAt(self.conditionEditorDialogForm);


                    self.conditionEditorDialog = new ConfirmDialog({
                        id: 'filterCreateDialog',
                        title: "Фильтр",
                        style: 'width:600px;',
                        content: self.conditionEditorDialogForm,
                        execute: function () {


                            var f = query("#filterCreateDialogForm")[0];

                            var filter = f["filter"];
                            var field = f["field"];
                            var value = f["value"];

                            var param = f["type"] !== undefined ? f["type"] : null;

                            if (param === null || param === undefined) {
                                array.forEach(self.filteredStoreDataOption, function (item) {
                                    if (item.value.name == field.value) {
                                        param = {};
                                        param.value = item.value.type;
                                        return 0;
                                    }
                                }, self);
                            }

                            var currentFilter = new SmartFilterNode(filter.value);
                            currentFilter.mayHawChild = false;
                            currentFilter.name = field.value;
                            currentFilter.value = parser(value.value, param.value);
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
                    self.conditionEditorDialogForm.startup();
                }
            },

            destroy: function () {
                var self = this;

                self.filter.destroy();
                self.filter = null;


                domConstruct.destroy(self.domNode);

                if (self.conditionEditorDialogForm !== null &&
                    self.conditionEditorDialogForm !== undefined &&
                    dom.byId("filterCreateDialogForm")) {

                    self.conditionEditorDialogForm.destroyRecursive();

                    try {
                        domConstruct.destroy("filterCreateDialogForm");
                    } catch (e) {}
                }
                if (self.conditionEditorDialog !== null &&
                    self.conditionEditorDialog !== undefined &&
                    dom.byId("filterCreateDialog")) {

                    self.conditionEditorDialog.destroyRecursive();

                    try {
                        domConstruct.destroy("filterCreateDialog");
                    } catch (e) {}
                }
            }
        });
    }
)
;