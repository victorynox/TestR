define(['dojo/dom', 'dojo/_base/declare'], function (dom, declare) {
    return declare(null, {
        _oldText: {},
        constructor: function () {

        },
        setText: function(id, text){
            var node = dom.byId(id);
            this._oldText[id] = node.innerHTML;
            node.innerHTML = text;
        },

        restoreText: function(id){
            var node = dom.byId(id);
            node.innerHTML = this._oldText[id];
            delete this._oldText[id];
        }
    });
});
