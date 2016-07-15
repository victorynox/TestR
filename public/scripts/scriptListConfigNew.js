define([], function () {
    return {
        "scripts": {

            "plotPublishPrice": {
                "reportName": "Гистограмма цены выставленных товаров",
                "axis": {
                    "xAxis": "Логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "Количество выставленных товаров из данного ценового интервала"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Логарифм по основанию 10 от цены выставленного товара",
                        "y": "Количество выставленных товаров из данного ценового интервала"
                    },

                    "fields": {
                        "label": {
                            "id": "id",
                            "x": "Логарифм по основанию 10 от цены выставленного товара",
                            "y": "Количество выставленных товаров из данного ценового интервала"
                        },
                        'type':{
                            "id": "int",
                            "x": "float",
                            "y": "int"
                        }
                    },
                    
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotSoldPrice": {
                "reportName": "Гистограмма цены проданых товаров",
                "axis": {
                    "xAxis": "Логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "Количество проданных товаров из данного ценового интервала"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Логарифм по основанию 10 от цены выставленного товара",
                        "y": "Количество проданных товаров из данного ценового интервала"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotProbPrice": {
                "reportName": "График вероятности продажи товара из заданной ценовой категории",
                "axis": {
                    "xAxis": "Логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "Оценка вероятности продажи товара из данной ценовой категории"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Логарифм по основанию 10 от цены выставленного товара",
                        "y": "Оценка вероятности продажи товара из данной ценовой категории"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotProfPrice": {
                "reportName": "График прибыли от одного выставления товара из заданой ценовой категории",
                "axis": {
                    "xAxis": "Логарифм по основанию 10 от цены выставленного товара",
                    "yAxis": "Оценка прибыли от одного выставления товара из заданой ценовой категории"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Логарифм по основанию 10 от цены выставленного товара",
                        "y": " Оценка прибыли от одного выставления товара из заданой ценовой категории"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotPublishDay": {
                "reportName": "Гистограмма количества выставлений по дням недели",
                "axis": {
                    "xAxis": "День недели",
                    "yAxis": "Количество товаров которые были выставленый в данный день недели"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "День недели",
                        "y": "Количество товаров которые были выставленый в данный день недели"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotSoldDay": {
                "reportName": "Гистограмма количества породаж в зависимости от дня выставления",
                "axis": {
                    "xAxis": "День недели",
                    "yAxis": "Количество проданых товаров которые были выставлены в данный день недели"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "День недели",
                        "y": "Количество проданых товаров которые были выставлены в данный день недели"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotProbDay": {
                "reportName": "График вероятности продажи товара выставленного в заданый день недели",
                "axis": {
                    "xAxis": "День недели",
                    "yAxis": "Количество проданых товаров которые были выставлены в данный день недели"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "День недели",
                        "y": "Оценка вероятности продажи товара выставленного в заданный день недели"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotCreatedDay": {
                "reportName": "Гистограмма количества продаж в каждый день недели ",
                "axis": {
                    "xAxis": "День недели",
                    "yAxis": "Количество товаров проданых в данный день"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "День недели",
                        "y": "Количество товаров проданых в данный день"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotPublishTime": {
                "reportName": "Гистограмма количества выставлений в каждый часв течении дня",
                "axis": {
                    "xAxis": "Время суток(час)",
                    "yAxis": "Количество выставленых в заданый чвс товаров"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Время суток(час)",
                        "y": "Количество выставленых в заданый чвс товаров"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotSoldTime": {
                "reportName": "Гистограмма количества проданных товаров выставленых в заданое время",
                "axis": {
                    "xAxis": "Время суток(час)",
                    "yAxis": "Количество проданных товаров которые были выставлены в заданое время"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Время суток(час)",
                        "y": "Количество проданных товаров которые были выставлены в заданое время"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotProbTime": {
                "reportName": "График вероятности продажи товара выставленного в заданое время суток",
                "axis": {
                    "xAxis": "Время суток(час)",
                    "yAxis": "Оценка вероятности продажи товара выставленого заданое время суток"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Время суток(час)",
                        "y": " Оценка вероятности продажи товара выставленого заданое время суток"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotCreatedTime": {
                "reportName": "Гистограмма количетва продаж в каждый час дня",
                "axis": {
                    "xAxis": "Время суток(час)",
                    "yAxis": "Количество продынных товаров в заданое время суток"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Время суток(час)",
                        "y": "Количество продынных товаров в заданое время суток"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "plotCreatedTimeWithTZ": {
                "reportName": "Гистограмма количества продаж в каждый час дня с учетом часовых почсов",
                "axis": {
                    "xAxis": "Время суток в штате где выло куплено товар",
                    "yAxis": "Количество продынных товаров в заданое время суток с учетом часовых поясов"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Время суток в штате где выло куплено товар",
                        "y": "Количество продынных товаров в заданое время суток с учетом часовых поясов"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tableCategoryPrice": {
                "reportName": "Таблица частот по ценовым категориям товаров",

                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id",
                        "category_price": "Ценовая категория",
                        "count_sold": "Количество проданных",
                        "count_push": "Количество выставленых",
                        "prob": "Оценка вероятности продажи",
                        "prof_mounth": "Оценка прибыли за месяц",
                        "new_prob": "Оценка вероятности продажи при выставлениия на 10 дней",
                        "new_prof_mounth": "Оценка прибыли за месяц при выставлении на 10 дней",
                        "delta_prof_mounth": "Разница в прибыли"
                    },
                    "fieldNames": [
                        "id",
                        "category_price",
                        "count_sold",
                        "count_push",
                        "prob",
                        "prof_mounth",
                        "new_prob",
                        "new_prof_mounth",
                        "delta_prof_mounth"
                    ]
                },
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tableCategory": {
                "reportName": "Таблица частот по категориям товаров",
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "ebaycategory_id": "Номер категории",
                        "count_sold": "Количество приданных",
                        "count_push": "Количество выставленных",
                        "mean_price": "Средняя цена по категории",
                        "prob": "Оценка вероятности продажи",
                        "prof_mounth": "Оценка прибыли за месяц",
                        "new_prob": "Оценка вероятности продажи при выставлении на 10 дней",
                        "new_prof_mounth": "Оценка прибыли за месяц при выставлении на 10 дней",
                        "delta_prof_mounth": "Разница в прибыли"

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
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tableProduct": {
                "reportName": "Таблица частот по отдельным товарам",

                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "ProductID": "ProductID",
                        "count_sold": "Количество проданных",
                        "count_push": "Количество выставленных",
                        "price": "Цена товара",
                        "prob": "Оценка вероятности продажи",
                        "prof_mounth": "Оценка прибыли за месяц",
                        "new_prob": "Оценка вероятности продажи при выставлении на 10 дней",
                        "new_prof_mounth": "Оценка прибыли за месяц при выставлении на 10 дней",
                        "delta_prof_mounth": "Разница прибыли"

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
                "paramsName": ["brand", "likeebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tableModel": {
                "reportName": "Таблица популярности каждой из марок мотоциклов",
                "paramsName": [],
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "vehicle": "Марка мотоцикла",
                        "vehicle_id": "id марки мотоцикла",
                        "count_sold": "Количество проданных товаров которые подходят данной марке",
                        "count_publish": "Количество выставленных товаров которые подходят данной марке"
                    },
                    "fieldNames": [
                        "id",
                        "vehicle",
                        "vehicle_id",
                        "count_sold",
                        "count_publish"
                    ]
                }
            },

            "tableProductModel": {
                "paramsName": [],
                "reportName": "Таблица популярности деталей по маркам которым она подходит",
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "ProductID": "ProductID",
                        "count_model_sold": "Количество проданых товаров которы подходят тем же маркам мотоциклов",
                        "count_model_publish": "Количество выставленных товаров которые подходят тем же маркам мотоцыклов",
                        "prob": "Оценка вероятности продажи товара который подходит тем же маркам мотоциклов"
                    },
                    "fieldNames": [
                        "id",
                        "ProductID",
                        "count_model_sold",
                        "count_model_publish",
                        "prob"
                    ]
                }
            },

            "bestProducts": {
                "paramsName": [],
                "reportName": "Список товаров которые продались больше всего на ebay",
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "title": "Тайтл товара",
                        "count_sold": "Количество продаж данного товара начиная с 2016-01-22",
                    },
                    "fieldNames": [
                        "id",
                        "title",
                        "count_sold",
                    ]
                }
            },

            "bestCompetitor": {
                "paramsName": [],
                "reportName": "Список продавцов и количество продаж этих продавцов",
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id строки",
                        "seller_name": "Ник продавца",
                        "count_sold": "Количество продаж данного товара начиная с 2016-01-22",
                    },
                    "fieldNames": [
                        "id",
                        "seller_name",
                        "count_sold",
                    ]
                }
            },

            "NN": {
                "reportName": "Гистограмма цен выставленных товаров с задаными словами",
                "axis": {
                    "xAxis": "Логарифм по основанию 10 от цены товара",
                    "yAxis": "Количество продаж"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Логарифм по основанию 10 от цены товара",
                        "y": "Количество продаж"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["liketitle", "begadd_date", "endadd_date"]
            },

            "NNSold": {
                "reportName": "Гистограмма цен проданых товаров с задаными словами",
                "axis": {
                    "xAxis": "логарифм по основанию 10 от цены товара",
                    "yAxis": "Количество продаж"
                },
                "return": {
                    "type": "plot",
                    "fieldLabel": {
                        "id": "id",
                        "x": "Логарифм по основанию 10 от цены товара",
                        "y": "Количество продаж"
                    },
                    "fieldNames": ['id', 'x', "y"]
                },
                "paramsName": ["liketitle", "begadd_date", "endadd_date"]
            },

            "soldProduct": {
                "reportName": "Таблица количества продаж каждого из товаров",
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id",
                        "ProductID": "ProductID",
                        "count_sold": "Количество продаж"
                    },
                    "fieldNames": ['id', 'ProductID', "count_sold"]
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "sold_view": {
                "reportName": "Таблица соотношений покупок и просмотров лотов",
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id",
                        "count_sold": "Количество продаж",
                        "count_view": "Количество просмотров лота",
                        "prob": "Вероятность продажи товара на который зашли"
                    },
                    "fieldNames": ['id', 'count_sold', "count_view", 'prob']
                },
                "paramsName": ["brand", "ebaycategory_id", "begadd_date", "endadd_date"]
            },

            "tablePublishTime": {
                "reportName": "Таблица количества выставлений на каждый час недели",
                "return": {
                    "type": "table",
                    "fieldLabel": {
                        "id": "id",
                        "Monday": "Monday",
                        "Tuesday": "Tuesday",
                        "Wednesday": "Wednesday",
                        "Thursday": "Thursday",
                        "Friday": "Friday",
                        "Saturday": "Saturday",
                        "Sunday": "Sunday"
                    },
                    "fieldNames": ['id',"Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]
                },
                "paramsName": []
            },

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
            "tableCategory",
            "tableProduct",
            "tableModel",
            "tableProductModel",
            "bestProducts",
            "bestCompetitor",
            "NN",
            "NNSold",
            "soldProduct",
            "sold_view",
            "tablePublishTime"
        ]
    };
});