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
            //__filter: null,
            //__filteredMemoryStore: null,
            __data: null,
            __store: null,
            constructor: function (store, settings) {
                var self = this;
                self.__store = store;
                
                var columns3 = {
                    id: 'id',
                    x: settings.axis.xAxis ? settings.axis.xAxis : 'x',
                    y: settings.axis.yAxis ? settings.axis.yAxis : 'y'
                };

                self.__dGrid = new OnDemandGrid({
                    title: settings.title,
                    collection:  self.__store ,//CacheStore.cachingStore,
                    columns: columns3,
                    selectionMode: "single"
                }, "grid");


                /*on(document.getElementById("filterButton"), "click", function () {
                    if(!self.__filter){
                        self.__filter  = new Filter().gt('x', 1.6);
                        self.__filteredMemoryStore = self.__store.filter(self.__filter )
                    }else{
                        self.__filter = self.__filter.lt('x', 2);
                        self.__filteredMemoryStore = self.__filteredMemoryStore.filter(self.__filter )
                    }

                    self.__dGrid.set('collection', self.__filteredMemoryStore);
                });

                on(document.getElementById("cleanFilterButton"), "click", function () {
                    //self.__filter = self.__memoryStore.Filter().lg('x', 1.6);\
                    self.__filter = null;
                    self.__dGrid.set('collection',  self.__store);
                });*/

                //self.__dGrid.startup();
            }
        });

    });