module.exports = function (grunt, options) {
    return {
        reviews_less: {
            files: ['src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/private/less/**/*.less'],
            tasks: ['less:reviews', 'copy:reviews_less', 'clean:reviews_less'],
            options: {
                spawn: false
            }
        },
        reviews_js: {
            files: ['src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/private/js/**/*.js'],
            tasks: ['copy:reviews_js'],
            options: {
                spawn: false
            }
        }
    }
};
