window._adm = {
    order : {
        data : {},
        fun_save : undefined,
        init : function (fun_save) {
            _adm.order.fun_save = fun_save;
        },
        insert : function (input) {
            _adm.order.data[input.id] = input;
        },
        get : function (key) {
            return _adm.order.data[key];
        },
        save : function (id) {
            return _adm.order.fun_save(this.data[id]);
        }
    }
};
