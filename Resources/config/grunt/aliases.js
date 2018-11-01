module.exports = {
    'build:reviews_css': [
        'less:reviews',
        'cssmin:reviews_less',
        'clean:reviews_less'
    ],
    'build:reviews_js': [
        'uglify:reviews_js',
        'twig:reviews'
    ],
    'build:reviews': [
        'clean:reviews_pre',
        'build:reviews_css',
        'build:reviews_js',
        'imagemin:reviews',
        'clean:reviews_post'
    ]
};
