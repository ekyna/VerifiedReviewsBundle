module.exports = function (grunt, options) {
    // @see https://github.com/gruntjs/grunt-contrib-less
    return {
        reviews: {
            files: {
                'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/tmp/css/reviews.css':
                    'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/private/less/reviews.less'
            }
        }
    }
};
