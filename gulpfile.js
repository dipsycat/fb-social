var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglifyjs');
    
gulp.task('mytask', function () {
    console.log('Привет, я таск!');
});

gulp.task('js', function() {
    return gulp.src([
        'src/Dipsycat/FbSocialBundle/Resources/private/**/*.js'
    ])
            .pipe(concat('fbsocial.min.js'))
            .pipe(uglify())
            .pipe(gulp.dest('src/Dipsycat/FbSocialBundle/Resources/public/js'));   
});