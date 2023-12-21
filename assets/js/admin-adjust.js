/*
    iframe - fit height to contents.
*/
/*
(function() {

    var setIframeHeight = function(iframe) {

        var newHeight = iframe.contentDocument.querySelector('html').offsetHeight;
        iframe.style.height = newHeight + 'px';
    };

    var fit_content = function() {
        // Get all elements we want to apply this to:
        var elements = document.querySelectorAll('iframe');

        Array.prototype.forEach.call(elements, function(el, i) {
            var iframe        = el;
            var iframe_window = iframe.contentWindow;

            iframe.addEventListener('load', function() {
                setIframeHeight(iframe);
            });

            iframe_window.addEventListener('resize', function(e) {
                setIframeHeight(iframe);
            });
        });
    };

    var ready = function(fn) {
        if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    ready(fit_content);
})();
*/