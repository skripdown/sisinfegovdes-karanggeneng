window._formdata = {
    make : function (elements) {
        const data = {};
        let file   = undefined;
        for (let i = 0; i < elements.length; i++) {
            data[elements[i].getAttribute('name')] = elements[i].value;
            if (elements[i].getAttribute('type') === 'file') {
                if (file === undefined)
                    file = {};
                file[elements[i].getAttribute('name')] = elements[i].files[0];
            }
        }

        return [data, file];
    }
}
