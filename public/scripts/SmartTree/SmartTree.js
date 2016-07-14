/**
 * Created by root on 29.04.16.
 */

define([
    'dojo/_base/declare',
    'dojo/_base/lang',
    'dojo/_base/array',
    'dojo/Deferred',
    'dojo/aspect',
    'dojo/dom',
    'dojo/has',
    'dojo/on',
    'dojo/when',
    "dijit/Tree",
    "dijit/tree/dndSource",
    "dijit",
    "./_StoreMixin"
], function (declare,
             lang,
             array,
             Deferred,
             aspect,
             dom,
             has,
             on,
             when,
             Tree,
             dndSource,
             dijit,
             _StoreMixin) {
    return declare([_StoreMixin], {

        tree: null,

        dnd: null,

        domNodeId: null,

        constructor: function (storeTree, domObjectId) {

            var self = this;

            self.domNodeId = domObjectId;
            self.setStoreTree(storeTree);
        },

        init: function () {
            var self = this;
            var container = dom.byId(self.domNodeId);
            container.innerHTML = '';

            self.dnd = declare([dndSource], {
                checkAcceptance: function (source, nodes) {
                    // summary:
                    //		Checks if the target can accept nodes from this source
                    // source: dijit/tree/dndSource
                    //		The source which provides items
                    // nodes: DOMNode[]
                    //		Array of DOM nodes corresponding to nodes being dropped, dijitTreeRow nodes if
                    //		source is a dijit/Tree.
                    // tags:
                    //		extension

                    return true;
                },

                checkItemAcceptance: function (target, source, position) {
                    var item = dijit.getEnclosingWidget(target);
                    item = item.item;

                    return this.tree.model.mayHaveChildren(item);
                },

                itemCreator: function(nodes, target, source){
                    try{
                        return this.tree.model.itemCreator(nodes, target, source);
                    }catch (e){
                        return array.map(nodes, function(node){
                            return {
                                "id": node.id,
                                "name": node.textContent || node.innerText || ""
                            };
                        });
                    }
                }
            });

            self.tree = new Tree({
                model: self._storeTree,
                openOnDblClick: true,
                autoExpand: true,
                dndController: self.dnd,
                getTooltip: function(item) {
                    try{
                        return this.model.getTooltip(item);
                    }catch (e){
                        return null;
                    }
                },
                getIconClass: function (item, opened) {
                    try{
                        return this.model.getIconClass(item, opened);
                    }catch (e){
                        return (!item || this.model.mayHaveChildren(item)) ? (opened ? "dijitFolderOpened" : "dijitFolderClosed") : "dijitLeaf"
                    }
                }

            });


            self.tree.placeAt(container).startup();
        }
    })
});