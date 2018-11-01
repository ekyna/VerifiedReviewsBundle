module.exports = function (grunt, options) {
    return {
        reviews_js: {
            files: [{
                expand: true,
                cwd: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/private/js',
                src: ['*.js', '**/*.js'],
                dest: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/js'
            }]
        }
    }
};
