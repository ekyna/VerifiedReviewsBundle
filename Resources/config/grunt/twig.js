module.exports = function (grunt, options) {
    return {
        reviews: {
            options: {
                amd_wrapper: true,
                amd_define: 'ekyna-verified-reviews/templates',
                variable: 'templates',
                template_key: function(path) {
                    var split = path.split('/');
                    return split[split.length-1];
                }
            },
            files: {
                'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/public/js/templates.js': [
                    'src/Ekyna/Bundle/VerifiedReviewsBundle/Resources/views/Js/reviews.html.twig'
                ]
            }
        }
    }
};
