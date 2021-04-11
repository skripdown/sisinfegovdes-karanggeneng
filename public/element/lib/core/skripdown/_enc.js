window._passmatch = {
    for: function (input) {
        let el1,el2,label;
        if (typeof input.source === 'string')
            el1 = document.getElementById(input.source);
        else
            el1 = input.source;
        if (typeof input.target === 'string')
            el2 = document.getElementById(input.target);
        else
            el2 = input.target;
        if (typeof input.label === 'string')
            label = document.getElementById(input.label);
        else
            label = input.label;

        el1.addEventListener("keyup", function () {
            if (el1.value !== '') {
                const temp  = _passmatch.validity(el1.value);
                let classes = el1.getAttribute('class');
                let lbclass = label.getAttribute('class');
                classes     = classes.replace(' border-danger', '');
                classes     = classes.replace(' mt-4', '');
                lbclass     = lbclass.replace(' text-danger','');
                lbclass     = lbclass.replace(' mt-4','');
                lbclass     = lbclass.replace(' d-none','');
                lbclass     = lbclass.replace('d-none','');
                if (temp[0]) {
                    classes    += ' mt-4';
                    lbclass    += ' d-none';
                }
                else {
                    classes    += ' border-danger';
                    lbclass    += ' mt-4 text-danger';
                    label.innerHTML = temp[1];
                }
                el1.setAttribute('class',classes);
                label.setAttribute('class',lbclass);
            }
            else {
                let classes = el1.getAttribute('class');
                classes     = classes.replace(' border-danger', '');
                classes     = classes.replace(' mt-4', '');
                classes    += ' mt-4';
                el1.setAttribute('class',classes);
                label.setAttribute('class',' d-none');
            }
        });
        el2.addEventListener("keyup", function () {
            const classes = el2.getAttribute('class');
            if (el2.value !== '') {
                if (el2.value === el1.value) {
                    el2.setAttribute('class', classes.replace(' border-danger', ''));
                }
                else {
                    const tmp = classes.replace(' border-danger', '');
                    el2.setAttribute('class', tmp+' border-danger');
                }
            }
            else {
                el2.setAttribute('class', classes.replace(' border-danger', ''));
            }
        })
    },
    validity : function (string) {
        const upper  = /([A-Z])/m;
        const number = /([0-9])/m;
        const symbol = /([^\w\s])/m
        if (string.length < 8) {
            return [false, 'kata sandi kurang dari 8 karakter'];
        }
        if (upper.exec(string) == null) {
            return [false, 'kata sandi tidak memiliki minimal satu huruf kapital'];
        }
        if (number.exec(string) == null) {
            return [false, 'kata sandi harus memiliki minimal satu angka'];
        }
        if (symbol.exec(string) == null) {
            return [false, 'kata sandi harus memiliki minimal satu simbol'];
        }
        return [true];
    }
}

window._mailmatch = {
    for : function (input) {
        let el;
        if (typeof input === 'string')
            el = document.getElementById(input);
        else
            el = input;
        el.addEventListener("keyup", function () {
            let classes = el.getAttribute('class');
            classes     = classes.replace(' border-danger','');
            classes     = classes.replace(' mt-4','');
            const re    = /^[\w]+@[\w]+(\.[\w]+)+$/m;
            if (re.exec(el.value) == null) {
                classes = classes + ' border-danger';
            }
            classes = classes + ' mt-4';
            el.setAttribute('class',classes);
        });
    }
}

window._valuematch = {
    for : function (input) {
        let source, target, fun = input.fun;
        if (typeof input.source === 'string')
            source = document.getElementById(input.source);
        else
            source = input.source;
        if (typeof input.target === 'string')
            target = document.getElementById(input.target);
        else
            target = input.target;

        source.addEventListener('keyup', function () {
            if (source.value === target.value)
                fun();
        });
    }
}

window._ctclipboard = {
    for : function (input,target=undefined) {
        if (typeof input === 'string')
            input = document.getElementById(input);
        if (target !== undefined) {
            if (typeof target === 'string')
                target = document.getElementById(target);
        }
        else
            target = input;
        input.addEventListener("click", function () {
            target.select();
            target.setSelectionRange(0,999999);
            document.execCommand('copy');
        });
    }
}

