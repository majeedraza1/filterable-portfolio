(function ($) {
    "use strict";

    var _this,
        galleryImages,
        createBtnText,
        editBtnText,
        progressBtnText,
        saveBtnText,
        frame,
        images,
        selection;

    $('[data-modal="MediaFramePost"]').on('click', function (e) {
        e.preventDefault();

        _this = $(this);
        galleryImages = _this.closest('.gallery_images');
        createBtnText = _this.data('create');
        editBtnText = _this.data('edit');
        progressBtnText = _this.data('progress');
        saveBtnText = _this.data('save');
        images = galleryImages.find('input[type="hidden"]').val();
        selection = loadImages(images);

        var options = {
            title: createBtnText,
            state: 'gallery-edit',
            frame: 'post',
            selection: selection
        };

        if (frame || selection) {
            options['title'] = editBtnText;
        }

        frame = wp.media(options).open();

        // Tweak Views
        frame.menu.get('view').unset('cancel');
        frame.menu.get('view').unset('separateCancel');
        frame.menu.get('view').get('gallery-edit').el.innerHTML = editBtnText;
        frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

        // when editing a gallery
        overrideGalleryInsert();
        frame.on('toolbar:render:gallery-edit', function () {
            overrideGalleryInsert();
        });

        frame.on('content:render:browse', function (browser) {
            if (!browser) return;
            // Hide Gallery Settings in sidebar
            browser.sidebar.on('ready', function () {
                browser.sidebar.unset('gallery');
            });
            // Hide filter/search as they don't work
            browser.toolbar.on('ready', function () {
                if (browser.toolbar.controller._state === 'gallery-library') {
                    browser.toolbar.$el.hide();
                }
            });
        });

        // All images removed
        frame.state().get('library').on('remove', function () {
            var models = frame.state().get('library');
            if (models.length === 0) {
                selection = false;
            }
        });

        function overrideGalleryInsert() {
            frame.toolbar.get('view').set({
                insert: {
                    style: 'primary',
                    text: saveBtnText,
                    click: function () {
                        var models = frame.state().get('library'),
                            ids = '',
                            thumbs_url = '';

                        models.each(function (attachment) {
                            ids += attachment.id + ',';
                            thumbs_url += '<li><img width="75" height="75" src="' + attachment.attributes.sizes.thumbnail.url + '" class="attachment-75x75 size-75x75"></li>';
                        });

                        this.el.innerHTML = progressBtnText;

                        selection = loadImages(ids);
                        galleryImages.find('input[type="hidden"]').val(ids);
                        galleryImages.find('.gallery_images_list').html(thumbs_url);

                        frame.close();
                    }
                }
            });
        }

    });

    function loadImages(images) {
        if (images) {
            var shortcode = new wp.shortcode({
                tag: 'gallery',
                attrs: {ids: images},
                type: 'single'
            });

            var attachments = wp.media.gallery.attachments(shortcode);

            var selection = new wp.media.model.Selection(attachments.models, {
                props: attachments.props.toJSON(),
                multiple: true
            });

            selection.gallery = attachments.gallery;

            selection.more().done(function () {
                // Break ties with the query.
                selection.props.set({query: false});
                selection.unmirror();
                selection.props.unset('orderby');
            });

            return selection;
        }
        return false;
    }
})(jQuery);