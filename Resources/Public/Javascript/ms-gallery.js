'use strict';

$(document).ready(function() {
    $('.lightgallery-text').lightGallery({
        selector: '.lightgallery-link',
        speed: 400,
        thumbnail: true,
        showThumbByDefault: false
    });

    $(window).on('resize orientationchange', function(){
        const lightGallery = $('.lightgallery');

        if (lightGallery.length) {
            lightGallery.each(function(){
                const xsColumns = $(this).data('xs-columns');
                const smColumns = $(this).data('sm-columns');
                const mdColumns = $(this).data('md-columns');

                if (mdColumns > 2) {
                    let rowColumns = 2;

                    if ($('.lightgallery .visible-xs').is(':visible')) {
                        rowColumns = xsColumns;
                    } else if ($('.lightgallery .visible-sm').is(':visible')) {
                        rowColumns = smColumns;
                    } else {
                        rowColumns = mdColumns;
                    }

                    const rowClass = $(this).find('.row:first').attr('class');

                    let newGallery = '';

                    let columns = $(this).find('.col');

                    columns.each(function (columnIndex) {
                        if (columnIndex === 0 || (columnIndex % rowColumns === 0)) {
                            newGallery += '<div class="' + rowClass + '">';
                        }

                        newGallery += $(this)[0].outerHTML;

                        if (columnIndex !== 0 && (columnIndex === columns.length || (columnIndex % rowColumns === rowColumns - 1))) {
                            newGallery += '</div>';
                        }
                    });

                    $(this).find('.lightgallery-content').html(newGallery);
                }
            });

            let activeGallery = lightGallery.data('lightGallery');

            if (activeGallery) {
                activeGallery.destroy(true);
            }

            activeGallery = lightGallery.lightGallery({
                selector: '.lightgallery-link',
                speed: 400,
                thumbnail: true,
                showThumbByDefault: false
            });
        }
    });

    $(window).resize();
});