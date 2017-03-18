"use strict"
	var gulp      = require('gulp'),
	concatCss     = require('gulp-concat-css'),
	concat        = require('gulp-concat'),
	notify        = require('gulp-notify'),
	sass          = require('gulp-sass'),
	sourcemaps    = require('gulp-sourcemaps'),
	minifyCss     = require('gulp-minify-css'),
	autoprefixer  = require('gulp-autoprefixer'),
	uglify        = require('gulp-uglifyjs');
	//	svgSprite = require("gulp-svg-sprites");


// css:build

gulp.task('css:build', function() {
	return gulp.src('public/scss/*.scss')
	.pipe(sass().on('error', sass.logError))
	.pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8', 'ie 7'], {cascade: true}))
	.pipe(gulp.dest('public/css'));
	// .pipe(browserSync.reload({stream: true}))
});

gulp.task('svg:build', function () {
    return gulp.src('public/img/svg/*.svg')
        .pipe(svgSprite())
        .pipe(gulp.dest("public/img/"));
});

// uglifyjs
gulp.task('js:build', function() {
    // Берем все необходимые библиотеки
    return gulp.src([
	        'public/js/jquery.js',
	        'public/js/init.js',
	        'public/js/modules/*.js'
        ])
        .pipe(concat('public/js/init.min.js'))  // Собираем их в кучу в новом файле libs.min.js
        .pipe(uglify()) 						// Сжимаем JS файл
        .pipe(gulp.dest('./')); 				// Выгружаем в папку app/js
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
gulp.task('default', ['css:build', 'watch']);
