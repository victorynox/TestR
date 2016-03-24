define([], function () {
    return {
        "scripts": {

            "plotPublishPrice": {
                "reportName": "гистограмма цены выставленных товаров",
                "axis": {
                    "xAxis": "логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "количество выставленных товаров из данного ценового интервала"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "логарифм по основанию 10 от цены выставленного товара",
                        "y": "количество выставленных товаров из данного ценового интервала"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotSoldPrice": {
                "reportName": "гистограмма цены проданых товаров",
                "axis": {
                    "xAxis": "логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "количество проданных товаров из данного ценового интервала"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "логарифм по основанию 10 от цены выставленного товара",
                        "y": "количество проданных товаров из данного ценового интервала"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotProbPrice": {
                "reportName": "график вероятности продажи товара из заданной ценовой категории",
                "axis": {
                    "xAxis": "логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "Оценка вероятности продажи товара из данной ценовой категории"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "логарифм по основанию 10 от цены выставленного товара",
                        "y": "Оценка вероятности продажи товара из данной ценовой категории"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotProfPrice": {
                "reportName": "график прибыли от одного выставления товара из заданой ценовой категории",
                "axis": {
                    "xAxis": "логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": " Оценка прибыли от одного выставления товара из заданой ценовой категории"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "логарифм по основанию 10 от цены выставленного товара",
                        "y": " Оценка прибыли от одного выставления товара из заданой ценовой категории"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotPublishDay": {
                "reportName": " гистограмма количества выставлений по дням недели",
                "axis": {
                    "xAxis": "день недели",
                    "yAxis": " количество товаров которые были выставленый в данный день недели"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "день недели",
                        "y": " количество товаров которые были выставленый в данный день недели"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotSoldDay": {
                "reportName": " гистограмма количества породаж в зависимости от дня выставления",
                "axis": {
                    "xAxis": "день недели",
                    "yAxis": "количество проданых товаров которые были выставлены в данный день недели"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "день недели",
                        "y": "количество проданых товаров которые были выставлены в данный день недели"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotProbDay": {
                "reportName": "график вероятности продажи товара выставленного в заданый день недели",
                "axis": {
                    "xAxis": "день недели",
                    "yAxis": "количество проданых товаров которые были выставлены в данный день недели"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "день недели",
                        "y": "оценка вероятности продажи товара выставленного в заданный день недели"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotCreatedDay": {
                "reportName": "гистограмма количества продаж в каждый день недели ",
                "axis": {
                    "xAxis": "день недели",
                    "yAxis": "количество товаров проданых в данный день"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "день недели",
                        "y": "количество товаров проданых в данный день"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotPublishTime": {
                "reportName": "гистограмма количества выставлений в каждый часв течении дня",
                "axis": {
                    "xAxis": "время суток(час)",
                    "yAxis": "количество выставленых в заданый чвс товаров"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "время суток(час)",
                        "y": "количество выставленых в заданый чвс товаров"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotSoldTime": {
                "reportName": "гистограмма количества проданных товаров выставленых в заданое время",
                "axis": {
                    "xAxis": "время суток(час)",
                    "yAxis": "количество проданных товаров которые были выставлены в заданое время"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "время суток(час)",
                        "y": "количество проданных товаров которые были выставлены в заданое время"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotProbTime": {
                "reportName": "график вероятности продажи товара выставленного в заданое время суток",
                "axis": {
                    "xAxis": "время суток(час)",
                    "yAxis": " Оценка вероятности продажи товара выставленого заданое время суток"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "время суток(час)",
                        "y": " Оценка вероятности продажи товара выставленого заданое время суток"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotCreatedTime": {
                "reportName": "гистограмма количетва продаж в каждый час дня",
                "axis": {
                    "xAxis": "время суток(час)",
                    "yAxis": "количество продынных товаров в заданое время суток"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "время суток(час)",
                        "y": "количество продынных товаров в заданое время суток"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotCreatedTimeWithTZ": {
                "reportName": " гистограмма количества продаж в каждый час дня с учетом часовых почсов",
                "axis": {
                    "xAxis": "время суток в штате где выло куплено товар",
                    "yAxis": "количество продынных товаров в заданое время суток с учетом часовых поясов"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": " время суток в штате где выло куплено товар",
                        "y": "количество продынных товаров в заданое время суток с учетом часовых поясов"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tableCategoryPrice": {
                "reportName": "таблица частот по ценовым категориям товаров",

                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id",
                        "category_price": "ценовая категория",
                        "count_sold": "количество проданных",
                        "count_publish": "количество выставленых",
                        "prob": "оценка вероятности продажи",
                        "prof_mounth": "оценка прибыли за месяц",
                        "new_prob": "оценка вероятности продажи при выставлениия на 10 дней",
                        "new_prof_mounth": "оценка прибыли за месяц при выставлении на 10 дней",
                        "delta_prof_mounth": "разница в прибыли"
                    },
                    "fieldNames": [
                        "id",
                        "category_price",
                        "count_sold",
                        "count_publish",
                        "prob",
                        "prof_mounth",
                        "new_prob",
                        "new_prof_mounth",
                        "delta_prof_mounth"
                    ]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tableCategoryID": {
                "reportName": "таблица частот по категориям товаров",

                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "ebaycategory_id": "номер категории",
                        "count_sold": "количество приданных",
                        "count_push": "количество выставленных",
                        "mean_price": "средняя цена по категории",
                        "prob": "оценка вероятности продажи",
                        "prof_mounth": "оценка прибыли за месяц",
                        "new_prob": "оценка вероятности продажи при выставлении на 10 дней",
                        "new_prof_mounth": "оценка прибыли за месяц при выставлении на 10 дней",
                        "delta_prof_mounth": "разница в прибыли"

                    },
                    "fieldNames": [
                        "id",
                        "ebaycategory_id",
                        "count_sold",
                        "count_push",
                        "mean_price",
                        "prob",
                        "prof_mounth",
                        "new_prob",
                        "new_prof_mounth",
                        "delta_prof_mounth"

                    ]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tableProduct": {
                "reportName": "таблица частот по отдельным товарам",

                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "ProductID": "ProductID",
                        "count_sold": "количество проданных",
                        "count_push": "количество выставленных",
                        "price": "цена товара",
                        "prob": "оценка вероятности продажи",
                        "prof_mounth": "оценка прибыли за месяц",
                        "new_prob": "оценка вероятности продажи пир выставлении на 10 дней",
                        "new_prof_mounth": "оценка прибыли за месяц при выставлении на 10 дней",
                        "delta_prof_mounth": "разница прибыли"

                    },
                    "fieldNames": [
                        "id",
                        "ProductID",
                        "count_sold",
                        "count_push",
                        "price",
                        "prob",
                        "prof_mounth",
                        "new_prob",
                        "new_prof_mounth",
                        "delta_prof_mounth"
                    ]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tableModel": {
                "reportName": " таблица популярности каждой из марок мотоциклов",
                "paramsName": [],
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "vehicle_id": "id марки мотоцикла",
                        "count_sold": "количество проданных товаров которые подходят данной марке",
                        "count_publish": "количество выставленных товаров которые подходят данной марке"
                    },
                    "fieldNames": [
                        "id",
                        "vehicle_id",
                        "count_sold",
                        "count_publish"
                    ]
                }
            },

            "tableProductModel": {
                "paramsName": [],
                "reportName": "таблица популярности деталей по маркам которым она подходит",
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "ProductID": "ProductID",
                        "count_model_sold": "количество проданых товаров которы подходят тем же маркам мотоциклов",
                        "count_model_publish": "количество выставленных товаров которые подходят тем же маркам мотоцыклов",
                        "prob": "оценка вероятности продажи товара который подходит тем же маркам мотоциклов"
                    },
                    "fieldNames": [
                        "id",
                        "ProductID",
                        "count_model_sold",
                        "count_model_publish",
                        "prob"
                    ]
                }
            }
        },
        "names": [
            "plotPublishPrice",
            "plotSoldPrice",
            "plotProbPrice",
            "plotProfPrice",
            "plotPublishDay",
            "plotSoldDay",
            "plotProbDay",
            "plotCreatedDay",
            "plotPublishTime",
            "plotSoldTime",
            "plotProbTime",
            "plotCreatedTime",
            "plotCreatedTimeWithTZ",
            "tableCategoryPrice",
            "tableCategoryID",
            "tableProduct",
            "tableModel",
            "tableProductModel"
        ]
    };
});