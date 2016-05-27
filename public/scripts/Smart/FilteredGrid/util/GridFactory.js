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

        'dstore/Rest',
        'dstore/RequestMemory',
        'dstore/Trackable',
        'dstore/Filter',
        
        '../../Util/GetTableColumnWithConfig'
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
              Rest,
              RequestMemory,
              Trackable,
              Filter,
              GetTableColumnWithConfig) {

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

        
        /*
         conf{
         name: "",
         configName: "",
         options: [],
         declare: [],
         domNode: node,
         }
         */
        return function (conf) {

            //configStore = store;

            var declareArray = [];
            var option = conf.options;

            var grid = null;

            array.forEach(conf.declare, function (item) {
                declareArray.push(gridMixinNameToConstructor[item]);
            });
            
            var getTableConf = new GetTableColumnWithConfig({name: conf.name});
            
            //if(conf.configName !== 'default'){
                var columnWithConf = getTableConf.getColumn(conf.configName, conf.name);
                option.columns = columnWithConf !== null ? columnWithConf : option.columns;
            //}


            grid = new (declare(declareArray))(option, conf.domNode);

            /*if (gridConfig[name] !== undefined && gridConfig[name] !== null) {

                //noinspection JSDuplicatedDeclaration
                for (var module in gridConfig[name]) {
                    if (gridConfig[name].hasOwnProperty(module)) {
                        declareArray.push(gridMixinNameToConstructor[module]);
                        
                        /!*if (gridConfig[name][module]["before"] !== null &&
                         gridConfig[name][module]["before"] !== undefined) {

                         for (var index in gridConfig[name][module]["before"]) {
                         if (gridConfig[name][module]["before"].hasOwnProperty(index)) {
                         option[index] = gridConfig[name][module]["before"][index];
                         }
                         }
                         }*!/
                    }
                }

                option['collection'] = store;

                grid = new (declare(declareArray))(option, domNode);

                //noinspection JSDuplicatedDeclaration

                /!*for (var module in gridConfig[name]) {
                 if (gridConfig[name].hasOwnProperty(module)) {
                 if (gridMixinNameToConfigure[module] !== null) {
                 gridMixinNameToConfigure[module](grid, gridConfig[name][module]['after']);
                 }
                 }
                 }*!/
            }*/

            return grid;
        }
    }
);