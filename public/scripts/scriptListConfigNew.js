define([], function () {
    return {
        "scripts": {
            "plotPrice": {
                "paramsName": ["prof", "begData", "endData", "ebaycategory_id", "brand"]
                //TODO добавить скрипты
            },
            "plotPublishPrice": {
                "reportName": "гистограмма цены выставленных товаров",
                "axis": {
                    "xAxis": "логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "доля выставленных товаров из данного ценового интервала"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "логарифм по основанию 10 от цены выставленного товара",
                        "y": "доля выставленных товаров из данного ценового интервала"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            }
        }, "names": ["plotPrice", "plotPublishPrice"]
    };

});