window._form = {
    for : function (input) {
        if (typeof input.submit === 'string')
            input.submit = document.getElementById(input.submit);
        if (typeof input.alias === undefined)
            input.alias = {};
        if (typeof input.optional === undefined)
            input.optional = {};
        if (typeof input.pass !== undefined && typeof input.pass === 'string') input.pass = document.getElementById(input.pass)
        if (typeof input.verify !== undefined && typeof input.verify === 'string') input.verify = document.getElementById(input.verify)
        const elements  = input.elements;
        const submit    = input.submit;
        const aliases   = input.alias;
        const optionals = input.optional;
        const fun       = input.func;
        const pass      = input.pass;
        const verify    = input.verify;
        submit.addEventListener('click',function () {
            let bool;
            if (pass !== undefined) {
                if (verify !== undefined)
                    bool = !_form.check(elements, aliases, optionals)[0] && pass.value !== '' && pass.value === verify.value;
                else
                    bool = !_form.check(elements, aliases, optionals)[0] && pass.value !== '';
            }
            else
                bool = !_form.check(elements, aliases, optionals)[0];
            if (bool)
                fun(elements);
        });
        for (let i = 0; i < elements.length; i++) {
            let alias   = aliases[elements[i]];
            if (typeof elements[i] === 'string')
                elements[i] = document.getElementById(elements[i]);
            if (alias !== undefined) {
                if (typeof alias === 'string')
                    alias = document.getElementById(alias);
            }
            elements[i].addEventListener('click', function () {
                let classes;
                if (alias === undefined) {
                    classes = elements[i].getAttribute('class');
                    elements[i].setAttribute('class',
                        classes.replace(' border-danger','').
                        replace(' text-danger',' text-muted')
                    );
                }
                else {
                    classes = alias.getAttribute('class');
                    alias.setAttribute('class',
                        classes.replace(' border-danger','').
                        replace(' text-danger',' text-muted')
                    );
                }
            });
        }
    },
    check : function (forms, aliases= undefined, optionals = undefined) {
        let hasError = false;
        for (let i = 0; i < forms.length; i++) {
            let alias  = document.getElementById(aliases[forms[i].getAttribute('id')]);
            let option = document.getElementById(optionals[forms[i].getAttribute('id')]);
            if (alias == null)
                alias  = undefined;
            if (option == null)
                option = undefined;
            if (typeof forms[i] === 'string')
                forms[i] = document.getElementById(forms[i]);
            if (forms[i].value === '') {
                if (option !== undefined) {
                    let classes;
                    if (alias === undefined) {
                        classes     = forms[i].getAttribute('class');
                        classes     = classes.replace(' border-danger','');
                        classes    += ' border-danger';
                        classes    += ' text-danger';
                        forms[i].setAttribute('class', classes);
                    }
                    else {
                        classes     = alias.getAttribute('class');
                        classes     = classes.replace(' border-danger','');
                        classes    += ' border-danger';
                        classes    += ' text-danger';
                        alias.setAttribute('class', classes);
                    }
                    hasError = true;
                }
            }
        }
        return [hasError];
    },
    clear : function (id) {
        const inputs = document.getElementById(id).firstChild.childNodes;
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].tagName === 'INPUT') {
                if (inputs[i].getAttribute('type') !== 'hidden') {
                    inputs[i].value = '';
                }
            }
        }
    },
    disable : function (id) {
        const inputs = document.getElementById(id).firstChild.childNodes;
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].tagName === 'INPUT' || inputs[i].tagName === 'DIV') {
                if (inputs[i].getAttribute('type') !== 'hidden' && !inputs[i].disabled) {
                    inputs[i].disabled = true;
                }
                if (inputs[i].firstChild !== null) {
                    if (inputs[i].firstChild.tagName === 'A') {
                        inputs[i].firstChild.setAttribute('class',inputs[i].firstChild.getAttribute('class')+' disabled');
                    }
                }
            }
        }
    },
    enable : function (id) {
        const inputs = document.getElementById(id).firstChild.childNodes;
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].tagName === 'INPUT') {
                if (inputs[i].getAttribute('type') !== 'hidden' && inputs[i].disabled) {
                    inputs[i].disabled = false;
                }
                else if (inputs[i].firstChild !== null) {
                    if (inputs[i].firstChild.tagName === 'A')
                        inputs[i].firstChild.setAttribute('class',inputs[i].firstChild.getAttribute('class').replace(' disabled', ''));
                }
            }
        }
    },
    fill : function (inputs) {
        let input;
        for (let i = 0; i < inputs.length; i++) {
            input       = document.getElementById(inputs[i].input);
            input.value = inputs[i].value;
        }
    }
}

window._delete = {
    render : function (fun) {
        const btn = document.createElement('button');
        btn.setAttribute('class', 'btn btn-secondary btn-sm rounded-0');
        btn.setAttribute('style', 'border-radius:0.15rem !important;');
        btn.setAttribute('type', 'button');
        btn.setAttribute('title', 'hapus');
        btn.innerHTML = '<i class="fa fa-trash"></i>';
        btn.addEventListener('click', function (e) {
            fun(e);
        });

        return btn;
    }
}

