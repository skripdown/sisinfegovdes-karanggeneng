window._dir = {
    init   : function () {
        document.addEventListener('click', function (e) {
           const el = e.target;
           if (el.getAttribute('data-folder') == null) {
               console.log('out');
               _dir.unAll();
           }
        });
    },
    create : function (name='Folder', click=function () {console.log('clicked');}, dblclick_=function () {console.log('double clicked');}, mouseenter_=function () {console.log('mouse enter');}, mouseleave_=function () {console.log('mouse out');}, contextmenu_=function () {console.log('context menu');}) {
        function makeid(length) {
            let result             = [];
            const characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            const charactersLength = characters.length;
            for ( let i = 0; i < length; i++ ) {
                result.push(characters.charAt(Math.floor(Math.random() *
                    charactersLength)));
            }
            return result.join('');
        }

        const title_content = name;
        if (name.length > 8)
            name = name.slice(0, 8) + '...';

        const folder = document.createElement('div');
        const title  = document.createElement('div');
        const icon   = document.createElement('div');
        const area   = document.createElement('div');

        folder.setAttribute('class', 'd-inline-block ml-2 mr-2 mt-2 mb-2 folder-block');
        folder.setAttribute('data-folder','deselected');
        folder.setAttribute('data-folder-id', makeid(8) + '-' + name);
        title.setAttribute('class', 'folder-title');
        icon.setAttribute('class', 'ffolder small yellow');
        area.setAttribute('class', 'folder-hover');
        area.setAttribute('data-folder', 'area');
        area.setAttribute('title', title_content);

        folder.appendChild(area);
        folder.appendChild(icon);
        folder.appendChild(title);
        title.innerText = name;

        folder.addEventListener('click', function (e) {click();_dir.toggle(folder,_control.onShift(e));});
        folder.addEventListener('dblclick', function () {dblclick_();});
        folder.addEventListener('mouseenter', function () {mouseenter_();});
        folder.addEventListener('mouseleave', function () {mouseleave_();});
        folder.addEventListener('contextmenu', function () {contextmenu_();return false;});

        return folder;
    },
    remove : function (folder) {
        const par = folder.parentNode;
        par.removeChild(folder);
    },
    toggle : function (folder, hold=false) {
        if (!hold) {
            const id = folder.getAttribute('data-folder-id');
            const folders = document.getElementsByClassName('folder-selected');
            for (let i = 0; i < folders.length; i++) {
                const folder_ = folders[i];
                const id_     = folder_.parentNode.getAttribute('data-folder-id');
                if (id !== id_) {
                    folder_.parentNode.setAttribute('data-folder', 'deselected');
                    folder_.setAttribute('class', 'folder-hover');
                }
            }
        }
        if (folder.getAttribute('data-folder') === 'selected') {
            folder.setAttribute('data-folder', 'deselected');
            folder.children[0].setAttribute('class', 'folder-hover');
        } else {
            folder.setAttribute('data-folder', 'selected');
            folder.children[0].setAttribute('class', 'folder-selected');
        }
    },
    unAll  : function (again=true) {
        const folders = document.getElementsByClassName('folder-selected');
        let iter = 0;
        while (iter < folders.length) {
            folders[iter].parentNode.setAttribute('data-folder', 'deselected');
            folders[iter].setAttribute('class', 'folder-hover');
            iter++;
        }
        if (again)
            _dir.unAll(false);

    },
    update : function (folder, name='') {
        if (name !== '')
            folder.childNodes[2].innerText = name;
    }
};

window._file = {
    make : function (extension, size='sm', click_=function () {console.log('click');}, dblclick_=function () {console.log('double click');}, mouseenter_=function () {console.log('mouse enter');}, mouseleave_=function () {console.log('mouse out');}, contextmenu_=function () {console.log('context menu');}) {
        if (size==='xs'||size==='sm'||size==='md'||size==='lg'||size==='xl')
            size = 'fi-size-' + size;
        else
            size = 'fi-size-sm';
        const ctr  = document.createElement('div');
        const icon = document.createElement('div');
        ctr.setAttribute('class', 'd-inline-block ml-2 mr-2 mt-2 mb-2 folder-block');
        icon.setAttribute('class', 'fi fi-' + extension + ' ' + size + ' fi-round-md');
        icon.innerHTML = '<div class="fi-content">' + extension + '</div>';
        icon.addEventListener('click', function () {click();});
        icon.addEventListener('dblclick', function () {dblclick_();});
        icon.addEventListener('mouseleave', function () {mouseleave_();});
        icon.addEventListener('mouseenter', function () {mouseenter_();});
        icon.addEventListener('contextmenu', function () {contextmenu_();return false;});
        ctr.appendChild(icon);

        return ctr;
    },
    change : function (icon, extension, size='sm') {
        if (size==='xs'||size==='sm'||size==='md'||size==='lg'||size==='xl')
            size = 'fi-size-' + size;
        else
            size = 'fi-size-sm';
        icon = icon.firstChild;
        icon.setAttribute('class', 'fi fi-' + extension + ' ' + size + ' fi-round-md');
        icon.innerHTML = '<div class="fi-content">' + extension + '</div>';
    }
};

window._control = {
    onShift : function (e) {
        return e.ctrlKey;
    }
}