window._properties = {
    properties : {},
    container  : undefined,
    init       : function (id) {
        _properties.container = document.getElementById(id);
    },
    render     : function (input) {
        const id       = input.id;
        const el       = input.element;
        const groups   = input.groups;
        const property = {};

        const group_item = {};
        const dd         = document.createElement('div');
        dd.setAttribute('id', 'property-' + id);
        dd.setAttribute('class', 'dropdown-menu dropdown-menu-sm');

        _properties.container.appendChild(dd);

        for (let i = 0; i < groups.length; i++) {
            const type  = groups[i].type;
            const menus = groups[i].menus;
            const items = [];
            for (let j = 0; j < menus.length; j++) {
                const menu = menus[j];
                const item = document.createElement('a');
                item.setAttribute('class', 'dropdown-item');
                item.setAttribute('href', '#');
                item.innerHTML = menu.content;
                item.addEventListener('click', function () {
                    $(dd).removeClass("show").hide();
                    menu.fun();
                });
                dd.appendChild(item);
                items.push(item);
            }
            group_item[type] = items;
        }

        $(el).on('contextmenu', function (e) {
            const top  = e.pageY - 20;
            const left = e.pageX - 260;
            $(dd).css({
                display: "block",
                top: top,
                left: left
            }).addClass("show");
            return false;
        }).on("click", function() {
            $(dd).removeClass("show").hide();
        });

        property['el']             = el;
        property['group']          = group_item;
        property['dropdown']       = dd;
        property['switch']         = function (group_id) {
            const menus = dd.children;
            for (let i = 0; i < menus.length; i++) {
                menus[i].setAttribute('class', menus[i].getAttribute('class').replace(' d-none', '') + ' d-none');
            }
            const actives = property['group'][group_id];
            for (let i = 0; i < actives.length; i++) {
                actives[i].setAttribute('class', actives[i].getAttribute('class').replace(' d-none', ''));
            }

            return property;
        }
        property['open']           = function (e) {
            const top  = e.pageY - 20;
            const left = e.pageX - 260;
            $(dd).css({
                display: "block",
                top: top,
                left: left
            }).addClass("show");

            return property;
        }
        _properties.properties[id] = property;

        return property;
    },
};