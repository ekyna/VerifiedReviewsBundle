module.exports = function (grunt, options) {
    return {
        reviews_less: { // For watch:reviews_less
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/tmp/css',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/css'
                }
            ]
        },
        reviews_js: { // For watch:reviews_js
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/private/js',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/js'
                }
            ]
        }
    }
};
