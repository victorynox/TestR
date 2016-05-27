/**
 * Created by root on 22.04.16.
 */
define(
    [
        'dojo/_base/declare',
        'dojo/_base/lang',
        "dgrid/Grid",
        'dgrid/Selection',
        'dgrid/Editor',
        'dgrid/extensions/Pagination',
        'dgrid/extensions/DnD',
        "dojo/dnd/Source",
        "dojo/on",
        "dojo/dom",
        "dojo/dom-construct",
        "dojo/query",
        "dojo/Deferred",
        'dstore/Memory',
        'dstore/Filter',
        "dijit/form/Form",
        "dijit/form/DateTextBox",
        "dijit/form/Button",
        "dijit/form/Select",
        "dojox/layout/TableContainer",
        "dijit/ConfirmDialog",
        "dijit/form/TextBox",
        "dijit/registry",
        "dijit/layout/AccordionContainer",
        "dijit/layout/ContentPane",
        "./FilterTree"

    ],
    function (declare,
              lang,
              Grid,
              Selection,
              Editor,
              Pagination,
              DnD,
              Source,
              on,
              dom,
              domConstruct,
              query,
              Deferred,
              Memory,
              Filter,
              Form,
              DateTextBox,
              Button,
              Select,
              TableContainer,
              ConfirmDialog,
              TextBox,
              registry,
              AccordionContainer,
              ContentPane,
              FilterTree) {
        return declare(null, {

            __store: null,
            __greed: null,
            __columns: null,
            __filterContainer: null,
            filterStore: null,

            __dgridSelect: function () {

            },
            __dgridDeselect: function () {

            },

            getSelectRow: function () {
                var self = this;
                return self.__greed.selectedItem;
            },

            constructor: function (store, columns, dgridSelect, dgridDeselect, divid, filterStore) {
                var self = this;

                if (dgridDeselect !== null && dgridDeselect !== undefined) {
                    this.__dgridDeselect = dgridDeselect;
                }

                if (dgridSelect !== null && dgridSelect !== undefined) {
                    this.__dgridSelect = dgridSelect;
                }


                this.__columns = columns;

                this.__store = store;

                this.__greed = new (declare([Grid, Selection, Pagination, Editor]))({
                    collection: this.__store,
                    columns: this.__columns,
                    selectionMode: "single",
                    /*className: 'dgrid-autoheight',*/
                    pagingLinks: false,
                    pagingTextBox: true,
                    firstLastArrows: true,
                    rowsPerPage: 15,
                    pageSizeOptions: [10, 15, 25]
                }, divid);
                this.__greed.startup();


                this.__selection(this.__dgridSelect, this.__dgridDeselect);
                
                if (filterStore !== null && filterStore !== undefined) {
                    this.filterStore = filterStore;
                    var filterEditDialog = null;
                    var selectParams = null;

                    var formLoad = function () {
                        var deferred = new Deferred();

                        var domNodeDialog = registry.byId("filterSetDialog");
                        if (domNodeDialog !== null && domNodeDialog !== undefined) {
                            domNodeDialog.destroyRecursive();
                        }

                        var domNodeForm = registry.byId("filterSetDialogForm");
                        if (domNodeForm !== null && domNodeForm !== undefined) {
                            domNodeForm.destroyRecursive();
                        }

                        var form = new Form({
                            id: "filterSetDialogForm",
                            doLayout: true
                        });

                        var formContainer = new TableContainer(
                            {
                                cols: 1,
                                customClass: "labelsAndFields",
                                "labelWidth": "200"
                            }
                        );
                        
                        var option = [];

                        self.filterStore.filter().forEach(function (item, i) {
                            option.push({label: (i + 1) + ")[id:" + item.id + "]:" + item.name, value: item.filter});
                        });

                        selectParams = new Select({
                            label: "Выберете фильтр",
                            name: "filter",
                            options: option,
                        });

                        formContainer.addChild(selectParams);
                        formContainer.placeAt(form);

                        filterEditDialog = new ConfirmDialog({
                            id: 'filterSetDialog',
                            title: "Фильтр",
                            style: 'width:600px;',
                            content: form,
                            execute: function () {
                                var parser = new FilterTree();
                                var f = query("#filterSetDialogForm")[0];
                                var rql = f["filter"].value;

                                var data = parser.parseRQLToData(rql, 0, null);
                                var node = parser.stackToObject(data);
                                var filter = parser.filterParse(node);

                                self.reload(self.__store.filter(filter));

                                dom.byId("filter-active").innerHTML = '';

                                self.filterStore.filter((new Filter).eq("filter", rql)).forEach(function (item) {

                                    var filterNode = domConstruct.create('button', {'class': "btn btn-success filter_active"});
                                    on(filterNode, "click", function () {
                                            self.reload(self.__store);
                                            dom.byId("filter-active").innerHTML = "";
                                        }
                                    );
                                    domConstruct.place("<span class='glyphicon glyphicon-remove' style='float:right;'></span><p>" + item.id + ")" + item.name + "</p>", filterNode);
                                    dom.byId("filter-active").appendChild(filterNode);
                                });
                            }
                        });
                        form.startup();
                        deferred.resolve("success");

                        return deferred.promise;
                    };

                    var addFilterBtn = new Button({
                        label: "Применить фильтр к таблице",
                        onClick: function () {
                            formLoad().then(function () {
                                filterEditDialog.show();
                            });
                        }
                    }, "addFilterOnGridButton");

                    var clearFilterBtn = new Button({
                        label: "Очистить фильтр",
                        onClick: function () {
                            self.reload(self.__store);
                            dom.byId("filter-active").innerHTML = "";
                        }
                    }, "clearFilterOnGridButton");

                }
            },

            reload: function (store) {
                var self = this;
                self.__greed.set('collection', store);
            },

            __selection: function (dgridSelect, dgridDeselect) {
                var self = this;
                self.__greed.on('dgrid-select', dgridSelect);
                self.__greed.on('dgrid-deselect', dgridDeselect);
            }
            
            

        });

    });