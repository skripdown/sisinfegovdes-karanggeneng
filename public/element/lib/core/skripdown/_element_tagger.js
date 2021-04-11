window._tagger = {
    content : undefined,
    tag     : function (name) {
        const div = document.createElement('div');
        div.setAttribute('id', name);
        if (_tagger.content !== undefined)
            _tagger.content.appendChild(div);
    }
}
