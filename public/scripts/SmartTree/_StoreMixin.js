/**
 * Created by root on 29.04.16.
 */

define([
    'dojo/_base/declare',
    'dojo/_base/lang',
    'dojo/_base/array',
    'dojo/Deferred',
    'dojo/aspect',
    'dojo/dom-construct',
    'dojo/has',
    'dojo/on',
    'dojo/when'
], function (declare,
             lang,
             array,
             Deferred,
             aspect,
             domConstruct,
             has,
             on,
             when) {

    return declare(null, {

        // _observerHandle: Object
        //		The observer handle for the current collection, if trackable.
        _observerHandle: null,

        __notificationStack: [],

        __reloadTimeout: 15,

        _storeTree: null,

        _observeStoreTree: function (storeTree, tree, options) {
            var self = this;
            var handles = [

                storeTree.on('update', function (event) {
                    tree._onItemChange(event.target);
                }),

                storeTree.on('add, update', function (event) {
                    storeTree.get(event.target.parentID).then(function (item) {
                        storeTree.getChildren(item, function (objects) {
                            tree._onItemChildrenChange(item, objects);
                        });
                    });

                }),

                storeTree.on('delete', function (event) {
                    tree._onItemDelete(event.target);
                })
            ];

            return {
                remove: function () {
                    while (handles.length > 0) {
                        handles.pop().remove();
                    }
                }
            }
        },

        setStoreTree: function (storeTree) {
            var self = this;
            self._storeTree = storeTree;
            self.init();
            self.own(self._storeTree);
            self._observerHandle = self._observeStoreTree(self._storeTree, self.tree);
        },

        _setStore: function (storeTree) {
            if (!this.storeTree) {
                console.debug('set(\'store\') call detected, but you probably meant set(\'StoreTree\')');
            }
        },

        own: function (storeTree) {
            var self = this;
            aspect.around(storeTree, 'add', function (originalPut) {
                return function (obj, options) {

                    if (options && options.parent) {
                        obj.parentID = options.parent.id;

                        var data = storeTree.data;
                        var max = 0;
                        data.forEach(function (element) {
                            if (max < element.id) {
                                max = element.id;
                            }
                        });

                        obj.id = max + 1;
                    }

                    return originalPut.call(storeTree, obj, options);
                }
            });

            aspect.around(storeTree, 'put', function (originalPut) {
                return function (obj, options) {
                    if (options && options.parent && options.oldParent){
                        if(obj.parentID !== options.parent.id){
                            obj.parentID = options.parent.id;
                            
                        }
                    }else if (options && options.parent) {
                        if (obj.id === null || obj.id === -1 || obj.id === undefined) {
                            obj.parentID = options.parent.id;

                            var data = storeTree.data;
                            var index = [];
                            data.forEach(function (element) {
                                index.push(element.id);
                            });
                            index.sort();
                            obj.id = index[index.length - 1] + 1;
                        }
                    }

                    return originalPut.call(storeTree, obj, options);
                }
            });

            storeTree.newItem =  function(/* dijit/tree/dndSource.__Item */ args, /*Item*/ parent, /*int?*/ insertIndex, /*Item*/ before){
                // summary:
                //		Creates a new item.   See `dojo/data/api/Write` for details on args.
                //		Used in drag & drop when item from external source dropped onto tree.

                return storeTree.add(args, {
                    parent: parent,
                    before: before
                });
            };

            storeTree.pasteItem = function(/*Item*/ childItem, /*Item*/ oldParentItem, /*Item*/ newParentItem,
                                           /*Boolean*/ bCopy, /*int?*/ insertIndex, /*Item*/ before){
                // summary:
                //		Move or copy an item from one parent item to another.
                //		Used in drag & drop.

                var d = new Deferred();

                if(oldParentItem === newParentItem && !bCopy && !before){
                    // Avoid problem when items visually disappear when dropped onto their parent.
                    // Happens because the (no-op) store.put() call doesn't generate any notification
                    // that the childItem was added/moved.
                    d.resolve(true);
                    return d;
                }

                if(oldParentItem && !bCopy){
                    // In order for DnD moves to work correctly, childItem needs to be orphaned from oldParentItem
                    // before being adopted by newParentItem.   That way, the TreeNode is moved rather than
                    // an additional TreeNode being created, and the old TreeNode subsequently being deleted.
                    // The latter loses information such as selection and opened/closed children TreeNodes.
                    // Unfortunately simply calling this.store.put() will send notifications in a random order, based
                    // on when the TreeNodes in question originally appeared, and not based on the drag-from
                    // TreeNode vs. the drop-onto TreeNode.

                    storeTree.getChildren(oldParentItem, lang.hitch(this, function(oldParentChildren){
                        oldParentChildren = [].concat(oldParentChildren); // concat to make copy
                        var index = array.indexOf(oldParentChildren, childItem);
                        oldParentChildren.splice(index, 1);
                        self.tree._onItemChildrenChange(oldParentItem, oldParentChildren);

                        d.resolve(storeTree.put(childItem, {
                            overwrite: true,
                            parent: newParentItem,
                            oldParent: oldParentItem,
                            before: before
                        }));

                    }));
                }else{

                    var newItem = lang.clone(childItem);
                    d.resolve(storeTree.add(newItem, {
                        parent: newParentItem,
                    }));
                }

                return d;
            };
        }


    });
});