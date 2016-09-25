"use strict"
	var gulp   = require('gulp'),
	concatCss  = require('gulp-concat-css'),
	concat     = require('gulp-concat'),
	notify     = require('gulp-notify'),
	compass    = require('gulp-compass'),
	sourcemaps = require('gulp-sourcemaps'),
	minifyCss  = require('gulp-minify-css'),
	uglify     = require('gulp-uglifyjs'),
	svgSprite  = require("gulp-svg-sprites");


// css:build
gulp.task('css:build', function()
{
	return gulp.src('public/scss/*.scss')
		.pipe(sourcemaps.init())
		.pipe(compass({
			css: './public/css',
			sass: './public/scss',
			image: './public/img',
			style:'expanded',
			comments:true
		}))
		// .pipe(minifyCss())
		// .pipe(sourcemaps.write())
		.pipe(gulp.dest('public/css'))
		.pipe(notify('Done!'));
});

gulp.task('svg:build', function () {
    return gulp.src('public/img/svg/*.svg')
        .pipe(svgSprite())
        .pipe(gulp.dest("public/img/"));
});

// uglifyjs
gulp.task('js:build', function() {
    return gulp.src([ // Берем все необходимые библиотеки
	        'public/js/jquery.js',
	        'public/js/init.js',
	        'public/js/modules/*.js'
        ])
        .pipe(concat('public/js/init.min.js')) // Собираем их в кучу в новом файле libs.min.js
        .pipe(uglify()) // Сжимаем JS файл
        .pipe(gulp.dest('./')); // Выгружаем в папку app/js
});


// watch
gulp.task('watch', function()
{
	gulp.watch([
       	'public/scss/*.scss',
       	'public/scss/base/*.scss',
       	'public/scss/components/*.scss',
       	'public/scss/helpers/*.scss',
       	'public/scss/layout/*.scss',
       	'scss/pages/*.scss',
       	'scss/vendors/*.scss'],
   		['css:build']
   	);
	// watch js
   	gulp.watch([
        'public/js/init.js',
        'public/js/modules/*.js'],
   		['js:build']
   	);
});
gulp.task('default', ['css:build','svg:build', 'watch']);
