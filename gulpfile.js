
var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var maps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var server = require('browser-sync').create();

function reload(done) {
  server.reload();
  done();
}

function serve(done) {
  server.init({
    server: {
      baseDir: 'app'
    }
  });
  done();
}

// compile sass to css
function css() {
  return gulp.src('assets/scss/style.scss')
  .pipe(maps.init())
  .pipe(sass())
  .pipe(maps.write('./'))
  .pipe(gulp.dest('public/dist/css'))
  .pipe(server.stream());
}

// concatenate js files
function scripts() {
  return gulp.src([
      'node_modules/jquery/dist/jquery.js',
      'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
      'node_modules/owl.carousel/dist/owl.carousel.js',
      'node_modules/magnific-popup/dist/jquery.magnific-popup.min.js',
      'node_modules/swiper/dist/js/swiper.js',
      'node_modules/masonry-layout/dist/masonry.pkgd.js',
      'node_modules/sticky-kit/dist/sticky-kit.js',
      'node_modules/headroom.js/dist/headroom.js',
      'node_modules/headroom.js/dist/jQuery.headroom.js',
      'node_modules/skrollr/dist/skrollr.min.js',
      'node_modules/smooth-scroll/dist/smooth-scroll.js',
      'node_modules/lavalamp/js/jquery.lavalamp.min.js',
      'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
      'node_modules/clipboard/dist/clipboard.min.js',
      'assets/js/modernizr.js',
      'assets/js/app.js'
    ])
    .pipe(maps.init())
    .pipe(concat('app.js'))
    .pipe(maps.write('./'))
    .pipe(gulp.dest('public/dist/js'));
}


// concatenate css files
function styles() {
  return gulp.src([
      'node_modules/swiper/dist/css/swiper.css',
      'node_modules/owl.carousel/dist/assets/owl.carousel.css',
      'node_modules/magnific-popup/dist/magnific-popup.css',
      'node_modules/bootstrap-select/dist/css/bootstrap-select.css',
    ])
    .pipe(maps.init({loadMaps: true}))
    .pipe(concat("vendor.css"))
    .pipe(maps.write())
    .pipe(gulp.dest('public/dist/css'));
}

// move fonts
function fonts() {
    return gulp.src('assets/fonts/*')
        .pipe(gulp.dest('public/dist/fonts'));
}

// move images
function images() {
    return gulp.src('assets/images/**/*')
        .pipe(gulp.dest('public/dist/images'));
}

// minify js
function minify() {
  return gulp.src('public/dist/js/app.js')
  .pipe(maps.init())
  .pipe(uglify())
  .pipe(rename('app.min.js'))
  .pipe(maps.write('./'))
  .pipe(gulp.dest('public/dist/js'));
}

// watch for changes
function watch() {
    gulp.watch('assets/scss/**/*', css);
    gulp.watch(['assets/js/*'], reload);
    gulp.watch('gulpfile.js', gulp.series(scripts, styles, minify));
}

const build = gulp.series(css, scripts, styles, fonts, images, minify);

// tasks
exports.css = css;
exports.scripts = scripts;
exports.styles = styles;
exports.images = images;
exports.fonts = fonts;
exports.minify = minify;
exports.watch = watch;

exports.default = build;