define([], function () {
    var scriptList = {
        "scripts": {
            "plotPrice": {
                "get": {
                    "prof": {
                        "label": "Процентаня ставка",
                        "formType": "TextBox",
                        "type": "float",
                        "required": true
                    },
                    "begData": {
                        "label": "Дата начала выборки",
                        "format": "Y-m-d TH:iP",
                        "formType": "DateTextBox",
                        "type": "string",
                        "required": true
                    },
                    "endData": {
                        "label": "Дата окончания выборки",
                        "formType": "DateTextBox",
                        "type": "string",
                        "required": true
                    },
                    "ebaycategory_id": {
                        "label": "Номер категории",
                        "formType": "TextBox",
                        "type": "int",
                        "required": false
                    },
                    "brand": {"label": "Бренд", "formType": "TextBox", "type": "string", "required": false}
                },
                "paramsName": ["prof", "begData", "endData", "ebaycategory_id", "brand"]
            },
            "plotPublishPrice": {
                "get": {
                    "brand": {
                        "name":"brand",
                        "label": "Бренд",
                        "getData":"getBrand",
                        "formType": "Select",
                        "type": "string",
                        "required": false
                    },
                    "ebaycategory_id": {
                        "label": "Номер категории",
                        "formType": "TextBox",
                        "type": "int",
                        "required": false
                    },
                    "begadd_date": {
                        "label": "Дата начала выборки",
                        "format": "Y-m-d TH:iP",
                        "formType": "DateTextBox",
                        "type": "string",
                        "required": true
                    },
                    "endadd_date": {
                        "label": "Дата окончания выборки",
                        "format": "Y-m-d TH:iP",
                        "formType": "DateTextBox",
                        "type": "string",
                        "required": true
                    }
                },
                "reportName": "гистограмма цены выставленных товаров",
                "axis": {"xAxis": "логарифм по основанию 10 от цены выставленного товара", "yAxis": "доля выставленных товаров из данного ценового интервала"},
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            }
        }, "names": ["plotPrice", "plotPublishPrice"]
    };
    return scriptList;

});