var gulp = require('gulp');
var concat = require('gulp-concat');
var bower = require('gulp-bower');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');
var strip = require('gulp-strip-comments');

var src = {
    js: [
        'resources/assets/js/src/Namespace.js',
        'resources/assets/js/src/!(Namespace|Bootstrap|BoardDragAndDrop)*.js',
        'resources/assets/js/src/BoardDragAndDrop.js',
        'resources/assets/js/components/*.js',
        'resources/assets/js/src/Bootstrap.js'
    ]
};

var vendor = {
    css: [
        'vendor/bower_components/jquery-ui/themes/base/jquery-ui.min.css',
        'vendor/bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.css',
        'vendor/bower_components/chosen/chosen.css',
        'vendor/bower_components/select2/dist/css/select2.min.css',
        'vendor/bower_components/fullcalendar/dist/fullcalendar.min.css',
        'vendor/bower_components/font-awesome/css/font-awesome.min.css',
        'vendor/bower_components/c3/c3.min.css'
    ],
    bootstrap: [
        'vendor/bower_components/jquery/dist/jquery.min.js',
        'vendor/bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js',
        'vendor/bower_components/jquery-ui/jquery-ui.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/core.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/autocomplete.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/datepicker.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/draggable.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/droppable.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/resizable.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/sortable.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/tooltip.min.js',
        'vendor/bower_components/jquery-ui/ui/minified/i18n/datepicker-*.min.js',
        'vendor/bower_components/jqueryui-timepicker-addon/dist/jquery-ui-timepicker-addon.min.js',
        'vendor/bower_components/jqueryui-timepicker-addon/dist/i18n/jquery-ui-timepicker-addon-i18n.min.js',
        'vendor/bower_components/jqueryui-touch-punch/jquery.ui.touch-punch.min.js',
    ],
    base: [
        'vendor/bower_components/moment/min/moment-with-locales.min.js',
        'vendor/bower_components/fullcalendar/dist/fullcalendar.min.js',
    ],
    extra: [
        'vendor/bower_components/chosen/chosen.jquery.js',
        'vendor/bower_components/select2/dist/js/select2.min.js',
        'vendor/bower_components/fullcalendar/dist/lang-all.js',
        'vendor/bower_components/mousetrap/mousetrap.min.js',
        'vendor/bower_components/mousetrap/plugins/global-bind/mousetrap-global-bind.min.js',
        'vendor/bower_components/d3/d3.min.js',
        'vendor/bower_components/c3/c3.min.js',
        'vendor/bower_components/isMobile/isMobile.min.js',
        'vendor/bower_components/marked/marked.min.js'
    ]
};

var dist = {
    fonts: 'public/assets/fonts/',
    css: 'public/assets/css/',
    js: 'public/assets/js/',
    img: 'public/assets/img/'
};

gulp.task('bower', function() {
    return bower();
});

gulp.task('vendor', function() {

    gulp.src(vendor.bootstrap)
        .pipe(concat('bootstrap.min.js'))
        .pipe(gulp.dest(dist.js))
    ;

    gulp.src('node_modules/vue/dist/vue.min.js')
        .pipe(strip({trim: true}))
        .pipe(gulp.dest('node_modules/vue/dist/'))
    ;

    vendor.base.push('node_modules/vue/dist/vue.min.js');

    gulp.src(vendor.base)
        .pipe(concat('base.min.js'))
        .pipe(gulp.dest(dist.js))
    ;

    gulp.src(vendor.extra)
        .pipe(concat('extra.min.js'))
        .pipe(gulp.dest(dist.js))
    ;

    gulp.src(vendor.css)
        .pipe(concat('vendor.min.css'))
        .pipe(gulp.dest(dist.css))
    ;

    gulp.src('vendor/bower_components/font-awesome/fonts/*')
        .pipe(gulp.dest(dist.fonts));

    gulp.src('vendor/bower_components/jquery-ui/themes/base/images/*')
        .pipe(gulp.dest(dist.css + 'images/'));

    gulp.src('vendor/bower_components/chosen/*.png')
        .pipe(gulp.dest(dist.css + ''));
});

gulp.task('js', function() {
    gulp.src(src.js)
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dist.js))
    ;
});

gulp.task('css', function() {
    gulp.src('resources/assets/sass/*.sass')
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(concat('app.min.css'))
        .pipe(gulp.dest(dist.css));
});

gulp.task('default', ['bower', 'vendor', 'js', 'css']);
