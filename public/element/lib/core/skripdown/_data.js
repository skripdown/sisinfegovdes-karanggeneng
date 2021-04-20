//@ NAME   : _ui_factory LIB
//@ HANDLE : document object UI.
//@ AUTHOR : Malko.

window._data = {
    __toArray: function (object, attribute) {
        const arr = [];
        let node  = object._first;
        while (node !== undefined) {
            arr.push(node[attribute]);
            node = node._next;
        }

        return arr;
    },
    __retrieve: function (object) {
        let data = object._refresh();
        let new_memory = {};
        if (!Array.isArray(data))
            data = [data];
        for (let i = 0; i < data.length; i++) {
            new_memory[data[i].id] = data[i];
            if (i+1 < data.length)
                data[i]._next = data[i+1];
            else
                data[i]._next = undefined;
        }
        if (data.length > 0)
            object._first = data[0];
        object.data = null;
        object._len = data.length;
        object.data = new_memory;
    },
    insert: function (pointer=(_data.district.data), id, data) {
        pointer[id] = data;
    },
    get: function (pointer=(_data.district.data), id) {
        return pointer[id];
    },
    district : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.district._refresh = fun;
            _data.__retrieve(_data.district);
        },
        toArray : function (attribute) {
            return _data.__toArray(_data.district, attribute);
        }
    },
    hamlet : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.hamlet._refresh = fun;
            _data.__retrieve(_data.hamlet);
        },
        toArray : function (attribute) {
            return _data.__toArray(_data.hamlet, attribute);
        }
    },
    neighboor : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.neighboor._refresh = fun;
            _data.__retrieve(_data.neighboor);
        },
        toArray : function (attribute) {
            return _data.__toArray(_data.neighboor, attribute);
        }
    },
    family : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.family._refresh = fun;
            _data.__retrieve(_data.family);
        }
    },
    citizen : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.citizen._refresh = fun;
            _data.__retrieve(_data.citizen);
        },
    },
    officer : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.officer._refresh = fun;
            _data.__retrieve(_data.officer);
        },
    },
    mutation_in : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.mutation_in._refresh = fun;
            _data.__retrieve(_data.mutation_in);
        },
    },
    mutation_out : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.mutation_out._refresh = fun;
            _data.__retrieve(_data.mutation_out);
        },
    },
    archive : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.archive._refresh = fun;
            _data.__retrieve(_data.archive);
        },
    },
    archive_type : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.archive_type._refresh = fun;
            _data.__retrieve(_data.archive_type);
        },
    },
    request : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.request._refresh = fun;
            _data.__retrieve(_data.request);
        },
    },
    album : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.album._refresh = fun;
            _data.__retrieve(_data.album);
        },
    },
    photo : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.photo._refresh = fun;
            _data.__retrieve(_data.photo);
        },
    },
    blog : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.blog._refresh = fun;
            _data.__retrieve(_data.blog);
        },
    },
    account : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.account._refresh = fun;
            _data.__retrieve(_data.account);
        },
    },
    education : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.education._refresh = fun;
            _data.__retrieve(_data.education);
        },
        toArray : function (attribute) {
            return _data.__toArray(_data.education, attribute);
        }
    },
    religion : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.religion._refresh = fun;
            _data.__retrieve(_data.religion);
        },
        toArray : function (attribute) {
            return _data.__toArray(_data.religion, attribute);
        }
    },
    occupation : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.occupation._refresh = fun;
            _data.__retrieve(_data.occupation);
        },
        toArray : function (attribute) {
            return _data.__toArray(_data.occupation, attribute);
        }
    },
    approval : {
        data : {},
        _len: 0,
        _first : undefined,
        _refresh : undefined,
        refresh : function (fun=undefined) {
            if (fun !== undefined)
                _data.approval._refresh = fun;
            _data.__retrieve(_data.approval);
        },
        toArray : function (attribute) {
            return _data.__toArray(_data.approval, attribute);
        }
    },
}
