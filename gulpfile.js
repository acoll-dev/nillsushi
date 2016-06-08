var gulp = require('gulp'),
    stylus = require('gulp-stylus'),
    coffee = require('gulp-coffee'),
    sourcemaps = require('gulp-sourcemaps'),
    rename = require('gulp-rename'),
    bootstrap = require('bootstrap-styl'),
    gutil = require('gulp-util'),
    ngAnnotate = require('gulp-ng-annotate');


/* Compilers */

gulp.task('compile-stylus', function(){
    gulp.src(['**/stylus/source/**/*.styl', '!**/node_modules/**', '!**/vendor/**', '!**/bootstrap.styl'])
        .pipe(sourcemaps.init())
        .pipe(stylus({
            compress: false
        }))
        .pipe(rename(function(path){
            path.dirname = path.dirname.replace('stylus/source', '') + '/css';
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./'));
});

gulp.task('compile-bootstrap-stylus', function(){
    gulp.src(['**/bootstrap.styl'])
        .pipe(sourcemaps.init())
        .pipe(stylus({
            compress: true,
            use: [bootstrap()]
        }))
        .pipe(rename(function(path){
            path.dirname = path.dirname.replace('stylus/source', '') + '/css';
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./'));
});

gulp.task('compile-coffee', function(){
    gulp.src(['**/coffee/**/*.coffee', '!**/node_modules/**', '!**/vendor/**'])
        .pipe(sourcemaps.init())
        .pipe(coffee()).on('error', gutil.log)
        .pipe(rename(function(path){
            path.dirname = path.dirname.replace('/coffee', '/js');
        }))
        .pipe(sourcemaps.write())
        .pipe(ngAnnotate())
        .pipe(gulp.dest('./'));
});


/* Watchers */

gulp.task('watch-stylus', function(){
    gulp.watch(['**/*.styl', '!**/bootstrap.styl'], ['compile-stylus']);
});

gulp.task('watch-bootstrap-stylus', function(){
    gulp.watch(['**/bootstrap.styl'], ['compile-bootstrap-stylus']);
});

gulp.task('watch-coffee', function(){
    gulp.watch(['**/*.coffee'], ['compile-coffee']);
});


/* Output Tasks */

gulp.task('default', ['watch-stylus', 'watch-coffee']);

gulp.task('stylus', ['watch-stylus']);

gulp.task('bootstrap', ['watch-bootstrap-stylus']);

gulp.task('coffee', ['watch-coffee']);