window._btn = {
    render : function (input) {
        if (input.operate === undefined)
            input.operate = 'none';
        if (input.size === undefined)
            input.size = 'sm';
        else
            input.size = 'btn-' + input.size;
        const type  = 'btn-' + input.type + ' ' + input.size;
        const btn   = document.createElement('button');
        btn.setAttribute('class', 'btn rounded-0 ' + type);
        btn.setAttribute('style', 'border-radius:0.15rem !important;');
        btn.setAttribute('type', 'button');
        btn.setAttribute('data-operate', input.operate);
        btn.setAttribute('title', input.title);
        btn.innerHTML = input.content;
        btn.addEventListener('click', function (e) {
            input.fun(e);
        });

        return btn;
    }
}

window._btn_group = {
    make : function (buttons) {
        const group = document.createElement('div');
        group.setAttribute('class', 'btn-toolbar');
        group.setAttribute('role', 'toolbar');
        for (let i = 0; i < buttons.length; i++) {
            const container = document.createElement('div');
            if (i !== buttons.length-1)
                container.setAttribute('class', 'btn-group mr-2');
            else
                container.setAttribute('class', 'btn-group');
            container.setAttribute('role', 'group');
            container.appendChild(buttons[i]);
            group.appendChild(container);
        }

        return group;
    }
}

window._switcher = {
    item : {},
    render : function (input) {
        const content   = document.createElement('div');
        const hr        = document.createElement('hr');
        const btn_ctr   = document.createElement('div');
        const text      = document.createElement('h4');
        const btn_pr    = document.createElement('button');
        const btn_sc    = document.createElement('button');
        const el        = {};
        content.setAttribute('class', 'pt-3');
        btn_ctr.setAttribute('class','text-right');
        if (input.primary.role === 1)
            btn_pr.setAttribute('class', 'btn btn-success');
        else
            btn_pr.setAttribute('class', 'btn btn-secondary');
        btn_pr.setAttribute('type', 'button');
        if (input.secondary.role === 1)
            btn_sc.setAttribute('class', 'btn btn-success');
        else
            btn_sc.setAttribute('class', 'btn btn-secondary');
        btn_sc.setAttribute('type', 'button');
        btn_pr.innerHTML = input.primary.btn;
        btn_sc.innerHTML = input.secondary.btn;
        el['primary']   = input.primary;
        el['secondary'] = input.secondary;
        el['content']   = content;
        el['text']      = text;
        el['status']    = 1;
        el['primary']['btn']   = btn_pr;
        el['secondary']['btn'] = btn_sc;
        content.appendChild(text);
        content.appendChild(hr);
        content.appendChild(btn_ctr)
        btn_ctr.appendChild(btn_pr);
        btn_ctr.appendChild(btn_sc);
        btn_pr.addEventListener('click', function () {
            el.primary.fun();
        });
        btn_sc.addEventListener('click', function () {
            el.secondary.fun();
        });

        _switcher.item[input.id] = el;
        text.innerHTML = el['primary'].text;
        btn_sc.setAttribute('class', btn_sc.getAttribute('class') + ' d-none');

        return content;
    },
    switch : function (id) {
        const item = _switcher.item[id];
        console.log('role before = '+item.status);
        console.log(item);
        let content, alter;
        if (item.status === 1) {
            item.status = 2;
            content     = item.secondary;
            alter       = item.primary;
        }
        else {
            item.status = 1;
            content     = item.primary;
            alter       = item.secondary;
        }
        alter.btn.setAttribute('class', alter.btn.getAttribute('class') + ' d-none');
        content.btn.setAttribute('class', content.btn.getAttribute('class').replace(' d-none',''));
        item.text.innerHTML = content.text;
    },
}

window._rand = {
    pass : function (length=8,character='printable') {
        let res      = '';
        let chars;
        if (character === 'printable')
            chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_=+!@#$%^&*()[]{}|:;,.<>?';
        else if (character === 'alphanumeric')
            chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_';
        const ch_len = chars.length;
        for ( let i  = 0; i < length; i++ ) {
            res     += chars.charAt(Math.floor(Math.random() * ch_len));
        }

        return res;
    },
}

window._select = {
    add : function (element, value) {
        element = document.getElementById(element);
        const list = document.createElement('option');
        list.setAttribute('value', value.value);
        list.innerHTML = value.label;
        element.appendChild(list);
    },
    remove : function (element, value) {
        element = document.getElementById(element);
        const child = element.children;
        for (let i = 0; i < child.length; i++) {
            if (child[i].getAttribute('value') === value) {
                element.removeChild(child[i]);
                break;
            }
        }
    },
    hasOne : function (element) {
        return document.getElementById(element).children.length === 1;
    }
}

