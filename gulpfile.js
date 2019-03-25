'use strict'

const gulp = require('gulp');
const sass = require('gulp-sass');
const del = require('del');
const csso = require('gulp-csso');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const webpack = require('webpack-stream');

gulp.paths = {
    src: 'resources/lcss',
    dist: 'public/admin',
};

var paths = gulp.paths;

gulp.task('sass', function () {
    return gulp.src(paths.src+'/main.scss')
        .pipe(sass())
        .pipe(csso({
            restructure: true,
            sourceMap: false,
            debug: false
        }))
        .pipe(gulp.dest(paths.dist+'/css'));
});

gulp.task('clean:dist', function () {
    return del([paths.dist+'/webfonts', paths.dist+'/fonts', paths.dist+'/css', paths.dist+'/js']);
});



gulp.task('copy:webfonts-fa4', function() {
   return gulp.src(paths.src+'/font-awesome-4.7.0/fonts/**/*')
   .pipe(gulp.dest(paths.dist+'/fonts'));
});
gulp.task('copy:webfonts-fa4css', function() {
   return gulp.src(paths.src+'/font-awesome-4.7.0/css/font-awesome.min.css')
   .pipe(gulp.dest(paths.dist+'/css'));
});

gulp.task('copy:webfonts-fa5', function() {
   return gulp.src('./node_modules/@fortawesome/fontawesome-free/webfonts/**/*')
   .pipe(gulp.dest(paths.dist+'/webfonts'));
});
gulp.task('copy:webfonts-fa5css', function() {
   return gulp.src('./node_modules/@fortawesome/fontawesome-free/css/fontawesome.min.css')
   .pipe(gulp.dest(paths.dist+'/css'));
});

gulp.task('copy:webfonts', gulp.parallel('copy:webfonts-fa4', 'copy:webfonts-fa4css'));
//gulp.task('copy:webfonts', ['copy:webfonts-fa5', 'copy:webfonts-fa5css']);

gulp.task('copy:css', function() {
   return gulp.src(paths.src+'/css/**/*')
   .pipe(gulp.dest(paths.dist+'/css'));
});

gulp.task('scripts', function() {
  return gulp.src([
        //'./node_modules/jquery/dist/jquery.min.js', 
        paths.src+'/js/common.js'
    ])
    .pipe(concat('common.js'))
    .pipe(uglify())
    .pipe(gulp.dest(paths.dist+'/js'));
});


gulp.task('copy:js', function() {
  return gulp.src([
        './node_modules/bootstrap/js/dist/dropdown.js',
        './node_modules/bootstrap/js/dist/tooltip.js',
    ])
    .pipe(webpack({
        mode: 'production',
        externals: {
          jquery: 'jQuery'
        },
        output: {
          filename: 'bundle.js',
        },
    }))
    .pipe(gulp.dest(paths.dist+'/js'));
});

gulp.task('build', 
    gulp.series('clean:dist', gulp.parallel('sass', 'copy:webfonts', 'copy:js', 'scripts', 'copy:css'))
);

gulp.task('watch', function(callback) {
    gulp.watch(paths.src+'/**/*.*', gulp.parallel('build'));
    // gulp.watch(paths.src+'/**/*.scss', function(callback) {
        // runSequence('sass', 'postcss', callback);
    // });
});

gulp.task('default', gulp.parallel('watch'));
