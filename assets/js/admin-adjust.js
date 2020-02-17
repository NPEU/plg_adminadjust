(function() {
    var ready = function(fn) {
        if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

	var admin_adjust = {

        init: function() {
            //var el = document.getElementById('folderFrame');
            //admin_adjust.resizeIframe(el);


            //jQuery(function(){
            


            jQuery(function(){

                // Fix iFrame sizing:
                var iFrames = jQuery('iframe');

                function iResize() {
                    for (var i = 0, j = iFrames.length; i < j; i++) {
                        iFrames[i].style.height = iFrames[i].contentWindow.document.body.offsetHeight + 'px';
                    }
                }

                if (jQuery.browser.safari || jQuery.browser.opera) {

                    iFrames.load(function(){
                        setTimeout(iResize, 0);
                    });

                    for (var i = 0, j = iFrames.length; i < j; i++) {
                        var iSource = iFrames[i].src;
                        iFrames[i].src = '';
                        iFrames[i].src = iSource;
                    }
                } else {
                    iFrames.load(function() {
                        this.style.height = this.contentWindow.document.body.offsetHeight + 'px';
                    });
                }


                jQuery(window).resize(iResize);

                // Poll for changes:
                //window.setInterval(iResize, 1000);
                
                /*
                // Add copyright control to media thumbnails:
                jQuery('.manager.thumbnails.thumbnails-media').each(function(){
                    
                    var popover = [];
                    popover.push('<p><small>Accepts <a href="https://www.markdownguide.org/basic-syntax/" target="_blank">Markdown</a></small></p>');
                    popover.push('<p><textarea class="form-control  credit-input" rows="3"></textarea></p>');
                    popover.push('<p>Preview:</p>');
                    popover.push('<div class="credit-preview"></div>');
                    popover.push('<p class="pull-right"><button type="button" class="btn btn-default  credit-cancel">Cancel</button> <button type="button" class="btn btn-primary  credit-ok">OK</button></p>');
                    
                    jQuery(this).find('.thumbnail:has(.imgThumb)').each(function(){
                        var $thumb = jQuery(this);
                        
                        jQuery('<button class="btn btn-small btn-warning copyright-control">&copy;</button>')
                        .prependTo($thumb)
                        .webuiPopover({
                            title:       'Attribution (Credit line)',
                            content:     popover.join("\n"),
                            closeable:   true,
                            dismissible: true,
                            cache:       false,
                            onShow: function($element) {
                                
                                var $img    = $thumb.find('img');
                                var src     = $img.attr('src').replace(window.location.origin, '');
                                var src_b64 = btoa(src);
                                
                                //console.log(src);
                                
                                // Get the image credit line if there is one:
                                jQuery.ajax({
                                    url: '/plugins/system/tinker/ajax/get-image-info.php',
                                    data: { 'image': src_b64 },
                                    dataType: "json"
                                })
                                .done(function( response ) {
                                    //console.log( response.data.copyright );
                                    copyright = response.data.copyright;
                                    
                                    var converter      = new showdown.Converter(),
                                        copyright_html = converter.makeHtml(copyright);
                                    
                                    $element.find('.credit-input')
                                    .val(copyright)
                                    .on('input', function(){
                                        $element.find('.credit-preview')
                                        .html(converter.makeHtml(jQuery(this).val()));
                                    });
                                    $element.find('.credit-preview')
                                    .html(copyright_html);
                                });
                                
                                
                                
                                $element.find('.credit-cancel').click(function(){
                                    //console.log('Cancelled');
                                    $element.hide();
                                });
                                
                                $element.find('.credit-ok').click(function(){
                                    copyright = $element.find('.credit-input').val();
                                    //console.log('Okayed');
                                    // Add/Update the credit line:
                                    jQuery.ajax({
                                        url: '/plugins/system/tinker/ajax/get-image-info.php?image=' + src_b64,
                                        method: "POST",
                                        data: { 'copyright': copyright }
                                    })
                                    .done(function( response ) {
                                        console.log( response );
                                    });
                                    
                                    $element.hide();
                                });
                            },
                        });
                    });
                    
                });*/
                
            });

        },

        /*resizeIframe: function(obj) {
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }*/
	}

	ready(admin_adjust.init);
})();