window._popup = {
    init : function (input) {
        let element   = input.element;
        let align     = input.align;
        const contain = document.createElement('div');
        const content = document.createElement('div');
        if (typeof element === 'string')
            element   = document.getElementById(element);
        element.setAttribute('class', 'modal fade');
        element.setAttribute('tabindex', '-1');
        element.setAttribute('role', 'dialog');
        element.setAttribute('aria-hidden', 'true');
        contain.setAttribute('class', 'modal-dialog modal-'+align);
        content.setAttribute('class', 'modal-content');
        element.appendChild(contain);
        contain.appendChild(content);
    },
    content : function (input) {
        const id      = input.id;
        const header  = input.header;
        const content = input.content;
        const footer  = input.footer;
        const close   = input.close;
        const type    = input.type;
        const element = document.getElementById(id);
        const contain = element.firstChild.firstChild;

        contain.innerHTML = '';
        if (header !== undefined) {
            const header_el = document.createElement('div');
            const title_el  = document.createElement('h4');
            header_el.setAttribute('class', 'modal-header');
            title_el.setAttribute('class', 'modal-title');
            if (type !== undefined)
                header_el.setAttribute('class', header_el.getAttribute('class') + ' modal-colored-header bg-'+type);
            if (typeof header === 'string')
                title_el.innerHTML = header;
            else
                title_el.appendChild(header);
            header_el.appendChild(title_el);
            if (close) {
                const close_el = document.createElement('button');
                close_el.setAttribute('type', 'button');
                close_el.setAttribute('class', 'close');
                close_el.setAttribute('data-dismiss', 'modal');
                close_el.setAttribute('aria-hidden', 'true');
                close_el.innerText = '×';
                header_el.appendChild(close_el);
            }
            contain.appendChild(header_el);
        }
        const content_el = document.createElement('div');
        content_el.setAttribute('class', 'modal-body');
        if (typeof content === 'string') content_el.innerHTML = content;
        else content_el.appendChild(content);
        contain.appendChild(content_el);
        if (footer !== undefined) {
            const footer_el = document.createElement('div');
            footer_el.setAttribute('class', 'modal-footer');
            if (typeof footer === 'string')
                footer_el.innerHTML = footer;
            else
                footer_el.appendChild(footer);
            contain.appendChild(footer_el);
        }

        $(element).modal();
    },
    close : function (id) {
        $("#" + id).modal('hide');
    }
}

window._date = {
    date : function (day, month, year, delimiter = ' ') {
        let out = day + '';
        day     = parseInt(day);
        month   = parseInt(month);
        year    = parseInt(year);
        if (month === 1) month = 'januari';
        else if (month === 2) month = 'februari';
        else if (month === 3) month = 'maret';
        else if (month === 4) month = 'april';
        else if (month === 5) month = 'mei';
        else if (month === 6) month = 'juni';
        else if (month === 7) month = 'juli';
        else if (month === 8) month = 'agustus';
        else if (month === 9) month = 'september';
        else if (month === 10) month = 'oktober';
        else if (month === 11) month = 'november';
        else month = 'desember';

        return out + delimiter + month + delimiter + year;
    },
    age : function (year, month=undefined, date=undefined) {
        year = parseInt(year);
        const _date = new Date();
        if (month === undefined)
            return _date.getFullYear() - year;
        month = parseInt(month);
        if (date === undefined) {
            let age = _date.getFullYear() - year;
            if (month <= _date.getMonth())
                return age + 1;
            return age;
        }
        let age = _date.getFullYear() - year;
        date = parseInt(date);
        if (month <= _date.getMonth())
            age += 1;
        if (month <= _date.getMonth() && date <= _date.getDate())
            age += 1;

        return age;
    },
    timestamp_old: function (created_at) {
        const res   = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})/m.exec(created_at);
        const date  = new Date();
        const day   = parseInt(res[3]);
        const month = parseInt(res[2])-1;
        const year  = parseInt(res[1]);
        console.log('day is = ' + date.getDate() + ' ' + day);
        if (date.getFullYear() === year) {
            if (date.getMonth() === month) {
                if (date.getDate() === day)
                    return 'sehari';
                else if (date.getDate() - day < 5)
                    return 'beberapa hari';
                else if (date.getDate() - day < 8)
                    return 'semingu';
                else if (date.getDate() - day < 14)
                    return 'seminggu lebih';
                else
                    return 'beberapa minggu';
            } else if (date.getMonth() - month < 7) {
                return 'beberapa bulan';
            }
            else {
                return (date.getMonth() - month) + ' bulan';
            }
        } else {
            if ( date.getFullYear() - year < 2)
                return 'setahun';
            if (date.getFullYear() - year < 3)
                return 'beberapa tahun';
            return (date.getFullYear() - year) + ' tahun';
        }
    },
    convert_created_at : function (created_at, timezone='', date_='', time_='') {
        const res  = /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})/m.exec(created_at);
        const date = _date.date(res[3], res[2], res[1]);
        let time   = Math.abs(12 - parseInt(res[4])) + ':' + res[5] + ':' + res[6];
        return date_ + date + time_ + time + timezone;
    }
}
