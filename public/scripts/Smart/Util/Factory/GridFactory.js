/**
 * Created by root on 25.05.16.
 */
/**
 * Created by root on 19.05.16.
 */
define(
    [
        'dojo/_base/declare',
        'dojo/_base/lang',
        'dojo/_base/array',
        "dojo/dom",
        "dojo/dom-construct",
        'dojo/on',
        "dojo/parser",
        'dgrid/Grid',
        'dgrid/Keyboard',
        'dgrid/Selection',
        'dgrid/extensions/Pagination',
        'dgrid/extensions/ColumnHider',
        'dgrid/extensions/ColumnReorder',
        'dgrid/extensions/ColumnResizer',
        'dgrid/_StoreMixin',
        "./StoreFactory",
        '../../FilteredGrid/data/gridConfig'
    ],
    function (declare,
              lang,
              array,
              dom,
              domConstruct,
              on,
              parser,
              Grid,
              Keyboard,
              Selection,
              Pagination,
              ColumnHider,
              ColumnReorder,
              ColumnResizer,
              _StoreMixin,
              StoreFactory,
              gridConfig) {

        var gridMixinNameToConstructor = {
            "Grid": Grid,
            "Keyboard": Keyboard,
            "Selection": Selection,
            "Pagination": Pagination,
            "ColumnHider": ColumnHider,
            "ColumnReorder": ColumnReorder,
            "ColumnResizer": ColumnResizer,
            "_StoreMixin": _StoreMixin,
        };


        function configureColumnHider(grid, config) {
            array.forEach(config['hiddenColumn'], function (id) {
                if (!grid.isColumnHidden(id)) {
                    grid.toggleColumnHiddenState(id, true);
                }
            });
            //return grid;
        }

        function configureColumnResizer(grid, config) {
            array.forEach(config['columnWidth'], function (item) {
                grid.resizeColumnWidth(item.id, item.width);
            });
            //return grid;
        }

        function configureColumnReorder(grid, config) {
            array.forEach(config['columnWidth'], function (item) {

            });
            //return grid;
        }

        var gridMixinNameToConfigure = {
            "Grid": null,
            "Keyboard": null,
            "Selection": null,
            "Pagination": null,
            "ColumnHider": configureColumnHider,
            "ColumnReorder": null,
            "ColumnResizer": configureColumnResizer,
            "_StoreMixin": null
        };

        return function (name) {
            var declareArray = [];
            var option = {};
            var grid = null;

            if (gridConfig[name] !== undefined && gridConfig[name] !== null) {


                //noinspection JSDuplicatedDeclaration
                for (var module in gridConfig[name]) {
                    if (gridConfig[name].hasOwnProperty(module)) {
                        declareArray.push(gridMixinNameToConstructor[module]);

                        if (gridConfig[name][module]["before"] !== null &&
                            gridConfig[name][module]["before"] !== undefined) {

                            for (var index in gridConfig[name][module]["before"]) {
                                if (gridConfig[name][module]["before"].hasOwnProperty(index)) {
                                    option[index] = gridConfig[name][module]["before"][index];
                                }
                            }
                        }
                    }
                }

                grid = new (declare(declareArray))(option);

                //noinspection JSDuplicatedDeclaration
                for (var module in gridConfig[name]) {
                    if (gridConfig[name].hasOwnProperty(module)) {
                        if (gridMixinNameToConfigure[module] !== null) {
                            gridMixinNameToConfigure[module](grid, gridConfig[name][module]['after']);
                        }
                    }
                }
            }

            return grid;
        }
    }
);