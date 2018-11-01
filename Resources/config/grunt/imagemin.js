module.exports = function (grunt, options) {
    return {
        reviews: {
            options: {
                optimizationLevel: 6
            },
            files: [{
                expand: true,
                cwd: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/private/img/',
                src: ['**/*.{png,jpg,gif,svg}'],
                dest: 'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/img/'
            }]
        }
    }
};
