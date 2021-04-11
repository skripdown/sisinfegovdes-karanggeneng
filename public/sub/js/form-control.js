window.tabs = {
    tabs   : [],
    prev   : undefined,
    submit : undefined,
    next   : undefined,
    active : undefined,
    init   : function (input) {
        tabs.submit = document.getElementById(input.submit);
        tabs.prev   = document.getElementById(input.prev);
        tabs.next   = document.getElementById(input.next);
    },
    add    : function (input) {
        function focus(source, target) {
            source.title.setAttribute('class', 'col-3 text-muted');
            source.title.setAttribute('style', 'opacity: 0.5;');
            source.body.setAttribute('class', 'd-none');
            target.title.setAttribute('class', 'col-3 text-dark font-weight-bold');
            target.title.setAttribute('style', '');
            target.body.setAttribute('class', '');
        }

        const lib      = window.tabs;
        const prev     = lib.prev;
        const next     = lib.next;
        let tabs       = lib.tabs;
        for (let i = 0; i < input.length; i++) {
            const tab      = {};
            const tabTitle = document.getElementById(input[i].title);
            const tabBody  = document.getElementById(input[i].body);

            tab['title']   = tabTitle;
            tab['body']    = tabBody;
            if (i === 0) {
                tabTitle.setAttribute('class', 'col-3 text-center text-dark font-weight-bold');
                tabTitle.setAttribute('style','');
                tabs.push(tab);
            }
            else {
                tab._before = tabs[i-1];
                tabs[i-1]._next = tab;
                tabTitle.setAttribute('class', 'col-3 text-center text-muted');
                tabTitle.setAttribute('style','opacity: 0.5;');
                tabBody.setAttribute('class', 'd-none');
                tabs.push(tab);
            }
        }
        lib.active          = tabs[0];
        prev.setAttribute('class', 'd-none');
        lib.submit.setAttribute('class', 'd-none');
        prev.addEventListener('click',()=>{
            const source = lib.active;
            const target = source._before;
            focus(source, target);
            lib.active   = target;
            if (target._before === undefined) {
                prev.setAttribute('class', 'd-none');
            }
            lib.submit.setAttribute('class', 'd-none');
            next.setAttribute('class', 'btn btn-secondary');
        });
        next.addEventListener('click',()=>{
            const source = lib.active;
            const target = source._next;
            focus(source, target);
            lib.active   = target;
            if (target._next === undefined) {
                next.setAttribute('class', 'd-none');
                lib.submit.setAttribute('class', 'btn btn-success');
            }
            prev.setAttribute('class', 'btn btn-secondary');
        });
    }
};

window.collections = {
    collection : {},
    validator : {
        name : function (text) {
            if (text === '' || text === undefined) return false;
            return /^([A-z' ]+)$/m.exec(text) != null;
        },
        email : function (text) {
            if (text === '' || text === undefined) return false;
            return /^([\w.]+)@\w+(\.[\w]+)+$/m.exec(text) != null;
        },
        year : function (text) {
            if (text === '' || text === undefined) return false;
            return /^(\d\d\d\d)$/m.exec(text) != null;
        },
        phone : function (text) {
            if (text === '' || text === undefined) return false;
            if (/^\+62([ \-])?|\d+(([ \-])?\d+)+$/m.exec(text) != null)
                return text.length > 9 && text.length < 13;
            return false;
        },
        number : function (text) {
            if (text === '' || text === undefined) return false;
            return /^\d{1,3}([.,]?\d{1,3})+/m.exec(text) != null;
        },
        check : function (object) {
            return object.checked;
        },
        noEmpty : function (text) {
            return text !== '' && text !== undefined;
        }
    },
    set : function (input) {
        function verify (collection) {
            const fields = collection.fields;
            for (let i = 0; i < fields.length; i++) {
                if (!fields[i].valid) {
                    collection.submit.disabled = true;
                    return false;
                }
            }
            collection.submit.disabled = false;
            return true;
        }
        const name      = input.name;
        const fields    = input.fields;
        const submit    = document.getElementById(input.submit);
        const coll      = {};
        coll['submit']  = submit;
        coll['hasFile'] = false;
        coll['fields']  = [];
        submit.disabled = true;
        for (let i = 0; i < fields.length; i++) {
            const field    = {};
            if (Array.isArray(fields[i].el)) {
                field['el'] = [];
                for (let j = 0; j < fields[i].el.length; j++) {
                    field['el'].push(document.getElementById(fields[i].el[j]));
                }
                field['valid'] = true;
            }
            else {
                field['el']    = document.getElementById(fields[i].el);
                if (field['el'].getAttribute('type') === 'file')
                    coll['hasFile'] = true;
                if (fields[i].validator !== undefined) {
                    if (fields[i].validator === 'check') {
                        field['el'].addEventListener('click',()=>{
                            field['valid'] = collections.validator[fields[i].validator](field['el']);
                            verify(coll);
                        });
                    }
                    else if (fields[i].validator === 'noEmpty') {
                        field['el'].addEventListener('focusout',()=>{
                            field['valid'] = collections.validator[fields[i].validator](field['el'].value);
                            verify(coll);
                        });
                    }
                    else {
                        field['el'].addEventListener('keyup',()=>{
                            console.log('changed');
                            field['el'].setAttribute('class', field['el'].getAttribute('class').replace(' border-danger',''));
                            field['valid'] = collections.validator[fields[i].validator](field['el'].value);
                            if (!field['valid'])
                                field['el'].setAttribute('class', field['el'].getAttribute('class') + ' border-danger');
                            else {
                                field['el'].setAttribute('class', field['el'].getAttribute('class').replace(' border-danger',''));
                                verify(coll);
                            }
                        });
                    }
                    field['valid'] = false;
                }
                else {
                    field['valid'] = true;
                    field['el'].addEventListener('change', function () {
                        verify(coll);
                    });
                }
            }
            if (fields[i].hasVal !== undefined) {
                field['valid'] = fields[i].hasVal;
            }
            field['name']      = fields[i].name;
            coll['fields'].push(field);
        }
        collections.collection[name] = coll;
    },
    collect : function (name, token = undefined) {
        const fields = collections.collection[name].fields;
        let data = {}, file = undefined;
        for (let i = 0; i < fields.length; i++) {
            if (!Array.isArray(fields[i].el)) {
                data[fields[i].name] = fields[i].el.value;
                if (fields[i].el.getAttribute('type') === 'file') {
                    if (file === undefined)
                        file = {};
                    file[fields[i].name] = fields[i].el.files[0];
                }
            }
            else {
                const elements = fields[i].el;
                for (let j = 0; j < elements.length; j++) {
                    if (elements[j].checked) {
                        data[fields[i].name] = elements[j].value;
                        break;
                    }
                }
            }
        }

        return [data,file];
    }
};

window.request = function (input) {
    let async;
    if (input.async === undefined)
        async = true;
    else
        async = input.async;
    const url      = input.url;
    const data     = input.data;
    const method   = input.method;
    const callback = input.callback;
    const xhr      = new XMLHttpRequest();
    xhr.open(method, url, async);
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            callback(this);
        }
    }
    if (input.json === undefined || input.json === true) {
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.send(JSON.stringify(data));
    }
    else {
        xhr.send(data);
    }
}
