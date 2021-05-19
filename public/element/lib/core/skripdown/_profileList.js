window._profileList = {
    make : function (icon, title, sub, imgSource=false) {
        if (imgSource) {
            let temp   = icon;
            icon       = document.createElement('img');
            icon.setAttribute('src', temp);
            icon.setAttribute('alt', 'user');
            icon.setAttribute('class', 'rounded-circle');
            icon.setAttribute('width', '45');
            icon.setAttribute('height', '45');
        }
        const root     = document.createElement('div');
        const icon_ctr = document.createElement('div');
        const text_ctr = document.createElement('div');
        const text_obj = document.createElement('h5');
        const subs_obj = document.createElement('span');

        root.setAttribute('class', 'd-flex no-block align-items-center');
        icon_ctr.setAttribute('class', 'mr-3');
        text_obj.setAttribute('class', 'text-dark mb-0 font-16 font-weight-medium');
        subs_obj.setAttribute('class', 'text-muted font-14');

        if (typeof title === 'string') text_obj.innerHTML = title;
        else text_obj.appendChild(title);
        if (typeof sub === 'string') subs_obj.innerHTML = sub;
        else subs_obj.appendChild(sub);
        text_ctr.appendChild(text_obj);
        text_ctr.appendChild(subs_obj);
        icon_ctr.appendChild(icon);
        root.appendChild(icon_ctr);
        root.appendChild(text_ctr);

        return root;

    }
};
