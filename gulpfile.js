"use strict"
var gulp 	   = require('gulp'),
	concatCss  = require('gulp-concat-css'),
	notify     = require('gulp-notify'),
	compass    = require('gulp-compass'),
	sourcemaps = require('gulp-sourcemaps'),
	minifyCss  = require('gulp-minify-css'),
	svgSprite = require("gulp-svg-sprites");


// css:build
gulp.task('css:build', function()
{
	return gulp.src('public/scss/*.scss')
		.pipe(sourcemaps.init())
		.pipe(compass({
			css: './public/css',
			sass: './public/scss',
			image: './public/img'
		}))
		.pipe(minifyCss())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('public/css'))
		.pipe(notify('Done!'));
});

gulp.task('svg:build', function () {
    return gulp.src('public/img/svg/*.svg')
        .pipe(svgSprite())
        .pipe(gulp.dest("public/img/"));
});

// watch
gulp.task('watch', function()
{
	gulp.watch(['public/scss/*.scss'],['css:build']);
});
gulp.task('default', ['css:build','svg:build', 'watch']);
