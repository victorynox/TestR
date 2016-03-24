define(['dojo/dom',
        "dojo/on",
        "dojo/request",
        "dojo/query",
        "dojo/_base/array",
        'dojo/_base/declare',
        "dstore/Memory",
        "dstore/Rest",
        'dstore/Trackable',
        'dstore/Filter',
        "dgrid/OnDemandGrid",
        "dojo/domReady!"],
    function (dom,
              on,
              request,
              query,
              array,
              declare,
              Memory,
              Rest,
              Trackable,
              Filter,
              OnDemandGrid
              ) {
        return declare(null, {
            __dGrid: null,
            __memoryStore: null,
            __data: null,
            __store: null,
            isError: false,

            constructor: function (store, settings) {
                try {
                    var self = this;
                    self.__store = store;

                    var columns = {};

                    array.forEach(settings.return.fieldNames, function (name) {
                        columns[name] = settings.return.fieldLabel[name];
                    });

                    self.__dGrid = new OnDemandGrid({
                        title: settings.title,
                        collection:  self.__store ,//CacheStore.cachingStore,
                        columns: columns,
                        selectionMode: "single"
                    }, "grid");
                }catch (e){
                    this.isError = true
                }
            }
        });

    });