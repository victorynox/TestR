/**
 * Created by root on 07.06.16.
 */
define([
    'dojo/_base/declare',
    'dojo/_base/array',
    'dojo/_base/lang',
    'dojo/aspect',
    'dojo/dom-construct',
    'dojo/dom-class',
    'dojo/on',
    'dojo/query',
    'dojo/string',
    'dojo/has',
    'dojo/when',
    'dgrid/_StoreMixin',
], function (declare,
             array,
             lang,
             aspect,
             domConstruct,
             domClass,
             on,
             query,
             string,
             has,
             when,
             _StoreMixin) {
    var BreakException = {};
    return declare(_StoreMixin, {

        benchmarkColumns: null,
        newReceivedColumns: null,
        rqlFilter: null,

        _observeCollection: function (collection, container, options) {
            var self = this,
                rows = options.rows,
                row;
            self.benchmarkColumns = [];

            for(var i in self.columns){
                if(self.columns.hasOwnProperty(i)){
                    var column = {};
                    for(var property in self.columns[i]){
                        if(self.columns[i].hasOwnProperty(property)){
                            if(property !== 'grid' &&
                                property !== 'headerNode' ){
                               column[property] = self.columns[i][property];
                            }
                        }
                    }
                    self.benchmarkColumns.push(column);
                }
            }

            var handles = [
                collection.on('delete, update', function (event) {
                    var from = event.previousIndex;
                    var to = event.index;

                    if (from !== undefined && rows[from]) {
                        if ('max' in rows && (to === undefined || to < rows.min || to > rows.max)) {
                            rows.max--;
                        }

                        row = rows[from];

                        // check to make the sure the node is still there before we try to remove it
                        // (in case it was moved to a different place in the DOM)
                        if (row.parentNode === container) {
                            self.removeRow(row, false, options);
                        }

                        // remove the old slot
                        rows.splice(from, 1);

                        if (event.type === 'delete' ||
                            (event.type === 'update' && (from < to || to === undefined))) {
                            // adjust the rowIndex so adjustRowIndices has the right starting point
                            rows[from] && rows[from].rowIndex--;
                        }
                    }
                    if (event.type === 'delete') {
                        // Reset row in case this is later followed by an add;
                        // only update events should retain the row variable below
                        row = null;
                    }
                }),

                collection.on('add, update', function (event) {
                    var from = event.previousIndex;
                    var to = event.index;
                    var nextNode;

                    function advanceNext() {
                        nextNode = (nextNode.connected || nextNode).nextSibling;
                    }

                    // When possible, restrict observations to the actually rendered range
                    if (to !== undefined && (!('max' in rows) || (to >= rows.min && to <= rows.max))) {
                        if ('max' in rows && (from === undefined || from < rows.min || from > rows.max)) {
                            rows.max++;
                        }
                        // Add to new slot (either before an existing row, or at the end)
                        // First determine the DOM node that this should be placed before.
                        if (rows.length) {
                            nextNode = rows[to];
                            if (!nextNode) {
                                nextNode = rows[to - 1];
                                if (nextNode) {
                                    // Make sure to skip connected nodes, so we don't accidentally
                                    // insert a row in between a parent and its children.
                                    advanceNext();
                                }
                            }
                        }
                        else {
                            // There are no rows.  Allow for subclasses to insert new rows somewhere other than
                            // at the end of the parent node.
                            nextNode = self._getFirstRowSibling && self._getFirstRowSibling(container);
                        }
                        // Make sure we don't trip over a stale reference to a
                        // node that was removed, or try to place a node before
                        // itself (due to overlapped queries)
                        if (row && nextNode && row.id === nextNode.id) {
                            advanceNext();
                        }
                        if (nextNode && !nextNode.parentNode) {
                            nextNode = document.getElementById(nextNode.id);
                        }
                        rows.splice(to, 0, undefined);
                        row = self.insertRow(event.target, container, nextNode, to, options);
                        self.highlightRow(row);
                    }
                    // Reset row so it doesn't get reused on the next event
                    row = null;
                }),

                collection.on('add, delete, update', function (event) {
                    var from = (typeof event.previousIndex !== 'undefined') ? event.previousIndex : Infinity,
                        to = (typeof event.index !== 'undefined') ? event.index : Infinity,
                        adjustAtIndex = Math.min(from, to);
                    from !== to && rows[adjustAtIndex] && self.adjustRowIndices(rows[adjustAtIndex]);

                    // the removal of rows could cause us to need to page in more items
                    if (from !== Infinity && self._processScroll && (rows[from] || rows[from - 1])) {
                        self._processScroll();
                    }

                    // Fire _onNotification, even for out-of-viewport notifications,
                    // since some things may still need to update (e.g. Pagination's status/navigation)
                    self._onNotification(rows, event, collection);

                    // Update _total after _onNotification so that it can potentially
                    // decide whether to perform actions based on whether the total changed
                    if (collection === self._renderedCollection && 'totalLength' in event) {
                        self._total = event.totalLength;
                    }
                }),
                aspect.around(this.collection, '_renderUrl', lang.hitch(this, function (originFetchRange) {
                    var self = this;
                    return function (requestParams) {
                        var kwArgs = {};

                        kwArgs.rql = self.rqlFilter;
                        kwArgs.requestParams = requestParams;

                        return originFetchRange.call(self.collection, kwArgs);
                    }
                })),
                collection.on('data-received', function (e) {
                    if (e.parsedResponse !== undefined) {
                        var resp = e.parsedResponse;

                        resp.then(function (objects) {
                            //todo objects массив с пришедшими элементами. достать колонки из первого элемента и заменить их в таблице
                            var item = objects[0];

                            var fieldsName = [];
                            var newColumns = [];

                            for (var index in item) {
                                if (item.hasOwnProperty(index)) {
                                    fieldsName.push(index);
                                }
                            }


                            for (var i in fieldsName) {
                                if (fieldsName.hasOwnProperty(i)) {
                                    var name = fieldsName[i];
                                    var isSet = false;
                                    for (var index in self.benchmarkColumns) {
                                        if (self.benchmarkColumns.hasOwnProperty(index)) {
                                            var column = self.benchmarkColumns[index];
                                            if (name === column.field) {
                                                isSet = true;
                                                var col = {};
                                                for(var property in column){
                                                    if(column.hasOwnProperty(property)){
                                                        if(property !== 'grid' && property !== 'headerNode'){
                                                            col[property] = column[property];
                                                        }
                                                    }
                                                }
                                                newColumns.push(col);
                                                break;
                                            } else {
                                                var regexp = new RegExp("^" + column.field + "->([\w\W]+)");
                                                var func= regexp.exec(name);
                                                if (func) {
                                                    isSet = true;
                                                    newColumns.push({
                                                        label: column.label + " " + func[1],
                                                        field: column.field,
                                                    });
                                                    break;
                                                }
                                            }
                                        }
                                    }

                                    if (!isSet) {
                                        newColumns.push( {
                                            label: name,
                                            field: name
                                        });
                                    }
                                }
                            }
                            self.newReceivedColumns = newColumns;
                            //self.set('columns', newColumns);
                        });
                    }
                })
            ];

            return {
                remove: function () {
                    while (handles.length > 0) {
                        handles.pop().remove();
                    }
                }
            };
        },

        setRqlFilter: function (rqlFilter) {
            this.rqlFilter = rqlFilter
        }
    });
});


/*

 */