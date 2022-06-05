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
    }
};
