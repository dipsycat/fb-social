var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglifyjs'),
    cleanCSS = require('gulp-clean-css'),
    concatCss = require('gulp-concat-css');

gulp.task('app-css', function () {
    return gulp.src([
        'src/Dipsycat/AppBundle/Resources/private/bootstrap/dist/css/bootstrap.min.css',
        'src/Dipsycat/AppBundle/Resources/private/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
        'src/Dipsycat/AppBundle/Resources/private/metisMenu/dist/metisMenu.min.css'
    ])
        .pipe(concatCss('app.min.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('src/Dipsycat/AppBundle/Resources/public/css'));
});

gulp.task('fbsocial-css', function () {
    return gulp.src([
        'src/Dipsycat/FbSocialBundle/Resources/private/css/sb-admin-2.css',
        'src/Dipsycat/FbSocialBundle/Resources/private/css/main.css'
    ])
        .pipe(concatCss('fbsocial.min.css'))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('src/Dipsycat/FbSocialBundle/Resources/public/css'));
});

gulp.task('app-js', function () {
    return gulp.src([
        'src/Dipsycat/AppBundle/Resources/private/jquery/dist/jquery.min.js',
        'src/Dipsycat/AppBundle/Resources/private/bootstrap/dist/js/bootstrap.min.js',
        'src/Dipsycat/AppBundle/Resources/private/moment/min/moment.min.js',
        'src/Dipsycat/AppBundle/Resources/private/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
        'src/Dipsycat/AppBundle/Resources/private/metisMenu/dist/metisMenu.min.js',
        'vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.js',
        'src/Dipsycat/FbSocialBundle/Resources/private/**/*.js'
    ])
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('src/Dipsycat/AppBundle/Resources/public/js'));
});

gulp.task('fbsocial-js', function () {
    return gulp.src([
        'src/Dipsycat/FbSocialBundle/Resources/private/**/*.js'
    ])
        .pipe(concat('fbsocial.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('src/Dipsycat/FbSocialBundle/Resources/public/js'));
});

gulp.task('watch', function() {
    gulp.watch('src/**/*.css', ['app-css', 'fbsocial-css']);
    gulp.watch('src/**/*.js', ['app-js', 'fbsocial-js']);
});

gulp.task('build', ['app-css', 'fbsocial-css', 'app-js', 'fbsocial-js']);

gulp.task('clear', function () {
    return cache.clearAll();
});