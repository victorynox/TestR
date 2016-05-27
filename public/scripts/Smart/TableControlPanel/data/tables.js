/**
 * Created by root on 25.05.16.
 */
define([], function () {
    return {
        "notification": {
            "title": "Уведомления Ebay",
            "name": "notification",
            "filteredGridOption": {
                "store": {
                    "options": {
                        "target": "/rest/ebay_notification"
                        //"data": null
                    },
                    "declare": [
                        "Rest",
                        "RequestMemory",
                        "Trackable"
                    ]
                },
                "grid": {
                    "options": {
                        "columns": [
                            {"label": "id", "field": "id"},
                            {"label": "Дата создания", "field": "add_date"},
                            {"label": "Тип уведомления", "field": "soapaction"}
                        ],
                        "selectionMode": "single",
                        "pagingLinks": false,
                        "pagingTextBox": true,
                        "firstLastArrows": true,
                        "rowsPerPage": 15,
                        "pageSizeOptions": [10, 15, 25]
                    },
                    "declare": [
                        "Grid",
                        "Keyboard",
                        "Selection",
                        "Pagination",
                        "ColumnHider",
                        "ColumnResizer",
                        "_StoreMixin"
                    ]
                }

            },
            "filterControlPanelOption": {
                "store": {
                    "options": {
                        "target": "/rest/filters_list"
                        //"data": null,
                    },
                    "declare": [
                        "Rest",
                        "RequestMemory",
                        "Trackable"
                    ]
                },

                "options": {
                    "columns": [
                        {"label": "id", "field": "id"},
                        {
                            "label": "Название",
                            "field": "name",
                            "editor": "text",
                            "editOn": "dblclick",
                            "autoSave": true
                        }
                    ],
                    "filteredStoreDataOption": [
                        {
                            "label": "Тип уведомления",
                            "value": {
                                "type": "string",
                                "name": "soapaction",
                                "field": {
                                    'type': "Select",
                                    'option': [
                                        {"id": 0, "label": "ItemListed", "value": "ItemListed"},
                                        {"id": 1, "label": "ERROR", "value": "ERROR"}
                                    ]
                                }
                            },
                            "filter": [
                                {"id": 0, "label": "=", "value": "eq"},
                                {"id": 0, "label": "!=", "value": "ne"}
                            ]
                        },
                        {
                            "label": "Дата создания",
                            "value": {
                                "type": "string",
                                "name": "add_date",
                                "field": {
                                    'type': "TextBox",
                                }
                            },
                            "filter": [
                                {"id": 0, "label": "=", "value": "eq"},
                                {"id": 0, "label": ">", "value": "gt"},
                                {"id": 0, "label": "<", "value": "lt"},
                                {"id": 0, "label": ">=", "value": "gte"},
                                {"id": 0, "label": "<=", "value": "lte"},
                                {"id": 0, "label": "!=", "value": "ne"}
                            ]
                        }
                    ]
                }

            }
        }
    }
});