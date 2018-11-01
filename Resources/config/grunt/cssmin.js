module.exports = function (grunt, options) {
    return {
        reviews_less: {
            files: [
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/tmp/css',
                    src: ['*.css'],
                    dest: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/css',
                    ext: '.css'
                }
            ]
        }
    }
};
