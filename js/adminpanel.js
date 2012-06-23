/**
 * Streamers Admin Panel - Content Loader
 *
 * This JavaScript Code acts as soon a doLoad() has been started through
 * an element's click (onClick). It loads the raw HTML content from the
 * main content.php file and replaces it with the current HTML data in
 * .box by fading it out, showing a loader image, and fading it back in.
 *
 * @author Sebastian Graebner <info@streamerspanel.com>
 * @package streamerspanel
 */
$(document).ready(function () {
    /**
     * doLoad function defined as a variable, due to JQuery's scope.
     */
    var doLoad = function () {
        $('.error').hide();
        $('.notifi').hide();
        $('.correct').hide();
        /**
         * Takes the object's href value and sub-strings it
         */
        var loadHREF = $(this).attr('href');
        var loadURL = 'content.php?request=html&include=' + encodeURIComponent(loadHREF.replace(/loadContent\-/, ''));
        location.hash = encodeURIComponent(loadHREF.replace(/loadContent\-/, ''));
            /**
         * Fades content out and the loader in, calls loadContent on success
         */
        $('#content').slideUp('fast', loadContent());
        $('#contentload').fadeIn('fast');
        /**
         * loadContent() loads over JQuery's load method and adds it into .box,
         * after that it slides #content back in if everything was successful,
         * by also make the loader disappear.
         */
        function loadContent() {
            $('.box').load(loadURL, function (response, status, xhr) {
                if (status == 'error') {
                    var msg = 'Sorry but there was an error: ';
                    $('.box').html(msg + xhr.status + ' ' + xhr.statusText);
                } else {
                    $('#content').delay(800).slideDown('fast', function () {
                        $('#contentload').delay(20).fadeOut('fast');
                    });
                }
            });
        }
        /**
         * Returns false so the link won't be executed by the browser
         */
        return false;
    };
    /**
     * Definition of elements possible to load this function.
     */
    $('ul.navMenu li a').click(doLoad);
    $('.logo a').click(doLoad);
});