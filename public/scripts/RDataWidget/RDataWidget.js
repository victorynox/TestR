/**
 * Created by root on 23.03.16.
 */
define([
    "dojo/_base/declare",
    "dijit/_Widget",
    "dijit/_TemplatedMixin",
    "./src/scriptSetup",
    "./src/plotCreate",
    "./src/formCreate",
    "./src/dgredCreate",
    "dojo/text!./templates/RDataWidget.html",
], function (declare,
             _Widget,
             _TemplatedMixin,
             scriptSetup,
             plotCreate,
             formCreate,
             dgredCreate,
             template) {
    return declare([_Widget, _TemplatedMixin], {
        templateString: template,
        store: null,
        scriptList: null,

        postCreate: function () {
            var rsript = new scriptSetup(this.scriptList, this.store);
        }

    });
});