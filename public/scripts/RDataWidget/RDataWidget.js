/**
 * Created by root on 23.03.16.
 *
 * scriptList - конфиг со статическими параметрами настроек R скриптов
 * "scripts": {
 * список скриптов
            "plotPublishPrice": {
                имя отчета
                "reportName": "гистограмма цены выставленных товаров",
                подписи осей для графика
                "axis": {
                    "xAxis": "логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "доля выставленных товаров из данного ценового интервала"
                },
                "return": {
                    тип возвращаемых данных
                    "type": "plot",
                    "fieldLabel": {
                        лейблы к возвращаемым параметрам
                        "id": "id",
                        "x": "логарифм по основанию 10 от цены выставленного товара",
                        "y": "доля выставленных товаров из данного ценового интервала"
                    },
                    список возвращаемых параметров
                    "fieldNames": ['id', 'x', "y"]
                },
                список передаваемых параметров
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            }
             "plotPrice": {
                "paramsName": ["prof", "begData", "endData", "ebaycategory_id", "brand"]
                ....
            },
            ....
        },
 "names": ["plotPrice", "plotPublishPrice"]
 *
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
        /**
         * сторе с данными
         */
        store: null,
        /**
         * конфиг со статическими параметрами настроек R скриптов
         */
        scriptList: null,

        /**
        * подписи к кнопкам управления фильтрами
        */
        /*newFilterButton: "new Filter",
        addFilterButton: "add Filter",
        cleanFilterButton: "clear Filter",*/

        /**
         * стили для графига
         */
        chartWidth: null,
        chartHeight: null,
        chartMargin: null,

        /**
         * общее стили виджета
         */
        //style: "div.main {width: 80%;margin: auto;padding: 25px;}div.dgrid {margin-top: 45px;}div#selectScript {float: left;padding-left: 25%;padding-right: 35px;}",

        postCreate: function () {
            var rsript = new scriptSetup(this.scriptList, this.store, {
                width: this.chartWidth,
                height: this.chartHeight,
                margin: this.chartMargin
            });
            this.inherited(arguments);
        }

    });
});