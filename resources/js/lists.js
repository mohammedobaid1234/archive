let GLOBALS = {
    options: {},
    lists: {
       
        roles: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="roles"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/users/roles",
                formatters: {
                    option: {
                        // value: "id",
                        value: "name",
                        title: "label"
                    }
                }
            });
        },
        employees_roles: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="employees_roles"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/users/roles/employees",
                formatters: {
                    option: {
                        value: "name",
                        title: "label"
                    }
                }
            });
        },
        employees: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="employees"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/employees",
                ajax: true,
                formatters: {
                    option: {
                        title: "full_name"
                    }
                }
            });
        },
        
        
        dayes: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="dayes"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 6, name: "السبت"},
                    {id: 0, name: "الأحد"},
                    {id: 1, name: "الاثنين"},
                    {id: 2, name: "الثلاثاء"},
                    {id: 3, name: "الأربعاء"},
                    {id: 4, name: "الخميس"},
                    {id: 5, name: "الجمعة"},
                ]
            });
        },
        gender: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="gender"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: "male", name: "ذكر"},
                    {id: "female", name: "أنثى"}
                ]
            });
        },
        customer_types: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="customer_types"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 'شخصي', name: "شخصي"},
                    {id: 'تاجر', name: "تاجر"},
                    {id: 'شركة', name: "شركة"}
                ]
            });
        },
        
        provinces: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="provinces"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/countries/provinces",
                formatters: {
                    option: {
                        title: "full_name"
                    }
                },
                ajax: true
            });
        },
        categories_of_contracts: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="categories_of_contracts"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/categories_of_contracts",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "name"
                    }
                }
            });
        },
        customers: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="customers"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/customers",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "full_name"
                    }
                }
            });
        },
        employees: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="employees"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/employees",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "full_name"
                    }
                }
            });
        },
        categoriesOfProducts: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="categoriesOfProducts"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/categories",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "name"
                    }
                }
            });
        },
        currencies: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="currencies"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/currencies",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "name"
                    }
                }
            });
        },
        banks: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="banks"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/banks",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "name"
                    }
                }
            });
        },
        products: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="products"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/products",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "name"
                    }
                }
            });
        },
        
        payment_methods: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="payment_methods"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 'نقدا', name: "نقدا"},
                    {id: 'شيك', name: "شيك"},
                ]
            });
        },
        teams: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="teams"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/teams",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "name"
                    }
                }
            });
        },
        cars: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="cars"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/cars",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "type" 
                    }
                }
            });
        },
        departments: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="departments"]');
            }

            $(element).briskSelectOptions({
                resource: $("meta[name='BASE_URL']").attr("content") + "/departments",
                ajax: true,
                formatters: {
                    option: {
                        value: "id",
                        title: "label" 
                    }
                }
            });
        },
        type_in_team: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="type_in_team"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 'مسؤول', name: "مسؤول"},
                    {id: 'عضو', name: "عضو"},
                ]
            });
        },
        type_of_checks: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="type_of_checks"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 'صادر', name: "صادر"},
                    {id: 'وارد', name: "وارد"},
                ]
            });
        },
        type_in_papers: function (element) {
            if (element === undefined) {
                element = $('[data-options_source="type_in_papers"]');
            }

            $(element).briskSelectOptions({
                options: [
                    {id: 'تأمين', name: "تأمين"},
                    {id: 'رخصة_سائق', name: "رخصة_سائق"},
                    {id: 'رخصة_سيارة', name: "رخصة_سيارة"},
                ]
            });
        },
    }
};
