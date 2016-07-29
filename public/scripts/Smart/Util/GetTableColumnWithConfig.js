/**
 * Created by root on 27.05.16.
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
        'dstore/Store',
        'dstore/Rest',
        'dstore/RequestMemory',
        'dstore/Trackable',
        'dstore/Filter'
    ],
    function (declare,
              lang,
              array,
              dom,
              domConstruct,
              on,
              parser,
              Store,
              Rest,
              RequestMemory,
              Trackable,
              Filter) {
        var configStore = new (declare([Rest, RequestMemory, Trackable]))({
            target: "/rest/tablePreferenceList"
        });

        function a(name, tableName) {
            var columns = null;
            var filter = (new Filter()).and((new Filter()).eq("tableName", tableName), (new Filter()).eq("name", name));
            configStore.filter(filter).then(function (item) {
                columns = item.preference;
            });
            return columns;
        }

        return declare([], {
            name: null,
            _store: configStore,

            constructor: function (object) {
                var self = this;

                if (object !== null && object !== undefined) {
                    for (var index in object) {
                        if (object.hasOwnProperty(index)) {
                            self[index] = object[index];
                        }
                    }
                }
            },

            getColumn: function (name, tableName) {
                var columns = null;
                var filter = (new Filter()).and((new Filter()).eq("tableName", tableName), (new Filter()).eq("name", name));
                
                configStore.filter(filter).forEach(function (item) {
                    columns = item.preference;
                });
                
                return columns;
            },

            setStore: function (store) {
                var self = this;
                if(store instanceof Store){
                    self._store = store;
                }
            },

            getStore: function () {
                var self = this;
                return self._store;
            }
        });
    }
);