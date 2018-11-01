define(['jquery', 'routing', 'ekyna-verified-reviews/templates'], function ($, Router, Templates) {
    "use strict";

    var $root = $('#verified-reviews');
    if (1 !== $root.length) {
        return;
    }

    var config = $root.data('config'),
        $body = $root.find('> .verified-reviews-body'),
        page = 1, $button, xhr;

    function fetchReviews() {
        if (xhr) {
            return;
        }

        $button
            .prop('disabled', true)
            .find('> i')
            .removeClass('fa-comments-o')
            .addClass('fa-spinner fa-pulse fa-fw');

        xhr = $.getJSON(Router.generate('ekyna_verified_reviews_api_reviews', {
            'productId': config.id,
            'page': page
        }));
        xhr.success(function (reviews) {
            if (0 < reviews.length) {
                var $reviews = $(Templates['reviews.html.twig'].render({
                    'config': config,
                    'reviews': reviews
                }));

                $reviews.appendTo($body).slideDown();

                $([document.documentElement, document.body]).animate({
                    scrollTop: $reviews.offset().top + 180
                }, 1000);

                if (reviews.length === config['columns'] * config['rows']){
                    page++;
                    $button.prop('disabled', false);
                } else {
                    disable();
                }
            } else {
                disable();
            }
        });
        xhr.always(function () {
            xhr = undefined;
            $button
                .find('> i')
                .removeClass('fa-spinner fa-pulse fa-3x fa-fw')
                .addClass('fa-comments-o');
        });
    }

    function disable() {
        $button
            .prop('disabled', true)
            .hide()
            .off('click', fetchReviews);
    }

    function enable() {
        $button = $root
            .find('> .verified-reviews-footer > button')
            .prop('disabled', false)
            .on('click', fetchReviews);
    }

    if (1 === $body.length) {
        enable();
    }
});
