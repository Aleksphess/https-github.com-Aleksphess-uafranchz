/*
 * jQuery File Upload Plugin JS Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';
    var $photosTotalEl = $('.js-photos-qty');
    var photosTotalQty = getTotalPhotosQty($photosTotalEl);

    function getTotalPhotosQty(el){
        return parseInt(el.text());
    }
    function setTotalPhotosQty(el, value){
        el.text(value);
    }

    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/server/php/index.php',
        fileInput: $('.js-media-file'),
        limitMultiFileUploads: 20,
        previewMaxWidth: 317,
        previewMinWidth: 317,
        previewMaxHeight: 227
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );
    
    $('#fileupload').bind('fileuploaddone', function (e, data) {
        photosTotalQty++;
        setTotalPhotosQty($photosTotalEl, photosTotalQty);
    });
    $('#fileupload').bind('fileuploaddestroyed', function (e, data){
        photosTotalQty--;
        setTotalPhotosQty($photosTotalEl, photosTotalQty);
    });
    // Load existing files:
    $('#fileupload').addClass('fileupload-processing');
    $.ajax({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: $('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        context: $('#fileupload')[0]
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
        $(this).fileupload('option', 'done')
            .call(this, $.Event('done'), {result: result});
    });

});