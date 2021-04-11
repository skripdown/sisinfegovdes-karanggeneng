window._district_input = {
    selector : undefined,
    make : function (input) {
        function makeOption(objects, part, prefix) {
            const arr = [];
            for (let i = 0; i < objects.length; i++) {
                const opt = document.createElement('option');
                opt.setAttribute('value', objects[i].id);
                opt.innerText = prefix + (' ' + objects[i][part]);
                arr.push(opt)
            }

            return arr;
        }

        function setOptions(options, select) {
            select.innerHTML = '';
            for (let i = 0; i < options.length; i++) {
                select.appendChild(options[i]);
            }
        }

        const districts       = input.districts;
        const district_select = document.getElementById(input.sel_district);
        const hamlet_select   = document.getElementById(input.sel_hamlet);
        const neighbor_select = document.getElementById(input.sel_neighbor);
        const key             = input.key;
        const root            = {};

        let node              = districts._first;
        while (node !== undefined) {
            const pointer = node;
            const opt     = document.createElement('option');
            opt.setAttribute('value', pointer.id);
            opt.innerText = pointer.name;
            district_select.appendChild(opt);
            const hamlets = pointer.hamlets;
            root[pointer.id] = {}
            root[pointer.id]['hamlets'] = {};
            root[pointer.id]['options'] = makeOption(hamlets, 'name', 'RT');
            if (neighbor_select !== null) {
                for (let i = 0; i < hamlets.length; i++) {
                    const hamlet    = hamlets[i];
                    const neighbors = hamlet.neighboors;
                    root[pointer.id]['hamlets'][hamlet.id]              = {};
                    root[pointer.id]['hamlets'][hamlet.id]['neighbors'] = {};
                    root[pointer.id]['hamlets'][hamlet.id]['options']   = makeOption(neighbors, 'name', 'RW');
                }
            }
            node = node._next;
        }

        setOptions(root[district_select.value]['options'], hamlet_select);
        if (neighbor_select !== null)
            setOptions(root[district_select.value]['hamlets'][hamlet_select.value]['options'], neighbor_select);

        district_select.addEventListener('change', function () {
            setOptions(root[district_select.value]['options'], hamlet_select);
            if (neighbor_select !== null)
                setOptions(root[district_select.value]['hamlets'][hamlet_select.value]['options'], neighbor_select);
        });
        hamlet_select.addEventListener('change', function () {
            if (neighbor_select !== null)
                setOptions(root[district_select.value]['hamlets'][hamlet_select.value]['options'], neighbor_select);
        });

        root['sel_district']     = district_select;
        root['sel_hamlet']       = hamlet_select;
        root['sel_neighbor']     = neighbor_select;
        if (key !== undefined)
            _district_input[key] = root;
        else
            _district_input.selector = root;

        return _district_input;
    },
    set : function (district_id, hamlet_id, neighbor_id, key=undefined) {
        function setOptions(options, select) {
            select.innerHTML = '';
            for (let i = 0; i < options.length; i++) {
                select.appendChild(options[i]);
            }
        }

        let root;
        if (key !== undefined)
            root            = _district_input[key];
        else
            root            = _district_input.selector;
        const sel_district  = root['sel_district'];
        const sel_hamlet    = root['sel_hamlet'];
        const sel_neighbor  = root['sel_neighbor'];
        let district_change = false;
        let hamlet_change   = false;

        if (sel_district.value !== district_id) {
            district_change = true;
            const options   = sel_district.children;
            for (let i = 0; i < options.length; i++) {
                if (options[i].selected === true)
                    options[i].selected = false;
                if (options[i].getAttribute('value') === district_id) {
                    options[i].selected = true;
                }
            }
            setOptions(root[district_id]['options'], sel_hamlet);
        }
        if (sel_hamlet.value !== hamlet_id || district_change) {
            hamlet_change = true;
            const options = sel_hamlet.children;
            for (let i = 0; i < options.length; i++) {
                if (options[i].selected === true)
                    options[i].selected = false;
                if (options[i].getAttribute('value') === hamlet_id) {
                    options[i].selected = true;
                }
            }
            if (sel_neighbor !== null)
                setOptions(root[district_id]['hamlets'][hamlet_id]['options'], sel_neighbor);
        }
        if (sel_neighbor !== null) {
            if (sel_neighbor.value !== neighbor_id || hamlet_change) {
                const options = root[district_id]['hamlets'][hamlet_id]['options'];
                for (let i = 0; i < options.length; i++) {
                    if (options[i].selected === true)
                        options[i].selected = false;
                    if (options[i].getAttribute('value') === neighbor_id) {
                        options[i].selected = true;
                    }
                }
            }
        }
    }
}
